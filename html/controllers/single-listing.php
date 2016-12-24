<?php
session_start();
include_once("dbconfig.php");
include_once("functions.php");
include_once("basicHead.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['buyer'])){
  $email = $_SESSION['email'];
  $role = 'buyer';
}
else if(isset($_SESSION['agent'])){
  $email = $_SESSION['email'];
  $agent_id = $_SESSION['agent_id'];
  $role = 'agent';
}
else{
  $email = $_SESSION['guestID'];
  $role = 'guest';
}

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");

$list_num = $_GET['list_numb'];

if(isset($_GET['newTab'])){ ?>
  <style>#backBtn{ display: none }</style>
<?php }
?>

  <title>HomePik - Listing Details</title>
  <?php include_css("/views/css/single-listing-detail.css"); ?>
</head>
<body>
  <div id="listing"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
  <div id="ajax-box3"></div>
<script type="text/babel">
  var Broker = React.createClass({
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div>
          <div className="text-popups clearfix grpelem" id="u26445-4-2">
            <h4 onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
          <p className="Text-3--2-kern">&nbsp;</p>
          {this.props.broker != '' ? <p className="Text-3--2-kern" style={{textAlign: "left"}}>This listing has been provided courtesy of {this.props.broker}</p> : <p className="Text-3--2-kern">This listing has been provided courtesy of {this.props.contract}</p> }
          <p className="Text-3--2-kern">&nbsp;</p>
          <p className="Text-3--2-kern" style={{textAlign: "left"}}>Contact: {this.props.contact != "" ? <span>{this.props.contact}</span> : <span>Not Available</span>}</p>
          <p className="Text-3--2-kern" style={{textAlign: "left"}}>Email: {this.props.contact_email != "" ? <span>{this.props.contact_email}</span> : <span>Not Available</span>}</p>
          <p className="Text-3--2-kern" style={{textAlign: "left"}}>Phone: {this.props.contact_phone != "" ? <span>{this.props.contact_phone}</span> : <span>Not Available</span>}</p>
          <p className="Text-3--2-kern">&nbsp;</p>
          <p className="Text-3--2-kern" style={{textAlign: "left"}}>Listing Number: {this.props.listing_num}</p>
          <p className="Text-3--2-kern">&nbsp;</p>
          <p className="Text-3--2-kern" style={{cursor: "pointer", fontWeight: "bold", textAlign: "right"}} onClick={this.closePopup}>Close</p>          
        </div>
      );
	  },
	});

	var Send = React.createClass({
	  getInitialState: function(){
      return{
        user: "<?php echo (isset($email) ? $email : "") ?>",
        role: "<?php echo (isset($role) ? $role : "" )?>",
        listing: this.props.listing_num,
        name: "",
        guestEmail: "",
        email: "",
        message: this.props.message,
        agent: false,
        agent2: false,
        friend: false
      };
	  },
	  componentDidMount: function(){
      if(this.props.selected == 'friend'){ this.setState({friend: true}); $( "input[value='friend']" ).attr("checked", true); }
      else if(this.props.selected == 'agent'){ this.setState({agent: true}); $( "input[value='agent']" ).attr("checked", true); }
      else if(this.props.selected == 'agent2'){ this.setState({agent2: true}); $( "input[value='agent2']" ).attr("checked", true); }
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
	  },
	  handleCheckbox: function (name, event) {
      if(name == "friend"){ this.setState({friend: !this.state.friend}); }
      else if(name == "agent"){ this.setState({agent: !this.state.agent}); }
      else if(name == "agent2"){ this.setState({agent2: !this.state.agent2}); }
	  },
	  checkAgent2: function(){
      if(this.props.agent2_email != "" && this.props.agent2_email != null){ return true; }
      else{ return false; }
	  },
	  sendListing: function(event){
      event.preventDefault();

      if(this.state.friend == true){
        if(this.state.role == "guest"){
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.state.email,
            from: this.state.guestEmail,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
        else{
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.state.email,
            from: this.state.user,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
      }

      if(this.state.agent == true){
        if(this.state.role == "guest"){
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.props.agent,
            from: this.state.guestEmail,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
        else{
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.props.agent,
            from: this.state.user,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
      }

      if(this.state.agent2 == true){
        if(this.state.role == "guest"){
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.props.agent2,
            from: this.state.guestEmail,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
        else{
          $.get("/controllers/ajax.php", {
            emailListing: 'true',
            list_num: this.state.listing,
            sendTo: this.props.agent2,
            from: this.state.user,
            comments: this.state.message,
            guestName: this.state.name,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  {this.props.closeDialog()}
                }
              }.bind(this));
            }.bind(this)
          });
        }
      }
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div className="clearfix grpelem">
          <div className="clearfix grpelem" id="pu2951-27">
            <div className="clearfix grpelem" id="u2951-27">
              <h2 className="Subhead-2" id="u2951-2">Email Listing</h2>
              <h4 className="text-popups" id="u2951-3">&nbsp;</h4>
              {this.state.role == "guest" ?
                <div>
                  <h4 className="text-popups" id="u2951-9">
                    <span id="u2951-6">Your Name</span>&nbsp; <input type="text" name="email" value={this.state.name} id="u2950" className="text ui-widget-content ui-corner-all text-popups" placeholder="enter your name" onChange={this.handleChange.bind(this,"name")}></input>
                  </h4>
                  <h4 className="text-popups" id="u2951-9">
                    <span id="u2951-6">Your Email</span>&nbsp;&nbsp; <input type="text" name="email" value={this.state.guestEmail} id="u2950" className="text ui-widget-content ui-corner-all text-popups" placeholder="enter your address" onChange={this.handleChange.bind(this,"guestEmail")}></input>
                  </h4>
                </div>
              :
                null
              }
              <h4 className="text-popups" id="u2951-9">
                <span id="u2951-4">
                  <input type="checkbox" name="sendee" value="friend" onChange={this.handleCheckbox.bind(this, "friend")}></input>
                </span>
                <span id="u2951-5">&nbsp;&nbsp; </span><span id="u2951-6">To a friend</span>&nbsp;&nbsp; <input type="text" name="email" value={this.state.email} id="u2950" className="text ui-widget-content ui-corner-all text-popups" placeholder="enter address" onChange={this.handleChange.bind(this,"email")}></input>
              </h4>
              <h4 className="text-popups" id="u2951-14">
                <span id="u2951-10">
                  <input type="checkbox" name="sendee" value="agent" onChange={this.handleCheckbox.bind(this, "agent")}></input>
                </span>
                <span id="u2951-11">&nbsp;&nbsp; </span><span id="u2951-12">To the agent</span>&nbsp;&nbsp; {this.props.agent_email}
              </h4>
              {this.checkAgent2() ?
                <h4 className="text-popups" id="u2951-14">
                  <span id="u2951-10">
                    <input type="checkbox" name="sendee" value="agent2" onChange={this.handleCheckbox.bind(this, "agent2")}></input>
                  </span>
                  <span id="u2951-11">&nbsp;&nbsp; </span><span id="u2951-12">To the agent</span>&nbsp;&nbsp; {this.props.agent2_email}
                </h4>
              : null }
              <div className="text-popups" id="u2951-15">
                <div className="clearfix grpelem" id="u2954">
                  <textarea name="comments" value={this.state.message} placeholder="enter your message here (optional)" id="u2955" className="text ui-widget-content ui-corner-all text-popups" onChange={this.handleChange.bind(this,"message")}></textarea>
                </div>
              </div>
              <h4 className="text-popups" id="u2951-19">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-20">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-21">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-22">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-22">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-22">&nbsp;</h4>
              <h4 className="text-popups" id="u2951-25" onClick={this.sendListing}><span id="u2951-23">Send </span><span id="u2951-24"><i className="fa fa-chevron-right"></i></span></h4>
            </div>
          </div>
          <div className="text-popups clearfix grpelem" id="u26445-4">
            <h4 onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
        </div>
      );
	  },
	});

  var AgentSave = React.createClass({
	  getInitialState: function(){
      return{
        user: "<?php echo (isset($email) ? $email : "") ?>",
        agent_id: "<?php echo (isset($agent_id) ? $agent_id : "") ?>",
        listing: this.props.listing_num,
        buyers:[],
        folders: this.props.folders,
        selected: [],
        comment: "",
        folder: ""
      };
	  },
	  componentDidMount: function(){
      this.getBuyers();
	  },
	  addComment: function(event){
      this.setState({comment: event.target.value});
	  },
	  handleChange: function(name, event){
      var folders = this.state.selected;
      if((event.target.checked == true) && (folders.indexOf(name) == -1)){ folders.push(name); }
      else if((event.target.checked == false) && (folders.indexOf(name) != -1)){
        var i = folders.indexOf(name);
        if(i != -1) { folders.splice(i, 1); }
      }
      this.setState({selected: folders});
	  },
	  getBuyers: function(){
      $.ajax({
        type: "POST",
        url: "get-buyers.php",
        data: {"agent_id":this.state.agent_id},
        success: function(data){
          var buyers = JSON.parse(data);
          this.setState({buyers: buyers});
        }.bind(this)
      });
	  },
	  saveListing: function(){
      if(this.state.selected < 1){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons: {
            Ok: function(){ $(this).dialog("destroy"); }
          },
          close: function() { $( this ).dialog( "destroy" ); },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('messages.php #saveListingAgent',function(){
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        $.get("/controllers/ajax.php", {
          agentSave: 'true',
          list_num: this.state.listing,
          comments: this.state.comment,
          buyers: this.state.selected,
          agent_id: this.state.agent_id,
          success: function(result){
            $('.fa-heart').addClass('blue');
            var ajaxStop = 0;
            $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              {this.props.closeDialog()}
            }
            }.bind(this));
          }.bind(this)
        });
      }
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      var buyers = this.state.buyers.map(function(buyer){
        return(
          <h4 className="text-popups" id="u1330-10" key={buyer.id}><span id="u1330-7"><input type="checkbox" name="buyer" value={buyer.email} onChange={this.handleChange.bind(this, buyer.email)}/></span><span id="u1330-8"> </span><span id="u1330-9">&nbsp; {buyer.last_name}, {buyer.first_name}</span></h4>
        );
      }.bind(this));
      return(
        <div>
          <div className="text-popups clearfix grpelem" id="u26451-5e">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
          <h2 className="Subhead-2" id="u1330-2">Save Listing</h2>
          <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-5">Save this listing to (check as many as apply):</h4>
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-10"><span id="u1330-7"><input type="checkbox" name="buyer" value={this.state.user} onChange={this.handleChange.bind(this, this.state.user)}/></span><span id="u1330-8"> </span><span id="u1330-9">&nbsp; My Agent Folder</span></h4>
          {this.state.buyers.length > 0 ? <div>{buyers}</div> : null }
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-5" style={{paddingBottom: 10 + 'px'}}>Add a comment:</h4>
          <textarea value={this.comment} style={{border: 1 + 'px solid #646464', width: 350 + 'px', height: 100 + 'px'}} onChange={this.addComment}></textarea>
          <h4 className="text-popups" id="u1330-22">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-21" onClick={this.saveListing}><span id="u1330-19">Submit </span><span id="u1330-20"><i className="fa fa-chevron-right"></i></span></h4>
        </div>
      );
	  },
	});

	var BuyerSave = React.createClass({
	  getInitialState: function(){
      return{
        user: "<?php echo (isset($email) ? $email : "") ?>",
        listing: this.props.listing_num,
        selected: [],
        comment: "",
        folder: ""
      };
	  },
	  addComment: function(event){
      this.setState({comment: event.target.value});
	  },
	  handleChange: function(name, event){
      var folders = this.state.selected;
      if((event.target.checked == true) && (folders.indexOf(name) == -1)){ folders.push(name); }
      else if((event.target.checked == false) && (folders.indexOf(name) != -1)){
        var i = folders.indexOf(name);
        if(i != -1){ folders.splice(i, 1); }
      }
      this.setState({selected: folders});
	  },
	  saveListing: function(){
      if(this.state.selected < 1){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
          buttons: {
            Ok: function(){ $(this).dialog("destroy"); }
          },
          close: function() { $( this ).dialog( "destroy" ); },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box2").dialog('close');
            });
          }
        });
        $('#ajax-box2').load('messages.php #saveListing',function(){
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        $.get("/controllers/ajax.php", {
          save: 'true',
          list_num: this.state.listing,
          comments: this.state.comment,
          folders: this.state.selected,
          success: function(result){
            $('.fa-heart').addClass('blue');
            var ajaxStop = 0;
            $(document).ajaxStop(function() {
              if(ajaxStop == 0){
                ajaxStop++;
                {this.props.closeDialog()}
              }
            }.bind(this));
          }.bind(this)
        });
      }
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      var folders = this.props.folders.map(function(folder){
        return(
          <h4 className="text-popups" id="u1330-10" key={folder.id}><span id="u1330-7"><input type="checkbox" name="folders" value={folder.name} onChange={this.handleChange.bind(this, folder.name)}/></span><span id="u1330-8"> </span><span id="u1330-9">&nbsp; {folder.name}</span></h4>
        );
      }.bind(this));
      return(
        <div>
          <div className="text-popups clearfix grpelem" id="u26451-5e">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
          <h2 className="Subhead-2" id="u1330-2">My Listing Folders</h2>
          <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-5">Save this listing to (check as many as apply):</h4>
          <h4 className="text-popups" id="u1330-6">&nbsp;</h4>
          {folders}
          <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-5" style={{paddingBottom: 10 + 'px'}}>Add a comment:</h4>
          <textarea value={this.comment} style={{border: 1 + 'px solid #646464', width: 350 + 'px', height: 100 + 'px'}} onChange={this.addComment}></textarea>
          <h4 className="text-popups" id="u1330-22">&nbsp;</h4>
          <h4 className="text-popups" id="u1330-21" onClick={this.saveListing}><span id="u1330-19">Submit </span><span id="u1330-20"><i className="fa fa-chevron-right"></i></span></h4>
        </div>
      );
	  },
	});

	var AgentInfo = React.createClass({
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div>
          <div className="text-popups clearfix grpelem" id="u26451-5e">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
          <div className="clip_frame clearfix colelem" id="u15594">
            <img className="position_content" id="u15594_img" src={this.props.agent_photo} alt="" width="880" height="1318"/>
          </div>
          <div className="clearfix colelem" id="u3471-11">
            <h2 className="Subhead-2" id="u3471-4">Bio: <span id="u3471-2">{this.props.agent_firstname} {this.props.agent_lastname}</span></h2>
            <h4 className="text-popups" id="u3471-5">&nbsp;</h4>
            {this.props.agent_bio != "" ? <h3 className="Paragraph-Style-1">{this.props.agent_bio}</h3> : <h3 className="Paragraph-Style-1">Agent Bio Unavailable</h3> }
            <h3 className="Paragraph-Style-1" id="u3471-9"><a><span id="u3471-8" style={{cursor: "pointer"}} onClick={this.closePopup}>Close</span></a></h3>
          </div>
        </div>
      );
	  }
	});

	var EmailAgent = React.createClass({
	  getInitialState: function(){
      return{
        user: "<?php echo (isset($email) ? $email : "") ?>",
        listing: this.props.listing_num,
        name: "",
        email: "",
        message: "Please send me information about this listing."
      };
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
	  },
	  sendEmail: function(){
      $.get("/controllers/ajax.php", {
        contact: 'true',
        list_num: this.state.listing,
        manager: this.props.agent_email,
        comments: this.state.message,
        guestName: this.state.name,
        guestEmail: this.state.email,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              {this.props.closeDialog()}
            }
          }.bind(this));
        }.bind(this)
      });
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div id="contact-form" title='Contact Bellmarc'>
          <div className="text-popups clearfix grpelem" id="u26451-5e">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
          <div>
            {this.props.role == "guest" ?
              <div>
                <p className="Text-3--2-kern" style={{textAlign: "left"}}>Your Name:</p>
                <input type="text" name="guestName" value={this.state.name} id="guestName" className="text ui-widget-content ui-corner-all" style={{width: 250 + 'px'}} onChange={this.handleChange.bind(this, "name")}></input>
                <p className="Text-3--2-kern">&nbsp;</p>
                <p className="Text-3--2-kern" style={{textAlign: "left"}}>Your Email:</p>
                <input type="text" name="guestEmail" value={this.state.email} id="guestEmail" className="text ui-widget-content ui-corner-all" style={{width: 250 + 'px'}} onChange={this.handleChange.bind(this, "email")}></input>
              </div>
            : null }
            <p className="Text-3--2-kern">&nbsp;</p>
            <textarea name="contactComments" value={this.state.message} id="contactComments" className="text ui-widget-content ui-corner-all" style={{width: 515 + 'px', height: 130 + 'px'}} onChange={this.handleChange.bind(this, "message")}></textarea>
            <p className="Text-3--2-kern">&nbsp;</p>
            <button type="submit" name="emailAgentBuyer" id="emailAgentSubmit" onClick={this.sendEmail}>Send<i id="arrow" className="fa fa-chevron-right"></i></button>
          </div>
        </div>
      );
	  }
	});

	var SpaceFactor = React.createClass({
    componentDidMount:function(){
      $("body").bind('click',function(){ $(".spaceFactor").hide(); })
      $('.spaceFactor').click(function(event){ event.stopPropagation(); });
    },
	  closePopup: function(){
      $(".spaceFactor").hide();
	  },
	  render: function(){
      return(
        <div className="clearfix colelem spaceFactor" id="pu26811-59" style={{display: "none"}}>
          <div className="clearfix grpelem" id="u26986">
            <div className="clip_frame grpelem" id="u26989">
              <img className="block" id="u26989_img" src="/images/box_270x200.png" alt="" width="300" height="300"/>
            </div>
            <div className="text-popups clearfix grpelem" id="u26451-5c">
              <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
            </div>
            <div className="clearfix grpelem" id="u26987">
              <div className="Text-3--2-kern clearfix grpelem" id="u26988-4" style={{textAlign: "left"}}>
                <p>Includes living room, dining room, all bedrooms and other significant rooms but does not include the kitchen, hallways and vestibule. See FAQs for more information.</p>
              </div>
            </div>
          </div>
        </div>
      );
	  }
	});

	var ImpliedFootage = React.createClass({
    componentDidMount:function(){
      $("body").bind('click',function(){ $(".impliedFootage").hide(); })
      $('.impliedFootage').click(function(event){ event.stopPropagation(); });
    },
	  closePopup: function(){
      $(".impliedFootage").hide();
	  },
	  render: function(){
      return(
        <div className="clearfix colelem impliedFootage" id="pu27204-59" style={{display: "none"}}>
          <div className="clearfix grpelem" id="u27035">
            <div className="clip_frame grpelem" id="u27037">
              <img className="block" id="u27037_img" src="/images/box_246x194.png" alt="" width="300" height="300"/>
            </div>
            <div className="text-popups clearfix grpelem" id="u26451-5c" style={{left: 250 + 'px'}} >
              <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
            </div>
            <div className="Text-3--2-kern clearfix grpelem" id="u27036-6">
              <p>Uses a multiplier of 1.3 to compensate for the missing areas as noted in the space factor. <br/>See FAQs for more information.</p>
            </div>
          </div>
        </div>
      );
	  }
	});

	var Slideshow = React.createClass({
	  getInitialState: function(){
      return{
        photos: this.props.photos,
        index: this.props.start_photo,
        numPhotos: this.props.num_photos
      };
	  },
    componentDidMount: function(){
      $("body").keydown(function(e) {
        if (e.keyCode == 37) { this.previous(); }
        else if (e.keyCode == 39) { this.next(); }
      }.bind(this));
    },
	  next: function(){
      var index = parseInt(this.state.index);
      index++;
      if(this.state.numPhotos < index + 1){ this.setState({index: 0}); }
      else{ this.setState({index: index}); }
	  },
	  previous: function(){
      var index = parseInt(this.state.index);
      index--;
      if(0 > index){ index = parseInt(this.state.numPhotos) - 1; }
      this.setState({index: index});
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div>
          <div className="clip_frame grpelem" id="u1963">
            <img className="block" id="u1963_img" src={this.state.photos[this.state.index]} alt="" width="1196" height="797"/>
          </div>
          <div className="clearfix grpelem" id="u1969-4">
            <p style={{cursor: "pointer"}} onClick={this.previous}><i className="fa fa-angle-left"></i></p>
          </div>
          <div className="clearfix grpelem" id="u1970-4">
            <p style={{cursor: "pointer"}} onClick={this.next}><i className="fa fa-angle-right"></i></p>
          </div>
          <div className="clearfix grpelem" id="u1971-5">
            <p style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></p>
          </div>
        </div>
      );
	  }
	});

	var CostEstimator = React.createClass({
	  getInitialState: function(){
      return{
        bid: this.props.price,
        maint: this.props.maint,
        taxes: this.props.taxes,
        interest: "4.25",
        term: "30",
        payment: "25",
        cashDown: "",
        monthlyPay: ""
      };
	  },
	  componentDidMount: function(){
      setTimeout(function(){this.calculate()}.bind(this), 1000);
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value
      this.setState(change);
      this.calculate(name, event.target.value);
	  },
	  calculate:function(name, value){
      if(name == "bid"){ var price = value; } else{ var price = this.state.bid; }
      if(name == "monthly"){ var monthly = value; } else{ var monthly = this.state.maint; }
      if(name == "taxes"){ var taxes = value; } else{ var taxes = this.state.taxes; }
      if(name == "interest"){ var interest = value; } else{ var interest = this.state.interest; }
      if(name == "term"){ var term = value; } else{ var term = this.state.term; }
      if(name == "payment"){ var payment = value; } else{ var payment = this.state.payment; }
      
      while (price.indexOf(",") != -1){ price = price.replace(",",""); }
      while (monthly.indexOf(",") != -1){ monthly = monthly.replace(",",""); }
      while (taxes.indexOf(",") != -1){ taxes = taxes.replace(",",""); }
      while (interest.indexOf(",") != -1){ interest = interest.replace(",",""); }
      while (term.indexOf(",") != -1){ term = term.replace(",",""); }
      while (payment.indexOf(",") != -1){ payment = payment.replace(",",""); }

      // Price * ( Cash Down Percentage / 100 )
      var cashDown = (price * (payment / 100));
      cashDown = cashDown.toFixed(2);
      var cd = cashDown.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      this.setState({cashDown: cd});

      // ( ( (Price + Interest) - Cash Down Payment ) / Years ) + Monthly + Tax
      var total = (((parseInt(price) + (price * (interest /100))) - cashDown ) / (term * 12)) + parseInt(monthly) + parseInt(taxes);
      total = total.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      this.setState({monthlyPay: total});
	  },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div>
          <div className="clearfix grpelem" id="pu17925-47">
            <div className="clearfix grpelem" id="u17925-47">
              <h2 className="Subhead-2" id="u17925-2">Cost estimator</h2>
              <h4 className="text-popups" id="u17925-3">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-4">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-5">&nbsp;</h4>
              <h4 className="text-popups">Apartment address <span id="u17925-7">{this.props.address}</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups">Asking price <span id="u17925-11">or</span> your bid price &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>$</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups">Monthly common/maintenance charge &nbsp;<span>$</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups">Monthly real estate tax (condo only) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>$</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups">Interest rate <span style={{float: "right"}}>%</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-28"><span id="u17925-24">Length of term</span><span className="text-popups" style={{float: "right"}}>years</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-34"><span id="u17925-30">Cash down payment percent</span><span className="text-popups" style={{float: "right"}}>%</span></h4>
              <h4 className="text-popups">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-37">Cash down payment in cash <span style={{float: "right"}}>${this.state.cashDown}</span></h4>
              <h4 className="text-popups" id="u17925-38">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-40">Your estimated monthly cost <span style={{float: "right"}}>${this.state.monthlyPay}</span></h4>
              <h4 className="text-popups" id="u17925-43">&nbsp;</h4>
              <h4 className="text-popups" id="u17925-45"><a><span id="u17925-44" style={{cursor: "pointer"}} onClick={this.closePopup}>Close</span></a></h4>
            </div>
            <div className="clip_frame grpelem" id="u17926">
              <img className="block" id="u17926_img" src="/images/button_calculator.jpg" alt="" width="66" height="66"/>
            </div>
            <div className="clearfix grpelem" id="u5421">
              <input className="grpelem c-col-r" id="u5422" value={this.state.bid} onChange={this.handleChange.bind(this, "bid")}></input>
            </div>
            <div className="clearfix grpelem" id="u5425">
              <input className="grpelem c-col-r" id="u5426" value={this.state.maint} onChange={this.handleChange.bind(this, "maint")}></input>
            </div>
            <div className="clearfix grpelem" id="u5427">
              <input className="grpelem c-col-r" id="u5428" value={this.state.taxes} onChange={this.handleChange.bind(this, "taxes")}></input>
            </div>
            <div className="clearfix grpelem" id="u5375">
              <input className="grpelem c-col-r" id="u5376" value={this.state.interest} onChange={this.handleChange.bind(this, "interest")}></input>
            </div>
            <div className="clearfix grpelem" id="u22501">
              <input type="number" min="0" max="100" defaultValue="30" className="grpelem c-col-r" id="u22502" onChange={this.handleChange.bind(this, "term")}></input>
            </div>
            <div className="clearfix grpelem" id="u22507">
              <input type="number" min="0" max="100" defaultValue="25" className="grpelem c-col-r" id="u22508" onChange={this.handleChange.bind(this, "payment")}></input>
            </div>
          </div>
          <div className="text-popups clearfix grpelem" id="u17921-4">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
          </div>
        </div>
      );
	  }
	});

  var SPVFilters = React.createClass({
	  getInitialState: function(){
      return{
        listing: this.props.listing,
        price: this.props.details['price'],
        min_price: "",
        max_price: "",
        min_maint: this.props.details['monthly'],
        min_bed: this.props.details['bed'],
        min_bath: this.props.details['bath'],
        min_bld: this.props.details['bld'],
        min_loc: this.props.details['loc'],
        min_vws: this.props.details['vws'],
        min_floor: this.props.details['floor'],
        lrd_one: 0,
        lrd_two: 0,
        lrdt: 0,
        mbd_one: 0,
        mbd_two: 0,
        mbdt: 0,
        drd_one: 0,
        drd_two: 0,
        drdt: 0,
        bbd_one: 0,
        bbd_two: 0,
        bbdt: 0,
        bbbd_one: 0,
        bbbd_two: 0,
        bbbdt: 0,
        min_space: this.props.details['space'],
        type: "Active",
        location: "Local",
        condition: "Level 3",
        demand: "Level 2",
        interest: 10,
        condo_only: "no",
        amenities: []
      };
	  },
    componentDidMount: function(){
      this.calculatePriceRange(this.state.interest);
      $("input[value='Local']").attr("checked", true);
      $("input[value='Active']").attr("checked", true);
      $("input[name='condition'][value='Level 3']").attr("checked", true);
      $("input[name='demand'][value='Level 2']").attr("checked", true);
      $("input[name='condos_only'][value='no']").attr("checked", true);
      $("input[name='location_grade'][value='"+this.state.min_loc+"']").attr("checked", true);
      $("input[name='building_grade'][value='"+this.state.min_bld+"']").attr("checked", true);
      $("input[name='view_grade'][value='"+this.state.min_vws+"']").attr("checked", true);
      if(this.props.details['amenities']['fireplace'] == "1" && this.props.details['amenities']['elevator'] == "1"){
        $("input[value='fireplace']").attr("checked", true);
        $("input[value='elevator']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("fireplace");        
        amenities.push("elevator");
        this.setState({amenities: amenities});
      }
      else if(this.props.details['amenities']['fireplace'] == "1" && this.props.details['amenities']['elevator'] == "0"){
        $("input[value='fireplace']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("fireplace");        
        this.setState({amenities: amenities});
      }
      else if(this.props.details['amenities']['fireplace'] == "0" && this.props.details['amenities']['elevator'] == "1"){
        $("input[value='elevator']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("elevator");
        this.setState({amenities: amenities});
      }
      if(this.props.details['amenities']['balcony'] == "1"){
        $("input[value='balcony']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("balcony");
        this.setState({amenities: amenities});      
      }
      else if(this.props.details['amenities']['terrace'] == "1"){
        $("input[value='terrace']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("terrace");
        this.setState({amenities: amenities});      
      }
      else if(this.props.details['amenities']['garden'] == "1" || this.props.details['amenities']['roofd'] == "1"){
        $("input[value='garden']").attr("checked", true);
        var amenities = this.state.amenities;
        amenities.push("garden");
        this.setState({amenities: amenities});      
      }
      else{
        $("input[value='none']").attr("checked", true);
      }
    },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value
      this.setState(change);
      if(name == "price"){ this.calculatePriceRange(this.state.interest); }
      else if(name == "interest"){ this.calculatePriceRange(event.target.value); }
      else if(name == "lrd_one" || name == "lrd_two" || name == "mbd_one" || name == "mbd_two" || name == "drd_one" || name == "drd_one" || name == "bbd_one" || name == "bbd_two" || name == "bbbd_one" || name == "bbbd_two"){ setTimeout(function(){ this.calculateDimensions(); }.bind(this), 1000); }
	  },
    handleAmenityChange: function(event) {
      var amenity = event.target.value
      var amenities = this.state.amenities;
      
      if(amenity == "fireplace" || amenity == "elevator"){
        if(amenities.indexOf(amenity) == -1){ amenities.push(amenity); }
        else{ amenities.splice(amenities.indexOf(amenity), 1); }
      }
      else if(amenity == "none" || amenity == "balcony" || amenity == "terrace" || amenity == "setBack" || amenity == "garden"){
        if(amenities.indexOf(amenity) == -1){ amenities.push(amenity); }
        if(amenity != "none" && amenities.indexOf("none") != -1){ amenities.splice(amenities.indexOf("none"), 1); }
        if(amenity != "balcony" && amenities.indexOf("balcony") != -1){ amenities.splice(amenities.indexOf("balcony"), 1); }
        if(amenity != "terrace" && amenities.indexOf("terrace") != -1){ amenities.splice(amenities.indexOf("terrace"), 1); }
        if(amenity != "setBack" && amenities.indexOf("setBack") != -1){ amenities.splice(amenities.indexOf("setBack"), 1); }
        if(amenity != "garden" && amenities.indexOf("garden") != -1){ amenities.splice(amenities.indexOf("garden"), 1); }
      }
      this.setState({amenities: amenities});
	  },
    calculatePriceRange: function(interest){
      var price = this.state.price;
      while(price.indexOf(",") != -1){ price = price.replace(",",""); }
      price = Number(price);
      
      var minPriceRange = price - (price * (interest / 100));
      minPriceRange = minPriceRange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      this.setState({min_price: minPriceRange});
      
      var maxPriceRange = price + (price * (interest / 100));
      maxPriceRange = maxPriceRange.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      this.setState({max_price: maxPriceRange});
    },
    calculateDimensions: function(){
      var ltotal = this.state.lrd_one * this.state.lrd_two;
      this.setState({lrdt: ltotal});
      var mtotal = this.state.mbd_one * this.state.mbd_two;
      this.setState({mbdt: mtotal});
      var dtotal = this.state.drd_one * this.state.drd_two;
      this.setState({drdt: dtotal});
      var bbtotal = this.state.bbd_one * this.state.bbd_two;
      this.setState({bbdt: bbtotal});
      var bbbtotal = this.state.bbbd_one * this.state.bbbd_two;
      this.setState({bbbdt: bbbtotal});
      
      var stotal = ltotal + mtotal + dtotal + bbtotal + bbbtotal;
      this.setState({min_space: stotal});
    },
    submit: function(){
      setTimeout(function(){ this.closePopup(); }.bind(this), 3000);
    },
	  closePopup: function(){
      {this.props.closeDialog()}
	  },
	  render: function(){
      return(
        <div>
          <div className="clearfix grpelem" id="SPV-div">
            <div className="clearfix grpelem" id="SPV-content">
              <h2 className="Subhead-2" id="u17925-2">Selection Portfolio Valuation</h2>
              <h4 className="text-popups" id="u17925-3">&nbsp;</h4>
              <form action="spv.php" method="POST" target="_blank" onSubmit={this.submit}>
                <div className="informationRow">
                  <h4 className="text-popups" id="address"><span className="title">Address:</span> {this.props.details['address']}</h4>
                  <h4 className="text-popups" id="listing_price"><span className="title">Price:</span> $<input type="text" id="priceInput" name="price" value={this.state.price} onChange={this.handleChange.bind(this, "price")} /></h4>
                  <h4 className="text-popups" id="bed"><span className="title">Bedrooms:</span> <input type="number" id="bedroomInput" name="min_bed" min="0" max="9" value={this.state.min_bed} onChange={this.handleChange.bind(this, "min_bed")} /></h4>
                </div>
                <div className="informationRow">
                  <h4 className="text-popups" id="apt"><span className="title">Apartment:</span> {this.props.details['apt']}</h4>
                  <h4 className="text-popups" id="floor"><span className="title">Floor:</span> <input type="number" id="minFloorInput" name="min_floor" min="0" max="30" defaultValue={this.state.min_floor} onChange={this.handleChange.bind(this, "min_floor")} /></h4>
                  <h4 className="text-popups" id="monthly"><span className="title">Monthly:</span> $<input type="text" id="monthlyInput" name="min_maint" value={this.state.min_maint} onChange={this.handleChange.bind(this, "min_maint")} /></h4>
                  <h4 className="text-popups" id="bath"><span className="title">Baths:</span> <input type="number" id="bathInput" name="min_bath" min="0" max="10" value={this.state.min_bath} onChange={this.handleChange.bind(this, "min_bath")} /></h4>
                </div>
                  
                <h4 className="text-popups">&nbsp;</h4>
                <ul className="nav nav-tabs text-popups">
                  <li role="presentation" className="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main</a></li>
                  <li role="presentation"><a href="#location" aria-controls="location" role="tab" data-toggle="tab">Location</a></li>
                  <li role="presentation"><a href="#building" aria-controls="building" role="tab" data-toggle="tab">Building</a></li>
                  <li role="presentation"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">View</a></li>
                  <li role="presentation"><a href="#size" aria-controls="size" role="tab" data-toggle="tab">Size</a></li>
                </ul>
                
                <div className="tab-content">
                  <div role="tabpanel" className="tab-pane active" id="main">
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="area">
                        <span className="title">Area:</span><br/>
                        <input type="radio" name="listing_location" value="Local" onChange={this.handleChange.bind(this, 'location')} /> Local<br/>
                        <input type="radio" name="listing_location" value="All" onChange={this.handleChange.bind(this, 'location')}/> All Manahattan<br/>
                        <input type="radio" name="listing_location" value="Zip" onChange={this.handleChange.bind(this, 'location')}/> With zip {this.props.details['zip']}
                      </h4>
                      <h4 className="text-popups" id="type">
                        <span className="title">Listing Type:</span><br/>
                        <input type="radio" name="listing_type" value="Active" onChange={this.handleChange.bind(this, 'type')} /> Active<br/>
                        <input type="radio" name="listing_type" value="Historical" onChange={this.handleChange.bind(this, 'type')}/> Historical<br/>
                        <input type="radio" name="listing_type" value="Both" onChange={this.handleChange.bind(this, 'type')}/> Both
                      </h4>
                      <h4 className="text-popups">
                        <span className="title">Price Range:</span><br/>
                        <span id="minPriceRange">${this.state.min_price}</span> To <span id="maxPriceRange">${this.state.max_price}</span><br/>
                        <input type="number" id="priceRangeInterest" name="interest" min="5" max="100" defaultValue="10" step="5" onChange={this.handleChange.bind(this, "interest")} /></h4>  
                    </div>
                    
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="condition">
                        <span className="title">Condition:</span><br/>
                        <input type="radio" name="condition" value="Level 1" onChange={this.handleChange.bind(this, 'type')}/> Complete rehabilitation required <br/>
                        <input type="radio" name="condition" value="Level 2" onChange={this.handleChange.bind(this, 'type')}/> Obsolete components in need of replacement <br/>
                        <input type="radio" name="condition" value="Level 3" onChange={this.handleChange.bind(this, 'type')}/> Premium quality high luxury <br/>
                        <input type="radio" name="condition" value="Level 4" onChange={this.handleChange.bind(this, 'type')}/> High quality decorator improvements <br/>
                        <input type="radio" name="condition" value="Level 5" onChange={this.handleChange.bind(this, 'type')}/> Decorator showcase, triple mint condition.
                      </h4>
                      <h4 className="text-popups" id="demand">
                        <span className="title">Demand:</span><br/>
                        <input type="radio" name="demand" value="Level 1" onChange={this.handleChange.bind(this, 'type')}/> Appreciating market <br/>
                        <input type="radio" name="demand" value="Level 2" onChange={this.handleChange.bind(this, 'type')}/> Stable market <br/>
                        <input type="radio" name="demand" value="Level 3" onChange={this.handleChange.bind(this, 'type')}/> Slightly declining market <br/>
                        <input type="radio" name="demand" value="Level 4" onChange={this.handleChange.bind(this, 'type')}/> Dramatically declining market
                      </h4>
                    </div>            
                    <h4 className="text-popups" id="u17925-3">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="condos">
                        <span className="title">Condos only:</span><br/>
                        <input type="radio" name="condos_only" value="yes" onChange={this.handleChange.bind(this, 'condo_only')}/> Yes<br/>
                        <input type="radio" name="condos_only" value="no" onChange={this.handleChange.bind(this, 'condo_only')}/> No
                      </h4>
                      <h4 className="text-popups" id="amenity_checkboxes">
                        <span className="title">Amenities:</span><br/>
                        <input type="checkbox" name="amenities[]" value="fireplace" onChange={this.handleAmenityChange}/> Fireplace<br/>
                        <input type="checkbox" name="amenities[]" value="elevator" onChange={this.handleAmenityChange}/> Elevator
                      </h4>
                      <h4 className="text-popups" id="private_outdoors">
                        <span className="title">Private Outdoors:</span><br/>
                        <input type="radio" name="outdoors_amenity" value="none" onChange={this.handleAmenityChange}/> None <br/>
                        <input type="radio" name="outdoors_amenity" value="balcony" onChange={this.handleAmenityChange}/> Balcony <br/>
                        <input type="radio" name="outdoors_amenity" value="terrace" onChange={this.handleAmenityChange}/> Terrace <br/>
                        <input type="radio" name="outdoors_amenity" value="setBack" onChange={this.handleAmenityChange}/> Setback Terrace <br/>
                        <input type="radio" name="outdoors_amenity" value="garden" onChange={this.handleAmenityChange}/> Private garden / Roof deck
                      </h4>
                    </div>                    
                  </div>
                  <div role="tabpanel" className="tab-pane" id="location">
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="loc">Location:</h4>
                      <h4 className="text-popups" id="loc_grade"><span className="title">Grade:</span> <input type="text" id="minLocInputSet" name="min_loc" value={this.state.min_loc} disabled="disabled" /></h4>
                    </div>
                    <div className="text-popups">
                      <input type="radio" name="location_grade" value="10" onChange={this.handleChange.bind(this, 'min_loc')}/> Internationally renown / near major park<br/>
                      <input type="radio" name="location_grade" value="9" onChange={this.handleChange.bind(this, 'min_loc')}/> Residential area close to major park<br/>
                      <input type="radio" name="location_grade" value="8" onChange={this.handleChange.bind(this, 'min_loc')}/> Residential area / near local park or river<br/>
                      <input type="radio" name="location_grade" value="7" onChange={this.handleChange.bind(this, 'min_loc')}/> Residential area / neighborhood amenities<br/>
                      <input type="radio" name="location_grade" value="6" onChange={this.handleChange.bind(this, 'min_loc')}/> Quiet residential street<br/>
                      <input type="radio" name="location_grade" value="5" onChange={this.handleChange.bind(this, 'min_loc')}/> Active commercial street / Residential Area<br/>
                      <input type="radio" name="location_grade" value="4" onChange={this.handleChange.bind(this, 'min_loc')}/> Active commercial street<br/>
                      <input type="radio" name="location_grade" value="3" onChange={this.handleChange.bind(this, 'min_loc')}/> Emerging residential area<br/>
                      <input type="radio" name="location_grade" value="2" onChange={this.handleChange.bind(this, 'min_loc')}/> New developing market<br/>
                      <input type="radio" name="location_grade" value="1" onChange={this.handleChange.bind(this, 'min_loc')}/> All locations
                    </div>
                  </div>
                  <div role="tabpanel" className="tab-pane" id="building">
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="bld">Building:</h4>
                      <h4 className="text-popups" id="bld_grade"><span className="title">Grade:</span> <input type="text" id="minBldInputSet" name="min_bld" value={this.state.min_bld} disabled="disabled" /></h4>
                    </div>
                    <div className="text-popups">
                      <input type="radio" name="building_grade" value="10" onChange={this.handleChange.bind(this, 'min_bld')}/> International renown<br/>
                      <input type="radio" name="building_grade" value="9" onChange={this.handleChange.bind(this, 'min_bld')}/> Locally renowned building or new construction with full services<br/>
                      <input type="radio" name="building_grade" value="8" onChange={this.handleChange.bind(this, 'min_bld')}/> Full service building with amenities<br/>
                      <input type="radio" name="building_grade" value="7" onChange={this.handleChange.bind(this, 'min_bld')}/> Doorman building with amenities<br/>
                      <input type="radio" name="building_grade" value="6" onChange={this.handleChange.bind(this, 'min_bld')}/> Doorman building &mdash;no amenities<br/>
                      <input type="radio" name="building_grade" value="5" onChange={this.handleChange.bind(this, 'min_bld')}/> Elevator building in good condition<br/>
                      <input type="radio" name="building_grade" value="4" onChange={this.handleChange.bind(this, 'min_bld')}/> Elevator building in fair condition<br/>
                      <input type="radio" name="building_grade" value="3" onChange={this.handleChange.bind(this, 'min_bld')}/> Walkup in good condition<br/>
                      <input type="radio" name="building_grade" value="2" onChange={this.handleChange.bind(this, 'min_bld')}/> Walkup in fair condition<br/>
                      <input type="radio" name="building_grade" value="1" onChange={this.handleChange.bind(this, 'min_bld')}/> All buildings
                    </div>
                  </div>
                  <div role="tabpanel" className="tab-pane" id="view">
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 className="text-popups" id="vws">Views:</h4>
                      <h4 className="text-popups" id="vws_grade"><span className="title">Grade:</span> <input type="text" id="minVwsInputSet" name="min_vws" value={this.state.min_vws} disabled="disabled" /></h4>
                    </div>
                    <div className="text-popups">
                      <input type="radio" name="view_grade" value="10" onChange={this.handleChange.bind(this, 'min_vws')}/> Cityscape and Central Park views<br/>
                      <input type="radio" name="view_grade" value="9" onChange={this.handleChange.bind(this, 'min_vws')}/> Cityscape and river or park views<br/>
                      <input type="radio" name="view_grade" value="8" onChange={this.handleChange.bind(this, 'min_vws')}/> Cityscape views<br/>
                      <input type="radio" name="view_grade" value="7" onChange={this.handleChange.bind(this, 'min_vws')}/> Rooftop views<br/>
                      <input type="radio" name="view_grade" value="6" onChange={this.handleChange.bind(this, 'min_vws')}/> Street view or interior garden, bright<br/>
                      <input type="radio" name="view_grade" value="5" onChange={this.handleChange.bind(this, 'min_vws')}/> Street view or interior garden, moderate light<br/>
                      <input type="radio" name="view_grade" value="4" onChange={this.handleChange.bind(this, 'min_vws')}/> Interior courtyard or area without view but bright<br/>
                      <input type="radio" name="view_grade" value="3" onChange={this.handleChange.bind(this, 'min_vws')}/> Interior courtyard or area with moderate light<br/>
                      <input type="radio" name="view_grade" value="2" onChange={this.handleChange.bind(this, 'min_vws')}/> Indirect light<br/>
                      <input type="radio" name="view_grade" value="1" onChange={this.handleChange.bind(this, 'min_vws')}/> All properties
                    </div>
                  </div>
                  <div role="tabpanel" className="tab-pane" id="size">
                    <h4 className="text-popups">&nbsp;</h4>
                    <h4 className="text-popups" id="value_rooms">Value Rooms</h4>
                    <h4 className="text-popups">The space factor consists of an accumulation of the measurements in the Living Room, Dining Room, Master Bedroom
                    and 2nd and 3rd Bedrooms. This is more accurate than gross footage since it is verifiable. The Space Factor generally accounts for approximately
                    90% of the usable area within a studio or 1 Bedroom apartment. It accounts for approximately 85% of the usable area in two and three bedroom apartments.</h4>
                    <h4 className="text-popups">&nbsp;</h4>
                    <div className="informationRow">
                      <h4 id="label" className="text-popups">Primary<br/>Value<br/>Rooms</h4>
                      <table id="dimensionTable">
                        <colgroup><col width="175"/><col width="145"/><col width="70"/></colgroup>
                        <tbody>
                          <tr>
                            <td><span className="text-popups">&nbsp;</span></td>
                            <td><span className="text-popups">&nbsp;</span></td>
                            <td><span className="text-popups">SQ.Feet</span></td>
                          </tr>
                          <tr>
                            <td><span className="text-popups">Living Room</span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionInput" name="living_dimension[]" value={this.state.lrd_one} onChange={this.handleChange.bind(this, "lrd_one")} /> x <input type="text" className="dimensionInput" name="living_dimension[]" value={this.state.lrd_two} onChange={this.handleChange.bind(this, "lrd_two")} /></span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionTotal" name="living_dimension[]" value={this.state.lrdt} disabled="disabled"/></span></td>
                          </tr>
                          <tr>
                            <td><span className="text-popups">Master Bedroom</span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionInput" name="master_dimension[]" value={this.state.mbd_one} onChange={this.handleChange.bind(this, "mbd_one")} /> x <input type="text" className="dimensionInput" name="master_dimension[]" value={this.state.mbd_two} onChange={this.handleChange.bind(this, "mbd_two")} /></span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionTotal" name="master_dimension[]" value={this.state.mbdt} disabled="disabled"/></span></td>
                          </tr>
                          <tr>
                            <td><span className="text-popups">Dining Room</span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionInput" name="dining_dimension[]" value={this.state.drd_one} onChange={this.handleChange.bind(this, "drd_one")} /> x <input type="text" className="dimensionInput" name="dining_dimension[]" value={this.state.drd_two} onChange={this.handleChange.bind(this, "drd_two")} /></span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionTotal" name="dining_dimension[]" value={this.state.drdt} disabled="disabled"/></span></td>
                          </tr>
                          <tr>
                            <td><span className="text-popups">2nd Bedroom</span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionInput" name="2bedroom_dimension[]" value={this.state.bbd_one} onChange={this.handleChange.bind(this, "bbd_one")} /> x <input type="text" className="dimensionInput" name="2bedroom_dimension[]" value={this.state.bbd_two} onChange={this.handleChange.bind(this, "bbd_two")} /></span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionTotal" name="2bedroom_dimension[]" value={this.state.bbdt} disabled="disabled"/></span></td>
                          </tr>
                          <tr>
                            <td><span className="text-popups">3rd Bedroom</span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionInput" name="3bedroom_dimension[]" value={this.state.bbbd_one} onChange={this.handleChange.bind(this, "bbbd_one")} /> x <input type="text" className="dimensionInput" name="3bedroom_dimension[]" value={this.state.bbbd_two} onChange={this.handleChange.bind(this, "bbbd_two")} /></span></td>
                            <td><span className="text-popups"><input type="text" className="dimensionTotal" name="3bedroom_dimension[]" value={this.state.bbbdt} disabled="disabled"/></span></td>
                          </tr>
                        </tbody>
                      </table>
                      <h4 id="total" className="text-popups">Total Value<br/>Room SQ.<br/>Footage<br/><input type="text" id="total_sqf" name="min_space" value={this.state.min_space} disabled="disabled" /></h4>
                    </div>
                  </div>
                </div>
                <h4 className="text-popups">&nbsp;</h4>
                <input type="hidden" name="listing" value={this.state.listing}/>
                <input type="hidden" name="min_loc" value={this.state.min_loc}/>
                <input type="hidden" name="min_bld" value={this.state.min_bld}/>
                <input type="hidden" name="min_view" value={this.state.min_vws}/>
                <input type="hidden" name="min_space" value={this.state.min_space}/>
                <button type="submit" name="submit" className="text-popups" id="submit">Process <i className="fa fa-chevron-right"></i></button>
              </form>              
            </div>
          </div>
          <h4 id="closeSpvPopup" onClick={this.closePopup}><i className="fa fa-times" title="close"></i></h4>
        </div>
      );
	  }
	});
  
	var Listing = React.createClass({
	  getInitialState: function() {
      return{
        role: "<?php echo (isset($role) ? $role : "") ?>",
        email: "<?php echo (isset($email) ? $email : "") ?>",
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>",
        listing: "<?php echo (isset($list_num) ? $list_num : "") ?>",
        agent1_img: "",
        agent2_img: "",
        image: "",
        details: [],
        streetEasyAddress: "",
        OLRAddress: "",
      };
	  },
	  componentWillMount: function(){
      this.getListingDetails();
	  },
	  getListingDetails: function(){
      $.ajax({
        type: "POST",
        url: "get-listing-details.php",
        data: {"listing": this.state.listing},
        success: function(data){
          var details = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({details: details});
              this.setState({photo: details['photo1']});
              this.setState({agent1_img: details['agent_photo']});
              this.setState({agent2_img: details['agent2_photo']});
              this.setSearchAddresses(details['address'], details['zip']);
              this.checkAmenities();
            }
          }.bind(this));
        }.bind(this),
        error: function() {
          console.log("failed");
        }
      });
	  },
    setSearchAddresses: function(address, zip){
      var ad = address;
      while(ad.indexOf(" ") != -1){ ad = ad.replace(" ", "+"); }
      var sea = ad + "+" + zip;
      this.setState({streetEasyAddress: sea});
      
      var olra = address;
      while(olra.indexOf(" ") != -1){ olra = olra.replace(" ", "%"); }
      this.setState({OLRAddress: olra});
    },
	  checkAmenities: function(){
      var amenities = "<tbody><col width='150'/><col width='150'/><tr>";
      var count = 0;
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['virtual'] != "0" && this.state.details['amenities']['virtual'] != ""){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Virtual</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Virtual</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['burnst'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Burnst</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Burnst</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['fireplace'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Fireplace</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Fireplace</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['garage'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Garage</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Garage</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['pool'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Pool</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Pool</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['roofd'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px"Outdoor</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Outdoor</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['garden'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Outdoor</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Outdoor</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['den'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Den</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Den</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['din'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Dining Room</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Dining Room</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['laundry'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Laundry</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Laundry</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['ac'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Alcove</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Alcove</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['security'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Security</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Security</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['doorman'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Doorman</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Doorman</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['wheelchair'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Wheelchair</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Wheelchair</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['elevator'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Elevator</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Elevator</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['healthclub'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Health Club</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Health Club</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['utilities'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Utilities</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Utilities</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['pets'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Pets allowed</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Pets allowed</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['furnished'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Furnished</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Furnished</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['prewar'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Prewar</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Prewar</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['loft'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Loft</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Loft</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['terrace'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Terrace</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Terrace</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['balcony'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Balcony</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Balcony</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['outdoor'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Outdoor</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Outdoor</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['wash_dry'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">Washer / Dryer</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">Washer / Dryer</td>';}
      }
      if(typeof this.state.details['amenities'] !== "undefined" && this.state.details['amenities']['nofee'] != "0"){
        count++;
        if(count % 2 == 0){ amenities += '<td style="width:150px">No Fee</td></tr><tr>'; }
        else{amenities += '<td style="width:150px">No Fee</td>';}
      }
      amenities += "</tr></tbody>";

      $("#amenities").html(amenities);
	  },
    imageHover: function(image){
      this.setState({photo: image});
	  },
	  checkAgent1: function(id){
      if(typeof id !== "undefined" && id != "" && id != null){ return true; }
      else{ return false; }
	  },
	  checkAgent2: function(id){
      if(typeof id !== "undefined" && id != "" && id != null){ return true; }
      else{ return false; }
	  },
	  primaryAgent: function(agent){
      $.get("/controllers/ajax.php", {
        agentCode: agent,
        usersEmail: this.state.email,
        makePrimary: 'true',
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getListingDetails();
            }
          }.bind(this));
        }.bind(this)
      });
	  },
	  agentImgError: function(agent){
      if(agent == "agent1"){ this.setState({agent1_img: "http://homepik.com/images/noagent.png"}); }
      if(agent == "agent2"){ this.setState({agent2_img: "http://homepik.com/images/noagent.png"}); }
	  },
	  spaceFactor: function(){
      if($(window).scrollTop() < 350){ $(window).scrollTop(350) };
      $(".impliedFootage").hide();
      $(".spaceFactor").show();
	  },
	  impliedFootage: function(){
      if($(window).scrollTop() < 350){ $(window).scrollTop(350) };
      $(".spaceFactor").hide();
      $(".impliedFootage").show();
	  },
	  slideshow(index){
      var $dialog =  $("#ajax-box3").dialog({
        modal: true,
        width: 1345,
        dialogClass: "slideshow slideshowPopup",
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box3'));
          var div = document.createElement('div');
          div.id = 'ajax-box3';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box3").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<Slideshow closeDialog={closeDialog} num_photos={this.state.details['numPhotos']} start_photo={index} photos={this.state.details['photos']}/>, $dialog[0]);
	  },
	  send: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 585,
        dialogClass: 'sendPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<Send closeDialog={closeDialog} listing_num={this.state.listing} agent_email={this.state.details['agent_email']} agent2_email={this.state.details['agent2_email']} selected={'friend'} message={""}/>, $dialog[0]);
	  },
	  save: function(){
      if(this.state.role == "agent"){
        var $dialog =  $("#ajax-box").dialog({
          modal: true,
          width: 500,
          dialogClass: 'agentSavePopup',
          close: function(){
            ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
            var div = document.createElement('div');
            div.id = 'ajax-box';
            document.getElementsByTagName('body')[0].appendChild(div);
            $( this ).remove();
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        var closeDialog = function(){
          $dialog.dialog('close');
        }

        ReactDOM.render(<AgentSave closeDialog={closeDialog} listing_num={this.state.listing} folders={this.state.details['folders']}/>, $dialog[0]);
      }
      else if(this.state.role == "buyer"){
        var $dialog =  $("#ajax-box").dialog({
          modal: true,
          width: 500,
          dialogClass: 'buyerSavePopup',
          close: function(){
            ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
            var div = document.createElement('div');
            div.id = 'ajax-box';
            document.getElementsByTagName('body')[0].appendChild(div);
            $( this ).remove();
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        var closeDialog = function(){
          $dialog.dialog('close');
        }

        ReactDOM.render(<BuyerSave closeDialog={closeDialog} listing_num={this.state.listing} folders={this.state.details['folders']}/>, $dialog[0]);
      }
      else{
        $.get("/controllers/ajax.php", {
          saveGuestListing: 'true', //Call the PHP function
          list_num: this.state.listing,
          success: function(result){
            console.log("Listing is saved!");
            $('.fa-heart').addClass('blue');
            $("#ajax-box").dialog({
              modal: true,
              height: 'auto',
              width: 'auto',
              autoOpen: false,
              dialogClass: 'ajaxbox guestSavePopup',
              buttons : {
                Ok: function(){ $(this).dialog("close"); }
              },
              close: function() { $( this ).dialog( "destroy" ); },
              open: function(){
                $(".ui-widget-overlay").bind("click", function(){
                  $("#ajax-box").dialog('close');
                });
              }
            });
            $('#ajax-box').load('/controllers/messages.php #guestSave',function(){
              $('#ajax-box').dialog('open');
            });
          }
        });        
      }
	  },
    delete: function(){
      if(this.state.role == "buyer" || this.state.role == "guest"){
        $.get("/controllers/ajax.php", {
          clear_one_saved_from_folders: 'true',
          buyer: this.state.email,
          role: this.state.role,
          delete_id: this.state.listing
        }, function(){
          $(".fa-heart").removeClass("blue");
        });
      }
      else if(this.state.role == "agent"){
        $.get("/controllers/ajax.php", {
          clear_one_queued_from_folders: 'true',
          agent: this.state.email,
          delete_id: this.state.listing
        }, function(){
          $(".fa-heart").removeClass("blue");
        });
      }
    },
	  print: function(){
      window.print();
	  },
	  broker: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 500,
        dialogClass: 'brokerPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<Broker closeDialog={closeDialog} broker={this.state.details['broker']} contract={this.state.details['contract']} contact={this.state.details['contact']} contact_email={this.state.details['contact_email']} contact_phone={this.state.details['contact_phone']} listing_num={this.state.listing}/>, $dialog[0]);
	  },
	  costEstimator: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 585,
        dialogClass: 'costEstimatorPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<CostEstimator closeDialog={closeDialog} address={this.state.details['address']} price={this.state.details['price']} maint={this.state.details['maint']} taxes={this.state.details['taxes']}/>, $dialog[0]);
	  },
	  agent1Bio: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 610,
        dialogClass: 'agentBioPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<AgentInfo closeDialog={closeDialog} agent_firstname={this.state.details['agent_firstname']} agent_lastname={this.state.details['agent_lastname']} agent_photo={this.state.agent1_img} agent_bio={this.state.details['agent_bio']}/>, $dialog[0]);
	  },
	  agent2Bio: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 610,
        dialogClass: 'agentBioPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<AgentInfo closeDialog={closeDialog} agent_firstname={this.state.details['agent2_firstname']} agent_lastname={this.state.details['agent2_lastname']} agent_photo={this.state.agent2_img} agent_bio={this.state.details['agent2_bio']}/>, $dialog[0]);
	  },
	  emailAgent: function(agent){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 610,
        dialogClass: 'sendPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<Send closeDialog={closeDialog} role={this.state.role} listing_num={this.state.listing} agent_email={this.state.details['agent_email']} agent2_email={this.state.details['agent2_email']} selected={agent} message={"Please send me more information on this listing."}/>, $dialog[0]);
	  },
    spvDocument: function(){
      var $dialog =  $("#ajax-box").dialog({
        modal: true,
        width: 850,
        dialogClass: 'spvFilterPopup',
        close: function(){
          ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
          var div = document.createElement('div');
          div.id = 'ajax-box';
          document.getElementsByTagName('body')[0].appendChild(div);
          $( this ).remove();
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      var closeDialog = function(){
        $dialog.dialog('close');
      }

      ReactDOM.render(<SPVFilters closeDialog={closeDialog} listing={this.state.listing} details={this.state.details}/>, $dialog[0]);
    },
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar mainPage={this.state.mainPage} />
            <AddressSearch mainPage={this.state.mainPage} />
            <div className="container-fluid details-container">
              <div className="row">
                <div className="col-md-12">
                  <div className="clearfix grpelem" id="u8786">
                    <div className="clearfix grpelem" id="u8794-6">
                      <p id="u8794-2">{this.state.details['address']}</p>
                      <p id="u8794-4">#{this.state.details['apt']}</p>
                    </div>
                  </div>
                </div>
              </div>
              <div className="row">
                <div className="col-md-4 col-sm-6 col-xs-12 first-section">
                  <div className="clip_frame clearfix grpelem" id="u8771">
                    <img className="position_content" id="bigImage" src={this.state.photo} alt="" onClick={this.slideshow.bind(this, '0')}/>
                    <img className="position_content listingImage" src={this.state.details['photo1']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo1'])} onClick={this.slideshow.bind(this, '0')}/>
                    {this.state.details['photo2'] != '' ? <img className="position_content listingImage" src={this.state.details['photo2']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo2'])} onClick={this.slideshow.bind(this, '1')}/> : null }
                    {this.state.details['photo3'] != '' ? <img className="position_content listingImage" src={this.state.details['photo3']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo3'])} onClick={this.slideshow.bind(this, '2')}/> : null }
                    {this.state.details['photo4'] != '' ? <img className="position_content listingImage" src={this.state.details['photo4']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo4'])} onClick={this.slideshow.bind(this, '3')}/> : null }
                    {this.state.details['photo5'] != '' ? <img className="position_content listingImage" src={this.state.details['photo5']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo5'])} onClick={this.slideshow.bind(this, '4')}/> : null }
                    {this.state.details['photo6'] != '' ? <img className="position_content listingImage" src={this.state.details['photo6']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo6'])} onClick={this.slideshow.bind(this, '5')}/> : null }
                    {this.state.details['photo7'] != '' ? <img className="position_content listingImage" src={this.state.details['photo7']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo7'])} onClick={this.slideshow.bind(this, '6')}/> : null }
                    {this.state.details['photo8'] != '' ? <img className="position_content listingImage" src={this.state.details['photo8']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo8'])} onClick={this.slideshow.bind(this, '7')}/> : null }
                    {this.state.details['photo9'] != '' ? <img className="position_content listingImage" src={this.state.details['photo9']} alt="" onMouseOver={this.imageHover.bind(this, this.state.details['photo9'])} onClick={this.slideshow.bind(this, '8')}/> : null }
                  </div>
                </div>
                <div className="col-md-4 col-sm-6 col-xs-6 second-section">
                  <div className="clearfix grpelem" id="u8770-59">
                    <h2 className="Subhead-4 essentialsHeader">Essentials</h2>
                    <table style={{width: 305 + 'px'}}>
                      <tbody>
                        <tr className="Text-3--2-kern" id="u8770-5">
                          <td><span id="u8770-3">Price</span></td>
                          <td style={{textAlign: "right"}}><span id="u8770-4">${this.state.details['price']}</span></td>
                        </tr>
                        <tr className="Text-3--2-kern" id="u8770-7">
                          <td>Neighborhood</td>
                          <td style={{textAlign: "right"}}>{this.state.details['nbrhood']}</td>
                        </tr>
                        <tr className="Text-3--2-kern" id="u8770-9">
                          <td>Bedrooms</td>
                          <td style={{textAlign: "right"}}>{this.state.details['bed']}</td>
                        </tr>
                        <tr className="Text-3--2-kern" id="u8770-11">
                          <td>Baths</td>
                          <td style={{textAlign: "right"}}>{this.state.details['bath']}</td>
                        </tr>
                        <tr className="Text-3--2-kern" id="u8770-13">
                          <td>Maint/CC &amp; Taxes</td>
                          <td style={{textAlign: "right"}}>${this.state.details['monthly']}/mo</td>
                        </tr>
                      </tbody>
                    </table>
                    <p className="Text-3--2-kern" id="u8770-14">&nbsp;</p>
                    <table className="Text-3--2-kern" id="u8770-20">
                      <colgroup><col width="50"/><col width="50"/><col width="50"/><col width="100"/><col width="30"/></colgroup>
                      <tbody>
                        <tr>
                          <td><a style={{cursor: "pointer"}} onClick={this.send}><i className="fa fa-envelope-o" title="Email Listing"></i></a></td>
                          <td>{this.state.details['saved'] ? <a style={{cursor: "pointer"}} onClick={this.delete} title="Delete Listing" ><i className="fa fa-heart blue"></i></a> : <a style={{cursor: "pointer"}} onClick={this.save} title="Save Listing"><i className="fa fa-heart"></i></a>}</td>
                          <td><a style={{cursor: "pointer"}} onClick={this.print}><i className="fa fa-print" title="Print" ></i></a></td>
                          {this.state.role == "agent" ? <td><a><img style={{opacity: 0.7, height: 1 + 'em', marginTop: -5 + 'px', cursor: "pointer"}} src="http://homepik.com/images/Agent.png" alt="Broker Details" onClick={this.broker} title="Listing Broker"/></a></td> : <td></td> }
                          <td style={{textAlign: "center"}}><a style={{cursor: "pointer"}} onClick={this.costEstimator} title="Cost Estimator"><i className="fa fa-calculator"></i></a></td>
                        </tr>
                        <tr className="Icon-labels" id="u8770-24">
                          <td>Send</td>
                          <td>Save</td>
                          <td>Print</td>
                          {this.state.role == "agent" ? <td>Broker</td> : <td></td> }
                          <td style={{textAlign: "center", paddingTop: 3 + "px"}}>Cost Estimator</td>
                        </tr>
                      </tbody>
                    </table>
                    <h2 className="Subhead-4 spaceHeader" id="u8770-28">Space</h2>
                    <table style={{width: 305 + 'px'}}>
                      <tbody>
                        {this.state.details['bedroom1'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Master Bed</td>
                            <td style={{textAlign: "right"}}>{this.state.details['bedroom1']}</td>
                          </tr>
                        : null }
                        {this.state.details['living_room'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-32">
                            <td>Living Room</td>
                            <td style={{textAlign: "right"}}>{this.state.details['living_room']}</td>
                          </tr>
                        : null }
                        {this.state.details['dining_room'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Dining Room</td>
                            <td style={{textAlign: "right"}}>{this.state.details['dining_room']}</td>
                          </tr>
                        : null }
                        {this.state.details['bedroom2'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-34">
                            <td>Bedroom 2</td>
                            <td style={{textAlign: "right"}}>{this.state.details['bedroom2']}</td>
                          </tr>
                        : null }
                        {this.state.details['bedroom3'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Bedroom 3</td>
                            <td style={{textAlign: "right"}}>{this.state.details['bedroom3']}</td>
                          </tr>
                        : null }
                        {this.state.details['bedroom4'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Bedroom 4</td>
                            <td style={{textAlign: "right"}}>{this.state.details['bedroom4']}</td>
                          </tr>
                        : null }
                        {this.state.details['den'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Den</td>
                            <td style={{textAlign: "right"}}>{this.state.details['den']}</td>
                          </tr>
                        : null }
                        {this.state.details['alcove'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Alcove</td>
                            <td style={{textAlign: "right"}}>{this.state.details['alcove']}</td>
                          </tr>
                        : null }
                        {this.state.details['kitchen'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Kitchen</td>
                            <td style={{textAlign: "right"}}>{this.state.details['kitchen']}</td>
                          </tr>
                        : null }
                        {this.state.details['maids_room'] != "" ?
                          <tr className="Text-3--2-kern" id="u8770-30">
                            <td>Maid's Room</td>
                            <td style={{textAlign: "right"}}>{this.state.details['maids_room']}</td>
                          </tr>
                        : null }
                        <tr className="Text-3--2-kern" id="u8770-38">
                          <td>Space factor <span id="u8770-36"><a style={{cursor: "pointer"}} onClick={this.spaceFactor}><i className="fa fa-question-circle"></i></a></span></td>
                          <td style={{textAlign: "right"}}>{this.state.details['space']} sf</td>
                        </tr>
                        <tr className="Text-3--2-kern" id="u8770-42">
                          <td>Implied gross footage <span id="u8770-40"><a style={{cursor: "pointer"}} onClick={this.impliedFootage}><i className="fa fa-question-circle"></i></a></span></td>
                          <td style={{textAlign: "right"}}>{this.state.details['footage']} sf</td>
                        </tr>
                        <tr>
                          <td className="Text-3--2-kern" id="u8770-46" colSpan="2">All dimensions are approximate. <br/>HomePik is not responsible for any errors or omissions</td>
                        </tr>
                      </tbody>
                    </table>
                    <SpaceFactor />
                    <ImpliedFootage />
                    <p className="Text-3--2-kern">&nbsp;</p>
                    <h2 className="Subhead-4 featuresHeader" id="u8770-49">Features</h2>
                    <table className="Text-3--2-kern" id="amenities"></table>
                  </div>
                </div>
                <div className="col-md-4 col-sm-6 col-xs-6 third-section">
                  <div className="clearfix grpelem" id="u8787">
                    <div className="clearfix grpelem" id="u8788-28">
                      <h2 className="Subhead-4 contactHeader" id="u8788-2">Contact</h2>
                      <p className="Text-3--2-kern" style={{paddingBottom: 5 + 'px'}}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="u8788-4">{this.state.details['agent_firstname']} {this.state.details['agent_lastname']}</span></p>
                      <p className="Text-3--2-kern" id="u8788-7" style={{width: 144 + 'px', marginLeft: 130 + 'px', textAlign: "left", paddingBottom: 5 + 'px'}}><span>{this.state.details['agent_title']}</span></p>
                      <p className="Text-3--2-kern" id="u8788-9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {this.state.details['agent_cellphone']}</p>
                      <p className="Text-3--2-kern" id="u8788-11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {this.state.details['agent_ext']}</p>
                      <p className="Text-3--2-kern" id="agentSpace">&nbsp;</p>
                      <p className="Text-3--2-kern" id="u8788-13">&nbsp;</p>
                      <p className="Text-3--2-kern" id="u8788-16"><span id="u8788-14"><i className="fa fa-chevron-right"></i></span><span id="u8788-15"><a style={{cursor: "pointer"}} onClick={this.agent1Bio}><span id="u8788-19">View</span> agent information</a></span></p>
                      {this.state.role == "buyer" ? <p className="Text-3--2-kern" id="u8788-21"><span id="u8788-17"><i className="fa fa-chevron-right"></i></span><span id="u8788-18"></span>{this.checkAgent1(this.state.details['pAgent']) ? <a style={{cursor: "pointer"}} onClick={this.primaryAgent.bind(this, this.state.details['pAgent'])}><span id="u8788-19">Remove</span><span id="u8788-20"> as your primary agent</span></a> : <a style={{cursor: "pointer"}} onClick={this.primaryAgent.bind(this, this.state.details['agent_id'])}><span id="u8788-19">Select</span><span id="u8788-20"> as your primary agent</span></a> }</p> : null }
                      <p className="Text-3--2-kern" id="u8788-24"><span id="u8788-22"><i className="fa fa-chevron-right"></i></span><span id="u8788-23"><a style={{cursor: "pointer"}} onClick={this.emailAgent.bind(this, 'agent')}><span id="u8788-19">Email</span> agent</a></span></p>
                      <p className="Text-3--2-kern" id="u8788-26">&nbsp;&nbsp; {this.state.details['agent_email']}</p>
                      <p className="Text-3--2-kern">&nbsp;</p>
                      <p className="Text-3--2-kern">&nbsp;</p>
                      {this.checkAgent2(this.state.details['agent2_id']) ?
                        <div id="secondAgentContactInfo">
                          <p className="Text-3--2-kern" style={{paddingBottom: 5 + 'px'}}>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span id="u8788-4b">{this.state.details['agent2_firstname']} {this.state.details['agent2_lastname']}</span></p>
                          <p className="Text-3--2-kern" id="u8788-7" style={{width: 144 + 'px', marginLeft: 130 + 'px', textAlign: "left", paddingBottom: 5 + 'px'}}><span>{this.state.details['agent2_title']}</span></p>
                          <p className="Text-3--2-kern" id="u8788-9">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {this.state.details['agent2_cellphone']}</p>
                          <p className="Text-3--2-kern" id="u8788-11">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {this.state.details['agent_ext']}</p>
                          <p className="Text-3--2-kern" id="agentSpace">&nbsp;</p>
                          <p className="Text-3--2-kern" id="u8788-13">&nbsp;</p>
                          <p className="Text-3--2-kern" id="u8788-16"><span id="u8788-14"><i className="fa fa-chevron-right"></i></span><span id="u8788-15"><a style={{cursor: "pointer"}} onClick={this.agent2Bio}><span id="u8788-19">View</span> agent information</a></span></p>
                          <p className="Text-3--2-kern" id="u8788-21"><span id="u8788-17"><i className="fa fa-chevron-right"></i></span><span id="u8788-18"></span><a style={{cursor: "pointer"}} onClick={this.primaryAgent.bind(this, this.state.details['pAgent2'])}><span id="u8788-19">Remove</span><span id="u8788-20"> as your primary agent</span></a></p>
                          <p className="Text-3--2-kern" id="u8788-24"><span id="u8788-22"><i className="fa fa-chevron-right"></i></span><span id="u8788-23"><a style={{cursor: "pointer"}} onClick={this.emailAgent.bind(this, 'agent2')}><span id="u8788-19">Email</span> agent</a></span></p>
                          <p className="Text-3--2-kern" id="u8788-26">&nbsp;&nbsp; {this.state.details['agent2_email']}</p>
                        </div>
                      : null }
                      </div>
                    <div className="clip_frame clearfix grpelem" id="u8789">
                      <img className="position_content" id="u8789_img" alt="No Image" onError={this.agentImgError.bind(this, "agent1")} src={this.state.agent1_img} width="120" height="119"/>
                    </div>
                    {this.checkAgent2(this.state.details['agent2_id']) ?
                      <div className="clip_frame clearfix grpelem" id="u8789b">
                        <img className="position_content" id="u8789b_img" alt="No Image" onError={this.agentImgError.bind(this, "agent2")} src={this.state.agent2_img} width="120" height="119"/>
                      </div>
                    : null }
                    {this.state.role == "agent" ? <br style={{lineHeight: 23}}/> : null }
                    {this.state.role == "agent" ?
                      <div id="u8788-28">
                        <h2 className="Subhead-4 contactHeader" id="u8788-2">HomePik Office</h2>
                        <p className="Text-3--2-kern" id="u8788-16">
                          <span id="u8788-14"><i className="fa fa-chevron-right"></i></span>
                          <span id="u8788-15"><a target="_blank" style={{cursor: "pointer"}}><span>Selection Portfolio Valuation</span></a></span>
                        </p>
                        <p className="Text-3--2-kern" id="u8788-16">
                          <span id="u8788-14"><i className="fa fa-chevron-right"></i></span>
                          <span id="u8788-15"><a target="_blank" href={"https://www.streeteasy.com/nyc/search?search=" + this.state.searchAddress} style={{cursor: "pointer"}}><span>Streeteasy Listing</span></a></span>
                        </p>
                        <p className="Text-3--2-kern" id="u8788-16">
                          <span id="u8788-14"><i className="fa fa-chevron-right"></i></span>
                          <span id="u8788-15"><a target="_blank" href={"http://public.olr.com/building_results.aspx?address=" + this.state.details['address']} style={{cursor: "pointer"}}><span>Online Residential Listing</span></a></span>
                        </p>
                        {/* <p className="Text-3--2-kern" id="u8788-16">
                          <span id="u8788-14"><i className="fa fa-chevron-right"></i></span>
                          <span id="u8788-15"><a target="_blank" style={{cursor: "pointer"}}><span>Listing Web Page</span></a></span>
                        </p> */}
                      </div>
                    : null }
                    </div>
                  </div>
              </div>
              <div className="row">
                <div className="col-md-12 col-sm-12 col-xs-12 description-header-section">
                  <h2 className="Subhead-4 descriptionHeader" id="u8796-2">Property Description</h2>
                  <div className="grpelem" id="u8799"></div>
                </div>
              </div>
              <div className="row">
                <div className="col-md-8 col-sm-8 col-xs-12 fourth-section">
                  <div className="clearfix grpelem" id="u8796-17">
                    {this.state.details['mkt_desc'] != "" ? <p className="Text-3--2-kern" id="u8796-4" dangerouslySetInnerHTML={{__html: this.state.details['mkt_desc']}}></p> : <p className="Text-3--2-kern" id="u8796-4">Property Description Unavailable</p> }
                    <p className="Text-3--2-kern" id="u8796-13">&nbsp;</p>
                    {this.state.role == "guest" ? <div>{ this.state.details['broker'] != '' ? <p className="Text-3--2-kern" id="u8796-15">This listing has been provided courtesy of {this.state.details['broker']}</p> : <p className="Text-3--2-kern" id="u8796-15">This listing has been provided courtesy of {this.state.details['contract']}</p> } </div> : null }
                    <p className="Text-3--2-kern" id="u8796-13">&nbsp;</p>
                    {this.state.role == "guest" && this.state.details['rlsMatch'] ? <p><img src="http://homepik.com/images/ListingService_Final.jpg" style={{width: 100 + "px", height: 50 + "px"}} /></p> : null }
                    <p className="Text-3--2-kern" id="u8796-13">&nbsp;</p>
                    <p className="Text-3--2-kern" id="listingDisclaimer">
                      {this.state.role == 'guest' ? <span>Guest public portal.</span> : <span>This is not a public portal and users who are not registered as HomePik buyers are prohibited from using this site.</span> }
                      <br/>HomePik is a Licensed Real Estate Broker in the State of New York.
                    </p>
                  </div>
                </div>
                <div className="col-md-4 col-sm-8 col-xs-12 fifth-section">
                  <div className="clip_frame clearfix grpelem" id="u8800">
                    <iframe src={"http://maps.google.com/maps?q="+this.state.details['address']+",+New+York,+NY+'&output=embed"} width="288" height="320"></iframe>
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
    <Listing/>,
    document.getElementById("listing")
  );

	ReactDOM.render(
    <Footer mainPage={"<?php echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
