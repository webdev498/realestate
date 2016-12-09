<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
$_SESSION['viewingBuyer'] = 'false';
$_SESSION['loadSaved'] = false;
if(!isset($_SESSION['email'])){
  $_SESSION['email'] = 'guest@email.com';
  $_SESSION['role'] = 'guest';
}

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

  <title>HomePik - Contact Us</title>
  <?php include_css("/views/css/contact-us.css");
  include_once("analyticstracking.php") ?>
</head>
<body>
  <div id="contact"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
	var Contact = React.createClass({
		getInitialState: function() {
      return{
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>"
      };
    },
		render: function() {
			return (
				<div className="clearfix" id="page">
					<Header />
					<NavBar mainPage={this.state.mainPage} />
					<AddressSearch mainPage={this.state.mainPage} />
					<div className="container-fluid contactContent">
						<div className="row">
							<div className="col-md-5ths col-xs-5ths">
								<h2 className="panel-heading" id="u6262-4">Our company</h2>
								<div className="panel">
									<div className="panel-body" id="first-panel">
										<h4 className="FAQ-1-grey-bd">HomePik team</h4><br/>
										<p className="Text-1">Darcie L. Binder</p>
										<p className="Text-1" id="u6260-7">President</p>
										<p className="Text-1">dbinder@homepik.com</p><br/>
										<p className="Text-1">Jessica Franke</p>
										<p className="Text-1" id="u6260-7">Project Manager</p>
										<p className="Text-1">jfranke@homepik.com</p><br/>
										<p className="Text-1">Nina Scerbo</p>
										<p className="Text-1" id="u6260-7">Design Director</p><br/>
										<p className="Text-1">Eve C. Binder</p>
										<p className="Text-1" id="u6260-7">Vice President and Consultant</p><br/>
										<p className="Text-1">Neil J. Binder</p>
										<p className="Text-1" id="u6260-7">Vice President and Consultant</p>
									</div>
								</div>
							</div>
							<div className="col-md-5ths col-xs-5ths">
								<h2 className="panel-heading empty-heading" id="u6262-4">&nbsp;&nbsp;</h2>
								<div className="panel">
									<div className="panel-body panel-border" id="second-panel">
										<h4 className="FAQ-1-grey-bd">Technology team</h4><br/>
										<h4 className="FAQ-1-grey-bd" id="u6261-5">Developers</h4>
										<p className="Text-1">
											David Blyth<br/>
											Jessica Franke<br/>
											Wendi English<br/>
											Lindsay Burton<br/>
											Andika Bijaya<br/>
										</p>
										<br/>
										<h4 className="FAQ-1-grey-bd" id="u6261-20">Technology consultant</h4>
										<p className="Text-1">Daren Broxmeyer</p>
									</div>
								</div>
							</div>
							<div className="col-md-5ths col-xs-5ths">
								<h2 className="panel-heading" id="u6262-4">Contact us</h2>
								<div className="panel">
									<div className="panel-body panel-border" id="third-panel">
										<h4 className="FAQ-1-grey-bd">General feedback</h4><br/>
										<p className="Text-1">We value our customers&rsquo; feedback and are happy to answer any questions you may have.</p><br/>
										<p className="Text-1">The best way to reach us is by email, at: support@homepik.com</p><br/>
                    <p className="Text-1">Or, you may call us at: 212-447-1122</p><br/>
										<p className="Text-1">Thank you for your comments.</p>
									</div>
								</div>
							</div>
							<div className="col-md-5ths col-xs-5ths">
								<h2 className="panel-heading" id="u6262-4">Press</h2>
								<div className="panel">
									<div className="panel-body panel-border" id="fourth-panel">
										<h4 className="FAQ-1-grey-bd">Press inquiries</h4><br/>
										<p className="Text-1">Please direct all inquiries to: press@homepik.com</p><br/>
										<p className="Text-1">Or, you may call us at: 646-783-6712</p>
									</div>
								</div>
							</div>
							<div className="col-md-5ths col-xs-5ths">
								<h2 className="panel-heading" id="u6262-4">Investors</h2>
								<div className="panel">
									<div className="panel-body panel-border" id="fifth-panel">
										<h4 className="FAQ-1-grey-bd">Investor inquiries</h4><br/>
										<p className="Text-1">Please direct inquiries to: investors@homepik.com</p><br/>
										<p className="Text-1">Or, you may call us at:</p>
										<p className="Text-1">646-783-6712</p>
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
		<Contact/>,
		document.getElementById("contact")
	);

	ReactDOM.render(
		<Footer mainPage={"<?php echo $mainPage ?>"} />,
		document.getElementById("footer")
	);
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
