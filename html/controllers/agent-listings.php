<?php
session_start();
include('functions.php');
include('basicHead.php');
include("dbconfig.php");
$con = mysql_connect($dbhost, $dbuser, $dbpassword) or die(mysql_error());
$db = mysql_select_db($database, $con) or die(mysql_error());

if ($_SESSION['agent']){
  $user = $_SESSION['id'];
  $role = 'agent';
  $agent_email = $_SESSION['email'];
} else {
  print "<script> window.location = '/users/logout.php' </script>";
}

$_SESSION['viewingBuyer'] = 'false';

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }

$sql = "SELECT first_name, last_name, agent_id FROM `registered_agents` where (email = '".$agent_email."')";
$res = mysql_query( $sql ) or die("Couldn't execute query.".mysql_error());
$row = mysql_fetch_array($res,MYSQL_ASSOC);
$agent_id = $row['agent_id'];
?>

  <title>HomePik - Agent Folders</title>
  <?php include_css("/views/css/agent-listing-folders.css");
  include_css("/views/css/buyer-profile-edit.css");
  include_once('autoLogout.php'); ?>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
  <script src="/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
  <script src="/js/jquery/jquery.ui.ipad.alt.js" type="text/javascript"></script>
</head>
<body>
	<div id="agent-listings"></div>
	<div id="footer"></div>
	<div id="overlay"></div>
	<div id="ajax-box"></div>
	<div id="ajax-box2"></div>
<script type="text/babel">
  var EditSearch = React.createClass({
	  getInitialState: function() {
      return{
        buyer_email: this.props.buyer,
        searchName: this.props.searchName,
        name: "",
        bedrooms: "0",
        location: "1",
        building: "1",
        views: "1",
        bedroomArea: "1",
        livingArea: "1",
        minPrice: "100000",
        maxPrice: "2000000",
        minPriceStart: "1",
        maxPriceStart: "20",
        elevator: false,
        doorman: false,
        laundry: false,
        pets: false,
        fireplace: false,
        pool: false,
        garage: false,
        healthclub: false,
        outdoor: false,
        handicap: false,
        prewar: false,
        timeshare: false,
        newconstruction: false,
        n_all: false,
        n_fu: false,
        n_uws: false,
        n_ues: false,
        n_mw: false,
        n_me: false,
        n_v: false,
        n_d: false,
        coop: false,
        condo: false,
        house: false,
        condop: false
      };
    },
    componentDidMount: function(){
      var size_grades = ['','S','M','L','XL'];

      var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      this.getCriteria();

      $( "#bedrooms_slider" ).slider({
        range: 'max',
        min: 0,
        max: 8,
        value: this.state.bedrooms,
        slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider */
          var bedrooms = ui.value;
          this.setState({bedrooms: bedrooms});
          var minBedroom = bedrooms;
          ui.sliderText =	'<span>'+ui.value+'</span>';
          $('#bedrooms_slider .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          ui.value = this.state.bedrooms;
          $('#bedrooms_slider .ui-slider-handle').html('<span>'+ui.value+'</span>');
          return false;
        }.bind(this)
      }).addTouch();

      $("#location_grade").slider({
        animate: true,
        range: 'max',
        min: 1,
        max: 10,
        value: this.state.location, /* default grade is 1 */
        slide: function(event, ui) { /* Function to execute when sliding the location slider */
          var locationGrade = ui.value;
          this.setState({location: locationGrade});
          ui.sliderText =	"<span><img src='/images/blue-tick.png'/></span>";
          $('#location_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /*Function to execute when initializing slider */
          $('#location_grade .ui-slider-handle').html("<span><img src='/images/blue-tick.png'/></span>");
        }.bind(this)
      }).addTouch();

      $("#building_grade").slider({
        animate: true,
        range: 'max',
        min: 1,
        max: 10,
        value: this.state.building,
        slide: function(event, ui) { /* Function to execute when sliding the building slider */
          var buildingGrade = ui.value;
          this.setState({building: buildingGrade});
          ui.sliderText =	"<span><img src='/images/purple-tick.png'/></span>";
          $('#building_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          $('#building_grade .ui-slider-handle').html("<span><img src='/images/purple-tick.png'/></span>");
          return false;
        }.bind(this)
      }).addTouch();

      $("#views_grade").slider({
        animate: true,
        range: 'max',
        min: 1,
        max: 10,
        value: this.state.views,
        slide: function(event, ui) { /* Function to execute when sliding the views slider */
          var viewsGrade = ui.value;
          this.setState({views: viewsGrade})
          ui.sliderText = "<span><img src='/images/pink-tick.png'/></span>";
          $('#views_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          $('#views_grade .ui-slider-handle').html("<span><img src='/images/pink-tick.png'/></span>");
          return false;
        }.bind(this)
      }).addTouch();

      $("#bedroom_grade").slider({
        animate: true,
        range: 'max',
        min: 1,
        max: 4,
        value: this.state.bedroomArea,
        slide: function(event, ui) { /* Function to execute when sliding the bedroom slider      */
          var bedroomGrade = ui.value;
          this.setState({bedroomArea: bedroomGrade})
          if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
          else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
          $('#bedroom_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          ui.value = this.state.bedroomArea;
          $('#bedroom_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
          return false;
        }.bind(this)
      }).addTouch();

      $("#living_grade").slider({
        animate: true,
        range: 'max',
        min: 1,
        max: 4,
        value: this.state.livingArea,
        slide: function(event, ui) { /* Function to execute when sliding the living room slider          */
          var livingGrade = ui.value;
          this.setState({livingArea: livingGrade});
          if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
          else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
          $('#living_grade .ui-slider-handle').html(ui.sliderText);/* show the new grade in the slider handle */
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          ui.value = this.state.livingArea;
          $('#living_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
          return false;
        }.bind(this)
      }).addTouch();

      $( "#price" ).slider({
        animate: true,
        range: 'true',
        min: 1,
        max: 32,
        values: [this.state.minPriceStart, this.state.maxPriceStart ],
        slide: function( event, ui ) { /* Function to execute when sliding the price slider */
          if ( ( ui.values[ 0 ] + 0 ) >= ui.values[ 1 ] ) { return false; } //Make sure both arrows have at least a .1 gap
          var min_value = ui.values[0], max_value = ui.values[1];
          this.setState({minPrice: prices[min_value]});
          this.setState({maxPrice: prices[max_value]});
        }.bind(this),
        create: function( event, ui ) { /* Function to execute when initializing slider */
          $('#price .ui-slider-handle').first().html('<span>&#155;</span');
          $('#price .ui-slider-handle').first().attr("id", "min_price_slider");
          $('#price .ui-slider-handle').last().html('<span>&#139;</span');
          $('#price .ui-slider-handle').last().attr("id", "max_price_slider");
          return false;
        }
      }).addTouch();
    },
    getCriteria: function(){
      var size_grades = ['','S','M','L','XL'];

      var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      $.ajax({
        type: "POST",
        url: "get-search-criteria2.php",
        data: {"email": this.state.buyer_email, "name": this.props.searchName},
        success: function(data){
          var criteria = JSON.parse(data);
          
          if(criteria.name != null){
            this.setState({searchName: criteria.name});
            if(criteria.bedrooms != ""){ this.setState({bedrooms: criteria.bedrooms}); }
            if(criteria.location_grade != ""){ this.setState({location: criteria.location_grade}); }
            if(criteria.building_grade != ""){ this.setState({building: criteria.building_grade}); }
            if(criteria.view_grade != ""){ this.setState({views: criteria.view_grade}); }
            if(criteria.bedroom_area != ""){ this.setState({bedroomArea: criteria.bedroom_area}); }
            if(criteria.living_area != ""){ this.setState({livingArea: criteria.living_area}); }
            if(criteria.min_price != ""){ this.setState({minPrice: criteria.min_price}); }
            if(criteria.max_price != ""){ this.setState({maxPrice: criteria.max_price}); }
            if(criteria.min_price_start != ""){ this.setState({minPriceStart: criteria.min_price_start}); }
            if(criteria.max_price_start != ""){ this.setState({maxPriceStart: criteria.max_price_start}); }
            if(criteria.amenities.indexOf("Elevator") > -1 || criteria.amenities.indexOf("elevator") > -1){ this.setState({elevator: true}); }else{ this.setState({elevator: false}); }
            if(criteria.amenities.indexOf("Doorman") > -1 || criteria.amenities.indexOf("doorman") > -1){ this.setState({doorman: true}); }else{ this.setState({doorman: false}); }
            if(criteria.amenities.indexOf("Laundry") > -1 || criteria.amenities.indexOf("laundry") > -1){ this.setState({laundry: true}); }else{ this.setState({laundry: false}); }
            if(criteria.amenities.indexOf("Pets") > -1 || criteria.amenities.indexOf("pets") > -1){ this.setState({pets: true}); }else{ this.setState({pets: false}); }
            if(criteria.amenities.indexOf("Fireplace") > -1 || criteria.amenities.indexOf("fireplace") > -1){ this.setState({fireplace: true}); }else{ this.setState({fireplace: false}); }
            if(criteria.amenities.indexOf("Pool") > -1 || criteria.amenities.indexOf("pool") > -1){ this.setState({pool: true}); }else{ this.setState({pool: false}); }
            if(criteria.amenities.indexOf("Garage") > -1 || criteria.amenities.indexOf("garage") > -1){ this.setState({garage: true}); }else{ this.setState({garage: false}); }
            if(criteria.amenities.indexOf("Healthclub") > -1 || criteria.amenities.indexOf("healthclub") > -1){ this.setState({healthclub: true}); }else{ this.setState({healthclub: false}); }
            if(criteria.amenities.indexOf("Outdoor") > -1 || criteria.amenities.indexOf("outdoor") > -1){ this.setState({outdoor: true}); }else{ this.setState({outdoor: false}); }
            if(criteria.amenities.indexOf("Wheelchair") > -1 || criteria.amenities.indexOf("wheelchair") > -1){ this.setState({handicap: true}); }else{ this.setState({handicap: false}); }
            if(criteria.amenities.indexOf("Prewar") > -1 || criteria.amenities.indexOf("prewar") > -1){ this.setState({prewar: true}); }else{ this.setState({prewar: false}); }
            if(criteria.amenities.indexOf("Timeshare") > -1 || criteria.amenities.indexOf("timeshare") > -1){ this.setState({timeshare: true}); }else{ this.setState({timeshare: false}); }
            if(criteria.amenities.indexOf("Newconstruction") > -1 || criteria.amenities.indexOf("newconstruction") > -1){ this.setState({newconstruction: true}); }else{ this.setState({newconstruction: false}); }
            if(criteria.neighborhoods.length == 7){ this.setState({n_all: true}); }else{ this.setState({n_all: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("North") > -1){ this.setState({n_fu: true}); }else{ this.setState({n_fu: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Westside") > -1){ this.setState({n_uws: true}); }else{ this.setState({n_uws: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Eastside") > -1){ this.setState({n_ues: true}); }else{ this.setState({n_ues: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Chelsea") > -1){ this.setState({n_mw: true}); }else{ this.setState({n_mw: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("SMG") > -1){ this.setState({n_me: true}); }else{ this.setState({n_me: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Village") > -1){ this.setState({n_v: true}); }else{ this.setState({n_v: false}); }
            if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Lower") > -1){ this.setState({n_d: true}); }else{ this.setState({n_d: false}); }
            if(criteria.prop_type.indexOf("Coop") > -1 || criteria.prop_type.indexOf("coop") > -1){ this.setState({coop: true}); }else{ this.setState({coop: false}); }
            if(criteria.prop_type.indexOf("Condo") > -1 || criteria.prop_type.indexOf("condo") > -1){ this.setState({condo: true}); }else{ this.setState({condo: false}); }
            if(criteria.prop_type.indexOf("House/Townhouse") > -1 || criteria.prop_type.indexOf("house/townhouse") > -1){ this.setState({house: true}); }else{ this.setState({house: false}); }
            if(criteria.prop_type.indexOf("Condop") > -1 || criteria.prop_type.indexOf("condop") > -1){ this.setState({condop: true}); }else{ this.setState({condop: false}); }
  
            $("#price").slider('values',0,criteria.min_price_start);
            $("#price").slider('values',1,criteria.max_price_start);
            $("#price").slider('refresh');
            $("#bedrooms_slider").find("span").html(criteria.bedrooms);
            $("#bedrooms_slider").slider('value',criteria.bedrooms);
            $("#bedrooms_slider" ).slider('refresh');
            $("#location_grade").slider('value',criteria.location_grade);
            $("#location_grade").slider('refresh');
            $("#building_grade").slider('value',criteria.building_grade);
            $("#building_grade").slider('refresh');
            $("#views_grade").slider('value',criteria.view_grade);
            $("#views_grade").slider('refresh');
            if(size_grades[criteria.bedroom_area] !== 'XL'){ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]); }else{ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]).addClass("smaller");}
            $("#bedroom_grade").slider('value',criteria.bedroom_area);
            $("#bedroom_grade").slider('refresh');
            if(size_grades[criteria.living_area] !== 'XL'){ $("#living_grade").find("span").html(size_grades[criteria.living_area]); }else{ $("#living_grade").find("span").html(size_grades[criteria.living_area]).addClass("smaller");}
            $("#living_grade").slider('value',criteria.living_area);
            $("#living_grade").slider('refresh');
          }
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleMinPriceChange: function(event){
      var price = event.target.value;
      if(price.length > 0){
        price = price.replace(",", "");
        price = parseInt(price);
      }
      this.setState({minPrice: price});
    },
    handleMaxPriceChange: function(event){
      var price = event.target.value;
      if(price.length > 0){
        price = price.replace(",", "");
        price = parseInt(price);
      }
      this.setState({maxPrice: price});
    },
    checkMinPrice: function(event){
      setTimeout(function(){
        var price = this.state.minPrice;
        var index = -1;
        var shift = 0;
        var prices = [100000, 200000, 300000, 400000, 500000, 600000,
        700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
        1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
        2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
        50000000, 99000000];

        for(var i = 0; i < prices.length; i++){
          if(prices[i] == price){
            index = i;
            break;
          }
          else{
            if(prices[i] < price){ shift = i; }
          }
        }

        if(index < 0){
          $("#ajax-box2").dialog({
            modal: true,
            height: 'auto',
            width: '275px',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage',
            buttons: {
              Ok: function(){
                $(this).dialog("destroy");
              }
            },
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box2").dialog('close');
              });
            }
          });
          $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
            $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
          });

          if(price < 100000){
            this.setState({minPrice: 100000});
            $("#price").slider('values',0,1);
          }
          else{
            this.setState({minPrice: prices[shift]});
            shift++;
            $("#price").slider('values',0,shift);
          }
        }
        else{
          index++;
          $("#price").slider('values',0,index);
        }
      }.bind(this), 3000);
    },
    checkMaxPrice: function(event){
      setTimeout(function(){
        var price = this.state.maxPrice;
        var index = -1;
        var shift = 0;
        var prices = [100000, 200000, 300000, 400000, 500000, 600000,
        700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
        1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
        2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
        50000000, 99000000];

        for(var i = 0; i < prices.length; i++){
          if(prices[i] == price){
            index = i;
            break;
          }
          else{
            if(prices[i] < price){ shift = i; }
          }
        }

        if(index < 0){
          $("#ajax-box2").dialog({
            modal: true,
            height: 'auto',
            width: '275px',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage',
            buttons: {
              Ok: function(){
                $(this).dialog("destroy");
              }
            },
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box2").dialog('close');
              });
            }
          });
          $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
            $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
          });

          if(price > 99000000){
            this.setState({maxPrice: 99000000});
            $("#price").slider('values',1,32);
          }
          else{
            this.setState({maxPrice: prices[shift]});
            shift++;
            $("#price").slider('values',1,shift);
          }
        }
        else{
          index++;
          $("#price").slider('values',1,index);
        }
      }.bind(this), 3000);
    },
    handleAmenityChange: function(name, event){
      if(name == "elevator"){ this.setState({elevator: !this.state.elevator}); }
      else if(name == "doorman"){ this.setState({doorman: !this.state.doorman}); }
      else if(name == "laundry"){ this.setState({laundry: !this.state.laundry}); }
      else if(name == "pets"){ this.setState({pets: !this.state.pets}); }
      else if(name == "fireplace"){ this.setState({fireplace: !this.state.fireplace}); }
      else if(name == "pool"){ this.setState({pool: !this.state.pool}); }
      else if(name == "garage"){ this.setState({garage: !this.state.garage}); }
      else if(name == "healthclub"){ this.setState({healthclub: !this.state.healthclub}); }
      else if(name == "outdoor"){ this.setState({outdoor: !this.state.outdoor}); }
      else if(name == "handicap"){ this.setState({handicap: !this.state.handicap}); }
      else if(name == "prewar"){ this.setState({prewar: !this.state.prewar}); }
      else if(name == "timeshare"){ this.setState({timeshare: !this.state.timeshare}); }
      else if(name == "newconstruction"){ this.setState({newconstruction: !this.state.newconstruction}); }
      else{ /* Do nothing */ }
    },
    handleNeighborhoodChange: function(name, event){
      if(name == "n_all"){
        this.setState({n_all: !this.state.n_all});
        this.setState({n_fu: false});
        this.setState({n_uws: false});
        this.setState({n_ues: false});
        this.setState({n_mw: false});
        this.setState({n_me: false});
        this.setState({n_v: false});
        this.setState({n_d: false});
      }
      else if(name == "n_fu"){
        this.setState({n_fu: !this.state.n_fu});
        this.setState({n_all: false});
      }
      else if(name == "n_uws"){
        this.setState({n_uws: !this.state.n_uws});
        this.setState({n_all: false});
      }
      else if(name == "n_ues"){
        this.setState({n_ues: !this.state.n_ues});
        this.setState({n_all: false});
      }
      else if(name == "n_mw"){
        this.setState({n_mw: !this.state.n_mw});
        this.setState({n_all: false});
      }
      else if(name == "n_me"){
        this.setState({n_me: !this.state.n_me});
        this.setState({n_all: false});
      }
      else if(name == "n_v"){
        this.setState({n_v: !this.state.n_v});
        this.setState({n_all: false});
      }
      else if(name == "n_d"){
        this.setState({n_d: !this.state.n_d});
        this.setState({n_all: false});
      }
      else{ /* Do nothing */ }
    },
    handlePropertyChange: function(name, event){
      if(name == "coop"){ this.setState({coop: !this.state.coop}); }
      else if(name == "condo"){ this.setState({condo: !this.state.condo}); }
      else if(name == "house"){ this.setState({house: !this.state.house}); }
      else if(name == "condop"){ this.setState({condop: !this.state.condop}); }
      else{ /* Do nothing */ }
    },
    bedroomText: function(){
      if(this.state.bedrooms == 0){ return(<span>Studio</span>); }
      else if(this.state.bedrooms == 1){ return(<span>{this.state.bedrooms} bedroom</span>); }
      else{ return(<span>{this.state.bedrooms} bedrooms</span>); }
    },
    minPriceText: function(){
      if(this.state.minPrice == 100000){ return(<span>100K</span>); }
      else if(this.state.minPrice == 200000){ return(<span>200K</span>); }
      else if(this.state.minPrice == 300000){ return(<span>300K</span>); }
      else if(this.state.minPrice == 400000){ return(<span>400K</span>); }
      else if(this.state.minPrice == 500000){ return(<span>500K</span>); }
      else if(this.state.minPrice == 600000){ return(<span>600K</span>); }
      else if(this.state.minPrice == 700000){ return(<span>700K</span>); }
      else if(this.state.minPrice == 800000){ return(<span>800K</span>); }
      else if(this.state.minPrice == 900000){ return(<span>900K</span>); }
      else if(this.state.minPrice == 1000000){ return(<span>1M</span>); }
      else if(this.state.minPrice == 1100000){ return(<span>1.1M</span>); }
      else if(this.state.minPrice == 1200000){ return(<span>1.2M</span>); }
      else if(this.state.minPrice == 1300000){ return(<span>1.3M</span>); }
      else if(this.state.minPrice == 1400000){ return(<span>1.4M</span>); }
      else if(this.state.minPrice == 1500000){ return(<span>1.5M</span>); }
      else if(this.state.minPrice == 1600000){ return(<span>1.6M</span>); }
      else if(this.state.minPrice == 1700000){ return(<span>1.7M</span>); }
      else if(this.state.minPrice == 1800000){ return(<span>1.8M</span>); }
      else if(this.state.minPrice == 1900000){ return(<span>1.9M</span>); }
      else if(this.state.minPrice == 2000000){ return(<span>2M</span>); }
      else if(this.state.minPrice == 2250000){ return(<span>2.25M</span>); }
      else if(this.state.minPrice == 2500000){ return(<span>2.5M</span>); }
      else if(this.state.minPrice == 2750000){ return(<span>2.75M</span>); }
      else if(this.state.minPrice == 3000000){ return(<span>3M</span>); }
      else if(this.state.minPrice == 3500000){ return(<span>3.5M</span>); }
      else if(this.state.minPrice == 4000000){ return(<span>4M</span>); }
      else if(this.state.minPrice == 6000000){ return(<span>6M</span>); }
      else if(this.state.minPrice == 8000000){ return(<span>8M</span>); }
      else if(this.state.minPrice == 12000000){ return(<span>12M</span>); }
      else if(this.state.minPrice == 25000000){ return(<span>25M</span>); }
      else if(this.state.minPrice == 50000000){ return(<span>50M</span>); }
      else if(this.state.minPrice == 99000000){ return(<span>99M</span>); }
      else{ /* Do nothing */ }
    },
    maxPriceText: function(){
      if(this.state.maxPrice == 100000){ return(<span>100K</span>); }
      else if(this.state.maxPrice == 200000){ return(<span>200K</span>); }
      else if(this.state.maxPrice == 300000){ return(<span>300K</span>); }
      else if(this.state.maxPrice == 400000){ return(<span>400K</span>); }
      else if(this.state.maxPrice == 500000){ return(<span>500K</span>); }
      else if(this.state.maxPrice == 600000){ return(<span>600K</span>); }
      else if(this.state.maxPrice == 700000){ return(<span>700K</span>); }
      else if(this.state.maxPrice == 800000){ return(<span>800K</span>); }
      else if(this.state.maxPrice == 900000){ return(<span>900K</span>); }
      else if(this.state.maxPrice == 1000000){ return(<span>1M</span>); }
      else if(this.state.maxPrice == 1100000){ return(<span>1.1M</span>); }
      else if(this.state.maxPrice == 1200000){ return(<span>1.2M</span>); }
      else if(this.state.maxPrice == 1300000){ return(<span>1.3M</span>); }
      else if(this.state.maxPrice == 1400000){ return(<span>1.4M</span>); }
      else if(this.state.maxPrice == 1500000){ return(<span>1.5M</span>); }
      else if(this.state.maxPrice == 1600000){ return(<span>1.6M</span>); }
      else if(this.state.maxPrice == 1700000){ return(<span>1.7M</span>); }
      else if(this.state.maxPrice == 1800000){ return(<span>1.8M</span>); }
      else if(this.state.maxPrice == 1900000){ return(<span>1.9M</span>); }
      else if(this.state.maxPrice == 2000000){ return(<span>2M</span>); }
      else if(this.state.maxPrice == 2250000){ return(<span>2.25M</span>); }
      else if(this.state.maxPrice == 2500000){ return(<span>2.5M</span>); }
      else if(this.state.maxPrice == 2750000){ return(<span>2.75M</span>); }
      else if(this.state.maxPrice == 3000000){ return(<span>3M</span>); }
      else if(this.state.maxPrice == 3500000){ return(<span>3.5M</span>); }
      else if(this.state.maxPrice == 4000000){ return(<span>4M</span>); }
      else if(this.state.maxPrice == 6000000){ return(<span>6M</span>); }
      else if(this.state.maxPrice == 8000000){ return(<span>8M</span>); }
      else if(this.state.maxPrice == 12000000){ return(<span>12M</span>); }
      else if(this.state.maxPrice == 25000000){ return(<span>25M</span>); }
      else if(this.state.maxPrice == 50000000){ return(<span>50M</span>); }
      else if(this.state.maxPrice == 99000000){ return(<span>99M</span>); }
      else{ /* Do nothing */ }
    },
    locationText: function(){
      if(this.state.location == 1){ return(<div><p id="y25213-6">All locations</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 2){ return(<div><p id="y25213-6">New developing market.</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 3){ return(<div><p id="y25213-6">Emerging residential area</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 4){ return(<div><p id="y25213-6">Active commercial street</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 5){ return(<div><p id="y25213-6">Active commercial street / Residential Area</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 6){ return(<div><p id="y25213-6">Quiet residential street</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 7){ return(<div><p id="y25213-6">Residential area / neighborhood amenities</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 8){ return(<div><p id="y25213-6">Residential area / near local park or river</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 9){ return(<div><p id="y25213-6">Residential area cose to major park</p><p id="y25213-7">&nbsp;</p></div>); }
      else if(this.state.location == 10){ return(<div><p id="y25213-6">Internationally renown / near major park</p><p id="y25213-7">&nbsp;</p></div>); }
      else{ /* Do nothing */ }
    },
    buildingText: function(){
      if(this.state.building == 1){ return(<div><p id="y25213-14">All buildings</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 2){ return(<div><p id="y25213-14">Walkup in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 3){ return(<div><p id="y25213-14">Walkup in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 4){ return(<div><p id="y25213-14">Elevator building in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 5){ return(<div><p id="y25213-14">Elevator building in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 6){ return(<div><p id="y25213-14">Doorman building &mdash;no amenities</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 7){ return(<div><p id="y25213-14">Doorman building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 8){ return(<div><p id="y25213-14">Full service building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
      else if(this.state.building == 9){ return(<div><p id="y25213-14">Locally renowned building or new construction with full services</p></div>); }
      else if(this.state.building == 10){ return(<div><p id="y25213-14">International renown</p><p id="y25213-15">&nbsp;</p></div>); }
      else{ /* Do nothing */ }
    },
    viewText: function(){
      if(this.state.views == 1){ return(<div><p id="y25213-22">All properties</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 2){ return(<div><p id="y25213-22">Indirect light</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 3){ return(<div><p id="y25213-22">Interior courtyard or area with moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 4){ return(<div><p id="y25213-22">Interior courtyard or area without view but bright</p></div>); }
      else if(this.state.views == 5){ return(<div><p id="y25213-22">Street view or interior garden, moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 6){ return(<div><p id="y25213-22">Street view or interior garden, bright</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 7){ return(<div><p id="y25213-22">Rooftop views</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 8){ return(<div><p id="y25213-22">Cityscape views</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 9){ return(<div><p id="y25213-22">Cityscape and river or park views</p><p id="y25213-23">&nbsp;</p></div>); }
      else if(this.state.views == 10){ return(<div><p id="y25213-22">Cityscape and Central Park views</p><p id="y25213-23">&nbsp;</p></div>); }
      else{ /* Do nothing */ }
    },
    bedroomAreaText: function(){
      if(this.state.bedroomArea == 1){ return(<div><p id="y25213-30">Any bedroom size is okay</p><p id="y25213-31">&nbsp;</p></div>); }
      else if(this.state.bedroomArea == 2){ return(<div><p id="y25213-30">A medium master bedroom or larger: at least 13 ft by 11 ft</p></div>); }
      else if(this.state.bedroomArea == 3){ return(<div><p id="y25213-30">A large master bedroom or larger: at least 16 ft by 11 ft</p></div>); }
      else if(this.state.bedroomArea == 4){ return(<div><p id="y25213-30">An extra-large master bedroom: at least 19 ft by 11 ft</p></div>); }
      else{ /* Do nothing */ }
    },
    livingAreaText: function(){
      if(this.state.livingArea == 1){ return(<div><p id="y25213-38">Any living room size is okay</p><p id="y25213-31">&nbsp;</p></div>); }
      else if(this.state.livingArea == 2){ return(<div><p id="y25213-38">A medium-sized living room or larger: at least 18 ft by 12 ft</p></div>); }
      else if(this.state.livingArea == 3){ return(<div><p id="y25213-38">A large-sized living room or larger: at least 22 ft by 12 ft</p></div>); }
      else if(this.state.livingArea == 4){ return(<div><p id="y25213-38">A extra-large living room or larger: at least 27 ft by 12 ft</p></div>); }
      else{ /* Do nothing */ }
    },
    minPriceInput: function(){
      var val = this.state.minPrice;
      if(val.toString().length % 3 == 0){
        while (/(\d+)(\d{3})/.test(val.toString())){
          val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
      }
      return val;
    },
    maxPriceInput: function(){
      var val = this.state.maxPrice;
      if(val.toString().length % 3 == 0){
        while (/(\d+)(\d{3})/.test(val.toString())){
          val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
        }
      }
      return val;
    },
    getPrices: function(){
      var prices = [{label:"100,000", value:"100000"},{label:"200,000", value:"200000"}, {label:"300,000", value:"300000"}, {label:"400,000", value:"400000"},
        {label:"500,000", value:"500000"}, {label:"600,000", value:"600000"}, {label:"700,000", value:"700000"}, {label:"800,000", value:"800000"},
        {label:"900,000", value:"900000"}, {label:"1,000,000", value:"1000000"}, {label:"1,100,000", value:"1100000"}, {label:"1,200,000", value:"1200000"},
        {label:"1,300,000", value:"1300000"}, {label:"1,400,000", value:"1400000"},{label:"1,500,000", value:"1500000"}, {label:"1,600,000", value:"1600000"},
        {label:"1,700,000", value:"1700000"}, {label:"1,800,000", value:"1800000"}, {label:"1,900,000", value:"1900000"}, {label:"2,000,000", value:"2000000"},
        {label:"2,250,000", value:"2250000"}, {label:"2,500,000", value:"2500000"}, {label:"2,750,000", value:"2750000"}, {label:"3,000,000", value:"3000000"},
        {label:"4,000,000", value:"4000000"}, {label:"6,000,000", value:"6000000"}, {label:"8,000,000", value:"8000000"}, {label:"12,000,000", value:"12000000"},
        {label:"25,000,000", value:"25000000"}, {label:"50,000,000", value:"50000000"}, {label:"99,000,000", value:"99000000"}];

      var pricesCompare = ["100000","200000", "300000", "400000", "500000", "600000", "700000", "800000", "900000", "1000000", "1100000", "1200000",
        "1300000", "1400000", "1500000", "1600000", "1700000", "1800000", "1900000", "2000000", "2250000", "2500000", "2750000", "3000000", "3500000",
        "4000000", "6000000", "8000000", "12000000", "25000000", "50000000", "99000000"];

      $( ".priceInput" ).autocomplete({
        source: prices,
        select: function(e, ui){
          if(e.target.name == "minPrice"){
            this.setState({minPrice: ui.item.value});
            var index = pricesCompare.indexOf(ui.item.value);
            index++;
            $("#price").slider('values',0,index);
          }
          if(e.target.name == "maxPrice"){
            this.setState({maxPrice: ui.item.value});
            var index = pricesCompare.indexOf(ui.item.value);
            index++;
            $("#price").slider('values',1,index);
          }
        }.bind(this)
      });
    },
    closeDialog: function(){
      $dialog.dialog('close');
    },
    saveChanges: function(){
      var email = this.state.buyer_email;
      var searchName = this.state.searchName;
      var oldName = this.state.searchName;
      var min_price = this.state.minPrice;
      var max_price = this.state.maxPrice;
      var bedrooms = this.state.bedrooms;
      var location_grade = this.state.location;
      var building_grade = this.state.building;
      var views_grade = this.state.views;
      var bedroom_area = this.state.bedroomArea;
      var living_area = this.state.livingArea;
      var min_floor = "";
      var neighborhoods = [];
      var prop_type = [];
      var amen = [];

      if(this.state.n_all == true){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"];}
      if(this.state.n_fu == true){ neighborhoods.push("North");}
      if(this.state.n_uws == true){ neighborhoods.push("Westside");}
      if(this.state.n_ues == true){ neighborhoods.push("Eastside");}
      if(this.state.n_mw == true){ neighborhoods.push("Chelsea");}
      if(this.state.n_me == true){ neighborhoods.push("SMG");}
      if(this.state.n_v == true){ neighborhoods.push("Village");}
      if(this.state.n_d == true){ neighborhoods.push("Lower");}

      if(neighborhoods.length == 0){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"]; }

      if(this.state.coop == true){ prop_type.push("1");}
      if(this.state.condo == true){ prop_type.push("2");}
      if(this.state.house == true){ prop_type.push("4");}
      if(this.state.condop == true){ prop_type.push("5");}

      if(prop_type.length == 0){ prop_type = ["1", "2", "4", "5"]; }

      if(this.state.elevator == true){ amen.push("Elevator");}
      if(this.state.doorman == true){ amen.push("Doorman");}
      if(this.state.laundry == true){ amen.push("Laundry");}
      if(this.state.pets == true){ amen.push("Pets");}
      if(this.state.fireplace == true){ amen.push("Fireplace");}
      if(this.state.pool == true){ amen.push("Pool");}
      if(this.state.garage == true){ amen.push("Garage");}
      if(this.state.healthclub == true){ amen.push("Healthclub");}
      if(this.state.outdoor == true){ amen.push("Outdoor");}
      if(this.state.handicap == true){ amen.push("Wheelchair");}
      if(this.state.prewar == true){ amen.push("Prewar");}
      if(this.state.timeshare == true){ amen.push("Timeshare");}
      if(this.state.newconstruction == true){ amen.push("Newconstruction");}

      if(Number(min_price) >= Number(max_price)){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox priceRangePopup',
          buttons: {
            Ok: function(){
              $(this).dialog("destroy");
            }
          },
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('/controllers/messages.php #priceRange',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });
      }
      else{
        $.ajax({
          type: "POST",
          url: "save-criteria.php",
          data: {"email":email, "oldname":oldName, "name":searchName, "location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : neighborhoods, "prop_type":prop_type, "amenities":amen},
          success: function(data){
            $.get("/controllers/ajax.php", {
              editFormulaEmail: 'true', //Call the PHP function
              email: email,
              success: function(result){
                console.log("email sent");
              }
            });

            var ajaxStop = 0;
            $(document).ajaxStop(function() {
              if(ajaxStop == 0){
                ajaxStop++;
                $(".ui-widget-overlay").hide();
                {this.props.closeDialog()}
              }
            }.bind(this));
          }.bind(this),
          error: function() {
            console.log("failed");
          }
        });
      }
    },
    cancel: function(){
      $(".ui-widget-overlay").hide();
      {this.props.closeDialog()}
    },
    updateData: function(){
      if(this.props.searchName != this.state.name){
        this.setState({name: this.props.searchName});
        this.getCriteria();
      }
    },
    render: function(){
      return(
        <div className="clearfix" id="editPage">
          <i className="fa fa-times close-edit-buyer-formula-popup" onClick={this.cancel} title="close"></i>
          {this.updateData()}
          <div className="clearfix grpelem" id="y25246-3">
            <p>&nbsp;</p>
          </div>
          <div className="clearfix grpelem" id="y25241-3">
            <p>&nbsp;</p>
          </div>
          <div className="clearfix grpelem" id="py2678">
            <div className="clearfix grpelem" id="py25217-6">
              <div className="clearfix colelem" id="y25217-6">
                <p id="y25217-4"><span id="y25217">Search name: </span><span id="y25217-2">{this.props.searchName}&nbsp; </span><span id="y25217-3"><a style={{cursor: "pointer"}} onClick = {this.cancel}>cancel</a></span></p>
              </div>
              <div className="clearfix colelem" id="py25231-53">
                <div className="clearfix grpelem" id="y25231-53">
                  <p id="y25231-2">Minimum Bedroom</p>
                  <p id="y25231-3">&nbsp;</p>
                  <p id="y25231-4">&nbsp;</p>
                  <p id="y25231-6">{this.bedroomText()}</p>
                  <p id="y25231-7">&nbsp;</p>
                  <p id="y25231-8">&nbsp;</p>
                  <p id="y25231-10">Price Range</p>
                  <p id="y25231-11">&nbsp;</p>
                  <p id="y25231-12">&nbsp;</p>
                  <p id="y25231-14">$ {this.minPriceText()} to {this.maxPriceText()}</p>
                  <p id="y25231-16">Or, fill in a range: $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to $&nbsp;&nbsp;</p>
                  <p id="y25231-17">&nbsp;</p>
                  <p id="y25231-18">&nbsp;</p>
                  <p id="y25231-20">Neighborhoods</p>
                  <p id="y25231-23"><span id="y25231-21">{this.state.n_all ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}} onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> }</span><span id="y25231-22">&nbsp;&nbsp; All of Manhattan</span></p>
                  <p id="y25231-26"><span id="y25231-24">{this.state.n_fu ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> }</span><span id="y25231-25">&nbsp;&nbsp; Far Uptown</span></p>
                  <p id="y25231-29"><span id="y25231-27">{this.state.n_uws ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> }</span><span id="y25231-28">&nbsp;&nbsp; Upper West Side</span></p>
                  <p id="y25231-32"><span id="y25231-30">{this.state.n_ues ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> }</span><span id="y25231-31">&nbsp;&nbsp; Upper East Side</span></p>
                  <p id="y25231-36">&nbsp;</p>
                  <p id="y25231-37">&nbsp;</p>
                  <p id="y25231-39">Property Type</p>
                  <p id="y25231-42"><span id="y25231-40">{this.state.coop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> }</span><span id="y25231-41">&nbsp;&nbsp; Co-op</span></p>
                  <p id="y25231-45"><span id="y25231-43">{this.state.condo ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> }</span><span id="y25231-44">&nbsp;&nbsp; Condo</span></p>
                  <p id="y25231-48"><span id="y25231-46">{this.state.house ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> }</span><span id="y25231-47">&nbsp;&nbsp; House/Townhouse</span></p>
                  <p id="y25231-51"><span id="y25231-49">{this.state.condop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> }</span><span id="y25231-50">&nbsp;&nbsp; Condop</span></p>
                </div>
                <div className="clearfix grpelem" id="y25224-14">
                  <p id="y25224-3"><span id="y25224">{this.state.n_mw ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> }</span><span id="y25224-2">&nbsp;&nbsp; Midtown West</span></p>
                  <p id="y25224-6"><span id="y25224-4">{this.state.n_me ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> }</span><span id="y25224-5">&nbsp;&nbsp; Midtown East</span></p>
                  <p id="y25224-9"><span id="y25224-7">{this.state.n_v ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> }</span><span id="y25224-8">&nbsp;&nbsp; East/West Village</span></p>
                  <p id="y25224-12"><span id="y25224-10">{this.state.n_d ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> }</span><span id="y25224-11">&nbsp;&nbsp; Downtown</span></p>
                </div>
                <div id="bedrooms_slider"></div>
                <div id="bedroomSliderText"><span className="leftNum">0</span><span className="rightNum">8</span></div>
                <div className="clip_frame grpelem" id="y25220">
                  <img className="block" id="y25220_img" src="/images/slider_purple_short.png" alt="" width="455" height="37" />
                </div>
                <div id="price"></div>
                <div id="priceSliderText"><span className="leftNum">0</span><span className="rightNum">99</span></div>
                <div className="clip_frame grpelem" id="y25211">
                  <img className="block" id="y25211_img" src="/images/slider_blue_short.png" alt="" width="455" height="37"/>
                </div>
                <div className="clearfix grpelem" id="y25385">
                  <div className="grpelem" id="y25398"><input type="text" id="minPrice" className="priceInput" name="minPrice" value={this.state.minPrice} onBlur={this.checkMinPrice} onChange={this.handleMinPriceChange} onFocus={this.getPrices}/></div>
                  <div className="grpelem" id="y25386"><input type="text" id="maxPrice" className="priceInput" name="maxPrice" value={this.state.maxPrice} onBlur={this.checkMaxPrice} onChange={this.handleMaxPriceChange} onFocus={this.getPrices}/></div>
                </div>
              </div>
            </div>
            <div className="grpelem" id="y25216"></div>
            <div className="clearfix grpelem" id="py25213-56">
              <div className="clearfix grpelem" id="y25213-56">
                <p id="y25213-2">Minimum Location Grade</p>
                <p id="y25213-3">&nbsp;</p>
                <p id="y25213-4">&nbsp;</p>
                {this.locationText()}
                <p id="y25213-8">&nbsp;</p>
                <p id="y25213-10">Minimum Building Grade</p>
                <p id="y25213-11">&nbsp;</p>
                <p id="y25213-12">&nbsp;</p>
                {this.buildingText()}
                <p id="y25213-16">&nbsp;</p>
                <p id="y25213-18">Minimum view Grade</p>
                <p id="y25213-19">&nbsp;</p>
                <p id="y25213-20">&nbsp;</p>
                {this.viewText()}
                <p id="y25213-24">&nbsp;</p>
                <p id="y25213-26">Master Bedroom</p>
                <p id="y25213-27">&nbsp;</p>
                <p id="y25213-28">&nbsp;</p>
                {this.bedroomAreaText()}
                <p id="y25213-32">&nbsp;</p>
                <p id="y25213-34">Living Room</p>
                <p id="y25213-35">&nbsp;</p>
                <p id="y25213-36">&nbsp;</p>
                {this.livingAreaText()}
                <p id="y25213-40">&nbsp;</p>
                <p id="y25213-44"><span id="y25213-41">Amenities</span><span id="y25213-42">&nbsp; </span><span id="y25213-43">Click icons to select amenities</span></p>
                <p id="y25213-45">&nbsp;</p>
                <p id="y25213-46">&nbsp;</p>
                <p id="y25213-47">&nbsp;</p>
                <p id="y25213-48">&nbsp;</p>
                <p id="y25213-49">&nbsp;</p>
                <p id="y25213-50">&nbsp;</p>
                <p id="y25213-51">&nbsp;</p>
                <p id="y25213-54"><span id="y25213-52" onClick={this.saveChanges} style={{cursor: "pointer"}}>Save changes and exit </span><span id="y25213-53"><i className="fa fa-chevron-right"></i></span></p>
              </div>
              <div id="location_grade"></div>
              <div className="clip_frame grpelem" id="y25229">
                <img className="block" id="y25229_img" src="/images/locationslider2.png" alt="" width="455" height="37"/>
              </div>
              <div id="building_grade"></div>
              <div className="clip_frame grpelem" id="y25214">
                <img className="block" id="y25214_img" src="/images/buildingslider2.png" alt="" width="455" height="37"/>
              </div>
              <div id="views_grade"></div>
              <div className="clip_frame grpelem" id="y25227">
                <img className="block" id="y25227_img" src="/images/viewsslider2.png" alt="" width="455" height="37"/>
                </div>
              <div id="bedroom_grade"></div>
              <div id="bedroomAreaSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
              <div className="clip_frame grpelem" id="y25222">
                <img className="block" id="y25222_img" src="/images/slider_orange_short.png" alt="" width="455" height="37"/>
              </div>
              <div id="living_grade"></div>
              <div id="livingRoomSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
              <div className="clip_frame grpelem" id="y25225">
                <img className="block" id="y25225_img" src="/images/slider_green_short.png" alt="" width="455" height="37"/>
              </div>
              <div className="clearfix grpelem" id="y25374">
                <div className="clip_frame grpelem" id="y25208">
                  <table id="amenities">
                    <tbody>
                      <tr>
                        <td onClick={this.handleAmenityChange.bind(this, 'elevator')}>{this.state.elevator ? <img className="amenity" src="/images/amenities/elevatorb.png" alt="elevator" width="30" height="30"/> : <img className="amenity" src="/images/amenities/elevator.png" alt="elevator" width="30" height="30" />}<div className="y25210-11">Elevator</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'doorman')}>{this.state.doorman ? <img className="amenity" src="/images/amenities/doormanb.png" alt="doorman" width="30" height="30"/> : <img className="amenity" src="/images/amenities/doorman.png" alt="doorman" width="30" height="30" />}<div className="y25210-11">Doorman</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'laundry')}>{this.state.laundry ? <img className="amenity" src="/images/amenities/laundryb.png" alt="laundry" width="30" height="30"/> : <img className="amenity" src="/images/amenities/laundry.png" alt="laundry" width="30" height="30" />}<div className="y25210-11">Laundry</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'pets')}>{this.state.pets ? <img className="amenity" src="/images/amenities/petsb.png" alt="pets" width="30" height="30"/> : <img className="amenity" src="/images/amenities/pets.png" alt="pets" width="30" height="30"/>}<div className="y25210-11">Pets</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'fireplace')}>{this.state.fireplace ? <img className="amenity" src="/images/amenities/fireplaceb.png" alt="fireplace" width="30" height="30"/> : <img className="amenity" src="/images/amenities/fireplace.png" alt="fireplace" width="30" height="30"/>}<div className="y25210-11">Fireplace</div></td>
                        <td></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'prewar')}>{this.state.prewar ? <img className="amenity" src="/images/amenities/prewar2b.png" alt="prewar" width="30" height="30" /> : <img className="amenity" src="/images/amenities/prewar2.png" alt="prewar" width="30" height="30" />}<div className="y25210-11">Prewar</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'timeshare')}>{this.state.timeshare ? <img className="amenity" src="/images/amenities/timeshareb.png" alt="timeshare" width="30" height="30"/> : <img className="amenity" src="/images/amenities/timeshare.png" alt="timeshare" width="30" height="30" />}<div className="y25210-11">Time shares</div></td>
                      </tr>
                      <tr>
                        <td onClick={this.handleAmenityChange.bind(this, 'pool')}>{this.state.pool ? <img className="amenity" src="/images/amenities/poolb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/pool.png" alt="pool" width="30" height="30" />}<div className="y25210-11">Pool</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'garage')}>{this.state.garage ? <img className="amenity" src="/images/amenities/garageb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/garage.png" alt="garage" width="30" height="30" />}<div className="y25210-11">Garage</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'healthclub')}>{this.state.healthclub ? <img className="amenity" src="/images/amenities/healthclubb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/healthclub.png" alt="healthclub" width="30" height="30"/>}<div className="y25210-11">Health club</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'outdoor')}>{this.state.outdoor ? <img className="amenity" src="/images/amenities/outdoorb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/outdoor.png" alt="outdoor" width="30" height="30" />}<div className="y25210-11">Outdoor space</div></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'handicap')}>{this.state.handicap ? <img className="amenity" src="/images/amenities/wheelchairb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/wheelchair.png" alt="wheelchair" width="30" height="30" />}<div className="y25210-11">Handicap accessible</div></td>
                        <td></td>
                        <td onClick={this.handleAmenityChange.bind(this, 'newconstruction')}>{this.state.newconstruction ? <img className="amenity" src="/images/amenities/newconstructionb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/newconstruction.png" alt="newconstruction" width="30" height="30" />}<div className="y25210-11">New construction</div></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div id="amenitiesSeparator"></div>
              </div>
            </div>
          </div>
        </div>
      );
    }
  });
  
  var GradeBubbles = React.createClass({
		closePopup: function(){
		  {this.props.closeDialog()}
		},
		render: function(){
			return(
				<div id="gradeBubbles">
          <i id="closeGradeBubblePopup" className="fa fa-times" onClick={this.closePopup} data-toggle='tooltip' title='close'></i>
					<h3>Grade Bubble Meanings</h3>
          <h6 className="Text-2-ex-lead" id="u334-12"><img className="viewImgs" src="/images/meets.png"/> = meets your criteria</h6>
          <h6 className="Text-2-ex-lead" id="u334-14"><img className="viewImgs" src="/images/exceeds.png"/> = exceeds your criteria</h6>
          <h6 className="Text-2-ex-lead"><img className="viewImgs" src="/images/greatly.png"/> = greatly exceeds your</h6>
				</div>
			);
		}
	});
  
	var EmailFolder = React.createClass({
    getInitialState: function() {
			return{
				email: this.props.user,
        agentSentBuyerFolder: this.props.agentSentBuyerFolder,
				folder: this.props.folder,
        recipient: "",
        comment: ""
			};
		},
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    emailFolder: function(){
      $.get("/controllers/ajax.php", {
        emailFolder: 'true',
        agentSentBuyerFolder: this.state.agentSentBuyerFolder, 
        sender: this.state.email,
        folder: this.state.folder,
        recipient: this.state.recipient,
        comment: this.state.comment,
        success: function(result){
          $(".ui-widget-overlay").hide();
          {this.props.closeDialog()}
        }.bind(this)
      });
		},
    closePopup: function(){
		  $(".ui-widget-overlay").hide();
		  {this.props.closeDialog()}
		},
		render: function(){
			return(
				<div>
          <div className="text-popups clearfix grpelem" id="closePopup3">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" data-toggle='tooltip' title='close'></i></h4>
          </div>
          <h2 className="Subhead-2" id="u1330-2">Email Folder</h2>
          <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
          <h4 className="text-popups padBottom" id="u1330-5">Send folder to:</h4>
          <h4 id="u1330-10"><span id="u1330-7"><input type="text" name="recipient" value={this.state.recipient} placeholder="Enter address" onChange={this.handleChange.bind(this, 'recipient')}/></span></h4>
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          <h4 className="text-popups padBottom" id="u1330-5">Add a comment:</h4>
          <textarea value={this.state.comment} placeholder="Comment (optional)" onChange={this.handleChange.bind(this, 'comment')}></textarea>
          <h4 className="text-popups" id="u1330-22">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-21" onClick={this.emailFolder}><span id="u1330-19">Send </span><span id="u1330-20"><i className="fa fa-chevron-right"></i></span></h4>
        </div>
			);
		}
  });
	
	var EditComment = React.createClass({
		getInitialState: function() {
			return{
				user: this.props.user,
				listing: this.props.listing,
				comment: this.props.comment
			};
		},
		handleChange: function(event) {
			this.setState({ comment: event.target.value });
		},
		editComment: function(event){
			event.preventDefault();
			$.get("ajax.php", {
				addComment: 'true', //Call the PHP function
				user: this.state.user,
				comments: this.state.comment,
				listing: this.state.listing,
				folder: this.props.folder,
				success: function(result){
					var ajaxStop = 0;
					$(document).ajaxStop(function() {
					  if(ajaxStop == 0){
              ajaxStop++;
              $(".ui-widget-overlay").hide();
              {this.props.closeDialog()}
					  }
					}.bind(this));
				}.bind(this)
			});
		},
		closePopup: function(){
		  $(".ui-widget-overlay").hide();
		  {this.props.closeDialog()}
		},
		render: function(){
			return(
				<div id="add-comment-form" title='Add Comment' list-numb=''>
					<img id="closePopup2" src="/images/close-x.png" onClick={this.closePopup}/>
					<form name="add-comment-form" id="add-comment-form">
						<p>Comment:</p>
						<br/>
						<textarea autofocus name="comments" value={this.state.comment} id="comments" className="text ui-widget-content ui-corner-all" style={{width: 515 + 'px', height: 130 + 'px'}} onChange={this.handleChange}></textarea>
						<br/><br/>
						<button type="submit" name="editComment" id="editCommentSubmit" onClick={this.editComment}>Submit<i id="arrow" className="fa fa-chevron-right"></i></button>
					</form>
				 </div>
			);
		}
	});
  
  var AgentListings = React.createClass({
		getInitialState: function() {
			return{
				agent_email: "<? echo $agent_email ?>",
				agent_id: "<? echo $agent_id ?>",
        mainPage: "<? echo $mainPage ?>",
				lastUpdate: "",
				folders: [],
        buyerFolders: [],
				openFolder: "",
        buyersViewed: "active"
			};
		},
		componentDidMount: function(){    
		  this.getListings();
      this.getBuyerFolders();
		},
		getListings: function(action){
			$.ajax({
				type: "POST",
				url: "get-agent-listings.php",
				data: {"email": this.state.agent_email},
				success: function(data){
				  var folders = JSON.parse(data);
				  var ajaxStop = 0;
				  $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({folders: folders});
              $("#loading").hide();
              $("#noFolders").show();
            }
				  }.bind(this));
				}.bind(this),
				error: function(a,b,c){
				  console.log("failed");
				}
			});
		},
    getBuyerFolders: function(){
      $("#loadingActive").show();
      $.ajax({
				type: "POST",
				url: "get-agents-buyer-folders.php",
				data: {"agentID": this.state.agent_id},
				success: function(data){
				  var folders = JSON.parse(data);
				  var ajaxStop = 0;
				  $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({buyerFolders: folders});
              $("#loadingActive").hide();
            }
				  }.bind(this));
				}.bind(this),
				error: function(){
				  console.log("failed");
				}
			});
    },
    getArchivedBuyerFolders: function(){
      $.ajax({
				type: "POST",
				url: "get-agents-archived-buyer-folders.php",
				data: {"agentID": this.state.agent_id},
				success: function(data){
				  var folders = JSON.parse(data);
				  var ajaxStop = 0;
				  $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({buyerFolders: folders});
              $("#loadingArchived").hide();
              $("#noFoldersAchived").show();
            }
				  }.bind(this));
				}.bind(this),
				error: function(){
				  console.log("failed");
				}
			});
    },
    switchViews: function(view){    
      this.setState({buyerFolders: []}); 
      this.setState({buyersViewed: view});     
      if(view == "active"){ this.getBuyerFolders(); }
      else if(view == "archived"){ this.getArchivedBuyerFolders(); }
    },
    openFolder: function(name){
		  if(this.state.openFolder != name){ this.setState({openFolder: name}); }
		  else{ this.setState({openFolder: ""}); }
		},
    viewGrades: function(){
			var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 260,
				dialogClass: 'viewGradesPopup',
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
			});
			var closeDialog = function(){
				$dialog.dialog('close');
			}.bind(this)

			$(".ui-widget-overlay").show();
			ReactDOM.render(<GradeBubbles closeDialog={closeDialog}/>, $dialog[0]);
		},
    emailFolder: function(name){
      var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 565,
				dialogClass: 'emailFolderPopup',
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $(".ui-widget-overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				$dialog.dialog('close');
			}.bind(this)

			$(".ui-widget-overlay").show();
			ReactDOM.render(<EmailFolder closeDialog={closeDialog} user={this.state.agent_email} folder={name} agentSentBuyerFolder={"false"}/>, $dialog[0]);
		},
    emailBuyerFolder: function(name, buyer){
      var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 565,
				dialogClass: 'emailFolderPopup',
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $(".ui-widget-overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				$dialog.dialog('close');
			}.bind(this)

			$(".ui-widget-overlay").show();
			ReactDOM.render(<EmailFolder closeDialog={closeDialog} user={buyer} folder={name} agentSentBuyerFolder={"true"}/>, $dialog[0]);
		},
    editBuyerFormula: function(name, buyer){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
		width: 1115,
        dialogClass: "editFormula",
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $(".ui-widget-overlay").hide();
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }.bind(this)

      $(".ui-widget-overlay").show();
      ReactDOM.render(<EditSearch closeDialog={closeDialog} searchName={name} buyer={buyer}/>, $dialog[0]);
    },
    deleteFolder: function(name, buyer){
      $("#ajax-box2").dialog({
        autoOpen: false,
        height: 240,
        width: 365,
        modal: true,
        dialogClass: 'deleteBuyerPopup',
        buttons: {
          Yes: function() {
            $.get("ajax.php", {
              deleteBuyer: 'true',
              buyer: buyer,
              success: function(result){
                var ajaxStop = 0;
                $(document).ajaxStop(function() {
                  if(ajaxStop == 0){
                    ajaxStop++;
                    $("#ajax-box2").dialog( "destroy" );
                    this.getBuyerFolders();
                  }
                }.bind(this));
              }.bind(this)
            });
          }.bind(this),
          No: function() {
            $( this ).dialog( "destroy" );
          }
        },
        close: function() {
          $( this ).dialog( "destroy" );
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box2").dialog('close');
          });
        }
      });
      $('#ajax-box2').load('messages.php #deleteBuyer',function(){
        $('#ajax-box2').dialog( "option", "title", "Permanently Delete Buyer" ).dialog('open');
        $("#deleteBuyer").find("#name").html(name);
      });
		},
    activateFolder: function(name, buyer){
      $.get("/controllers/ajax.php", {
        activateBuyer: 'true',
        buyer: buyer,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getArchivedBuyerFolders();
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
    },
    archiveFolder: function(name, buyer){
      console.log(buyer);
      $.get("/controllers/ajax.php", {
        archiveBuyer: 'true',
        buyer: buyer,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getBuyerFolders();
            }
          }.bind(this));
        }.bind(this),
        error: function(a,b,c){
          console.log("failed");
        }
      });
    },
    editComment: function(comment, listing, folder){
			var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 565,
				dialogClass: 'editCommentPopup',
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $(".ui-widget-overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				this.getListings("comment");
				$dialog.dialog('close');
			}.bind(this)

			$(".ui-widget-overlay").show();
			ReactDOM.render(<EditComment closeDialog={closeDialog} user={this.state.agent_email} comment={comment} listing={listing} folder={folder}/>, $dialog[0]);
		},
    editBuyerComment: function(comment, listing, folder, buyer){
			var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 565,
				dialogClass: 'editCommentPopup',
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          <!--$(this).css("display", "block");-->
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $(".ui-widget-overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				this.getBuyerFolders();
				$dialog.dialog('close');
			}.bind(this)

			$(".ui-widget-overlay").show();
			ReactDOM.render(<EditComment closeDialog={closeDialog} user={buyer} comment={comment} listing={listing} folder={folder}/>, $dialog[0]);
		},
    deleteListing: function(listing, folder){
			$.get("/controllers/ajax.php", {
				clear_one_queued_from_folder: 'true',
				delete_id: listing,
				folder: folder
			}, function(){
				var ajaxStop = 0;
				$(document).ajaxStop(function() {
				  if(ajaxStop == 0){
            ajaxStop++;
            this.getListings("deletion");
				  }
				}.bind(this));
			 }.bind(this));
		},
    deleteBuyerListing: function(listing, folder, buyer){
			$.get("/controllers/ajax.php", {
				clear_one_saved_from_folder: 'true',
				buyer: buyer,
				delete_id: listing,
				folder: folder
			}, function(){
				var ajaxStop = 0;
				$(document).ajaxStop(function() {
				  if(ajaxStop == 0){
            ajaxStop++;
            this.getBuyerFolders();
				  }
				}.bind(this));
			 }.bind(this));
		},
    render: function(){
      var folders = this.state.folders.map(function(folder){
				var name = folder['name'];
				var key = name.replace(/ /g,"_").replace(/\//g, '_');
				var last_update = folder['last_update'];
				var listings = folder['listings'].map(function(listing) {
				  return (
            <div>
              <div>
                <table id="fullTable" style={{marginLeft: 10 + 'px'}} key={"full"+listing.id}>
                  <colgroup>
                    <col width="173"/><col width="60"/><col width="100"/><col width="40"/><col width="51"/><col width="80"/><col width="35"/><col width="39"/><col width="37"/><col width="45"/><col width="33"/><col width="290"/><col width="85"/><col width="80"/>
                  </colgroup>
                  <tbody>
                  <tr>
                    <td style={{width: 173 + 'px'}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                    <td style={{width: 60 + 'px'}}>{listing.apt}</td>
                    <td style={{width: 100 + 'px'}}>${listing.price}</td>
                    <td style={{textAlign: "center", width: 40 + 'px'}}>{listing.bed}</td>
                    <td style={{textAlign: "center", width: 51 + 'px'}}>{listing.bath}</td>
                    <td style={{width: 80 + 'px'}}>${listing.monthly}</td>
                    <td style={{textAlign: "center", width: 35 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                    <td style={{textAlign: "center", width: 39 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                    <td style={{textAlign: "center", width: 37 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                    <td style={{textAlign: "center", width: 45 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                    <td style={{width: 33 + 'px'}}></td>
                    <td style={{width: 290 + 'px'}}>
                      <a style={{cursor: "pointer"}} onClick={this.editComment.bind(this, listing.comments, listing.listing_num, listing.folder)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                    </td>
                    <td style={{width: 85 + 'px'}}>{listing.status}</td>
                    <td style={{width: 80 + 'px'}}>{listing.date}</td>
                    <td>
                    <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteListing.bind(this, listing.listing_num, listing.folder)}><i className="fa fa-times" title="delete"></i></a></span>
                    </td>
                  </tr>
                  </tbody>
                </table>

                <div className="midTable" key={"mid"+listing.id}>
                  <table className="midTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                        <td style={{width: 80 + 'px'}}>Apt# {listing.apt}</td>
                        <td style={{width: 100 + 'px'}}>${listing.price}</td>
                        <td style={{textAlign: "center", width: 60 + 'px'}}>{listing.bed} beds</td>
                        <td style={{textAlign: "center", width: 80 + 'px'}}>{listing.bath} baths</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="midTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 150 + 'px'}}>${listing.monthly} / month</td>
                        <td style={{width: 120 + 'px'}}>Location: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                        <td style={{width: 105 + 'px'}}>View: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="midTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>
                          <a style={{cursor: "pointer"}} onClick={this.editComment.bind(this, listing.comments, listing.listing_num, listing.folder)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                        </td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                        <td>
                          <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteListing.bind(this, listing.listing_num, listing.folder)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div className="smallTable" key={"small"+listing.id}>
                  <table className="smallTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                        <td style={{width: 80 + 'px'}}>Apt# {listing.apt}</td>
                        <td style={{width: 100 + 'px'}}>${listing.price}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 60 + 'px'}}>{listing.bed} beds</td>
                        <td style={{width: 65 + 'px'}}>{listing.bath} baths</td>
                        <td style={{width: 150 + 'px'}}>${listing.monthly} / month</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 120 + 'px'}}>Location: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                      </tr>
                      <tr>
                        <td style={{width: 105 + 'px'}}>View: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>
                          <a style={{cursor: "pointer"}} onClick={this.editComment.bind(this, listing.comments, listing.listing_num, listing.folder)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                        </td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                        <td>
                          <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteListing.bind(this, listing.listing_num, listing.folder)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
				  );
				}.bind(this));        
				return(
				  <div key={key} className="Text-1 u16157-235">
					  <div id="u16157-8" className="container-fluid">
              <div className="row">
                <div className="col-md-3 col-sm-3 col-xs-12 folderDetails">
                  {this.state.openFolder == name ? <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><img className="block" id="u16154_img" src="/images/icon_folder_open_blue_1a.png" alt="" width="40" height="37"/></span> : <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><i className="fa fa-folder-o"></i></span> }
                  <span id="u16157-2"><a style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}>&nbsp; {name}</a></span>
                </div>
                <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent"><span id="u16157-7">Last updated: {last_update}</span></div>
								<div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.emailFolder.bind(this, name)}>email listings</a></span></div>
              </div>
            </div>
            {this.state.openFolder == name ?
              <div id={key}>
                {folder['listings'].length > 0 ?
                  <div id="listingsSection">
                    <table id="tableHeader" style={{marginLeft: 10 + 'px', marginBottom: 10 + 'px'}}>
                      <colgroup>
                        <col width="173"/><col width="60"/><col width="100"/><col width="40"/><col width="51"/><col width="80"/><col width="35"/><col width="39"/><col width="37"/><col width="45"/><col width="33"/><col width="290"/><col width="85"/><col width="85"/>
                      </colgroup>
                      <tbody>
                        <tr style={{fontWeight: "bold"}}>
                          <th style={{width: 173 + 'px'}}>Address</th>
                          <th style={{width: 60 + 'px'}}>Apt</th>
                          <th style={{width: 100 + 'px'}}>Price</th>
                          <th style={{width: 40 + 'px'}}>BRs</th>
                          <th style={{width: 51 + 'px'}}>Baths</th>
                          <th style={{width: 80 + 'px'}}>Charge/<br/>Month</th>
                          <th style={{width: 35 + 'px'}}>Loc</th>
                          <th style={{width: 39 + 'px'}}>Bldg</th>
                          <th style={{width: 37 + 'px'}}>View</th>
                          <th style={{width: 45 + 'px'}}>Space</th>
                          <th style={{width: 33 + 'px'}}></th>
                          <th style={{width: 290 + 'px'}}>Comments<br/>click (<span id="u16157-13"><i className="fa fa-plus"></i></span>) to edit</th>
                          <th style={{width: 85 + 'px'}}>Status</th>
                          <th style={{width: 85 + 'px'}}>Date added</th>
                        </tr>
                      </tbody>
                    </table>
                    {listings}
                    <div id="u16157-212"><p id="u16157-211">Gold bubble icons evaluate only the listings saved to this folder, and show how they rate compared to one another. When new listings are added, the gold bubbles will update.&nbsp; <span id="u16156-12"><i className="fa fa-question-circle" title="more info"></i> </span><span id="u16156-13"><a href={"faq.php?section=manage&MP="+this.state.mainPage}>more info</a></span></p></div>
                    <p>&nbsp;</p>
                  </div>
                : <div className="Text-1 noSaveMessage"><span>No Saved Listings</span></div> }
              </div>
            : null }
				  </div>
				);
			}.bind(this));
      var buyerFolders = this.state.buyerFolders.map(function(folder){
				var buyer = folder['user'];
        var name = folder['name'];
        var folderName = folder['folderName'];
				var key = name.replace(/ /g,"_").replace(/\//g, '_');
				var last_update = folder['last_update'];
				var listings = folder['listings'].map(function(listing) {
				  return (
            <div>
              <div>
                <table id="fullTable" style={{marginLeft: 10 + 'px'}} key={"full"+listing.id}>
                  <colgroup>
                    <col width="173"/><col width="60"/><col width="100"/><col width="40"/><col width="51"/><col width="80"/><col width="35"/><col width="39"/><col width="37"/><col width="45"/><col width="33"/><col width="290"/><col width="85"/><col width="80"/>
                  </colgroup>
                  <tbody>
                  <tr>
                    <td style={{width: 173 + 'px'}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                    <td style={{width: 60 + 'px'}}>{listing.apt}</td>
                    <td style={{width: 100 + 'px'}}>${listing.price}</td>
                    <td style={{textAlign: "center", width: 40 + 'px'}}>{listing.bed}</td>
                    <td style={{textAlign: "center", width: 51 + 'px'}}>{listing.bath}</td>
                    <td style={{width: 80 + 'px'}}>${listing.monthly}</td>
                    <td style={{textAlign: "center", width: 35 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} data-toggle='tooltip' title={listing.locTitle} height="16"/></td>
                    <td style={{textAlign: "center", width: 39 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} data-toggle='tooltip' title={listing.bldTitle} height="16"/></td>
                    <td style={{textAlign: "center", width: 37 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} data-toggle='tooltip' title={listing.vwsTitle} height="16"/></td>
                    <td style={{textAlign: "center", width: 45 + 'px'}}><img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} data-toggle='tooltip' title={listing.spaceTitle} height="16"/></td>
                    <td style={{width: 33 + 'px'}}></td>
                    <td style={{width: 290 + 'px'}}>
                      <a style={{cursor: "pointer"}} onClick={this.editBuyerComment.bind(this, listing.comments, listing.listing_num, listing.folder, buyer)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                    </td>
                    <td style={{width: 85 + 'px'}}>{listing.status}</td>
                    <td style={{width: 80 + 'px'}}>{listing.date}</td>
                    <td>
                    <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteBuyerListing.bind(this, listing.listing_num, listing.folder, buyer)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
                    </td>
                  </tr>
                  </tbody>
                </table>

                <div className="midTable" key={"mid"+listing.id}>
                  <table className="midTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                        <td style={{width: 80 + 'px'}}>Apt# {listing.apt}</td>
                        <td style={{width: 100 + 'px'}}>${listing.price}</td>
                        <td style={{textAlign: "center", width: 60 + 'px'}}>{listing.bed} beds</td>
                        <td style={{textAlign: "center", width: 80 + 'px'}}>{listing.bath} baths</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="midTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 150 + 'px'}}>${listing.monthly} / month</td>
                        <td style={{width: 120 + 'px'}}>Location: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                        <td style={{width: 105 + 'px'}}>View: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="midTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>
                          <a style={{cursor: "pointer"}} onClick={this.editBuyerComment.bind(this, listing.comments, listing.listing_num, listing.folder, buyer)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                        </td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                        <td>
                          <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteBuyerListing.bind(this, listing.listing_num, listing.folder, buyer)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div className="smallTable" key={"small"+listing.id}>
                  <table className="smallTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a href={"/controllers/single-listing.php?list_numb=" + listing.listing_num + "&MP=" + this.state.mainPage}>{listing.address}</a></td>
                        <td style={{width: 80 + 'px'}}>Apt# {listing.apt}</td>
                        <td style={{width: 100 + 'px'}}>${listing.price}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 60 + 'px'}}>{listing.bed} beds</td>
                        <td style={{width: 65 + 'px'}}>{listing.bath} baths</td>
                        <td style={{width: 150 + 'px'}}>${listing.monthly} / month</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 120 + 'px'}}>Location: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                      </tr>
                      <tr>
                        <td style={{width: 105 + 'px'}}>View: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img style={{cursor: "pointer"}} onClick={this.viewGrades} className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>
                          <a style={{cursor: "pointer"}} onClick={this.editBuyerComment.bind(this, listing.comments, listing.listing_num, listing.folder, buyer)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                        </td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                        <td>
                          <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteBuyerListing.bind(this, listing.listing_num, listing.folder, buyer)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
				  );
				}.bind(this));
				return(
				  <div key={key} className="Text-1 u16157-235">
					  <div id="u16157-8" className="container-fluid">
              {this.state.buyersViewed == "active" ?
                <div className="row">
                  <div className="col-md-3 col-sm-3 col-xs-12 folderDetails">
                    {this.state.openFolder == name ? <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><img className="block" id="u16154_img" src="/images/icon_folder_open_blue_1a.png" alt="" width="40" height="37"/></span> : <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><i className="fa fa-folder-o"></i></span> }
                    <span id="u16157-2"><a style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}>&nbsp; {name} ({folderName})</a></span>
                  </div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent"><span id="u16157-7">Last updated: {last_update}</span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.emailBuyerFolder.bind(this, folderName, buyer)}>email listings</a></span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.editBuyerFormula.bind(this, folderName, buyer)}>view/edit buyer formula</a></span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.deleteFolder.bind(this, folderName, buyer)}>delete</a></span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.archiveFolder.bind(this, folderName, buyer)}>archive</a></span></div>
                </div>
              :
                <div className="row">
                  <div className="col-md-3 col-sm-3 col-xs-12 folderDetails">
                    {this.state.openFolder == name ? <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><img className="block" id="u16154_img" src="/images/icon_folder_open_blue_1a.png" alt="" width="40" height="37"/></span> : <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><i className="fa fa-folder-o"></i></span> }
                    <span id="u16157-2"><a style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}>&nbsp; {name} ({folderName})</a></span>
                  </div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent"><span id="u16157-7">Last updated: {last_update}</span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.activateFolder.bind(this, folderName, buyer)}>make folder active</a></span></div>
                  <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.deleteFolder.bind(this, folderName, buyer)}>delete</a></span></div>
                </div>
              }
            </div>
            {this.state.openFolder == name ?
              <div id={key}>
                {folder['listings'].length > 0 ?
                  <div id="listingsSection">                    
                    <table id="tableHeader" style={{marginLeft: 10 + 'px', marginBottom: 10 + 'px'}}>
                      <colgroup>
                        <col width="173"/><col width="60"/><col width="100"/><col width="40"/><col width="51"/><col width="80"/><col width="35"/><col width="39"/><col width="37"/><col width="45"/><col width="33"/><col width="290"/><col width="85"/><col width="85"/>
                      </colgroup>
                      <tbody>
                        <tr style={{fontWeight: "bold"}}>
                          <th style={{width: 173 + 'px'}}>Address</th>
                          <th style={{width: 60 + 'px'}}>Apt</th>
                          <th style={{width: 100 + 'px'}}>Price</th>
                          <th style={{width: 40 + 'px'}}>BRs</th>
                          <th style={{width: 51 + 'px'}}>Baths</th>
                          <th style={{width: 80 + 'px'}}>Charge/<br/>Month</th>
                          <th style={{width: 35 + 'px'}}>Loc</th>
                          <th style={{width: 39 + 'px'}}>Bldg</th>
                          <th style={{width: 37 + 'px'}}>View</th>
                          <th style={{width: 45 + 'px'}}>Space</th>
                          <th style={{width: 33 + 'px'}}></th>
                          <th style={{width: 290 + 'px'}}>Comments<br/>click (<span id="u16157-13"><i className="fa fa-plus"></i></span>) to edit</th>
                          <th style={{width: 85 + 'px'}}>Status</th>
                          <th style={{width: 85 + 'px'}}>Date added</th>
                        </tr>
                      </tbody>
                    </table>                    
                    {listings}
                    <div id="u16157-212"><p id="u16157-211">Gold bubble icons evaluate only the listings saved to this folder and shows how they rate when compared to one another. When new listings are added, the gold bubbles will update.&nbsp; <span id="u16156-12"><i className="fa fa-question-circle" title="more info"></i> </span><span id="u16156-13"><a href="faq.php?section=manage">more info</a></span></p></div>
                    <p>&nbsp;</p>
                  </div>
                : <div className="Text-1 noSaveMessage"><span>No Saved Listings</span></div> }
              </div>
            : null }
				  </div>
				);
			}.bind(this));
      return(
			  <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
           <Header />
           <NavBar mainPage={this.state.mainPage} />
           <AddressSearch mainPage={this.state.mainPage} />
            {this.state.buyersViewed == "active" ?
              <div className="container-fluid folder-section">
                <div className="row">
                  <div className="col-md-5 col-sm-5 col-xs-12 pageTitle">
                    <div className="clearfix grpelem" id="u16159-5">
                      <p id="u16159-3"><span id="u16159">Your Folders </span><span id="u16159-2">click name to open or edit</span></p>
                    </div>
                  </div>
                </div>
                <div className="row">
                  <div className="col-md-12">
                    {this.state.folders.length > 0 ?
                      <div id="folderSection">
                        <div style={{display: "block"}}>{folders}</div>
                        {this.state.buyerFolders.length > 0 ? 
                          <div>{buyerFolders}</div>
                        :
                          <div id="loadingActive" className="Text-1 noSaveMessage"><span>Loading Buyer Folders...</span></div>
                        }
                      </div>
                    :
                      <div>
                        <div id="loading" className="Text-1 noSaveMessage"><span>Loading Folders...</span></div>
                        <div id="noFolders" className="Text-1 noSaveMessage" style={{display: 'none'}}><span>No Saved Folders</span></div>
                      </div>
                    }
                  </div>
                </div>
                <div className="row">
                  <div className="clearfix grpelem" id="u16159-5b">
                    <p id="u16159-3" onClick={this.switchViews.bind(this, 'archived')}><span id="u16159">View Archived Folders </span><i className="fa fa-chevron-right"></i></p>
                  </div>
                </div>
              </div>
            :
              <div className="container-fluid folder-section">
                <div className="row">
                  <div className="col-md-5 col-sm-5 col-xs-12 pageTitle">
                    <div className="clearfix grpelem" id="u16159-5">
                      <p id="u16159-3"><span id="u16159">Archived Folders </span><span id="u16159-2">click name to open or edit</span></p>
                    </div>
                  </div>
                </div>
                <div className="row">
                  <div className="col-md-12">
                    {this.state.buyerFolders.length > 0 ?
                      <div id="folderSection">
                        <div>{buyerFolders}</div>
                      </div>
                    :
                      <div>
                        <div id="loadingArchived" className="Text-1 noSaveMessage"><span>Loading Folders...</span></div>
                        <div id="noFoldersAchived" className="Text-1 noSaveMessage" style={{display: 'none'}}><span>No Archived Folders</span></div>
                      </div>
                    }
                  </div>
                </div>
                <div className="row">
                  <div className="clearfix grpelem" id="u16159-5b">
                    <p id="u16159-3" onClick={this.switchViews.bind(this, 'active')}><span id="u16159">View Active Folders </span><i className="fa fa-chevron-right"></i></p>
                  </div>
                </div>
              </div>
            }
          </div>
			  </div>
			);
    }
	});

	ReactDOM.render(
		<AgentListings />,
		document.getElementById("agent-listings")
	);

	ReactDOM.render(
		<Footer mainPage={"<? echo $mainPage ?>"} />,
		document.getElementById("footer")
	);
</script>
</body>
</html>