<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
$_SESSION['viewingBuyer'] = 'false';

if(isset($_SESSION['agent'])){
  $agent_email = $_SESSION['email'];
  $unreadMessages = $_SESSION['unreadMessages'];
  $role = "agent";
}
elseif(isset($_SESSION['buyer'])){
  $unreadMessages = $_SESSION['unreadMessages'];
  $role = "buyer";
}
else{ print "<script> window.location = '/users/logout.php' </script>"; }
?>

  <title>HomePik - Main Menu</title>
  <?php include_css("/views/css/menu.css");
  include_once("analyticstracking.php");
  include_once('autoLogout.php'); ?>
</head>
<body>
  <div id="menu"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
  <div id='ajax-box3'></div>
<script type="text/babel">
  var BuyerOptions = React.createClass({
    getInitialState: function() {
      return{
        messages: "<?php echo (isset($unreadMessages) ? $unreadMessages : "") ?>",
      };
    },
    logout:function(){
      location.assign("/users/logout.php");
    },
    render: function () {
      return (
        <div id="main-section">
          <div id="pu7925">
            <div className="clip_frame grpelem menu-image first-image" id="u7925">
              <a href="/search.php#newSearch"><img className="position_content" id="u28169_img" src="/images/fotolia_83358921_darker_sm-crop-u28169.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image" id="u7931">
              <a href="buyer-profile.php?MP=menu"><img className="block" id="u28038_img" src="/images/fotolia_99608333_subscription_monthly_m-crop-u28038.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image" id="u7927">
              <a href="buyer-profile.php?MP=menu"><img className="position_content" id="u7927_img" src="/images/fotolia_69914734_subscription_monthly_m-crop-u7927.jpg" alt=""/></a>
            </div>
            <div className="clip_frame grpelem menu-image" id="u8055">
              <a href="saved.php?MP=menu"><img className="block" id="u8055_img" src="/images/fotolia_73766424_coloradjust-crop-u8055.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image last-image" id="u7929">
              <a href="my-messages.php?MP=menu"><img className="position_content" id="u7929_img" src="/images/fotolia_54239025_subscription_monthly_m-crop-u7929.jpg" alt=""/></a>
            </div>
          </div>
          <div id="u8048">
            <a href="/search.php#newSearch" className="button-links">
              <div className="museBGSize grpelem" id="u7952"><span className="button-text">NEW SEARCH</span></div>
            </a>
            <a href="buyer-profile.php?MP=menu" className="button-links">
              <div className="clearfix grpelem" id="pu4865">
                <div className="clip_frame grpelem" id="u4865"></div>
                <div className="museBGSize grpelem" id="u7968"><span className="button-text" style={{width: 165 + 'px', left: -30 + 'px'}}>SEARCH<br/>using buying formula</span></div>
              </div>
            </a>
            <a href="buyer-profile.php?MP=menu" className="my-profile button-links">
              <div className="museBGSize grpelem" id="u8000"><span className="button-text">MY PROFILE</span></div>
            </a>
            <a href="saved.php?MP=menu" style={{cursor: "pointer"}} className="button-links">
              <div className="museBGSize grpelem" id="u8016"><span className="button-text">MY SAVED LISTINGS</span></div>
            </a>
            <a href="my-messages.php?MP=menu" className="button-links last-button">
              <div className="museBGSize grpelem" id="u27965"><span className="button-text">MESSAGES {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</span></div>
            </a>
          </div>
        </div>
      );
    }
  });

  var AddBuyer = React.createClass({
    getInitialState: function() {
      return{
        agent_email: "<?php echo (isset($agent_email) ? $agent_email : "") ?>",
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
    checkFirstName: function(){
      if(this.state.firstname != ""){ return true; }
      else{ return false; }
    },
    checkLastName: function(){
      if(this.state.lastname != ""){ return true; }
      else{ return false; }
    },
    checkEmail: function(){
      if(this.state.email != ""){ return true; }
      else{ return false; }
    },
    checkInput: function(){
      if( this.state.firstname != "" && this.state.lastname != "" && this.state.email != "" ){ return true; }
      else{ return false; }
    },
    updatePhone: function(){
  	  var number = this.state.phone;
  	  var x = number.replace(/\D/g,'');

      if(x.length > 0){
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
          $('#ajax-box2').load('messages.php #invalidBuyerPhone',function(){
            $('#ajax-box2').dialog( "option", "title", "Invalid Phone Number" ).dialog('open');
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
          $('#ajax-box2').dialog('open');
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
          $('#ajax-box2').dialog('open');
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
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        $.ajax({
          type: "POST",
          url: "check-buyer.php",
          data: {"email": email},
          success: function(data){
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
                    $('#ajax-box2').dialog('open');
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
                  $('#ajax-box2').dialog('open');
                });

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
                      $('#ajax-box2').dialog('open');
                    });

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
                    $('#ajax-box2').dialog('open');
                  });

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
                $('#ajax-box2').dialog('open');
              });

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
      {this.props.closeDialog()}
    },
    render: function(){
      return(
        <div>
          <div id="addBuyerTop">
          <span className="text-popups" id="addBuyerHeader">Add New Buyer</span>
          <img src="/images/manage_buyers_normal.png" width="65" height="65" style={{float: "right"}}/>
          <h4 id="closePopup" onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
          </div>
          <div id="addBuyerBorder">
            <form onSubmit={this.addBuyer} autoComplete="off" data-bind="nextFieldOnEnter:true">
              <table cellPadding="2" cellSpacing="0" border="0">
                <colgroup>
                  <col width="250"/>
                  <col width="350"/>
                </colgroup>
                <tbody>
                  <tr>
                    <td className="text-popups" style={{width: 250 + "px"}}>First Name:</td>
                    <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" value={this.state.firstname} autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.checkFirstName() ? null : <strong id="firstnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                  </tr>
                  <tr>
                    <td className="text-popups" style={{width: 250 + "px"}}>Last Name:</td>
                    <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formLastName" className="grade_desc input1" name="lastName" value={this.state.lastname} onChange={this.handleChange.bind(this, 'lastname')} />{this.checkLastName() ? null : <strong id="lastnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                  </tr>
                  <tr>
                    <td className="text-popups" style={{width: 250 + "px"}}>Email:</td>
                    <td className="text-popups" style={{width: 350 + "px"}}><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} />{this.checkEmail() ? null : <strong id="emailMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
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
                      <button type="submit" name="addBuyer" className="text-popups" id="addBuyerSubmit" onClick={this.addBuyer}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
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

  var AgentOptions = React.createClass({
    getInitialState: function() {
      return{
        messages: "<?php echo (isset($unreadMessages) ? $unreadMessages : "") ?>"        
      };
    },
    addBuyer: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 585,
        dialogClass: 'ajaxbox addBuyerPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<AddBuyer closeDialog={closeDialog}/>, $dialog[0]);
    },
    logout:function(){
      location.assign("/users/logout.php");
    },
    render: function () {
      return (
        <div id="main-section">
          <div id="pu7925">
            <div className="clip_frame grpelem menu-image first-image" id="u7925">
              <a href="/search.php#newSearch"><img className="position_content" id="u28169_img" src="/images/fotolia_57355360_subscription_monthly_m-crop-u7925.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image" id="u7931">
              <a className="addBuyer" style={{cursor:"pointer"}} onClick={this.addBuyer}><img className="block" id="u28038_img" src="/images/fotolia_100847921_retouched_3-crop-u29321.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image" id="u7927">
              <a href="buyers.php?MP=menu"><img className="position_content" id="u7927_img" src="/images/fotolia_69914734_subscription_monthly_m-crop-u7927.jpg" alt=""/></a>
            </div>
            <div className="clip_frame grpelem menu-image" id="u8055">
              <a href="agent-listings.php?MP=menu"><img className="block" id="u8055_img" src="/images/fotolia_73766424_coloradjust-crop-u8055.jpg" alt=""/></a>
            </div>
            <div className="clip_frame clearfix grpelem menu-image last-image" id="u7929">
              <a href="my-messages.php?MP=menu"><img className="position_content" id="u7929_img" src="/images/fotolia_54239025_subscription_monthly_m-crop-u7929.jpg" alt=""/></a>
            </div>
          </div>
          <div id="u8048">
            <a className="button-links" href="/search.php#newSearch">
              <div className="museBGSize grpelem" id="u7952-2"><span className="button-text" style={{top: 110 + 'px'}}>NEW SEARCH</span></div>
            </a>
            <a className="addBuyer" style={{cursor:"pointer"}} className="button-links">
              <div className="clearfix grpelem" id="pu4865">
                <div className="clip_frame grpelem" id="u4865"></div>
                <div className="museBGSize grpelem" id="u28069" onClick={this.addBuyer}><span className="button-text">ADD NEW BUYER</span></div>
              </div>
            </a>
            <a href="buyers.php?MP=menu" className="buyers button-links">
              <div className="museBGSize grpelem" id="u28093"><span className="button-text">MANAGE BUYERS</span></div>
            </a>
            <a href="agent-listings.php?MP=menu" className="agent-listings button-links">
              <div className="museBGSize grpelem" id="u28141"><span className="button-text">SAVED LISTINGS</span></div>
            </a>
            <a href="my-messages.php?MP=menu" className="button-links last-button">
              <div className="museBGSize grpelem" id="u27965"><span className="button-text">MESSAGES {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</span></div>
            </a>
          </div>
          <div id="u8048">
            <a href="change-password.php?MP=menu" className="adminLink">Change Password</a>
          </div>
        </div>
      );
    }
  });

  var Menu = React.createClass({
    getInitialState: function() {
      return{
        role: "<?php echo (isset($role) ? $role : "") ?>",
      };
    },
    render: function () {
      return (
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <MenuNavBar />
            <AddressSearch mainPage={""}/>
            {this.state.role == "agent" ? <AgentOptions /> : null}
          	{this.state.role == "buyer" ? <BuyerOptions /> :null}
            <div className="verticalspacer"></div>
          </div>

        </div>
      );
    }
  });

  ReactDOM.render(
    <Menu/>,
    document.getElementById("menu")
  );

  ReactDOM.render(
    <Footer mainPage={"menu"} />,
    document.getElementById("footer")
  );
</script>
</body>
</html>
