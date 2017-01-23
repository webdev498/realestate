<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
if((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
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
  <div id="verificationBackground2"></div>
  <div id="verification"></div>
  <div id='ajax-box'></div>
<script type="text/babel">
  var Verify = React.createClass({
    getInitialState: function() {
      return{
        email: "",
        question: "",
        answer: "",
        step: 1
      };
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    verify: function(){
      if( this.state.email == "" ){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage verificationBlank',
          buttons : {
            close: function(){
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
        $('#ajax-box').load('messages.php #agent_verification_blank',function(){
          $('#ajax-box').dialog('open');
        });
      }
      else{
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"emailValidation": "true", "email": this.state.email },
          success: function(data){
            var info = jQuery.parseJSON(data);
  
            if(info != null){
              $("#error").hide();  
              this.setState({question: info.security_question});
              this.setState({step: 2});
              $("#formQuestion").val(info.security_question);
            }
            else{
              $("#ajax-box").dialog({
                modal: true,
                height: 'auto',
                width: 'auto',
                autoOpen: false,
                dialogClass: 'ajaxbox errorMessage invalidInformation',
                buttons : {
                  close: function(){
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
              $('#ajax-box').load('messages.php #invalid_information',function(){
                $('#ajax-box').dialog('open');
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
  
        if( this.state.email == "" || this.state.question == "default" || this.state.answer == "" ){
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage verificationBlank',
            buttons : {
              close: function(){
                $(this).dialog("close");
              }
            },
            close: function(){
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('messages.php #agent_verification_blank',function(){
            $('#ajax-box').dialog('open');
          });
          e.preventDefault();
        }        
        else if(!emailValid){
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage invalidEmail',
            buttons : {
              close: function(){
                $(this).dialog("close");
              }
            },
            close: function(){
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('messages.php #invalid_email',function(){
            $('#ajax-box').dialog('open');
          });
          e.preventDefault();
        }
      }
      else{        
        e.preventDefault();
      }
    },
    back: function(e){
      e.preventDefault();
      window.location.assign("agent-signin.php");
    },
    render: function(){
      return(
        <div id="wrapper">
          <div className="container-fluid">
            <div className="row">
              <div className="col-md-2 col-sm-2"></div>
              <div className="col-md-7 col-sm-10 col-xs-11">
                <div id="verificationArea" className='long'>
                  <div id="verificationTop">
                    <span id="verificationHeader" className="text-popups">Agent Verification</span>
                  </div>
                  <div id="verificationBorder">
                    <form onSubmit={this.validate} action="users/agent-verification-process.php" id="validate" method="post" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup>
                          <col width="250"/><col width="350"/>
                        </colgroup>
                        <tbody>
                          <tr>
                            <td className="text-popups" colSpan="2">
                              <div style={{textAlign: "justify"}}>The password you entered did not match the one on file for the email entered. Return to agent login <a href='/controllers/agent-signin.php'>here</a> or enter the information below to verify who you are.<br/><br/></div>
                            </td>
                          </tr>                          
                          <tr>
                            <td className="text-popups">Email:</td>
                            <td className="text-popups"><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" name="email" onChange={this.handleChange.bind(this, 'email')} /></td>
                          </tr>
                          {this.state.step == 2 ?
                            <tr>
                              <td className="text-popups">Security Question:</td>
                              <td className="text-popups">
                                {this.state.question == 1 ? <span>What is your middle name?</span> : null}
                                {this.state.question == 2 ? <span>What is your mother's maiden name?</span> : null}
                                {this.state.question == 3 ? <span>What was the name of the street where you grew up?</span> : null}
                                {this.state.question == 4 ? <span>What is your favorite food?</span> : null}
                                <select id="formQuestion" name="security-question" style={{display: "none"}}>
                                  <option value="default" selected="selected">Select A Security Question</option>
                                  <option value="1">What is your middle name?</option>
                                  <option value="2">What is your mother's maiden name?</option>
                                  <option value="3">What was the name of the street where you grew up?</option>
                                  <option value="4">What is your favorite food?</option>
                                </select>
                              </td>
                            </tr>
                          : null }
                          {this.state.step == 2 ?
                            <tr>
                              <td className="text-popups">Security Answer:</td>
                              <td className="text-popups"><input type="text" id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'answer')}/></td>
                            </tr>
                          : null }                          
                          <tr>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="verification" />
                              <a href="agent-signin.php"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Back</button></a>
                              {this.state.step == 1 ? <button type="submit" name="submit" id="verificationVerify" className="text-popups" onClick={this.verify}>Continue <i id="arrow" className="fa fa-chevron-right"></i></button> : null }
                              {this.state.step == 2 ? <button type="submit" name="submit" id="verificationSubmit" className="text-popups">Verify <i id="arrow" className="fa fa-chevron-right"></i></button> : null }
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
