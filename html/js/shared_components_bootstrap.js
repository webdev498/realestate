/* Shared Components */
window.Header = React.createClass({
  getInitialState: function() {
    return{
      role: ""
    };
  },
  componentDidMount: function(){
    $.ajax({
      type: "POST",
		  url: "/controllers/get-sessions.php",
		  data: {"getRole": "true"},
		  success: function(data){
        var role = JSON.parse(data);
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
          ajaxStop++;
            this.setState({role: role});
          }
        }.bind(this));
		  }.bind(this),
		  error: function() {
        console.log("failed");
		  }
		});
  },
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    location.assign("/users/logout.php");
  },
  render: function () {
    return (
      <div className="container-fluid header-container">
        <div className="row">
          <div className="clip_frame colelem" id="u25521">
            <div className="col-md-3 col-sm-3 col-xs-5" id="first-section">
              {this.state.role == "user" || this.state.role == "agent" ? <a href='/controllers/menu.php'><img className="block" id="u25521_img_1" src="/images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a> : <a onClick={this.logout} style={{cursor: 'pointer'}}><img className="block" id="u25521_img_1" src="/images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a> }
            </div>
            <div className="col-md-4 col-sm-4 col-xs-7" id="second-section">
              <img className="block" id="u25521_img_2" src="/images/homepik_logo_bubbles_legend_7_part_2.png" alt=""/>
            </div>
            <div className="col-md-5 col-sm-5 col-xs-12" id="third-section">
              <img className="block" id="u25521_img_3" src="/images/homepik_logo_bubbles_legend_7_part_3.png" alt=""/>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

var AddBuyer = React.createClass({
  getInitialState: function() {
    return{
      agent_email: this.props.agent_email,
      firstname: "",
      lastname: "",
      email: "",
      phone: ""
    };
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  checkInput: function(){
    if( this.state.firstname != "" && this.state.lastname != "" && this.state.email != "" ){ return true; }
    else{ return false }
  },
  updatePhone: function(){
    var number = this.state.phone;
    var x = number.replace(/\D/g,'');

    if (x.length > 0) {
      if (x.length == 10 && !isNaN(x)){
        var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
        this.setState({ phone: y }); // Replace number with formatted number
      } else {
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons: {
            Ok: function(){
              $(this).dialog("destroy");
            }
          },
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('/controllers/messages.php #invalidBuyerPhone',function(){
          $('#ajax-box2').dialog( "option", "title", "Invalid Phone Number" ).dialog('open');
          $('#ajax-box2').parent().attr('rel', 'purple');
        });
      }
    }
  },
  addBuyer: function(event){
    event.preventDefault();
    var firstname = this.state.firstname,
    lastname = this.state.lastname,
    email = this.state.email,
    phone = this.state.phone,
    agentEmail = this.state.agent_email;
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var emailValid = emailReg.test(email);

    // CHECK IF ANY OF THE INPUTS ARE BLANK IF SO DISPLAY ERROR POPUP
    if(firstname == "" || lastname == "" || email == ""){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
        buttons: {
          Ok: function(){
            $(this).dialog("destroy");
          }
        },
        close: function() {
          $( this ).dialog( "destroy" );
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box2").dialog('close');
          });
        }
      });
      $('#ajax-box2').load('messages.php #addBuyerInfo',function(){
        $('#ajax-box2').dialog( "option", "title", "Buyer Information" ).dialog('open');
        $('#ajax-box2').parent().attr('rel', 'purple');
      });
    }
    // CHECK IF THE EMAIL IS VALID IF NOT DISPLAY ERROR POPUP
    else if (!emailValid) {
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
        buttons: {
          Ok: function(){
            $(this).dialog("destroy");
          }
        },
        close: function() {
          $( this ).dialog( "destroy" );
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box2").dialog('close');
          });
        }
      });
      $('#ajax-box2').load('/controllers/messages.php #invalidBuyerEmail',function(){
        $('#ajax-box2').dialog( "option", "title", "Invalid Email" ).dialog('open');
        $('#ajax-box2').parent().attr('rel', 'purple');
      });
    }
    // CHECK IF THE EMAIIL IS A BELLMARC EMAIL IF SO DISPLAY ERROR POPUP
    else if(email.indexOf("@bellmarc.com") != -1){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
        buttons: {
          Ok: function(){
            $(this).dialog("destroy");
          }
        },
        close: function() {
          $( this ).dialog( "destroy" );
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box2").dialog('close');
          });
        }
      });
      $('#ajax-box2').load('/controllers/messages.php #addBuyerEmail',function(){
        $('#ajax-box2').dialog( "option", "title", "Buyer Email" ).dialog('open');
      });
    }
    else{
      $.ajax({
        type: "POST",
        url: "check-buyer.php",
        data: {"email": email},
        success: function(data){
          console.log("success");
          var info = jQuery.parseJSON(data);

          // CHECK IF ACCOUNT EXISTS IF NOT CREATE ONE
          if(info == null){

            $.get("/controllers/ajax.php", {
              addBuyer: 'true',
              firstname: firstname,
              lastname: lastname,
              email: email,
              phone: phone,
              success: function(result){
                $("#ajax-box2").dialog({
                  modal: true,
                  height: 'auto',
                  width: '275px',
                  autoOpen: false,
                  dialogClass: 'ajaxbox confirmationMessage',
                  buttons: {
                    "View Buyer": function(){
                      window.location = "http://homepik.com/controllers/buyers.php?buyer="+email+"&fn="+firstname+"&ln="+lastname;
                    },
                    "Close": function(){                        
                      $( this ).dialog( "destroy" );
                    }
                  },
                  close: function() {
                    $( this ).dialog( "destroy" );
                  },
                  open: function(){
                    $(".ui-widget-overlay").bind("click", function(){
                      $("#ajax-box2").dialog('close');
                    });
                  }
                });
                $('#ajax-box2').load('/controllers/messages.php #addBuyerConfirmation',function(){
                  $('#ajax-box2').dialog( "option", "title", "Buyer Email" ).dialog('open');
                });
              }
            });
            
            {this.props.closeDialog()}
          }
          else{
            // IF ACCOUNT EXISTS CHECK IF THEY HAVE AT LEAST ONE AGENT
            // IF NOT ASSIGN AGENT AS ONE OF BUYER'S AGENTS
            if(info.P_agent == "" || info.P_agent == null){
              $.get("/controllers/ajax.php", {
                AddPrimary: 'true',
                email: email
              });

              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '265px',
                autoOpen: false,
                dialogClass: 'ajaxbox errorMessage',
                buttons: {
                  Ok: function(){
                    $(this).dialog("destroy");
                    window.location = "http://homepik.com/controllers/buyers.php";
                  }
                },
                close: function() {
                  $( this ).dialog( "destroy" );
                },
                open: function(){
                  $(".ui-widget-overlay").bind("click", function(){
                    $("#ajax-box2").dialog('close');
                  });
                }
              });
              $('#ajax-box2').load('/controllers/messages.php #addBuyerPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
                $('#ajax-box2').parent().attr('rel', 'purple');
              });

              $("#overlay").hide();
              {this.props.closeDialog()}
            }
            // IF THEY HAVE ONE AGENT CHECK IF THEY HAVE TWO AGENTS
            // IF ONLY HAVE ONE ASSIGN AGENT AS SECOND AGENT
            else if((info.P_agent != '' && info.P_agent != null) && (info.P_agent2 == '' || info.P_agent2 == null)){
              $.ajax({
                type: "POST",
                url: "check-agent.php",
                data: {"getEmail":"true", "id":info.P_agent},
                success: function(data){
                  var aEmail = JSON.parse(data);

                  // CHECK IF EMAIL OF AGENT TRYING TO ADD IS ALREADY ASSIGNED
                  // IF NOT ADDED AGENT, IF SO DISPLAY POPUP WITH MESSAGE
                  if(agentEmail != aEmail){
                    $.get("/controllers/ajax.php", {
                      AddPrimary2: 'true',
                      email: email
                    });

                    $("#ajax-box2").dialog({
                      modal: true,
                      height: 'auto',
                      width: '265px',
                      autoOpen: false,
                      dialogClass: 'ajaxbox errorMessage',
                      buttons: {
                        Ok: function(){
                          $(this).dialog("destroy");
                          window.location = "http://homepik.com/controllers/buyers.php";
                        }
                      },
                      close: function() {
                        $( this ).dialog( "destroy" );
                      },
                      open: function(){
                        $(".ui-widget-overlay").bind("click", function(){
                          $("#ajax-box2").dialog('close');
                        });
                      }
                    });
                    $('#ajax-box2').load('/controllers/messages.php #addBuyerPrimary2',function(){
                      $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
                      $('#ajax-box2').parent().attr('rel', 'purple');
                    });

                    $("#overlay").hide();
                    {this.props.closeDialog()}
                  }
                  else{
                    $("#ajax-box2").dialog({
                      modal: true,
                      height: 'auto',
                      width: '265px',
                      autoOpen: false,
                      dialogClass: 'ajaxbox errorMessage',
                      buttons: {
                        Ok: function(){
                          $(this).dialog("destroy");
                          window.location = "http://homepik.com/controllers/buyers.php";
                        }
                      },
                      close: function() {
                        $( this ).dialog( "destroy" );
                      },
                      open: function(){
                        $(".ui-widget-overlay").bind("click", function(){
                          $("#ajax-box2").dialog('close');
                        });
                      }
                    });
                    $('#ajax-box2').load('/controllers/messages.php #alreadyAgent',function(){
                      $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
                      $('#ajax-box2').parent().attr('rel', 'purple');
                    });

                    $("#overlay").hide();
                    {this.props.closeDialog()}
                  }
                }
              });
            }
            // IF THEY HAVE TWO AGENTS DISPLAY POPUP WITH ALERT
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '265px',
                autoOpen: false,
                dialogClass: 'ajaxbox errorMessage',
                buttons: {
                  Ok: function(){
                    $(this).dialog("destroy");
                    $('#ajax-box').dialog( "option", "title", "Add A New Buyer" ).dialog('destroy');
                  }
                },
                close: function() {
                  $( this ).dialog( "destroy" );
                },
                open: function(){
                  $(".ui-widget-overlay").bind("click", function(){
                    $("#ajax-box2").dialog('close');
                  });
                }
              });
              $('#ajax-box2').load('/controllers/messages.php #addBuyerExists',function(){
                $('#ajax-box2').dialog( "option", "title", "Buyer Exists" ).dialog('open');
                $('#ajax-box2').parent().attr('rel', 'purple');
              });

              $("#overlay").hide();
              {this.props.closeDialog()}
            }
          }
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
    }
  },
  closePopup: function(){
    $("#overlay").hide();
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div>
        <div id="addBuyerTop">
          <span id="addBuyerHeader" className="text-popups">Add New Buyer</span>
          <img src="/images/manage_buyers_normal.png" width="65" height="65" style={{float: "right"}}/>
          <h4 id="closePopup" onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
        </div>
        <div id="addBuyerBorder">
          <form onSubmit={this.addBuyer} autoComplete="off" data-bind="nextFieldOnEnter:true">
            <table cellPadding="2" cellSpacing="0" border="0">
              <tbody>
                <col width="250"/>
                <col width="350"/>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>First Name:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" value={this.state.firstname} autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.state.firstname != "" ? null : <strong id="firstnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Last Name:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formLastName" className="grade_desc input1" name="lastName" value={this.state.lastname} onChange={this.handleChange.bind(this, 'lastname')} />{this.state.lastname != "" ? null : <strong id="lastnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Email:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} />{this.state.email != "" ? null : <strong id="emailMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Phone:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></td>
                </tr>
                <tr><td></td></tr>
                <tr>
                  <td className="text-popups" colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong style={{color:'#D2008F', fontSize: 1.1 + 'em', float:"right", fontWeight: 600}}> {'All Required Fields Filled'}</strong> : <strong style={{color:'#D2008F', fontSize: 1.1 + 'em', float:"right", fontWeight: 600}}> {'\u002A Required Fields'}</strong> }</td>
                </tr>
                <tr>
                  <td className="text-popups" colSpan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <button type="submit" name="addBuyer" className="text-popups" id="addBuyerSubmit" onClick={this.addBuyer}>Submit<span id="arrow">&nbsp;{'\u276F'}</span></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    );
  }
});

var EditSearch = React.createClass({
  getInitialState: function() {
    return{
      buyer_email: this.props.buyer,
      searchName: this.props.searchName,
      name: "",
      bedrooms: "0",
      location: "1",
      building: "1",
      views: "1",
      bedroomArea: "1",
      livingArea: "1",
      minPrice: "100000",
      maxPrice: "2000000",
      minPriceStart: "1",
      maxPriceStart: "20",
      elevator: false,
      doorman: false,
      laundry: false,
      pets: false,
      fireplace: false,
      pool: false,
      garage: false,
      healthclub: false,
      outdoor: false,
      handicap: false,
      prewar: false,
      timeshare: false,
      newconstruction: false,
      n_all: false,
      n_fu: false,
      n_uws: false,
      n_ues: false,
      n_mw: false,
      n_me: false,
      n_v: false,
      n_d: false,
      coop: false,
      condo: false,
      house: false,
      condop: false,
      initial: this.props.initial
    };
  },
  componentDidMount: function(){

    var size_grades = ['','S','M','L','XL'];

    var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
    700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
    1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
    2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
    50000000, 99000000];

    this.getCriteria();

    $( "#bedrooms_slider" ).slider({
      range: 'max',
      min: 0,
      max: 8,
      value: this.state.bedrooms,
      slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider    */
        var bedrooms = ui.value;
        this.setState({bedrooms: bedrooms});
        var minBedroom = bedrooms;
        ui.sliderText =	'<span>'+ui.value+'</span>';
        $('#bedrooms_slider .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedrooms;
        $('#bedrooms_slider .ui-slider-handle').html('<span>'+ui.value+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $("#location_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.location,
      slide: function(event, ui) { /* Function to execute when sliding the location slider */
        var locationGrade = ui.value;
        this.setState({location: locationGrade});
        ui.sliderText =	"<span><img src='/images/blue-tick.png'/></span>";
        $('#location_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /*Function to execute when initializing slider */
        ui.value = this.state.location;
        $('#location_grade .ui-slider-handle').html("<span><img src='/images/blue-tick.png'/></span>");
      }.bind(this)
    }).addTouch();

    $("#building_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.building,
      slide: function(event, ui) { /* Function to execute when sliding the building slider */
        var buildingGrade = ui.value;
        this.setState({building: buildingGrade});
        ui.sliderText =	"<span><img src='/images/purple-tick.png'/></span>";
        $('#building_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.building;
        $('#building_grade .ui-slider-handle').html("<span><img src='/images/purple-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $("#views_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.views,
      slide: function(event, ui) { /* Function to execute when sliding the views slider */
        var viewsGrade = ui.value;
        this.setState({views: viewsGrade});
        ui.sliderText =	"<span><img src='/images/pink-tick.png'/></span>";
        $('#views_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.views;
        $('#views_grade .ui-slider-handle').html("<span><img src='/images/pink-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $("#bedroom_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.bedroomArea,
      slide: function(event, ui) { /* Function to execute when sliding the bedroom slider */
        var bedroomGrade = ui.value;
        this.setState({bedroomArea: bedroomGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('#bedroom_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedroomArea;
        $('#bedroom_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $("#living_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.livingArea,
      slide: function(event, ui) { /* Function to execute when sliding the living room slider */
        var livingGrade = ui.value;
        this.setState({livingArea: livingGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('#living_grade .ui-slider-handle').html(ui.sliderText);/* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) {
        /* Function to execute when initializing slider */
        ui.value = this.state.livingArea;
        $('#living_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

     $( "#price" ).slider({
      animate: true,
      range: 'true',
      min: 1,
      max: 32,
      values: [this.state.minPriceStart, this.state.maxPriceStart ],
      slide: function( event, ui ) { /* Function to execute when sliding the price slider */
        if ( ( ui.values[ 0 ] + 0 ) >= ui.values[ 1 ] ) { return false; } //Make sure both arrows have at least a .1 gap
        var min_value = ui.values[0], max_value = ui.values[1];
        this.setState({minPrice: prices[min_value]});
        this.setState({maxPrice: prices[max_value]});
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        $('#price .ui-slider-handle').first().html('<span>&#155;</span');
        $('#price .ui-slider-handle').first().attr("id", "min_price_slider");
        $('#price .ui-slider-handle').last().html('<span>&#139;</span');
        $('#price .ui-slider-handle').last().attr("id", "max_price_slider");
        return false;
      }
    }).addTouch();
  },
  getCriteria: function(){
    var size_grades = ['','S','M','L','XL'];

    var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
    700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
    1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
    2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
    50000000, 99000000];

    $.ajax({
      type: "POST",
      url: "get-search-criteria2.php",
      data: {"email": this.state.buyer_email, "name": this.props.searchName},
      success: function(data){
        var criteria = JSON.parse(data);
        if(criteria.bedrooms != ""){ this.setState({bedrooms: criteria.bedrooms}); }
        if(criteria.location_grade != ""){ this.setState({location: criteria.location_grade}); }
        if(criteria.building_grade != ""){ this.setState({building: criteria.building_grade}); }
        if(criteria.view_grade != ""){ this.setState({views: criteria.view_grade}); }
        if(criteria.bedroom_area != ""){ this.setState({bedroomArea: criteria.bedroom_area}); }
        if(criteria.living_area != ""){ this.setState({livingArea: criteria.living_area}); }
        if(criteria.min_price != ""){ this.setState({minPrice: criteria.min_price}); }
        if(criteria.max_price != ""){ this.setState({maxPrice: criteria.max_price}); }
        if(criteria.min_price_start != ""){ this.setState({minPriceStart: criteria.min_price_start}); }
        if(criteria.max_price_start != ""){ this.setState({maxPriceStart: criteria.max_price_start}); }
        if(criteria.amenities.indexOf("Elevator") > -1 || criteria.amenities.indexOf("elevator") > -1){ this.setState({elevator: true}); }else{ this.setState({elevator: false}); }
        if(criteria.amenities.indexOf("Doorman") > -1 || criteria.amenities.indexOf("doorman") > -1){ this.setState({doorman: true}); }else{ this.setState({doorman: false}); }
        if(criteria.amenities.indexOf("Laundry") > -1 || criteria.amenities.indexOf("laundry") > -1){ this.setState({laundry: true}); }else{ this.setState({laundry: false}); }
        if(criteria.amenities.indexOf("Pets") > -1 || criteria.amenities.indexOf("pets") > -1){ this.setState({pets: true}); }else{ this.setState({pets: false}); }
        if(criteria.amenities.indexOf("Fireplace") > -1 || criteria.amenities.indexOf("fireplace") > -1){ this.setState({fireplace: true}); }else{ this.setState({fireplace: false}); }
        if(criteria.amenities.indexOf("Pool") > -1 || criteria.amenities.indexOf("pool") > -1){ this.setState({pool: true}); }else{ this.setState({pool: false}); }
        if(criteria.amenities.indexOf("Garage") > -1 || criteria.amenities.indexOf("garage") > -1){ this.setState({garage: true}); }else{ this.setState({garage: false}); }
        if(criteria.amenities.indexOf("Healthclub") > -1 || criteria.amenities.indexOf("healthclub") > -1){ this.setState({healthclub: true}); }else{ this.setState({healthclub: false}); }
        if(criteria.amenities.indexOf("Outdoor") > -1 || criteria.amenities.indexOf("outdoor") > -1){ this.setState({outdoor: true}); }else{ this.setState({outdoor: false}); }
        if(criteria.amenities.indexOf("Wheelchair") > -1 || criteria.amenities.indexOf("wheelchair") > -1){ this.setState({handicap: true}); }else{ this.setState({handicap: false}); }
        if(criteria.amenities.indexOf("Prewar") > -1 || criteria.amenities.indexOf("prewar") > -1){ this.setState({prewar: true}); }else{ this.setState({prewar: false}); }
        if(criteria.amenities.indexOf("Timeshare") > -1 || criteria.amenities.indexOf("timeshare") > -1){ this.setState({timeshare: true}); }else{ this.setState({timeshare: false}); }
        if(criteria.amenities.indexOf("Newconstruction") > -1 || criteria.amenities.indexOf("newconstruction") > -1){ this.setState({newconstruction: true}); }else{ this.setState({newconstruction: false}); }
        if(criteria.neighborhoods.length == 7){ this.setState({n_all: true}); }else{ this.setState({n_all: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("North") > -1){ console.log("true"); this.setState({n_fu: true}); }else{ console.log("false"); this.setState({n_fu: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Westside") > -1){ this.setState({n_uws: true}); }else{ this.setState({n_uws: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Eastside") > -1){ this.setState({n_ues: true}); }else{ this.setState({n_ues: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Chelsea") > -1){ this.setState({n_mw: true}); }else{ this.setState({n_mw: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("SMG") > -1){ this.setState({n_me: true}); }else{ this.setState({n_me: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Village") > -1){ this.setState({n_v: true}); }else{ this.setState({n_v: false}); }
        if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Lower") > -1){ this.setState({n_d: true}); }else{ this.setState({n_d: false}); }
        if(criteria.prop_type.indexOf("Coop") > -1 || criteria.prop_type.indexOf("coop") > -1){ this.setState({coop: true}); }else{ this.setState({coop: false}); }
        if(criteria.prop_type.indexOf("Condo") > -1 || criteria.prop_type.indexOf("condo") > -1){ this.setState({condo: true}); }else{ this.setState({condo: false}); }
        if(criteria.prop_type.indexOf("House/Townhouse") > -1 || criteria.prop_type.indexOf("house/townhouse") > -1){ this.setState({house: true}); }else{ this.setState({house: false}); }
        if(criteria.prop_type.indexOf("Condop") > -1 || criteria.prop_type.indexOf("condop") > -1){ this.setState({condop: true}); }else{ this.setState({condop: false}); }

        $("#price").slider('values',0,criteria.min_price_start);
        $("#price").slider('values',1,criteria.max_price_start);
        $("#price").slider('refresh');
        $("#bedrooms_slider").find("span").html(criteria.bedrooms);
        $("#bedrooms_slider").slider('value',criteria.bedrooms);
        $("#bedrooms_slider" ).slider('refresh');
        $("#location_grade").slider('value',criteria.location_grade);
        $("#location_grade").slider('refresh');
        $("#building_grade").slider('value',criteria.building_grade);
        $("#building_grade").slider('refresh');
        $("#views_grade").slider('value',criteria.view_grade);
        $("#views_grade").slider('refresh');
        if(size_grades[criteria.bedroom_area] !== 'XL'){ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]); }else{ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]).addClass("smaller");}
        $("#bedroom_grade").slider('value',criteria.bedroom_area);
        $("#bedroom_grade").slider('refresh');
        if(size_grades[criteria.living_area] !== 'XL'){ $("#living_grade").find("span").html(size_grades[criteria.living_area]); }else{ $("#living_grade").find("span").html(size_grades[criteria.living_area]).addClass("smaller");}
        $("#living_grade").slider('value',criteria.living_area);
        $("#living_grade").slider('refresh');
      }.bind(this),
      error: function(){
        console.log("failed");
      }
    });
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  handleMinPriceChange: function(event){
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    this.setState({minPrice: price});
  },
  handleMaxPriceChange: function(event){
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    this.setState({maxPrice: price});
  },
  checkMinPrice: function(event){
    setTimeout(function(){
      var price = this.state.minPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];
      
      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }
      
      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons: {
            Ok: function(){
              $(this).dialog("destroy");
            }
          },
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });
        
        if(price < 100000){
          this.setState({minPrice: 100000});
          $("#price").slider('values',0,1);
        }
        else{
          this.setState({minPrice: prices[shift]});
          shift++;
          $("#price").slider('values',0,shift);
        }
      }
      else{
        index++;
        $("#price").slider('values',0,index);
      }    
    }.bind(this), 3000);
  },
  checkMaxPrice: function(event){
    setTimeout(function(){
      var price = this.state.maxPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];
      
      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }
            
      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons: {
            Ok: function(){
              $(this).dialog("destroy");
            }
          },
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });
        
        if(price > 99000000){
          this.setState({maxPrice: 99000000});
          $("#price").slider('values',1,32);
        }
        else{
          this.setState({maxPrice: prices[shift]});
          shift++;
          $("#price").slider('values',1,shift);
        }
      }
      else{
        index++;
        $("#price").slider('values',1,index);
      }
    }.bind(this), 3000);
  },
  handleAmenityChange: function(name, event){
    if(name == "elevator"){ this.setState({elevator: !this.state.elevator}); }
    else if(name == "doorman"){ this.setState({doorman: !this.state.doorman}); }
    else if(name == "laundry"){ this.setState({laundry: !this.state.laundry}); }
    else if(name == "pets"){ this.setState({pets: !this.state.pets}); }
    else if(name == "fireplace"){ this.setState({fireplace: !this.state.fireplace}); }
    else if(name == "pool"){ this.setState({pool: !this.state.pool}); }
    else if(name == "garage"){ this.setState({garage: !this.state.garage}); }
    else if(name == "healthclub"){ this.setState({healthclub: !this.state.healthclub}); }
    else if(name == "outdoor"){ this.setState({outdoor: !this.state.outdoor}); }
    else if(name == "handicap"){ this.setState({handicap: !this.state.handicap}); }
    else if(name == "prewar"){ this.setState({prewar: !this.state.prewar}); }
    else if(name == "timeshare"){ this.setState({timeshare: !this.state.timeshare}); }
    else if(name == "newconstruction"){ this.setState({newconstruction: !this.state.newconstruction}); }
    else{ /* Do nothing */ }
  },
  handleNeighborhoodChange: function(name, event){
    if(name == "n_all"){
      this.setState({n_all: !this.state.n_all});
      this.setState({n_fu: false});
      this.setState({n_uws: false});
      this.setState({n_ues: false});
      this.setState({n_mw: false});
      this.setState({n_me: false});
      this.setState({n_v: false});
      this.setState({n_d: false});
    }
    else if(name == "n_fu"){
      this.setState({n_fu: !this.state.n_fu});
      this.setState({n_all: false});
    }
    else if(name == "n_uws"){
      this.setState({n_uws: !this.state.n_uws});
      this.setState({n_all: false});
    }
    else if(name == "n_ues"){
      this.setState({n_ues: !this.state.n_ues});
      this.setState({n_all: false});
    }
    else if(name == "n_mw"){
      this.setState({n_mw: !this.state.n_mw});
      this.setState({n_all: false});
    }
    else if(name == "n_me"){
      this.setState({n_me: !this.state.n_me});
      this.setState({n_all: false});
    }
    else if(name == "n_v"){
      this.setState({n_v: !this.state.n_v});
      this.setState({n_all: false});
    }
    else if(name == "n_d"){
      this.setState({n_d: !this.state.n_d});
      this.setState({n_all: false});
    }
    else{
      // Do nothing
    }
  },
  handlePropertyChange: function(name, event){
    if(name == "coop"){ this.setState({coop: !this.state.coop}); }
    else if(name == "condo"){ this.setState({condo: !this.state.condo}); }
    else if(name == "house"){ this.setState({house: !this.state.house}); }
    else if(name == "condop"){ this.setState({condop: !this.state.condop}); }
    else{ /* Do nothing */ }
  },
  bedroomText: function(){
    if(this.state.bedrooms == 0){ return(<span>Studio</span>); }
    else if(this.state.bedrooms == 1){ return(<span>{this.state.bedrooms} bedroom</span>); }
    else{ return(<span>{this.state.bedrooms} bedrooms</span>); }
  },
  minPriceText: function(){
    if(this.state.minPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.minPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.minPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.minPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.minPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.minPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.minPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.minPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.minPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.minPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.minPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.minPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.minPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.minPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.minPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.minPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.minPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.minPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.minPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.minPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.minPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.minPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.minPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.minPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.minPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.minPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.minPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.minPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.minPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.minPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.minPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.minPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  maxPriceText: function(){
    if(this.state.maxPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.maxPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.maxPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.maxPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.maxPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.maxPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.maxPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.maxPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.maxPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.maxPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.maxPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.maxPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.maxPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.maxPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.maxPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.maxPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.maxPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.maxPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.maxPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.maxPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.maxPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.maxPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.maxPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.maxPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.maxPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.maxPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.maxPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.maxPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.maxPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.maxPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.maxPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.maxPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  locationText: function(){
    if(this.state.location == 1){ return(<div><p id="y25213-6">All locations</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 2){ return(<div><p id="y25213-6">New developing market.</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 3){ return(<div><p id="y25213-6">Emerging residential area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 4){ return(<div><p id="y25213-6">Active commercial street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 5){ return(<div><p id="y25213-6">Active commercial street / Residential Area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 6){ return(<div><p id="y25213-6">Quiet residential street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 7){ return(<div><p id="y25213-6">Residential  area/ neighborhood amenities</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 8){ return(<div><p id="y25213-6">Residential area/ near local park or river</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 9){ return(<div><p id="y25213-6">Residential area cose to major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 10){ return(<div><p id="y25213-6">Internationally renown/ near major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  buildingText: function(){
    if(this.state.building == 1){ return(<div><p id="y25213-14">All buildings</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 2){ return(<div><p id="y25213-14">Walkup in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 3){ return(<div><p id="y25213-14">Walkup in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 4){ return(<div><p id="y25213-14">Elevator building in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 5){ return(<div><p id="y25213-14">Elevator building in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 6){ return(<div><p id="y25213-14">Doorman building –no amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 7){ return(<div><p id="y25213-14">Doorman building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 8){ return(<div><p id="y25213-14">Full service building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 9){ return(<div><p id="y25213-14">Local renown or new construction with full services</p></div>); }
    else if(this.state.building == 10){ return(<div><p id="y25213-14">International renown</p><p id="y25213-15">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  viewText: function(){
    if(this.state.views == 1){ return(<div><p id="y25213-22">All properties</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 2){ return(<div><p id="y25213-22">Indirect light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 3){ return(<div><p id="y25213-22">Interior courtyard or area with moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 4){ return(<div><p id="y25213-22">Interior courtyard or area without view but bright</p></div>); }
    else if(this.state.views == 5){ return(<div><p id="y25213-22">Street view or interior garden moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 6){ return(<div><p id="y25213-22">Street view or interior garden bright</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 7){ return(<div><p id="y25213-22">Rooftop views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 8){ return(<div><p id="y25213-22">Cityscape views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 9){ return(<div><p id="y25213-22">Cityscape and river or park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 10){ return(<div><p id="y25213-22">Cityscape and Central Park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  bedroomAreaText: function(){
    if(this.state.bedroomArea == 1){ return(<div><p id="y25213-30">Any bedroom size is okay</p><p id="y25213-31">&nbsp;</p></div>); }
    else if(this.state.bedroomArea == 2){ return(<div><p id="y25213-30">A medium-sized master bedroom or larger: at least 13 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 3){ return(<div><p id="y25213-30">A large-sized master bedroom or larger: at least 16 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 4){ return(<div><p id="y25213-30">An extra-large master bedroom or larger: at least 19 ft by 11 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  livingAreaText: function(){
    if(this.state.livingArea == 1){ return(<div><p id="y25213-38">Any living room size is okay</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.livingArea == 2){ return(<div><p id="y25213-38">A medium-sized living room or larger: at least 18 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 3){ return(<div><p id="y25213-38">A large-sized living room or larger: at least 22 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 4){ return(<div><p id="y25213-38">A extra-large living room or larger: at least 27 ft by 12 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  minPriceInput: function(){
    var val = this.state.minPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  maxPriceInput: function(){
    var val = this.state.maxPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  getPrices: function(){
    var prices = [{label:"100,000", value:"100000"},{label:"200,000", value:"200000"}, {label:"300,000", value:"300000"}, {label:"400,000", value:"400000"},
      {label:"500,000", value:"500000"}, {label:"600,000", value:"600000"}, {label:"700,000", value:"700000"}, {label:"800,000", value:"800000"},
      {label:"900,000", value:"900000"}, {label:"1,000,000", value:"1000000"}, {label:"1,100,000", value:"1100000"}, {label:"1,200,000", value:"1200000"},
      {label:"1,300,000", value:"1300000"}, {label:"1,400,000", value:"1400000"},{label:"1,500,000", value:"1500000"}, {label:"1,600,000", value:"1600000"},
      {label:"1,700,000", value:"1700000"}, {label:"1,800,000", value:"1800000"}, {label:"1,900,000", value:"1900000"}, {label:"2,000,000", value:"2000000"},
      {label:"2,250,000", value:"2250000"}, {label:"2,500,000", value:"2500000"}, {label:"2,750,000", value:"2750000"}, {label:"3,000,000", value:"3000000"},
      {label:"4,000,000", value:"4000000"}, {label:"6,000,000", value:"6000000"}, {label:"8,000,000", value:"8000000"}, {label:"12,000,000", value:"12000000"},
      {label:"25,000,000", value:"25000000"}, {label:"50,000,000", value:"50000000"}, {label:"99,000,000", value:"99000000"}];
      
    var pricesCompare = ["100000","200000", "300000", "400000", "500000", "600000", "700000", "800000", "900000", "1000000", "1100000", "1200000",
      "1300000", "1400000", "1500000", "1600000", "1700000", "1800000", "1900000", "2000000", "2250000", "2500000", "2750000", "3000000", "3500000",
      "4000000", "6000000", "8000000", "12000000", "25000000", "50000000", "99000000"];
    
    $( ".priceInput" ).autocomplete({
      source: prices,
      select: function(e, ui){
        if(e.target.name == "minPrice"){
          this.setState({minPrice: ui.item.value});
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $("#price").slider('values',0,index);  
        }
        if(e.target.name == "maxPrice"){
          this.setState({maxPrice: ui.item.value});            
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $("#price").slider('values',1,index); 
        }
      }.bind(this)
    });
  },
  closeDialog: function(){
    $dialog.dialog('close');
  },
  saveChanges: function(){
    var email = this.state.buyer_email;
    var searchName = this.state.searchName;
    var oldName = this.state.searchName;
    var min_price = this.state.minPrice;
    var max_price = this.state.maxPrice;
    var bedrooms = this.state.bedrooms;
    var location_grade = this.state.location;
    var building_grade = this.state.building;
    var views_grade = this.state.views;
    var bedroom_area = this.state.bedroomArea;
    var living_area = this.state.livingArea;
    var min_floor = "";
    var neighborhoods = [];
    var prop_type = [];
    var amen = [];

    if(this.state.n_all == true){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"];}
    if(this.state.n_fu == true){ neighborhoods.push("North");}
    if(this.state.n_uws == true){ neighborhoods.push("Westside");}
    if(this.state.n_ues == true){ neighborhoods.push("Eastside");}
    if(this.state.n_mw == true){ neighborhoods.push("Chelsea");}
    if(this.state.n_me == true){ neighborhoods.push("SMG");}
    if(this.state.n_v == true){ neighborhoods.push("Village");}
    if(this.state.n_d == true){ neighborhoods.push("Lower");}

    if(neighborhoods.length == 0){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"]; }

    if(this.state.coop == true){ prop_type.push("1");}
    if(this.state.condo == true){ prop_type.push("2");}
    if(this.state.house == true){ prop_type.push("4");}
    if(this.state.condop == true){ prop_type.push("5");}

    if(prop_type.length == 0){ prop_type = ["1", "2", "4", "5"]; }

    if(this.state.elevator == true){ amen.push("Elevator");}
    if(this.state.doorman == true){ amen.push("Doorman");}
    if(this.state.laundry == true){ amen.push("Laundry");}
    if(this.state.pets == true){ amen.push("Pets");}
    if(this.state.fireplace == true){ amen.push("Fireplace");}
    if(this.state.pool == true){ amen.push("Pool");}
    if(this.state.garage == true){ amen.push("Garage");}
    if(this.state.healthclub == true){ amen.push("Healthclub");}
    if(this.state.outdoor == true){ amen.push("Outdoor");}
    if(this.state.handicap == true){ amen.push("Wheelchair");}
    if(this.state.prewar == true){ amen.push("Prewar");}
    if(this.state.timeshare == true){ amen.push("Timeshare");}
    if(this.state.newconstruction == true){ amen.push("Newconstruction");}

    if(Number(min_price) >= Number(max_price)){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox priceRangePopup',
        buttons: {
          Ok: function(){
            $(this).dialog("destroy");
          }
        },
        close: function() {
          $( this ).dialog( "destroy" );
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box2").dialog('close');
          });
        }
      });
      $('#ajax-box2').load('/controllers/messages.php #priceRange',function(){
        $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
      });
    }
    else{
      $.ajax({
        type: "POST",
        url: "save-criteria.php",
        data: {"email":email, "oldname":oldName, "name":searchName, "location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : neighborhoods, "prop_type":prop_type, "amenities":amen},
        success: function(data){
          $.get("/controllers/ajax.php", {
            editFormulaEmail: 'true', //Call the PHP function
            email: email,
            success: function(result){
            console.log("email sent");
            }
          });

          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              {this.props.closeDialog()}
            }
          }.bind(this));
        }.bind(this),
        error: function() {
          console.log("failed");
        }
      });
    }
  },
  cancel: function(){
    {this.props.closeDialog()}
  },
  updateData: function(){
    if(this.props.searchName != this.state.name){
      this.setState({name: this.props.searchName});
      this.getCriteria();
    }
  },
  render: function(){
    return(
      <div className="clearfix" id="editPage">
        {this.updateData()}
        <div className="clearfix grpelem" id="y25246-3">
          <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="y25241-3">
         <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="py2678">
          <div className="clearfix grpelem" id="py25217-6">
            <div className="clearfix colelem" id="y25217-6">
              <p id="y25217-4"><span id="y25217">Search name: </span><span id="y25217-2">{this.props.searchName}&nbsp; </span><span id="y25217-3"><a style={{cursor: "pointer"}} onClick = {this.cancel}>Cancel</a></span></p>
            </div>
            <div className="clearfix colelem" id="py25231-53">
              <div className="clearfix grpelem" id="y25231-53">
                <p id="y25231-2">Minimum Bedroom</p>
                <p id="y25231-3">&nbsp;</p>
                <p id="y25231-4">&nbsp;</p>
                <p id="y25231-6">{this.bedroomText()}</p>
                <p id="y25231-7">&nbsp;</p>
                <p id="y25231-8">&nbsp;</p>
                <p id="y25231-10">Price Range</p>
                <p id="y25231-11">&nbsp;</p>
                <p id="y25231-12">&nbsp;</p>
                <p id="y25231-14">$ {this.minPriceText()} to {this.maxPriceText()}</p>
                <p id="y25231-16">Or, fill in a range: $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to $&nbsp;&nbsp;</p>
                <p id="y25231-17">&nbsp;</p>
                <p id="y25231-18">&nbsp;</p>
                <p id="y25231-20">Neighborhoods</p>
                <p id="y25231-23"><span id="y25231-21">{this.state.n_all ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}} onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> }</span><span id="y25231-22">&nbsp;&nbsp; All of Manhattan</span></p>
                <p id="y25231-26"><span id="y25231-24">{this.state.n_fu ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> }</span><span id="y25231-25">&nbsp;&nbsp; Far Uptown</span></p>
                <p id="y25231-29"><span id="y25231-27">{this.state.n_uws ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> }</span><span id="y25231-28">&nbsp;&nbsp; Upper West Side</span></p>
                <p id="y25231-32"><span id="y25231-30">{this.state.n_ues ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> }</span><span id="y25231-31">&nbsp;&nbsp; Upper East Side</span></p>
                <p id="y25231-36">&nbsp;</p>
                <p id="y25231-37">&nbsp;</p>
                <p id="y25231-39">Property Type</p>
                <p id="y25231-42"><span id="y25231-40">{this.state.coop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> }</span><span id="y25231-41">&nbsp;&nbsp; Coop</span></p>
                <p id="y25231-45"><span id="y25231-43">{this.state.condo ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> }</span><span id="y25231-44">&nbsp;&nbsp; Condo</span></p>
                <p id="y25231-48"><span id="y25231-46">{this.state.house ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> }</span><span id="y25231-47">&nbsp;&nbsp; House/Townhouse</span></p>
                <p id="y25231-51"><span id="y25231-49">{this.state.condop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> }</span><span id="y25231-50">&nbsp;&nbsp; Condop</span></p>
              </div>
              <div className="clearfix grpelem" id="y25224-14">
                <p id="y25224-3"><span id="y25224">{this.state.n_mw ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> }</span><span id="y25224-2">&nbsp;&nbsp; Midtown West</span></p>
                <p id="y25224-6"><span id="y25224-4">{this.state.n_me ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> }</span><span id="y25224-5">&nbsp;&nbsp; Midtown East</span></p>
                <p id="y25224-9"><span id="y25224-7">{this.state.n_v ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> }</span><span id="y25224-8">&nbsp;&nbsp; East/West Village</span></p>
                <p id="y25224-12"><span id="y25224-10">{this.state.n_d ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> }</span><span id="y25224-11">&nbsp;&nbsp; Downtown</span></p>
              </div>
              <div id="bedrooms_slider"></div>
              <div id="bedroomSliderText"><span className="leftNum">0</span><span className="rightNum">8</span></div>
              <div className="clip_frame grpelem" id="y25220">
                <img className="block" id="y25220_img" src="/images/slider_purple_short.png" alt="" width="455" height="37"/>
              </div>
              <div id="price"></div>
              <div id="priceSliderText"><span className="leftNum">0</span><span className="rightNum">99</span></div>
              <div className="clip_frame grpelem" id="y25211">
                <img className="block" id="y25211_img" src="/images/slider_blue_short.png" alt="" width="455" height="37"/>
              </div>
              <div className="clearfix grpelem" id="y25385">
                <div className="grpelem" id="y25398"><input type="text" id="minPrice" className="priceInput" name="minPrice" value={this.state.minPrice} onBlur={this.checkMinPrice} onChange={this.handleMinPriceChange} onFocus={this.getPrices}/></div>
                <div className="grpelem" id="y25386"><input type="text" id="maxPrice" className="priceInput" name="maxPrice" value={this.state.maxPrice} onBlur={this.checkMaxPrice} onChange={this.handleMaxPriceChange} onFocus={this.getPrices}/></div>
              </div>
            </div>
          </div>
          <div className="grpelem" id="y25216"></div>
          <div className="clearfix grpelem" id="py25213-56">
            <div className="clearfix grpelem" id="y25213-56">
              <p id="y25213-2">Minimum Location Grade</p>
              <p id="y25213-3">&nbsp;</p>
              <p id="y25213-4">&nbsp;</p>
              {this.locationText()}
              <p id="y25213-8">&nbsp;</p>
              <p id="y25213-10">Minimum Building Grade</p>
              <p id="y25213-11">&nbsp;</p>
              <p id="y25213-12">&nbsp;</p>
              {this.buildingText()}
              <p id="y25213-16">&nbsp;</p>
              <p id="y25213-18">Minimum view Grade</p>
              <p id="y25213-19">&nbsp;</p>
              <p id="y25213-20">&nbsp;</p>
              {this.viewText()}
              <p id="y25213-24">&nbsp;</p>
              <p id="y25213-26">Master Bedroom</p>
              <p id="y25213-27">&nbsp;</p>
              <p id="y25213-28">&nbsp;</p>
              {this.bedroomAreaText()}
              <p id="y25213-32">&nbsp;</p>
              <p id="y25213-34">Living Room</p>
              <p id="y25213-35">&nbsp;</p>
              <p id="y25213-36">&nbsp;</p>
              {this.livingAreaText()}
              <p id="y25213-40">&nbsp;</p>
              <p id="y25213-44"><span id="y25213-41">Amenities</span><span id="y25213-42">&nbsp; </span><span id="y25213-43">Click icons to select amenities</span></p>
              <p id="y25213-45">&nbsp;</p>
              <p id="y25213-46">&nbsp;</p>
              <p id="y25213-47">&nbsp;</p>
              <p id="y25213-48">&nbsp;</p>
              <p id="y25213-49">&nbsp;</p>
              <p id="y25213-50">&nbsp;</p>
              <p id="y25213-51">&nbsp;</p>
              <p id="y25213-54"><span id="y25213-52" onClick={this.saveChanges} style={{cursor: "pointer"}}>Save changes and exit </span><span id="y25213-53"><i className="fa fa-chevron-right"></i></span></p>
            </div>
            <div id="location_grade"></div>
            <div className="clip_frame grpelem" id="y25229">
              <img className="block" id="y25229_img" src="/images/locationslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="building_grade"></div>
            <div className="clip_frame grpelem" id="y25214">
              <img className="block" id="y25214_img" src="/images/buildingslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="views_grade"></div>
            <div className="clip_frame grpelem" id="y25227">
              <img className="block" id="y25227_img" src="/images/viewsslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="bedroom_grade"></div>
            <div id="bedroomAreaSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25222">
              <img className="block" id="y25222_img" src="/images/slider_orange_short.png" alt="" width="455" height="37"/>
            </div>
            <div id="living_grade"></div>
            <div id="livingRoomSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25225">
              <img className="block" id="y25225_img" src="/images/slider_green_short.png" alt="" width="455" height="37"/>
            </div>
            <div className="clearfix grpelem" id="y25374">
              <div className="clip_frame grpelem" id="y25208">
                <table id="amenities">
                  <tbody>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'elevator')}>{this.state.elevator ? <img className="amenity" src="/images/amenities/elevatorb.png" alt="elevator" width="30" height="30"/> : <img className="amenity" src="/images/amenities/elevator.png" alt="elevator" width="30" height="30" />}<div className="y25210-11">Elevator</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'doorman')}>{this.state.doorman ? <img className="amenity" src="/images/amenities/doormanb.png" alt="doorman" width="30" height="30"/> : <img className="amenity" src="/images/amenities/doorman.png" alt="doorman" width="30" height="30" />}<div className="y25210-11">Doorman</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'laundry')}>{this.state.laundry ? <img className="amenity" src="/images/amenities/laundryb.png" alt="laundry" width="30" height="30"/> : <img className="amenity" src="/images/amenities/laundry.png" alt="laundry" width="30" height="30" />}<div className="y25210-11">Laundry</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'pets')}>{this.state.pets ? <img className="amenity" src="/images/amenities/petsb.png" alt="pets" width="30" height="30"/> : <img className="amenity" src="/images/amenities/pets.png" alt="pets" width="30" height="30"/>}<div className="y25210-11">Pets</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'fireplace')}>{this.state.fireplace ? <img className="amenity" src="/images/amenities/fireplaceb.png" alt="fireplace" width="30" height="30"/> : <img className="amenity" src="/images/amenities/fireplace.png" alt="fireplace" width="30" height="30"/>}<div className="y25210-11">Fireplace</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'prewar')}>{this.state.prewar ? <img className="amenity" src="/images/amenities/prewar2b.png" alt="prewar" width="30" height="30" /> : <img className="amenity" src="/images/amenities/prewar2.png" alt="prewar" width="30" height="30" />}<div className="y25210-11">Prewar</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'timeshare')}>{this.state.timeshare ? <img className="amenity" src="/images/amenities/timeshareb.png" alt="timeshare" width="30" height="30"/> : <img className="amenity" src="/images/amenities/timeshare.png" alt="timeshare" width="30" height="30" />}<div className="y25210-11">Time shares</div></td>
                    </tr>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'pool')}>{this.state.pool ? <img className="amenity" src="/images/amenities/poolb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/pool.png" alt="pool" width="30" height="30" />}<div className="y25210-11">Pool</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'garage')}>{this.state.garage ? <img className="amenity" src="/images/amenities/garageb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/garage.png" alt="garage" width="30" height="30" />}<div className="y25210-11">Garage</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'healthclub')}>{this.state.healthclub ? <img className="amenity" src="/images/amenities/healthclubb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/healthclub.png" alt="healthclub" width="30" height="30"/>}<div className="y25210-11">Health club</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'outdoor')}>{this.state.outdoor ? <img className="amenity" src="/images/amenities/outdoorb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/outdoor.png" alt="outdoor" width="30" height="30" />}<div className="y25210-11">Outdoor space</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'handicap')}>{this.state.handicap ? <img className="amenity" src="/images/amenities/wheelchairb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/wheelchair.png" alt="wheelchair" width="30" height="30" />}<div className="y25210-11">Handicap accessible</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'newconstruction')}>{this.state.newconstruction ? <img className="amenity" src="/images/amenities/newconstructionb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/newconstruction.png" alt="newconstruction" width="30" height="30" />}<div className="y25210-11">New construction</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div id="amenitiesSeparator"></div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

var ChooseFormula = React.createClass({
	getInitialState: function() {
    return{
      buyer_email: this.props.email,
      buyer_formulas: [],
      num_formulas: "",
      formula_selected: "",
      formulaBeingEdited: ""
    };
  },
	componentDidMount: function(){
	  this.getFormulas();
	},
	handleClick: function(name, event){
	  this.setState({formula_selected: name});
	},
	getFormulas: function(){
	  $.ajax({
      type: "POST",
      url: "/controllers/get-buyer-formulas.php",
      data: {"buyer": this.state.buyer_email},
      success: function(data){
        console.log("success");
        var formulas = JSON.parse(data);
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            this.setState({buyer_formulas: formulas});
            this.setState({num_formulas: formulas.length});
            if(formulas.length > 0){
              this.setState({formula_selected: formulas[0]['name']});
            }
          }
        }.bind(this));
      }.bind(this),
      error: function(){
        console.log("failed");
      }
	  });
	},
	closePopup: function(){
	  $("#overlay").hide();
	  {this.props.closeDialog()}
	},
  editFormula: function(formula){
    this.setState({formulaBeingEdited: formula});
    var initial = true;
    var $dialog =  $("#ajax-box2").dialog({
      width: 1115,
      dialogClass: "editFormula",
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box2'));
        var div = document.createElement('div');
        div.id = 'ajax-box2';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box2").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      this.getFormulas();
      $dialog.dialog('close');
    }.bind(this)

    ReactDOM.render(<EditSearch closeDialog={closeDialog} buyer={this.state.buyer_email} searchName={formula} initial={initial}/>, $dialog[0]);
  },
	conductSearch: function(){
	  var buyer = this.state.buyer_email;
	  var searchName = this.state.formula_selected;

	  $.ajax({
      type: "POST",
      url: "/controllers/check-criteria.php",
      data: {"email": buyer, "name": searchName},
      success: function(data){
        console.log("success");

        var num = data[data.length -1];

        if(num == 0){
          $("#ajax-box2").dialog({
            modal: true,
            height: 'auto',
            width: '245px',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage',
            buttons : {
              Ok: function(){
                $(this).dialog("close");
              }
            },
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box2").dialog('close');
              });
            }
          });
          $('#ajax-box2').load('/controllers/messages.php #no-formula',function(){
            $('#ajax-box2').dialog( "option", "title", "No Formula" ).attr('rel','yourbuyers').dialog('open');
          });
        }
        else{

          $.get("/controllers/ajax.php", {
            saveBuyer: 'true', //Call the PHP function
            email: buyer, //Put variables into AJAX variables
            success: function(result){
              console.log("buyer saved");
            }
          });

          while(searchName.indexOf(" ") != -1){
            searchName = searchName.replace(" ", "_");
          }
          
          $.cookie("previousPage", window.location.href);
          // Set previous page for use on first criteria back button
          $.get("ajax.php", {
            setPreviousPage:"true",
            page: window.location.href,
            success: function(result){
              console.log("Page Set");
              $(document).ajaxStop(function() {
                window.location = "http://homepik.com/search.php#buyerFormula?"+buyer+"?"+searchName;
              });
            }
          });          
        }
      },
      error: function(){
        console.log("failed");
      }
	  });
	},
	render: function(){
	  var formulas = this.state.buyer_formulas.map(function(formula){
      return(
        <div>
          <table id="formulaTable" key={formula.id}>
            <colgroup>
              <col width="250"/>
              <col width="500"/>
            </colgroup>
            <tbody>
              <tr>
                <td><p id="x22476-34"><span id="x22476-5" onClick={this.editFormula.bind(this, formula.name)}>{formula.name}</span></p></td>
                <td><span id="x22476-6" style={{float: "right"}}>{this.state.formula_selected == formula.name ? <i className="fa fa-check checkmark"></i> : <i className="fa fa-circle-thin thin-circle" onClick={this.handleClick.bind(this, formula.name)}></i>}</span></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-10">Price</p></td>
                <td><p className="Text-1" id="x22477-6">{formula.min_price} to {formula.max_price}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-12">Minimum bedrooms</p></td>
                <td><p className="Text-1" id="x22477-8">{formula.bedrooms}</p></td>
              </tr>
              <tr>
                <td valign="top"><p className="Text-1" id="x22476-14">Neighborhoods</p></td>
                <td><p className="Text-1" id="x22477-10">{formula.neighborhoods}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-16">Property types</p></td>
                <td><p className="Text-1" id="x22477-12">{formula.properties}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-18">Minimum location grade</p></td>
                <td><p className="Text-1" id="x22477-14">{formula.location_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-20">Minimum building grade</p></td>
                <td><p className="Text-1" id="x22477-16">{formula.building_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-22">Minimum view grade</p></td>
                <td><p className="Text-1" id="x22477-18">{formula.view_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-24">Master bedroom</p></td>
                <td><p className="Text-1" id="x22477-20">{formula.bedroom_area}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-26">Living room</p></td>
                <td><p className="Text-1" id="x22477-22">{formula.living_room}</p></td>
              </tr>
              <tr>
                <td valign="top"><p className="Text-1" id="x22476-28">Amenities</p></td>
                <td><p className="Text-1" id="x22477-24">{formula.amenities}</p></td>
              </tr>
            </tbody>
          </table>
          <p className="Text-1">{'\u00A0'}</p>
          <p className="Text-1">{'\u00A0'}</p>
        </div>
      );
	  }.bind(this));
	  return(
      <div>
        <div className="clearfix grpelem" id="px22476-84">
        {this.state.num_formulas > 0 ?
          <div className="clearfix grpelem" id="x22476-84">
            <p className="Text-1">&nbsp;</p>
            <p className="Text-1" id="x22476-4"><span id="x22476-2">Choose a buying formula</span><span>&nbsp;&nbsp; </span><span id="u7137-4">click on any </span><span id="u7137-5">blue formula name</span><span id="u7137-6"> to edit the search</span><span id="x22476-3"></span></p>
            {formulas}
            <button type="submit" name="search" id="searchFormulaSubmit" onClick={this.conductSearch}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
            <p className="Text-1">&nbsp;</p>
            <p className="Text-1">&nbsp;</p>
          </div>
        :
          <div className="clearfix grpelem" id="x22476-84">
            <p className="Text-1">&nbsp;</p>
            <p className="Text-1" id="x22476-4"><span id="x22476-2">Choose a buying formula</span><span id="x22476-3"></span></p>
            <p>No formulas saved. Please go to new search to create a buying formula.</p>
          </div>
        }
        </div>
        <div className="text-popups clearfix grpelem" id="x26457-4">
        <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
        </div>
      </div>
	  );
	}
});

var ClearListings = React.createClass({
  getInitialState: function(){
    return{
      rememberListings: "true",
      clearListings: "false"
    };
  },
  handleChange: function(option){
    if(option == "rememberListings"){
      this.setState({"rememberListings": "true"});
      this.setState({"clearListings": "false"});
    }
    else if (option == "clearListings") {
      this.setState({"rememberListings": "false"});
      this.setState({"clearListings": "true"});
    }
  },
  logout: function(){
    if(this.state.rememberListings == "true") { location.assign("/users/logout.php"); }
    else if(this.state.clearListings == "true") { location.assign("/users/logout.php?listings=clear"); }
  },
  closePopup: function(){
    $("#overlay").hide();
    {this.props.closeDialog()}    
  },
  render: function(){
    return(
      <div>
        <h4 onClick={this.closePopup} id="closeClearListingPopup"><i className="fa fa-times" title="close"></i></h4>
        <h2 id="title" className="Subhead-2">Viewed Listings</h2>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 className="text-popups">Would you like HomePik to remember which listings you have viewed?</h4>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 id="firstOption" className="text-popups">{this.state.rememberListings == "true" ? <i className="fa fa-check"></i> : <i className="fa fa-circle-o" style={{cursor: "pointer"}} onClick={this.handleChange.bind(this, 'rememberListings')}></i>} Remember viewed listings</h4>
        <h4 className="text-popups">{this.state.clearListings == "true" ? <i className="fa fa-check"></i> : <i className="fa fa-circle-o" style={{cursor: "pointer"}} onClick={this.handleChange.bind(this, 'clearListings')}></i>} Clear viewed listings</h4>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 id="submit" className="text-popups" onClick={this.logout}>Submit <i id="arrow" className="fa fa-chevron-right"></i></h4>
      </div>
    );
  }
});

window.NavBar = React.createClass({
  getInitialState: function() {
    return{
      name: "",
      email: "",
      role: "",
      agent: "",
      searchName: ""
    };
  },
  componentDidMount: function(){
    $.ajax({
		  type: "POST",
		  url: "/controllers/get-sessions.php",
		  data: {"getInformation": "true"},
		  success: function(data){
        var info = JSON.parse(data);
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            this.setState({name: info['name']});
            this.setState({email: info['email']});
            this.setState({role: info['role']});
          }
        }.bind(this));
      }.bind(this),
		  error: function() {
        console.log("failed");
		  }
		});
  },
  editSearch: function(){
    $.get("/controllers/ajax.php", {
      storeSearchPage: 'true', //Call the PHP function
      page: "tab-1",
      success: function(result){
        $( document ).ajaxStop(function() {
          window.location.assign("/search.php");
        });
      }
    });
  },
  selectFormula: function(){
    var $dialog =  $("#ajax-box").dialog({
      width: 795,
      height: 550,
      dialogClass: 'choseBuyingFormula',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
	  });
	  var closeDialog = function(){
      $dialog.dialog('close');
	  }

	  $("#overlay").show();
	  ReactDOM.render(<ChooseFormula closeDialog={closeDialog} email={this.state.email}/>, $dialog[0]);
  },
  addBuyer: function(){
    var $dialog =  $("#ajax-box").dialog({
      width: 580,
      dialogClass: 'ajaxbox addBuyerPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    $("#overlay").show();
    ReactDOM.render(<AddBuyer closeDialog={closeDialog} agent_email={this.state.email}/>, $dialog[0]);
  },
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");
    
    var $dialog =  $("#ajax-box").dialog({
      width: 580,
      dialogClass: 'ajaxbox clearListingsPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    $("#overlay").show();
    ReactDOM.render(<ClearListings closeDialog={closeDialog}/>, $dialog[0]);
  },
  guestlogout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    location.assign("/users/logout.php");
  },
  render: function(){
    return(
      <div>
        <nav className="navbar navbar-default menuNavBar">
          <div className="container-fluid">
            <div className="navbar-header">
              <p className="navbar-text visible-xs-inline-block">Navigation Menu</p>
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".menu-navbar-collapse" aria-expanded="false" title="Toggle Menu">
                <span className="sr-only">Toggle navigation</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
            </div>

            <div className="collapse navbar-collapse menu-navbar-collapse" id="bs-example-navbar-collapse-1">
              {this.state.role == "guest" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a style={{cursor: "pointer"}} onClick={this.guestlogout}>Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    {this.props.mainPage == "results" ? <li id="editCriteriaOption"><a style={{cursor: "pointer"}} onClick={this.editSearch}>Edit Search Criteria</a></li> : null}
                    <li><a href={"/controllers/saved.php?MP="+this.props.mainPage}>Guest Folder</a></li>
                  </ul>
              : null }
              {this.state.role == "user" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/menu.php">Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    {this.props.mainPage == "results" ? <li id="editCriteriaOption"><a style={{cursor: "pointer"}} onClick={this.editSearch}>Edit Search Criteria</a></li> : null}
                    <li><a href={"buyer-profile.php?MP="+this.props.mainPage} className="my-profile">My Profile</a></li>
                    <li><a href={"saved.php?MP="+this.props.mainPage}>Listing Folders</a></li>
                    <li><a href={"my-messages.php?MP="+this.props.mainPage}>Messages</a></li>
                  </ul>
              : null }
              {this.state.role == "agent" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/menu.php">Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    {this.props.mainPage == "results" ? <li id="editCriteriaOption"><a style={{cursor: "pointer"}} onClick={this.editSearch}>Edit Search Criteria</a></li> : null}
                    <li><a style={{cursor: "pointer"}} onClick={this.addBuyer}>Add New Buyer</a></li>
                    <li><a href={"buyers.php?MP="+this.props.mainPage}>Manage Buyers</a></li>
                    <li><a href={"agent-listings.php?MP="+this.props.mainPage}>Saved Listings</a></li>
                    <li><a href={"my-messages.php?MP="+this.props.mainPage}>Messages</a></li>
                  </ul>
              : null }

              <ul className="nav navbar-nav navbar-right">
                <li id="name">{this.state.name}</li>
                {this.state.role == "guest" ? <li id="signup"><a href="/controllers/guest-register.php"><span id="u2688">Sign<br/>up</span></a></li> : null}
                {this.state.role == "guest" ?
                  <li id="logout" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="u2688">Log<br/>in</span></a>
										<ul className="dropdown-menu">
											<li><a href="/controllers/signin.php">Log in as a buyer</a></li>
											<li><a href="/controllers/agent-signin.php">Log in as an agent</a></li>
										</ul>
									</li>
                :
                  <li id="logout"><a><span id="u2688" style={{cursor: "pointer"}} onClick={this.logout}>Log<br/>out</span></a></li>
                }                
              </ul>
            </div>
          </div>
        </nav>
      </div>
    );
  }
});

window.MenuNavBar = React.createClass({
  getInitialState: function() {
    return{
      name: "",
      email: "",
      role: "",
      agent: "",
      searchName: ""
    };
  },
  componentDidMount: function(){
    $.ajax({
		  type: "POST",
		  url: "/controllers/get-sessions.php",
		  data: {"getInformation": "true"},
		  success: function(data){
        var info = JSON.parse(data);
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            this.setState({name: info['name']});
            this.setState({email: info['email']});
            this.setState({role: info['role']});
          }
        }.bind(this));
      }.bind(this),
		  error: function() {
        console.log("failed");
		  }
		});
  },
  selectFormula: function(){
    var $dialog =  $("#ajax-box").dialog({
      width: 795,
      height: 550,
      dialogClass: 'choseBuyingFormula',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
	  });
	  var closeDialog = function(){
      $dialog.dialog('close');
	  }

	  $("#overlay").show();
	  ReactDOM.render(<ChooseFormula closeDialog={closeDialog} email={this.state.email}/>, $dialog[0]);
  },
  addBuyer: function(){
    var $dialog =  $("#ajax-box").dialog({
      width: 580,
      dialogClass: 'ajaxbox addBuyerPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    $("#overlay").show();
    ReactDOM.render(<AddBuyer closeDialog={closeDialog} agent_email={this.state.email}/>, $dialog[0]);
  },
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");
    
    var $dialog =  $("#ajax-box").dialog({
      width: 580,
      dialogClass: 'ajaxbox clearListingsPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    $("#overlay").show();
    ReactDOM.render(<ClearListings closeDialog={closeDialog}/>, $dialog[0]);
  },
  guestlogout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    location.assign("/users/logout.php");
  },
  render: function(){
    return(
      <div>
        <nav className="navbar navbar-default menuNavBar">
          <div className="container-fluid">
            <div className="navbar-header">
              <p className="navbar-text visible-xs-inline-block">Navigation Menu</p>
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".menu-navbar-collapse" aria-expanded="false" title="Toggle Menu">
                <span className="sr-only">Toggle navigation</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
            </div>

            <div className="collapse navbar-collapse menu-navbar-collapse" id="bs-example-navbar-collapse-1">
              {this.state.role == "guest" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a style={{cursor: "pointer"}} onClick={this.guestlogout}>Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    <li id="editCriteriaOption"><a onClick={this.editSearch}>Edit Search Criteria</a></li>
                    <li><a href="/controllers/saved.php?MP=menu">Guest Folder</a></li>
                  </ul>
              : null }
              {this.state.role == "user" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/menu.php">Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    <li><a href="buyer-profile.php?MP=menu" className="my-profile">My Profile</a></li>
                    <li><a href="saved.php?MP=menu">Listing Folders</a></li>
                    <li><a href="my-messages.php?MP=menu">Messages</a></li>
                  </ul>
              : null }
              {this.state.role == "agent" ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/menu.php">Home</a></li>
                    <li><a href="/search.php#newSearch">New Search</a></li>
                    <li><a style={{cursor: "pointer"}} onClick={this.addBuyer}>Add New Buyer</a></li>
                    <li><a href="buyers.php?MP=menu">Manage Buyers</a></li>
                    <li><a href="agent-listings.php?MP=menu">Saved Listings</a></li>
                    <li><a href="my-messages.php?MP=menu">Messages</a></li>
                  </ul>
              : null }

              <ul className="nav navbar-nav navbar-right">
                <li id="name">{this.state.name}</li>
                {this.state.role == "guest" ? <li id="signup"><a href="/controllers/guest-register.php"><span id="u2688">Sign<br/>up</span></a></li> : null}
                {this.state.role == "guest" ?
                  <li id="logout" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="u2688">Log<br/>in</span></a>
										<ul className="dropdown-menu">
											<li><a href="/controllers/signin.php">Log in as a buyer</a></li>
											<li><a href="/controllers/agent-signin.php">Log in as an agent</a></li>
										</ul>
									</li>
                :
                  <li id="logout"><a><span id="u2688" style={{cursor: "pointer"}} onClick={this.logout}>Log<br/>out</span></a></li>
                }                
              </ul>
            </div>
          </div>
        </nav>
      </div>
    );
  }
});

window.AddressSearch = React.createClass({
  getInitialState: function() {
    return{
      searchAddress: ""
    };
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  handleKeyPress: function(event){
    if (event.key === "Enter"){
      this.searchAddress();
    }
  },
  back: function(){
    window.history.back();
  },
  searchAddress: function(){
    var address = this.state.searchAddress;

    $("#addressSearch").attr("data-address", address);

    address = address.toUpperCase();
    address = address.replace(".", "");
    address = address.split(' ');
    
    if(address[address.length - 1] == 'AVE' || address[address.length - 1] == 'ST' || address[address.length - 1] == 'BLVD' || address[address.length - 1] == 'CT' || address[address.length - 1] == 'RD' || address[address.length - 1] == 'DR'){
      address[address.length - 1] = address[address.length - 1].replace("AVE", "avenue");
      address[address.length - 1] = address[address.length - 1].replace("ST", "street");
      address[address.length - 1] = address[address.length - 1].replace("BLVD", "boulevard");
      address[address.length - 1] = address[address.length - 1].replace("CT", "court");
      address[address.length - 1] = address[address.length - 1].replace("RD", "road");
      address[address.length - 1] = address[address.length - 1].replace("DR", "drive");
    }
    if(address[1] == 'N' || address[1] == 'E' || address[1] == 'S' || address[1] == 'W'){
      address[1] = address[1].replace("N", "north");
      address[1] = address[1].replace("E", "east");
      address[1] = address[1].replace("S", "south");
      address[1] = address[1].replace("W", "west");
    }
    
    address = address.join(' ');
    address = address.toLowerCase();
    var ad = address.split(' ').join('_');
    
    $.cookie("previousPage", window.location.href);
    window.location = "http://homepik.com/addressSearch.php#address?"+ad;
  },
  render: function(){
    return(
      <div className="clearfix colelem" id="pu2680">
        {this.props.mainPage == "index" ? <span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/index.php"><span className="fa fa-chevron-left"></span> <span className="backBtnText">Home</span></a></span> : null }
        {this.props.mainPage == "menu" ? <span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/menu.php"><span className="fa fa-chevron-left"></span> <span className="backBtnText">Menu</span></a></span> : null }
        {this.props.mainPage == "criteria" ? <span id="backBtn" className="Text-1 clearfix colelem"><a href="/search.php"><span className="fa fa-chevron-left"></span> <span className="backBtnText">Search Criteria</span></a></span> : null }
        {this.props.mainPage == "results" ? <span id="backBtn" className="Text-1 clearfix colelem"><a href="/search.php"><span className="fa fa-chevron-left"></span> <span className="backBtnText">Search Results</span></a></span> : null }
        {this.props.mainPage == "address" ? <span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/addressSearch.php"><span className="fa fa-chevron-left"></span> <span className="backBtnText">Address Search Results</span></a></span> : null }
        <div className="clip_frame grpelem" id="u2683">
          <img className="block" id="u2683_img" src="/images/raster_grey.png" alt="" width="28" height="28" style={{cursor: "pointer"}} onClick={this.searchAddress} title='search'/>
        </div>
        <div className="clearfix grpelem" id="u2680">
          <div className="clearfix grpelem" id="u2681">
            <div className="clearfix grpelem" id="u2682-4">
              <input type="text" name="addressSearch" id="addressSearch" placeholder="Search for an address" value={this.state.searchAddress} onChange={this.handleChange.bind(this, 'searchAddress')} onKeyPress={this.handleKeyPress}/>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

var ComingSoon = React.createClass({
  closePopup: function(){
    $("#overlay").hide();
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div>
        <div>
          <p className="space">&nbsp;</p>
          <p className="space">&nbsp;</p>
          <p className="space">&nbsp;</p>
          <p style={{textAlign: "center", fontSize: 20 + 'px', fontWeight: "bold"}}>Coming soon...</p>
        </div>
        <div className="text-popups clearfix grpelem" id="close1">
          <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
        </div>
      </div>
    );
  }
});

window.Footer = React.createClass({
  comingSoon: function(){
    var $dialog =  $("#ajax-box").dialog({
      width: 250,
      dialogClass: "comingSoon",
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
        var div = document.createElement('div');
        div.id = 'ajax-box';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $(this).css("display", "block");
        $("#overlay").bind("click", function(){
          $("#ajax-box").dialog('close');
          $("#overlay").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }.bind(this)

    $("#overlay").show();
    //$('.comingSoon').show(); // To make the popup appear on the search results.
    ReactDOM.render(<ComingSoon closeDialog={closeDialog}/>, $dialog[0]);
  },
  render: function () {
    return (
      <div>
       <div className="verticalspacer"></div>
       <nav className="navbar navbar-default footerNavBar">
          <div className="container-fluid">
            <div className="navbar-header">
              <p className="navbar-text visible-xs-inline-block">Navigation Footer</p>
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".footer-navbar-collapse" aria-expanded="false" title="Toggle Footer">
                <span className="sr-only">Menu</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
            </div>

            <div className="collapse navbar-collapse footer-navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul className="nav navbar-nav">
                <li id="copyright">Copyright 2016 Nice Idea Media, Inc and HomePik.com</li>
                <li><a href={"about.php?MP="+this.props.mainPage}><span id="u15181-2">|</span>About HomePik</a></li>
                <li><a href={"contactus.php?MP="+this.props.mainPage}><span id="u15181-6">|</span>Contact Us</a></li>
                <li><a style={{cursor:"pointer"}} onClick={this.comingSoon}><span id="u15181-8">|</span>Site Map</a></li>
                <li><a href={"faq.php?MP="+this.props.mainPage}><span id="u15181-10">|</span>FAQs</a></li>
                <li><a style={{cursor:"pointer"}} onClick={this.comingSoon}><span id="u15181-12">|</span>Blog</a></li>
              </ul>

              <ul className="nav navbar-nav navbar-right">
                <li className="socialMedia" >
                  <img id="facebook" src="/images/socialMedia_facebook.png" onClick={this.comingSoon} title='Facebook'/>
                  <img id="twitter" src="/images/socialMedia_twitter.png" onClick={this.comingSoon} title='Twitter'/>
                  <img id="instagram" src="/images/socialMedia_instagram.png" onClick={this.comingSoon} title='Instagram'/>
                  <img id="youtube" src="/images/socialMedia_youtube.png" onClick={this.comingSoon} title='YouTube'/>
                </li>
              </ul>
            </div>
          </div>
        </nav>
      </div>
    );
  }
});
