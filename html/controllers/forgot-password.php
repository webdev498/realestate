<?php
session_start();
include_once("functions.php");
include_once("basicHead.php");
if ((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
?>

<html>
<head>
  <meta name="robots" content="noindex">
  <title>HomePik - Buyer Verification</title>
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
        oldemail: "",
        phone: "",
        agent1: "",
        agent2: "",
        secQues: "default",
        secAns: "",
        newPass: "",
        confPass: "",
        step: 1,
        edit: "false",
        option: "",
        editYes: "false",
        editNo: "false"
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
    handleEditOptionChange: function(name,event){
      if(name == "yes"){
        this.setState({editYes: "true"});
        this.setState({editNo: "false"});
        this.setState({edit: "true"});
      }
      else if(name == "no"){
        this.setState({editNo: "true"});
        this.setState({editYes: "false"});
        this.setState({edit: "false"});
      }
    },
    checkInput: function(){
      if( this.state.firstname != "" && this.state.lastname != "" && this.state.email != "" && this.state.pass != "" && (this.state.phone != "" || (this.state.secQues != "default" && this.state.secAns != "")) ){ return true; }
      else{ return false; }
    },
    updatePhone: function(){
      var number = this.state.phone;
      var x = number.replace(/\D/g,'');

      if (x.length == 10 && !isNaN(x)){
        var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
  		  this.setState({ phone: y }); // Replace number with formatted number
      }else {
        if(x.length > 0){
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage invalidPhone',
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
          $('#ajax-box').load('messages.php #invalid_phone',function(){
            $('#ajax-box').dialog('open');
          });
        }
      }
    },
    verify: function(){
      if(this.state.firstname == "" || this.state.lastname == "" || this.state.email == ""){
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
        $('#ajax-box').load('messages.php #buyer_verification_blank',function(){
          $('#ajax-box').dialog('open');
        });
      }
      else{
        $.ajax({
          type: "POST",
          url: "check-buyer.php",
          data: {"partialValidation": "true", "email": this.state.email, "firstName": this.state.firstname, "lastName": this.state.lastname},
          success: function(data){
            console.log(data);
            var info = jQuery.parseJSON(data);
            
            this.setState({oldemail: info.email});
            this.setState({agent1: info.agent1});
            this.setState({agent2: info.agent2});
  
            if(info != null){
              $("#error").hide();
              if(info.phone != "" && info.phone != null ){
                $("#phone").show();
                this.setState({option: "Phone"});
              }
  
              if((info.phone == "" || info.phone == null) && info.security_question != "" && info.security_question != null && info.security_question != "default" ){
                this.setState({secQues: info.security_question});
                $("#formQuestion").val(info.security_question);
                $("#question").show();
                $("#answer").show();
                this.setState({option: "Question"});
              }
              
              this.setState({step: 2});
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
        this.updatePhone();
  
        if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || (this.state.phone == "" && (this.state.secQues == "default" || this.state.secAns == "" ))){
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
          $('#ajax-box').load('messages.php #buyer_verification_blank_two',function(){
            $('#ajax-box').dialog('open');
          });
          e.preventDefault();
        }
        else if(this.state.firstname.match(/[0-9]/g) != null || this.state.lastname.match(/[0-9]/g) != null){
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage invalidName',
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
          $('#ajax-box').load('messages.php #invalid_name',function(){
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
            close: function() {
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
        else{
          $.ajax({
            type: "POST",
            url: "check-buyer.php",
            data: {"fullValidation": "true", "email": this.state.email, "firstName": this.state.firstname, "lastName": this.state.lastname, "phone": this.state.phone, "question": this.state.secQues, "answer": this.state.secAns},
            success: function(status){
              if(status == "exists"){
                $("#error").hide();
                $(".step3Rows").show();
                this.setState({step: 3});
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
      }
      else{
        e.preventDefault();
      }
    },
    resetPassword: function(){
      if( this.state.newPass != "" && this.state.confPass != "" && this.state.newPass.length >= 5 && this.state.confPass.length >= 5 ){
        if(this.state.newPass == this.state.confPass){
          $.ajax({
            type: "POST",
            url: "check-buyer.php",
            data: {"passReset": "true", "email": this.state.email, "password": this.state.newPass},
            success: function(status){
              $("#verificationArea").hide();
              $("#buyerInformationArea").show();
            }
          });
        }
        else{
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage passwordDontMatch',
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
          $('#ajax-box').load('messages.php #passwords_dont_match',function(){
            $('#ajax-box').dialog('open');
          });
        }
      }
      else{
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage shortPassword',
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
        $('#ajax-box').load('messages.php #short_password',function(){
          $('#ajax-box').dialog('open');
        });
      }
    },
		getAgents: function(){
		  $.ajax({
				type: "POST",
				url: "get-agents.php",
				data: {"agent": "true", "new": "true"},
				success: function(data){
					var info = JSON.parse(data);
					$( ".agent-code" ).autocomplete({
						source: info
					});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    switchAgent: function(agent,event){
      if(agent == "agent1"){ this.setState({agent1: event.target.value}); }
      else if(agent == "agent2"){ this.setState({agent2: event.target.value}); }
      var code = event.target.value;
  
      if(code.length > 3){
        var name = code.split(", ");
        var firstname = name[1].replace(" ", "");
        var lastname = name[0];
  
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"getID":"true", "firstname": firstname, "lastname": lastname},
          success: function(data){
            var id = JSON.parse(data);
            if(agent == "agent1"){ this.setState({ agent1: id }); }
            else if(agent == "agent2"){ this.setState({ agent2: id }); }
          }.bind(this)
        });
      }
    },
    validateInformation: function(e){
		  var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		  var emailValid = emailReg.test(this.state.email);

		  if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.newPass == "" || this.state.option == "" || (this.state.phone == "" && (this.state.secQues == "default" || this.state.secAns == "" ))){
				$("#ajax-box").dialog({
					modal: true,
					height: 'auto',
					width: 'auto',
					autoOpen: false,
					dialogClass: 'ajaxbox errorMessage blankInformationEdit',
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
				$('#ajax-box').load('messages.php #buyer_information_edit_blank',function(){
					$('#ajax-box').dialog('open');
				});
				e.preventDefault();
		  }
		  else if(this.state.firstname.match(/[0-9]/g) != null || this.state.lastname.match(/[0-9]/g) != null){
				$("#ajax-box").dialog({
					modal: true,
					height: 'auto',
					width: 'auto',
					autoOpen: false,
					dialogClass: 'ajaxbox errorMessage invalidName',
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
				$('#ajax-box').load('messages.php #invalid_name',function(){
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
          close: function() {
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
		  else if(this.state.newPass.length < 5){
				$("#ajax-box").dialog({
					modal: true,
					height: 'auto',
					width: 'auto',
					autoOpen: false,
					dialogClass: 'ajaxbox errorMessage shortPassword',
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
				$('#ajax-box').load('messages.php #short_password',function(){
					$('#ajax-box').dialog('open');
				});
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
                    <span id="verificationHeader" className="text-popups">Buyer Verification</span>                    
                    <img src="/images/button_my_profile.png" style={{float: "right"}}/>
                  </div>
                  <div id="verificationBorder">
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
                        <tr id="phone" style={{display:"none"}}>
                          <td className="text-popups">Phone:</td>
                          <td className="text-popups"><input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></td>
                        </tr>
                        <tr id="question" style={{display:"none"}}>
                          <td className="text-popups">Security Question:</td>
                          <td className="text-popups">
                            {this.state.secQues == 1 ? <span>What is your middle name?</span> : null}
                            {this.state.secQues == 2 ? <span>What is your mother's maiden name?</span> : null}
                            {this.state.secQues == 3 ? <span>What was the name of the street where you grew up?</span> : null}
                            {this.state.secQues == 4 ? <span>What is your favorite food?</span> : null}
                            <select id="formQuestion" name="security-question" style={{display: "none"}}>
                              <option value="default" selected="selected">Select A Security Question</option>
                              <option value="1">What is your middle name?</option>
                              <option value="2">What is your mother's maiden name?</option>
                              <option value="3">What was the name of the street where you grew up?</option>
                              <option value="4">What is your favorite food?</option>
                            </select>
                          </td>
                        </tr>
                        <tr id="answer" style={{display: "none"}}>
                          <td className="text-popups">Security Answer:</td>
                          <td className="text-popups"><input type="text" id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/></td>
                        </tr>
                        <tr id="error" style={{display: "none"}}>
                          <td className="text-popups" colSpan="2"><strong style={{color: "#D2008F"}}>{'\u002A'} No account associated with that email.</strong></td>
                        </tr>
                        <tr>
                          <td colSpan="2">
                          <div id="disclaimer" className="text-popups">Confidential Information: A Registered buyer is given access to listings which are not available to the public and are confidential. </div>
                          </td>
                        </tr>
                        <tr>
                          <td colSpan="2">                            
                            <input type="hidden" name="formStep" value="verification1" />
                            <a href="javascript:history.back()"><button id="backBtn" className="text-popups"><i id="arrow" className="fa fa-chevron-left"></i> Back</button></a>
                            {this.state.step == 1 ? <button type="submit" name="submit" id="verificationVerify" className="text-popups" onClick={this.verify}>Continue <i id="arrow" className="fa fa-chevron-right"></i></button> : null }
                            {this.state.step == 2 ? <button type="submit" name="submit" id="verificationSubmit" className="text-popups" onClick={this.validate}>Verify <i id="arrow" className="fa fa-chevron-right"></i></button> : null }                            
                            {this.state.step == 3 ? <span id="verifiedButton"><i className="fa fa-check"></i> Verify</span> : null }
                          </td>
                        </tr>
                        <tr className="step3Rows" style={{display: "none"}}><td></td></tr>
                        <tr className="step3Rows" style={{display: "none"}}>
                          <td className="text-popups">New Password: </td>
                          <td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')} /></td>
                        </tr>
                        <tr className="step3Rows" style={{display: "none"}}>
                          <td className="text-popups">Retype Password: </td>
                          <td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" autocomplete="off" size="25"onChange={this.handleChange.bind(this, 'confPass')} /></td>
                        </tr>
                        <tr className="step3Rows" style={{display: "none"}}><td></td></tr>
                        <tr className="step3Rows" style={{display: "none"}}>
                          <td colSpan="2">
                            <button type="submit" name="submit" id="resetPasswordSubmit" className="text-popups" onClick={this.resetPassword}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="buyerInformationArea" className='long' style={{display:"none"}}>
                  <div id="buyerInformationTop">
                    <span id="buyerInformationHeader" className="text-popups">Buyer Information</span>
                    <img src="/images/button_pen_states.png" style={{float: "right"}}/>
                  </div>
                  <div id="buyerInformationBorder">
                    <form onSubmit={this.validateInformation} action="users/edit-buyer-information-process.php" id="validate editBuyerInformation" className="validate" method="get" autoComplete="off" data-bind="nextFieldOnEnter:true">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup><col width="250"/><col width="350"/></colgroup>
                        <tbody>
                          <tr>
                            <td className="text-popups">First Name:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formFirstName" className="grade_desc input1" value={this.state.firstname} name="firstName" autoFocus onChange={this.handleChange.bind(this, 'firstname')} /> : this.state.firstname }<br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Last Name:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formLastName" className="grade_desc input1" value={this.state.lastname} name="lastName" onChange={this.handleChange.bind(this, 'lastname')} /> : this.state.lastname }<br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Email:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} /> : this.state.email }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Password:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.newPass} name="password" onChange={this.handleChange.bind(this, 'newPass')} /> : this.state.newPass }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Primary Agent:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formAgent1" className="grade_desc agent-code input1" name="agent1-code" value={this.state.agent1} onChange={this.handleChange.bind(this, 'agent1')} onFocus={this.getAgents} onBlur={this.switchAgent.bind(this, 'agent1')} /> : this.state.agent1 }</td>
                          </tr>
                          { this.state.agent2 != "" ?                            
                            <tr>
                              <td className="text-popups">Secondary Agent:</td>
                              <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formAgent2" className="grade_desc agent-code input1" name="agent2-code" value={this.state.agent2} onChange={this.handleChange.bind(this, 'agent2')} onFocus={this.getAgents} onBlur={this.switchAgent.bind(this, 'agent2')} /> : this.state.agent2 }</td>
                            </tr>
                          : null }
                          <tr>
                            <td className="text-popups">Security Option:</td>
                            <td className="text-popups">
                              {this.state.edit == "true" ?
                                <select id="securityOption" name="security-option" onChange={this.handleChange.bind(this, 'option')}>
                                  {this.state.option == "Phone" ? <option value="Phone" selected="selected">Phone</option> : <option value="Phone">Phone</option> }
                                  {this.state.option == "Question" ? <option value="Question" selected="selected">Question</option> : <option value="Question">Question</option> }
                                </select>
                              : this.state.option }
                            </td>
                          </tr>
                          {this.state.option == "Phone" ?
                            <tr id="phone">
                              <td className="text-popups">Phone:</td>
                              <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/> : this.state.phone }</td>
                            </tr>
                          : null }
                          {this.state.option == "Question" ?
                            <tr id="question">
                              <td className="text-popups">Security Question:</td>
                              <td className="text-popups">
                                {this.state.edit == "true" ?
                                  <select id="formQuestion" name="security-question">
                                    {this.state.secQues == "" || this.state.secQues == "default" ? <option value="default" selected="selected">Select A Security Question</option> : <option value="default">Select A Security Question</option> }
                                    {this.state.secQues == 1 ? <option value="1" selected="selected">What is your middle name?</option> : <option value="1">What is your middle name?</option> }
                                    {this.state.secQues == 2 ? <option value="2" selected="selected">What is your mother's maiden name?</option> : <option value="2">What is your mother's maiden name?</option> }
                                    {this.state.secQues == 3 ? <option value="3" selected="selected">What was the name of the street where you grew up?</option> : <option value="3">What was the name of the street where you grew up?</option> }
                                    {this.state.secQues == 4 ? <option value="4" selected="selected">What is your favorite food?</option> : <option value="4">What is your favorite food?</option> }
                                  </select>
                                :
                                  <span>
                                    {this.state.secQues == 1 ? <span>What is your middle name?</span> : null}
                                    {this.state.secQues == 2 ? <span>What is your mother's maiden name?</span> : null}
                                    {this.state.secQues == 3 ? <span>What was the name of the street where you grew up?</span> : null}
                                    {this.state.secQues == 4 ? <span>What is your favorite food?</span> : null}
                                  </span>
                                }
                              </td>
                            </tr>
                          : null }
                          {this.state.option == "Question" ?
                            <tr id="answer">
                              <td className="text-popups">Security Answer:</td>
                              <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formAnswer" className="grade_desc input1" value={this.state.secAns} name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/> : this.state.secAns } </td>
                            </tr>
                          : null }
                          <tr><td></td></tr>
                          {this.state.edit == "true" ?
                            <tr>
                              <td colSpan="2">
                                <input type="hidden" name="formStep" value="editBuyerInfo" />
                                <input type="hidden" name="oldEmail" value={this.state.oldemail} />
                                <button type="submit" name="submit" id="editBuyerInformationSubmit" className="text-popups" onClick={this.verify}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                              </td>
                            </tr>
                          :
                            <tr id="editInformation">
                              <td className="text-popups" colSpan="2">
                                <input type="hidden" name="formStep" value="noEditContinue" />                                
                                <input type="hidden" name="email" value={this.state.email} />
                                Do you want to edit this information?
                                <span id="editOptions">
                                  {this.state.editYes == "true" ? <span className="indent"><i className="fa fa-check"></i> yes</span> : <span className="indent" onClick={this.handleEditOptionChange.bind(this,"yes")}><i className="fa fa-circle-thin"></i> yes</span> }
                                  {this.state.editNo == "true" ? <span><i className="fa fa-check"></i> no</span> : <button type="submit" name="submit" id="editBuyerInformationSubmit" className="text-popups"><i className="fa fa-circle-thin"></i> no</button> }
                                </span>
                              </td>
                            </tr>
                          }                          
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
