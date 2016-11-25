<?php

session_start();
include("dbconfig.php");
include ("functions.php");
include("basicHead.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");
if (!$_SESSION['user']) {
  print "<script> window.location = '/users/logout.php' </script>";
}

if(!isset($_SESSION['admin']) || $_SESSION['admin'] == 'N'){
  print "<script> window.location = '/users/logout.php' </script>";
}

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }

$analytics = $_SESSION['analytics'];
$analysis = $_SESSION['activity_analysis'];
$email = $_SESSION['email'];
$sql = "SELECT first_name, last_name, agent_id FROM `registered_agents` where email= '".$email."'";
$res = mysql_query( $sql ) or die("Couldn't execute query. Error 1.".mysql_error());
while($row = mysql_fetch_array($res,MYSQL_ASSOC)) {
  $firstname = $row['first_name'];
  $lastname = $row['last_name'];
  $id = $row['agent_id'];
}
$name = explode('@', $_SESSION['email']);
$name = $name[0];
?>


  <title>HomePik - Activity Analysis</title>
	<script type="text/javascript">
    if(typeof Muse == "undefined") window.Muse = {}; window.Muse.assets = {"required":["jquery-1.8.3.min.js", "museutils.js", "jquery.watch.js", "jquery.musepolyfill.bgsize.js", "buyer-options.css"], "outOfDate":[]};
	</script>
  <?php include_css("/views/css/analysis.css"); ?>
</head>
<body>
<div className="clearfix" id="page">
  <div className="position_content" id="page_position_content">
	<div id="header"></div>
	<div id="navbar"></div>
	<div id="address-search"></div>
	<div id="page-header" class="container-fluid">
		<form action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?> method="post">
      <div class="row"></div>
			<div class="row report-row">
				<div class="col-md-3 col-sm-3">
          <p class="report-header">Listings By Month</p>
					<fieldset class="form-group">
						<select class="form-control" id="listMonth" name="listMonth">
						  <option value="blank"<?php echo($_POST['listMonth']=='blank'?' selected="selected"':'');?>>-- Select Month --</option>
						  <option value="01"<?php echo($_POST['listMonth']=='01'?' selected="selected"':'');?>>January</option>
						  <option value="02"<?php echo($_POST['listMonth']=='02'?' selected="selected"':'');?>>February</option>
						  <option value="03"<?php echo($_POST['listMonth']=='03'?' selected="selected"':'');?>>March</option>
						  <option value="04"<?php echo($_POST['listMonth']=='04'?' selected="selected"':'');?>>April</option>
						  <option value="05"<?php echo($_POST['listMonth']=='05'?' selected="selected"':'');?>>May</option>
						  <option value="06"<?php echo($_POST['listMonth']=='06'?' selected="selected"':'');?>>June</option>
						  <option value="07"<?php echo($_POST['listMonth']=='07'?' selected="selected"':'');?>>July</option>
						  <option value="08"<?php echo($_POST['listMonth']=='08'?' selected="selected"':'');?>>August</option>
						  <option value="09"<?php echo($_POST['listMonth']=='09'?' selected="selected"':'');?>>September</option>
						  <option value="10"<?php echo($_POST['listMonth']=='10'?' selected="selected"':'');?>>October</option>
						  <option value="11"<?php echo($_POST['listMonth']=='11'?' selected="selected"':'');?>>November</option>
						  <option value="12"<?php echo($_POST['listMonth']=='12'?' selected="selected"':'');?>>December</option>
						</select>
					</fieldset>
				</div>
        <div class="col-md-3 col-sm-3">
          <p class="report-header">Listings Year-to-Date</p>
					<fieldset class="form-group">
						<select class="form-control" id="listYearlyArea" name="listYearlyArea">
						  <option value="blank">-- Select Area --</option>
              <option value="All Markets"<?php echo($_POST['listYearlyArea']=='All Markets'?' selected="selected"':'');?>>All Areas</option>
						  <option value="North"<?php echo($_POST['listYearlyArea']=='North'?' selected="selected"':'');?>>Far Uptown</option>
						  <option value="Westside"<?php echo($_POST['listYearlyArea']=='Westside'?' selected="selected"':'');?>>Upper West Side</option>
						  <option value="Eastside"<?php echo($_POST['listYearlyArea']=='Eastside'?' selected="selected"':'');?>>Upper East Side</option>
						  <option value="Chelsea"<?php echo($_POST['listYearlyArea']=='Chelsea'?' selected="selected"':'');?>>Midtown West</option>
						  <option value="SMG"<?php echo($_POST['listYearlyArea']=='SMG'?' selected="selected"':'');?>>Midtown East</option>
						  <option value="Village"<?php echo($_POST['listYearlyArea']=='Village'?' selected="selected"':'');?>>East/West Village</option>
						  <option value="Lower"<?php echo($_POST['listYearlyArea']=='Lower'?' selected="selected"':'');?>>Downtown</option>
						</select>
					</fieldset>
				</div>
				<div class="col-md-3 col-sm-3">
          <p class="report-header">Agents By Month</p>
					<fieldset class="form-group">
						<select class="form-control" id="agentMonth" name="agentMonth">
						  <option value="blank">-- Select Month --</option>
						  <option value="01"<?php echo($_POST['agentMonth']=='01'?' selected="selected"':'');?>>January</option>
						  <option value="02"<?php echo($_POST['agentMonth']=='02'?' selected="selected"':'');?>>February</option>
						  <option value="03"<?php echo($_POST['agentMonth']=='03'?' selected="selected"':'');?>>March</option>
						  <option value="04"<?php echo($_POST['agentMonth']=='04'?' selected="selected"':'');?>>April</option>
						  <option value="05"<?php echo($_POST['agentMonth']=='05'?' selected="selected"':'');?>>May</option>
						  <option value="06"<?php echo($_POST['agentMonth']=='06'?' selected="selected"':'');?>>June</option>
						  <option value="07"<?php echo($_POST['agentMonth']=='07'?' selected="selected"':'');?>>July</option>
						  <option value="08"<?php echo($_POST['agentMonth']=='08'?' selected="selected"':'');?>>August</option>
						  <option value="09"<?php echo($_POST['agentMonth']=='09'?' selected="selected"':'');?>>September</option>
						  <option value="10"<?php echo($_POST['agentMonth']=='10'?' selected="selected"':'');?>>October</option>
						  <option value="11"<?php echo($_POST['agentMonth']=='11'?' selected="selected"':'');?>>November</option>
						  <option value="12"<?php echo($_POST['agentMonth']=='12'?' selected="selected"':'');?>>December</option>
						</select>
					</fieldset>
				</div>
        <div class="col-md-3 col-sm-3">
          <p class="report-header">Agents Year-to-Date</p>
          <fieldset class="form-group">
            <select class="form-control" id="agentYearlyArea" name="agentYearlyArea">
              <option value="blank">-- Select Area --</option>
              <option value="All Markets"<?php echo($_POST['agentYearlyArea']=='All Markets'?' selected="selected"':'');?>>All Areas</option>
              <option value="North"<?php echo($_POST['agentYearlyArea']=='North'?' selected="selected"':'');?>>Far Uptown</option>
              <option value="Westside"<?php echo($_POST['agentYearlyArea']=='Westside'?' selected="selected"':'');?>>Upper West Side</option>
              <option value="Eastside"<?php echo($_POST['agentYearlyArea']=='Eastside'?' selected="selected"':'');?>>Upper East Side</option>
              <option value="Chelsea"<?php echo($_POST['agentYearlyArea']=='Chelsea'?' selected="selected"':'');?>>Midtown West</option>
              <option value="SMG"<?php echo($_POST['agentYearlyArea']=='SMG'?' selected="selected"':'');?>>Midtown East</option>
              <option value="Village"<?php echo($_POST['agentYearlyArea']=='Village'?' selected="selected"':'');?>>East/West Village</option>
              <option value="Lower"<?php echo($_POST['agentYearlyArea']=='Lower'?' selected="selected"':'');?>>Downtown</option>
            </select>
          </fieldset>
        </div>
			</div>
			<div class="row report-row">
        <div class="col-md-3 col-sm-3">
					<fieldset class="form-group">
						<select class="form-control" id="listYear" name="listYear">
						  <option value="blank">-- Select Year --</option>
              <option value="2013"<?php echo($_POST['listYear']=='2013'?' selected="selected"':'');?>>2013</option>
              <option value="2014"<?php echo($_POST['listYear']=='2014'?' selected="selected"':'');?>>2014</option>
						  <option value="2015"<?php echo($_POST['listYear']=='2015'?' selected="selected"':'');?>>2015</option>
						  <option value="2016"<?php echo($_POST['listYear']=='2016'?' selected="selected"':'');?>>2016</option>
						  <option value="2017"<?php echo($_POST['listYear']=='2017'?' selected="selected"':'');?>>2017</option>
						</select>
					</fieldset>
				</div>
        <div class="col-md-3 col-sm-3">
					<fieldset class="form-group">
						<button class="form-control" type="submit" name="listings-yearly" style="background-color: #0085C6; color: #FFFFFF;">Year-to-Date Report</button>
					</fieldset>
				</div>
				<div class="col-md-3 col-sm-3">
					<fieldset class="form-group">
						<select class="form-control" id="agentYear" name="agentYear">
						  <option value="blank">-- Select Year --</option>
              <option value="2013"<?php echo($_POST['agentYear']=='2013'?' selected="selected"':'');?>>2013</option>
              <option value="2014"<?php echo($_POST['agentYear']=='2014'?' selected="selected"':'');?>>2014</option>
						  <option value="2015"<?php echo($_POST['agentYear']=='2015'?' selected="selected"':'');?>>2015</option>
						  <option value="2016"<?php echo($_POST['agentYear']=='2016'?' selected="selected"':'');?>>2016</option>
						  <option value="2017"<?php echo($_POST['agentYear']=='2017'?' selected="selected"':'');?>>2017</option>
						</select>
					</fieldset>
				</div>
        <div class="col-md-3 col-sm-3">
          <fieldset class="form-group">
            <select class="form-control" id="agentYearlyCode" name="agentYearlyCode" placeholder="-- Select Agent --" value="blank">
              <?php
              $SQL = "SELECT `first_name`, `last_name`, `agent_id` FROM `registered_agents` WHERE (`active` = 'Y') GROUP BY agent_id ORDER BY last_name ASC";
              $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

              while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                if($row['first_name'] != ".T." && $row['first_name'] != ".F." && $row['last_name'] != ".T." && $row['last_name'] != ".F."){
                  $id = $row['agent_id'];
                  if (strlen($id) <= 1){
                    $name = "No Agent";
                  } else {
                    $name = $row['last_name'] . ",  " . $row['first_name'];
                  }
                  echo "<option value='".$id."' ".(($_POST["agentYearlyCode"]==$id)?"selected":"").">".$name."</option>";
                  //echo "<option value='".$id."' ".(($_POST["agentCode"]==$id)?"selected":"").">".$name."</option>";
                }
              }
               ?>
            </select>
          </fieldset>
        </div>
			</div>
			<div class="row report-row">
				<div class="col-md-3 col-sm-3">
					<fieldset class="form-group">
						<select class="form-control" id="listArea" name="listArea">
						  <option value="blank">-- Select Area --</option>
              <option value="All Markets"<?php echo($_POST['listArea']=='All Markets'?' selected="selected"':'');?>>All Areas</option>
						  <option value="North"<?php echo($_POST['listArea']=='North'?' selected="selected"':'');?>>Far Uptown</option>
						  <option value="Westside"<?php echo($_POST['listArea']=='Westside'?' selected="selected"':'');?>>Upper West Side</option>
						  <option value="Eastside"<?php echo($_POST['listArea']=='Eastside'?' selected="selected"':'');?>>Upper East Side</option>
						  <option value="Chelsea"<?php echo($_POST['listArea']=='Chelsea'?' selected="selected"':'');?>>Midtown West</option>
						  <option value="SMG"<?php echo($_POST['listArea']=='SMG'?' selected="selected"':'');?>>Midtown East</option>
						  <option value="Village"<?php echo($_POST['listArea']=='Village'?' selected="selected"':'');?>>East/West Village</option>
						  <option value="Lower"<?php echo($_POST['listArea']=='Lower'?' selected="selected"':'');?>>Downtown</option>
						</select>
					</fieldset>
				</div>
  				<div class="col-md-3 col-md-offset-3 col-sm-3 col-sm-offset-3">
  					<fieldset class="form-group">
  						<select class="form-control" id="agentArea" name="agentArea">
  						  <option value="blank">-- Select Area --</option>
                <option value="All Markets"<?php echo($_POST['agentArea']=='All Markets'?' selected="selected"':'');?>>All Areas</option>
                <option value="North"<?php echo($_POST['agentArea']=='North'?' selected="selected"':'');?>>Far Uptown</option>
  						  <option value="Westside"<?php echo($_POST['agentArea']=='Westside'?' selected="selected"':'');?>>Upper West Side</option>
  						  <option value="Eastside"<?php echo($_POST['agentArea']=='Eastside'?' selected="selected"':'');?>>Upper East Side</option>
  						  <option value="Chelsea"<?php echo($_POST['agentArea']=='Chelsea'?' selected="selected"':'');?>>Midtown West</option>
  						  <option value="SMG"<?php echo($_POST['agentArea']=='SMG'?' selected="selected"':'');?>>Midtown East</option>
  						  <option value="Village"<?php echo($_POST['agentArea']=='Village'?' selected="selected"':'');?>>East/West Village</option>
  						  <option value="Lower"<?php echo($_POST['agentArea']=='Lower'?' selected="selected"':'');?>>Downtown</option>
  						</select>
  					</fieldset>
  				</div>
          <div class="col-md-3 col-sm-3">
  					<fieldset class="form-group">
  						<button class="form-control" type="submit" name="agent-yearly" style="background-color: #0085C6; color: #FFFFFF;">Year-to-Date Report</button>
  					</fieldset>
  				</div>
  			</div>
  			<div class="row report-row">
				<div class="col-md-3 col-sm-3">
          <fieldset class="form-group">
  				  <button class="form-control" type="submit" name="report-submit" style="background-color: #0085C6; color: #FFFFFF;">Monthly Report</button>
  				</fieldset>
				</div>
			  <!--<div class="col-md-2 col-md-offset-3">
          <fieldset class="form-group">
					       <input class="form-control" type="text" id="agentCode" name="agentCode" placeholder="--Agent Code--" value="<?php if (isset($_POST['agentCode'])) echo $_POST['agentCode']; ?>"/>
          </fieldset>
				</div>-->
        <div class="col-md-3 col-md-offset-3 col-sm-3 col-sm-offset-3">
          <fieldset class="form-group">
            <select class="form-control" id="agentCode" name="agentCode" placeholder="-- Select Agent --" value="blank">
              <?php
              $SQL = "SELECT `first_name`, `last_name`, `agent_id` FROM `registered_agents` WHERE (`active` = 'Y') GROUP BY agent_id ORDER BY last_name ASC";
              $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());

              while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
                if($row['first_name'] != ".T." && $row['first_name'] != ".F." && $row['last_name'] != ".T." && $row['last_name'] != ".F."){
                  $id = $row['agent_id'];
                  if (strlen($id) <= 1){
                    $name = "No Agent";
                  } else {
                    $name = $row['last_name'] . ",  " . $row['first_name'];
                  }
                  echo "<option value='".$id."' ".(($_POST["agentCode"]==$id)?"selected":"").">".$name."</option>";
                  //echo "<option value='".$id."' ".(($_POST["agentCode"]==$id)?"selected":"").">".$name."</option>";
                }
              }
               ?>
            </select>
          </fieldset>
        </div>
			</div>
			<div class="row report-row">
        <div class="col-md-3 col-md-offset-6 col-sm-3 col-sm-offset-6">
          <fieldset class="form-group">
  				  <button class="form-control" type="submit" name="agent-submit" style="background-color: #0085C6; color: #FFFFFF;">Monthly Report</button>
  				</fieldset>
				</div>
			</div>
		</form>
		<div class="container">
      <div class="row">
				<div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-1">
					<div id="chart_div1" className="report-chart"></div>
          <div id="chart_div2" className="report-chart"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-1">
					<div id="chart_div3" className="report-chart"></div>
          <div id="chart_div4" className="report-chart"></div>
				</div>
			</div>
      <div class="row">
				<div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-1">
					<div id="chart_div5" className="report-chart"></div>
          <div id="chart_div6" className="report-chart"></div>
				</div>
			</div>
      <div class="row">
				<div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-1">
					<div id="chart_div7" className="report-chart"></div>
          <div id="chart_div8" className="report-chart"></div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4 col-md-offset-2 col-sm-4 col-sm-offset-1">
					<div id="chart_div9" className="report-chart"></div>
          <div id="chart_div10" className="report-chart"></div>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>
<div id="footer"></div>
<div id="overlay"></div>
<div id="ajax-box"></div>
<div id="ajax-box2"></div>

<script src="/js/jquery.watch.js?3866665977" type="text/javascript"></script>
<script type="text/babel">

  ReactDOM.render(
    <Header />,
    document.getElementById("header")
  );

  ReactDOM.render(
    <NavBar mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("navbar")
  );

  ReactDOM.render(
    <AddressSearch mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("address-search")
  );

  ReactDOM.render(
    <Footer mainPage={"<? echo $mainPage ?>"} />,
    document.getElementById("footer")
  );

</script>

<?php
// listings MONTHLY REPORTS
if (isset($_POST['report-submit'])) {

  ?>
  <!--Change all other dropdowns to first option-->
  <script type="text/javascript">
  $( '#listYearlyArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentMonth' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYear' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentCode' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYearlyArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYearlyCode' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  </script>

  <?php


	$listMonth = $_POST['listMonth'];
	$listYear = $_POST['listYear'];
	$listArea = $_POST['listArea'];

  if (($listMonth == "blank") || ($listYear == "blank") || ($listYearArea == "blank"))
		{
			//DO NOT SUBMIT
			//Show pop up instead
			echo "<script type='text/javascript'>alert('Please fill out all fields!')</script>";
		}
	else
		{

      if (($listMonth == '04') || ($listMonth == '06') || ($listMonth == '09') || ($listMonth == '11')) {
        $listDay = '30';
      } elseif ($listMonth == '02'){
        $listDay = '28';
      } else {
        $listDay = '31';
      }

      if ($listMonth == '01') {
        $chartLabelYear = $listYear - 1;
        $chartLabelDay = '31';
        $chartLabelMonth = '12';
        $chartLabelStartOther = $chartLabelMonth . '/' . $chartLabelDay . '/' . $chartLabelYear;
      } else {
        $chartLabelYear = $listYear;
        $chartLabelMonth = $listMonth - 1;
          if (($chartLabelMonth == '04') || ($chartLabelMonth == '06') || ($chartLabelMonth == '09') || ($chartLabelMonth == '11')) {
            $chartLabelDay = '30';
          } elseif ($chartLabelMonth == '02'){
            $chartLabelDay = '28';
          } else {
            $chartLabelDay = '31';
          }
        $chartLabelStartOther = $chartLabelMonth . '/' . $chartLabelDay . '/' . $chartLabelYear;
      }

      $listBegDay = '01';
      //$listEndDay = '28';
  		$listMonthName = date('F', mktime(0, 0, 0, $listMonth, 10));
    	$listBegDate = $listYear . '-' . $listMonth . '-' . $listBegDay;
      $listEndDate = $listYear . '-' . $listMonth . '-' . $listDay;
      $chartLabelStart = $listMonth . '/' . $listBegDay . '/' . $listYear;
      $chartLabelEnd = $listMonth . '/' . $listDay . '/' . $listYear;


		//Query for number of beds by month
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND LEFT(list_date, 2) = '" . $listMonth . "' AND RIGHT(list_date, 4) = '" . $listYear . "' AND status = 'AVAIL' AND last_change_date < '" . $listEndDate . "' GROUP BY bed";
		//Query for total inventory at beginning of month - chart 1
    if ($listArea == 'All Markets') {
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') < '" . $listBegDate . "') GROUP BY bed";
      //print $sql;
    } else {
      if ($listArea == 'North'){
         $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'E-North' OR nbrhood = 'W-North') AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') < '" . $listBegDate . "') GROUP BY bed";
      } else {
         $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') < '" . $listBegDate . "') GROUP BY bed";
      }
    }
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') < '" . $listBegDate . "') GROUP BY bed";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Beginning of month query error.".mysql_error());

		$bedChartTotal = 0;
		$bedChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$bedChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$bedChartTotal = $bedChartTotal + $row['bedCount'];
		}
		//echo $bedChartTotal;
		$jsonBedChart = json_encode($bedChart);

		//Query for new and updated listings for month
    if ($listArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
    } else {
      if ($listArea == 'North'){
        $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE (nbrhood = 'E-North' OR nbrhood = 'W-North') AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
      }
    }
	  //$sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. New/Updated query error.".mysql_error());

		$chartDataTotal = 0;
		$chartData = array(
			'cols' => array(
			  array('type' => 'string', 'label' => 'Bedroom'),
			  array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$chartData['rows'][] = array(
				'c' => array (
				  array('v' => $row['bed']),
				  array('v' => $row['totalCount'])
				 )
			);
			$chartDataTotal = $chartDataTotal + $row['totalCount'];
		}

		$jsonNewChart = json_encode($chartData);

		//Query for sold/off-the-market at the end of the month
    if ($listArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
    } else {
      if ($listArea == 'North') {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'E-North' OR nbrhood = 'W-North')  AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
      }
    }
	 //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
  $result = mysql_query( $sql ) or die("Couldn't execute query. Sold/off-market query error.".mysql_error());

		$soldChartTotal = 0;
		$soldChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$soldChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$soldChartTotal = $soldChartTotal + $row['bedCount'];
		}


		$jsonSoldChart = json_encode($soldChart);

		//Total inventory at end of the month
    if ($listArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
    } else {
      if ($listArea == 'North') {
          $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$endChartTotal = 0;
		$endListChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$endListChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$endChartTotal = $endChartTotal + $row['bedCount'];
		}

		$jsonEndListChart = json_encode($endListChart);

    //Total inventory by age at end of the month
    if ($listArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
    } else {
      if ($listArea == 'North') {
         $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = 'W-North' OR nbrhood = 'E-North' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());
    //print $sql;

		$ageChartTotal = 0;
		$ageListChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Year'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      //$monthName = date('M', mktime(0,0,0, $row['date'],10));
			$ageListChart['rows'][] = array(
				'c' => array (
					 array('v' =>  $row['date']),
					 array('v' => $row['ageCount'])
				 )
			);
			$ageChartTotal = $ageChartTotal + $row['ageCount'];
		}

		$jsonAgeListChart = json_encode($ageListChart);


    switch ($listArea) { // Translate Bellmarc neighborhood codes to standard names
      case "All Markets": $listArea = "All Areas";
          break;
      case "North": $listArea = "Far Uptown";
          break;
      case "Eastside": $listArea = "Upper East";
          break;
      case "Westside": $listArea = "Upper West";
          break;
      case "SMG": $listArea = "Midtown East";
          break;
      case "Chelsea": $listArea = "Midtown West";
          break;
      case "Village": $listArea = "Greenwich Village";
          break;
      case "Lower": $listArea = "Downtown";
          break;
    };

		?>
			<!--Load the AJAX API-->
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<script type="text/javascript">
			  // Load the Visualization API
			  console.log(<?php echo $jsonBedChart;?>);
			  console.log(<?php echo $jsonNewChart;?>);
			  console.log(<?php echo $jsonEndListChart;?>);
			  console.log(<?php echo $jsonSoldChart;?>);
        console.log(<?php echo $jsonAgeListChart;?>);
			  //console.log(<?php echo $json;?>);
			  google.load('visualization', '1', {'packages':['corechart', 'corechart']});


			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);
			//google.setOnLoadCallback(drawTable);



			function drawChart() {

        // total inventory at beginning of month
				var data1 = new google.visualization.DataTable(<?php echo $jsonBedChart;?>);
        var view1 = new google.visualization.DataView(data1);
        view1.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

				// new and updated listings for month
				var data2 = new google.visualization.DataTable(<?php echo $jsonNewChart;?>);
        var view2 = new google.visualization.DataView(data2);
        view2.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

				// sold/off-the-market at the end of the month
				var data3 = new google.visualization.DataTable(<?php echo $jsonSoldChart;?>);
        var view3 = new google.visualization.DataView(data3);
        view3.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

				// Total inventory at end of the month
				var data4 = new google.visualization.DataTable(<?php echo $jsonEndListChart;?>);
        var view4 = new google.visualization.DataView(data4);
        view4.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

        // Total inventory at end of the month by age
				var data5 = new google.visualization.DataTable(<?php echo $jsonAgeListChart;?>);
        var view5 = new google.visualization.DataView(data5);
        view5.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);


				var options1 = {
					title: 'Listings Total:   <?php echo $bedChartTotal;?> \nArea:   <?php echo $listArea;?> \nPeriod:   as of <?php echo $chartLabelStartOther;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

				var options2 = {
					title: 'New and Updated Listings Total:   <?php echo $chartDataTotal;?> \nArea:   <?php echo $listArea;?> \nPeriod:   <?php echo $chartLabelStart;?> - <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

				var options3 = {
					title: 'Sold and Off-The-Market Total:   <?php echo $soldChartTotal;?> \nArea:   <?php echo $listArea;?> \nPeriod:   <?php echo $chartLabelStart;?> - <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

        var options4 = {
					title: 'Listings Total:   <?php echo $endChartTotal;?> \nArea:   <?php echo $listArea;?> \nPeriod:   through <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

        var options5 = {
					title: 'Listings Total:   <?php echo $ageChartTotal;?> \nArea:   <?php echo $listArea;?> \nPeriod:   through <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Listings by Age',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};


			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
			  chart.draw(view1, options1);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
			  chart.draw(view2, options2);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div5'));
			  chart.draw(view3, options3);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div7'));
			  chart.draw(view4, options4);

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div9'));
			  chart.draw(view5, options5);

			}

			/*window.onerror = function(msg, url, linenumber) {
				alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
				return true;
			}*/
			</script>
		<?php
	}
}

// LISTINGS YEAR_TO_DATE REPORT
if (isset($_POST['listings-yearly'])) {

  ?>
  <!--Change all other dropdowns to first option-->
  <script type="text/javascript">
  $( '#listMonth' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#listYear' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#listArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentMonth' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYear' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentCode' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYearlyArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYearlyCode' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  </script>

  <?php

	$listYearlyArea = $_POST['listYearlyArea'];
  $_POST['listYear'] = "blank";
  $today = date("Y-m-d");
  $year = date("Y");
  $month = date("m");
  $listDay = date("d");

  //echo 'year' . $year . '  month ' . $month . '  day ' . $listDay;

  if (($listYearlyArea == "blank"))
		{
			//DO NOT SUBMIT
			//Show pop up instead
			echo "<script type='text/javascript'>alert('Please select area!')</script>";
		}
	else
		{


      $listBegDay = '01';
      $listBegMonth = '01';
  		$listMonthName = date('F', mktime(0, 0, 0, $listMonth, 10));
    	$listBegDate = $year . '-' . $listBegMonth . '-' . $listBegDay;
      $listEndDate = $year . '-' . $month . '-' . $listDay;
      $chartStartDate = $listBegMonth . '-' . $listBegDay . '-' . $year;
      $chartEndDate = $month . '-' . $listDay . '-' . $year;

    //  echo 'Start ' . $chartStartDate . '  End ' . $chartEndDate;


		//Query for number of beds year-to-date
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND LEFT(list_date, 2) = '" . $listMonth . "' AND RIGHT(list_date, 4) = '" . $listYear . "' AND status = 'AVAIL' AND last_change_date < '" . $listEndDate . "' GROUP BY bed";
		//Query for total inventory at beginning of month - chart 1
    if ($listYearlyArea == 'All Markets') {
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') GROUP BY bed";
      //print $sql;
    } else {
      if ($listYearlyArea == 'North'){
         $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = 'E-North' OR nbrhood = 'W-North' AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') GROUP BY bed";
      } else {
         $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') GROUP BY bed";
      }
    }
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') < '" . $listBegDate . "') GROUP BY bed";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Beginning of month query error.".mysql_error());

		$bedChartTotal = 0;
		$bedChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$bedChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$bedChartTotal = $bedChartTotal + $row['bedCount'];
		}
		//echo $bedChartTotal;
		$jsonBedChart = json_encode($bedChart);

		//Query for new and updated listings year-to-date
    if ($listYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
    } else {
      if ($listYearlyArea == 'North'){
        $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE nbrhood = 'E-North' OR nbrhood = 'W-North' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
      }
    }
	  //$sql = "SELECT COUNT(*) AS totalCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) OR ((DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(last_change_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "'))) GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. New/Updated query error.".mysql_error());

		$chartDataTotal = 0;
		$chartData = array(
			'cols' => array(
			  array('type' => 'string', 'label' => 'Bedroom'),
			  array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$chartData['rows'][] = array(
				'c' => array (
				  array('v' => $row['bed']),
				  array('v' => $row['totalCount'])
				 )
			);
			$chartDataTotal = $chartDataTotal + $row['totalCount'];
		}

		$jsonNewChart = json_encode($chartData);

		//Query for sold/off-the-market year-to-date
    if ($listYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
    } else {
      if ($listYearlyArea == 'North') {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = 'E-North' OR nbrhood = 'W-North'  AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
      }
    }
	 //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND ((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "')) AND status <> 'AVAIL' GROUP BY bed";
  $result = mysql_query( $sql ) or die("Couldn't execute query. Sold/off-market query error.".mysql_error());

		$soldChartTotal = 0;
		$soldChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$soldChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$soldChartTotal = $soldChartTotal + $row['bedCount'];
		}


		$jsonSoldChart = json_encode($soldChart);

		//Total inventory
    if ($listYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
    } else {
      if ($listYearlyArea == 'North') {
          $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$endChartTotal = 0;
		$endListChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$endListChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$endChartTotal = $endChartTotal + $row['bedCount'];
		}


		$jsonEndListChart = json_encode($endListChart);

    //Total inventory by age year-to-date
    if ($listYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
    } else {
      if ($listYearlyArea == 'North') {
        $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = 'W-North' OR nbrhood = 'E-North' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$ageChartTotal = 0;
		$ageListChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Month'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$ageListChart['rows'][] = array(
				'c' => array (
					 array('v' => date('M', mktime(0,0,0, $row['date'],10))),
					 array('v' => $row['ageCount'])
				 )
			);
			$ageChartTotal = $ageChartTotal + $row['ageCount'];
		}


		$jsonAgeListChart = json_encode($ageListChart);

    switch ($listYearlyArea) { // Translate Bellmarc neighborhood codes to standard names
      case "All Markets": $listYearlyArea = "All Areas";
          break;
      case "North": $listYearlyArea = "Far Uptown";
          break;
      case "Eastside": $listYearlyArea = "Upper East";
          break;
      case "Westside": $listYearlyArea = "Upper West";
          break;
      case "SMG": $listYearlyArea = "Midtown East";
          break;
      case "Chelsea": $listYearlyArea = "Midtown West";
          break;
      case "Village": $listYearlyArea = "Greenwich Village";
          break;
      case "Lower": $listYearlyArea = "Downtown";
          break;
    };

		?>
			<!--Load the AJAX API-->
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<script type="text/javascript">
			  // Load the Visualization API
			  console.log(<?php echo $jsonBedChart;?>);
			  console.log(<?php echo $jsonNewChart;?>);
			  console.log(<?php echo $jsonEndListChart;?>);
			  console.log(<?php echo $jsonSoldChart;?>);
        console.log(<?php echo $jsonAgeListChart;?>);
			  //console.log(<?php echo $json;?>);
			  google.load('visualization', '1', {'packages':['corechart', 'corechart']});


			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);
			//google.setOnLoadCallback(drawTable);



			function drawChart() {

        // total inventory at beginning of month
				//var data1 = new google.visualization.DataTable(<?php echo $jsonBedChart;?>);

				// new and updated listings
				var data2 = new google.visualization.DataTable(<?php echo $jsonNewChart;?>);
        var view2 = new google.visualization.DataView(data2);
        view2.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

				// sold/off-the-market
				var data3 = new google.visualization.DataTable(<?php echo $jsonSoldChart;?>);
        var view3 = new google.visualization.DataView(data3);
        view3.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

				// Total inventory
				var data4 = new google.visualization.DataTable(<?php echo $jsonEndListChart;?>);
        var view4 = new google.visualization.DataView(data4);
        view4.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

        // Total inventory by age
				var data5 = new google.visualization.DataTable(<?php echo $jsonAgeListChart;?>);
        var view5 = new google.visualization.DataView(data5);
        view5.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);


				var options1 = {
					title: 'Listings Total:   <?php echo $bedChartTotal;?> \nArea:   <?php echo $listYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

				var options2 = {
					title: 'New and Updated Listings Total:   <?php echo $chartDataTotal;?> \nArea:   <?php echo $listYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

				var options3 = {
					title: 'Sold and Off-The-Market Total:   <?php echo $soldChartTotal;?> \nArea:   <?php echo $listYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

        var options4 = {
					title: 'Listings Total:   <?php echo $endChartTotal;?> \nArea:   <?php echo $listYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

        var options5 = {
					title: 'Listings Total:   <?php echo $ageChartTotal;?> \nArea:   <?php echo $listYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Listings by Age',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};


			  //var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
			  //chart.draw(data1, options1);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div1'));
			  chart.draw(view2, options2);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div3'));
			  chart.draw(view3, options3);

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div5'));
			  chart.draw(view4, options4);

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div7'));
			  chart.draw(view5, options5);

			}

			/*window.onerror = function(msg, url, linenumber) {
				alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
				return true;
			}*/
			</script>
		<?php
	}
}

// AGENT MONTHLY REPORTS
if (isset($_POST['agent-submit'])) {

?>
<!--Change all other dropdowns to first option-->
<script type="text/javascript">
$( '#listMonth' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
$( '#listYear' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
$( '#listArea' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
$( '#listYearlyArea' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
$( '#agentYearlyArea' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
$( '#agentYearlyCode' ).each( function () {
    if ( $( this ).children().length > 0 ) {
        $( $( this ).children()[0] ).attr( 'selected', 'selected' );
    }
} );
</script>

<?php

	$agentArea = $_POST['agentArea'];
  $agentMonth = $_POST['agentMonth'];
	$agentYear = $_POST['agentYear'];
	$agentCode = $_POST['agentCode'];

  //echo $agentCode;
	//echo $month;
	if (($agentMonth == "blank") || ($agentYear == "blank") || ($agentArea == "blank") || ($agentCode == "--Agent Code--"))
		{
			//DO NOT SUBMIT
			//Show pop up instead
			echo "<script type='text/javascript'>alert('Please fill out all fields!')</script>";
		}
	else
		{


    if (($agentMonth == '04') || ($agentMonth == '06') || ($agentMonth == '09') || ($agentMonth == '11')) {
      $agentDay = '30';
    } elseif ($agentMonth == '02'){
      $agentDay = '28';
    } else {
      $agentDay = '31';
    }

    if ($agentMonth == '01') {
      $chartLabelYear = $agentYear - 1;
      $chartLabelDay = '31';
      $chartLabelMonth = '12';
      $chartLabelStartOther = $chartLabelMonth . '/' . $chartLabelDay . '/' . $chartLabelYear;
    } else {
      $chartLabelYear = $agentYear;
      $chartLabelMonth = $agentMonth - 1;
        if (($chartLabelMonth == '04') || ($chartLabelMonth == '06') || ($chartLabelMonth == '09') || ($chartLabelMonth == '11')) {
          $chartLabelDay = '30';
        } elseif ($chartLabelMonth == '02'){
          $chartLabelDay = '28';
        } else {
          $chartLabelDay = '31';
        }
      $chartLabelStartOther = $chartLabelMonth . '/' . $chartLabelDay . '/' . $chartLabelYear;
    }

    $agentBegDay = '01';

		$agentMonthName = date('F', mktime(0, 0, 0, $agentMonth, 10));
  	$agentBegDate = $agentYear . '-' . $agentMonth . '-' . $agentBegDay;
    $agentEndDate = $agentYear . '-' . $agentMonth . '-' . $agentDay;
    $chartLabelStart = $agentMonth . '/' . $agentBegDay . '/' . $agentYear;
    $chartLabelEnd = $agentMonth . '/' . $agentDay . '/' . $agentYear;


    //$date = date_create();
    //echo date_format($date, 'U = m/d/Y');
    $rtimeBegDate = strtotime($agentBegDate);
    //echo $rtimeBegDate;
    $rtimeEndDate = strtotime($agentEndDate);
    //echo $rtimeEndDate;


    // Agent Name
		$sql = "SELECT first_name, last_name FROM `registered_agents` WHERE agent_id = '" . $agentCode . "'";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Error 2.".mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$firstname = $row['first_name'];
			$lastname = $row['last_name'];
		}

    if ($_POST['agentCode'] == ""){
      $lastname = "No Agent";
      $firstname = "";
    }

    //echo $lastname;

		//Agent Report
    if ($agentArea == 'All Markets') {
      $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND agent_id_1 = '". $agentCode ."' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      //print $sql;
    } else {
      if ($agentArea == 'North') {
        $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND agent_id_1 = '". $agentCode ."' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND agent_id_1 = '". $agentCode ."' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      }
    }

    //$sql = "SELECT COUNT(*) AS listCount FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND agent_id_1 = '". $agentCode ."' AND list_date >= '" . $agentBegDate . "' AND list_date <='" . $agentEndDate . "' AND status = 'AVAIL'";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Error 8.".mysql_error());

    $agentChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$agentChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['listCount'])
				 )
			);
      $agentChartTotal = $agentChartTotal + $row['listCount'];
    }

      $jsonAgentChart = json_encode($agentChart);

    //New buyers
    //$sql = "SELECT COUNT(*) AS buyerCount FROM `users` WHERE P_agent = '" . $agentCode . "'";
    $sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "') AND (rtime >= '" . $rtimeBegDate . "' AND rtime <= '" . $rtimeEndDate . "')";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Error 9.".mysql_error());

    $buyerChartTotal = 0;
    $buyerChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Buyers'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $buyerChart['rows'][] = array(
            'c' => array (
                 array('v' => 'New Buyers'),
                 array('v' => $row['buyerCount'])
             )
        );
        $buyerChartTotal = $buyerChartTotal + $row['buyerCount'];
    }


    $jsonBuyerChart = json_encode($buyerChart);

    //Total buyers
    //$sql = "SELECT COUNT(*) AS buyerCount FROM `users` WHERE P_agent = '" . $agentCode . "'";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    $sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE rtime <= '" . $rtimeEndDate . "' AND (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Error 9.".mysql_error());

    $buyerAllChartTotal = 0;
    $buyerAllChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Buyers'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $buyerAllChart['rows'][] = array(
            'c' => array (
                 array('v' => 'Buyers'),
                 array('v' => $row['buyerCount'])
             )
        );
        $buyerAllChartTotal = $buyerAllChartTotal + $row['buyerCount'];
    }


    $jsonBuyerAllChart = json_encode($buyerAllChart);

    //Total inventory at end of the month
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND list_date <= '" . $agentEndDate . "' AND status = 'AVAIL' GROUP BY bed";
    if ($agentArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
    } else {
      if ($agentArea == 'North') {
          $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$agentAllChartTotal = 0;
		$agentListChart = array(
      'cols' => array(
         array('type' => 'string', 'label' => 'Bedroom'),
         array('type' => 'number', 'label' => 'Number')
      )
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$agentListChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$agentAllChartTotal = $agentAllChartTotal + $row['bedCount'];
		}


		$jsonAgentChartTotal = json_encode($agentListChart);

    //Total inventory by age at end of the month
    if ($agentArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
    } else {
      if ($agentArea == 'North') {
         $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = 'W-North' OR nbrhood = 'E-North' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS ageCount, YEAR(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND agent_id_1 = '". $agentCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());
    //print $sql;

    $agentAgeChartTotal = 0;
    $agentAgeListChart = array(
      'cols' => array(
         array('type' => 'string', 'label' => 'Year'),
         array('type' => 'number', 'label' => 'Number')
      )
    );

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
      //$monthName = date('M', mktime(0,0,0, $row['date'],10));
      $agentAgeListChart['rows'][] = array(
        'c' => array (
           array('v' =>  $row['date']),
           array('v' => $row['ageCount'])
         )
      );
      $agentAgeChartTotal = $agentAgeChartTotal + $row['ageCount'];
    }

    $jsonAgentAgeListChart = json_encode($agentAgeListChart);

    switch ($agentArea) { // Translate Bellmarc neighborhood codes to standard names
      case "All Markets": $agentArea = "All Areas";
          break;
      case "North": $agentArea = "Far Uptown";
          break;
      case "Eastside": $agentArea = "Upper East";
          break;
      case "Westside": $agentArea = "Upper West";
          break;
      case "SMG": $agentArea = "Midtown East";
          break;
      case "Chelsea": $agentArea = "Midtown West";
          break;
      case "Village": $agentArea = "Greenwich Village";
          break;
      case "Lower": $agentArea = "Downtown";
          break;
    };

		?>
			<!--Load the AJAX API-->
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<script type="text/javascript">
			  // Load the Visualization API
			  console.log(<?php echo $jsonAgentChart;?>);
        console.log(<?php echo $jsonBuyerChart;?>);
        console.log(<?php echo $jsonAgentChartTotal;?>);
			  google.load('visualization', '1', {'packages':['corechart']});


			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);

			function drawChart() {

			  // New Listings for date selected
				var data1 = new google.visualization.DataTable(<?php echo $jsonAgentChart;?>);
        var view1 = new google.visualization.DataView(data1);
        view1.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // New Buyers for date selected
        var data2 = new google.visualization.DataTable(<?php echo $jsonBuyerChart;?>);
        var view2 = new google.visualization.DataView(data2);
        view2.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // All Buyers
        var data3 = new google.visualization.DataTable(<?php echo $jsonBuyerAllChart;?>);
        var view3 = new google.visualization.DataView(data3);
        view3.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // Total listings for date selected
        var data4 = new google.visualization.DataTable(<?php echo $jsonAgentChartTotal;?>);
        var view4 = new google.visualization.DataView(data4);
        view4.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // Total listings by age for date selected
        var data5 = new google.visualization.DataTable(<?php echo $jsonAgentAgeListChart;?>);
        var view5 = new google.visualization.DataView(data5);
        view5.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

        var options1 = {
					title: 'New Listings Total:   <?php echo $agentChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentArea;?> \nPeriod:   <?php echo $chartLabelStart;?> - <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

        var options2 = {
          title: 'New Buyers Total:   <?php echo $buyerChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   All Areas \nPeriod:   <?php echo $chartLabelStart;?> - <?php echo $chartLabelEnd;?>',
          width: 600,
          height: 400,
          legend: { position: "none" },
          hAxis: {
            title: '',
            minValue: 0,
          },
          vAxis: {
            title: 'Monthly Totals',
            minValue: 0,
          },
        };

        var options3 = {
          title: 'Buyers Total:   <?php echo $buyerAllChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   All Areas \nPeriod:   through <?php echo $chartLabelEnd;?>',
          width: 600,
          height: 400,
          legend: { position: "none" },
          hAxis: {
            title: '',
            minValue: 0,
          },
          vAxis: {
            title: 'Monthly Totals',
            minValue: 0,
          },
        };

        var options4 = {
					title: 'Total Listings:   <?php echo $agentAllChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentArea;?> \nPeriod:   through <?php echo $chartLabelEnd;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Monthly Totals',
					},
				};

        var options5 = {
          title: 'Total Listings:   <?php echo $agentAgeChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentArea;?> \nPeriod:   through <?php echo $chartLabelEnd;?>',
          width: 600,
          height: 400,
          legend: { position: "none" },
          hAxis: {
            title: 'Listings by Age',
          },
          vAxis: {
            title: 'Monthly Totals',
          },
        };

			  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
			  chart.draw(view1, options1);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div4'));
			 chart.draw(view2, options2);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div6'));
			 chart.draw(view3, options3);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div8'));
			 chart.draw(view4, options4);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div10'));
			 //chart.draw(data5, options5);
       chart.draw(view5, options5);

			}
		/*	window.onerror = function(msg, url, linenumber) {
			alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
			return true;
		}*/
			</script>
<?php }
}

// AGENT YEAR TO DATE REPORTS
if (isset($_POST['agent-yearly'])) {

  ?>
  <!--Change all other dropdowns to first option-->
  <script type="text/javascript">
  $( '#listMonth' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#listYear' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#listArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#listYearlyArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentMonth' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentYear' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentArea' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  $( '#agentCode' ).each( function () {
      if ( $( this ).children().length > 0 ) {
          $( $( this ).children()[0] ).attr( 'selected', 'selected' );
      }
  } );
  </script>

  <?php

  $agentYearlyArea = $_POST['agentYearlyArea'];
  $agentYearlyCode = $_POST['agentYearlyCode'];

  $today = date("Y-m-d");
  $year = date("Y");
  $month = date("m");
  $listDay = date("d");

  //echo 'year' . $year . '  month ' . $month . '  day ' . $listDay;

  if (($agentYearlyArea == "blank"))
		{
			//DO NOT SUBMIT
			//Show pop up instead
			echo "<script type='text/javascript'>alert('Please select area!')</script>";
		}
	else
		{


      $agentBegDay = '01';
      $agentBegMonth = '01';
  		$agentMonthName = date('F', mktime(0, 0, 0, $month, 10));
    	$agentBegDate = $year . '-' . $agentBegMonth . '-' . $agentBegDay;
      $agentEndDate = $year . '-' . $month . '-' . $listDay;
      $chartStartDate = $agentBegMonth . '-' . $agentBegDay . '-' . $year;
      $chartEndDate = $month . '-' . $listDay . '-' . $year;

    //$date = date_create();
    //echo date_format($date, 'U = m/d/Y');
    $rtimeBegDate = strtotime($agentBegDate);
    //echo $rtimeBegDate;
    $rtimeEndDate = strtotime($agentEndDate);
    //echo $rtimeEndDate;


    // Agent Name
		$sql = "SELECT first_name, last_name FROM `registered_agents` WHERE agent_id = '" . $agentYearlyCode . "'";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Error 2.".mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$firstname = $row['first_name'];
			$lastname = $row['last_name'];
		}

    if ($_POST['agentCode'] == ""){
      $lastname = "No Agent";
      $firstname = "";
    }

    //echo $lastname;

		//Agent Report
    if ($agentYearlyArea == 'All Markets') {
      $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE status = 'AVAIL' AND agent_id_1 = '". $agentYearlyCode ."' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      //print $sql;
    } else {
      if ($agentYearlyArea == 'North') {
        $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND agent_id_1 = '". $agentYearlyCode ."' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS listCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentYearlyArea . "' AND agent_id_1 = '". $agentYearlyCode ."' AND status = 'AVAIL' AND (((DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "'))) GROUP BY bed";
      }
    }

    //$sql = "SELECT COUNT(*) AS listCount FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND agent_id_1 = '". $agentCode ."' AND list_date >= '" . $agentBegDate . "' AND list_date <='" . $agentEndDate . "' AND status = 'AVAIL'";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Error 8.".mysql_error());

    $agentChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Bedroom'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$agentChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['listCount'])
				 )
			);
      $agentChartTotal = $agentChartTotal + $row['listCount'];
    }

      $jsonAgentChart = json_encode($agentChart);

    //New buyers
    //$sql = "SELECT COUNT(*) AS buyerCount FROM `users` WHERE P_agent = '" . $agentCode . "'";
  //  $sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentYearlyCode . "' OR P_agent2 = '" . $agentYearlyCode . "') AND (rtime >= '" . $rtimeBegDate . "' AND rtime <= '" . $rtimeEndDate . "')";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
  //  $result = mysql_query( $sql ) or die("Couldn't execute query.".mysql_error());

    $buyerChartTotal = 0;
    $buyerChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Buyers'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $buyerChart['rows'][] = array(
            'c' => array (
                 array('v' => 'New Buyers'),
                 array('v' => $row['buyerCount'])
             )
        );
        $buyerChartTotal = $buyerChartTotal + $row['buyerCount'];
    }


    $jsonBuyerChart = json_encode($buyerChart);

    //Total buyers
    //$sql = "SELECT COUNT(*) AS buyerCount FROM `users` WHERE P_agent = '" . $agentCode . "'";
    $sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE rtime >= '" . $rtimeBegDate . "' AND rtime <= '" . $rtimeEndDate . "' AND (P_agent = '" . $agentYearlyCode . "' OR P_agent2 = '" . $agentYearlyCode . "')";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Total buyers.".mysql_error());

    $buyerAllChartTotal = 0;
    $buyerAllChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Buyers'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $buyerAllChart['rows'][] = array(
            'c' => array (
                 array('v' => 'Buyers'),
                 array('v' => $row['buyerCount'])
             )
        );
        $buyerAllChartTotal = $buyerAllChartTotal + $row['buyerCount'];
    }


    $jsonBuyerAllChart = json_encode($buyerAllChart);
	
	//Total buyer listings
    //$sql = "SELECT COUNT(*) AS buyerCount FROM `users` WHERE P_agent = '" . $agentCode . "'";
    $sql = "SELECT COUNT(*) AS buyerListingCount, list_num FROM `users`, `saved_listings` WHERE email = user AND time >= '" . $rtimeBegDate . "' AND time <= '" . $rtimeEndDate . "' AND (P_agent = '" . $agentYearlyCode . "' OR P_agent2 = '" . $agentYearlyCode . "')";
    //$sql = "SELECT COUNT(*) AS buyerCount, id FROM `users` WHERE (P_agent = '" . $agentCode . "' OR P_agent2 = '" . $agentCode . "')";
    $result = mysql_query( $sql ) or die("Couldn't execute query. Total buyers.".mysql_error());

    $buyerAllListingsChartTotal = 0;
    $buyerAllListingsChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Buyers'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        $buyerAllListingsChart['rows'][] = array(
            'c' => array (
                 array('v' => 'Buyers'),
                 array('v' => $row['buyerListingCount'])
             )
        );
        $buyerAllListingsChartTotal = $buyerAllListingsChartTotal + $row['buyerListingCount'];
    }


    $jsonBuyerAllListingsChart = json_encode($buyerAllListingsChart);

    //Total inventory
		//$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentArea . "' AND list_date <= '" . $agentEndDate . "' AND status = 'AVAIL' GROUP BY bed";
    if ($agentYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
    } else {
      if ($agentYearlyArea == 'North') {
          $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $agentYearlyArea . "' AND agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$agentAllChartTotal = 0;
		$agentListChart = array(
      'cols' => array(
         array('type' => 'string', 'label' => 'Bedroom'),
         array('type' => 'number', 'label' => 'Number')
      )
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$agentListChart['rows'][] = array(
				'c' => array (
					 array('v' => $row['bed']),
					 array('v' => $row['bedCount'])
				 )
			);
			$agentAllChartTotal = $agentAllChartTotal + $row['bedCount'];
		}

		$jsonAgentChartTotal = json_encode($agentListChart);

    //Total inventory by age
    if ($agentYearlyArea == 'All Markets'){
      $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
    } else {
      if ($agentYearlyArea == 'North') {
        $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE (nbrhood = 'W-North' OR nbrhood = 'E-North') AND agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = 'W-North' OR nbrhood = 'E-North' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      } else {
        $sql = "SELECT COUNT(*) AS ageCount, MONTH(DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d')) AS date FROM `vow_data` WHERE nbrhood = '" . $agentYearlyArea . "' AND agent_id_1 = '". $agentYearlyCode ."' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $agentBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $agentEndDate . "') AND status = 'AVAIL' GROUP BY date";
        //$sql = "SELECT COUNT(*) AS ageCount, bed FROM `vow_data` WHERE nbrhood = '" . $listYearlyArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') >= '" . $listBegDate . "') AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
      }
    }
    //$sql = "SELECT COUNT(*) AS bedCount, bed FROM `vow_data` WHERE nbrhood = '" . $listArea . "' AND (DATE_FORMAT(STR_TO_DATE(list_date, '%m/%d/%Y'), '%Y-%m-%d') <= '" . $listEndDate . "') AND status = 'AVAIL' GROUP BY bed";
		$result = mysql_query( $sql ) or die("Couldn't execute query. Total inventory query error.".mysql_error());

		$agentAgeChartTotal = 0;
		$agentAgeListChart = array(
			'cols' => array(
				 array('type' => 'string', 'label' => 'Month'),
				 array('type' => 'number', 'label' => 'Number')
			)
		);

		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$agentAgeListChart['rows'][] = array(
				'c' => array (
					 array('v' => date('M', mktime(0,0,0, $row['date'],10))),
					 array('v' => $row['ageCount'])
				 )
			);
			$agentAgeChartTotal = $agentAgeChartTotal + $row['ageCount'];
		}


		$jsonAgentAgeListChart = json_encode($agentAgeListChart);


    switch ($agentYearlyArea) { // Translate Bellmarc neighborhood codes to standard names
      case "All Markets": $agentYearlyArea = "All Areas";
          break;
      case "North": $agentYearlyArea = "Far Uptown";
          break;
      case "Eastside": $agentYearlyArea = "Upper East";
          break;
      case "Westside": $agentYearlyArea = "Upper West";
          break;
      case "SMG": $agentYearlyArea = "Midtown East";
          break;
      case "Chelsea": $agentYearlyArea = "Midtown West";
          break;
      case "Village": $agentYearlyArea = "Greenwich Village";
          break;
      case "Lower": $agentYearlyArea = "Downtown";
          break;
    };

		?>
			<!--Load the AJAX API-->
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
			<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
			<script type="text/javascript">
			  // Load the Visualization API
			  console.log(<?php echo $jsonAgentChart;?>);
        console.log(<?php echo $jsonBuyerChart;?>);
        console.log(<?php echo $jsonAgentChartTotal;?>);
			  google.load('visualization', '1', {'packages':['corechart']});


			// Set a callback to run when the Google Visualization API is loaded.
			google.setOnLoadCallback(drawChart);

			function drawChart() {

			  // New Listings for date selected
			//	var data1 = new google.visualization.DataTable(<?php echo $jsonAgentChart;?>);
        // New Buyers for date selected
      //  var data2 = new google.visualization.DataTable(<?php echo $jsonBuyerChart;?>);
        // All Buyers for date selected
        var data2 = new google.visualization.DataTable(<?php echo $jsonBuyerAllChart;?>);
        var view2 = new google.visualization.DataView(data2);
        view2.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
		// All Buyers listings for date selected
        var data3 = new google.visualization.DataTable(<?php echo $jsonBuyerAllListingsChart;?>);
        var view3 = new google.visualization.DataView(data3);
        view3.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // Total Listings for date selected
        var data4 = new google.visualization.DataTable(<?php echo $jsonAgentChartTotal;?>);
        var view4 = new google.visualization.DataView(data4);
        view4.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);
        // Total Listings for date selected
        var data5 = new google.visualization.DataTable(<?php echo $jsonAgentAgeListChart;?>);
        var view5 = new google.visualization.DataView(data5);
        view5.setColumns([0, 1, {
            type: 'string',
            role: 'annotation',
            sourceColumn: 1,
            calc: 'stringify'
        }]);

        var options1 = {
					title: 'New Listings Total:   <?php echo $agentChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

        var options2 = {
          title: 'Buyers Total:   <?php echo $buyerChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   All Areas \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
          width: 600,
          height: 400,
          legend: { position: "none" },
          hAxis: {
            title: '',
            minValue: 0,
          },
          vAxis: {
            title: 'Year-to-Date Totals',
            minValue: 0,
          },
        };

        var options3 = {
          title: 'Buyer Saved Listings Total:   <?php echo $buyerAllListingsChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   All Areas \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
          width: 600,
          height: 400,
          legend: { position: "none" },
          hAxis: {
            title: 'Listings',
            minValue: 0,
          },
          vAxis: {
            title: 'Year-to-Date Totals',
            minValue: 0,
          },
        };

        var options4 = {
					title: 'Total Listings:   <?php echo $agentAllChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Number of Rooms',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

        var options5 = {
					title: 'Total Listings:   <?php echo $agentAgeChartTotal;?> \nAgent:   <?php echo $firstname;?> <?php echo $lastname;?> \nArea:   <?php echo $agentYearlyArea;?> \nPeriod:   <?php echo $chartStartDate;?> - <?php echo $chartEndDate;?>',
					width: 600,
					height: 400,
					legend: { position: "none" },
					hAxis: {
					  title: 'Listings by Age',
					},
					vAxis: {
					  title: 'Year-to-Date Totals',
					},
				};

			  //var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
			  //chart.draw(data1, options1);

       //var chart = new google.visualization.ColumnChart(document.getElementById('chart_div4'));
			// chart.draw(data2, options2);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div2'));
			 chart.draw(view2, options2);
			 
	   var chart = new google.visualization.ColumnChart(document.getElementById('chart_div4'));
			 chart.draw(view3, options3);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div6'));
			 chart.draw(view4, options4);

       var chart = new google.visualization.ColumnChart(document.getElementById('chart_div8'));
			 chart.draw(view5, options5);

			}
		/*	window.onerror = function(msg, url, linenumber) {
			alert('Error message: '+msg+'\nURL: '+url+'\nLine Number: '+linenumber);
			return true;
		}*/
			</script>
<?php }
}

?>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
