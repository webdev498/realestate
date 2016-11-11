<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include_once('basicHead.php');
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db('sp', $con) or die(mysql_error());
$_SESSION['viewingBuyer'] = 'false';
$_SESSION['loadSaved'] = false;
if (!$_SESSION['email']){
  $_SESSION['email'] = 'guest@email.com';
  $_SESSION['role'] = 'guest';
}
$limit = limit();// If the user has been rate limited because of too many requests, cut them off (VOW RULE)
if ($limit != 'clear') { limit(); }

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

  <title>HomePik - Sell Your Home</title>
  <?php include_css("/views/css/sell-home.css");
  include_once("analyticstracking.php"); ?>
</head>
<body>
  <div id="sellHome"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
  var SellHome = React.createClass({
    getInitialState: function() {
      return{
        mainPage: "<? echo $mainPage ?>"
      };
    },
    render: function() {
      return (
        <div className="clearfix" id="page">
          <Header />
          <NavBar mainPage={this.state.mainPage} />
          <AddressSearch mainPage={this.state.mainPage} />
        </div>
      );
    }
  });

  ReactDOM.render(
    <SellHome/>,
    document.getElementById("sellHome")
  );

  ReactDOM.render(
    <Footer mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
