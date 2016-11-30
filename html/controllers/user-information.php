<?
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include("basicHead.php");

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

<title>HomePik - User Information</title>

<?php include_css("/views/css/user-information.css");
  include_once('autoLogout.php'); ?>
  <script type="text/javascript">
    document.write('\x3Cscript src="' + (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//webfonts.creativecloud.com/lato:n3,n4,i4,n7:default.js" type="text/javascript">\x3C/script>');
  </script>
</head>
<body>
  <div id="userInformation"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
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
      $.get("ajax.php", {
        emailFolder: 'true',
        agentSentBuyerFolder: this.state.agentSentBuyerFolder, 
        sender: this.state.email,
        folder: this.state.folder,
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
  
  var BuyerInfo = React.createClass({
    getInitialState: function() {
      return{
        buyerInfo: [],
        reassign_agent1: "false",
        reassign_agent2: "false",
        agent1_id: "",
        agent2_id: "",
        status: false
      };
	  },
    componentDidMount: function(){
      this.getBuyerInfo();
    },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleReassignAgent: function(name, event){
      if(name == "agent1"){ this.setState({reassign_agent1: "true"}); }
      else if(name == "agent2"){ this.setState({reassign_agent2: "true"}); }
    },
    getBuyerInfo: function(){
      $.ajax({
				type: "POST",
				url: "get-buyers.php",
				data: {"buyerInformation": "true", "email": this.props.buyer},
				success: function(data){
					var buyer = JSON.parse(data);
					this.setState({buyerInfo: buyer});
					this.setState({agent1_id: buyer['P_agent']});
					this.setState({agent2_id: buyer['P_agent2']});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getAgentsForInput: function(){
		  $.ajax({
				type: "POST",
				url: "get-agents.php",
				data: {"agent": "true", "new": "true"},
				success: function(data){
					var info = JSON.parse(data);
					$( ".agent-code" ).autocomplete({
						source: info
					});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
		},
		switchAgent: function(agent, event){
      var code = $("#formAgent").val();
      
      if(code.length == 3){
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"reassignCheck": "true", "id": code, "email": this.state.buyerInfo['email']},
          success: function(data){
            var info = JSON.parse(data);

            if(info == "good"){
              if(agent == "agent1"){ this.setState({agent1_id: code}); }
              else if(agent == "agent2"){ this.setState({agent2_id: code}); }
              this.setState({status: true});
            }
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '275px',
                autoOpen: false,
                dialogClass: 'ajaxbox invalidCodePopup',
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
              $('#ajax-box2').load('../controllers/messages.php #addPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Invalid Code" ).dialog('open');
              });
            }
          }.bind(this),
          error: function(){
            console.log("failed");
          }
        });
      }
      else{
        var name = code.split(", ");
        var firstname = name[1].replace(" ", "");
        var lastname = name[0];

        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"reassignCheck": "true", "firstname": firstname, "lastname": lastname, "email": this.state.buyerInfo['email']},
          success: function(data){
            var info = JSON.parse(data);

            if(info == "good"){
              $.ajax({
                type: "POST",
                url: "check-agent.php",
                data: {"getID":"true", "firstname": firstname, "lastname": lastname},
                success: function(data){
                  var id = JSON.parse(data);
                  if(agent == "agent1"){ this.setState({agent1_id: id}); }
                  else if(agent == "agent2"){ this.setState({agent2_id: id}); }
                  this.setState({status: true});
                }.bind(this)
              });
            }
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '275px',
                autoOpen: false,
                dialogClass: 'ajaxbox invalidCodePopup',
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
              $('#ajax-box2').load('../controllers/messages.php #addPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Invalid Code" ).dialog('open');
              });
            }
          }.bind(this),
          error: function(){
            console.log("failed");
          }
        });
      }
		},
    assignAgent: function(agent, event){
      this.switchAgent(agent);
      setTimeout(function() {
        if(this.state.status){      
          if(agent == "agent1"){ var id = this.state.agent1_id; }
          else if(agent == "agent2"){ var id = this.state.agent2_id; }
          
          $.get("ajax.php", {
            reassignAgent: 'true',
            buyer: this.state.buyerInfo['email'],
            agent: agent,
            id: id,
            success: function(result){
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  if(agent == "agent1"){ this.setState({reassign_agent1: "false"}); }
                  else if(agent == "agent2"){ this.setState({reassign_agent2: "false"}); }
                  this.getBuyerInfo();
                }
              }.bind(this));
            }.bind(this)
          });
        }
      }.bind(this), 1000)
    },
    closePopup: function(){
      $("#overlay").hide();
      {this.props.closeDialog()}
    },
    render: function(){
      return(
        <div id="buyerInformationArea">
          <div id="buyerInformationTop">
            <span id="buyerInformationHeader" className="text-popups">Buyer Information</span>
            <img src="../images/button_pen_states.png" style={{float: "right"}}/>
            <h4 id="closePopup" onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
          </div>
          <div id="buyerInformationBorder">
            <table cellPadding="2" cellSpacing="0" border="0">
              <colgroup><col width="250"/><col width="350"/></colgroup>
              <tbody>
                <tr>
                  <td className="text-popups">ID:</td>
                  <td className="text-popups">{this.state.buyerInfo['id']}<br/></td>
                </tr>
                <tr>
                  <td className="text-popups">First Name:</td>
                  <td className="text-popups">{this.state.buyerInfo['first_name']}<br/></td>
                </tr>
                <tr>
                  <td className="text-popups">Last Name:</td>
                  <td className="text-popups">{this.state.buyerInfo['last_name']}<br/></td>
                </tr>
                <tr>
                  <td className="text-popups">Email:</td>
                  <td className="text-popups">{this.state.buyerInfo['email']}</td>
                </tr>
                <tr>
                  <td className="text-popups">Phone:</td>
                  <td className="text-popups">{this.state.buyerInfo['phone']}</td>
                </tr>
                <tr>
                  <td className="text-popups">Agent 1: </td>
                  {this.state.buyerInfo['P_agent'] != "" && this.state.buyerInfo['P_agent'] != null ?
                    <td className="text-popups">{this.state.reassign_agent1 == "true" ?
                      <span><input type="text" id="formAgent" className="agent-code input1" name="agent1-code" value={this.state.agent1_id} onChange={this.handleChange.bind(this, 'agent1_id')} onFocus={this.getAgentsForInput}/> <a id="reassignAgent" onClick={this.assignAgent.bind(this, "agent1")}>assign</a></span>
                    :
                      <span>{this.state.buyerInfo['P_agent']} <a id="reassignAgent" onClick={this.handleReassignAgent.bind(this, "agent1")}>reassign</a></span>
                    } </td>
                  : <td className="text-popups">No Agent</td> }
                </tr>
                {this.state.buyerInfo['P_agent2'] != "" && this.state.buyerInfo['P_agent2'] != null ?
                  <tr>
                    <td className="text-popups">Agent 2: </td>
                    <td className="text-popups">{this.state.reassign_agent2 == "true" ?
                      <span><input type="text" id="formAgent" className="agent-code input1" name="agent2-code" value={this.state.agent2_id} onChange={this.handleChange.bind(this, 'agent2_id')} onFocus={this.getAgentsForInput}/> <a id="reassignAgent" onClick={this.assignAgent.bind(this, "agent2")}>assign</a></span>
                    :
                      <span>{this.state.buyerInfo['P_agent2']} <a id="reassignAgent" onClick={this.handleReassignAgent.bind(this, "agent2")}>reassign</a></span>
                    } </td>
                  </tr>
                : null}                        
              </tbody>
            </table>
          </div>
        </div>
      );
    }    
  });
  
  var UserInformation = React.createClass({
    getInitialState: function() {
      return{
        mainPage: "<? echo $mainPage ?>",
        users: [],
        user_type: "",
        selected_user: "",
        selected_user_info: [],
        agents_buyers: [],
        buyer_folders: [],
				openFolder: "",
        reassign_agent1: "false",
        reassign_agent2: "false",
        agent1_id: "",
        agent2_id: "",
        status: false,
        step: 1,
        month: "default",
        day: "default",
        year: "default",
        activity: []
      };
	  },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleReassignAgent: function(name, event){
      if(name == "agent1"){ this.setState({reassign_agent1: "true"}); }
      else if(name == "agent2"){ this.setState({reassign_agent2: "true"}); }
    },
	  handleUserChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
      $("#userSelect select").val("default");
      
      if(event.target.value == "agent"){ this.getAgents(); }
      else if(event.target.value == "buyer"){ this.getBuyers(); }
    },
    handleUserSelect: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
      
      if(event.target.value == "default"){ this.setState({step: 2}); }
      else{
        if(this.state.user_type == "agent"){ this.getAgentInformation(event.target.value); }
        else if(this.state.user_type == "buyer"){ this.getBuyerInformation(event.target.value); }
      }
    },
    getAgents: function(){
      $.ajax({
				type: "POST",
				url: "get-registered-agents.php",
				data: {"activeAgents": "true"},
				success: function(data){
					var users = JSON.parse(data);
					this.setState({users: users});
          this.setState({step: 2});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getAgentInformation: function(email){
      $.ajax({
				type: "POST",
				url: "get-registered-agents.php",
				data: {"information": "true", "email": email},
				success: function(data){
					var info = JSON.parse(data);
					var ajaxStop = 0;
					$(document).ajaxStop(function() {
					  if(ajaxStop == 0){
              ajaxStop++;
              this.setState({selected_user_info: info});
              this.setState({step: 3});
              this.getAgentsBuyers(info['agent_id']);
					  }
					}.bind(this));
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getAgentsBuyers: function(id){
      $.ajax({
				type: "POST",
				url: "get-buyers.php",
				data: {"agent_id": id},
				success: function(data){
					var buyers = JSON.parse(data);
					this.setState({agents_buyers: buyers});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getBuyers: function(){
      $.ajax({
				type: "POST",
				url: "get-buyers.php",
				data: {"allBuyers": "true"},
				success: function(data){
					var users = JSON.parse(data);
					this.setState({users: users});
          this.setState({step: 2});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getBuyerInformation: function(email){
      $.ajax({
				type: "POST",
				url: "get-buyers.php",
				data: {"buyerInformation": "true", "email": email},
				success: function(data){
					var info = JSON.parse(data);
          var ajaxStop = 0;
					$(document).ajaxStop(function() {
					  if(ajaxStop == 0){
              ajaxStop++;
              this.setState({selected_user_info: info});
              this.setState({agent1_id: info['P_agent']});
              this.setState({agent2_id: info['P_agent2']});
              this.setState({step: 3});
              this.getBuyerFolders(info['email']);
					  }
					}.bind(this));					
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
    },
    getBuyerFolders: function(email){
      $.ajax({
        type: "POST",
        url: "get-buyer-listings.php",
        data: {"email": email },
        success: function(data){
          var folders = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({buyer_folders: folders});
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
    getAgentsForInput: function(){
		  $.ajax({
				type: "POST",
				url: "get-agents.php",
				data: {"agent": "true", "new": "true"},
				success: function(data){
					var info = JSON.parse(data);
					$( ".agent-code" ).autocomplete({
						source: info
					});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
		},
		switchAgent: function(agent, event){
      var code = $("#formAgent").val();
      
      if(code.length == 3){
        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"reassignCheck": "true", "id": code, "email": this.state.selected_user_info.email},
          success: function(data){
            var info = JSON.parse(data);

            if(info == "good"){
              if(agent == "agent1"){ this.setState({agent1_id: code}); }
              else if(agent == "agent2"){ this.setState({agent2_id: code}); }
              this.setState({status: true});
            }
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '275px',
                autoOpen: false,
                dialogClass: 'ajaxbox invalidCodePopup',
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
              $('#ajax-box2').load('../controllers/messages.php #addPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Invalid Code" ).dialog('open');
              });
            }
          }.bind(this),
          error: function(){
            console.log("failed");
          }
        });
      }
      else{
        var name = code.split(", ");
        var firstname = name[1].replace(" ", "");
        var lastname = name[0];

        $.ajax({
          type: "POST",
          url: "check-agent.php",
          data: {"reassignCheck": "true", "firstname": firstname, "lastname": lastname, "email": this.state.selected_user_info.email},
          success: function(data){
            var info = JSON.parse(data);

            if(info == "good"){
              $.ajax({
                type: "POST",
                url: "check-agent.php",
                data: {"getID":"true", "firstname": firstname, "lastname": lastname},
                success: function(data){
                  var id = JSON.parse(data);
                  if(agent == "agent1"){ this.setState({agent1_id: id}); }
                  else if(agent == "agent2"){ this.setState({agent2_id: id}); }
                  this.setState({status: true});
                }.bind(this)
              });
            }
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '275px',
                autoOpen: false,
                dialogClass: 'ajaxbox invalidCodePopup',
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
              $('#ajax-box2').load('../controllers/messages.php #addPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Invalid Code" ).dialog('open');
              });
            }
          }.bind(this),
          error: function(){
            console.log("failed");
          }
        });
      }
		},
    assignAgent: function(agent, event){
      this.switchAgent(agent);
      setTimeout(function() {
        if(this.state.status){      
          if(agent == "agent1"){ var id = this.state.agent1_id; }
          else if(agent == "agent2"){ var id = this.state.agent2_id; }
          
          $.get("ajax.php", {
            reassignAgent: 'true',
            buyer: this.state.selected_user_info.email,
            agent: agent,
            id: id,
            success: function(result){
              if(agent == "agent1"){ this.setState({reassign_agent1: "false"}); }
              else if(agent == "agent2"){ this.setState({reassign_agent2: "false"}); }
              this.getBuyerInformation(this.state.selected_user_info.email);
            }.bind(this)
          });
        }
      }.bind(this), 1000)
    },
    openFolder: function(name){
		  if(this.state.openFolder != name){ this.setState({openFolder: name}); }
		  else{ this.setState({openFolder: ""}); }
		},
    emailBuyerFolder: function(name){
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
			ReactDOM.render(<EmailFolder closeDialog={closeDialog} user={this.state.selected_user_info.email} folder={name} agentSentBuyerFolder={"true"}/>, $dialog[0]);
		},
    viewBuyerInfo: function(email){
      console.log(email);
      var $dialog =  $("#ajax-box").dialog({
				width: 565,
				dialogClass: 'viewBuyerInfoPopup',
        modal: true,
				close: function(){
					ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
					var div = document.createElement('div');
					div.id = 'ajax-box';
					document.getElementsByTagName('body')[0].appendChild(div);
					$( this ).remove();
				},
        open: function(){
          $(this).css("display", "block");
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
			});
			var closeDialog = function(){
				$dialog.dialog('close');
			}.bind(this)

			ReactDOM.render(<BuyerInfo closeDialog={closeDialog} buyer={email} />, $dialog[0]);
    },
    checkDate: function(){
      var m = this.state.month;
      var d = this.state.day;
      var y = this.state.year;
      
      if( (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) && (d <= 31 || d == "default") ){ return true; } // Check if month has 31 days
      else if( (m == 4 || m == 6 || m == 9 || m == 11) && (d <= 30 || d == "default") ){ return true; } // Check if month has 30 days
      else if( m == 2 && d == "default" ){ return true; } // February only selected no day
      else if( m == 2 && d != "default" ){ // February and day selected 
        if( ( ( ( y % 4 == 0 ) && ( y % 100 != 0) ) || ( year % 400 == 0 ) ) && (d <= 29 || d == "default" ) ){ return true; } // Leap year day <= 29
        else{
          if( d <= 28 ){ return true; } // Not leap year day <= 28
          else{ return false; } // Invalid date
        }
      }
      else{ return false; } // Invalid date
    },
    getAgentActivity: function(){    
      if(this.state.month == "default" || this.state.year == "default"){ // Check if a date is defaulted
        $("#ajax-box").dialog({
					modal: true,
					height: 'auto',
					width: 'auto',
					autoOpen: false,
					dialogClass: 'ajaxbox errorMessage',
					buttons : {
						Ok: function(){
							$(this).dialog("close");
						}
					},
          close: function() {
            $( this ).dialog( "destroy" );
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #needDate',function(){
          $('#ajax-box').dialog('open');
        });
      }
      else{
        if(this.checkDate()){
          $.ajax({
            type: "POST",
            url: "get-activity.php",
            data: {"user": "agent", "email": this.state.selected_user_info['email'], "id": this.state.selected_user_info['agent_id'], "month": this.state.month, "day": this.state.day, "year": this.state.year},
            success: function(data){
              var activity = JSON.parse(data);
              var ajaxStop = 0;
              $(document).ajaxStop(function() {
                if(ajaxStop == 0){
                  ajaxStop++;
                  this.setState({activity: activity});
                  $("#userActivityInfo").show();
                }
              }.bind(this));              
            }.bind(this),
            error: function(){
              console.log("failed");
            }
          });
        }
        else{
          $("#ajax-box").dialog({
            modal: true,
            height: 'auto',
            width: 'auto',
            autoOpen: false,
            dialogClass: 'ajaxbox errorMessage',
            buttons : {
              Ok: function(){
                $(this).dialog("close");
              }
            },
            close: function() {
              $( this ).dialog( "destroy" );
            },
            open: function(){
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
              });
            }
          });
          $('#ajax-box').load('/controllers/messages.php #invalidDate',function(){
            $('#ajax-box').dialog('open');
          });
        }
      }
    },
	  render: function(){
      var users = this.state.users.map(function(user) {
        return(
          <option value={user.email}>{user.last_name}, {user.first_name}</option>
        );
      }.bind(this));
      var buyers = this.state.agents_buyers.map(function(buyer) {
        return(
          <p><a style={{cursor: "pointer"}} onClick={this.viewBuyerInfo.bind(this, buyer.email)}>{buyer.first_name} {buyer.last_name}</a></p>
        );
      }.bind(this));
      var folders = this.state.buyer_folders.map(function (folder) {
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
                <div className="col-md-2 col-sm-2 col-xs-12 folderDetails indent customWidth"><span id="u28562-3"><a style={{cursor: "pointer"}} onClick={this.emailBuyerFolder.bind(this, name)}>email listings</a></span></div>
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
                      <th style={{width: 290 + 'px'}}>Comments</th>
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
            <div className="Text-1 container-fluid" id="u8521-1">
              <span className="Text-1" id="u8522-1">User Information</span>
              <div className="row">
                <div className="col-md-5 col-sm-5 col-xs-12">
                  <div id="userInformationSection">
                    <div id="userTypeSelect">
                      Select a user: 
                      <input type="radio" name="user_type" value="agent" className="indent" onChange={this.handleUserChange.bind(this, 'user_type')}/> Agent &nbsp;&nbsp;&nbsp;<input type="radio" name="user_type" value="buyer" onChange={this.handleUserChange.bind(this, 'user_type')}/> Buyer
                    </div>
                    {this.state.step  > 1 ?
                      <div id="userSelect">
                        {this.state.user_type == "agent" ? <span>Select an agent: </span> : null }
                        {this.state.user_type == "buyer" ? <span>Select a buyer: </span> : null }
                        <select onChange={this.handleUserSelect.bind(this, 'selected_user')}>
                          <option value="default" selected="selected">Select user</option>
                          {users}
                        </select>
                      </div>
                    : null }
                    {this.state.step > 2 ?
                      <div id="userInformationPortion">
                        {this.state.user_type == "agent" ?
                          <div>
                            <table id="usersInfo">
                              <colgroup><col width="100"/><col width="350"/></colgroup>
                              <tbody>
                                <tr><td>First Name: </td><td>{this.state.selected_user_info.first_name}</td></tr>
                                <tr><td>Last Name: </td><td>{this.state.selected_user_info.last_name}</td></tr>
                                <tr><td>Title: </td><td>{this.state.selected_user_info.title}</td></tr>
                                <tr><td>Email: </td><td>{this.state.selected_user_info.email}</td></tr>
                                <tr><td>Phone: </td><td>{this.state.selected_user_info.phone != "" ? this.state.selected_user_info.phone : <span>N/A</span>}</td></tr>
                                <tr><td>Agent ID: </td><td>{this.state.selected_user_info.agent_id}</td></tr>
                                <tr><td>Bio: </td><td>{this.state.selected_user_info.bio != "" ? this.state.selected_user_info.bio : <span>N/A</span>}</td></tr>
                              </tbody>
                            </table>
                            
                            <div id="agentsBuyers">
                              <div className="clearfix grpelem" id="u16159-5">
                                <p id="u16159-3"><span id="u16159">{this.state.selected_user_info.first_name} {this.state.selected_user_info.last_name}'s Buyers</span></p>
                              </div>
                              <br/><br/>
                              <div id="buyerList">
                                {buyers}
                              </div>
                            </div>
                          </div>
                        : null }
                        {this.state.user_type == "buyer" ?
                          <div>
                            <table id="usersInfo">
                              <colgroup><col width="100"/><col width="350"/></colgroup>
                              <tbody>
                                <tr><td>ID: </td><td>{this.state.selected_user_info.id}</td></tr>
                                <tr><td>First Name: </td><td>{this.state.selected_user_info.first_name}</td></tr>
                                <tr><td>Last Name: </td><td>{this.state.selected_user_info.last_name}</td></tr>
                                <tr><td>Email: </td><td>{this.state.selected_user_info.email}</td></tr>
                                <tr><td>Phone: </td><td>{this.state.selected_user_info.phone != "" ? this.state.selected_user_info.phone : <span>N/A</span>}</td></tr>                            
                                <tr>
                                  <td>Agent 1: </td>
                                  {this.state.selected_user_info.P_agent != "" && this.state.selected_user_info.P_agent != null ?
                                    <td>{this.state.reassign_agent1 == "true" ?
                                      <span><input type="text" id="formAgent" className="agent-code input1" name="agent1-code" value={this.state.agent1_id} onChange={this.handleChange.bind(this, 'agent1_id')} onFocus={this.getAgentsForInput}/> <a id="reassignAgent" onClick={this.assignAgent.bind(this, "agent1")}>assign</a></span>
                                    :
                                      <span>{this.state.selected_user_info.P_agent} <a id="reassignAgent" onClick={this.handleReassignAgent.bind(this, "agent1")}>reassign</a></span>
                                    } </td>
                                  :
                                    <td>N/A</td>
                                  }
                                </tr>
                                {this.state.selected_user_info.P_agent2 != "" && this.state.selected_user_info.P_agent2 != null ?
                                  <tr>
                                    <td>Agent 2: </td>
                                    <td>{this.state.reassign_agent2 == "true" ?
                                      <span><input type="text" id="formAgent" className="agent-code input1" name="agent2-code" value={this.state.agent2_id} onChange={this.handleChange.bind(this, 'agent2_id')} onFocus={this.getAgentsForInput}/> <a id="reassignAgent" onClick={this.assignAgent.bind(this, "agent2")}>assign</a></span>
                                    :
                                      <span>{this.state.selected_user_info.P_agent2} <a id="reassignAgent" onClick={this.handleReassignAgent.bind(this, "agent2")}>reassign</a></span>
                                    } </td>
                                  </tr>
                                : null}
                              </tbody>
                            </table>
                          </div>
                        : null}
                      </div>
                    : null }
                  </div>
                </div>
            
                {this.state.step > 2 && this.state.user_type == "agent" ?
                  <div className="col-md-7 col-sm-7 col-xs-12">
                    <div id="userActivity">
                      Select a date to view agent's activity: 
                      <select id="month" onChange={this.handleChange.bind(this, 'month')}>
                        <option value="default">Month</option>
                        <option value="1">January</option><option value="2">February</option><option value="3">March</option>
                        <option value="4">April</option><option value="5">May</option><option value="6">June</option>
                        <option value="7">July</option><option value="8">August</option><option value="9">September</option>
                        <option value="10">October</option><option value="11">November</option><option value="12">December</option>
                      </select>
                      <select id="day" onChange={this.handleChange.bind(this, 'day')}>
                        <option value="default">Day</option>
                        <option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option>
                        <option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option>
                        <option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option>
                        <option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                        <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option>
                        <option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
                      </select>
                      <select id="year" onChange={this.handleChange.bind(this, 'year')}>
                        <option value="default">Year</option>
                        <option value="2015">2015</option><option value="2016">2016</option><option value="2017">2017</option>
                      </select>
                      <button onClick={this.getAgentActivity}>View Activity</button>
                      <div id="userActivityInfo">
                        <table id="userActivityInfoTable">
                          <tbody>
                            <tr>
                              <td>Last login: </td><td>{this.state.activity['login']}</td>
                            </tr>
                            <tr>
                              <td>New Buyers: </td><td>{this.state.activity['buyers']} buyers</td>
                            </tr>
                            <tr>
                              <td>Searches Performed: </td><td>{this.state.activity['searches']} searches</td>
                            </tr>
                            <tr>
                              <td>Listings Viewed: </td><td>{this.state.activity['listingsViewed']} listings</td>
                            </tr>
                            <tr>
                              <td>Listings Emailed: </td><td>{this.state.activity['listingsEmailed']} listings</td>
                            </tr>
                            <tr>
                              <td>Listings Saved to thier Folder: </td><td>{this.state.activity['listingsSavedFolder']} listings</td>
                            </tr>
                            <tr>
                              <td>Listings Saved to Buyer Folders: </td><td>{this.state.activity['listingsSavedBuyers']} listings</td>
                            </tr>
                            <tr>
                              <td>Messages Received: </td><td>{this.state.activity['messagesReceived']} messages</td>
                            </tr>
                            <tr>
                              <td>Messages Sent: </td><td>{this.state.activity['messagesSent']} messages</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                : null }
              </div>
              <div id="userInformationSection2">
                {this.state.step > 2 ?
                  <div id="userInformationPortion">
                    {this.state.user_type == "buyer" ?
                      <div>                      
                        <div className="folder-section">
                          <div className="row">
                            <div className="col-md-5 col-sm-5 col-xs-12 pageTitle">
                              <div className="clearfix grpelem" id="u16159-5">
                                <p id="u16159-3"><span id="u16159">{this.state.selected_user_info.first_name} {this.state.selected_user_info.last_name}'s Listing Folders </span><span id="u16159-2">click name to open</span></p>
                              </div>
                            </div>
                          </div>
                          <div className="row">
                            <div className="col-md-12">
                              <div className="clearfix"></div>
                              {this.state.buyer_folders.length > 0 ?
                                <div id="folderSection">
                                  {folders}
                                </div>
                              :
                                <div>
                                  <div id="loading" className="Text-1"><span>Loading Folders...</span></div>
                                  <div id="noFolders" className="Text-1">
                                    <span>No Saved Folders</span>
                                    <p>&nbsp;</p>
                                  </div>                      
                                </div>
                              }
                            </div>
                          </div>
                        </div>
                      </div>
                    : null}
                  </div>
                : null }
              </div>
            </div>
          </div>
        </div>
      );
    }
  });

  ReactDOM.render(
    <UserInformation/>,
    document.getElementById("userInformation")
  )

  ReactDOM.render(
	  <Footer mainPage={"<? echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>