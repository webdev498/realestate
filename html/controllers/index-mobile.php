<?php
session_start();
include_once('functions.php');
include_once('indexHead-mobile.php');
if ((authentication() == 'agent') OR ( authentication() == 'user')) { print "<script> window.location = 'menu.php' </script>";}
if (authentication() == 'guest') {
	print"
		<script>
			$.cookie('minPrice', 1);
			$.cookie('maxPrice', 20);
			$.cookie('location', 1);
			$.cookie('building', 1);
			$.cookie('views', 1);
			$.cookie('bedroom', 1);
			$.cookie('living', 1);
			$.cookie('minBedroom', 0);
			$.cookie('North', 'false');
			$.cookie('Westside', 'false');
			$.cookie('Eastside', 'false');
			$.cookie('Chelsea', 'false');
			$.cookie('SMG', 'false');
			$.cookie('Village', 'false');
			$.cookie('Lower', 'false');
			$.cookie('Coop', 'false');
			$.cookie('Condo', 'false');
			$.cookie('House', 'false');
			$.cookie('Condop', 'false');
			$.cookie('garage', 'false');
			$.cookie('pool', 'false');
			$.cookie('laundry', 'false');
			$.cookie('doorman', 'false');
			$.cookie('elevator', 'false');
			$.cookie('pets', 'false');
			$.cookie('fireplace', 'false');
			$.cookie('healthclub', 'false');
			$.cookie('prewar', 'false');
			$.cookie('outdoor', 'false');
			$.cookie('wheelchair', 'false');
		</script>";
}

if(isset($_GET['saved']) && $_GET['saved'] == true) { $_SESSION['loadSaved'] = true; }
?>

	<title>HomePik.com - The smartest way to your best home</title>
	<script type="text/javascript">
		if (typeof Muse == "undefined")
			window.Muse = {};
			window.Muse.assets = {"required": ["jquery-1.8.3.min.js", "museutils.js", "jquery.watch.js", "jquery.musepolyfill.bgsize.js", "webpro.js", "musewpslideshow.js", "jquery.museoverlay.js", "touchswipe.js", "index.css"], "outOfDate": []};
	</script>
	<script type="text/javascript">
		document.documentElement.className = document.documentElement.className.replace(/\bnojs\b/g, 'js');
		var __adobewebfontsappname__ = "muse";
	</script>
</head>
<body>
	<div id="index"></div>
	<div id="footer"></div>
	<div id="overlay"></div>
	<div id="ajax-box"></div>
<script type="text/javascript">
	if (document.location.protocol != 'https:')
		document.write('\x3Cscript src="http://musecdn2.businesscatalyst.com/scripts/4.0/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
<script type="text/javascript">
	window.jQuery || document.write('\x3Cscript src="scripts/jquery-1.8.3.min.js" type="text/javascript">\x3C/script>');
</script>
<script src="/js/museutils.js?531812214" type="text/javascript"></script>
<script src="/js/whatinput.js?84559013" type="text/javascript"></script>
<script src="/js/webpro.js?4156477058" type="text/javascript"></script>
<script src="/js/musewpslideshow.js?4058618124" type="text/javascript"></script>
<script src="/js/jquery.museoverlay.js?3810062583" type="text/javascript"></script>
<script src="/js/touchswipe.js?4174436727" type="text/javascript"></script>
<script src="/js/jquery.watch.js?3866665977" type="text/javascript"></script>
<script src="/js/jquery.musepolyfill.bgsize.js?120190942" type="text/javascript"></script>
<script type="text/babel">
	var Slider = React.createClass({
		componentDidMount:function(){
			try {
				(function () {
					var a = {}, b = function (a) {
						if (a.match(/^rgb/))
							return a = a.replace(/\s+/g, "").match(/([\d\,]+)/gi)[0].split(","), (parseInt(a[0]) << 16) + (parseInt(a[1]) << 8) + parseInt(a[2]);
						if (a.match(/^\#/))
							return parseInt(a.substr(1), 16);
							return 0
					};
					(function () {
						$('link[type="text/css"]').each(function () {
							var b = ($(this).attr("href") || "").match(/\/?css\/([\w\-]+\.css)\?(\d+)/);
							b && b[1] && b[2] && (a[b[1]] = b[2])
						})
					})();
					(function () {
						$("body").append('<div class="version" style="display:none; width:1px; height:1px;"></div>');
						for (var c = $(".version"), d = 0; d < Muse.assets.required.length; ) {
							var f = Muse.assets.required[d], g = f.match(/([\w\-\.]+)\.(\w+)$/), k = g && g[1] ? g[1] : null, g = g && g[2] ? g[2] : null;
							switch (g.toLowerCase()) {
								case "css":
									k = k.replace(/\W/gi, "_").replace(/^([^a-z])/gi, "_$1");
									c.addClass(k);
									var g = b(c.css("color")), h = b(c.css("background-color"));
									g != 0 || h != 0 ? (Muse.assets.required.splice(d, 1), "undefined" != typeof a[f] && (g != a[f] >>> 24 || h != (a[f] & 16777215)) && Muse.assets.outOfDate.push(f)) : d++;
									c.removeClass(k);
									break;
								case "js":
									k.match(/^jquery-[\d\.]+/gi) &&
									typeof $ != "undefined" ? Muse.assets.required.splice(d, 1) : d++;
									break;
									default:
									throw Error("Unsupported file type: " + g);
							}
						}
						c.remove();
						if (Muse.assets.outOfDate.length || Muse.assets.required.length)
							c = "Some files on the server may be missing or incorrect. Clear browser cache and try again. If the problem persists please contact website author.", (d = location && location.search && location.search.match && location.search.match(/muse_debug/gi)) && Muse.assets.outOfDate.length && (c += "\nOut of date: " + Muse.assets.outOfDate.join(",")), d && Muse.assets.required.length && (c += "\nMissing: " + Muse.assets.required.join(",")), alert(c)
					})()
				})();

				/* body */
				Muse.Utils.transformMarkupToFixBrowserProblemsPreInit();/* body */
				Muse.Utils.prepHyperlinks(true);/* body */
				//  Muse.Utils.resizeHeight('.browser_width');/* resize height */
				Muse.Utils.requestAnimationFrame(function () {
					$('body').addClass('initialized');
				});/* mark body as initialized */
				Muse.Utils.fullPage('#page');/* 100% height page */
				Muse.Utils.initWidget('#slideshowu644', ['#bp_infinity'], function (elem) {
					var widget = new WebPro.Widget.ContentSlideShow(elem, {autoPlay: true, displayInterval: 2000, slideLinkStopsSlideShow: false, transitionStyle: 'fading', lightboxEnabled_runtime: false, shuffle: false, transitionDuration: 300, enableSwipe: true, elastic: 'fullWidth', resumeAutoplay: true, resumeAutoplayInterval: 3000, playOnce: false, autoActivate_runtime: false});
					$(elem).data('widget', widget);
					return widget;
				});/* #slideshowu644 */
				Muse.Utils.showWidgetsWhenReady();/* body */
				Muse.Utils.transformMarkupToFixBrowserProblems();/* body */
			} catch (e) {
			if (e && 'function' == typeof e.notify)
				e.notify();
			else
				console.log('Error calling selector function:' + e); Muse.Assert.fail('Error calling selector function:' + e);
			}
		},
		render: function () {
			return (
				<div className="popup_anchor" id="u662popup">
					<div className="SlideShowContentPanel clearfix" id="u662">
						<div className="SSSlide clip_frame grpelem" id="u663">
							<img className="ImageInclude" id="u663_img" data-src="/images/adobestock_55781660.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="933"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u693">
							<img className="ImageInclude" id="u693_img" data-src="/images/fotolia_99079528.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="934"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u705">
							<img className="ImageInclude" id="u705_img" data-src="/images/fotolia_68256058_subscription_monthly_m.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="1054"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u717">
							<img className="ImageInclude" id="u717_img" data-src="/images/fotolia_76838711.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="934"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u729">
							<img className="ImageInclude" id="u729_img" data-src="/images/adobestock_73226030.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="843"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u741">
							<img className="ImageInclude" id="u741_img" data-src="/images/adobestock_82392865.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="934"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u753">
							<img className="ImageInclude" id="u753_img" data-src="/images/fotolia_74677854.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="788"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u765">
							<img className="ImageInclude" id="u765_img" data-src="/images/adobestock_51254934.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="1082"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u777">
							<img className="ImageInclude" id="u777_img" data-src="/images/adobestock_5790312.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="933"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u789">
							<img className="ImageInclude" id="u789_img" data-src="/images/adobestock_51254965.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="934"/>
						</div>
						<div className="SSSlide invi clip_frame grpelem" id="u801">
							<img className="ImageInclude" id="u801_img" data-src="/images/fotolia_82749614.jpg" src="images/blank.gif" alt="" data-width="1400" data-height="978"/>
						</div>
					</div>
				</div>
			);
		}
	});
	
	var Header2 = React.createClass({
		render: function () {
			return (
				<div className="container-fluid header-container">
					<div className="row">
						<div className="clip_frame colelem" id="u25521">
							<div className="col-xs-12" id="first-section">
								<p>Find and compare<br/>your best home choices</p>
							</div>
							<div className="col-xs-12" id="second-section">
								<img className="block" src="/images/homepik_logo.png" alt=""/>
							</div>
							<div className="col-xs-12" id="third-section">
								<img className="block" src="/images/good-better-best.png" alt=""/>
							</div>
						</div>
					</div>
				</div>
			);
		}
		});
	
	var TopNavBar = React.createClass({
		render: function(){
			return(
				<div>
				 <nav className="navbar navbar-default indexNavBar">
						<div className="container-fluid">
							<div className="navbar-collapse menu-navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul className="nav navbar-nav">
									<li className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">How It Works<br/><i className="fa fa-chevron-down"></i></a>
										<ul className="dropdown-menu">
											<li><a><span id="u108">1.</span> Create your buying formula</a></li>
											<li><a><span id="u108-3">2.</span> Search for apartments that match it</a></li>
											<li><a><span id="u108-5">3.</span> Compare them to find the best fit</a></li>
											<li><a href="/controllers/search.php#newSearch">Try it now <i className="fa fa-chevron-right"></i></a></li>
										</ul>
									</li>
								</ul>
							</div>
						</div>
					</nav>
				</div>
			);
		}
	});

	var ComingSoon2 = React.createClass({
    componentDidMount:function(){
      $("#overlay").bind('click',function(){
				$(".comingSoonPopup").hide();				
				$("#overlay").hide();
			})
    },
		closePopup: function(){
			$(".comingSoonPopup").hide();
			$("#overlay").hide();
		},
		render: function(){
			return(
				<div className="shadow clearfix grpelem comingSoonPopup" id="u25718" style={{display: "none"}}>
					<div id="comingSoonText"><p>Coming soon...</p></div>
					<div className="text-popups clearfix grpelem" id="comingSoonClose">
						<h4 style={{cursor: "pointer"}} onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
					</div>
				</div>
			);
		}
	});

	var Footer2 = React.createClass({
		comingSoon: function(){
			$(".comingSoonPopup").show();
			$("#overlay").show();
		},
		render: function () {
			return (
				<div>
					<div className="verticalspacer" style={{height: 0 + "!important"}}></div>
					<nav className="navbar navbar-default footerNavBar">
					   <div className="container-fluid">
						 <div className="navbar-header">
						   <p className="navbar-text visible-xs-inline-block">Navigation Footer</p>
						   <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".footer-navbar-collapse" aria-expanded="false" title="Toggle Footer">
							 <span className="sr-only">Menu</span>
							 <span className="icon-bar"></span>
							 <span className="icon-bar"></span>
							 <span className="icon-bar"></span>
						   </button>
						 </div>

						 <div className="collapse navbar-collapse footer-navbar-collapse" id="bs-example-navbar-collapse-1">
						   <ul className="nav navbar-nav">
								<li id="copyright">Copyright 2016 Nice Idea Media, Inc and HomePik.com</li>
								<li><a href="about.php?MP=index"><span id="u15181-2">|</span>About HomePik</a></li>
								<li><a href="contactus.php?MP=index"><span id="u15181-6">|</span>Contact Us</a></li>
								<li><a style={{cursor:"pointer"}} onClick={this.comingSoon}><span id="u15181-8">|</span>Site Map</a></li>
								<li><a href="faq.php?MP=index"><span id="u15181-10">|</span>FAQs</a></li>
								<li><a style={{cursor:"pointer"}} onClick={this.comingSoon}><span id="u15181-12">|</span>Blog</a></li>
						   </ul>

							 <ul className="nav navbar-nav navbar-right">
                <li className="socialMedia" >
                  <img id="facebook" src="/images/socialMedia_facebook.png" onClick={this.comingSoon} title="Facebook"/>
                  <img id="twitter" src="/images/socialMedia_twitter.png" onClick={this.comingSoon} title="Twitter"/>
                  <img id="instagram" src="/images/socialMedia_instagram.png" onClick={this.comingSoon} title="Instagram"/>
                  <img id="youtube" src="/images/socialMedia_youtube.png" onClick={this.comingSoon} title="YouTube"/>
                </li>
              </ul>
						 </div>
					   </div>
					</nav>
				</div>
			);
		}
	});

	var Content = React.createClass({
		render: function () {
			return (
				<div className="clearfix" id="page">
					<div className="position_content" id="page_position_content">
						<Header2 />
						<TopNavBar />
						<div className="SlideShowWidget clearfix HeroFillFrame colelem" id="slideshowu644">
							<Slider />
						</div>
						<div className="slider-seller-option"><a href="sell-home.php"><img src="/images/selling_black_button.png" height="125"/></a></div>
						<div className="clearfix colelem i_c_f" id="pu6300">
							<a href="/controllers/search.php#newSearch">
								<div className="i_b_c" id="browse" >Browse Listings</div>
							</a>
							<a href="/controllers/signin.php">
								<div className="i_b_c">Buyer Log In</div>
							</a>
							<a href="/controllers/guest-register.php">
								<div className="i_b_c">Sign Up</div>
							</a>
							<a href="/controllers/agent-signin.php">
								<div className="i_b_c">Agent Log In</div>
							</a>
							<a href="/controllers/sell-home.php">
								<div className="i_b_c">Sell Your Home</div>
							</a>
						</div>
						<ComingSoon2 />
					</div>
				</div>
			);
		}
	});

	ReactDOM.render(
		<Content/>,
		document.getElementById("index")
	);
	
	ReactDOM.render(
		<Footer2 />,
		document.getElementById("footer")
	);

</script>
</body>
</html>
