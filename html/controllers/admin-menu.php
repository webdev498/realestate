<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
$_SESSION['viewingBuyer'] = 'false';

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 'N'){ print "<script> window.location = 'menu.php' </script>"; }
?>

  <title>HomePik - Administrator Menu</title>
  <?php include_css("/views/css/admin-menu.css");
  include_once("analyticstracking.php");
  include_once('autoLogout.php'); ?>
</head>
<body>
  <div id="menu"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
  <div id='ajax-box3'></div>
<script type="text/babel">
  var Menu = React.createClass({
    render: function () {
      return (
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <MenuNavBar />
            <AddressSearch mainPage={""}/>
            <div id="main-section">
              <div id="pu7925">
                <div className="clip_frame grpelem menu-image first-image" id="u7925">
                  <a href="analytics.php?MP=admin"><img className="position_content" id="u28169_img" src="../images/fotolia_57355360_subscription_monthly_m-crop-u7925.jpg" alt=""/></a>
                </div>
                <div className="clip_frame clearfix grpelem menu-image" id="u7929">
                  <a href="analysis.php?MP=admin"><img className="position_content" id="u7929_img" src="../images/fotolia_54239025_subscription_monthly_m-crop-u7929.jpg" alt=""/></a>
                </div>
                <div className="clip_frame clearfix grpelem menu-image" id="u7927">
                  <a href="manage-agents.php?MP=admin"><img className="position_content" id="u7927_img" src="../images/fotolia_69914734_subscription_monthly_m-crop-u7927.jpg" alt=""/></a>
                </div>
                <div className="clip_frame grpelem menu-image last-image" id="u8055">
                  <a href="user-information.php?MP=admin"><img className="block" id="u8055_img" src="../images/fotolia_73766424_coloradjust-crop-u8055.jpg" alt=""/></a>
                </div>
              </div>
              <div id="u8048">
                <a className="button-links" href="analytics.php?MP=admin">
                  <div className="museBGSize grpelem" id="u7952-2"><span className="button-text" style={{top: 110 + 'px'}}>ANALYTICS</span></div>
                </a>
                <a href="analysis.php?MP=admin" className="button-links ">
                  <div className="museBGSize grpelem" id="u28069"><span className="button-text">ACTIVITY ANALYSIS</span></div>
                </a>
                <a href="manage-agents.php?MP=admin" className="agents button-links">
                  <div className="museBGSize grpelem" id="u28093"><span className="button-text">MANAGE AGENTS</span></div>
                </a>
                <a href="user-information.php?MP=admin" className="user-info button-links last-button">
                  <div className="museBGSize grpelem" id="u27965"><span className="button-text">USER INFORMATION</span></div>
                </a>
              </div>
            </div>
            <div className="verticalspacer"></div>
          </div>

        </div>
      );
    }
  });

  ReactDOM.render(
    <Menu/>,
    document.getElementById("menu")
  );

  ReactDOM.render(
    <Footer mainPage={"admin"} />,
    document.getElementById("footer")
  );
</script>
</body>
</html>
