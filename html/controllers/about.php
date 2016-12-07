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

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

  <title>HomePik - About HomePik</title>
  <?php include_css("/views/css/about-us.css");
  include_once("analyticstracking.php"); ?>
</head>
<body>
  <div id="about"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
  var About = React.createClass({
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
          <div id="container-fluid">
            <div className="row-fluid">
              <div className="col-md-5ths">
                <h2 className="panel-heading u6175-4">About HomePik</h2>
                <div className="panel">
                  <div className="panel-body about-panel">
                    <h3 className="FAQ-1-grey-bd" id="u6178-2">The Background</h3><br />
                    <p className="Text-1">Neil Binder is the inventor of Selection Portfolio, the patented technology underlying HomePik. In his real estate experience of over 36 years, Neil realized that the three basic questions asked by customers have never changed.</p><br />
                    <p className="Text-1"><span id="u6178-10">1&nbsp;</span>What is available in my price range?</p>
                    <p className="Text-1"><span id="u6178-16">2&nbsp;</span>Which apartment should I buy?</p>
                    <p className="Text-1"><span id="u6178-22">3&nbsp;</span> How much should I pay for it?</p>
                  </div>
                </div>
              </div>
              <div className="col-md-5ths">
                <h2 className="panel-heading u6175-4">&nbsp;&nbsp;</h2>
                <div className="panel">
                  <div className="panel-body about-panel panel-border">
                    <h3 className="FAQ-1-grey-bd" id="u6183-2">The Problem</h3><br />
                    <p className="Text-1">There is a lot of insecurity and confusion involved in buying a home and as a result customers want to see an enormous number of apartments in order to understand the market.</p><br />
                    <p className="Text-1">This can frustrate salespeople who are inclined to say, &quot;trust me, it’s a good deal.&quot; Why should buyers trust a salesperson? Buyers know salespeople have a vested interest in making a sale. What proof is being offered?</p>
                  </div>
                </div>
              </div>
              <div className="col-md-5ths">
                <h2 className="panel-heading u6175-4">&nbsp;&nbsp;</h2>
                <div className="panel">
                  <div className="panel-body about-panel panel-border">
                    <h3 className="FAQ-1-grey-bd" id="u6179-2">The Solution: HomePik</h3><br />
                    <p className="Text-1">HomePik is the only product, site or search engine that can analyze, rate and compare apartments to find the best one for a particular buyer.</p>
                  </div>
                </div>
              </div>
              <div className="col-md-5ths">
                <h2 className="panel-heading u6175-4">&nbsp;&nbsp;</h2>
                <div className="panel">
                  <div className="panel-body about-panel panel-border">
                    <h3 className="FAQ-1-grey-bd" id="u6176-2">The Details</h3><br />
                    <p className="Text-1">We have graded every apartment in Manhattan. Each grade means something specific so consistency is ensured. Floor plans were used to document the sizes of apartments. We now have the best database in the city, and are constantly updating it.</p><br />
                    <p className="Text-1">There are two sides to HomePik, the buyer side and the agent side. Both agents and buyers can browse and save listings on their own, but HomePik also allows agents and buyers to interact. Once connected, they can share buying formulas and listings, conduct further searches, and communicate.</p>
                  </div>
                </div>
              </div>
              <div className="col-md-5ths">
                <h2 className="panel-heading u6175-4">&nbsp;&nbsp;</h2>
                <div className="panel panel">
                  <div className="panel-body about-panel panel-border">
                    <h3 className="FAQ-1-grey-bd" id="u6180-2">The Difference</h3><br />
                    <p className="Text-1">The breadth of data HomePik analyzes when making evaluations far surpasses what any human could do. Up to now, agents have relied primarily on instinct and experience. They are fundamentally limited in their ability to process vast amounts of data. Data analysis is something computers excel at. HomePik takes care of the technical side so that agents can focus on the human element: customers and clients.</p><br />
                    <p className="Text-1">You will not find a product like HomePik anywhere else because the right to compare apartment listings on a website is patented.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="content"><div className="row"><div><p> </p></div></div></div>
          <div id="container">
            <div className="row">
              <div>
                <p className="about-phrase">HomePik is the future of real estate. If you're not using it, you're missing out.</p>
                <p> </p>
              </div>
              <div className="col-md-3 video-image">
                <p className="FAQ-1-grey-bd" style={{marginBottom: 20 + "px"}}>Video Overview of HomePik</p>
                <div>
                  <iframe id="video" className="video-container" src="https://www.youtube.com/embed/Yv_O-j-cRTo" frameBorder="0" allowFullScreen></iframe>
                </div>
              </div>
              <div className="col-md-4 col-md-offset-2 pdf-image">
                <p className="FAQ-1-grey-bd" style={{marginBottom: 20 + "px"}}>PowerPoint Presentation</p>
                <div>
                  <a href="/documents/HomePik_Investor_Presentation.pdf"><img className="image-container" alt="press release" src="http://homepik.com/images/investimage.JPG" title="PowerPoint Presentation" /></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      );
    }
  });

  ReactDOM.render(
    <About/>,
    document.getElementById("about")
  );

  ReactDOM.render(
    <Footer mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
