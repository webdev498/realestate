<? session_start();
include("dbconfig.php");
include 'functions.php';
include ('basicHead.php');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
limit(); // Rate limit to prevent scraping
if ((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); }
if(isset($_GET['agent'])){$assigned=$_GET['agent'];}else{$assigned='';}
if($_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
?>

<html>
<head>
  <meta name="robots" content="noindex">
  <title>HomePik - Agent Verification</title>
  <?php include_css("/views/css/verification.css"); ?>
  <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body class="varification_page">
  <? if(!isset($_GET['x'])){ ?>
    <div id="verificationBackground1"></div>
  <? }
  if(isset($_GET['x'])){ ?>
    <div id="verificationBackground2"></div>
  <? } ?>
  <div id="verification"></div>
  <div id='ajax-box'></div>
<script type="text/babel">
  var Verify = React.createClass({
    getInitialState: function() {
      return{
        firstname: "",
        lastname: "",
        email: "",
        agent_id: ""
      };
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    checkInput: function(){
      if( this.state.firstname != "" && this.state.lastname != "" && this.state.email != "" && this.state.agent_id != ""){ return true; }
      else{ return false; }
    },
    validate: function(e){
      var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
      var emailValid = emailReg.test(this.state.email);
      this.updatePhone();

      if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.agent_id == ""){
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
                <? if(!isset($_GET['x'])){ ?>
                  <div id="verificationArea" className='short'>
                <? }else{ ?>
                  <div id="verificationArea" className='long'>
                <? } ?>
                  <div id="verificationTop">
                    <span id="verificationHeader" className="text-popups">Agent Verification</span>
                  </div>
                  <div id="verificationBorder">
                    <form onSubmit={this.validate} action="http://homepik.com/controllers/users/agent-verification-process.php" id="validate" method="post" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup>
                          <col width="250"/><col width="350"/>
                        </colgroup>
                        <tbody>
                          <? if(isset($_GET['x'])){ ?>
                            <tr>
                            <td className="text-popups" colSpan="2">
                              <div style={{textAlign: "justify"}}>The password you entered did not match the one on file for the email entered. Return to agent login <a href='/controllers/agent-signin.php'>here</a> or enter the information below to verify who you are.<br/><br/></div>
                            </td>
                            </tr>
                          <? } ?>
                          <tr>
                            <td className="text-popups">First Name:</td>
                            <td className="text-popups"><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.state.firstname != "" ? null : <strong id="firstnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Last Name:</td>
                            <td className="text-popups"><input type="text" id="formLastName" className="grade_desc input1" name="lastName" onChange={this.handleChange.bind(this, 'lastname')} />{this.state.lastname != "" ? null : <strong id="lastnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                          </tr>
                          <tr>
                            <td className="text-popups">Email:</td>
                            <td className="text-popups"><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" name="email" onChange={this.handleChange.bind(this, 'email')} />{this.state.email != "" ? null : <strong id="emailMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td className="text-popups">Agent Code:</td>
                            <td className="text-popups"><input type="text" id="formAgentCode" className="grade_desc input1" name="agentCode" value={this.state.agent_id} onChange={this.handleChange.bind(this, 'agent_id')} />{this.state.agent_id != "" ? null : <strong id="agentIdMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2" id="fieldsAlert" className="text-popups">{this.checkInput() ? <span style={{color: "#D2008F", float:"right"}}> {'All Fields Filled'}</span> : <span style={{color: "#D2008F", float:"right"}}> {'\u002A Required Fields'}</span> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2">
                              <? if(!isset($_GET['x'])){ ?>
                                <input type="hidden" name="formStep" value="verification1" />
                              <? }
                              if(isset($_GET['x'])){ ?>
                                <input type="hidden" name="formStep" value="verification2" />
                              <? } ?>
                              <input type="hidden" name="code" value="<?=$password?>" /><br />
                              <a href="agent-signin.php"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Back</button></a>
                              <button type="submit" name="submit" id="verificationSubmit" className="text-popups">Verify <i id="arrow" className="fa fa-chevron-right"></i></button>
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
