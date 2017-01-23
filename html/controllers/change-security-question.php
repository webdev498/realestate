<?php
session_start();
include_once("/controllers/dbconfig.php");
include_once('/controllers/functions.php');
include_once('/controllers/basicHead2.php');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());

if(isset($_SESSION['buyer'])){
	$email = $_SESSION['email'];
	$result = mysql_query( "SELECT security_question, security_answer FROM `users` WHERE email ='".$email."'" ) or die("Couldn't execute query.".mysql_error());
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	$secQues = $row['security_question'];
    $secAns = $row['security_answer'];
}

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");
?>

  <title>HomePik - Change Password</title>
  <?php include_css("/views/css/change-password.css");
  include_once('autoLogout.php'); ?>

</head>
<body>
  <div id="changePassword"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var ChangeSecurity = React.createClass({
    getInitialState: function() {
      return{
        secQues: "<?php echo (isset($secQues) ? $secQues : "default") ?>",
        secAns: "<?php echo (isset($secAns) ? $secAns : "") ?>",
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>"
      };
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
	  checkInput: function(name){
      if(this.state.secQues != 'default' && this.state.secAns != ''){ return true; }
      else{ return false; }
    },
    validate: function(e){
      if( this.state.secQues == "default" || this.state.secAns == ""){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage registerRequirements',
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
        $('#ajax-box').load('messages.php #registerRequirements',function(){
          $('#ajax-box').dialog('open');
        });
        e.preventDefault();
	  }
    },
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar mainPage={this.state.mainPage}  />
            <AddressSearch mainPage={this.state.mainPage}  />
            <div className="Text-1" id="u8521-1">
			<span className="Text-1" id="u8522-1">Update Security Question</span> 
              <form onSubmit={this.validate} action="users/change-security.php" id="validate" className="validate" method="get">
                <table cellPadding="2" cellSpacing="0" border="0">
                  <colgroup><col width="250"/><col width="300"/></colgroup>
                  <tbody>
                    <tr>
						<td className="text-popups security">Security Question:</td>
                            <td>
							<select id="secQues" name="security-question" value={this.state.secQues} onChange={this.handleChange.bind(this, 'secQues')}>
								<option value="default">Select A Security Question</option>
								<option value="1">What is your middle name?</option>
								<option value="2">What is your mother's maiden name?</option>
								<option value="3">What was the name of the street where you grew up?</option>
								<option value="4">What is your favorite food?</option>
                            </select>
							</td>
					</tr>
					<tr id="answer">
					  <td className="text-popups security">Security Answer:</td>
					  <td className="text-popups"><input type="text" value={this.state.secAns} id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/></td>
					</tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong> {'All Fields Filled'}</strong> : <strong> {'\u002A Required Fields'}</strong> }</td>
                    </tr>
					<tr><td style={{paddingBottom: 0}}>&nbsp;</td></tr>
                    <tr>
                      <td colSpan="2">
                        <button type="submit" name="submit" id="changePasswordSubmit" className="text-popups">Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>
          </div>
        </div>
      );
    }
  });

  ReactDOM.render(
    <ChangeSecurity />,
    document.getElementById("changePassword")
  )

  ReactDOM.render(
	  <Footer mainPage={"<?php echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>
