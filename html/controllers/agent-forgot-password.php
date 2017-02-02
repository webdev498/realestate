<?php
session_start();
include_once("functions.php");
include_once("basicHead.php");
if((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }

$step = isset($_GET['step']) ? $_GET['step'] : 1;
$email = isset($_GET['e']) ? $_GET['e'] : "";
$password = isset($_GET['p']) ? $_GET['p'] : "";
?>

<html>
<head>
  <meta name="robots" content="noindex">
  <title>HomePik - Agent Verification</title>
  <?php include_css("/views/css/verification.css"); ?>
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
        title: "",
        id: "",
        email: "<?php echo $email ?>",
        oldemail: "",
        phone: "",
        admin: "",
        bio: "",
        newPass: "<?php echo $password ?>",
        confPass: "<?php echo $password ?>",
        question: "",
        answer: "",
        step: <?php echo $step ?>,
        edit: "false",
        option: "",
        editYes: "false",
        editNo: "false"
      };
    },
    componentWillMount: function(){
      if(this.state.step == 4){
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"emailValidation": "true", "email": this.state.email },
          success: function(data){
            var info = jQuery.parseJSON(data);
  
            if(info != null){
              this.setState({firstname: info.first_name});
              this.setState({lastname: info.last_name});
              this.setState({id: info.agent_id});
              this.setState({oldemail: info.email});
              this.setState({title: info.title});
              this.setState({phone: info.phone});
              this.setState({admin: info.admin});
              this.setState({bio: info.bio});
              this.setState({question: info.security_question});
              this.setState({answer: info.security_answer});
              $("#verificationArea").hide();
              $("#agentInformationArea").show();
              $("#verificationBackground1").css('height', "130%");
            }            
          }.bind(this)
        });
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
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);

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
      }
      else{
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"emailValidation": "true", "email": this.state.email },
          success: function(data){
            var info = jQuery.parseJSON(data);
            console.log(info);
  
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
    verify_two: function(){
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
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('messages.php #agent_verification_blank_two',function(){
          $('#ajax-box').dialog('open');
        });
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
      }
      else{
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"partialValidation": "true", "email": this.state.email, "question": this.state.question, "answer": this.state.answer },
          success: function(data){
            var info = jQuery.parseJSON(data);
          
            if(info != null){
              $("#error").hide();
              this.setState({firstname: info.first_name});
              this.setState({lastname: info.last_name});
              this.setState({id: info.agent_id});
              this.setState({oldemail: info.email});
              this.setState({title: info.title});
              this.setState({phone: info.phone});
              this.setState({admin: info.admin});
              this.setState({bio: info.bio});
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
    },
    resetPassword: function(){
      if( this.state.newPass != "" && this.state.confPass != "" && this.state.newPass.length >= 5 && this.state.confPass.length >= 5 ){
        if(this.state.newPass == this.state.confPass){
          $.ajax({
            type: "POST",
            url: "check-agent.php",
            data: {"passReset": "true", "email": this.state.email, "password": this.state.newPass},
            success: function(status){
              $("#verificationArea").hide();
              $("#agentInformationArea").show();
              $("#verificationBackground1").css('height', "130%");
            }
          });
        }
        else{
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage',
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
    validateInformation: function(e){
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);

      if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.newPass == "" || this.state.question == "default" || this.state.answer == "" ){
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
        $('#ajax-box').load('messages.php #agent_information_edit_blank',function(){
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
    back: function(){
      window.location.assign("javascript:history.back()")
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
                    <img src="/images/button_my_profile.png" style={{float: "right"}}/>
                  </div>
                  <div id="verificationBorder">
                    <table cellPadding="2" cellSpacing="0" border="0">
                      <colgroup><col width="250"/><col width="350"/></colgroup>
                      <tbody>                        
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
                        {this.state.step == 1 || this.state.step == 2 ? <tr><td></td></tr> : null }
                        {this.state.step == 1 || this.state.step == 2 ?
                          <tr>
                            <td colSpan="2">
                              <a href="javascript:history.back()"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Agent Login</button></a>                            
                              {this.state.step == 1 ? <button type="submit" name="submit" id="verificationVerify" className="text-popups" onClick={this.verify}>Continue <i id="arrow" className="fa fa-chevron-right"></i></button> : null }                            
                              {this.state.step == 2 ? <button type="submit" name="submit" id="verificationSubmit" className="text-popups" onClick={this.verify_two}>Verify <i id="arrow" className="fa fa-chevron-right"></i></button> : null }
                            </td>
                          </tr>
                        : null }
                        {this.state.step == 3 ? <tr><td></td></tr> : null }
                        {this.state.step == 3 ?
                          <tr>
                            <td className="text-popups">New Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')} /></td>
                          </tr>
                        : null }
                        {this.state.step == 3 ?
                          <tr>
                            <td className="text-popups">Retype Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" autocomplete="off" size="25"onChange={this.handleChange.bind(this, 'confPass')} /></td>
                          </tr>
                        : null }
                        {this.state.step == 3 ? <tr><td></td></tr> : null }
                        {this.state.step == 3 ?
                          <tr>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="reset" />
                              <a href="javascript:history.back()"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Agent Login</button></a>                            
                              <button type="submit" name="submit" id="resetPasswordSubmit" className="text-popups" onClick={this.resetPassword}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                            </td>
                          </tr>
                        : null }
                      </tbody>
                    </table>
                  </div>
                </div>
                <div id="agentInformationArea" className='long' style={{display:"none"}}>
                  <div id="agentInformationTop">
                    <span id="agentInformationHeader" className="text-popups">Agent Information</span>
                    <img src="/images/button_pen_states.png" style={{float: "right"}}/>
                  </div>
                  <div id="agentInformationBorder">
                    <form onSubmit={this.validateInformation} action="users/edit-agent-information-process.php" id="validate editBuyerInformation" className="validate" method="get" autoComplete="off" data-bind="nextFieldOnEnter:true">
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
                            <td className="text-popups">Title:</td>
                            <td className="text-popups">{this.state.title}</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Agent ID:</td>
                            <td className="text-popups">{this.state.id}</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Email:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} /> : this.state.email }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Phone:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formPhone" className="grade_desc input1" value={this.state.phone} name="phone" onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone} /> : this.state.phone }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Password:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.newPass} name="password" onChange={this.handleChange.bind(this, 'newPass')} /> : this.state.newPass }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Security Question:</td>
                            <td className="text-popups">
                              {this.state.edit == "true" ?
                                <select id="formQuestion" name="security-question" onChange={this.handleChange.bind(this, 'question')}>
                                  {this.state.question == "default" ? <option value="default" selected="selected">Select A Security Question</option> : <option value="default">Select A Security Question</option> }
                                  {this.state.question == 1 ? <option value="1" selected="selected">What is your middle name?</option> : <option value="1">What is your middle name?</option>}
                                  {this.state.question == 2 ? <option value="2" selected="selected">What is your mother's maiden name?</option> : <option value="2">What is your mother's maiden name?</option>}
                                  {this.state.question == 3 ? <option value="3" selected="selected">What was the name of the street where you grew up?</option> : <option value="3">What was the name of the street where you grew up?</option>}
                                  {this.state.question == 4 ? <option value="4" selected="selected">What is your favorite food?</option> : <option value="4">What is your favorite food?</option>}
                                </select>
                              :
                                <span>
                                  {this.state.question == 1 ? <span>What is your middle name?</span> : null}
                                  {this.state.question == 2 ? <span>What is your mother's maiden name?</span> : null}
                                  {this.state.question == 3 ? <span>What was the name of the street where you grew up?</span> : null}
                                  {this.state.question == 4 ? <span>What is your favorite food?</span> : null}
                                </span>
                              }
                            </td>
                          </tr>                          
                          <tr>
                            <td className="text-popups">Security Answer:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formAnswer" className="grade_desc input1" value={this.state.answer} name="security-answer" onChange={this.handleChange.bind(this, 'answer')}/> : this.state.answer }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Administrator:</td>
                            <td className="text-popups">{this.state.admin == "Y" ? <span>Yes</span> : <span>No</span>}</td>
                          </tr>
                          <tr>
                            <td id="bio" className="text-popups">Bio:</td>
                            <td className="text-popups">{this.state.edit == "true" ? <textarea type="text" id="formBio" className="grade_desc input1" name="bio" value={this.state.bio} onChange={this.handleChange.bind(this, 'bio')}/> : this.state.bio }</td>
                          </tr>
                          <tr><td></td></tr>
                          {this.state.edit == "true" ?
                            <tr>
                              <td colSpan="2">
                                <input type="hidden" name="formStep" value="editAgentInfo" />
                                <input type="hidden" name="oldEmail" value={this.state.oldemail} />
                                <button type="submit" name="submit" id="editAgentInformationSubmit" className="text-popups" onClick={this.verify}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
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
                                  {this.state.editNo == "true" ? <span><i className="fa fa-check"></i> no</span> : <button type="submit" name="submit" id="editAgentInformationSubmit" className="text-popups"><i className="fa fa-circle-thin"></i> no</button> }
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
