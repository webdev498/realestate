
var listing = 0;
var fixedTheadTop = 0;
var listingHeight = 0;
var theadTop = 0;
var previousOpenListings = 0;
var buyerEmail = "";
var lastBuyersSavedTo = [];
var formulaName = "";
var lastUserFolderOpen = ""
var lastFolderNameOpen = "";

// This is executed after the search results are loaded.
function loadComplete(data) {
  // Hide the loading message
  var name = $(this).find('.name').text();
  $('#load_list').hide();
  $("#loadingDiv").hide();
  $('#load_list').addClass("scrollLoad");
  $("#list").css("padding-top", "0px");
  $("option[value=100000000]").text('All');
  $('.ui-pg-selbox').closest("td").before("<td dir='ltr'>&nbsp;&nbsp;&nbsp; Number of Rows </td>");

  $(window).resize(function(){
    $('#list').jqGrid('setGridHeight',$(window).innerHeight());
  });

  // Add the disclaimer at the bottom
  $('#footer').remove();
  if($('#footer').length == 0){
    var disclaimer = "";
    if ($('#role').text() == "guest") { disclaimer = "Guest public portal."; }
    else{ disclaimer = "This is not a public portal. For registered buyers only."; }
    $('#pager').after('<div id="footer"><div id="disclaimer">Patents US 8.527.528B2 & US 9.146.528B2. '+disclaimer+' All data is deemed reliable but is not guaranteed accurate by the RLS, HomePik, or Bellmarc Realty. All information regarding properties is from sources deemed reliable. No representation is made as to the accuracy thereof, and such information is subject to errors, omission, change of price, rental, commission, prior sale, lease or financing, or withdrawal without notice. All square footage and dimensions, including room dimensions and space comparisons, are approximate, based on data obtained from third parties, and the accuracy of the data has not been verified by HomePik.com or Bellmarc. Exact dimensions can be obtained by retaining the services of a professional architect or engineer. Grades and comparisons, including for location building, views and space, represent nothing more than the subjective opinion of real estate professionals consulted by HomePik.com based on the available information, and may be inaccurate. For corrections, removal requests or questions about our grading system, contact inquiries@homepik.com. The number of bedrooms listed above is not a legal conclusion. Each person should consult with his/her own attorney, architect or zoning expert to make a determination as to the number of rooms in the unit that may be legally used as a bedroom.</div><a target="_blank" href="tos.php">Terms of Use</a>&nbsp;|&nbsp;<a target="blank" href="privacy.php">Privacy Policy</a></div>');
  }

  var records = $(data).find('records').text();
  $('#jqgh_address').html('<span style="color: #AAAAAA;font-size: 0.8em;font-weight: normal;left: -9.8em;position: relative;text-align: left;top: 0.05em;">'+records+' listings</span>');
  $('#listingsNumbersNearToSearch').html('<span>'+records+' listings</span>');

  var listingNum;
  $("#customMenu #openNewTab").click(function(e){
    window.open('/controllers/single-listing.php?list_numb='+listingNum+'&newTab=T','_blank');
  });

  $('div.open-list-detail').live("contextmenu", function(){
    listingNum = $(this).parent().parent().attr("id");
  });

  // This is the function executed when a user clicks on an item in the search results -- it creates the new tab for that listing. Tabs are initialized in search-react.js (that's where the function is set to switch to a new tab after it's been created)
  $('#list .open-list-detail').click(function(e){ // When the user clicks a listing
    $.cookie('listActive', "yes");
    var active = $.cookie('listActive');
    var self  = $(this).closest('.jqgrow');
    var self_id = self.attr('id');
    var address = self.find('.address').text(), // get the address
    apt = self.find('.address .details')[0].outerHTML,
    aptNum =self.find('.address .details')[0].innerHTML,
    code = self.find('.code').text(); // get the encrypted listing number (the url can't contain the actual listing number because of VOW requirements)
    saved = self.find('.saved').text();
    address = address.slice(0, 14); // truncate the address
    var list_numb = self.attr('id'); // get the actual listing number
    scroll_to_y = window.pageYOffset;

    if (e.ctrlKey){
      window.open('/controllers/single-listing.php?list_numb='+list_numb+'&newTab=T','_blank');
    }
    else{
      $('#listings').removeClass('fixedTop').css('height', 'unset');
      $('#listings .ui-tabs-nav').removeClass('fixedTop');
      $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
      $('.thead-container').css('display', 'none');

      //Maybe set a function here to check if the listing has already been saved?
      if(checkDeuplicateTab(list_numb) == false){
        $('#listings').tabs( "add" , '/controllers/custom.php?list_numb='+ list_numb + '&code=' + code, address + apt);
        if(saved){
          var role = $("#role").text();

          if (role == 'agent') {
            $('#listings').find('li').last().find('.ui-icon-close')
              .before('<div class="tab-save tab-head-div'
                +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-agent-tab-head heart-tabHead-'+list_numb+'"></span></div>'); // add the save button
          }
          else if(role == 'buyer'){
            $('#listings').find('li').last().find('.ui-icon-close')
              .before('<div class="tab-save tab-head-div'
                +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-buyer-tab-head heart-tabHead-'+list_numb+'"></span></div>'); // add the save button
          }
          else{
            $('#listings').find('li').last().find('.ui-icon-close')
              .before('<div class="tab-save tab-head-div'
                +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-guest-tab-head heart-tabHead-'+list_numb+'"></span></div>'); // add the save button
          }
        }

        $('#listings').find('li').last().find('.ui-icon-close')
          .before('<div class="listing-info" data-listing="'+list_numb
            +'" data-apt="'+self.find('.address .details')[0].innerHTML
            +'" data-address="'+address+'" data-save="'+saved
            +'" data-code="'+code+'" style="display:none"></div>');

        if($('#listings').find('.tab-save').attr('data-text') == "saved"){
          $('#listings').find('.tab-save').addClass("tab-saved");
          $('#listings').find('.tab-saved').removeClass("tab-save");
        }
        /*mark viewed when opened*/
        var p = $(this).siblings('p');
        p.find('span.icon-ok').addClass('color-blue').attr("title", "viewed");

        // Added listing to store list of opened listings
        storeListing(list_numb, address, aptNum, saved, code);
      }
      else{
        $(".listing-info").each(function(){
          if($(this).attr('data-listing') == self_id){
            if(saved){
              $(this).prev('div').prev("a").trigger('click');
            } else{
              $(this).prev("a").trigger('click');
            }
          }
        });
      }

      window.scroll(0, scroll_to_y);
      tab_overflow(); // Handle tabs overflowing past the line

      // BBQ PLUGIN -- This enables the browser back and forward buttons for ajax applications
      var tabs = $('.ui-tabs'), // The "tab widgets" to handle.
      tab_a_selector = 'ul.ui-tabs-nav a'; // This selector will be reused when selecting actual tab widget A elements.

      // Enable tabs on all tab widgets. The `event` property must be overridden so
      // that the tabs aren't changed on click, and any custom event name can be
      // specified. Note that if you define a callback for the 'select' event, it
      // will be executed for the selected tab whenever the hash changes.
      // Define our own click handler for the tabs, overriding the default.
      tabs.find( tab_a_selector ).click(function(){
      	$.cookie('listActive', "yes");
        var active = $.cookie('listActive');
        var state = {},
        /* Get the id of this tab widget. */
        id = $(this).closest( '.ui-tabs' ).attr( 'id' ),
        /* Get the index of this tab. */
        idx = $(this).parent().prevAll().length;
        // Set the state!
        state[ id ] = idx;
        $.bbq.pushState( state );
      });
    }
    event.stopPropagation();
    event.preventDefault();
  });

  /*
  var getListings = function(){
    alert("hello");
  }
  */

  // Set tooltips using qtip jquery plugin
  $('a[title], div#amenity_icons[title]').qtip({
    style: {
      name: 'light',
      tip: true,
      color: '#707070',
      border: {
        width: 5,
        radius: 5,
        color: '#d0d0d0'
      }
    },
    position: {
      corner: {
        target: 'bottomMiddle',
        tooltip: 'topMiddle'
      }
    }
  });

  /*set sort order in the active column*/
  $('.jqgh_sorter').attr('data-order','');
  $('.jqgh_sorter i.fa').remove();
  var currentSortColumn =  $("#list").jqGrid('getGridParam','sortname');
  var currentSortOrder =  $("#list").jqGrid('getGridParam','sortorder');
  var headerToActivate =  $('div[data-column="'+currentSortColumn+'"]');
  headerToActivate.attr('data-order',currentSortOrder)
  if(currentSortOrder=='asc'){ headerToActivate.append("<i class='fa fa-chevron-up'></i>"); }
  else{ headerToActivate.append("<i class='fa fa-chevron-down'></i>"); }
}

$( document ).ready(function() {
  $("body").scroll(function(){
    if(($("#results-tab").hasClass("ui-tabs-selected ui-state-active") == true) && ($("body").scrollTop() > 185) && ($('#listings').hasClass("fixedTop") == false)){
      $('#listings').addClass('fixedTop').css('height', listingHeight);
      $('#listings .ui-tabs-nav').addClass('fixedTop');
      $('.thead-container').addClass('fixedTheadTop').css('top', fixedTheadTop);
    }

    if(($("#results-tab").hasClass("ui-tabs-selected ui-state-active") == true) && ($("body").scrollTop() < 185) && ($('#listings').hasClass("fixedTop") == true)){
      $('#listings').removeClass('fixedTop').css('height', 'unset');
      $('#listings .ui-tabs-nav').removeClass('fixedTop');
      $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
    }
  });

  $(window).scroll(function(){
    if(($("#results-tab").hasClass("ui-tabs-selected ui-state-active") == true) && ($(window).scrollTop() > 185) && ($('#listings').hasClass("fixedTop") == false)){
      $('#listings').addClass('fixedTop').css('height', listingHeight);
      $('#listings .ui-tabs-nav').addClass('fixedTop');
      $('.thead-container').addClass('fixedTheadTop').css('top', fixedTheadTop);
    }

    if(($("#results-tab").hasClass("ui-tabs-selected ui-state-active") == true) && ($(window).scrollTop() < 185) && ($('#listings').hasClass("fixedTop") == true)){
      $('#listings').removeClass('fixedTop').css('height', 'unset');
      $('#listings .ui-tabs-nav').removeClass('fixedTop');
      $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
    }
  });
});



function gridComplete(){
  if($("#list").getGridParam("records") == 0){
    $("#noListings").show();
    $("#gview_list").css("display","none");
  }else{
    $("#noListings").hide();
    $("#gview_list").show();
  }
}

// when the user changes the page, change the url hash using bbq
function onPaging (){
  var state = {};
  state [ 'page' ] = 4;
  $.bbq.pushState( state );
}

// Handle Overflowing Tabs
function tab_overflow(){
  var url = window.location.href;
  if((navigator.userAgent.match(/iPad/i) != null) != true){
    if(url.indexOf("search.php") > -1){
      var num_tabs = $('#listings').find('li').length - 1; // Get the number of open tabs to deal with overflow in case they don't fit on one line
      // Tab Overflow Option 2: Add 40px to elements that need to be moved down, for each additional row of tabs
      var num_rows = Math.ceil(num_tabs/5) - 1;
      var thead_top = 46 + (num_rows * 34);
      var amenitiesmenu_top = 245 + (num_rows * 40);
      var scrollwrapper_top = amenitiesmenu_top;
      theadTop = thead_top;
      $('#new-thead').css('top',thead_top+'px');
      $('.amenitiesmenu').css('top',amenitiesmenu_top+'px');
      $('#scrollwrapper').css('top',amenitiesmenu_top+'px');

      var thead_top2 = 35 + (num_rows * 33);
      var listing_height = 45 * (num_rows + 1);
      fixedTheadTop = thead_top2;
      listingHeight = listing_height;
    }
    else if(url.indexOf("addressSearch.php") > -1){
      var num_tabs = $('#listings').find('li').length - 1; // Get the number of open tabs to deal with overflow in case they don't fit on one line
      // Tab Overflow Option 2: Add 40px to elements that need to be moved down, for each additional row of tabs
      var num_rows = Math.ceil(num_tabs/5) - 1;
      thead_top = 46 + (num_rows * 34);
      amenitiesmenu_top = 251 + (num_rows * 40);
      scrollwrapper_top = amenitiesmenu_top - 106;
      $('#new-thead').css('top',thead_top+'px');
      $('.amenitiesmenu').css('top',amenitiesmenu_top+'px');
      $('#scrollwrapper').css('top',scrollwrapper_top+'px');

      var thead_top2 = 32 + (num_rows * 33);
      var listing_height = 36 * (num_rows + 1);
      fixedTheadTop = thead_top2;
      listingHeight = listing_height;
    }
    else{
      // Do nothing
    }
  }
  else{
    if(url.indexOf("search.php") > -1){
      var num_tabs = $('#listings').find('li').length; // Get the number of open tabs to deal with overflow in case they don't fit on one line
      // Tab Overflow Option 2: Add 40px to elements that need to be moved down, for each additional row of tabs
      var num_rows = Math.ceil(num_tabs/5) - 1;
      thead_top = 49 + (num_rows * 34);
      amenitiesmenu_top = 245 + (num_rows * 40);
      scrollwrapper_top = amenitiesmenu_top;
      $('#new-thead').css('top',thead_top+'px');
      $('.amenitiesmenu').css('top',amenitiesmenu_top+'px');
      $('#scrollwrapper').css('top',amenitiesmenu_top+'px');
    }
    else if(url.indexOf("addressSearch.php") > -1){
      var num_tabs = $('#listings').find('li').length; // Get the number of open tabs to deal with overflow in case they don't fit on one line
      // Tab Overflow Option 2: Add 40px to elements that need to be moved down, for each additional row of tabs
      var num_rows = Math.ceil(num_tabs/5) - 1;
      thead_top = 49 + (num_rows * 34);
      amenitiesmenu_top = 251 + (num_rows * 40);
      scrollwrapper_top = amenitiesmenu_top - 106;
      $('#new-thead').css('top',thead_top+'px');
      $('.amenitiesmenu').css('top',amenitiesmenu_top+'px');
      $('#scrollwrapper').css('top',scrollwrapper_top+'px');
    }
    else{
      // Do nothing
    }
  }
}

function checkDeuplicateTab(list_num){
  var tabs = $( "#listings span.ui-icon-close" ).parent().find(".listing-info").length;
  listing = 0;
  for(i = 0; i < tabs; i++){
    listing++;
    var number = $( "#listings span.ui-icon-close" ).parent().find(".listing-info")[i].getAttribute('data-listing');
    if(list_num == number){
      return true;
    }
  }
  return false;
}

function storePage(page) {
  $.ajax({
    url : "/controllers/ajax.php",
    data: {'storeSearchPage': 'true', 'page': page},
    async: false,
    success : function(userStatus) {
      console.log("page set: " + page);
    }
  });
}

function saveCriteria() {
  var location_grade = $('#location_grade').slider("value"),
  neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
  prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
  building_grade = $('#building_grade').slider("value"),
  views_grade = $('#views_grade').slider("value"),
  min_floor = $('#min_floor').val(),
  bedrooms = $( "#bedrooms_slider" ).slider("value"),
  min_price = $('#min_price').attr('data-price'),
  max_price = $('#max_price').attr('data-price'),
  living_area = $('#living_grade').slider("value"),
  bedroom_area = $('#bedroom_grade').slider("value"),
  building_address = $('#address-search').val(),
  n = [],
  p = [],
  amen = [];

  for(var i=0; i < neighborhoods.length; i++){ n.push(neighborhoods[i].value); }
  for(var i=0; i < prop_type.length; i++){ p.push(prop_type[i].value); }

  if(n.length == 0){
    n.push("North");
    n.push("Westside");
    n.push("Eastside");
    n.push("Chelsea");
    n.push("SMG");
    n.push("Village");
    n.push("Lower");
  }
  if(p.length == 0){
    p.push("1");
    p.push("2");
    p.push("4");
    p.push("5");
  }
  if(amenities['garage'] === true){ amen.push(1); }
  if(amenities['pool'] === true){ amen.push(2); }
  if(amenities['laundry'] === true){ amen.push(3); }
  if(amenities['doorman'] === true){ amen.push(4); }
  if(amenities['elevator'] === true){ amen.push(5); }
  if(amenities['pets'] === true){ amen.push(6); }
  if(amenities['fireplace'] === true){ amen.push(7); }
  if(amenities['healthclub'] === true){ amen.push(8); }
  if(amenities['prewar'] === true){ amen.push(9); }
  if(amenities['outdoor'] === true){ amen.push(10); }
  if(amenities['wheelchair'] === true){ amen.push(11); }

  $.ajax({
    type: "POST",
    url: "/controllers/save-criteria-auto.php",
    data: {"location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : n, "prop_type":p, "amenities":amen},
    success: function(data){
      console.log("formula saved");
    },
    error: function(){
      console.log("formula save failed");
    }
  });
}

function getStoredPage(saveForm) {
  $.ajax({
    type: "POST",
    url: "/controllers/ajax.php",
    data: {"getStoredSearchPage":"true"},
    success: function(page){
      page = JSON.parse(page);
      if (page === "tab-2") {
        var state = {},
        // Get the id of this tab widget.
        id = "tabs",
        tabs = $('#tabs').tabs(),
        // Get the index of this tab.
        current = tabs.tabs('option', 'selected');
        current = (current + 1);
        var next = (current + 1);
        state[ id ] = current; // Set the state!
        $.bbq.pushState( state );
        tabs.tabs('select', current); // switch to next tab
        return "noSave";
      }
      else if (page == "results") {
        // To prevent starting on a previously opened tab that's been re-opened.
        $('#listings').tabs('select',0);
        $.cookie('listActive', "no");
        var active = $.cookie('listActive');

        show_results(); // this function is in head.tpl.php

        // Move the state to the second page for when back to criteria button is clicked.
        var state = {},
        // Get the id of this tab widget.
        id = "tabs",
        tabs = $('#tabs').tabs(),
        // Get the index of this tab.
        current = tabs.tabs('option', 'selected');
        current = (current + 1);
        var next = (current + 1);
				state[ id ] = current; // Set the state!
				$.bbq.pushState( state );
				tabs.tabs('select', current); // switch to next tab

        if (previousOpenListings > 0) {
          $('#listings').removeClass('fixedTop').css('height', 'unset');
          $('#listings .ui-tabs-nav').removeClass('fixedTop');
          $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
        }
        
        if(saveForm == "true") {
          saveCriteria();
        }
        
        return "save";
      }
    }
  });
}

// Store the listings that are currently open to have them re-open
function storeListing(list_numb, address, apt, saved, code){
  $.get("/controllers/ajax.php", {
    storeOpenListing: 'true', //Call the PHP function
    listing: list_numb,
    address: address,
    apt: apt,
    saved: saved,
    code: code,
    success: function(result){
      console.log("done");
    }
  });
}

function removeStoredListing(list_numb) {
  $.get("/controllers/ajax.php", {
    removeStoredOpenListing: 'true', //Call the PHP function
    listing: list_numb,
    success: function(result){
      console.log("listing removed");
    }
  });
}

function reOpenListingTabs() {
  $.ajax({
    type: "POST",
    url: "/controllers/ajax.php",
    data: {"getStoredOpenListing":"true"},
    success: function(data){
      var listings = JSON.parse(data);

      if (listings != "") {
        for(i = 0; i < listings.length; i++){
          openListings(listings[i]['address'],listings[i]['apt'],listings[i]['list_numb'], listings[i]['code'], listings[i]['saved']);
          previousOpenListings++;
        }
      }
    }
  });
}

function openListings(address, apt, list_numb, code, saved){
  var aptHTML = '<span class="details" style="color:#a0a0a0;">'+apt+'</span>';
  scroll_to_y = window.pageYOffset;

  if(checkDeuplicateTab(list_numb) == false){
    $('#listings').tabs( "add" , '/controllers/custom.php?list_numb='+ list_numb + '&code=' + code, address + apt);

    //Maybe set a function here to check if the listing has already been saved?
    if(saved){
      var role = $("#role").text();

      if (role == 'agent') {
        if (saved == "saved") {
          $('#listings').find('li').last().find('.ui-icon-close')
          .before('<div class="tab-save tab-head-div'
            +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-agent-tab-head heart-tabHead-'+list_numb+' color-blue" data-toggle="tooltip" data-placement="right" title="saved"></span></div>'); // add the save button
        }
        else{
          $('#listings').find('li').last().find('.ui-icon-close')
            .before('<div class="tab-save tab-head-div'
              +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-agent-tab-head heart-tabHead-'+list_numb+'" data-toggle="tooltip" data-placement="right" title="save"></span></div>'); // add the save button
        }
      }
      else if(role == 'buyer'){
        if (saved == "saved") {
          $('#listings').find('li').last().find('.ui-icon-close')
          .before('<div class="tab-save tab-head-div'
            +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-buyer-tab-head heart-tabHead-'+list_numb+' color-blue" data-toggle="tooltip" data-placement="right" title="saved"></span></div>'); // add the save button
        }
        else{
          $('#listings').find('li').last().find('.ui-icon-close')
            .before('<div class="tab-save tab-head-div'
              +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-buyer-tab-head heart-tabHead-'+list_numb+'" data-toggle="tooltip" data-placement="right" title="save"></span></div>'); // add the save button
        }
      }
      else{
        if (saved == "saved") {
          $('#listings').find('li').last().find('.ui-icon-close')
          .before('<div class="tab-save tab-head-div'
            +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-guest-tab-head heart-tabHead-'+list_numb+' color-blue" data-toggle="tooltip" data-placement="right" title="saved"></span></div>'); // add the save button
        }
        else{
          $('#listings').find('li').last().find('.ui-icon-close')
            .before('<div class="tab-save tab-head-div'
              +'" data-text='+saved+' data-list-num='+list_numb+'><span class="icon-heart save-listing-guest-tab-head heart-tabHead-'+list_numb+'" data-toggle="tooltip" data-placement="right" title="save"></span></div>'); // add the save button
        }
      }
    }

    $('#listings').find('li').last().find('.ui-icon-close')
      .before('<div class="listing-info" data-listing="'+list_numb
        +'" data-apt="'+apt+'" data-address="'+address+'" data-save="'+saved
        +'" data-code="'+code+'" style="display:none"></div>');

    if($('#listings').find('.tab-save').attr('data-text') == "saved"){
      $('#listings').find('.tab-save').addClass("tab-saved");
      $('#listings').find('.tab-saved').removeClass("tab-save");
    }

    tab_overflow(); // Handle tabs overflowing past the line

    $('#listings').tabs('select',0);
    $.cookie('listActive', "no");
    var active = $.cookie('listActive');
  }
}

// GET SEARCH RESULTS WITH JQGRID
function jqgrid(sortColumn, sortOrder){
  if (typeof(sortColumn)==='undefined') sortColumn = 'price';
  if (typeof(sortOrder)==='undefined') sortOrder = 'asc';

	// Get the search criteria from the form fields
  var	location_grade = $('#location_grade').slider("value"),
  neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
  prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
  building_grade = $('#building_grade').slider("value"),
  views_grade = $('#views_grade').slider("value"),
  min_floor = $('#min_floor').val(),
  bedrooms = $( "#bedrooms_slider" ).slider("value"),
  min_price = $('#min_price').attr('data-price'),
  max_price = $('#max_price').attr('data-price'),
  living_area = $('#living_grade').slider("value"),
  bedroom_area = $('#bedroom_grade').slider("value");
  var neighborhoodsValue = $.cookie("test", neighborhoods);
  building_address = $('#address-search').val();

  $.cookie("North", "false");
  $.cookie("Westside", "false");
  $.cookie("Eastside", "false");
  $.cookie("Chelsea", "false");
  $.cookie("SMG", "false");
  $.cookie("Village", "false");
  $.cookie("Lower", "false");
  $.cookie("Coop", "false");
  $.cookie("Condo", "false");
  $.cookie("House", "false");
  $.cookie("Condop", "false");

  var n = [];
  for(i=0; i < neighborhoods.length; i++){
    n.push(neighborhoods[i].value);
    $.cookie(neighborhoods[i].value, "true");
  }

  var p = [];
  for(i=0; i < prop_type.length; i++){
    p.push(prop_type[i].value);

    if(prop_type[i].value == "1"){
      $.cookie("Coop", "true");
    }
    else if(prop_type[i].value == "2"){
      $.cookie("Condo", "true");
    }
    else if(prop_type[i].value == "4"){
      $.cookie("House", "true");
    }
    else if(prop_type[i].value == "5"){
      $.cookie("Condop", "true");
    }
    else{
      //Do nothing
    }
  }

  if(n.length == 0){
    n = "null";
  }

  if(p.length == 0){
    p = "null";
  }

  //CHECK TO SEE WHICH OPTIONS WERE CHECKED
  //THEN MAKE COOKIES T OR F
  var amenities = [];

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){
    amenities.push("garage");
    $.cookie("garage", "true");
  }
  else{ $.cookie("garage", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){
    amenities.push("pool");
    $.cookie("pool", "true");
  }
  else{ $.cookie("pool", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){
    amenities.push("laundry");
    $.cookie("laundry", "true");
  }
  else{ $.cookie("laundry", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){
    amenities.push("doorman");
    $.cookie("doorman", "true");
  }
  else{ $.cookie("doorman", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){
    amenities.push("elevator");
    $.cookie("elevator", "true");
  }
  else{ $.cookie("elevator", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){
    amenities.push("pets");
    $.cookie("pets", "true");
  }
  else{ $.cookie("pets", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){
    amenities.push("fireplace");
    $.cookie("fireplace", "true");
  }
  else{ $.cookie("fireplace", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){
    amenities.push("healthclub");
    $.cookie("healthclub", "true");
  }
  else{ $.cookie("healthclub", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){
    amenities.push("prewar");
    $.cookie("prewar", "true");
  }
  else{ $.cookie("prewar", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){
    amenities.push("outdoor");
    $.cookie("outdoor", "true");
  }
  else{ $.cookie("outdoor", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){
    amenities.push("wheelchair");
    $.cookie("wheelchair", "true");
  }
  else{ $.cookie("wheelchair", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=timeshare]').hasClass('selected')){
    amenities.push("timeshare");
    $.cookie("timeshare", "true");
  }
  else{ $.cookie("timeshare", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=newconstruction]').hasClass('selected')){
    amenities.push("newconstruction");
    $.cookie("newconstruction", "true");
  }
  else{ $.cookie("newconstruction", "false"); }

  if(amenities.length == 0){
    amenities = null;
  }

  $("#list").jqGrid({
    url:'/controllers/jqgrid.php',
    datatype: 'XML',
    mtype: 'GET',
    postData:{
      'location_grade':location_grade,
      'neighborhoods':n,
      'building_grade':building_grade,
      'views_grade':views_grade,
      'min_floor':min_floor,
      'amenities':amenities,
      'max_price':max_price,
      'min_price':min_price,
      'bedrooms':bedrooms,
      'living_area':living_area,
      'prop_type':p,
      'bedroom_area':bedroom_area,
      'building_address':building_address
    },
    colNames:['details','price','amenities','location','building','views','space'],
    colModel :[
      {
        name:'details',
        index:'details',
        width:425,
        sortable:false,
        title:false
      },
      {
        name:'price',
        index:'price',
        width:100,
        align:'center',
        title:false
      },
      {
        name:'amenities',
        index:'amen',
        width:130,
        sortable: true,
        align:'center',
        title:false
      },
      {
        name:'loc',
        index:'loc',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'bld',
        index:'bld',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'vws',
        index:'vws',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'vroom_sqf',
        index:'vroom_sqf',
        width:75,
        align:'center',
        title:false
      },
    ],
    pager: '#pager',
    toppager: false,
    scroll: 1,
    loadtext : '',
    altRows: true,
    scrollOffset: 30,
    //rowNum: 10,
    sortname: sortColumn,
    sortorder: sortOrder,
    viewrecords: true,
    height: '800',
    //height: '100%',
    width: 'auto',
    loadComplete: loadComplete,
    gridComplete: gridComplete,
    onPaging: onPaging,
    caption: 'Search Results'
  });

  if(((navigator.userAgent.match(/iPad/i) != null) == true) && (Math.abs(window.orientation) === 0) || (Math.abs(window.orientation) === 180)){
    $(".ui-jqgrid-bdiv").height("850px");
  }
}

function jqgridReload(sortColumn, sortOrder){
  if (typeof(sortColumn)==='undefined') sortColumn = 'price';
  if (typeof(sortOrder)==='undefined') sortOrder = 'asc';

	// Get the search criteria from the form fields
  var	location_grade = $('#location_grade').slider("value"),
  neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
  prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
  building_grade = $('#building_grade').slider("value"),
  views_grade = $('#views_grade').slider("value"),
  min_floor = $('#min_floor').val(),
  bedrooms = $( "#bedrooms_slider" ).slider("value"),
  min_price = $('#min_price').attr('data-price'),
  max_price = $('#max_price').attr('data-price'),
  living_area = $('#living_grade').slider("value"),
  bedroom_area = $('#bedroom_grade').slider("value");
  var neighborhoodsValue = $.cookie("test", neighborhoods);
  building_address = $('#address-search').val();

  $.cookie("North", "false");
  $.cookie("Westside", "false");
  $.cookie("Eastside", "false");
  $.cookie("Chelsea", "false");
  $.cookie("SMG", "false");
  $.cookie("Village", "false");
  $.cookie("Lower", "false");
  $.cookie("Coop", "false");
  $.cookie("Condo", "false");
  $.cookie("House", "false");
  $.cookie("Condop", "false");

  var n = [];
  for(i=0; i < neighborhoods.length; i++){
    n.push(neighborhoods[i].value);
    $.cookie(neighborhoods[i].value, "true");
  }

  var p = [];
  for(i=0; i < prop_type.length; i++){
    p.push(prop_type[i].value);
    if(prop_type[i].value == "1"){ $.cookie("Coop", "true"); }
    else if(prop_type[i].value == "2"){ $.cookie("Condo", "true"); }
    else if(prop_type[i].value == "4"){ $.cookie("House", "true"); }
    else if(prop_type[i].value == "5"){ $.cookie("Condop", "true"); }
  }

  if(n.length == 0){ n = "null"; }
  if(p.length == 0){ p = "null"; }

  //CHECK TO SEE WHICH OPTIONS WERE CHECKED
  //THEN MAKE COOKIES T OR F
  var amenities = [];

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){
    amenities.push("garage");
    $.cookie("garage", "true");
  }
  else{ $.cookie("garage", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){
    amenities.push("pool");
    $.cookie("pool", "true");
  }
  else{ $.cookie("pool", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){
    amenities.push("laundry");
    $.cookie("laundry", "true");
  }
  else{ $.cookie("laundry", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){
    amenities.push("doorman");
    $.cookie("doorman", "true");
  }
  else{ $.cookie("doorman", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){
    amenities.push("elevator");
    $.cookie("elevator", "true");
  }
  else{ $.cookie("elevator", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){
    amenities.push("pets");
    $.cookie("pets", "true");
  }
  else{ $.cookie("pets", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){
    amenities.push("fireplace");
    $.cookie("fireplace", "true");
  }
  else{ $.cookie("fireplace", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){
    amenities.push("healthclub");
    $.cookie("healthclub", "true");
  }
  else{ $.cookie("healthclub", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){
    amenities.push("prewar");
    $.cookie("prewar", "true");
  }
  else{ $.cookie("prewar", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){
    amenities.push("outdoor");
    $.cookie("outdoor", "true");
  }
  else{ $.cookie("outdoor", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){
    amenities.push("wheelchair");
    $.cookie("wheelchair", "true");
  }
  else{ $.cookie("wheelchair", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=timeshare]').hasClass('selected')){
    amenities.push("timeshare");
    $.cookie("timeshare", "true");
  }
  else{ $.cookie("timeshare", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=newconstruction]').hasClass('selected')){
    amenities.push("newconstruction");
    $.cookie("newconstruction", "true");
  }
  else{ $.cookie("newconstruction", "false"); }

  if(amenities.length == 0){ amenities = null; }
  
  $("#list").jqGrid('setGridParam', {search: true,
    postData:{
      location_grade:location_grade,
      neighborhoods:n,
      building_grade:building_grade,
      views_grade:views_grade,
      min_floor:min_floor,
      amenities:amenities,
      max_price:max_price,
      min_price:min_price,
      bedrooms:bedrooms,
      living_area:living_area,
      prop_type:p,
      bedroom_area:bedroom_area,
      building_address:building_address
    }
  });
  
  $("#list").trigger('reloadGrid');
}

function movepage_onpressarrowkey(keyvalue)
{
  if(keyvalue == 38)
  {
    $('html,body').animate({
        scrollTop: '-=50px'
    },0);
  }
  else
  {
    $('html,body').animate({
          scrollTop: '+=50px'
      },0);
  }
}
// GET COUNT OF LISTINGS FOR CRITERIA
function getListingCount(){
  var	location_grade = $('#location_grade').slider("value"),
  neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
  prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
  building_grade = $('#building_grade').slider("value"),
  views_grade = $('#views_grade').slider("value"),
  min_floor = $('#min_floor').val(),
  bedrooms = $( "#bedrooms_slider" ).slider("value"),
  min_price = $('#min_price').attr('data-price'),
  max_price = $('#max_price').attr('data-price'),
  living_area = $('#living_grade').slider("value"),
  bedroom_area = $('#bedroom_grade').slider("value");

  var n = [];
  for(i=0; i < neighborhoods.length; i++){ n.push(neighborhoods[i].value); }

  var p = [];
  for(i=0; i < prop_type.length; i++){ p.push(prop_type[i].value); }

  if(n.length == 0){ n = "null"; }
  if(p.length == 0){ p = "null"; }

  //CHECK TO SEE WHICH OPTIONS WERE CHECKED
  //THEN MAKE COOKIES T OR F
  var amenities = [];
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){ amenities.push("garage"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){ amenities.push("pool"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){ amenities.push("laundry"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){ amenities.push("doorman"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){ amenities.push("elevator"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){ amenities.push("pets"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){ amenities.push("fireplace"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){ amenities.push("healthclub"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){ amenities.push("prewar"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){ amenities.push("outdoor"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){ amenities.push("wheelchair"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=timeshare]').hasClass('selected')){ amenities.push("timeshare"); }
  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=newconstruction]').hasClass('selected')){ amenities.push("newconstruction"); }
  if(amenities.length == 0){ amenities = null; }

  $.ajax({
    type: "POST",
    url: "/controllers/getListingCount.php",
    data: {"location_grade": location_grade, "neighborhoods": n, "prop_type": p, "building_grade": building_grade, "amenities": amenities, "views_grade": views_grade, "min_floor": min_floor, "max_price": max_price, "min_price": min_price, "bedrooms": bedrooms, "living_area": living_area, "bedroom_area": bedroom_area },
    success: function(data){
      var listingcount = JSON.parse(data);

      if(listingcount.homepik_listingcount != '')
      {
        $("#listing-count #l_count").html(listingcount.public_listingcount);
        $("#listing-count.grade_desc").removeClass('hide');
        $("#listing-count_homepik #lm_count").html(listingcount.homepik_listingcount);
      }
      else
      {
        $("#listing-count_homepik #lm_count").html(listingcount.public_listingcount);
        //$("#loginOrRegister_lnk").addClass('hide');
        //$('#listing-count').find('h4').text('Listings in Homepik');
        //$('#listing-count').find('h4').next().addClass('hide');
      }

    }
  });
}

// GET SEARCH RESULTS WITH JQGRID
function jqgridTablet(){
	// Get the search criteria from the form fields
  var	location_grade = $('#location_grade').slider("value"),
  neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
  prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
  building_grade = $('#building_grade').slider("value"),
  views_grade = $('#views_grade').slider("value"),
  min_floor = $('#min_floor').val(),
  bedrooms = $( "#bedrooms_slider" ).slider("value"),
  min_price = $('#min_price').attr('data-price'),
  max_price = $('#max_price').attr('data-price'),
  living_area = $('#living_grade').slider("value"),
  bedroom_area = $('#bedroom_grade').slider("value");
  var neighborhoodsValue = $.cookie("test", neighborhoods);
  building_address = $('#address-search').val();

  $.cookie("North", "false");
  $.cookie("Westside", "false");
  $.cookie("Eastside", "false");
  $.cookie("Chelsea", "false");
  $.cookie("SMG", "false");
  $.cookie("Village", "false");
  $.cookie("Lower", "false");
  $.cookie("Coop", "false");
  $.cookie("Condo", "false");
  $.cookie("House", "false");
  $.cookie("Condop", "false");

  var n = [];
  for(i=0; i < neighborhoods.length; i++){
    n.push(neighborhoods[i].value);
    $.cookie(neighborhoods[i].value, "true");
  }

  var p = [];
  for(i=0; i < prop_type.length; i++){
    p.push(prop_type[i].value);

    if(prop_type[i].value == "1"){ $.cookie("Coop", "true"); }
    else if(prop_type[i].value == "2"){ $.cookie("Condo", "true"); }
    else if(prop_type[i].value == "4"){ $.cookie("House", "true"); }
    else if(prop_type[i].value == "5"){ $.cookie("Condop", "true"); }
    else{ /*Do nothing */ }
  }

  if(n.length == 0){ n = "null"; }
  if(p.length == 0){ p = "null"; }

  //CHECK TO SEE WHICH OPTIONS WERE CHECKED
  //THEN MAKE COOKIES T OR F
  var amenities = [];

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){
    amenities.push("garage");
    $.cookie("garage", "true");
  }
  else{ $.cookie("garage", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){
    amenities.push("pool");
    $.cookie("pool", "true");
  }
  else{ $.cookie("pool", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){
    amenities.push("laundry");
    $.cookie("laundry", "true");
  }
  else{ $.cookie("laundry", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){
    amenities.push("doorman");
    $.cookie("doorman", "true");
  }
  else{ $.cookie("doorman", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){
    amenities.push("elevator");
    $.cookie("elevator", "true");
  }
  else{ $.cookie("elevator", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){
    amenities.push("pets");
    $.cookie("pets", "true");
  }
  else{ $.cookie("pets", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){
    amenities.push("fireplace");
    $.cookie("fireplace", "true");
  }
  else{ $.cookie("fireplace", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){
    amenities.push("healthclub");
    $.cookie("healthclub", "true");
  }
  else{ $.cookie("healthclub", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){
    amenities.push("prewar");
    $.cookie("prewar", "true");
  }
  else{ $.cookie("prewar", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){
    amenities.push("outdoor");
    $.cookie("outdoor", "true");
  }
  else{ $.cookie("outdoor", "false"); }

  if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){
    amenities.push("wheelchair");
    $.cookie("wheelchair", "true");
  }
  else{ $.cookie("wheelchair", "false"); }

  if(amenities.length == 0){ amenities = null; }

  $("#list").jqGrid({
    url:'/controllers/jqgrid.php',
    datatype: 'XML',
    mtype: 'GET',
    postData:{
      'location_grade':location_grade,
      'neighborhoods':neighborhoods,
      'building_grade':building_grade,
      'views_grade':views_grade,
      'min_floor':min_floor,
      'amenities':amenities,
      'max_price':max_price,
      'min_price':min_price,
      'bedrooms':bedrooms,
      'living_area':living_area,
      'prop_type':prop_type,
      'bedroom_area':bedroom_area,
      'building_address':building_address
    },
    colNames:['details','price','amenities','location','building','views','space'],
    colModel :[
      {
        name:'details',
        index:'details',
        width:450,
        sortable:false,
        title:false
      },
      {
        name:'price',
        index:'price',
        width:100,
        align:'left',
        sortable:true,
        title:false
      },
      {
        name:'amenities',
        index:'amen',
        width:100,
        sortable: false,
        align:'center',
        title:false
      },
      {
        name:'loc',
        index:'loc',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'bld',
        index:'bld',
        width:100,
        align:'center',
        title:false
      },
      {
        name:'vws',
        index:'vws',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'vroom_sqf',
        index:'vroom_sqf',
        width:75,
        align:'center',
        title:false
      },
    ],
    pager: '#pager',
    loadtext : '',
    toppager: false,
    scroll: 1,
    altRows: true,
    //rowNum: 10,
    sortname: 'price',
    sortorder: 'asc',
    viewrecords: true,
    height: '500',
    //height: '100%',
    width: 'auto',
    loadComplete: loadComplete,
    gridComplete: gridComplete,
    onPaging: onPaging,
    caption: 'Search Results'
  });
}

$(window).resize(function () {
  var outerwidth = $('#grid').width();
  $('#list').setGridWidth(outerwidth); // setGridWidth method sets a new width to the grid dynamically
});

// GET SEARCH RESULTS WITH JQGRID
function addressSearch(address, sortColumn, sortOrder){
  if (typeof(sortColumn)==='undefined') sortColumn = 'price';
  if (typeof(sortOrder)==='undefined') sortOrder = 'asc';
  var amenities = [];

  if($('img.amenity-icons[rel=garage]').hasClass('selected')){ amenities.push("garage"); $.cookie("garage", "true"); }
  else{ $.cookie("garage", "false"); }

  if($('img.amenity-icons[rel=pool]').hasClass('selected')){ amenities.push("pool"); $.cookie("pool", "true"); }
  else{ $.cookie("pool", "false"); }

  if($('img.amenity-icons[rel=laundry]').hasClass('selected')){ amenities.push("laundry"); $.cookie("laundry", "true"); }
  else{ $.cookie("laundry", "false"); }

  if($('img.amenity-icons[rel=doorman]').hasClass('selected')){ amenities.push("doorman"); $.cookie("doorman", "true"); }
  else{ $.cookie("doorman", "false"); }

  if($('img.amenity-icons[rel=elevator]').hasClass('selected')){ amenities.push("elevator"); $.cookie("elevator", "true"); }
  else{ $.cookie("elevator", "false"); }

  if($('img.amenity-icons[rel=pets]').hasClass('selected')){ amenities.push("pets"); $.cookie("pets", "true"); }
  else{ $.cookie("pets", "false"); }

  if($('img.amenity-icons[rel=fireplace]').hasClass('selected')){ amenities.push("fireplace"); $.cookie("fireplace", "true"); }
  else{ $.cookie("fireplace", "false"); }

  if($('img.amenity-icons[rel=healthclub]').hasClass('selected')){ amenities.push("healthclub"); $.cookie("healthclub", "true"); }
  else{ $.cookie("healthclub", "false"); }

  if($('img.amenity-icons[rel=prewar]').hasClass('selected')){ amenities.push("prewar"); $.cookie("prewar", "true"); }
  else{ $.cookie("prewar", "false"); }

  if($('img.amenity-icons[rel=outdoor]').hasClass('selected')){ amenities.push("outdoor"); $.cookie("outdoor", "true"); }
  else{ $.cookie("outdoor", "false"); }

  if($('img.amenity-icons[rel=wheelchair]').hasClass('selected')){ amenities.push("wheelchair"); $.cookie("wheelchair", "true"); }
  else{ $.cookie("wheelchair", "false"); }

  if($('img.amenity-icons[rel=timeshare]').hasClass('selected')){ amenities.push("timeshare"); $.cookie("timshare", "true"); }
  else{ $.cookie("timeshare", "false"); }

  if($('img.amenity-icons[rel=newconstruction]').hasClass('selected')){ amenities.push("newconstruction"); $.cookie("newconstruction", "true"); }
  else{ $.cookie("newconstruction", "false"); }

  if(amenities.length == 0){ amenities = null; }

  console.log(amenities);

  $("#list").jqGrid({
    url:'/controllers/jqgrid2.php',
    datatype: 'xml',
    mtype: 'GET',
    postData:{
      'address':address,
      'amenities':amenities
    },
    colNames:['details','price','amenities','location','building','views','space'],
    colModel :[
      {
        name:'details',
        index:'details',
        width:425,
        sortable:false,
        title:false
      },
      {
        name:'price',
        index:'price',
        width:100,
        align:'center',
        title:false
      },
      {
        name:'amenities',
        index:'amen',
        width:135,
        sortable: false,
        align:'center',
        title:false
      },
      {
        name:'loc',
        index:'loc',
        width:80,
        align:'center',
        title:false
      },
      {
        name:'bld',
        index:'bld',
        width:75,
        align:'center',
        title:false
      },
      {
        name:'vws',
        index:'vws',
        width:70,
        align:'center',
        title:false
      },
      {
        name:'vroom_sqf',
        index:'vroom_sqf',
        width:70,
        align:'center',
        title:false
      },
    ],
    pager: '#pager',
    toppager: false,
    scroll: 1,
    loadtext : '',
    altRows: true,
    scrollOffset: 30,
    sortname: sortColumn,
    sortorder: sortOrder,
    viewrecords: true,
    height: '800',
    width: 'auto',
    loadComplete: loadComplete,
    gridComplete: gridComplete,
    onPaging: onPaging,
    caption: 'Search Results'
  });
}

function addressSearchReload(address, sortColumn, sortOrder){
  if (typeof(sortColumn)==='undefined') sortColumn = 'price';
  if (typeof(sortOrder)==='undefined') sortOrder = 'asc';
  var amenities = [];

  if($('img.amenity-icons[rel=garage]').hasClass('selected')){ amenities.push("garage"); $.cookie("garage", "true"); }
  else{ $.cookie("garage", "false"); }

  if($('img.amenity-icons[rel=pool]').hasClass('selected')){ amenities.push("pool"); $.cookie("pool", "true"); }
  else{ $.cookie("pool", "false"); }

  if($('img.amenity-icons[rel=laundry]').hasClass('selected')){ amenities.push("laundry"); $.cookie("laundry", "true"); }
  else{ $.cookie("laundry", "false"); }

  if($('img.amenity-icons[rel=doorman]').hasClass('selected')){ amenities.push("doorman"); $.cookie("doorman", "true"); }
  else{ $.cookie("doorman", "false"); }

  if($('img.amenity-icons[rel=elevator]').hasClass('selected')){ amenities.push("elevator"); $.cookie("elevator", "true"); }
  else{ $.cookie("elevator", "false"); }

  if($('img.amenity-icons[rel=pets]').hasClass('selected')){ amenities.push("pets"); $.cookie("pets", "true"); }
  else{ $.cookie("pets", "false"); }

  if($('img.amenity-icons[rel=fireplace]').hasClass('selected')){ amenities.push("fireplace"); $.cookie("fireplace", "true"); }
  else{ $.cookie("fireplace", "false"); }

  if($('img.amenity-icons[rel=healthclub]').hasClass('selected')){ amenities.push("healthclub"); $.cookie("healthclub", "true"); }
  else{ $.cookie("healthclub", "false"); }

  if($('img.amenity-icons[rel=prewar]').hasClass('selected')){ amenities.push("prewar"); $.cookie("prewar", "true"); }
  else{ $.cookie("prewar", "false"); }

  if($('img.amenity-icons[rel=outdoor]').hasClass('selected')){ amenities.push("outdoor"); $.cookie("outdoor", "true"); }
  else{ $.cookie("outdoor", "false"); }

  if($('img.amenity-icons[rel=wheelchair]').hasClass('selected')){ amenities.push("wheelchair"); $.cookie("wheelchair", "true"); }
  else{ $.cookie("wheelchair", "false"); }

  if($('img.amenity-icons[rel=timeshare]').hasClass('selected')){ amenities.push("timeshare"); $.cookie("timshare", "true"); }
  else{ $.cookie("timeshare", "false"); }

  if($('img.amenity-icons[rel=newconstruction]').hasClass('selected')){ amenities.push("newconstruction"); $.cookie("newconstruction", "true"); }
  else{ $.cookie("newconstruction", "false"); }

  if(amenities.length == 0){ amenities = null; }

  $("#list").jqGrid('setGridParam', {search: true,
    postData:{
      address:address,
      amenities:amenities
    }
  });
  
  $("#list").trigger('reloadGrid');
}

$(document).ready(function(){

  function getPopupHtml(clickedOn){
    return "<div class='need-to-signup-first-div bubble speech'>"
      +"<span class='fa fa-times need-to-signup-first-div-closer' title='close'></span>"
      +"You cannot "+clickedOn+" listings without an account."
      +"<br><br>To create an account,"
      +"<br>click <a class='need-to-signup-first-div-link' href='/controllers/guest-register.php'>here</a>."
      +"</div>";
  }

  $('body').delegate('.need-to-signup-first-div-closer','click',function(e){
    e.preventDefault();
    clearAllDialogs();
    $('.need-to-signup-first-div').remove();
  });

  /*space factor popup*/
  $('body').delegate('.space-factor-popup-trigger','click',function(e){
    clearAllPopups();
    var popup = getSpaceFactorPopupHtml();
    $(e.target).parent().append(popup);
    if($(window).scrollTop() < 500){ $(window).scrollTop(500); } // search
    if($("body").scrollTop() < 500){ $("body").scrollTop(500); } // address search
  });

  function getSpaceFactorPopupHtml(){
    return "<div class='space-factor-popup bubble speech'>"
      +"<span class='fa fa-times space-factor-popup-closer' title='close'></span>"
      +"Includes living room, dining room, all bedrooms and other significant rooms but "
      +"does not include the kitchen, hallways and vestibule. See FAQs for more information."
      +"</div>";
  }

  $('body').delegate('.space-factor-popup-closer','click',function(e){
    e.preventDefault();
    $('.space-factor-popup').remove();
  });

  /*implied sqaure footage popup*/
  $('body').delegate('.implied-square-footage-popup-trigger','click',function(e){
    clearAllPopups();
    var popup = getImplieSquareFootagePopupHtml();
    $(e.target).parent().append(popup);
    if($(window).scrollTop() < 440){ $(window).scrollTop(440); } // search
    if($("body").scrollTop() < 440){ $("body").scrollTop(440); } // address search
  });

  function getImplieSquareFootagePopupHtml(){
    return "<div class='implied-square-footage-popup bubble speech'>"
      +"<span class='fa fa-times implied-square-footage-popup-closer' title='close'></span>"
      +"Uses a multiplier of 1.3 to compensate for the missing areas as noted in the space factor."
      +"<br>"
      +"See FAQs for more information."
      +"</div>";
  }

  $('body').delegate('.implied-square-footage-popup-closer','click',function(e){
    e.preventDefault();
    $('.implied-square-footage-popup').remove();
  });

  function clearAllPopups(){
    $('.space-factor-popup').remove();
    $('.need-to-signup-first-div').remove()
    $('.implied-square-footage-popup').remove();
    $('.need-to-signup-first-div-inner').hide();
  }

  function clearAllDialogs(){
    $('#ajax-box').dialog('destroy');
    $('#save-listing').dialog('close');
    $('#save-listing').dialog('destroy');
    $('#compare-listings').dialog('close');
    $('#compare-listings').dialog('destroy');
    $('#add-edit-listing-comment').dialog('close');
    $('#add-edit-listing-comment').dialog('destroy');
    $('#email-listing-folder').dialog('close');
    $('#email-listing-folder').dialog('destroy');
  }
  
  $('body').delegate(".ui-widget-overlay","click", function(){
    $(".folder-section.compare-saved-listings-div").remove();
    $('#add-edit-listing-comment').empty();
    $('#email-listing-folder').empty();
    clearAllPopups();
    clearAllDialogs();
  });
  
  $('#overlay2').bind("click", function(){
      $('#add-edit-listing-comment').empty();
      $('#add-edit-listing-comment').dialog('close');
      $('#add-edit-listing-comment').dialog('destroy');
      $('#email-listing-folder').empty();
      $('#email-listing-folder').dialog('close');
      $('#email-listing-folder').dialog('destroy');
      $("#ajax-box").dialog('close');
      
      $("#overlay2").hide();
    });
   

  $('body').delegate('.save-listing-guest-inner-folders','click',function(e){
    clearAllPopups();
    $('.need-to-signup-first-div-inner-folders').css("display", "block");
  });

  $('body').delegate('.need-to-signup-first-div-inner-closer','click',function(e){
    e.preventDefault();
    $('.need-to-signup-first-div-inner').hide();
    $('.need-to-signup-first-div-inner-folders').css("display", "none");
  });

  /* Show popup when guest clicks save heart button on listing details page*/
  $('body').delegate('.save-listing-guest-inner','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('a.listing-small-icons').attr('data-list-num');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('a.listing-small-icons').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("listing-details-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('a.listing-small-icons').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('a.listing-small-icons').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('a.listing-small-icons').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
        }, "json");
      }
    });
  });

  /*show popup when buyer clicks save heart button on listing details page*/
  $('body').delegate('.save-listing-buyer-tab-inner','click',function(e){
   clearAllPopups();
    //$(e.target).closest('a.listing-small-icons').append(appendLoaderInBubble());
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('a.listing-small-icons').attr('data-list-num');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('a.listing-small-icons').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("listing-details-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('a.listing-small-icons').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('a.listing-small-icons').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('a.listing-small-icons').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });

            updateSearchCriteria(folders[0].name);
          }
          else{
            clearAllDialogs();
            var popup = getSaveThisListingPopupHtml(folders, list_num);
            $("#save-listing").append(popup);
            getLastUsedFolders();
            $("#save-listing").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'saveListingPopup'
            });
            $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
            //$(e.target).closest('a.listing-small-icons').append(popup);
            /*set referrer who opened the tab*/
            $('.save-this-listing-popup-footer button').attr('data-referrer', 'heart-tab-inner');
          }
        }, "json");
      }
    });
  });

  /*show popup when agent clicks save heart button on listing details page*/
  $('body').delegate('.save-listing-agent-tab-inner','click',function(e){
    clearAllPopups();
    //$(e.target).closest('a.listing-small-icons').append(appendLoaderInBubble());
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('a.listing-small-icons').attr('data-list-num')

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('a.listing-small-icons').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("listing-details-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('a.listing-small-icons').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getAgentSave:'true'
        },
        function (info) {
          if (info.buyers.length == 0) {
            var folder = [info.agent_email];
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "agentSaveListingInFolders":"true",
                selectedBuyers:folder,
                list_num:list_num
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('a.listing-small-icons').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('a.listing-small-icons').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
          else{
            clearAllPopups();
            $('#save-listing').dialog('destroy');
            var popup = getSaveThisListingAgentPopupHtml(info['folders'], info['buyers'], info['agent_email'], list_num);
            $("#save-listing").append(popup);
            getLastUsedBuyer();
            $("#save-listing").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'saveListingPopup'
            });
            $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
            /*set referrer who opened the tab*/
            $('.save-this-listing-agent-popup-footer button').attr('data-referrer', 'heart-tab-inner');
          }
        }, "json");
      }
    });
  });

  /* Show popup when guest clicks save heart button on listing*/
  $('body').delegate('.save-listing-guest','click',function(e){
    var list_num = $(e.target).closest('tr').attr('id');
    e.preventDefault();
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('tr').attr('id');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('p#abc').append(getRemovedFromTipHtml(status));
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('p#abc').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('p#abc').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('p#abc').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
        }, "json");
      }
    });
  });

  /*show popup when buyer clicks save heart button on listing*/
  $('body').delegate('.save-listing-buyer','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('tr').attr('id');

    $.get("/controllers/dialog.php", {
        checkIfListingAlreadySaved:'true',
        list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('p#abc').append(getRemovedFromTipHtml(status));
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('p#abc').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('p#abc').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('p#abc').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });

            updateSearchCriteria(folders[0].name);
          }
          else{
            clearAllDialogs()
            var popup = getSaveThisListingPopupHtml(folders, list_num);
            $("#save-listing").append(popup);
            getLastUsedFolders();
            $("#save-listing").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'saveListingPopup'
            });
            $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
            /*set referrer who opened the tab*/
            $('.save-this-listing-popup-footer button').attr('data-referrer', 'heart-list');
          }
        }, "json");
      }
    });
  });

  /*Save listing when agent clicks save heart button on listing*/
  $('body').delegate('.save-listing-agent','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).closest('tr').attr('id');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('p#abc').append(getRemovedFromTipHtml(status));
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('p#abc').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getAgentSave:'true'
        },
        function (info) {
          if (info.buyers.length == 0) {
            var folder = [info.agent_email];
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "agentSaveListingInFolders":"true",
                selectedBuyers:folder,
                list_num:list_num
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('p#abc').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('p#abc').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
          else{
              clearAllPopups();
              $('#save-listing').dialog('destroy');
              var popup = getSaveThisListingAgentPopupHtml(info['folders'], info['buyers'], info['agent_email'], list_num);
              $("#save-listing").append(popup);
              getLastUsedBuyer();
              $("#save-listing").dialog({
                modal: true,
                height: '0',
                width: '0',
                autoOpen: false,
                dialogClass: 'saveListingPopup'
              });
              $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
              /*set referrer who opened the tab*/
              $('.save-this-listing-agent-popup-footer button').attr('data-referrer', 'heart-list');
          }
        }, "json");
      }
    });
  });

  /* Show popup when guest clicks save heart button on tab head*/
  $('body').delegate('.save-listing-guest-tab-head','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).parent('div').attr('data-list-num');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('div.tab-head-div').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("tab-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('div.tab-head-div').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('div.tab-head-div').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('div.tab-head-div').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
        }, "json");
      }
    });
  });

  /*show popup when buyer clicks save heart button on tab head*/
  $('body').delegate('.save-listing-buyer-tab-head','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).parent('div').attr('data-list-num');

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('div.tab-head-div').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("tab-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('div.tab-head-div').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
          getMyFolders:'true'
        },
        function (folders) {
          if (folders.length == 1) {
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "saveListingInMyOneFolder":"true",
                selectedFolders:folders[0].name,
                list_num:list_num,
                comment: ''
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('div.tab-head-div').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('div.tab-head-div').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });

            updateSearchCriteria(folders[0].name);
          }
          else{
            clearAllPopups();
            $('#save-listing').dialog('destroy');
            var popup = getSaveThisListingPopupHtml(folders, list_num);
            $("#save-listing").append(popup);
            getLastUsedFolders();
            $("#save-listing").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'saveListingPopup'
            });
            $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
            /*set referrer who opened the tab*/
            $('.save-this-listing-popup-footer button').attr('data-referrer', 'heart-tab-head');
          }
        }, "json");
      }
    });
  });

  /*show popup when agent clicks save/compare heart button on tab head*/
  $('body').delegate('.save-listing-agent-tab-head','click',function(e){
    clearAllPopups();
    $("#save-listing").append(appendLoaderInBubble());
    $("#save-listing").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadsaveListingPopup'
    });
    $('#save-listing').dialog( "option", "title", "Loading Save Listing" ).dialog('open');
    var list_num = $(e.target).parent('div').attr('data-list-num')

    $.get("/controllers/dialog.php", {
      checkIfListingAlreadySaved:'true',
      list_num: list_num
    },
    function (ifAlreadySaved) {
      if(ifAlreadySaved == 1){
        clearAllDialogs();
        $.ajax({
          type: "POST",
          url: "/controllers/dialog.php/",
          data: {
            "deleteListingFromFolders":"true",
            list_num:list_num
          },
          async:false,
          success: function(status){
            clearAllPopups();
            clearAllDialogs();
            $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
            $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            $(e.target).closest('div.tab-head-div').append(getRemovedFromTipHtml(status));
            $("div.listing-removed-from-tip").addClass("tab-remove");
            updateStoredListing(list_num, "save");
            setTimeout(function (){
              $(e.target).closest('div.tab-head-div').find('div.listing-removed-from-tip').remove();
            },3000);
          }
        });
      } else{
        $.get("/controllers/dialog.php", {
            getAgentSave:'true'
        },
        function (info) {
          if (info.buyers.length == 0) {
            var folder = [info.agent_email];
            $.ajax({
              type: "POST",
              url: "/controllers/dialog.php/",
              data: {
                "agentSaveListingInFolders":"true",
                selectedBuyers:folder,
                list_num:list_num
              },
              async:false,
              success: function(status){
                clearAllPopups();
                clearAllDialogs();
                $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
                $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
                $(e.target).closest('div.tab-head-div').append(getSavedToTipHtml(status));
                updateStoredListing(list_num, "saved");
                setTimeout(function (){
                  $(e.target).closest('div.tab-head-div').find('div.listing-saved-to-tip').remove();
                },3000);
              }
            });
          }
          else{
            clearAllPopups();
            $('#save-listing').dialog('destroy');
            var popup = getSaveThisListingAgentPopupHtml(info['folders'], info['buyers'], info['agent_email'], list_num);
            $("#save-listing").append(popup);
            getLastUsedBuyer();
            $("#save-listing").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'saveListingPopup'
            });
            $('#save-listing').dialog( "option", "title", "Save Listing" ).dialog('open');
            /*set referrer who opened the tab*/
            $('.save-this-listing-agent-popup-footer button').attr('data-referrer', 'heart-tab-head');
          }
        }, "json");
      }
    });
  });
  function appendLoaderInBubble(){
    var loader = "<div id='loadingGIF'>"
      +"<img src='/images/ajax-loader-large.gif' alt='loading, please wait...' height='50'>"
      +"</div>";

    return "<div class='need-to-signup-first-div bubble speech save-this-listing-popup' title='Save listing'>"
      +"<div class='save-this-listing-popup-body'>"
      +loader
      +"</div>"
      +"</div>";
  }

  function listingAlreadySaved(){
    return "<div class='need-to-signup-first-div bubble speech save-this-listing-popup' title='Save listing'>"
      +"<span class='fa fa-times need-to-signup-first-div-closer save-this-listing-popup-closer' title='close'></span>"
      +"<span class='save-this-listing-popup-header'>Listing already saved</span>"
      +"<span class='save-this-listing-popup-sub-header color-blue'>This listing has already been saved to your folder.</span>"
      +"</div>";
  }

  function getSaveThisListingPopupHtml(folders, list_num){
    var html = "<div class='need-to-signup-first-div bubble speech save-this-listing-popup' title='Save listing'>"
      +"<span class='fa fa-times need-to-signup-first-div-closer save-this-listing-popup-closer' title='close'></span>"
      +"<span class='save-this-listing-popup-header'>My Listing Folders</span><br/><br/>"
      +"<span class='save-this-listing-popup-sub-header color-blue'>Save this listing to (check as many as apply):</span><br/><br/>"
      +"<div class='save-this-listing-popup-body'>"
      +"<ul>";

    folders.forEach(function (v,k ){
      html += "<li>";
        if (formulaName == v.name) { html += "<label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.name+"' checked/>"; }
        else{ html += "<label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.name+"' />"; }
        html += "<span> "+v.buyerName+" ("+v.name+")</span></label>"
      +"</li>";
    });

    html += "</ul><br/><br/>"
      +"<span class='save-this-listing-popup-sub-header color-blue'>Add a comment:</span>"
      +"<textarea class='save-this-listing-popup-comment'></textarea>"
      +"</div>"
      +"<div class='save-this-listing-popup-footer'>"
        +"<button data-listing="+list_num+">submit <i class='fa fa-chevron-right color-blue'></i></button>"
      +"</div>"
      +"</div>";
    return html;
  }

  function getSaveThisListingAgentPopupHtml(folders, buyers, email, list_num){
    var html = "<div class='need-to-signup-first-div bubble speech save-this-listing-popup' title='Save listing'>"
      +"<span class='fa fa-times need-to-signup-first-div-closer save-this-listing-popup-closer' title='close'></span>"
      +"<span class='save-this-listing-popup-header'>Save Listing</span><br/><br/>"
      +"<span class='save-this-listing-popup-sub-header color-blue'>Save this listing to (check as many as apply):</span><br/><br/>"
      +"<div class='save-this-listing-popup-body'>"
        + "<ul class='save-this-listings-popup-folder-section'>"
        + "<li><label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+email+"'/><span>My Agent Folder</span></label></li>";

        if (lastBuyersSavedTo.length > 0) {
          buyers.forEach(function(v,k){
            if (lastBuyersSavedTo.indexOf(v.email) > -1) {
              html += "<li><label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.email+"' checked/><span> "+v.last_name+", "+v.first_name+"</span></label></li>";
            }
          });

          html += "<li><label class='view-other-buyers'>View other buyers <i class='fa fa-chevron-down'></i></label></li>"
          + "<div class='list-of-other-buyers'>";

            buyers.forEach(function(v,k){
              if (lastBuyersSavedTo.indexOf(v.email) < 0) {
                html += "<li><label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.email+"'/><span> "+v.last_name+", "+v.first_name+"</span></label></li>";
              }
            });

          html += "</div>"
        }
        else{
          buyers.forEach(function(v,k){
            html += "<li><label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.email+"'/><span> "+v.last_name+", "+v.first_name+"</span></label></li>";
          });
        }
    html +="</ul><br/><br/>"
      +"<span class='save-this-listing-popup-sub-header color-blue'>Add a comment:</span>"
      +"<textarea class='save-this-listing-popup-comment'></textarea>"
      +"</div>"
      +"<div class='save-this-listing-agent-popup-footer'>"
          +"<button data-listing="+list_num+">submit <i class='fa fa-chevron-right color-blue'></i></button><br/><br/>"
      +"</div>"
      +"</div>";
    return html;
  }

  function getLastUsedFolders() {
    $.get("/controllers/ajax.php", {
      getLastFolder:'true'
    },
    function (folders) {
      folders.forEach(function(v,k){
        $(".save-this-listing-popup input[value='"+v+"']").attr("checked", true)
      });
    }, "json");
  }

  function getLastUsedBuyer() {
    $.get("/controllers/ajax.php", {
      getLastBuyer:'true'
    },
    function (folders) {
      if(folders.length > 0){
        folders.forEach(function(v,k){
          $(".save-this-listing-popup input[value='"+v+"']").attr("checked", true)
        });
      }      
    }, "json");
  }

  function getSaveThisListingAgentPopupFolderSectionHtml(folders){
    var html = ""
    folders.forEach(function (v,k ){
      html += "<li>"
        +"<label><input type='checkbox' class='save-this-listing-popup-checkbox' value='"+v.name+"' />"
        +"<span> "+v.name+"</span></label>"
      +"</li>";
    });
    return html;
  }

  function updateSearchCriteria(folder) {
    if (window.location.href.indexOf("/search.php") > -1){
      var location_grade = $('#location_grade').slider("value"),
      neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
      prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
      building_grade = $('#building_grade').slider("value"),
      views_grade = $('#views_grade').slider("value"),
      min_floor = $('#min_floor').val(),
      bedrooms = $( "#bedrooms_slider" ).slider("value"),
      min_price = $('#min_price').attr('data-price'),
      max_price = $('#max_price').attr('data-price'),
      living_area = $('#living_grade').slider("value"),
      bedroom_area = $('#bedroom_grade').slider("value"),
      building_address = $('#address-search').val(),
      n = [],
      p = [],
      amen = [];

      for(var i=0; i < neighborhoods.length; i++){ n.push(neighborhoods[i].value); }
      for(var i=0; i < prop_type.length; i++){ p.push(prop_type[i].value); }

      if(n.length == 0){
        n.push("North");
        n.push("Westside");
        n.push("Eastside");
        n.push("Chelsea");
        n.push("SMG");
        n.push("Village");
        n.push("Lower");
      }
      if(p.length == 0){
        p.push("1");
        p.push("2");
        p.push("4");
        p.push("5");
      }

      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){ amen.push(1); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){ amen.push(2); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){ amen.push(3); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){ amen.push(4); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){ amen.push(5); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){ amen.push(6); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){ amen.push(7); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){ amen.push(8); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){ amen.push(9); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){ amen.push(10); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){ amen.push(11); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=timeshare]').hasClass('selected')){ amen.push(12); }
      if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=newconstruction]').hasClass('selected')){ amen.push(13); }

      $.ajax({
        type: "POST",
        url: "/controllers/save-criteria-auto.php",
        data: {"folder":folder, "location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : n, "prop_type":p, "amenities":amen}
      });
    }
  }

  function updateStoredListing(listing, save) {
    $.get("/controllers/ajax.php", {
      updateStoredListing:'true',
      listing: listing,
      save: save
    },
    function (listings) {
      console.log("Listing Updated");
    }, "json");
  }

  $('body').delegate('.save-this-listing-popup-closer','click',function(e){
    e.preventDefault();
    $('.save-this-listing-popup').remove();
    clearAllDialogs();
  });

  $('body').delegate('.view-other-buyers','click',function(e){
    $(this).html("Hide other buyers <i class='fa fa-chevron-up'></i>");
    $(this).addClass('hide-other-buyers').removeClass('view-other-buyers');
    $('.list-of-other-buyers').slideDown();
  });

  $('body').delegate('.hide-other-buyers','click',function(e){
    $(this).html("View other buyers <i class='fa fa-chevron-down'></i>");
    $(this).addClass('view-other-buyers').removeClass('hide-other-buyers');
    $('.list-of-other-buyers').slideUp();
  });

  $('body').delegate('.save-this-listing-popup-footer button','click',function(e){
    var selected_folders = getSelectedFolders();
    if(!selected_folders.length){
      $("#ajax-box").dialog({
        modal: true,
        height: 'auto',
        width: 'auto',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage2 saveError',
        buttons : {
          Ok: function(){
            $(this).dialog("close");
          }
        }
      });
      $('#ajax-box').load('/controllers/messages.php #saveError',function(){
        $('#ajax-box').dialog( "option", "title", "Save Error" ).dialog('open');
      });
    }
    else{
      var referrer = $(e.target).attr('data-referrer');
      var list_num = $(e.target).attr('data-listing');
      var comment = $(e.target).parents().find('.save-this-listing-popup-comment').val();

      if(referrer == 'heart-list'){
        var parent_p = $('.heart-list-'+list_num).closest('p#abc');
      } else if (referrer == 'heart-tab-head'){
        var parent_p = $('.heart-tabHead-'+list_num).closest('div.tab-head-div');
      } else if (referrer == 'heart-tab-inner'){
        var parent_p = $('.heart-tabInner-'+list_num).closest('a.listing-small-icons');
      } else{
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage errorMsg',
          buttons : {
            Ok: function(){
              $(this).dialog("close");
            }
          }
        });
        $('#ajax-box').load('/controllers/messages.php #errorMessage',function(){
          $('#ajax-box').dialog( "option", "title", "Error" ).dialog('open');
        });
        return;
      }

      clearAllPopups();
      $.ajax({
        type: "POST",
        url: "/controllers/dialog.php/",
        data: {
          "saveListingInMyFolders":"true",
          selectedFolders:selected_folders,
          list_num:list_num,
          comment: comment
        },
        async:false,
        success: function(status){
          clearAllPopups();
          clearAllDialogs();
          $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
          $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
          $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
          parent_p.append(getSavedToTipHtml(status));
          setTimeout(function (){
            parent_p.find('div.listing-saved-to-tip').remove();
          },3000);
        }
      });

      updateSearchCriteria(selected_folders);
    }
  });

  $('body').delegate('.save-this-listing-agent-popup-footer button','click',function(e){
    var selected_folders = getSelectedFolders();
    if(!selected_folders.length){
      $("#ajax-box").dialog({
        modal: true,
        height: 'auto',
        width: 'auto',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage2 saveError',
        buttons : {
          Ok: function(){
            $(this).dialog("close");
          }
        }
      });
      $('#ajax-box').load('/controllers/messages.php #saveError',function(){
        $('#ajax-box').dialog( "option", "title", "Save Error" ).dialog('open');
      });

      return false;
    }
    else{
      lastBuyersSavedTo = selected_folders;
      var referrer = $(e.target).attr('data-referrer');
      var list_num = $(e.target).attr('data-listing');
      var comment = $(e.target).parents().find('.save-this-listing-popup-comment').val();
      var buyer = $(e.target).parents().find('.save-this-listing-dropdown').val();
          
      if(referrer == 'heart-list'){
        var parent_p = $('.heart-list-'+list_num).closest('p#abc');
      } else if (referrer == 'heart-tab-head'){
        var parent_p = $('.heart-tabHead-'+list_num).closest('div.tab-head-div');
      } else if (referrer == 'heart-tab-inner'){
        var parent_p = $('.heart-tabInner-'+list_num).closest('a.listing-small-icons');
      } else{
        $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage errorMsg',
            buttons : {
              Ok: function(){
                $(this).dialog("close");
              }
            }
        });
        $('#ajax-box').load('/controllers/messages.php #errorMessage',function(){
          $('#ajax-box').dialog( "option", "title", "Error" ).dialog('open');
        });
        return;
      }

      clearAllPopups();
      $.ajax({
        type: "POST",
        url: "/controllers/dialog.php/",
        data: {
          "agentSaveListingInFolders":"true",
          selectedBuyers:selected_folders,
          list_num:list_num,
          comment: comment
        },
        async:false,
        success: function(status){
          clearAllPopups();
          clearAllDialogs();
          $('.heart-list-'+list_num).addClass('color-blue').attr("title", "saved");
          $('.heart-tabHead-'+list_num).addClass('color-blue').attr("title", "saved");
          $('.heart-tabInner-'+list_num).addClass('color-blue').attr("title", "saved");
          parent_p.append(getSavedToTipHtml(status));
          updateStoredListing(list_num, "saved");
          setTimeout(function (){
            parent_p.find('div.listing-saved-to-tip').remove();
          },3000);
        }
      });
    }
  });

  $('body').delegate('.save-this-listing-dropdown','change',function(e){
    $.get("/controllers/dialog.php", {
      getMyFolders:'true',
      email: $(e.target).val()
    },
    function (folders) {
      var foldersHTML = getSaveThisListingAgentPopupFolderSectionHtml(folders);
      $(e.target).parent().find('.save-this-listings-popup-folder-section').html(foldersHTML);
    }, "json");
  });

  function  getSavedToTipHtml(folders){
    return "<div class='need-to-signup-first-div listing-saved-to-tip' title='Listing saved'>"
      +"Saved to "+ folders
      +"</div>";
  }

  function  getRemovedFromTipHtml(folders){
    return "<div class='need-to-signup-first-div listing-removed-from-tip' title='Listing removed'>"
      +"Removed from "+ folders
      +"</div>";
  }

  function getSelectedFolders(){
    return $('input:checkbox:checked.save-this-listing-popup-checkbox').map(function () {
      return this.value;
    }).get();
  }

  // Open popup when guest clicks "Click to compare saved listings"
  $('body').delegate('.clickGuestCompare','click',function(e){
    clearAllPopups();
    $("#compare-listings").append(appendLoaderInBubble());
    $("#compare-listings").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadCompareListingsPopup',
    });
    $('#compare-listings').dialog( "option", "title", "Loading Compare Listing" ).dialog('open');

    $.ajax({
      type: "POST",
      url: "/controllers/get-buyer-listings.php",
      async:false,
      success: function(listings){
        listings = JSON.parse(listings);
        clearAllPopups();
        $('#compare-listings').dialog('destroy');
        var popup = getCompareListingsHTML(listings, "guest");
        $('#compare-listings').append(popup)
        $("#compare-listings").dialog({
          modal: true,
          height: '0',
          width: '0',
          autoOpen: false,
          dialogClass: 'compareListingsPopup'
        });
        $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
      },
      error: function(){
        console.log("failed");
        clearAllPopups();
        clearAllDialogs();
      }
    });
  });

  // Open popup when buyer clicks "Click to compare saved listings"
  $('body').delegate('.clickBuyerCompare','click',function(e){
    clearAllPopups();
    $("#compare-listings").append(appendLoaderInBubble());
    $("#compare-listings").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadCompareListingsPopup'
    });
    $('#compare-listings').dialog( "option", "title", "Loading Compare Listing" ).dialog('open');

    $.ajax({
      type: "POST",
      url: "/controllers/get-buyer-listings.php",
      data: {
        "email": $("#email").text()
      },
      async:false,
      success: function(listings){
        listings = JSON.parse(listings);
        clearAllPopups();
        $('#compare-listings').dialog('destroy');
        var popup = getCompareListingsHTML(listings, "buyer");
        $('#compare-listings').append(popup)
        $("#compare-listings").dialog({
          modal: true,
          height: '0',
          width: '0',
          autoOpen: false,
          dialogClass: 'compareListingsPopup'
        });
        $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
      },
      error: function(){
        console.log("failed");
        clearAllPopups();
        clearAllDialogs();
      }
    });
  });

  // Open popup when agent clicks "Click to compare saved listings"
  $('body').delegate('.clickAgentCompare','click',function(e){
    clearAllPopups();
    $("#compare-listings").append(appendLoaderInBubble());
    $("#compare-listings").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'loadCompareListingsPopup'
    });
    $('#compare-listings').dialog( "option", "title", "Loading Compare Listing" ).dialog('open');

    $.ajax({
      type: "POST",
      url: "/controllers/get-agent-listings.php",
      data: { "email": $("#email").text() },
      async:false,
      success: function(listings){
        listings = JSON.parse(listings);
        var popup = getAgentCompareListingsHTML(listings, "agent");
        $('#compare-listings').dialog('destroy');
        $.ajax({
          type: "POST",
          url: "/controllers/get-agents-buyer-folders.php",
          data: {"agentID": $("#agentID").text() },
          async:false,
          success: function(listings){
            listings = JSON.parse(listings);
            clearAllPopups();
            popup += getAgentCompareListingsHTML(listings, "agent-buyer");
            $('#compare-listings').append(popup)
            $("#compare-listings").dialog({
              modal: true,
              height: '0',
              width: '0',
              autoOpen: false,
              dialogClass: 'compareListingsPopup'
            });
            $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
          },
          error: function(){
            console.log("failed");
            clearAllPopups();
            clearAllDialogs();
          }
        });
      },
      error: function(){
        console.log("failed");
        clearAllPopups();
        clearAllDialogs();
      }
    });
  });

  $('body').delegate('.compare-listings-closer','click',function(e){
    $(".folder-section.compare-saved-listings-div").remove();
    clearAllPopups();
    clearAllDialogs();
  });
  
  $('body').delegate('.moreInfo','click',function(e){
    location.assign('/controllers/faq.php?section=manage');
  });

  $('body').delegate('.toggleFolder','click',function(e){
    $(e.target).parents().find(".toggleFolderClosed").addClass("toggleFolder").removeClass("toggleFolderClosed"); // Set all folder class back to original class
    $('.toggleFolder').parents().find(".listingSection").hide(); // Close any open folders
    $(".openFolder").hide(); // hide all open folder icon
    $(".closedFolder").show(); // show all closed folder icon
    $(e.target).parents().eq(3).find(".listingSection").toggle(); // Open folder
    $(e.target).parents().eq(3).find(".closedFolder").hide(); // hide the closed folder icon
    $(e.target).parents().eq(3).find(".openFolder").show(); // show the open folder icon
    lastUserFolderOpen = $(e.target).parents().find(".folderDetails:first-child").attr("data-user"); // Set open folder
    lastFolderNameOpen = $(e.target).parents().find(".folderDetails:first-child").attr("data-folder"); // Set open Folder
    $(e.target).parents().eq(3).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
  });

  $('body').delegate('.toggleFolderClosed','click',function(e){
    $(e.target).parents().eq(3).find(".listingSection").toggle(); // Close folder
    $(e.target).parents().eq(3).find(".openFolder").hide(); // hide the open folder icon
        $(e.target).parents().eq(3).find(".closedFolder").show(); // show the closed folder icon
    $(e.target).parents().eq(3).find(".toggleFolderClosed").addClass("toggleFolder").removeClass("toggleFolderClosed"); // Change class to original class
    lastUserFolderOpen = ""; // Unset open folder
    lastFolderNameOpen = ""; // Unset open folder
  });

  // Delete listing from guest folder
  $('body').delegate('.deleteSavedGuestListing','click',function(e){
    var list_num = $(e.target).attr("data-listing");
    var folder = $(e.target).attr("data-folder");

    $.get("/controllers/ajax.php", {
      clear_one_saved_from_folder: 'true',
      buyer: $("#guestID").text(),
      delete_id: list_num,
      folder: folder
    }, function(){
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-buyer-listings.php",
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              clearAllPopups();
              $('#compare-listings').dialog('destroy');
              var popup = getCompareListingsHTML(listings, "guest");
              $('#compare-listings').append(popup)
              $("#compare-listings").dialog({
                modal: true,
                height: '0',
                width: '0',
                autoOpen: false,
                dialogClass: 'compareListingsPopup'
              });
              $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
              
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder        
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close

              $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
              $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
              $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    });
  });

  // Delete listing from buyer folder
  $('body').delegate('.deleteSavedBuyerListing','click',function(e){
    var list_num = $(e.target).attr("data-listing");
    var folder = $(e.target).attr("data-folder");

    $.get("/controllers/ajax.php", {
      clear_one_saved_from_folder: 'true',
      buyer: $("#email").text(),
      delete_id: list_num,
      folder: folder
    }, function(){
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-buyer-listings.php",
            data: {
              "email": $("#email").text()
            },
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              clearAllPopups();
              $('#compare-listings').dialog('destroy');
              var popup = getCompareListingsHTML(listings, "buyer");
              $('#compare-listings').append(popup)
              $("#compare-listings").dialog({
                modal: true,
                height: '0',
                width: '0',
                autoOpen: false,
                dialogClass: 'compareListingsPopup'
              });
              $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
              
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon

              $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
              $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
              $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    });
  });

  // Delete listing from agent folder
  $('body').delegate('.deleteSavedAgentListing','click',function(e){
    var list_num = $(e.target).attr("data-listing");
    var folder = $(e.target).attr("data-folder");

    $.get("/controllers/ajax.php", {
      clear_one_queued_from_folder: 'true',
      delete_id: list_num,
      folder: folder
    }, function(){
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-agent-listings.php",
            data: { "email": $("#email").text() },
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              var popup = getAgentCompareListingsHTML(listings, "agent");
              $.ajax({
                type: "POST",
                url: "/controllers/get-agents-buyer-folders.php",
                data: {"agentID": $("#agentID").text() },
                async:false,
                success: function(listings){
                  listings = JSON.parse(listings);
                  clearAllPopups();
                  $('#compare-listings').dialog('destroy');
                  popup += getAgentCompareListingsHTML(listings, "agent-buyer");
                  $('#compare-listings').append(popup)
                  $("#compare-listings").dialog({
                    modal: true,
                    height: '0',
                    width: '0',
                    autoOpen: false,
                    dialogClass: 'compareListingsPopup'
                  });
                  $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
                  
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon

                  $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
                  $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
                  $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
                },
                error: function(){
                  console.log("failed");
                  clearAllPopups();
                  clearAllDialogs();
                }
              });
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    });
  });

  // Agent delete listing from buyer folder
  $('body').delegate('.agentDeleteSavedBuyerListing','click',function(e){
    var list_num = $(e.target).attr("data-listing");
    var folder = $(e.target).attr("data-folder");
    var buyer = $(e.target).attr("data-buyer");

    $.get("/controllers/ajax.php", {
      clear_one_saved_from_folder: 'true',
      buyer: buyer,
      delete_id: list_num,
      folder: folder
    }, function(){
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;
          $.ajax({
            type: "POST",
            url: "/controllers/get-agent-listings.php",
            data: { "email": $("#email").text() },
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              var popup = getAgentCompareListingsHTML(listings, "agent");
              $.ajax({
                type: "POST",
                url: "/controllers/get-agents-buyer-folders.php",
                data: {"agentID": $("#agentID").text() },
                async:false,
                success: function(listings){
                  listings = JSON.parse(listings);
                  clearAllPopups();
                  $('#compare-listings').dialog('destroy');
                  popup += getAgentCompareListingsHTML(listings, "agent-buyer");
                  $('#compare-listings').append(popup)
                  $("#compare-listings").dialog({
                    modal: true,
                    height: '0',
                    width: '0',
                    autoOpen: false,
                    dialogClass: 'compareListingsPopup'
                  });
                  $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
                  
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon

                  $('.heart-list-'+list_num).removeClass('color-blue').attr("title", "save");
                  $('.heart-tabHead-'+list_num).removeClass('color-blue').attr("title", "save");
                  $('.heart-tabInner-'+list_num).removeClass('color-blue').attr("title", "save");
                },
                error: function(){
                  console.log("failed");
                  clearAllPopups();
                  clearAllDialogs();
                }
              });
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    });
  });
  
  function refreshClickComparePopup(user){
    if (user == "guest") {
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-buyer-listings.php",
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              clearAllPopups();
              $('#compare-listings').dialog('destroy');
              var popup = getCompareListingsHTML(listings, "guest");
              $('#compare-listings').append(popup)
              $("#compare-listings").dialog({
                modal: true,
                height: '0',
                width: '0',
                autoOpen: false,
                dialogClass: 'compareListingsPopup'
              });
              $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
              
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close              
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    }
    else if(user == "buyer"){
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-buyer-listings.php",
            data: {
              "email": $("#email").text()
            },
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              clearAllPopups();
              $('#compare-listings').dialog('destroy');
              var popup = getCompareListingsHTML(listings, "buyer");
              $('#compare-listings').append(popup)
              $("#compare-listings").dialog({
                modal: true,
                height: '0',
                width: '0',
                autoOpen: false,
                dialogClass: 'compareListingsPopup'
              });
              $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
              
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
              $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    }
    else if (user == "agent") {
      $(".folder-section.compare-saved-listings-div").remove();
      $("#compare-listings").append(appendLoaderInBubble());
      var ajaxStop = 0;
      $(document).ajaxStop(function() {
        if(ajaxStop == 0){
          ajaxStop++;

          $.ajax({
            type: "POST",
            url: "/controllers/get-agent-listings.php",
            data: { "email": $("#email").text() },
            async:false,
            success: function(listings){
              listings = JSON.parse(listings);
              var popup = getAgentCompareListingsHTML(listings, "agent");
              $.ajax({
                type: "POST",
                url: "/controllers/get-agents-buyer-folders.php",
                data: {"agentID": $("#agentID").text() },
                async:false,
                success: function(listings){
                  listings = JSON.parse(listings);
                  clearAllPopups();
                  $('#compare-listings').dialog('destroy');
                  popup += getAgentCompareListingsHTML(listings, "agent-buyer");
                  $('#compare-listings').append(popup)
                  $("#compare-listings").dialog({
                    modal: true,
                    height: '0',
                    width: '0',
                    autoOpen: false,
                    dialogClass: 'compareListingsPopup'
                  });
                  $('#compare-listings').dialog( "option", "title", "Compare Listings" ).dialog('open');
                  
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".listingSection").toggle(); // Open folder
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".toggleFolder").addClass("toggleFolderClosed").removeClass("toggleFolder"); // Change class in oder to close
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".closedFolder").hide(); // hide the closed folder icon      
                  $(".folderDetails[data-user="+lastUserFolderOpen+"][data-folder="+lastFolderNameOpen+"]").parents().eq(1).find(".openFolder").show(); // show the open folder icon
                },
                error: function(){
                  console.log("failed");
                  clearAllPopups();
                  clearAllDialogs();
                }
              });
            },
            error: function(){
              console.log("failed");
              clearAllPopups();
              clearAllDialogs();
            }
          });
        }
      });
    }
  }

  function getCompareListingsHTML(listings, user) {
      if (user != "agent-buyer") {
        var html = "<div class='folder-section compare-saved-listings-div'>";
      }
      else{
        var html = "<div class='folder-section compare-saved-listings-div'>";
      }
      if (user != "agent-buyer"){
        html += "<span class='fa fa-times compare-listings-closer' title='close'></span>"
        + "<div class='clearfix grpelem' id='u16159-5'>";
          if (user == "agent") {
            html += "<p id='u16159-3'><span id='u16159'>Your Folders </span><span id='u16159-2'>click name to open</span></p>";
          }
          else{
            html += "<p id='u16159-3'><span id='u16159'>My Listing Folders </span><span id='u16159-2'>click name to open</span></p>";
          }
        html += "</div>";
      }
      html += "<div class='clearfix'></div>";
      if (listings.length > 0) {
        html += "<div id='folderSection' style='margin-top: 0;'>"
          html += getFolderListingsHTML(listings, user);
        + "</div>";
      }
      else{
        html += "<div>"
          + "<div id='noFolders' style='width: 250px; min-height: 200px; margin-left: 10px; font-size: 16pt;'><span>No Saved Folders</span></div>"
        + "</div>";
      }
    html += "</div>";

    return html;
  }

  function getAgentCompareListingsHTML(listings, user) {
    var html = "";
    if (user == "agent") {
      html += "<div class='folder-section compare-saved-listings-div'>"
        + "<span class='fa fa-times compare-listings-closer' title='close'></span>"
        + "<div class='clearfix grpelem' id='u16159-5'>"
          + "<p id='u16159-3'><span id='u16159'>Your Folders </span><span id='u16159-2'>click name to open</span></p>"
        + "</div>"
        + "<div class='clearfix'></div>";
    }

      if (listings.length > 0) {
        if (user == "agent") {
          html += "<div id='folderSection' style='margin-top: 0;'>";
        }
          html += getFolderListingsHTML(listings, user);
        if (user == "agent-buyer") {
          html += "</div>";
        }
      }
      else{
        html += "<div>"
          + "<div id='noFolders' class='Text-1 noSaveMessage'><span>No Saved Folders</span></div>"
        + "</div>";
      }
    if (user == "agent-buyer") {
      html += "</div>";
    }

    return html;
  }

  function getFolderListingsHTML(listings, user) {
    var html = "";
    if (listings.length > 0){
      listings.forEach(function(folder){
        html += "<div class='Text-1 clearfix u16157-235' style='margin-top: 0;'>"
          + "<div id='u16157-8'>"
            + "<div class='folderDetails' data-user='"+folder.user+"' data-folder='"+folder.name+"'>"
              + "<span id='u16157' class='toggleFolder closedFolder' style='cursor: pointer;'><i class='fa fa-folder-o'></i></span>"
              + "<span id='u16157' class='toggleFolder openFolder' style='cursor: pointer; display: none'><img className='block' id='u16154_img' src='/images/icon_folder_open_blue_1a.png' alt='' width='40' height='37'/></span>";
              if (user == "buyer" || user == "guest"){ html += "<span id='u16157-2' class='toggleFolder'><a style='cursor: pointer;'>&nbsp; " + folder.buyerName + " (" + folder.name + ")</a></span>"; }
              else if (user == "agent-buyer"){ html += "<span id='u16157-2' class='toggleFolder'><a style='cursor: pointer;'>&nbsp; " + folder.name + " (" + folder.folderName + ")</a></span>"; }
              else{ html += "<span id='u16157-2' class='toggleFolder'><a style='cursor: pointer;'>&nbsp; " + folder.name + "</a></span>"; }              
            html += "</div>"
            + "<div class='folderDetails'><span id='u16157-7'>"+folder.agent+"</span></div>"
            + "<div class='folderDetails'><span id='u16157-7'>Last updated: "+folder.last_update+"</span></div>";
            if(user == "agent-buyer"){ html += "<div class='folderDetails'><span id='u28562-3' class='emailFolder' data-user='"+folder.user+"' data-folder='"+folder.folderName+"' data-type='"+user+"'><a style='cursor: pointer;'>email listings</a></span></div>"; }
            else{ html += "<div class='folderDetails'><span id='u28562-3' class='emailFolder' data-user='"+folder.user+"' data-folder='"+folder.name+"' data-type='"+user+"'><a style='cursor: pointer;'>email listings</a></span></div>"; }
            if(user == "buyer" || user == 'agent-buyer'){ html += "<div class='folderDetails view-edit-buyer-formula' data-user='"+folder.user+"'><span id='u28562-3'><a style='cursor: pointer;'>view/edit buyer formula</a></span></div>"; }
          html += "</div>"
          + "<div class='listingSection'>";
            if(folder.listings.length > 0){
              html += "<div id='listingSection'>"
                + "<table id='tableHeader' style='margin-left: 10px; margin-bottom: 10px;'>"
                  + "<colgroup><col width='173'/><col width='60'/><col width='100'/><col width='40'/><col width='51'/><col width='80'/><col width='35'/><col width='39'/><col width='37'/><col width='45'/><col width='33'/><col width='290'/><col width='85'/><col width='85'/></colgroup>"
                  + "<tbody>"
                    + "<tr style='font-weight: bold;'>"
                      + "<th style='width: 173px;'>Address</th>"
                      + "<th style='width: 60px;'>Apt</th>"
                      + "<th style='width: 100px;'>Price</th>"
                      + "<th style='width: 40px;'>BRs</th>"
                      + "<th style='width: 51px;'>Baths</th>"
                      + "<th style='width: 80px;'>Charge/<br/>Month</th>"
                      + "<th style='width: 35px;'>Loc</th>"
                      + "<th style='width: 39px;'>Bldg</th>"
                      + "<th style='width: 37px;'>View</th>"
                      + "<th style='width: 45px;'>Space</th>"
                      + "<th style='width: 33px;'></th>"
                      + "<th style='width: 290px;'>Comments</th>"
                      + "<th style='width: 85px;'>Status</th>"
                      + "<th style='width: 85px;'>Date added</th>"
                    + "</tr>"
                  + "</tbody>"
                + "</table>"
                + "<div>"
                  + "<table id='fullTable' style='margin-left: 10px;'>"
                    + "<colgroup><col width='173'/><col width='60'/><col width='100'/><col width='40'/><col width='51'/><col width='80'/><col width='35'/><col width='39'/><col width='37'/><col width='45'/><col width='33'/><col width='290'/><col width='85'/><col width='80'/></colgroup>"
                    + "<tbody>";
                      folder.listings.forEach(function(listing){
                        html += "<tr>";
                          if(window.location.href.indexOf("/addressSearch.php") > -1){
                            html += "<td style='width: 173px;'><a href='/controllers/single-listing.php?list_numb="+listing.listing_num+"&MP=address' class='viewListingLink' data-link='/controllers/single-listing.php?list_numb="+listing.listing_num+"&MP=address'>"+listing.address+"</a></td>";
                          }
                          else{
                            html += "<td style='width: 173px;'><a href='/controllers/single-listing.php?list_numb="+listing.listing_num+"&MP=address' class='viewListingLink' data-link='/controllers/single-listing.php?list_numb="+listing.listing_num+"&MP=results'>"+listing.address+"</a></td>";
                          }
                          html += "<td style='width: 60px;'>"+listing.apt+"</td>"
                          + "<td style='width: 100px;'>$"+listing.price+"</td>"
                          + "<td style='text-align: center; width: 40px;'>"+listing.bed+"</td>"
                          + "<td style='text-align: center; width: 51px;'>"+listing.bath+"</td>"
                          + "<td style='width: 80px;'>$"+listing.monthly+"</td>"
                          + "<td style='text-align: center; width: 35px;'><img class='quality2' src='"+listing.loc+"' height='16' data-toggle='tooltip' title='"+listing.locTitle+"'/></td>"
                          + "<td style='text-align: center; width: 39px;'><img class='quality2' src='"+listing.bld+"' height='16' data-toggle='tooltip' title='"+listing.bldTitle+"'/></td>"
                          + "<td style='text-align: center; width: 37px;'><img class='quality2' src='"+listing.vws+"' height='16' data-toggle='tooltip' title='"+listing.vwsTitle+"'/></td>"
                          + "<td style='text-align: center; width: 45px;'><img class='quality2' src='"+listing.space+"' height='16' data-toggle='tooltip' title='"+listing.spaceTitle+"'/></td>"
                          + "<td style='width: 33px;'></td>"
                          + "<td style='width: 290px;'><a class='add-edit-comment' style='cursor: pointer' data-user='"+folder.user+"' data-comment='"+listing.comments+"' data-listnum='"+listing.listing_num+"' data-folder='"+listing.folder+"' data-type='"+user+"'>"+listing.comments+" &nbsp;<span id='u16157-13'><i class='fa fa-plus' data-toggle='tooltip' title='edit comment'></i></a></span></td>"
                          + "<td style='width: 85 + 'px;'>"+listing.status+"</td>"
                          + "<td style='width: 80 + 'px;'>"+listing.date+"</td>"
                          + "<td>";
                            if (user == "guest") {
                              html += "<span id='u22086-58'><a><i class='fa fa-times deleteSavedGuestListing' title='delete' data-folder='"+folder.name+"' data-listing='"+listing.listing_num+"'></i></a></span>";
                            }
                            else if (user == "buyer") {
                              html += "<span id='u22086-58'><a><i class='fa fa-times deleteSavedBuyerListing' title='delete'  data-folder='"+folder.name+"' data-listing='"+listing.listing_num+"'></i></a></span>";
                            }
                            else if (user == "agent") {
                              html += "<span id='u22086-58'><a><i class='fa fa-times deleteSavedAgentListing' title='delete' data-folder='"+folder.name+"' data-listing='"+listing.listing_num+"'></i></a></span>";
                            }
                            else if (user == "agent-buyer") {
                              html += "<span id='u22086-58'><a><i class='fa fa-times agentDeleteSavedBuyerListing' title='delete' data-buyer='"+folder.user+"' data-folder='"+listing.folder+"' data-listing='"+listing.listing_num+"'></i></a></span>";
                            }
                          html += "</td>"
                        + "</tr>";
                      });
                    html += "</tbody>"
                  + "</table>"
                + "</div>"
                + "<div id='u16157-212'><p id='u16157-211'>Gold bubble icons evaluate only the listings saved to this folder, and show how they rate compared to one another. When new listings are added, the gold bubbles will update.&nbsp; <span id='u16156-12'><i class='fa fa-question-circle'></i> </span><span id='u16156-13'><a class='moreInfo' href='/controllers/faq.php?section=manage'>more info</a></span></p></div>"
                + "<p>&nbsp;</p>"
              + "</div>";
            }
            else{
              html += "<div class='Text-1 noSaveMessage'><span>No Saved Listings</span></div>"
            }
          html += "</div>"
        + "</div>";
      });
    }
    else{
      html += "<div>"
        + "<div id='noFolders' class='Text-1 noSaveMessage' style='display: none;'><span>No Saved Folders</span></div>"
      + "</div>"
    }
    return html;
  }
  
  $('body').delegate('.viewListingLink','click',function(e){
    window.location = $(this).attr('data-link');
  });
  
  $('body').delegate('.emailFolder','click',function(e){
    var email = $(this).attr('data-user');
    var folder = $(this).attr('data-folder');
    var type = $(this).attr('data-type');
    var popup = getEmailFolderHTML(email, folder, type);
    $('#email-listing-folder').append(popup);
    $("#email-listing-folder").dialog({
      modal: true,
      height: '0',
      width: '0',
      autoOpen: false,
      dialogClass: 'emailFolderPopup',
      open: function(){ $("#overlay2").show(); },
      close: function(){ $("#overlay2").hide(); }
    });
    $('#email-listing-folder').dialog( "option", "title", "Email Folder" ).dialog('open');
  });
  
  $('body').delegate('.closeEmailFolderPopup','click',function(e){
    $('#email-listing-folder').empty();
    $('#email-listing-folder').dialog('close');
    $('#email-listing-folder').dialog('destroy');
  });
  
  $('body').delegate('.emailFolderSubmit','click',function(e){
    var email = $(this).attr('data-user');
    var folder = $(this).attr('data-folder');
    var type = $(this).attr('data-type');
    var recipient = $("#email-folder-popup #recipient").val();
    var comments = $("#email-folder-popup #comments").val();  
    if(type == "guest"){ var guestName = $("#email-folder-popup #guestName").val(); }
    else{ var guestName = ""; }
    if(type == 'agent-buyer'){ var agentSentBuyerFolder = "true"; }
    else{ var agentSentBuyerFolder = "false"; }
    
    $.get("/controllers/ajax.php", {
      emailFolder: 'true',
      agentSentBuyerFolder: agentSentBuyerFolder, 
      sender: email,
      folder: folder,
      guestName: guestName,
      recipient: recipient,
      comment: comments,
      success: function(){
        $('#email-listing-folder').empty();
        $('#email-listing-folder').dialog('close');
        $('#email-listing-folder').dialog('destroy');
      }
    });
  });
  
  function getEmailFolderHTML(user, folder, type) {
    var html = "";
    
    html += "<div id='email-folder-popup'>"
      + "<div class='text-popups clearfix grpelem closeEmailFolderPopup' id='closeEmailFolderPopup'>"
        + "<h4 style='cursor: pointer;'><i class='fa fa-times' title='close'></i></h4>"
      + "</div>"
      + "<h2 class='Subhead-2' id='u1330-2'>Email Folder</h2>"
      + "<h4 class='text-popups' id='u1330-3'>&nbsp;</h4>";
      if(type == "guest") {
        html += "<h4 class='text-popups padBottom' id='u1330-5'>Your name:</h4>"
        + "<h4 id='u1330-10'><span id='u1330-7'><input type='text' id='guestName' name='guestName' placeholder='Enter name'/></span></h4>"
        + "<h4 class='text-popups' id='u1330-3'>&nbsp;</h4>";
      }
      html += "<h4 class='text-popups padBottom' id='u1330-5'>Send folder to:</h4>"
      + "<h4 id='u1330-10'><span id='u1330-7'><input type='text' id='recipient' name='recipient' placeholder='Enter address'/></span></h4>"
      + "<h4 class='text-popups' id='u1330-6'>&nbsp;</h4>"
      + "<h4 class='text-popups padBottom' id='u1330-5'>Add a comment:</h4>"
      + "<textarea id='comments' placeholder='Comment (optional)'></textarea>"
      + "<h4 class='text-popups' id='u1330-22'>&nbsp;</h4>"
      + "<h4 class='text-popups emailFolderSubmit' data-user='"+user+"' data-folder='"+folder+"' data-type='"+type+"' id='u1330-21'><span id='u1330-19'>Send </span><span id='u1330-20'><i class='fa fa-chevron-right'></i></span></h4>"
    + "</div>"
    
    return html;
  }
  
  $('body').delegate('.add-edit-comment','click',function(e){
    var email = $(this).attr('data-user');
    var folder = $(this).attr('data-folder');
    var listing = $(this).attr('data-listnum');
    var comment = $(this).attr('data-comment');
    var type = $(this).attr('data-type');
    var popup = getAddEditCommentHTML(email, folder, listing, comment, type);
    $('#add-edit-listing-comment').append(popup);
    $("#add-edit-listing-comment").dialog({
      modal: true,
      height: '230px',
      width: '565px',
      autoOpen: false,
      dialogClass: 'addEditListingCommentPopup',
      open: function(){ $("#overlay2").show(); $(".addEditListingCommentPopup #comments").val(comment) },
      close: function(){ $("#overlay2").hide(); }
    });
    $('#add-edit-listing-comment').dialog( "option", "title", "Add/Edit Comment" ).dialog('open');
  
  });
  
  $('body').delegate('.close-add-edit-comment-popup','click',function(e){
    $('#add-edit-listing-comment').empty();
    $('#add-edit-listing-comment').dialog('close');
    $('#add-edit-listing-comment').dialog('destroy');
  });
  
  $('body').delegate('.update-listing-comment','click',function(e){
    e.preventDefault();
    var email = $(this).attr('data-user');
    var folder = $(this).attr('data-folder');
    var listing = $(this).attr('data-listnum');
    var comment = $(".addEditListingCommentPopup #comments").val();
    var type = $(this).attr('data-type');
    
    $.get("/controllers/ajax.php", {
      addComment: 'true', //Call the PHP function
      user: email,
      comments: comment,
      listing: listing,
      folder: folder,
      success: function(result){
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            $("#overlay2").hide();
            $('#add-edit-listing-comment').empty();
            $('#add-edit-listing-comment').dialog('close');
            $('#add-edit-listing-comment').dialog('destroy');
            refreshClickComparePopup(type);
          }
        });
      }
    });
  });
  
  function getAddEditCommentHTML(email, folder, listing, comment, type) {
    var html = "";
    
    html += "<div id='add-comment-form' title='Add Comment'>"
      + "<i id='closePopup2' class='fa fa-times close-add-edit-comment-popup' title='close'></i>"
      + "<form name='add-comment-form' id='add-comment-form'>"
        + "<p>Comment:</p>"
        + "<br/>"
        + "<textarea autofocus name='comments' id='comments' class='text ui-widget-content ui-corner-all' style='width: 515px; height: 130px'></textarea>"
        + "<br/><br/>"
        + "<button type='submit' name='editComment' id='editCommentSubmit' class='update-listing-comment' data-user='"+email+"' data-listnum='"+listing+"' data-folder='"+folder+"' data-type='"+type+"'>Submit <i id='arrow' class='fa fa-chevron-right'></i></button>"
      + "</form>"
    + "</div>";
     
    return html;
  }

  /*jqgrid sorters*/
  $('body').delegate('.jqgh_sorter','click',function(e){
    var target = $(e.target).closest('div.jqgh_sorter');
    var sortOrder = target.attr('data-order');
    var sortColumn = target.attr('data-column');

    if(sortColumn=='price'){ sortOrder = sortOrder=='asc' ? 'desc' :'asc'; }
    else{ sortOrder = sortOrder=='desc' ? 'asc' :'desc'; }

    $("#list").jqGrid('setGridParam', {
      sortname: sortColumn,
      sortorder: sortOrder
    }).trigger("reloadGrid");
  });
});

// SEARCH RESULTS AMENITIES MENU
var amenities = [];

$('.amenitiesmenu .amenity_icons').live('click',function(){
  var amenity = $(this).attr('rel');
  
  if(amenities[amenity] === true){
    amenities[amenity] = false; // this is the value submitted by the search function
    $('img.amenity-icons[rel='+amenity+']').attr('src','http://homepik.com/images/amenities/'+amenity+'.png').removeClass('selected'); // change the icon color
    $('img.amenity_icons[rel='+amenity+']').attr('src','http://homepik.com/images/amenities/'+amenity+'.png').removeClass('selected'); // change the icon color
    $.cookie(amenity, "false");
    setTimeout(function () { getListingCount(); }, 200);
  } else {
    amenities[amenity] = true; // this is the value submitted by the search function
    $('img.amenity-icons[rel='+amenity+']').attr('src','http://homepik.com/images/amenities/'+amenity+'b.png').addClass('selected'); // change the icon color
    $('img.amenity_icons[rel='+amenity+']').attr('src','http://homepik.com/images/amenities/'+amenity+'b.png').addClass('selected'); // change the icon color
    $.cookie(amenity, "true");
    setTimeout(function () { getListingCount(); }, 200);
  }
  return false;
});
$('.amenity_icons_text').live('click',function(){
  $(this).prev().trigger( "click" );
});

$(function() {
  state = {}; // used to pass states to BBQ plugin for browser history (see show_results() in head.php for example)

  // Reposition the dialog boxes for desktops when the browser is resized
  $(window).resize(function() {
    $("#ajax-box").dialog("option", "position", "center");
    $("#ajax-box2").dialog("option", "position", "center");

    var left = $(".ajaxbox").css("left");
    if(left){
      left.replace("px", '');
      if( parseInt(left) <= 0 ){ $(".ajaxbox").css("left", "0px"); }

      left = $(".ajaxbox2").css("left");
      if(left){
        left.replace("px", ' ');
        if( parseInt(left) <= 0 ){ $(".ajaxbox2").css("left", "0px"); }
      }
    }
  });

  // Reposition the dialog boxes for mobile devices when the device is rotated.
  window.addEventListener("orientationchange", function() {
    $("#ajax-box").dialog("option", "position", "center");
  }, false);

  $.fn.digits = function(){
    return this.each(function(){
      $(this).val( $(this).val().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,") );
    });
  }; // Add commas to the price box every three digits (e.g. 1,000,000)

  //Detect back button
    var detectBackOrForward = function(onBack, onForward) {
      hashHistory = [window.location.hash];
      historyLength = window.history.length;

      return function() {
        var hash = window.location.hash, length = window.history.length;
        if (hashHistory.length && historyLength == length) {
          if (hashHistory[hashHistory.length - 2] == hash) { //If you went backward
            hashHistory = hashHistory.slice(0, -1);
            onBack();
            var url = window.location.href;
            $('#ajax-box').dialog("close");
            $('.scrollwrapper2').addClass("unhide-div");
            var activeStatus = $.cookie('listActive');
            if (activeStatus == "yes"){
              $.cookie('listActive', "no");
              var activeStatus = $.cookie('listActive');
              $('#listings').tabs('select',0);
            }
            else if (url.indexOf("listings=0") > -1)
            {
            /*  $( "#results-tab" ).addClass( "ui-state-active ui-tabs-selected" );
              alert("It worked!"); */
            }
          }else { //If you went forward
            hashHistory.push(hash);
            onForward();
          }
        } else {
          hashHistory.push(hash);
          historyLength = length;
        }
      };
    };

    window.addEventListener("hashchange", detectBackOrForward(
    function() {
      // GO BACK TO THE SECOND CRITERIA PAGE IF ON SEARCH RESULTS
      URL = window.location.href;
      if(URL.indexOf("tabs=1") > -1 && URL.indexOf("listings") == -1){
        $.get("/controllers/ajax.php", {
          setPreviousPage: 'true', //Call the PHP function
          value: "secondCriteria",
          success: function(result){
            window.location.replace("/search.php");
            $(".continue.details").click();
          }
        });
      }
    },
    function() {
      // FORWARD FUNCTION
    }
  ));

  // PRINT
  $('.print').live('click',function(){
    window.print();
  });
});

// BBQ HISTORY PLUGIN
$(function(){
     // The "tab widgets" to handle.
    var tabs = $('.ui-tabs'),

    // This selector will be reused when selecting actual tab widget A elements.
    tab_a_selector = 'ul.ui-tabs-nav a';

    // Enable tabs on all tab widgets. The `event` property must be overridden so
    // that the tabs aren't changed on click, and any custom event name can be
    // specified. Note that if you define a callback for the 'select' event, it
    // will be executed for the selected tab whenever the hash changes.
    tabs.tabs({
      event: 'change'
    });

    // Define our own click handler for the tabs, overriding the default.
    tabs.find( tab_a_selector ).click(function(){
      var state = {},

      // Get the id of this tab widget.
      id = $(this).closest( '.ui-tabs' ).attr( 'id' ),

      // Get the index of this tab.
      idx = $(this).parent().prevAll().length;

      // Set the state!
      state[ id ] = idx;
      $.bbq.pushState( state );
    });

$('#searchbtn').click(function()
{
	getListingCount();
});
    // CONTINUE BUTTON and SHOW RESULTS
    $('#submit-search-tab a, #address-box label').click(function(){
      show_results(); // this function is in head.php.tpl
      return false;
    });

    $('.previous').click(function() {
      $.get("/controllers/ajax.php", {
        setPreviousPage: 'true', //Call the PHP function
        value: "firstCriteria",
        success: function(result){
          console.log("Previous Page Set to First Criteria");
        }
      });
    });

    // Bind an event to window.onhashchange that, when the history state changes,
    // iterates over all tab widgets, changing the current tab as necessary.
    $(window).bind( 'hashchange', function(e) {

      // Iterate over all tab widgets.
      tabs.each(function(){

        // Get the index for this tab widget from the hash, based on the
        // appropriate id property. In jQuery 1.4, you should use e.getState()
        // instead of $.bbq.getState(). The second, 'true' argument coerces the
        // string value to a number.
        var id = this.id,
        idx = e.getState( id, true ) || 0;

        // Select the appropriate tab for this tab widget by triggering the custom
        // event specified in the .tabs() init above (you could keep track of what
        // tab each widget is on using .data, and only select a tab if it has
        // changed).
        $(this).find( tab_a_selector ).eq( idx ).triggerHandler( 'change' );
        // Show or hide search results and set header position to fixed or absolute

        if(id == 'listings' && state['listings'] != undefined){
          if (idx == 0) { // if changing back to the search results tab
            $('#scrollwrapper').css('display','block');
            /* for IE */
            $('#new-thead .ui-jqgrid-htable').css('display','block');
            $('#new-thead .ui-jqgrid-htable').css('display','table');
            $('.ui-jqgrid .ui-jqgrid-hbox').css('border-bottom','1px solid #CCCCCC');
            $('.table-header').css('position','absolute');
            $('#address-box').addClass('results');
            $('#key').show();
            $('#results-tab a').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Results');
            window.scroll(0, scroll_to_y); // scroll_to_y is set in search.js, this returns them to how far they had scrolled in the search results before they had clicked to go to a listing page,
          } else if (idx != 0) { // if changing to a listing tab
            $('#scrollwrapper').css('display','none');
            $('#new-thead .ui-jqgrid-htable').css('display','none');
            $('.ui-jqgrid .ui-jqgrid-hbox').css('border-bottom','0px');
            $('.table-header').css('position','absolute');
            $('#results-tab a').html('<span style="font-size:13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Results</span>');
          }
        }
      });
    });

    $( "#listings span.ui-icon-close" ).live( "click", function() {
      $.cookie('listActive', "no");
      var active = $.cookie('listActive');
      var $listings = $('#listings').tabs();
      var tabs = $('#listings').tabs(),
      current = tabs.tabs('option', 'selected'),
      index = $( "li", $listings ).index( $( this ).parent() ),
      list_numb = $( this ).parent().find(".listing-info").attr('data-listing');
      removeStoredListing(list_numb);
      $('#listings').tabs( "remove", index );
      tab_overflow(); // Handle tabs overflowing past the line (located in search.js)
      if (current == index) {
        tabs.tabs('select', 0);
        var current = tabs.tabs('option', 'selected');
        if (current == 0) {
          var state = { };
          state [ 'listings' ] = 0;
          $.bbq.pushState( state );
          $('#scrollwrapper').css('display','block');
          $('#new-thead .ui-jqgrid-htable').css('display','table');
          $('.ui-jqgrid .ui-jqgrid-hbox').css('border-bottom','1px solid #CCCCCC');
          $('.table-header').css('position','absolute');
        }
      }
    });

    $("#validate").validate();
    if(touch == true){
      myScroll = new iScroll('scroller', {
        desktopCompatibility:true
      });
    }

    $(document).ready(function(){

      setTimeout(
      function()
      {
         $('#reg-or-not-popup').parent().addClass('reg_or_not_popup_block');
      }, 5000);
    });
});
