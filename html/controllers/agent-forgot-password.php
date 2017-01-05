<?php
session_start();
include("functions.php");
include_once("basicHead.php");
if ((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
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
        email: "",
        oldemail: "",
        phone: "",
        admin: "",
        bio: "",
        newPass: "",
        confPass: "",
        step: 1,
        edit: "false",
        option: "",
        editYes: "false",
        editNo: "false"
      };
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
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);

      if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.id == ""){
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
          data: {"fullValidationInfo": "true", "email": this.state.email, "firstName": this.state.firstname, "lastName": this.state.lastname, "id": this.state.id},
          success: function(data){
            var info = jQuery.parseJSON(data);
          
            if(info != null){
              $("#error").hide();
              this.setState({oldemail: info.email});
              this.setState({title: info.title});
              this.setState({phone: info.phone});
              this.setState({admin: info.admin});
              this.setState({bio: info.bio});
              this.setState({step: 2});
              $(".step2Rows").show();
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

      if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.newPass == "" || this.state.phone == ""){
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
                            <a href="javascript:history.back()"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Back</button></a>                            
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
