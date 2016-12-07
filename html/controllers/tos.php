<?php
session_start();
include_once('functions.php');
include("basicHead.php");
?>

  <title>HomePik - Terms of Service</title>
  <?php include_css("/views/css/tos.css"); ?>
</head>
<body>
  <div id="tos"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var TOS = React.createClass({
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar />
            <AddressSearch />
            <div id="termsOfService">
              <div className="Text-1 clearfix colelem" id="u22654-4">
                <p>Terms of Use</p>
              </div>
              <div className="clearfix colelem" id="tosText">
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(i)</span><span> The Registrant acknowledges entering into a lawful consumer-broker relationship with the Participant.</span></p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(ii)</span><span> All Listing Information obtained from the Participant VOW is intended only for the Registrant's personal, non-commercial use.</span></p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(iii)</span><span> The Registrant has a bona fide interest in the purchase, sale, or lease of an Exclusive Property of the type being offered through the Participant VOW.</span></p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(iv)</span><span> The Registrant shall not copy, redistribute, or retransmit any of the Listing Information or data provided by the Participant VOW, except in connection with the Registrant's consideration of the purchase, sale or lease of an individual Exclusive Property;</span></p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(v)</span><span> The Registrant acknowledges each other RLS Broker's ownership of, and the validity of their respective copyright in, the Exclusive Listings that are transmitted over the RLS.</span></p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1"><span className="boldText">(vi)</span><span> This agreement authorizes REBNY and RLS Brokers (and each of their duly authorized representatives) to access the Participant VOW for the purposes of verifying compliance with the provisions of this Agreement relating to monitoring display of Listing Information by the Participant VOW. Any formal complaints regarding the Participant VOW shall be filed with REBNY and resolved in accordance with the RLS VOW Policy.</span></p>
              </div>
            </div>
          </div>
        </div>
      );
	  }
	});

	ReactDOM.render(
    <TOS/>,
    document.getElementById("tos")
  );

  ReactDOM.render(
    <Footer/>,
    document.getElementById("footer")
  );

</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
