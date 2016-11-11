<?
session_start();
include_once("dbconfig.php");
include_once('functions.php');
include("basicHead.php");

if(isset($_GET['MP'])){ $mainPage = $_GET['MP']; }
else{ $mainPage = ""; }
?>

<title>HomePik - Manage Agents</title>

<?php include_css("/views/css/manage-agents.css");
  include_once('autoLogout.php'); ?>
  <script type="text/javascript">
    document.write('\x3Cscript src="' + (document.location.protocol == 'https:' ? 'https:' : 'http:') + '//webfonts.creativecloud.com/lato:n3,n4,i4,n7:default.js" type="text/javascript">\x3C/script>');
  </script>
</head>
<body>
  <div id="manageAgents"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
  var ManageAgents = React.createClass({
    getInitialState: function() {
      return{
        registered_agents: [],
        registered_active_agents: [],
        registered_archived_agents: [],
        addAgent_firstname: "",
        addAgent_lastname: "",
        addAgent_email: "",
        addAgent_agentId: "",
        addAgent_status: "",
        agentTableDisplay: ""
      };
	  },
    componentDidMount: function(){
      this.getAgents();
      this.getActiveAgents();
      this.getArchivedAgents();
    },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    getAgents: function(){
      $.ajax({
			type: "POST",
			url: "get-registered-agents.php",
      data: {"allAgents": "true"},
			success: function(data){
			  var agents = JSON.parse(data);
			  var ajaxStop = 0;
			  $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            this.setState({registered_agents: agents});
            this.displayAgents(agents, "all");
          }
			  }.bind(this));
			}.bind(this),
			error: function(){
			  console.log("failed");
			}
		  });
    },
    getActiveAgents: function(){
      $.ajax({
        type: "POST",
        url: "get-registered-agents.php",
        data: {"activeAgents": "true"},
        success: function(data){
          var agents = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({registered_active_agents: agents});
              this.displayAgents(agents, "active");
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
		  });
    },
    getArchivedAgents: function(){
      $.ajax({
        type: "POST",
        url: "get-registered-agents.php",
        data: {"archivedAgents": "true"},
        success: function(data){
          var agents = JSON.parse(data);
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.setState({registered_archived_agents: agents});
              this.displayAgents(agents, "archived");
            }
          }.bind(this));
        }.bind(this),
        error: function(){
          console.log("failed");
        }
		  });
    },
    displayAgents: function(agents, section){
      var numRows = Math.ceil(agents.length/5);
      var group = [];
      var row = 0;
      var table = "";
      
      while(row < numRows){
        for(var i=row; i < agents.length; i+=numRows){
          if(agents[i] != ""){ group.push(agents[i]); }
          else{ break; }
        }
        
        table += "<tr>";
        for(var j=0; j< group.length; j++){
          table += "<td><input type='checkbox' name='deleteAgent' value='"+group[j]['email']+"'/> <label>"+group[j]['last_name']+", "+group[j]['first_name']+"</label></td>";
        }
        table += "</tr>";
        
        group = [];
        row++;
      }
      
      if(section == "all"){ $("#displayAgents").html(table); }
      else if(section == "active"){ $("#displayActiveAgents").html(table); }
      else if(section == "archived"){ $("#displayArchivedAgents").html(table); }
      $("#loading").hide();
      $("#noAgents").show();     
    },
    addAgent: function(){
      var name = this.state.addAgent_firstname + " " + this.state.addAgent_lastname;
      $.get("ajax.php", {
        addAgent: 'true',
        email: this.state.addAgent_email,
        firstname: this.state.addAgent_firstname,
        lastname: this.state.addAgent_lastname,
        agent_id: this.state.addAgent_agentId,
        status: this.state.addAgent_status,
        success: function(result){
          $("#ajax-box2").dialog({
            modal: true,
            height: 'auto',
            width: '275px',
            autoOpen: false,
            dialogClass: 'ajaxbox agentAddedConfirmation',
            buttons: {
              Ok: function(){
                $(this).dialog("destroy");
              }
            },
            open: function(){
              $("#agentName").html(name);
              $(".ui-widget-overlay").bind("click", function(){
                $("#ajax-box2").dialog('close');
              });        
            },
            close: function() {
              $( this ).dialog( "destroy" );
            }
          });
          $('#ajax-box2').load('messages.php #agentAdded',function(){
            $('#ajax-box2').dialog( "option", "title", "Agent Added" ).dialog('open');
          });
          
          this.setState({addAgent_email: ""});
          this.setState({addAgent_firstname: ""});
          this.setState({addAgent_lastname: ""});
          this.setState({addAgent_agentId: ""});
          this.setState({addAgent_status: ""});
          $("input[type=radio]").prop('checked', false);
          
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getAgents();
              this.getActiveAgents();
              this.getArchivedAgents();
            }
          }.bind(this));
        }.bind(this)
		  });
    },
    removeAgents: function(){
      var agents = [];
      
      $("#removeAgents input[name=deleteAgent]:checked").each(function(){ agents.push($(this).val()) });
      
      $.get("ajax.php", {
        deleteAgent: 'true',
        agents: agents,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getAgents();
              this.getActiveAgents();
              this.getArchivedAgents();
            }
          }.bind(this));
        }.bind(this)
		  });
    },
    archiveAgents: function(){
      var agents = [];
      
      $("#updateAgents #activeAgents input[name=deleteAgent]:checked").each(function(){ agents.push($(this).val()) });
            
      $.get("ajax.php", {
        archiveAgent: 'true',
        agents: agents,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getActiveAgents();
              this.getArchivedAgents();
            }
          }.bind(this));
        }.bind(this)
		  });
    },
    activateAgents: function(){
      var agents = [];
      
      $("#updateAgents #archivedAgents input[name=deleteAgent]:checked").each(function(){ agents.push($(this).val()) });
            
      $.get("ajax.php", {
        activateAgent: 'true',
        agents: agents,
        success: function(result){
          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              this.getActiveAgents();
              this.getArchivedAgents();
            }
          }.bind(this));
        }.bind(this)
		  });
    },
	  render: function(){
      var agents = this.state.registered_agents.map(function (agent) {
        return(
          <td><input type="checkbox" name="deleteAgent" value={agent.email}/> <label>{agent.last_name}, {agent.first_name}</label></td>
        );
      });
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar mainPage={this.state.mainPage} />
            <AddressSearch mainPage={this.state.mainPage} />
            <div className="Text-1" id="u8521-1">
              <span className="Text-1" id="u8522-1">Manage Agents</span>
              <div id="adminOptions">
                <div id="addAgent">
                  <span className="Text-1" id="addAgentTitle">Add Agent</span>
                  <div id="addAgentForm">
                    <table>
                      <tbody>
                        <tr>
                          <td>First Name: </td><td><input className="input1" name="firstname" value={this.state.addAgent_firstname} onChange={this.handleChange.bind(this, 'addAgent_firstname')}/></td>
                          <td>Last Name: </td><td><input className="input1" name="lastname" value={this.state.addAgent_lastname} onChange={this.handleChange.bind(this, 'addAgent_lastname')}/></td>
                        </tr>
                        <tr>
                          <td>Email: </td><td><input className="input1" name="email" value={this.state.addAgent_email} onChange={this.handleChange.bind(this, 'addAgent_email')}/></td>
                          <td>Agent ID: </td><td><input className="input1" name="agentId" value={this.state.addAgent_agentId} onChange={this.handleChange.bind(this, 'addAgent_agentId')}/></td>                                                   
                        </tr>
                        <tr>
                          <td>Status: </td><td><input type="radio" name="status" value="Y" onChange={this.handleChange.bind(this, 'addAgent_status')}/> Active &nbsp;&nbsp;&nbsp;<input type="radio" name="status" value="N" onChange={this.handleChange.bind(this, 'addAgent_status')}/> Not Active</td>
                        </tr>
                      </tbody>
                    </table>
                    <button type="submit" name="submit" id="addAgentSubmit" onClick={this.addAgent}>Add Agent <i id="arrow" className="fa fa-chevron-right"></i></button>
                  </div>
                </div>
                <div id="removeAgents">
                  <span className="Text-1" id="removeAgentsTitle">Remove Agents</span>
                    {this.state.registered_agents.length > 0 ?
                    <div id="agentsRegisteredSection">
                      <table>
                        <tbody id="displayAgents">
                        </tbody>
                      </table>
                      <button type="submit" name="submit" id="removeAgentsSubmit" onClick={this.removeAgents}>Remove Agents <i id="arrow" className="fa fa-chevron-right"></i></button>
                    </div>
                  :
                    <div>
                      <div id="loading" className="Text-1"><span>Loading Agents...</span></div>
                      <div id="noAgents" className="Text-1">
                        <span>No Registered Agents</span>                        
                      </div>                      
                    </div>
                  }
                  
                </div>
                <div id="updateAgents">
                  <span className="Text-1" id="updateAgentsTitle">Update Agent Status</span>
                  <div id="agentsRegisteredSection">
                
                    <ul className="nav nav-tabs" role="tablist">
                      <li className="active"><a href="#activeAgents" aria-controls="activeAgents" role="tab" data-toggle="tab">Active Agents</a></li>
                      <li><a href="#archivedAgents" aria-controls="archivedAgents" role="tab" data-toggle="tab">Archived Agents</a></li>
                    </ul>

                    <div className="tab-content">
                      <div role="tabpanel" className="tab-pane active" id="activeAgents">
                        {this.state.registered_active_agents.length > 0 ?
                          <div>
                            <table id="activeAgents">
                              <tbody id="displayActiveAgents">
                              </tbody>
                            </table>
                            
                            <button type="submit" name="submit" id="updateAgentsSubmit" onClick={this.archiveAgents}>Archive Agents <i id="arrow" className="fa fa-chevron-right"></i></button>
                          </div>
                        :
                          <div>
                            <div id="loading" className="Text-1"><span>Loading Active Agents...</span></div>
                            <div id="noAgents" className="Text-1">
                              <span>No Active Agents</span>                        
                            </div>                      
                          </div>
                        }
                      </div>
                      <div role="tabpanel" className="tab-pane" id="archivedAgents">
                        {this.state.registered_archived_agents.length > 0 ?
                          <div>
                            <table id="archivedAgents">
                              <tbody id="displayArchivedAgents">
                              </tbody>
                            </table>
                            
                            <button type="submit" name="submit" id="updateAgentsSubmit" onClick={this.activateAgents}>Activate Agents <i id="arrow" className="fa fa-chevron-right"></i></button>
                          </div>
                        :
                          <div>
                            <div id="loading" className="Text-1"><span>Loading Archived Agents...</span></div>
                            <div id="noAgents" className="Text-1">
                              <span>No Archived Agents</span>                        
                            </div>                      
                          </div>
                        }
                      </div>                      
                    </div>
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
    <ManageAgents/>,
    document.getElementById("manageAgents")
  )

  ReactDOM.render(
	  <Footer mainPage={"<? echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>
