<?php
session_start();
include("dbconfig.php");
include('functions.php');
include('basicHead.php');

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

	<title>HomePik - How it works</title>
  <?php include_css("/views/css/how-it-works.css");
	include_once("analyticstracking.php") ?>
</head>
<body>
	<div id="how_it_works"></div>
	<div id="footer"></div>
	<div id="overlay"></div>
	<div id="ajax-box"></div>
	<div id="ajax-box2"></div>
<script type="text/babel">
	var Content = React.createClass({
		render: function () {
			return (
				<div className="clearfix" id="page">
					<div className="position_content" id="page_position_content">
						<Header />
						<div className="container-fluid middle-container">
							<span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/index.php"><span className="fa fa-chevron-left"></span> Home</a></span>
						</div>
						<div className="container-fluid">
							<div className="container how_it_work">
								<h2>How it works</h2>
								<div className="col-xs-12 col-sm-3">
									<h3 className="FAQ-1-grey-bd"><span className="c_green">Select</span> your basic parameters</h3>
									<h4>Using simple sliders, make selections for four primary categories:</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/price_range.png"/></span> Price range</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/number_of_bedroom.png"/></span> Number of bedrooms</h4>
									<h4 className="item-height word-overflow"><span className="cont_img"><img src="http://homepik.com/images/nightborhood.png"/></span> Neighborhoods</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/property_types.png"/></span> Property types</h4>
								</div>
								<div className="col-xs-12 col-sm-3">
									<h3 className="FAQ-1-grey-bd"><span className="c_orange">Define</span> your buying formula</h3>
									<h4>Using simple sliders, make selections for five primary motivators:</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/location.png"/></span> Location grade</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/building.png"/></span> Building grade</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/ser.png"/></span> View grade</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/bed.png"/></span> Master bedroom size</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/sofa.png"/></span> Living room size</h4>
									<p>...and desired amenities such as fireplace, laundry and outdoor space.</p>
									<img className="amenityIcon" src="http://homepik.com/images/icon_fireplace_2_30k_33px.png"/>
									<img className="amenityIcon" src="http://homepik.com/images/washer_3_37px.png"/>
									<img className="amenityIcon" src="http://homepik.com/images/icon_roofdeck_4_30k_33px.png"/>
								</div>
								<div className="col-xs-12 col-sm-3">
									<h3 className="FAQ-1-grey-bd"><span className="c_pink">Search</span> for apartments that match it</h3>
									<span className="cont_img pinkArrow"><img src="http://homepik.com/images/search_homepik.png"/></span>
									<h4 className="pink_arrow"><span className="homepik">HomePik</span>&nbsp; will scan the entire database of available apartments in New York City for those that match your buying formula</h4>
								</div>
								<div className="col-xs-12 col-sm-3">
									<h3 className="FAQ-1-grey-bd"><span className="c_blue">View</span> the results of the search</h3>
									<h4 className="item-height"><span className="homepik">HomePik</span> has located every apartment that meets your minimum criteria and compared them using its patented rating system:</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/meets.png"/></span>  = meets your criteria</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/exceeds.png"/></span>  = exceeds your criteria</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/greatly.png"/></span>  = greatly exceeds your criteria</h4>
										<img src="http://homepik.com/images/divider_d.png"/>
									<p className="text-center"><span className="c_blue_p">HomePik is the one and only search engine able to compare properties.</span> Your searches. Your way.</p>
								</div>
								<div className="col-xs-12 col-sm-3 border_right_none"><h3 className="FAQ-1-grey-bd"><span className="c_purple">Manage</span> your searches and selections</h3>
									<h4 className="item-height">Save searches and properties, email them, and connect with your agent</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/save_to_folders.png"/></span> Save to folders</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/emails.png"/></span> Email</h4>
									<h4 className="item-height"><span className="cont_img"><img src="http://homepik.com/images/chose_your_agent.png"/></span> Choose an agent</h4>
								</div>
							</div>
							<div className="container" id="videoDocumentSection">
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
					</div>
				</div>
			);
		}
	});

	ReactDOM.render(
		<Content/>,
		document.getElementById("how_it_works")
	);

	ReactDOM.render(
    <Footer mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
</body>
</html>
