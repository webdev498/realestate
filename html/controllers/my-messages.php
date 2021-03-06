<?php
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include_once("basicHead.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['buyer'])){
  $buyer_email = $_SESSION['email'];
	$agent_code = $_SESSION['agent1'];
	$agent2_code = $_SESSION['agent2'];
  $role = 'user';
  
  if($agent_code != ""){
    $result = mysql_query( "SELECT r.first_name, r.last_name, r.email, r.agent_id, u.P_agent FROM `registered_agents` r, `users` u WHERE (u.email='".$buyer_email."') AND (r.agent_id = u.P_agent)" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent_first_name = $row['first_name'];
    $agent_last_name = $row['last_name'];
    $agent_email = $row['email'];
  }

  if($agent2_code != ""){
    $result = mysql_query( "SELECT r.first_name, r.last_name, r.email, r.agent_id, u.P_agent FROM `registered_agents` r, `users` u WHERE (u.email='".$buyer_email."') AND (r.agent_id = u.P_agent2)" ) or die("Couldn't execute query.".mysql_error());
    $row = mysql_fetch_array($result,MYSQL_ASSOC);
    $agent2_first_name = $row['first_name'];
    $agent2_last_name = $row['last_name'];
    $agent2_email = $row['email'];
  }
}
elseif(isset($_SESSION['agent'])){
  $agent_email = $_SESSION['email'];
  $agent_id = $_SESSION['agent_id'];
  $role = 'agent';
  
  $result1 = mysql_query( "SELECT `first_name`, `last_name`, `email` FROM `users` WHERE ((`P_agent` = '".$agent_id."') AND ((`P_agent` != '') AND (`P_agent` != 'null'))) OR ((`P_agent2` = '".$agent_id."') AND ((`P_agent2` != '') AND (`P_agent2` != 'null'))) ORDER BY last_name ASC" ) or die("Couldn't execute query.".mysql_error());
  $num_buyers = mysql_num_rows($result1);
}
else{ print "<script> window.location = '/users/logout.php' </script>"; }

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");
$_SESSION['unreadMessages'] = 0;
?>

  <title>HomePik - My Messages</title>
  <?php include_css("/views/css/my-messages.css");
  include_once("analyticstracking.php") ?>
</head>
<body>
  <div id="messages"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var BuyerMessages = React.createClass({
    getInitialState: function() {
      return{
        buyer_email: "<?php echo (isset($buyer_email) ? $buyer_email : "") ?>",
        agent1_name: "<?php echo (isset($agent_first_name) ? $agent_first_name : "") . " " . (isset($agent_last_name) ? $agent_last_name : "") ?>",
        agent1_email: "<?php echo (isset($agent_email) ? $agent_email : "") ?>",
        agent2_name: "<?php echo (isset($agent2_first_name) ? $agent2_first_name : "") . " " . (isset($agent2_last_name) ? $agent2_last_name : "") ?>",
        agent2_email: "<?php echo (isset($agent2_email) ? $agent2_email : "") ?>",
        agent1_selected: true,
        agent2_selected: false,
        message: "",
        conversation: "",
      };
    },
	  componentDidMount: function(){
      this.getConversation();
      setInterval(function(){this.getConversation();}.bind(this), 20000);
	  },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleAgentClick: function(name, event){
      if(name == 'agent1'){
        this.setState({agent1_selected: true});
        this.setState({agent2_selected: false});
        this.getConversation('agent1');
      }
      else if(name == 'agent2'){
        this.setState({agent1_selected: false});
        this.setState({agent2_selected: true});
        this.getConversation('agent2');
      }
    },
	  getConversation: function(agent){
      var agent_email;
  
      if(typeof agent !== 'undefined' && agent == 'agent1'){ agent_email = this.state.agent1_email; }
      else if(typeof agent !== 'undefined' && agent == 'agent2'){ agent_email = this.state.agent2_email; }
      else{
        if(this.state.agent1_selected == true){ agent_email = this.state.agent1_email; }
        else if(this.state.agent2_selected == true){ agent_email = this.state.agent2_email; }
      }

      $.ajax({
        type: "POST",
        url: "get-messages.php",
        data: {"buyer": this.state.buyer_email, "agent": agent_email},
        success: function(data){
          var messages = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
            ajaxStop++;
            this.setState({conversation: messages});
            this.displayMessages(messages);
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
	  },
	  displayMessages: function(messages){
      var html = "";
      for(var key in messages){
        if(this.state.buyer_email == messages[key]["sender"]){
          html += "<div class='sender'><p class='messages-date'><i>" + messages[key]["time"] + "</i></p><p class='messages-1'>" + messages[key]["message"] + "</p></div>";
        }
        else{
          html += "<div class='receiver'><p class='messages-date'><i>" + messages[key]["time"] + "</i></p><p class='messages-2-ital'>" + messages[key]["message"] + "</p></div>";
        }
      }
      $(".messages").html(html);
	  },
    sendMessage: function(){
      if(this.state.agent1_selected == true){ var email = this.state.agent1_email; }
      else if( this.state.agent2_selected == true){ var email = this.state.agent2_email; }

      $.get("/controllers/ajax.php", {
        saveMessage: 'true', //Call the PHP function
        buyer: this.state.buyer_email,
        agent: email,
        sender: this.state.buyer_email,
        message: this.state.message,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({message: ""});
              $(".messageInput").val("");
              this.getConversation();
            }
          }.bind(this));
        }.bind(this)
      });
    },
    render:function(){
      return(
        <div>
          <div className="clearfix colelem" id="u22654-4">
            <p>My Messages</p>
          </div>
          {this.state.agent1_email != "" ?
            <div className="clearfix colelem" id="pu22655-16">
              <div className="text-popups clearfix grpelem" id="u22655-16">
                <table>
                  <colgroup><col width="300"/></colgroup>
                  <tbody>
                    <tr>
                      <td className="selectionImage" style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent1')}>
                        {this.state.agent1_selected ? <img className="block" id="u22763_img" src="/images/button_agent.png" alt="" width="66" height="66"/> : <img className="block" id="u22780_img" src="/images/button_agent_lighter.png" alt="" width="66" height="66" style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent1')}/>}
                      </td>
                    </tr>
                    <tr>
                      <td style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent1')}>
                        {this.state.agent1_selected ? <i className="fa fa-check"></i> : <i className="fa fa-circle-thin"></i>}<span id="u22655-6"> {this.state.agent1_name}</span><div>&nbsp;</div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                {this.state.agent2_email != "" ?
                  <table>
                    <colgroup><col width="300"/></colgroup>
                    <tbody>
                      <tr>
                        <td className="selectionImage" style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent2')}>
                          {this.state.agent2_selected ? <img className="block" id="u22763_img" src="/images/button_agent.png" alt="" width="66" height="66"/> : <img className="block" id="u22780_img" src="/images/button_agent_lighter.png" alt="" width="66" height="66" style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent2')}/>}
                        </td>
                      </tr>
                      <tr>
                        <td style={{cursor: "pointer"}} onClick={this.handleAgentClick.bind(this, 'agent2')}>
                          {this.state.agent2_selected ? <i className="fa fa-check"></i> : <i className="fa fa-circle-thin"></i>}<span id="u22655-13"> {this.state.agent2_name}</span><div>&nbsp;</div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                : null }
              </div>
              <div className="clearfix grpelem" id="u22718-99">
                <div className="messages"></div>
                <div className="rounded-corners grpelem" style={{marginLeft: 12 + 'px'}}>
                  <textarea className="messageInput" id="u22754" rows="4" cols="50" onChange={this.handleChange.bind(this, 'message')} maxLength="500"></textarea>
                </div>
                <p className="messages-2-ital" style={{marginLeft: 12 + 'px'}}><a style={{cursor: "pointer"}} onClick={this.sendMessage}>Send</a></p>
              </div>
            </div>
          :
            <div className="clearfix colelem" id="pu22655-16">
              <p id="noMessages">No saved messages</p>
            </div>
          }
        </div>
      );
    }
  });

	var AgentMessages = React.createClass({
    getInitialState: function() {
      return{
        agent_email: "<?php echo (isset($agent_email) ? $agent_email : "") ?>",
        agent_id: "<?php echo (isset($agent_id) ? $agent_id : "") ?>",
        buyers: [],
        num_buyers: "<?php echo (isset($num_buyers) ? $num_buyers : "") ?>" ,
        buyer_selected: "",
        message: "",
        conversation: ""
      };
    },
	  componentDidMount: function(){
      this.getBuyers();
      this.getConversation();
      setInterval(function(){this.getConversation();}.bind(this), 20000);
	  },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleBuyerClick: function(email, event){
      this.setState({buyer_selected: email});
      window.scrollTo(0,200);
      this.getConversation(email);
    },
	  getBuyers: function(){
      $.ajax({
        type: "POST",
        url: "get-buyers.php",
        data: {"agent_id": this.state.agent_id},
        success: function(data){
          var buyers = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({buyers: buyers});
              if(buyers.length > 0){
                this.setState({buyer_selected: buyers[0]['email']});
                this.getConversation(buyers[0]['email']);
              }
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
	  },
	  getConversation: function(email){
      var buyer_email;
      if(typeof email === 'undefined'){ buyer_email = this.state.buyer_selected; }
      else{ buyer_email = email; }

      $.ajax({
        type: "POST",
        url: "get-messages.php",
        data: {"buyer": buyer_email, "agent": this.state.agent_email},
        success: function(data){
          var messages = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({conversation: messages});
              this.displayMessages(messages);
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
	  },
	  displayMessages: function(messages){
      var html = "";
      for(var key in messages){
        if(this.state.agent_email == messages[key]["sender"]){ html += "<div class='sender'><p class='messages-date'><i>" + messages[key]["time"] + "</i></p><p class='messages-1'>" + messages[key]["message"] + "</p></div>"; }
        else{ html += "<div class='receiver'><p class='messages-date'><i>" + messages[key]["time"] + "</i></p><p class='messages-2-ital'>" + messages[key]["message"] + "</p></div>"; }
      }
      $(".messages").html(html);
	  },
    sendMessage: function(){
      
      $.get("/controllers/ajax.php", {
        saveMessage: 'true', //Call the PHP function
        buyer: this.state.buyer_selected,
        agent: this.state.agent_email,
        sender: this.state.agent_email,
        message: this.state.message,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({message: ""});
              $(".messageInput").val("");
              this.getConversation();
            }
          }.bind(this));
        }.bind(this)
      });
    },
    render:function(){
      var buyers = this.state.buyers.map(function(buyer){
        return(
          <table key={buyer.id}>
            <colgroup><col width="300"/></colgroup>
            <tbody>
              <tr>
                <td className="selectionImage" style={{cursor: "pointer"}} onClick={this.handleBuyerClick.bind(this, buyer.email)}>
                  {this.state.buyer_selected == buyer.email ? <img className="block" id="u22763_img" src="/images/button_my_profile_hr_states-my_profile_normal.png" alt="" width="66" height="66"/> : <img className="block" id="u22780_img" src="/images/button_my_profile_hr_states-my_profile_over.png" alt="" width="66" height="66"/> }
                </td>
              </tr>
              <tr>
                <td style={{cursor: "pointer"}} onClick={this.handleBuyerClick.bind(this, buyer.email)}>
                  {this.state.buyer_selected == buyer.email ? <i className="fa fa-check"></i> : <i className="fa fa-circle-thin"></i> }&nbsp;<span className="buyerName">{buyer.last_name}, {buyer.first_name}</span><div>&nbsp;</div>
                </td>
              </tr>
            </tbody>
          </table>
        );
      }.bind(this));
      return(
        <div>
          <div className="clearfix colelem" id="u22654-4">
            <p>My Messages</p>
          </div>
          {this.state.num_buyers > 0 ?
            <div className="clearfix colelem" id="pu22655-16">
              <div className="text-popups clearfix grpelem buyers" id="u22655-16">{buyers}</div>
              <div className="clearfix grpelem" id="u22718-99">
                <div className="messages"></div>
                <div className="rounded-corners grpelem" style={{marginLeft: 12 + 'px'}}>
                  <textarea className="messageInput" id="u22754" rows="4" cols="50" onChange={this.handleChange.bind(this, 'message')} maxLength="500"></textarea>
                </div>
                <p className="messages-2-ital" style={{marginLeft: 12 + 'px'}}><a style={{cursor: "pointer"}} onClick={this.sendMessage}>Send</a></p>
              </div>
            </div>
          :
            <div className="clearfix colelem" id="pu22655-16">
              <p id="noMessages">No saved messages</p>
            </div>
          }
        </div>
      );
    }
  });

	var Messages = React.createClass({
	  getInitialState: function() {
      return{
        role: "<?php echo (isset($role) ? $role : "") ?>",
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>"
      };
	  },
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar mainPage={this.state.mainPage} />
            <AddressSearch mainPage={this.state.mainPage} />
            {this.state.role == "user" ? <BuyerMessages /> : null}
            {this.state.role == "agent" ? <AgentMessages /> : null}
          </div>
        </div>
      );
	  }
	});

	ReactDOM.render(
    <Messages/>,
    document.getElementById("messages")
  );

	ReactDOM.render(
    <Footer mainPage={"<?php echo $mainPage ?>"} />,
    document.getElementById("footer")
  );

</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>