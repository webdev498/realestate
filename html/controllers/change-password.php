<?php
session_start();
include_once('functions.php');
include_once("basicHead.php");

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

  <title>HomePik - Change Password</title>
  <?php include_css("/views/css/change-password.css");
  include_once('autoLogout.php'); ?>
  <script type="text/javascript">
    document.write('\x3Cscript src="' + (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//webfonts.creativecloud.com/lato:n3,n4,i4,n7:default.js" type="text/javascript">\x3C/script>');
  </script>
</head>
<body>
  <div id="changePassword"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var ChangePassword = React.createClass({
    getInitialState: function() {
      return{
        oldPass: "",
        newPass: "",
        conPass: "",
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>"
      };
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
	  checkInput: function(name){
      if(this.state.oldPass != '' && this.state.newPass != '' && this.state.conPass != ''){ return true; }
      else{ return false; }
    },
    validate: function(e){
      if( this.state.oldPass == "" || this.state.newPass == "" || this.state.conPass == ""){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() { $( this ).dialog( "destroy" ); },
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
      else if( this.state.oldPass.length < 5 || this.state.newPass.length < 5 || this.state.conPass.length < 5){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() { $( this ).dialog( "destroy" ); },
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
      else if( this.state.newPass != this.state.conPass){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons : {
            Ok: function(){ $(this).dialog("close"); }
          },
          close: function() { $( this ).dialog( "destroy" ); },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #passwordsMatch',function(){
          $('#ajax-box').dialog('open');
        });
        e.preventDefault();
      }
      else{
        // Go to process page.
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
              <span className="Text-1" id="u8522-1">Change Password</span>
              <form onSubmit={this.validate} action="/controllers/users/change-pass.php" id="validate resetPass" className="validate" method="get">
                <table cellPadding="2" cellSpacing="0" border="0">
                  <colgroup><col width="250"/><col width="315"/></colgroup>
                  <tbody>
                    <tr style={{display: "none"}}><td>Type in your old password: </td><td><input type="password" className="users input1 required oldPassword" name="oldPassword" size="25"/></td></tr>
                    <tr style={{display: "none"}}><td>Type in your new password: </td><td><input type="password" className="users input1 required newPassword" name="newPassword" size="25"/></td></tr>
                    <tr style={{display: "none"}}><td>Confirm your new password: </td><td><input type="password" className="users input1 required confirmPassword" name="confirmPassword" size="25"/></td></tr>
                    <tr>
                      <td>Type in your old password: </td>
                      <td><input type="password" className="users input1 required oldPassword" name="oldPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'oldPass')}/>{this.state.oldPass != '' ? null : <strong id="oldPassMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                    </tr>
                    <tr>
                      <td>Type in your new password: </td>
                      <td><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')}/>{this.state.newPass != '' ? null : <strong id="newPassMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                    </tr>
                    <tr>
                      <td>Confirm your new password: </td>
                      <td><input type="password" className="users input1 required confirmPassword" name="confirmPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'conPass')}/>{this.state.conPass != '' ? null : <strong id="conPassMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong> {'All Fields Filled'}</strong> : <strong> {'\u002A Required Fields'}</strong> }</td>
                    </tr>
                    <tr><td style={{paddingBottom: 0}}>&nbsp;</td></tr>
                    <tr>
                      <td colSpan="2">
                        <input type="hidden" name="formStep" value="reset" />
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
    <ChangePassword />,
    document.getElementById("changePassword")
  )

  ReactDOM.render(
	  <Footer mainPage={"<?php echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>
