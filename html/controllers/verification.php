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
  <title>HomePik - Buyer Verification</title>
  <?php include_css("/views/css/verification.css"); ?>
  <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body class="verification_page">
  <div id="verificationBackground2"></div>
  <div id="verification"></div>
  <div id='ajax-box'></div>

<script type="text/babel">
  var Verify = React.createClass({
    getInitialState: function() {
      return{
        firstname: "",
        lastname: "",
        email: "",
        phone: "",
        secQues: "default",
        secAns: "",
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
          $('#ajax-box').load('/controllers/messages.php #invalidBuyerPhone',function(){
            $('#ajax-box').dialog( "option", "title", "Invalid Phone Number" ).dialog('open');
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
          url: "/controllers/check-buyer.php",
          data: {"email": this.state.email, "firstName": this.state.firstname, "lastName": this.state.lastname},
          success: function(data){
            var info = jQuery.parseJSON(data);
  
            if(info != null){
              $("#error").hide();
              if(info.phone != "" && info.phone != null ){
                $("#phone").show();
              }
  
              if((info.phone == "" || info.phone == null) && info.security_question != "" && info.security_question != null && info.security_question != "default" ){
                this.setState({secQues: info.security_question});
                $("#formQuestion").val(info.security_question);
                $("#question").show();
                $("#answer").show();
              }
              
              this.setState({step: 2});
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
        this.updatePhone();
  
        if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || (this.state.phone == "" && (this.state.secQues == "default" || this.state.secAns == "" ))){
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
      }
      else{        
        e.preventDefault();
      }
    },
    back: function(e){
      e.preventDefault();
      window.location.assign("signin.php");
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
                    <span id="verificationHeader" className="text-popups">Buyer Verification</span>
                  </div>
                  <div id="verificationBorder">
                    <form onSubmit={this.validate} action="http://homepik.com/controllers/users/verification-process.php" id="validate" method="post" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup>
                          <col width="250"/><col width="350"/>
                        </colgroup>
                        <tbody>
                          <tr>
                            <td className="text-popups" colSpan="2">
                              <div style={{textAlign: "justify"}}>The password you entered did not match the one on file for the email entered. Return to buyer login <a href='/controllers/signin.php'>here</a> or enter the information below to verify who you are.<br/><br/></div>
                            </td>
                          </tr>
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
                          <tr id="phone" style={{display:"none"}}>
                            <td className="text-popups">Phone:</td>
                            <td className="text-popups"><input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/>{this.state.phone != "" ? null : <strong id="phoneMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
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
                            <td className="text-popups"><input type="text" id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/>{this.state.secAns != "" ? null : <strong id="phoneMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr id="error" style={{display: "none"}}>
                            <td className="text-popups" colSpan="2"><strong style={{color: "#D2008F"}}>{'\u002A'} No account associated with that email.</strong></td>
                          </tr>
                          <tr>
                            <td colSpan="2" id="fieldsAlert" className="text-popups">{this.checkInput() ? <span style={{color: "#D2008F", float:"right"}}> {'All Fields Filled'}</span> : <span style={{color: "#D2008F", float:"right"}}> {'\u002A Required Fields'}</span> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2">
                            <div id="disclaimer" className="text-popups">Confidential Information: A Registered buyer is given access to listings which are not available to the public and are confidential. </div>
                            </td>
                          </tr>
                          <tr>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="verification2" />
                              <input type="hidden" name="code" value="<?=$password?>" /><br />
                              <a href="signin.php"><button id="backBtn" className="text-popups" onClick={this.back}><i id="arrow" className="fa fa-chevron-left"></i> Back</button></a>
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
