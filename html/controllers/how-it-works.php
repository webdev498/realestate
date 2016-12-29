<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include_once('basicHead.php');

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");
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
						<div className="container-fluid">
							<div className="row-fluid">
								<div className="clearfix colelem" id="pu336-4">
									<div className="browser_width grpelem" id="u336-4-bw">
										<div className="Subhead-2 clearfix" id="u336-4">
											<h2>
												<span>How it works</span>
												<span id="backBtn" className="Text-1 clearfix colelem"><a href="/controllers/index.php"><span className="fa fa-chevron-left"></span> Home</a></span>
											</h2>
										</div>
									</div>
								</div>
								<div className="col-md-5ths" id="col-one">
                  <div className="panel">
                    <div className="panel-body first-panel how-it-works-panel">
                      <div className="clearfix grpelem" id="u332-24">
												<h4 className="text-popups" id="u332-3"><span id="u332">Select</span><span id="u332-2"> your basic parameters</span></h4>
												<p id="u332-6">&nbsp;</p>
												<p className="Text-1" id="select_paragraph">Using simple sliders, make selections for four primary categories:<br/><br/></p>
												<h6 className="Text-2-ex-lead" id="u332-16"><img className="selectImgs" src="/images/price_range.png"/> Price range</h6>
												<h6 className="Text-2-ex-lead" id="u332-18"><img className="selectImgs" src="/images/number_of_bedroom.png"/> Number of bedrooms</h6>
												<h6 className="Text-2-ex-lead" id="u332-20"><img className="selectImgs" src="/images/neighborhood.png"/> Neighborhoods</h6>
												<h6 className="Text-2-ex-lead" id="u332-22"><img className="selectImgs" src="/images/property_types.png"/> Property types</h6>
											</div>
                    </div>
                  </div>
                </div>
								<div className="col-md-5ths" id="col-two">
                  <div className="panel">
                    <div className="panel-body how-it-works-panel">
                      <div className="clearfix grpelem" id="u331-24">
												<h4 className="text-popups" id="u331-3"><span id="u331">Define</span><span id="u331-2"> your buying formula</span></h4>
												<p>&nbsp;</p>
												<p className="Text-1">Using simple sliders, make selections for five primary motivators:</p>
												<p className="Text-1">&nbsp;</p>
												<h6 className="Text-2-ex-lead" id="u331-11"><img className="defineImgs" src="/images/location.png"/> Location grade</h6>
												<h6 className="Text-2-ex-lead" id="u331-13"><img className="defineImgs" src="/images/building.png"/> Building grade</h6>
												<h6 className="Text-2-ex-lead" id="u331-15"><img className="defineImgs" src="/images/ser.png"/> View grade</h6>
												<h6 className="Text-2-ex-lead" id="u331-17"><img className="defineImgs" src="/images/bed.png"/> Master bedroom size</h6>
												<h6 className="Text-2-ex-lead" id="u331-19"><img className="defineImgs" src="/images/sofa.png"/> Living room size</h6>
												<h6 className="Text-2-ex-lead" id="u331-20">&nbsp;</h6>
												<p className="Text-1">… and desired amenities such as fireplace, laundry and outdoor space.</p>
												<div className="amenityIconImgs">
													<img className="amenityIcon" id="fireplace" src="/images/icon_fireplace_2_30k_33px.png"/>
													<img className="amenityIcon" id="washer" src="/images/washer_3_37px.png"/>
													<img className="amenityIcon" id="roofdeck" src="/images/icon_roofdeck_4_30k_33px.png"/>
												</div>
											</div>
                    </div>
                  </div>
                </div>
								<div className="col-md-5ths" id="col-three">
                  <div className="panel">
                    <div className="panel-body how-it-works-panel">
                      <div className="clearfix grpelem" id="u333-22">
												<h4 className="text-popups" id="u333-3"><span id="u333">Search</span><span id="u333-2"> for apartments that match it</span></h4>
												<h4 className="text-popups">&nbsp;</h4>
												<img id="pinkArrow" src="/images/search_homepik.png"/>
												<p className="Text-1" id="searchText"><span id="u333-8">HomePik</span> will scan the entire database of available apartments in New York City for those that match your buying formula</p>
											</div> 
                    </div>
                  </div>
                </div>
								<div className="col-md-5ths" id="col-four">
                  <div className="panel">
                    <div className="panel-body how-it-works-panel">
                      <div className="clearfix grpelem" id="u334-20">
												<h4 className="text-popups" id="u334-3"><span id="u334">View</span><span id="u334-2"> the results of the search</span></h4>
												<p>&nbsp;</p>
												<p className="Text-1"><span id="u334-7">HomePik</span> has located every apartment that meets your minimum criteria and compared them using its patented rating system:</p>
												<p className="Text-1">&nbsp;</p>
												<h6 className="Text-2-ex-lead" id="u334-12"><img className="viewImgs" src="/images/meets.png"/> = meets your criteria</h6>
												<h6 className="Text-2-ex-lead" id="u334-14"><img className="viewImgs" src="/images/exceeds.png"/> = exceeds your criteria</h6>
												<h6 className="Text-2-ex-lead" id="greater_criteria"><img className="viewImgs" src="/images/greatly.png"/> = greatly exceeds your <span id="indent">criteria</span></h6>
												<img id="divider" src="/images/divider_d.png"/>
												<div className="Text-1 clearfix grpelem" id="u339-9">
													<p id="u339-2">HomePik is the one </p>
													<p id="u339-4">and only search engine able to compare properties.</p>
													<p>&nbsp;</p>
													<p>Your searches. Your way.</p>
												</div>
											</div>
                    </div>
                  </div>
                </div>
								<div className="col-md-5ths" id="col-five">
                  <div className="panel last-panel">
                    <div className="panel-body how-it-works-panel">
                      <div className="clearfix grpelem" id="u335-18">
												<h4 className="text-popups" id="u335-3"><span id="u335">Manage</span><span id="u335-2"> your searches and selections</span></h4>
												<p>&nbsp;</p>
												<p className="Text-1">Save searches and properties, email them, and connect with your agent</p>
												<p className="Text-1">&nbsp;</p>
												<h6 className="Text-2-ex-lead" id="u335-11"><img className="manageImgs" src="/images/save_to_folders.png"/> Save to folders</h6>
												<h6 className="Text-2-ex-lead" id="u335-13"><img className="manageImgs" src="/images/emails.png"/> Email</h6>
												<h6 className="Text-2-ex-lead" id="u335-15"><img className="manageImgs" src="/images/chose_your_agent.png"/> Choose an agent</h6>
												<p>&nbsp;</p>
											</div> 
                    </div>
                  </div>
                </div>
							</div>
							<div className="row" id="video-powerpoint-container">
								<div>
									<p className="about-phrase">HomePik is the future of real estate. If you're not using it, you're missing out.</p>
									<p> </p>
								</div>
								<div className="col-md-3 video-image">
									<p className="FAQ-1-grey-bd">Video Overview of HomePik</p>
									<div>
										<iframe id="video" className="video-container" src="https://www.youtube.com/embed/Yv_O-j-cRTo" frameBorder="0" allowFullScreen></iframe>
									</div>
								</div>
								<div className="col-md-4 col-md-offset-2 pdf-image">
									<p className="FAQ-1-grey-bd">PowerPoint Presentation</p>
									<div>
										<a href="/documents/HomePik_Investor_Presentation.pdf"><img className="image-container" alt="press release" src="/images/investimage.JPG" title="PowerPoint Presentation" /></a>
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
