// PHOTO GALLERY
var {$tplvar['list_num']}_photoDefault = $('#{$tplvar['list_num']} .imagebig a img').attr('src');

$('#{$tplvar['list_num']} .gallerysmallimage a img').mouseover(function(){
  var this_photo = $(this).attr('src');
  $('#{$tplvar['list_num']} .imagebig a img').attr('src',this_photo);
});
$('#{$tplvar['list_num']} .gallerysmallimage a img').live('click',function(){
  var this_photo = $(this).attr('src');
 {$tplvar['list_num']}_photoDefault = this_photo
});
$('#{$tplvar['list_num']} .gallerysmallimage a img').mouseout(function(){
  $('#{$tplvar['list_num']} .imagebig a img').attr('src',{$tplvar['list_num']}_photoDefault);
}).delay(1000);
    
//ADD / REMOVE PRIMARY AGENT
$('.{$tplvar['list_num']}primary').click(function(){
  var agent = $(this).attr('data-agent');
  var userEmail = '{$tplvar['userEmail']}';
  $.get("/controllers/ajax.php", { 
    agentCode: agent, 
    usersEmail: userEmail, 
    makePrimary: 'true',
    success: function(result){
      setTimeout(function(){
        var current_index = $("#listings").tabs("option","selected"); 
        $("#listings").tabs('load',current_index);
      }, 500); 
    }
  });
}); 

// CONTACT FORM FOR BOTH AGENTS
$( "#{$tplvar['list_num']}contact-form" ).dialog({
  autoOpen: false,
  height: 'auto',
  width: 555,
  modal: true,
  dialogClass: 'emailForm',
  draggable:false,
  resizable:false,
  close: function() { 
    $('.ui-dialog').dialog('close');
  },                
  open: function(){
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}contact-form").dialog('close');
    });
  }
});

$('.ui-dialogue-custom-closer').click(function (){
    $('.ui-dialogue-custom-content').dialog('close');
});

$('.ui-dialogue-email-send').click(function (){
  var comments = $('.ui-dialogue-custom-content').find('#contactComments').val();
  var guestName = $('.ui-dialogue-custom-content').find('#guestName').val();
  var guestEmail = $('.ui-dialogue-custom-content').find('#guestEmail').val();
  
  
  if ( ($('.ui-dialogue-custom-content').find('#guestName').length > 0 && guestName == "") || ($('.ui-dialogue-custom-content').find('#guestEmail').length > 0 && guestEmail == "") || ( ($('.ui-dialogue-custom-content').find("#friendSend:checkbox:checked").length > 0 && $('.ui-dialogue-custom-content').find('#friendEmail').val() == "" ) ) ) {	  
    $("#ajax-box").dialog({
      modal: true,
      height: 'auto',
      width: 'auto',
      autoOpen: false,
      dialogClass: 'ajaxbox errorMessage',
      buttons : {
        Ok: function(){
          $(this).dialog("close");
        }
      },                
      open: function(){
        $(".ui-widget-overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
        });
      }
    });
    $('#ajax-box').load('/controllers/messages.php #registerRquirements',function(){
      $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
    });
  }
  else{

    if ($('.ui-dialogue-custom-content').find("#friendSend:checkbox:checked").length > 0) {
      var friendEmail = $('.ui-dialogue-custom-content').find('#friendEmail').val();
      $.get("/controllers/ajax.php", { list_num: '{$tplvar['list_num']}', sendTo: friendEmail, comments: comments, guestName: guestName, emailListing: 'true'}, function(result) { console.log("Success!"); });
    }  
    if ($('.ui-dialogue-custom-content').find("#agent1Send:checkbox:checked").length > 0) {
      $.get("/controllers/ajax.php", { list_num: '{$tplvar['list_num']}', manager: '{$tplvar['agent_email']}', contact: 'true', comments: comments, guestName: guestName, guestEmail: guestEmail });
    }  
    if ($('.ui-dialogue-custom-content').find("#agent2Send:checkbox:checked").length > 0) {
      $.get("/controllers/ajax.php", { list_num: '{$tplvar['list_num']}', manager: '{$tplvar['agent2_email']}', contact: 'true', comments: comments, guestName: guestName, guestEmail: guestEmail });
    }
  
    $('.ui-dialogue-custom-content').dialog('close');
  }
});

$( "#{$tplvar['list_num']}agentSave" ).dialog({
  autoOpen: false,
  height: 400,
  width: 555,
  modal: true,
  dialogClass: 'green',
  buttons: {
    "Save and Email": function() {
      var saveBuyer = $('#{$tplvar['list_num']}agentSave #buyerSave').val();	
      var agentComments = $('#{$tplvar['list_num']}agentComments').val();	
			var buyerName = $('#{$tplvar['list_num']}agentSave #buyerSave').text();
      $.get("/controllers/ajax.php", { 
        list_num: '{$tplvar['list_num']}', 
        comments: agentComments, 
        saveAndEmail: 'true', 
        buyer: saveBuyer
      }, function(result) { 
        console.log("Success!"); 
      }); 
      $('.tab-save.{$tplvar['list_num']}save-listing').text('saved');
      $('.tab-save.{$tplvar['list_num']}save-listing').addClass("tab-saved");
      $('.tab-save.{$tplvar['list_num']}save-listing').removeClass("tab-save");
      // refresh tab to show Saved To data
      var current_index = $("#listings").tabs("option","selected"); 
      $("#listings").tabs('load',current_index);
      $( this ).dialog( "close" );
    },
    "Cancel": function() {
      $( this ).dialog( "close" );
    }					
  },
  open: function(){
    $('.btnicon').remove();
    $('.ui-dialog-buttonpane').find('button:contains("Save")').first().prepend("<img class='btnicon' style='opacity:0.8;' src='/images/folderd.png'>");
    $('.ui-dialog-buttonpane').find('button:contains("Save and Email")').prepend("<img class='btnicon' src='/images/folderg.png'>");
    				
    $.ajax({
      type: "POST",
      url: "http://homepik.com/controllers/buyer-save.php",
      success: function(data){
        var buyer = JSON.parse(data);
        $("#buyerSave").val(buyer);
      },
      error: function(){
        console.log("failed");
      }
    });
    
    if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
      $('.ui-dialog').css("left", "35%")
    }
    
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}agentSave").dialog('close');
    });
  },
  close: function() { 
    $('.ui-dialog').dialog('close');
  }
});		
    
$( "#{$tplvar['list_num']}buyerSave" ).dialog({
  autoOpen: false,
  height: 'auto',
  width: 320,
  modal: true,
  dialogClass: 'green',
  buttons: {
    "Save": function() {
      var listNumber = '{$tplvar['list_num']}';
      var buyerComments = $('#{$tplvar['list_num']}buyerComments').val();
      var agent = $(this).find("#agent").val();
      $.get("/controllers/ajax.php",{ 
        list_num: '{$tplvar['list_num']}', 
        comments: buyerComments, 
        agent:agent, 
        save: 'true'
      }, function(result) {
          console.log("Success!"); 
      }); 
      $('.tab-save.{$tplvar['list_num']}save-listing').attr('data-text','saved');
      $('.tab-save.{$tplvar['list_num']}save-listing').addClass("tab-saved");
      $('.tab-save.{$tplvar['list_num']}save-listing span').addClass("tab-saved");
      $('.tab-save.{$tplvar['list_num']}save-listing').removeClass("tab-save");
      // refresh tab to show Saved To data
      var current_index = $("#listings").tabs("option","selected"); 
      $("#listings").tabs('load',current_index);
      $(".heart-list-{$tplvar['list_num']}").addClass('color-blue');
      $(".heart-tabInner-{$tplvar['list_num']}").addClass('color-blue');
      $( this ).dialog( "close" );
    },
    "Cancel": function() {
      $( this ).dialog( "close" );
    }
  },
  open: function(){
    // Add the folder icon to the button
    $('.btnicon').remove();
    $('.ui-dialog-buttonpane').find('button:contains("Save")').first().prepend("<img class='btnicon' style='opacity:0.8;' src='/images/folderd.png'>");
    $('.ui-dialog-buttonpane').find('button:contains("Save and Email")').prepend("<img class='btnicon' src='/images/folderg.png'>");
    // Preselect the agent's current active buyer
    $('#buyerSave').val(buyer.email);
    
    if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
      $('.ui-dialog').css("left", "35%")
    }
    
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}buyerSave").dialog('close');
    });
  },
  close: function() { 
    $('.ui-dialog').dialog('close');
  }
});

$( "#{$tplvar['list_num']}brokerDetails" ).dialog({
  autoOpen: false,
  height: 350,
  width: 500,
  modal: true,
  dialogClass: "brokerDetailsPopup",
  buttons: {
    close: function() { $( this ).dialog( "close" ); }
  },                
  open: function(){
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}brokerDetails").dialog('close');
    });
  }
});
    
$('.{$tplvar['list_num']}brokerDetails').click(function(){
  $( "#{$tplvar['list_num']}brokerDetails" ).dialog( "open" );
  $( "#{$tplvar['list_num']}brokerDetails" ).parent().attr('rel', "blue");
  if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
    $('.ui-dialog').css("left", "35%")
  }
  return false;
});

$('.ui-dialogue-broker-details-closer').click(function (){
  $("#{$tplvar['list_num']}brokerDetails").dialog('close');
});

$('.{$tplvar['list_num']}contact').click(function(){
  $( "#{$tplvar['list_num']}contact-form" ).dialog( "open" );
  $( "#{$tplvar['list_num']}contact-form" ).parent().attr('rel', "blue");
  $( "#{$tplvar['list_num']}contact-form .cont_f_n_p input" ).parent().find("input:first-child").attr("checked", false);
  $( "#{$tplvar['list_num']}contact-form .cont_f_n_p input[value='"+$(this).find("button").attr("id")+"']" ).parent().find("input:first-child").attr("checked", true);
  $( "#{$tplvar['list_num']}contact-form" ).find('#contactComments').val("Please send me information about this property.");
  if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
    $('.ui-dialog').css("left", "35%")
  }
  return false;
});

$('#{$tplvar['list_num']}emailListing').click(function(){
  $( "#{$tplvar['list_num']}contact-form" ).dialog( "open" );
  $( "#{$tplvar['list_num']}contact-form" ).parent().attr('rel', "blue");
  $( "#{$tplvar['list_num']}contact-form .cont_f_n_p input" ).attr("checked", false);
  $( "#{$tplvar['list_num']}contact-form .cont_f_n_p #friendSend" ).attr("checked", true);
  $( "#{$tplvar['list_num']}contact-form" ).find('#contactComments').val("");
  if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
    $('.ui-dialog').css("left", "35%")
  }
  return false;
});
     
/* MY PROFILE DIALOG FOR FIRST AGENT */
$( "#{$tplvar['list_num']}{$tplvar['agent_id_1']}agentDialog" ).dialog({
  autoOpen: false,
  height: 'auto',
  width: 500,
  modal: true,
  draggable:false,
  resizable:false,
  dialogClass: "ui-dialogue-agent-bio",
  create: function(e, ui) {
    $(this).dialog('widget').removeClass('ui-corner-all');
  },
  close: function() { 
    $('.ui-dialog').dialog('close');
  },                
  open: function(){
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}{$tplvar['agent_id_1']}agentDialog").dialog('close');
    });
  }
});

$( "#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog" ).dialog({
  autoOpen: false,
  height: 'auto',
  width: 500,
  modal: true,
  draggable:false,
  resizable:false,
  dialogClass: "ui-dialogue-agent-bio",
  create: function(e, ui) {
    $(this).dialog('widget').removeClass('ui-corner-all');
  },
  close: function() { 
    $('.ui-dialog').dialog('close');
  },                
  open: function(){
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog").dialog('close');
    });
  }
});

/* MY PROFILE DIALOG FOR SECOND AGENT */
$( "#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog2" ).dialog({  
  autoOpen: false,
  height: 'auto',
  width: 500,
  modal: true,
  draggable:false,
  resizable:false,
  dialogClass: "ui-dialogue-agent-bio",
  create: function(e, ui) {
    $(this).dialog('widget').removeClass('ui-corner-all');
  },
  close: function() { 
    $('.ui-dialog').dialog('close');
  },                
  open: function(){
    $(".ui-widget-overlay").bind("click", function(){
      $("#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog2").dialog('close');
    });
  }
});
    
$( "#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentBio" ).click(function() {
  $( "#{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog2" ).dialog( "open" );
  $( "#{$tplvar['list_num']}agentDialog2" ).parent().attr('rel', "blue");
  
  if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
    $('.ui-dialog').css("left", "35%")
  }
  return false;
});

$('.ui-dialogue-agent-bio-closer').click(function (){
  $('.ui-dialogue-agent-bio-content').dialog('close');
});

$( "#{$tplvar['list_num']}{$tplvar['agent_id_1']}agentBio" ).click(function() {
  $( "#{$tplvar['list_num']}{$tplvar['agent_id_1']}agentDialog" ).dialog( "open" );
  $( "#{$tplvar['list_num']}agentDialog" ).parent().attr('rel', "blue");
  
  if((navigator.userAgent.match(/iPad/i) != null) == true && ((Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180))){
    $('.ui-dialog').css("left", "35%")
  }
  return false;
});

/* COST ESTIMATOR POPUP */
$( "#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']}" ).dialog({ 
    autoOpen: false,
    modal:true,
    dialogClass: 'costEstimatorPopup',
    draggable:false,
    resizable:false,
    width: 600,
    create: function(e, ui) {
      $(this).dialog('widget')
      .addClass('ui-dialogue-cost-estimator-popup-container');
    },
    open: function(){
      $(':focus', this).blur();
      calculate();
      $(".ui-widget-overlay").bind("click", function(){
        $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']}").dialog('close');
      });
    }
});
$( "#cost-estimator-pop-up-trigger{$tplvar['list_num']}{$tplvar['agent_id_1']}" ).click(function() {
  $( "#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']}" ).dialog( "open" );
});
$('.ui-dialogue-cost-estimator-popup-closer').click(function (){
    $('.ui-dialogue-cost-estimator-popup').dialog('close');
});

$("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} input").bind('input',function(){
  calculate();
});

function calculate(){
  var price = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #bid").val();
  var monthly = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #monthly_charge").val();
  var taxes = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #monthly_tax").val();
  var interest = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #interest_rate").val();
  var term = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #length_of_term").val();
  var payment = $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #payment_percent").val();
    
  price = price.replace(/,/g,'');
  price = price.replace(/ /g,"");
  monthly = monthly.replace(/,/g,'');
  monthly = monthly.replace(/ /g,"");
  taxes = taxes.replace(/,/g,'');
  taxes = taxes.replace(/ /g,"");
  interest = interest.replace(/,/g,'');
  interest = interest.replace(/ /g,"");
  term = term.replace(/,/g,'');
  term = term.replace(/ /g,"");
  payment = payment.replace(/,/g,'');
  payment = payment.replace(/ /g,"");
    
  // Price * ( Cash Down Percentage / 100 )
  var cashDown = (price * (payment / 100));
  cashDown = cashDown.toFixed(2);
  var cd = formatNumber(cashDown);
  //var cd = cashDown.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #cashDownPayment").text("$" + cd);
  
  // ( ( (Price + Interest) - Cash Down Payment ) / Years ) + Monthly + Tax
  var total = (((parseInt(price) + (price * (interest /100))) - cashDown ) / (term * 12)) + parseInt(monthly) + parseInt(taxes);
  total = total.toFixed(2);
  total = formatNumber(total);
  $("#ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']} #monthlyCost").text("$" + total);
};

function formatNumber(number) {
  number = number.toString();
  var num = number.substring(0, number.indexOf('.'));
  var decimal = number.substring(number.indexOf('.'));
  var sections = [];
  var index = 0;
  var formattedNum = "";
  while(num.length != 0){
    sections[index] = num.slice(-3);
    num = num.substring(0, num.length - 3);
    index++;
  }
  
  for(var i = 0; i < index; i++){
    if( (i+1) < index) { formattedNum = "," + sections[i] + formattedNum; }
    else{ formattedNum = sections[i] + formattedNum; }    
  }
  
  return formattedNum + decimal;
}
/*cost estimator popup ends*/
    
var numImages = 0;
var index = 1;
    
$(".image").click(function(){
  $("#{$tplvar['list_num']}slideshow").find(".active").removeClass("active").addClass("nonactive");
  var id = $(this).attr("id");
  index = id[5];
  numImages = $("#{$tplvar['list_num']}slideshow").find(".slideshowImg").length;
  $("#{$tplvar['list_num']}slideshow").find("#"+id).removeClass("nonactive").addClass("active");
  $("#{$tplvar['list_num']}slideshowArea").show();
  $(window).scrollTop(240);
  $("#{$tplvar['list_num']}overlay").show();
});
    
$(".{$tplvar['list_num']}close").click(function(){
  $("#{$tplvar['list_num']}slideshow").find(".active").removeClass("active").addClass("nonactive");
  $("#{$tplvar['list_num']}slideshowArea").hide();
  $("#{$tplvar['list_num']}overlay").hide();
});
    
$("#{$tplvar['list_num']}previous").die('click').live('click',function(){
  previous();
});

$("body").keydown(function(e) {
  if($("#{$tplvar['list_num']}slideshowArea").css('display') != "none") {
    if (e.keyCode == 37) {
      previous();
    }
    else if (e.keyCode == 39) {
      next();
    }
  }
});
    
$("#{$tplvar['list_num']}next").click(function(){
	next();
});
    
function next(){
  var id = $("#{$tplvar['list_num']}slideshow").find(".active").attr("id")

  if(id.indexOf(numImages) > -1){ index = 1; }
  else{ index++; }
      
  $("#{$tplvar['list_num']}slideshow").find(".active").removeClass("active").addClass("nonactive");
  $("#{$tplvar['list_num']}slideshow").find("#image"+index).removeClass("nonactive").addClass("active");
};
    
function previous(){
  var id = $("#{$tplvar['list_num']}slideshow").find(".active").attr("id")

  if(id.indexOf("1") > -1){ index = numImages; }
  else{ index--; }

  $("#{$tplvar['list_num']}slideshow").find(".active").removeClass("active").addClass("nonactive");
  $("#{$tplvar['list_num']}slideshow").find("#image"+index).removeClass("nonactive").addClass("active");
};

$("#main").bind('click',function(){ $('.space-factor-popup').remove(); $('.implied-square-footage-popup').remove(); })
$('.space-factor-popup').click(function(event){ event.stopPropagation(); });
$('.implied-square-footage-popup').click(function(event){ event.stopPropagation(); });

/*
$( "#ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}" ).dialog({ 
  autoOpen: false,
  modal: true,
  dialogClass: 'selectionValuationPopup',
  draggable: false,
  resizable: false,
  width: 850,
  open: function(){
    $(':focus', this).blur();
    calculatePriceRange(10);
    $("input[value='Local']").attr("checked", true);
    $("input[value='Active']").attr("checked", true);
    $("input[name='condition'][value='Level 3']").attr("checked", true);
    $("input[name='demand'][value='Level 2']").attr("checked", true);
    $("input[name='condos_only'][value='no']").attr("checked", true);
    $("input[name='location_grade'][value='{$tplvar['loc']}']").attr("checked", true);
    $("input[name='building_grade'][value='{$tplvar['bld']}']").attr("checked", true);
    $("input[name='view_grade'][value='{$tplvar['vws']}']").attr("checked", true);
    if({$tplvar['amenities']['Fireplace']} == "1"){ $("input[value='fireplace']").attr("checked", true); }
    if({$tplvar['amenities']['Elevator']} == "1"){ $("input[value='elevator']").attr("checked", true); }
    if({$tplvar['amenities']['Balcony']} == "1"){ $("input[value='balcony']").attr("checked", true); }
    else if({$tplvar['amenities']['Terrace']} == "1"){ $("input[value='terrace']").attr("checked", true); }
    else if({$tplvar['amenities']['Garden']} == "1" || {$tplvar['amenities']['Roof Deck']} == "1"){ $("input[value='garden']").attr("checked", true); }
    else{ $("input[value='none']").attr("checked", true); }
    $(".ui-widget-overlay").bind("click", function(){
      $("#ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}{$tplvar['agent_id_1']}").dialog('close');
    });
  }
});
$( "#{$tplvar['list_num']}-spv" ).click(function() {
  $( "#ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}" ).dialog( "open" );
});

$('#{$tplvar['list_num']}closeSpvPopup').click(function (){
  $( "#ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}" ).dialog('close');
});

$('#submit').click(function (){
  $( "#ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}" ).dialog('close');
});

function calculatePriceRange(interest){
  var price = this.state.price;
  while(price.indexOf(",") != -1){ price = price.replace(",",""); }
  price = Number(price);
  
  var minPriceRange = price - (price * (interest / 100));
  minPriceRange = minPriceRange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  $("#minPrice").html(minPriceRange);
  
  var maxPriceRange = price + (price * (interest / 100));
  maxPriceRange = maxPriceRange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  $('#maxPrice').html(maxPriceRange);
};

function calculateDimensions(){
  var ltotal = this.state.lrd_one * this.state.lrd_two;
  $("#lrt").html(ltotal).val(ltotal);
  var mtotal = this.state.mbd_one * this.state.mbd_two;
  $("#mbt").html(mtotal).val(mtotal);
  var dtotal = this.state.drd_one * this.state.drd_two;
  $("#drt").html(dtotal).val(dtotal);
  var bbtotal = this.state.bbd_one * this.state.bbd_two;
  $("#bbt").html(bbtotal).val(bbtotal);
  var bbbtotal = this.state.bbbd_one * this.state.bbbd_two;
  $("#bbbt").html(bbbtotal).val(bbbtotal);
  
  var stotal = ltotal + mtotal + dtotal + bbtotal + bbbtotal;
  $("#total_sqf").html(stotal).val(stotal);
};
*/
/* StreetEasy */
$('#{$tplvar['list_num']}-streeteasy').click(function(){
  var ad = '{$tplvar['address']}';
  while(ad.indexOf(" ") != -1){ ad = ad.replace(" ", "+"); }
  var sea = ad + "+" + {$tplvar['zip']};
  
  window.open('https://www.streeteasy.com/nyc/search?search='+sea,'_blank');
});

/* OLR */
$('#{$tplvar['list_num']}-olr').click(function(){
  var olra = '{$tplvar['address']}';
  while(olra.indexOf(" ") != -1){ olra = olra.replace(" ", "%"); }
  
  window.open('http://public.olr.com/building_results.aspx?address='+olra,'_blank');
});

/* Listing Wep Page */
$('#{$tplvar['list_num']}-broker-website').click(function(){
  console.log("clicked");
});