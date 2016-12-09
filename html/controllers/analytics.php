<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
$_SESSION['viewingBuyer'] = 'false';

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 'N'){ print "<script> window.location = '/users/logout.php' </script>"; }

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

  <title>HomePik - Analytics</title>
  <?php include_css("/views/css/analytics.css"); ?>
</head>
<body>
  <div id="analytics"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script src="/js/jquery.watch.js?3866665977" type="text/javascript"></script>
<script type="text/babel">
/* Page Content */
var Analytics = React.createClass({	
	getInitialState: function() {
		return{
			mainPage: "<?php echo $mainPage ?>"
		};
	},
  render: function() {
		return (
			<div className="clearfix" id="page">
			<div className="position_content" id="page_position_content">
				<Header />
				<NavBar mainPage={this.state.mainPage} />
				<AddressSearch mainPage={this.state.mainPage} />
				<div id="info-wrapper">
				<div className="container-fluid">
					<div className="row">
					<div className="col-md-6 col-md-offset1">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=74061680&amp;format=image"></iframe>
					</div>
					<div className="col-md-5 col-md-offset2">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=185887001&amp;format=image"></iframe>
					</div>
					</div>
					<div className="row">
					<div className="col-md-6 col-md-offset1">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=831707272&amp;format=image"></iframe>
					</div>
					<div className="col-md-5 col-md-offset2">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=186446136&amp;format=image"></iframe>
					</div>
					</div>
					<div className="row">
					<div className="col-md-6 col-md-offset1">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=466542660&amp;format=image"></iframe>
					</div>
					<div className="col-md-5 col-md-offset2">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/1DcPohzXMQb9ZHGrlCHlQgPhxiIONZ-LyBtwGkw-8fwc/pubchart?oid=189324738&amp;format=interactive"></iframe>
					</div>
					</div>
					<div className="row">
					<div className="col-md-6 col-md-offset1">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=799767021&amp;format=image"></iframe>
					</div>
					<div className="col-md-5 col-md-offset2">
						<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=1600286555&amp;format=image"></iframe>
					</div>
					</div>
					<div className="row">
						<div className="col-md-6 col-md-offset1">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vSbQEUOvSccypv509bp3eIH6IBy8qJ5yXP-FbGBiytKPWFAD5-NXnIjeGqFvy1n0e9S0qTrFWV9vYbi/pubchart?oid=1734270620&amp;format=image"></iframe>
						</div>
						<div className="col-md-5 col-md-offset2">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vSbQEUOvSccypv509bp3eIH6IBy8qJ5yXP-FbGBiytKPWFAD5-NXnIjeGqFvy1n0e9S0qTrFWV9vYbi/pubchart?oid=1130476696&amp;format=image"></iframe>
						</div>
					</div>
					<div className="row">
						<div className="col-md-6 col-md-offset1">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=1640876707&amp;format=image"></iframe>
						</div>
						<div className="col-md-5 col-md-offset2">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=524076379&amp;format=image"></iframe>
						</div>
					</div>
					<div className="row">
						<div className="col-md-6 col-md-offset1">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=890762391&amp;format=image"></iframe>
						</div>
						<div className="col-md-5 col-md-offset2">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/e/2PACX-1vR_nuLGFqnBPWz4UlB9xLhd6ihysFK8yXM_6Rl2HD6-B7_qaJvJhUhqvZ-Lhwim0wVlWDEnKSywN5iz/pubchart?oid=1410989639&amp;format=image"></iframe>
						</div>
					</div>
					<div className="row">
						<div className="col-md-6 col-md-offset1">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=1775011768&amp;format=image"></iframe>
						</div>
						<div className="col-md-5 col-md-offset2">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=1809883464&amp;format=image"></iframe>
						</div>
					</div>
					<div className="row">
						<div className="col-md-6 col-md-offset1">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=611191644&amp;format=image"></iframe>
						</div>
						<div className="col-md-5 col-md-offset2">
							<iframe className="iframe" seamless frameBorder="1" scrolling="no" src="https://docs.google.com/spreadsheets/d/12J9EqAOCR63p3YoWW6LPfAzgEh5DJvaI4rUzlVtEy6I/pubchart?oid=1214312471&amp;format=image"></iframe>
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
	<Analytics/>,
	document.getElementById("analytics")
);	
	
ReactDOM.render(
  <Footer mainPage={"<?php echo $mainPage ?>"} />,
  document.getElementById("footer")
);
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
