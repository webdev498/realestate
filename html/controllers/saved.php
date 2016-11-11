<?php
session_start();
include('functions.php');
include('basicHead.php');
include("dbconfig.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");
if($_SESSION['buyer']){
  $user = $_SESSION['id'];
  $role = 'user';
  $buyer_email = $_SESSION['email'];
  $folder = "";
}
elseif($_SESSION['agent']){
  $user = $_SESSION['id'];
  $role = 'agent';
  $agent_email = $_SESSION['email'];
  if(isset($_GET['buyer'])){ $buyer_email = $_GET['buyer']; }
  if(isset($_GET['folder'])){ $folder = $_GET['folder']; }
}
else{
  $user = $_SESSION['guestID'];
  $role = 'guest';
  $buyer_email = $_SESSION['guestID'];
}
if($role == "agent"){
  $SQL = "SELECT id FROM `Agent_Import` WHERE (e_mail = '".$agent_email."')";
  $result = mysql_query( $SQL ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $agent_id = $row['id'];
}
else{
  $agent_id = "";
}

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>
  <title>HomePik - Listing Folders</title>
  <?php include_css("/views/css/buyer-listing-folders.css");
  include_css("/views/css/buyer-profile-edit.css"); ?>
 </head>
 <body>
	<div id="buyer-listings"></div>
	<div id="footer"></div>
	<div id="overlay"></div>
	<div id="ajax-box"></div>
	<div id="ajax-box2"></div>
<script type="text/babel">
  var EmailFolder = React.createClass({
    getInitialState: function() {
			return{
				email: "<? echo $buyer_email ?>",
				folder: this.props.folder,
        guestName: "",
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
        sender: this.state.email,
        folder: this.state.folder,
        guestName: this.state.guestName,
        recipient: this.state.recipient,
        comment: this.state.comment,
        success: function(result){
          $("#overlay").hide();
          {this.props.closeDialog()}
        }.bind(this)
      });
		},
    closePopup: function(){
		  $("#overlay").hide();
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
          {this.props.role == "guest" ?
            <div>
              <h4 className="text-popups padBottom" id="u1330-5">Your name:</h4>
              <h4 id="u1330-10"><span id="u1330-7"><input type="text" name="guestName" placeholder="Enter name" value={this.state.guestName} onChange={this.handleChange.bind(this, 'guestName')}/></span></h4>
              <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
            </div>
          : null }
          <h4 className="text-popups padBottom" id="u1330-5">Send folder to:</h4>
          <h4 id="u1330-10"><span id="u1330-7"><input type="text" name="recipient" placeholder="Enter address" value={this.state.recipient} onChange={this.handleChange.bind(this, 'recipient')}/></span></h4>
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          <h4 className="text-popups padBottom" id="u1330-5">Add a comment:</h4>
          <textarea placeholder="Comment: (optional)" value={this.state.comment} onChange={this.handleChange.bind(this, 'comment')}></textarea>
          <h4 className="text-popups" id="u1330-22">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-21" onClick={this.emailFolder}><span id="u1330-19">Send </span><span id="u1330-20"><i className="fa fa-chevron-right"></i></span></h4>
        </div>
			);
		}
  });
  
	var EditComment = React.createClass({
		getInitialState: function() {
			return{
				agent: "<? echo $buyer_email ?>",
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
				user: this.state.agent,
				comments: this.state.comment,
				listing: this.state.listing,
				folder: this.props.folder,
				success: function(result){
					var ajaxStop = 0;
					$(document).ajaxStop(function() {
					  if(ajaxStop == 0){
              ajaxStop++;
              $("#overlay").hide();
              {this.props.closeDialog()}
					  }
					}.bind(this));
				}.bind(this)
			});
		},
		closePopup: function(){
		  $("#overlay").hide();
		  {this.props.closeDialog()}
		},
		render: function(){
			return(
				<div id="add-comment-form" title='Add Comment' list-numb=''>
					<i id="closePopup2" className="fa fa-times" onClick={this.closePopup} data-toggle='tooltip' title='close'></i>
          <form name="add-comment-form" id="add-comment-form">
						<p>Comment:</p>
						<br/>
						<textarea autofocus name="comments" value={this.state.comment} id="comments" className="text ui-widget-content ui-corner-all" style={{width: 515 + 'px', height: 130 + 'px'}} onChange={this.handleChange}></textarea>
						<br/><br/>
						<button type="submit" name="editComment" id="editCommentSubmit" onClick={this.editComment}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
					</form>
				 </div>
			);
		}
	});
  
  var BuyerListings = React.createClass({
		getInitialState: function() {
			return{
        role: "<? echo $role ?>",
				buyer_email: "<? echo $buyer_email ?>",
				agent_id: "<? echo $agent_id ?>",
        mainPage: "<? echo $mainPage ?>",
				folders: [],
				openFolder: "<? echo $folder ?>"
      };
		},
		componentDidMount: function(){
		  this.getListings();
		},
		getListings: function(action){
		  $.ajax({
			type: "POST",
			url: "get-buyer-listings.php",
			data: {"email": this.state.buyer_email, "agentID": this.state.agent_id},
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
			error: function(){
			  console.log("failed");
			}
		  });
		},
    openFolder: function(name){
		  if(this.state.openFolder != name){ this.setState({openFolder: name}); }
		  else{ this.setState({openFolder: ""}); }
		},
		handleFolder: function(name, event) {
		  var change = {};
		  change[name] = event.target.value;
		  this.setState(change);
		},
    emailFolder: function(name){
      var $dialog =  $("#ajax-box").dialog({
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
          $(this).css("display", "block");
          $("#overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $("#overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				$dialog.dialog('close');
			}.bind(this)

			$("#overlay").show();
			ReactDOM.render(<EmailFolder closeDialog={closeDialog} folder={name} role={this.state.role}/>, $dialog[0]);
    },
    editComment: function(comment, listing, folder){
			var $dialog =  $("#ajax-box").dialog({
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
          $(this).css("display", "block");
          $("#overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
            $("#overlay").hide();
          });
        }
			});
			var closeDialog = function(){
				this.getListings("comment");
				$dialog.dialog('close');
			}.bind(this)

			$("#overlay").show();
			ReactDOM.render(<EditComment closeDialog={closeDialog} comment={comment} listing={listing} folder={folder}/>, $dialog[0]);
		},
    deleteListing: function(listing_num, folder){
			$.get("/controllers/ajax.php", {
				clear_one_saved_from_folder: 'true',
				buyer: this.state.buyer_email,
				delete_id: listing_num,
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
                  <td style={{textAlign: "center", width: 35 + 'px'}}><img className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                  <td style={{textAlign: "center", width: 39 + 'px'}}><img className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                  <td style={{textAlign: "center", width: 37 + 'px'}}><img className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                  <td style={{textAlign: "center", width: 45 + 'px'}}><img className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
                  <td style={{width: 33 + 'px'}}></td>
                  <td style={{width: 290 + 'px'}}>
                    <a style={{cursor: "pointer"}} onClick={this.editComment.bind(this, listing.comments, listing.listing_num, listing.folder)}>{listing.comments} &nbsp;<span id="u16157-13"><i className="fa fa-plus" data-toggle='tooltip' title='edit comment'></i></span></a>
                  </td>
                  <td style={{width: 85 + 'px'}}>{listing.status}</td>
                  <td style={{width: 80 + 'px'}}>{listing.date}</td>
                  <td>
                  <span id="u22086-58"><a style={{cursor: "pointer"}} onClick={this.deleteListing.bind(this, listing.listing_num, listing.folder)}><i className="fa fa-times" data-toggle='tooltip' title='delete'></i></a></span>
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
                      <td style={{width: 120 + 'px'}}>Location: <img className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                      <td style={{width: 120 + 'px'}}>Building: <img className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                      <td style={{width: 105 + 'px'}}>View: <img className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                      <td style={{width: 120 + 'px'}}>Space: <img className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
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
                      <td style={{width: 120 + 'px'}}>Location: <img className='quality2' src={listing.loc} height="16" data-toggle='tooltip' title={listing.locTitle}/></td>
                      <td style={{width: 120 + 'px'}}>Building: <img className='quality2' src={listing.bld} height="16" data-toggle='tooltip' title={listing.bldTitle}/></td>
                    </tr>
                    <tr>
                      <td style={{width: 105 + 'px'}}>View: <img className='quality2' src={listing.vws} height="16" data-toggle='tooltip' title={listing.vwsTitle}/></td>
                      <td style={{width: 120 + 'px'}}>Space: <img className='quality2' src={listing.space} height="16" data-toggle='tooltip' title={listing.spaceTitle}/></td>
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
				  );
				}.bind(this));
				return(
				  <div key={key} className="Text-1 clearfix u16157-235" style={{marginTop: 0}}>
            <div id="u16157-8" className="container-fluid">
              <div className="row">
                <div className="col-md-3 col-sm-3 col-xs-12 folderDetails">
                  {this.state.openFolder == name ? <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><img className="block" id="u16154_img" src="/images/icon_folder_open_blue_1a.png" alt="" width="40" height="37"/></span> : <span id="u16157" style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}><i className="fa fa-folder-o"></i></span> }
                  <span id="u16157-2"><a style={{cursor: "pointer"}} onClick={this.openFolder.bind(this,name)}>&nbsp; {name}</a></span>
                </div>
                <div className="col-md-2 col-sm-1 col-xs-12 folderDetails indent customWidth"><span id="u16157-7">{agent_name}</span></div>
                <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent"><span id="u16157-7">Last updated: {last_update}</span></div>
                <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.emailFolder.bind(this, name)}>email listings</a></span></div>
              </div>
            </div>
					{this.state.openFolder == name ?
					  <div id={key}>
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
                      <th style={{width: 290 + 'px'}}>Comments<br/>click (<span id="u16157-13"><i className="fa fa-plus"></i></span>) to edit</th>
                      <th style={{width: 85 + 'px'}}>Status</th>
                      <th style={{width: 85 + 'px'}}>Date added</th>
                    </tr>
                    </tbody>
                  </table>							  
                  {listings}
                  <div id="u16157-212"><p id="u16157-211">Gold bubble icons evaluate only the listings saved to this folder, and show how they rate compared to one another. When new listings are added, the gold bubbles will update.&nbsp; <span id="u16156-12"><i className="fa fa-question-circle"></i> </span><span id="u16156-13"><a href={"faq.php?section=manage&MP="+this.state.mainPage}>more info</a></span></p></div>
                  <p>&nbsp;</p>
                </div>
						  :
                <div className="Text-1 noSaveMessage"><span>No Saved Listings</span></div>
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
            <NavBar mainPage={this.state.mainPage} />
            <AddressSearch mainPage={this.state.mainPage} />
            <div className="container-fluid folder-section">
              <div className="row">
                <div className="col-md-5 col-sm-5 col-xs-12 pageTitle">
                  <div className="clearfix grpelem" id="u16159-5">
                    <p id="u16159-3"><span id="u16159">My Listing Folders </span><span id="u16159-2">click name to open or edit</span></p>
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-md-12">
                  <div className="clearfix"></div>
                  {this.state.folders.length > 0 ?
                    <div id="folderSection">
                      {folders}
                      {this.state.role == "guest" ? <div id="guestFolderNote" class="Text-1"><p>Note: Your temporary guest folder will be deleted once you leave the site.</p></div> : null }
                    </div>
                  :
                    <div>
                      <div id="loading" className="Text-1"><span>Loading Folders...</span></div>
                      <div id="noFolders" className="Text-1">
                        <span>No Saved Folders</span>
                        <p>&nbsp;</p>
                        {this.state.role == "guest" ? <div id="noFoldersNote"><p>Note: Your temporary guest folder will be created when you perform your first search. The folder will be deleted once you leave the site.</p></div> : null }
                      </div>                      
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
		<BuyerListings />,
		document.getElementById("buyer-listings")
	);

	ReactDOM.render(
		<Footer mainPage={"<? echo $mainPage ?>"} />,
		document.getElementById("footer")
	);
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>