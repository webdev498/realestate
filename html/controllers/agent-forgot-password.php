<?php
session_start();
include("dbconfig.php");
include("functions.php");
include("basicHead.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
limit(); // Rate limit to prevent scraping
if ((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
if(isset($_GET['agent'])){$assigned=$_GET['agent'];}else{$assigned='';}
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
?>

<html>
<head>
  <meta name="robots" content="noindex">
  <title>HomePik - Agent Verification</title>
  <?php include_css("/views/css/verification.css"); ?>
  <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body class="varification_page">
  <div id="verificationBackground1"></div>
  <div id="verification"></div>
  <div id='ajax-box'></div>

<script type="text/babel">
  var Verify = React.createClass({
    getInitialState: function() {
      return{
        firstname: "",
        lastname: "",
        email: "",
        id: "",
        newPass: "",
        confPass: "",
        step: 1
      };
    },
	  componentDidMount: function(){
      if (navigator.userAgent.indexOf('Mac OS X') != -1 && navigator.userAgent.indexOf('Firefox') != -1) {
        $("#formQuestion").addClass("macFF");
      }
	  },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    verify: function(){
      if(this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.id == ""){
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
          close: function() {
            $( this ).dialog( "destroy" );
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
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"fullValidation": "true", "email": this.state.email, "firstName": this.state.firstname, "lastName": this.state.lastname, "id": this.state.id},
          success: function(status){
          
            if(status == "exists"){
              this.setState({step: 2});
              $(".step2Rows").show();
            }
            else{
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
                close: function() {
                  $( this ).dialog( "destroy" );
                },
                open: function(){
                  $(".ui-widget-overlay").bind("click", function(){
                    $("#ajax-box").dialog('close');
                  });
                }
              });
              $('#ajax-box').load('/controllers/messages.php #invalidInput',function(){
                $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
              });
            }
          }.bind(this)
        });
      }
    },
    validate: function(e){      
      if(this.state.step == 2){
        var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
        var emailValid = emailReg.test(this.state.email);
  
        if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.id == ""){
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
            close: function() {
              $( this ).dialog( "destroy" );
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
          e.preventDefault();
        }
        else if(this.state.firstname.match(/[0-9]/g) != null || this.state.lastname.match(/[0-9]/g) != null){
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
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('/controllers/messages.php #invalidName',function(){
            $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
          });
          e.preventDefault();
        }
        else if(!emailValid){
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
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('/controllers/messages.php #invalidBuyerEmail',function(){
            $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
          });
          e.preventDefault();
        }
        else if( this.state.newPass == "" || this.state.confPass == "" || this.state.newPass.length < 5 && this.state.confPass.length < 5 ){
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
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('messages.php #passwordRequirement',function(){
            $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
          });
          e.preventDefault();
        }
        else if(this.state.newPass != this.state.confPass){
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
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('messages.php #passwordsMatch',function(){
            $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
          });
          e.preventDefault();
        }
        else{
          // Submit form
        }
      }
      else{
        e.preventDefault();
      }
    },
    render: function(){
      return(
        <div id="wrapper">
          <div className="container-fluid">
            <div className="row">
              <div className="col-md-2 col-sm-2"></div>
              <div className="col-md-7 col-sm-10 col-xs-11">
                <div id="verificationArea" className='short'>
                  <div id="verificationTop">
                    <span id="verificationHeader" className="text-popups">Agent Verification</span>                    
                    <img src="../images/button_my_profile.png" style={{float: "right"}}/>
                  </div>
                  <div id="verificationBorder">
                    <form onSubmit={this.validate} action="users/agent-pass-reset-process.php" id="validate" method="get" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup><col width="250"/><col width="350"/></colgroup>
                        <tbody>
                          <tr>
                            <td className="text-popups">First Name:</td>
                            <td className="text-popups"><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" autoFocus onChange={this.handleChange.bind(this, 'firstname')} /><br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Last Name:</td>
                            <td className="text-popups"><input type="text" id="formLastName" className="grade_desc input1" name="lastName" onChange={this.handleChange.bind(this, 'lastname')} /><br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Email:</td>
                            <td className="text-popups"><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" name="email" onChange={this.handleChange.bind(this, 'email')} /></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Agent ID:</td>
                            <td className="text-popups"><input type="text" id="formAgentCode" className="grade_desc input1" name="agentCode" value={this.state.id} onChange={this.handleChange.bind(this, 'id')} /></td>
                          </tr>
                          <tr id="error" style={{display: "none"}}>
                            <td className="text-popups" colSpan="2"><strong style={{color: "#D2008F"}}>{'\u002A'} No account associated with that email.</strong></td>
                          </tr>
                          <tr>
                            <td colSpan="2">                            
                              {this.state.step == 1 ? <button type="submit" name="submit" id="verificationSubmit" className="text-popups" onClick={this.verify}>Verify <i id="arrow" className="fa fa-chevron-right"></i></button> : null }                            
                              {this.state.step == 2 ? <span id="verifiedButton"><i className="fa fa-check"></i> Verify</span> : null }
                            </td>
                          </tr>
                          <tr className="step2Rows" style={{display: "none"}}><td></td></tr>
                          <tr className="step2Rows" style={{display: "none"}}>
                            <td className="text-popups">New Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')} /></td>
                          </tr>
                          <tr className="step2Rows" style={{display: "none"}}>
                            <td className="text-popups">Retype Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" autocomplete="off" size="25"onChange={this.handleChange.bind(this, 'confPass')} /></td>
                          </tr>
                          <tr className="step2Rows" style={{display: "none"}}><td></td></tr>
                          <tr className="step2Rows" style={{display: "none"}}>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="reset" />
                              <button type="submit" name="submit" id="resetPasswordSubmit" className="text-popups" onClick={this.resetPassword}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      );
    }
  });

  ReactDOM.render(
    <Verify/>,
    document.getElementById("verification")
  );

</script>
</body>
</html>
