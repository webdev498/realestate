<?php
session_start();
include_once('functions.php');
include_once("basicHead.php");
?>

  <title>HomePik - Change Email Alert Settings</title>
  <?php include_css("/views/css/change-email-alert-settings.css");
  include_once('autoLogout.php'); ?>
  <script type="text/javascript">
    document.write('\x3Cscript src="' + (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//webfonts.creativecloud.com/lato:n3,n4,i4,n7:default.js" type="text/javascript">\x3C/script>');
  </script>
</head>
<body>
  <div id="changeSettings"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var ChangeSettings = React.createClass({
    getInitialState: function() {
      return{
        email: "<?php echo (isset($_GET['user']) ? $_GET['user'] : "") ?>"
      };
	  },
    setNotify: function(){
      $("input[value=notify]").prop("checked",true);
    },
    setNotifyOptions: function(option){
      if(option == "set"){ $("input[name^='secondSetting']").attr("checked", true); }
      else if(option == "unset"){ $("input[name^='secondSetting']").attr("checked", false); }
    },
    checkSecondSettings: function(option){
      if($("input[name^='secondSetting']:checked").length == 0){
        $("input[value="+option+"]").prop("checked", true);
      }
    },
    validate: function(e){
      //e.preventDefault();
    },
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar />
            <AddressSearch />
            <div className="Text-1" id="u8521-1">
              <span className="Text-1" id="u8522-1">Change Email Alert Settings</span>
              <form onSubmit={this.validate} action="/controllers/users/change-email-alert-settings-process.php" id="validate changeEmailSettings" className="validate" method="get">
                <table cellPadding="2" cellSpacing="0" border="0">
                  <colgroup><col width="400"/></colgroup>
                  <tbody>
                    <tr>
                      <td><input type="radio" name="setting" value="notify" onClick={this.setNotifyOptions.bind(this, "set")}/> Notify me by email when: </td>
                    </tr>
                    <tr>
                      <td className="indent"><input type="checkbox" name="secondSetting[]" value="messages" onClick={this.setNotify} onChange={this.checkSecondSettings.bind(this, "messages")}/> Someone sends me a message</td>
                    </tr>
                    <tr>                      
                      <td className="indent"><input type="checkbox" name="secondSetting[]" value="folder" onClick={this.setNotify} onChange={this.checkSecondSettings.bind(this, "folder")}/> Someone adds a listing to my folder</td>
                    </tr>
                    <tr>
                      <td><input type="radio" name="setting" value="noNotify" onClick={this.setNotifyOptions.bind(this, "unset")}/> Never notify me by email</td>                      
                    </tr>
                    <tr><td style={{paddingBottom: 0}}>&nbsp;</td></tr>
                    <tr>
                      <td colSpan="2">
                        <input type="hidden" name="formStep" value="changeSettings" />
                        <input type="hidden" name="user" value={this.state.email} />
                        <button type="submit" name="submit" id="changeSettingsSubmit" className="text-popups">Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
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
    <ChangeSettings/>,
    document.getElementById("changeSettings")
  )

  ReactDOM.render(
	  <Footer />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>
