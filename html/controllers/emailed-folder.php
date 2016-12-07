<?php
session_start();
include('functions.php');
include('basicHead.php');
include("dbconfig.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['buyer'])){
  $user = $_SESSION['id'];
  $buyer_email = $_SESSION['email'];
  $role = 'user';
  $folder = "";  
  $agent_id = '';
}
elseif(isset($_SESSION['agent'])){
  $user = $_SESSION['id'];
  $agent_email = $_SESSION['email'];
  $agent_id = $_SESSION['agent_id'];
  $role = 'agent';

  if(isset($_GET['buyer'])){ $buyer_email = $_GET['buyer']; }
  if(isset($_GET['folder'])){ $folder = $_GET['folder']; }
}
else{
  $role = 'guest';
  $agent_id = '';
}

$sender = $_GET['user'];
$folder = $_GET['folder'];
$name = $_GET['name'];

if(strpos($sender, "@bellmarc.com") !== false){
  $SQL = "SELECT first_name, last_name FROM `registered_agents` WHERE (email = '".$sender."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $sender_firstname = $row['first_name'];
  $sender_lastname = $row['last_name'];
}
else{
  $SQL = "SELECT first_name, last_name FROM `users` WHERE (email = '".$sender."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $sender_firstname = $row['first_name'];
  $sender_lastname = $row['last_name'];
}

if($sender_firstname == ""){ $sender_firstname = $name; }
?>
  <title>HomePik - Listing Folders</title>
  <?php include_css("/views/css/buyer-listing-folders.css"); ?>
  <style> #backBtn{ display: none;} </style>
 </head>
 <body>
	<div id="buyer-listings"></div>
	<div id="footer"></div>
	<div id="overlay"></div>
	<div id="ajax-box"></div>
	<div id="ajax-box2"></div>
<script type="text/babel">
	var UserListings = React.createClass({
		getInitialState: function() {
			return{
				user_email: "<? echo $user_email ?>",
        sender: "<? echo $sender ?>",
				agent_id: "<? echo $agent_id ?>",
				lastUpdate: "",
				folders: [],
				openFolder: "<? echo $folder ?>",
        documentWidth: ""
			};
		},
		componentDidMount: function(){
		  this.getListings();
      this.setState({documentWidth: $(document).width()});
		},
		getListings: function(action){
    console.log(this.state.sender);
    console.log(this.state.openFolder);
		  $.ajax({
			type: "POST",
			url: "get-emailed-folder.php",
			data: {"email": this.state.sender, "folder": this.state.openFolder},
			success: function(data){
			  var folders = JSON.parse(data);
        console.log(folders);
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
			error: function(){
			  console.log("failed");
			}
		  });
		},
		checkFolders: function(){
		  if(this.state.folders.length > 0){ return true; }
		  else{ return false; }
		},
		openFolder: function(name){
		  if(this.state.openFolder != name){
        this.setState({openFolder: name});
        if(this.state.editFolder == true){
          this.setState({folderEditing: name});
          this.setState({folderName: name});
        }
		  }
		  else{
        this.setState({openFolder: ""});
		  }
		},
		handleFolder: function(name, event) {
		  var change = {};
		  change[name] = event.target.value;
		  this.setState(change);
		},
		checkName: function(name){
		  if(this.state.openFolder == name){ return true; }
		  else{ return false; }
		},
		viewListing: function(listing){
		  window.location = 'http://homepik.com/controllers/single-listing.php?list_numb=' + listing;
		},
		render: function(){
			var folders = this.state.folders.map(function (folder) {
				var name = folder['name'];
				var key = name.replace(/ /g,"_").replace(/\//g, '_');
				var last_update = folder['last_update'];
				var agent_name = folder['agent'];
				var agent1 =  folder['agent1'];
				var agent2 = folder['agent2'];
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
                    <td style={{width: 173 + 'px'}}><a style={{cursor: "pointer"}} onClick={this.viewListing.bind(this,listing.listing_num)}>{listing.address}</a></td>
                    <td style={{width: 60 + 'px'}}>{listing.apt}</td>
                    <td style={{width: 100 + 'px'}}>${listing.price}</td>
                    <td style={{textAlign: "center", width: 40 + 'px'}}>{listing.bed}</td>
                    <td style={{textAlign: "center", width: 51 + 'px'}}>{listing.bath}</td>
                    <td style={{width: 80 + 'px'}}>${listing.monthly}</td>
                    <td style={{textAlign: "center", width: 35 + 'px'}}><img className='quality2' src={listing.loc} height="16"/></td>
                    <td style={{textAlign: "center", width: 39 + 'px'}}><img className='quality2' src={listing.bld} height="16"/></td>
                    <td style={{textAlign: "center", width: 37 + 'px'}}><img className='quality2' src={listing.vws} height="16"/></td>
                    <td style={{textAlign: "center", width: 45 + 'px'}}><img className='quality2' src={listing.space} height="16"/></td>
                    <td style={{width: 33 + 'px'}}></td>
                    <td style={{width: 290 + 'px'}}>{listing.comments}</td>
                    <td style={{width: 85 + 'px'}}>{listing.status}</td>
                    <td style={{width: 80 + 'px'}}>{listing.date}</td>
                  </tr>
                  </tbody>
                </table>
  
                <div className="midTable" key={"mid"+listing.id}>
                  <table className="midTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a style={{cursor: "pointer"}} onClick={this.viewListing.bind(this,listing.listing_num)}>{listing.address}</a></td>
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
                        <td style={{width: 120 + 'px'}}>Location: <img className='quality2' src={listing.loc} height="16"/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img className='quality2' src={listing.bld} height="16"/></td>
                        <td style={{width: 105 + 'px'}}>View: <img className='quality2' src={listing.vws} height="16"/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img className='quality2' src={listing.space} height="16"/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="midTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>{listing.comments}</td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
  
                <div className="smallTable" key={"small"+listing.id}>
                  <table className="smallTable" style={{marginLeft: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: "auto", paddingRight: 10 + "px"}}><a style={{cursor: "pointer"}} onClick={this.viewListing.bind(this,listing.listing_num)}>{listing.address}</a></td>
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
                        <td style={{width: 120 + 'px'}}>Location: <img className='quality2' src={listing.loc} height="16"/></td>
                        <td style={{width: 120 + 'px'}}>Building: <img className='quality2' src={listing.bld} height="16"/></td>
                      </tr>
                      <tr>
                        <td style={{width: 105 + 'px'}}>View: <img className='quality2' src={listing.vws} height="16"/></td>
                        <td style={{width: 120 + 'px'}}>Space: <img className='quality2' src={listing.space} height="16"/></td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 200 + 'px'}}>{listing.comments}</td>
                        <td style={{width: 120 + 'px'}}>{listing.status}</td>
                      </tr>
                    </tbody>
                  </table>
                  <table className="smallTable" style={{marginLeft: 40 + "px", marginBottom: 20 + "px"}}>
                    <tbody>
                      <tr>
                        <td style={{width: 150 + 'px'}}>Added: {listing.date}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
				  );
				}.bind(this));
				return(
				  <div key={key} className="Text-1 clearfix u16157-235" style={{marginTop: 0}}>
            <div id="u16157-8" className="container-fluid">
              <div className="row">
                <div className="col-md-3 col-sm-3 col-xs-12 folderDetails">
                  <span id="u16157"><i className="fa fa-folder-o"></i></span>
                  {this.checkName(name) ? <div className="clip_frame grpelem" id="u16154"><img className="block" id="u16154_img" src="/images/pointer_arrow_right.png" alt="" width="20" height="20"/></div> : null }
                  <span id="u16157-2"><a style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}>&nbsp; {name}</a></span>
                </div>
                <div className="col-md-2 col-sm-1 col-xs-12 folderDetails indent customWidth"><span id="u16157-7">{agent_name}</span></div>
                <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent"><span id="u16157-7">Last updated: {last_update}</span></div>
              </div>
            </div>
					{this.checkName(name) ?
					  <div id={key}>
						<p id="u16157-9">&nbsp;</p>
						  {folder['listings'].length > 0 ?
							<div id="listingSection">
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
                    <th style={{width: 290 + 'px'}}>Comments</th>
                    <th style={{width: 85 + 'px'}}>Status</th>
                    <th style={{width: 85 + 'px'}}>Date added</th>
                  </tr>
                  </tbody>
                </table>
							  {listings}
							  <p id="u16157-209">&nbsp;</p>
							  {/* <p id="u16157-212"><span id="u16157-211">Note: each time new listings are added,<br/>the comparison of the gold bubbles will update.</span></p> */}
                <div id="u16157-212"><p id="u16157-211">Gold bubble icons evaluate only the listings saved to this folder and shows how they rate when compared to one another. When new listings are added, the gold bubbles will update.&nbsp; <span id="u16156-12"><i className="fa fa-question-circle"></i> </span><span id="u16156-13"><a href="faq.php?section=manage">more info</a></span></p></div>
							  <p>&nbsp;</p>
							</div>
						  :
							<div style={{width: 250 + "px", minHeight: 200 + "px", marginLeft: 40 + "px", fontSize: 16 + "pt"}}><span>No Saved Listings</span></div>
						  }
						</div>
					:
					  null
					}
				  </div>
				);
			}.bind(this));
			return(
			  <div className="clearfix" id="page">
				<div className="position_content" id="page_position_content">
				  <Header />
				  <NavBar />
				  <AddressSearch />
				  <div className="container-fluid folder-section">
					<div className="row">
					  <div className="col-md-5 col-sm-5 col-xs-12">
						<div className="clearfix grpelem" id="u16160-3">
						  <p>&nbsp;</p>
						</div>
						<div className="clearfix grpelem" id="u16159-5">
						  <p id="u16159-3"><span id="u16159"><?php if($sender_firstname != ""){ echo $sender_firstname . " ". $sender_lastname; }else{ echo $sender; } ?>'s Listing Folder </span><span id="u16159-2">click name to open</span></p>
						</div>
					  </div>
            {/*
					  <div className="col-md-7 col-sm-7 col-xs-12">
              <div className="grpelem" id="u16158"></div>
              <div className="clearfix grpelem" id="u16156-17">
               <p id="u16156-4"><span id="u16156">1. </span><span id="u16156-2">This list</span> shows all of the apartments that have been saved to this folder, either by the buyer or by their agent.</p>
               <p id="u16156-14"><span id="u16156-5">2.</span><span id="u16156-6"> </span><span id="u16156-7">Gold bubble icons</span><span id="u16156-11"> evaluate only the listings saved to this folder and shows how they rate when compared to one another. When new listings are added, the gold bubbles will update.&nbsp; </span> <span id="u16156-12"><i className="fa fa-question-circle"></i> </span><span id="u16156-13"><a href="faq.php?section=manage">more info</a></span></p>
              </div>
					  </div>
            */}
					</div>
					<div className="row">
					  <div className="col-md-12">
						<div className="clearfix"></div>
						{this.checkFolders() ?
						  <div id="folderSection" style={{marginTop: 0 + 'px'}}>
							{folders}
						  </div>
						:
						  <div>
							<div id="loading" className="Text-1" style={{width: 250 + "px", minHeight: 200 + "px", marginLeft: 10 + "px", fontSize: 16 + "pt"}}><span>Loading Folder...</span></div>
							<div id="noFolders" style={{width: 250 + "px", minHeight: 200 + "px", marginLeft: 10 + "px", fontSize: 16 + "pt", display: "none"}}><span>The Folder Does Not Exist</span></div>
						  </div>
						}
					  </div>
					</div>
				  </div>
				</div>
			  </div>
			);
		}
	});

	ReactDOM.render(
		<UserListings />,
		document.getElementById("buyer-listings")
	);

	ReactDOM.render(
		<Footer />,
		document.getElementById("footer")
	);
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
