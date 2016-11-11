<?php
session_start();
include_once('functions.php');
include("basicHead.php");
?>

  <title>HomePik - Reset Password</title>
  <?php include_css("/views/css/password-reset.css"); ?>
  <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body>
  <div id="resetBackground"></div>
  <div id="resetPass"></div>
  <div id='ajax-box'></div>
<script type="text/babel">
  var Reset = React.createClass({
    getInitialState: function() {
      return{
        email: "",
        newPass: "",
        confPass: ""
      };
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    checkInput: function(){
      if(this.state.email != "" || this.state.newPass != "" || this.state.confPass != ""){ return true; }
      else{ return false; }
    },
    validate: function(e){
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);

      if( this.state.email == "" || this.state.newPass == "" || this.state.confPass == ""){
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
        $('#ajax-box').load('messages.php #registerRquirements',function(){
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
        $('#ajax-box').load('messages.php #invalidBuyerEmail',function(){
          $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
        });
        e.preventDefault();
      }
      else if(this.state.newPass.length < 5 || this.state.confPass.length < 5){
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
    },
    render: function(){
      return(
        <div id="wrapper">
          <div className="container-fluid">
            <div className="row">
              <div className="col-md-2 col-sm-2"></div>
              <div className="col-md-7 col-sm-10 col-xs-11">
                <div id="resetArea">
                  <div id="resetTop">
                    <span id="resetHeader" className="text-popups">Reset your password</span>
                    <img src="/images/button_lock.png" style={{float: "right"}}/>
                  </div>
                  <div id="resetBorder">
                    <form onSubmit={this.validate} action="http://homepik.com/controllers/users/pass-reset-process.php" id="validate resetPass" className="validate" method="get" autoComplete="off" data-bind="nextFieldOnEnter:true">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup>
                          <col width="250"/><col width="350"/>
                        </colgroup>
                        <tbody>
                          <tr style={{display: "none"}}><td className="text-popups">Email: </td><td className="text-popups"><input type="text" autoCapitalize="off" className="users input1 required email" name="email" size="25"/></td></tr>
                          <tr style={{display: "none"}}><td className="text-popups">New Password: </td><td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25"/></td></tr>
                          <tr style={{display: "none"}}><td className="text-popups">Confirm Password: </td><td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" size="25"/></td></tr>
                          <tr>
                            <td className="text-popups">Email: </td>
                            <td className="text-popups"><input type="text" autoCapitalize="off" className="users input1 required email" name="email" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'email')} />{this.state.email != "" ? null : <strong id="emailMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">New Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')} />{this.state.newPass != "" ? null : <strong id="newPassMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Confirm Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" autocomplete="off" size="25"onChange={this.handleChange.bind(this, 'confPass')} />{this.state.confPass != "" ? null : <strong id="confPassMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2" id="fieldsAlert" className="text-popups">{this.checkInput() ? <span style={{color:"#D2008F", fontWeight:'bold', float:"right"}}> {'All Fields Filled'}</span> : <span style={{color:'#D2008F', float:"right"}}> {'\u002A Required Fields'}</span> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2" style={{textAlign: "center"}}>
                              <input type="hidden" name="formStep" value="reset" />
                              <br/>
                              <button type="submit" name="submit" id="resetSubmit" className="text-popups">Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
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
    <Reset/>,
    document.getElementById("resetPass")
  )
</script>
</body>
</html>
