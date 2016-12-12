<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "index");
?>

  <title>HomePik - Sell Your Home</title>
  <?php include_css("/views/css/sell-home.css");
	include_once("analyticstracking.php") ?>
</head>
<body>
  <div id="sellHome"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
  var SellHome = React.createClass({
    render: function() {
      return (
        <div className="clearfix borderbox" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <div className="container-fluid">
              <div className="row-fluid">
                <div className="clearfix colelem" id="pu106-6">
                  <div className="browser_width grpelem" id="u106-6-bw">
                    <div className="Subhead-2 clearfix" id="u106-6">
                      <h2>
                        <span id="u106">Selling</span> Your Home? List with <span id="u106-3">HomePik</span>
                        <span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/index.php"><span className="fa fa-chevron-left"></span> Home</a></span>
                      </h2>
                    </div>
                  </div>
                </div>
                <div className="text-popups clearfix colelem" id="u124-15">
                  <h4 id="u124-6">HomePik is the <span id="u124-2">only</span> brokerage that uses <span id="u124-4">current market data</span> to price your home competitively.</h4>
                  <h4 id="u124-7">&nbsp;</h4>
                  <h4 id="u124-11">Contact us for a <span id="u124-9">free</span> consultation:</h4>
                  <h4 id="u124-13">212- 421-1122&nbsp;&nbsp;&nbsp; |&nbsp;&nbsp;&nbsp; sellers@homepik.com</h4>
                </div>
                <div className="col-md-5ths">
                  <div className="panel">
                    <div className="panel-body first-panel sell-home-panel">
                      <div className="clearfix grpelem" id="u116">
                        <div className="text-popups clearfix grpelem" id="u117-11">
                          <h4 id="u117-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Smarter</h4>
                          <h4 id="u117-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; pricing</h4>
                          <h4>&nbsp;</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u117-7">Market valuation.</span> HomePik is the only system that actively values your property against current competition, helping you price your home based on market conditions today.</h4>
                        </div>
                        <div className="clip_frame grpelem" id="u118">
                          <img id="u119" src="/images/icon_circle_pricetag.png" width="75" height="75" alt="" />
                        </div>
                      </div>                     
                    </div>
                  </div>
                </div>
                <div className="col-md-5ths">
                  <div className="panel">
                    <div className="panel-body sell-home-panel">
                      <div className="clearfix grpelem" id="u112">
                        <div className="text-popups clearfix grpelem" id="u115-18">
                          <h4 id="u115-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Simpler</h4>
                          <h4 id="u115-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; selling</h4>
                          <h4 id="u115-5">&nbsp;</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u115-7">Seamless promotion.</span> HomePik coordinates with brokers to create an effective sales strategy tailored to your home.</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u115-11">Better prospects.</span><span id="u115-14"> <br/></span>Our patented algorithm makes sure your home will appear to the people most likely to buy it.</h4>
                        </div>
                        <div className="clip_frame grpelem" id="u113">
                          <img id="u114" src="/images/icon_circle_house_price.png" width="75" height="75" alt="" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div className="col-md-5ths">
                  <div className="panel">
                    <div className="panel-body sell-home-panel">
                      <div className="clearfix grpelem" id="u129">
                        <div className="text-popups clearfix grpelem" id="u132-13">
                          <h4 id="u132-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Awesome</h4>
                          <h4 id="u132-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; access</h4>
                          <h4>&nbsp;</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u132-7">Powerful network.</span> <br/>As a member of the NYC Broker Alliance, HomePik coordinates with the best agents in the business to promote your home to the largest number of prospective buyers.</h4>
                        </div>
                        <div className="clip_frame grpelem" id="u130">
                          <img id="u131" src="/images/icon_circle_house_arrows.png" width="75" height="75" alt="" />
                        </div>
                      </div>                      
                    </div>
                  </div>
                </div>
                <div className="col-md-5ths">
                  <div className="panel">
                    <div className="panel-body sell-home-panel">
                      <div className="clearfix grpelem" id="u125">
                        <div className="text-popups clearfix grpelem" id="u126-13">
                          <h4 id="u126-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; No-obligation</h4>
                          <h4 id="u126-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; evaluation</h4>
                          <h4 id="u126-5">&nbsp;</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u126-7">Custom report</span><span id="u126-8">.</span><br/>A qualified agent will visit your home and generate a detailed report analyzing&nbsp; your home's value compared to similar properties in the current market.</h4>
                        </div>
                        <div className="clip_frame grpelem" id="u127">
                          <img id="u128" src="/images/icon_circle_pad_pencil.png" width="75" height="75" alt="" />
                        </div>
                      </div>                      
                    </div>
                  </div>
                </div>
                <div className="col-md-5ths">
                  <div className="panel">
                    <div className="panel-body sell-home-panel">
                      <div className="clearfix grpelem" id="u120">
                        <div className="text-popups clearfix grpelem" id="u121-12">
                          <h4 id="u121-2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Promise of</h4>
                          <h4 id="u121-4">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; satisfaction</h4>
                          <h4 id="u121-5">&nbsp;</h4>
                          <h4>&nbsp;</h4>
                          <h4><span id="u121-7">Our guarantee. </span><br/>If you are not satisfied with our service at any time, you may terminate our agreement.</h4>
                        </div>
                        <div className="clip_frame grpelem" id="u122">
                          <img id="u123" src="/images/icon_circle_star.png" width="75" height="75" alt="" />
                        </div>
                      </div>                     
                    </div>
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
    <SellHome/>,
    document.getElementById("sellHome")
  );

  ReactDOM.render(
    <Footer mainPage={"<?php echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
