<?php
session_start();
include_once('functions.php');
include_once("basicHead.php");
if((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); };
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
?>

  <title>HomePik - Agent Login</title>
  <?php include_css("/views/css/signin.css"); ?>
</head>
<body>
  <div id="signinBackground"></div>
  <div id="signin"></div>
  <div id="ajax-box"></div>
<script type="text/babel">
  var Signin = React.createClass({
    getInitialState: function() {
      return{
        email: "<?php echo (isset($_GET['e']) ? $_GET['e'] : "") ?>",
        pass: ""
      };
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
	  validate: function(e){
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);

	    if( this.state.email == "" || this.state.pass == "" ){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() { 
            $( this ).dialog( "destroy" );
            $("#ajax-box").html("");
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #registerRquirements',function(){
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
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() {
            $( this ).dialog( "destroy" );
            $("#ajax-box").html("");
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #invalidBuyerEmail',function(){
          $('#ajax-box').dialog('open');
        });
        e.preventDefault();
      }
      else if(this.state.pass.length < 5){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() {
            $( this ).dialog( "destroy" );
            $("#ajax-box").html("");
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #passwordRequirement',function(){
          $('#ajax-box').dialog('open');
        });
        e.preventDefault();
      }
      else{
        // Continue to process
      }
    },
	  render: function(){
      return(
        <div id="wrapper">
          <div className="container-fluid">
            <div className="row">
              <div className="col-md-2 col-sm-2"></div>
              <div className="col-md-9 col-sm-9 col-xs-11">
                <div id="agentSigninArea">
                  <div id="signinHeader">
                    <span id="loginHeader" className="text-popups">Agent Login</span>
                    <img src="/images/button_agent.png" style={{float: "right"}}/>
                  </div>
                  <div id="signinBorder">
                    <form onSubmit={this.validate} action="http://homepik.com/controllers/users/agent-process.php" id="validate" method="post" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup><col width="250"/><col width="350"/></colgroup>
                        <tbody>
                          <tr style={{display: "none"}}><td className="text-popups">Email: </td><td className="text-popups"><input type="text" autoCapitalize="off" className="users input1 required email" name="email" size="25"/></td></tr>
                          <tr style={{display: "none"}}><td className="text-popups">Password: </td><td className="text-popups"><input type="password" className="users input1 required" minLength="4" name="password"/></td></tr>
                          <tr>
                            <td className="text-popups">Email: </td>
                            <td className="text-popups"><input type="text" autoCapitalize="off" className="users input1 required email" name="email" size="25" value={this.state.email} onChange={this.handleChange.bind(this, 'email')}/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Password: </td>
                            <td className="text-popups"><input type="password" className="users input1 required" minLength="4" name="password" onChange={this.handleChange.bind(this, 'pass')}/></td>
                          </tr>
                          <tr>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="login-register" />
                              <input type="hidden" name="role" value="agent" />
                              <button type="submit" name="submit" id="signinSubmit" className="text-popups">Continue <i id="arrow" className="fa fa-chevron-right"></i></button>
                            </td>
                          </tr>
                          <tr><td></td></tr>
                          <tr>
                            <td colSpan="2" algin="center">
                              <a href="agent-forgot-password.php" className="text-popups">Forgot Your Password ?</a>
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
    <Signin/>,
    document.getElementById("signin")
  )
</script>
</body>
</html>
