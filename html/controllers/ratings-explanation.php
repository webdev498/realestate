<?php
  session_start();
  include_once('functions.php');
  include_once('basicHead.php');
?>

  <title>HomePik - Ratings Explanation</title>
  <?php include_css("/views/css/ratings-explanation.css");
  include_once("analyticstracking.php") ?>
</head>
<body>
  <div id="ratings"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
  var Ratings = React.createClass({
    render: function() {
      return (
      <div class="clearfix" id="page">
        <Header />
        <div className="content container-fluid">
        <div className="row">
          <div className="col-md-12 col-sx-12 panel-heading">
          <div className="Subhead-2 clearfix" id="u8938-4">
            <h2>Why are there two kinds of bubbles?</h2>
          </div>
          </div>
        </div>
        </div>
        <div></div>
        <div className="container-fluid information-container">
        <div className="row">
          <div className="col-md-3 col-sm-6 col-xs-12">
          <div className="panel">
            <div className="panel-body" id="first-panel">
            <h4 className="text-popups" id="u8942-3"><span id="u8942">1.</span><span id="u8942-2"></span></h4>
            <h4 className="text-popups" id="u8942-5">HomePik finds all the properties that meet or exceed your minimum criteria.</h4>
            <p>&nbsp;</p>
            <h4 className="text-popups">HomePik then displays and compares all the properties using special icons:</h4>
            <h4 className="text-popups">&nbsp;</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="meets" src="http://homepik.com/images/large_bronze.png" /> &nbsp; &nbsp;= meets your criteria</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="exceeds" src="http://homepik.com/images/large_silver.png" /> &nbsp; &nbsp;= exceeds your criteria</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="greatly exceeds" src="http://homepik.com/images/large_gold.png" /> &nbsp; &nbsp;= greatly exceeds your criteria</h4>
            </div>
          </div>
          </div>
          <div className="col-md-3 col-sm-6 col-xs-12">
          <div className="panel">
            <div className="panel-body panel-border ratings-panel" id="second-panel">
            <h4 className="text-popups" id="u8950-3"><span id="u8950">2.</span><span id="u8950-2"></span></h4>
            <h4 className="text-popups" id="u8950-5">From that large pool of properties, you select preferred ones for further consideration.</h4>
            <h4 className="text-popups">&nbsp;</h4>
            <h4 className="text-popups">You place your favorites in a folder.</h4><br/>
            <p><img alt="How it works" src="http://homepik.com/images/how_it_works_icons_vert_purple-crop-u8944.png" /></p>
            </div>
          </div>
          </div>
          <div className="col-md-3 col-sm-6 col-xs-12">
          <div className="panel">
            <div className="panel-body panel-border ratings-panel" id="third-panel">
            <h4 className="text-popups" id="u8949-3"><span id="u8949">3.</span><span id="u8949-2"></span></h4>
            <h4 className="text-popups" id="u8949-5">In the folder, your selections get re-graded because they are now compared within a smaller set of apartments.</h4>
            <p>&nbsp;</p>
            <h4 className="text-popups">The bubbles mean the same thing, but the four ratings for any apartment may get higher or lower depending on how the other apartments in your folder stack up against it.</h4>
            <h4 className="text-popups">&nbsp;</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="meets" src="http://homepik.com/images/large_bronze2.png" /> &nbsp; &nbsp;= meets your criteria</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="exceeds" src="http://homepik.com/images/large_silver2.png" style={{position: "relative", left: 1 + "px"}}/> &nbsp; &nbsp;= exceeds your criteria</h4>
            <h4 className="text-popups bubbleMeaning"><img alt="greatly exceeds" src="http://homepik.com/images/large_gold2.png" /> &nbsp; &nbsp;= greatly exceeds your criteria</h4>
            </div>
          </div>
          </div>
          <div className="col-md-3 col-sm-6 col-xs-12">
          <div className="panel">
            <div className="panel-body panel-border ratings-panel" id="fourth-panel">
            <h4 className="text-popups" id="u8948-2"><span id="u8948">4.</span></h4>
            <h4 className="text-popups" id="u8948-4">HomePik shows you both sets of bubbles.</h4>
            <p className="Text-1" id="u8948-5">&nbsp;</p>
            <h4 className="text-popups">The gold bubbles are now the more meaningful ones, because they reflect the comparison made within the smaller group of preferred apartments.</h4>
            <h4 className="text-popups">&nbsp;</h4>
            <h4 className="text-popups">Example:</h4>
            <h4 className="text-popups">Original rating</h4>
            <p><img alt="Find and Compare" src="http://homepik.com/images/original_ratings.png" /></p>
            <h4 className="text-popups">New rating</h4>
            <p><img alt="Find and Compare" src="http://homepik.com/images/new_ratings.png" /></p>
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
    <Ratings/>,
    document.getElementById("ratings")
  );

  ReactDOM.render(
    <Footer/>,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
