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
  var EditAgentInfo = React.createClass({
    getInitialState: function(){
      return{
        firstname: this.props.info['first_name'],
        lastname: this.props.info['last_name'],
        title: this.props.info['title'],        
        oldEmail: this.props.info['email'],
        email: this.props.info['email'],
        phone: this.props.info['phone'],
        agentID: this.props.info['agent_id'],
        status: this.props.info['active'],
        admin: this.props.info['admin'],
        bio: this.props.info['bio'],
        newPass: "",
        confPass: "",
        edit: "false",
        changePassword: "false"
      }
    },    
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    handleEditOptionChange: function(name,event){
      this.setState({edit: "true"});
    },
    handleChangePasswordOption: function(name,event){
      this.setState({changePassword: "true"});
    },
    updatePhone: function(){
		  var number = this.state.phone;
		  var x = number.replace(/\D/g,'');

		  if (x.length == 10 && !isNaN(x)){
				var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
				this.setState({ phone: y }); // Replace number with formatted number
		  }else {
				if(x.length > 0 || (x.length <= 0 && number.match(/[a-z]/i))){
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
					$('#ajax-box').load('messages.php #invalidBuyerPhone',function(){
						$('#ajax-box').dialog('open');
					});
				}
		  }
		},
    checkPassword: function(){
      if( this.state.changePassword == "true" && this.state.newPass != "" && this.state.confPass != "" && this.state.newPass.length >= 5 && this.state.confPass.length >= 5 ){
        if(this.state.newPass == this.state.confPass){ return true; }
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
          $('#ajax-box').load('messages.php #passwordsMatch',function(){
            $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
          });
          
          return false;
        }
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
        $('#ajax-box').load('messages.php #passwordRequirement',function(){
          $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
        });
        
        return false;
      }
    },
    submitInfo: function(){
      if(this.checkPassword()){
        $.get("ajax.php", {
          updateAgent: 'true',
          oldEmail: this.state.oldEmail,
          firstname: this.state.firstname,
          lastname: this.state.lastname,
          title: this.state.title,
          email: this.state.email,
          phone: this.state.phone,
          status: this.state.status,
          admin: this.state.admin,
          bio: this.state.bio,
          newPass: this.state.newPass,
          success: function(result){
            console.log(result);
            var ajaxStop = 0;
            $(document).ajaxStop(function() {
              if(ajaxStop == 0){
                ajaxStop++;
                this.setState({edit: "false"});
                this.setState({changePassword: "false"});
                this.props.getActiveAgents();
                this.props.getArchivedAgents();
              }
            }.bind(this));
          }.bind(this)
        });
      }
    },
    closePopup: function(){
      $("#overlay").hide();
      {this.props.closeDialog()}
    },
    render: function(){
      return(
        <div id="agentInformationArea">
          <div id="agentInformationTop">
            <span id="agentInformationHeader" className="text-popups">Agent Information</span>
            <img src="/images/button_pen_states.png" style={{float: "right"}}/>
            <h4 id="closePopup" onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
          </div>
          <div id="agentInformationBorder">
            <table cellPadding="2" cellSpacing="0" border="0">
              <colgroup><col width="250"/><col width="350"/></colgroup>
              <tbody>
                <tr>
                  <td className="text-popups">First Name:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formFirstName" className="grade_desc input1" value={this.state.firstname} name="firstName" autoFocus onChange={this.handleChange.bind(this, 'firstname')} /> : this.state.firstname }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups">Last Name:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <input type="text" id="formLastName" className="grade_desc input1" value={this.state.lastname} name="lastName" onChange={this.handleChange.bind(this, 'lastname')} /> : this.state.lastname }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups">Title:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formTitle" className="grade_desc input1" value={this.state.title} name="title" onChange={this.handleChange.bind(this, 'title')} /> : this.state.title }</td>
                </tr>
                <tr>
                  <td className="text-popups">Email:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} /> : this.state.email }</td>
                </tr>
                <tr>
                  <td className="text-popups">Phone:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formPhone" className="grade_desc input1" value={this.state.phone} name="phone" onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/> : this.state.phone }</td>
                </tr>
                <tr>
                  <td className="text-popups">Agent ID:</td>
                  {/* <td className="text-popups">{this.state.edit == "true" ? <input type="text" autoCapitalize="off" id="formAgentID" className="grade_desc input1" value={this.state.agentID} name="phone" onChange={this.handleChange.bind(this, 'agentID')} /> : this.state.agentID }</td> */}
                  <td className="text-popups">{this.state.agentID}</td>
                </tr>
                <tr>
                  <td className="text-popups">Status:</td>
                  <td className="text-popups">
                    {this.state.edit == "true" ?
                      <span>
                        {this.state.status == "Y" ? <input type="radio" name="status" value="Y" className="indent" checked onChange={this.handleChange.bind(this, 'status')}/> : <input type="radio" name="status" value="Y" className="indent" onChange={this.handleChange.bind(this, 'status')}/> } Active &nbsp;&nbsp;&nbsp;
                        {this.state.status == "N" ? <input type="radio" name="status" value="N" checked onChange={this.handleChange.bind(this, 'status')}/> : <input type="radio" name="status" value="N" onChange={this.handleChange.bind(this, 'status')}/> } Not Active
                      </span>
                    :
                      <span>{this.state.status == "Y" ? <span>Active</span> : <span>Not Active</span> }</span>
                    }
                  </td>
                </tr>                  
                <tr>
                  <td className="text-popups">Administrator:</td>
                  <td className="text-popups">
                    {this.state.edit == "true" ?
                      <span>
                        {this.state.admin == "Y" ? <input type="radio" name="admin" value="Y" checked className="indent" onChange={this.handleChange.bind(this, 'admin')}/> : <input type="radio" name="admin" value="Y" className="indent" onChange={this.handleChange.bind(this, 'admin')}/> } Yes &nbsp;&nbsp;&nbsp;
                        {this.state.admin == "N" ? <input type="radio" name="admin" value="N" checked onChange={this.handleChange.bind(this, 'admin')}/> : <input type="radio" name="admin" value="N" onChange={this.handleChange.bind(this, 'admin')}/> } No
                      </span>
                    :
                      <span>{this.state.admin == "Y" ? <span>Yes</span> : <span>No</span> } </span>
                    }
                  </td>
                </tr>                                  
                <tr>
                  <td className="text-popups">Bio:</td>
                  <td className="text-popups">{this.state.edit == "true" ? <textarea type="text" id="formBio" value={this.state.bio} name="bio" onChange={this.handleChange.bind(this, 'bio')} /> : this.state.bio }</td>
                </tr>
                {this.state.changePassword == "true" ?
                  <tr>
                    <td className="text-popups">New Password: </td>
                    <td className="text-popups"><input type="password" className="users input1 required newPassword" name="newPassword" size="25" autocomplete="off" onChange={this.handleChange.bind(this, 'newPass')} /></td>
                  </tr>
                : null }
                {this.state.changePassword == "true" ?
                  <tr>
                    <td className="text-popups">Retype Password: </td>
                    <td className="text-popups"><input type="password" className="users input1 required confirmPassword" name="confirmPassword" autocomplete="off" size="25"onChange={this.handleChange.bind(this, 'confPass')} /></td>
                  </tr>
                : null }
                {this.state.edit == "true" && this.state.changePassword == "false" ?
                  <tr id="editInformation">
                    <td className="text-popups" colSpan="2">
                      <a className="indent" style={{cursor: 'pointer'}} onClick={this.handleChangePasswordOption}>change password</a>
                    </td>
                  </tr>
                : null }  
                <tr><td></td></tr>
                {this.state.edit == "true" ?
                  <tr>
                    <td colSpan="2">
                      <button type="submit" id="editAgentInformationSubmit" className="text-popups" onClick={this.submitInfo}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                    </td>
                  </tr>
                :
                  <tr id="editInformation">
                    <td className="text-popups" colSpan="2">
                      Do you want to edit this information?
                      <span id="editOptions">
                        {this.state.edit == "true" ? <span className="indent"><i className="fa fa-check"></i> yes</span> : <span className="indent" onClick={this.handleEditOptionChange}><i className="fa fa-circle-thin"></i> yes</span> }
                        <span onClick={this.closePopup}><i className="fa fa-circle-thin"></i> no</span>
                      </span>
                    </td>
                  </tr>
                }                          
              </tbody>
            </table>
          </div>
        </div>
      );
    }    
  });
  
  var ManageAgents = React.createClass({
    getInitialState: function() {
      return{
        mainPage: "<? echo $mainPage ?>",
        registered_agents: [],
        registered_active_agents: [],
        registered_archived_agents: [],
        addAgent_firstname: "",
        addAgent_lastname: "",
        addAgent_title: "",
        addAgent_email: "",
        addAgent_phone: "",
        addAgent_agentId: "",
        addAgent_status: "",
        addAgent_admin: "",
        agentTableDisplay: ""
      };
	  },
    componentDidMount: function(){
      this.getAgents();
      this.getActiveAgents();
      this.getArchivedAgents();
      
      $("body").delegate(".updateAgentA","click", function(e){
        var email = e.target.id;
        this.editAgent(email);
      }.bind(this));
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
        this.setState({registered_agents: agents});
        this.displayAgents(agents, "all");
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
          this.setState({registered_active_agents: agents});
          this.displayAgentsLinked(agents, "active");
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
          this.setState({registered_archived_agents: agents});
          this.displayAgentsLinked(agents, "archived");
        }.bind(this),
        error: function(){
          console.log("failed");
        }
		  });
    },
    editAgent: function(email){    
      $.ajax({
        type: "POST",
        url: "get-registered-agents.php",
        data: {"information": "true", "email": email},
        success: function(data){
          var info = JSON.parse(data);
          
          var $dialog =  $("#ajax-box").dialog({
            width: 560,
            dialogClass: 'ajaxbox editAgentInfoPopup',
            close: function(){
              ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
              var div = document.createElement('div');
              div.id = 'ajax-box';
              document.getElementsByTagName('body')[0].appendChild(div);
              $( this ).remove();
            },
            open: function(){
              $("#overlay").bind("click", function(){
                $("#ajax-box").dialog('close');
                $("#overlay").hide();
              });
            }
          });
          var closeDialog = function(){
            $dialog.dialog('close');
          }
    
          $("#overlay").show();
          ReactDOM.render(<EditAgentInfo closeDialog={closeDialog} info={info} getActiveAgents={this.getActiveAgents} getArchivedAgents={this.getArchivedAgents}/>, $dialog[0]);
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
          if(group[j]['admin'] == "Y"){ table += "<td><input type='checkbox' name='deleteAgent' value='"+group[j]['email']+"'/> <label>"+group[j]['last_name']+", "+group[j]['first_name']+" (A)</label></td>"; }
          else{ table += "<td><input type='checkbox' name='deleteAgent' value='"+group[j]['email']+"'/> <label>"+group[j]['last_name']+", "+group[j]['first_name']+"</label></td>"; }          
        }
        table += "</tr>";
        
        group = [];
        row++;
      }
      
      $("#displayAgents").html(table);
      $("#loading").hide();
      $("#noAgents").show();     
    },
    displayAgentsLinked: function(agents, section){
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
          if(group[j]['admin'] == "Y"){ table += "<td><input type='checkbox' name='updateAgent' value='"+group[j]['email']+"'/> <label><a class='updateAgentA' id='"+group[j]['email']+"'>"+group[j]['last_name']+", "+group[j]['first_name']+" (A)</a></label></td>"; }
          else{ table += "<td><input type='checkbox' name='updateAgent' value='"+group[j]['email']+"'/> <label><a class='updateAgentA' id='"+group[j]['email']+"'>"+group[j]['last_name']+", "+group[j]['first_name']+"</a></label></td>"; }          
        }
        table += "</tr>";
        
        group = [];
        row++;
      }
      
      if(section == "active"){ $("#displayActiveAgents").html(table); }
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
        title: this.state.addAgent_title,
        phone: this.state.addAgent_phone,
        agent_id: this.state.addAgent_agentId,
        status: this.state.addAgent_status,
        success: function(result){
          console.log(result);
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
          this.setState({addAgent_title: ""});
          this.setState({addAgent_phone: ""});
          this.setState({addAgent_agentId: ""});
          this.setState({addAgent_status: ""});          
          this.setState({addAgent_admin: ""});
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
      
      $("#updateAgents #activeAgentsTable input[name=updateAgent]:checked").each(function(){ agents.push($(this).val()) });
            
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
      
      $("#updateAgents #archivedAgentsTable input[name=updateAgent]:checked").each(function(){ agents.push($(this).val()) });
            
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
    updatePhone: function(){
		  var number = this.state.addAgent_phone;
		  var x = number.replace(/\D/g,'');

		  if (x.length == 10 && !isNaN(x)){
				var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
				this.setState({ addAgent_phone: y }); // Replace number with formatted number
		  }else {
				if(x.length > 0 || (x.length <= 0 && number.match(/[a-z]/i))){
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
					$('#ajax-box').load('messages.php #invalidBuyerPhone',function(){
						$('#ajax-box').dialog('open');
					});
				}
		  }
		},
	  render: function(){
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
                          <td>Title: </td><td><input className="input1" name="title" value={this.state.addAgent_title} onChange={this.handleChange.bind(this, 'addAgent_title')}/></td>
                        </tr>
                        <tr>
                          <td>Email: </td><td><input className="input1" name="email" value={this.state.addAgent_email} onChange={this.handleChange.bind(this, 'addAgent_email')}/></td>
                          <td>Phone: </td><td><input className="input1" name="phone" value={this.state.addAgent_phone} onChange={this.handleChange.bind(this, 'addAgent_phone')} onBlur={this.updatePhone}/></td>
                          <td>Agent ID: </td><td><input className="input1" name="agentId" value={this.state.addAgent_agentId} onChange={this.handleChange.bind(this, 'addAgent_agentId')}/></td>                                                                           
                        </tr>
                        <tr>
                          <td>Status: </td><td><input type="radio" name="status" value="Y" className="indent" onChange={this.handleChange.bind(this, 'addAgent_status')}/> Active &nbsp;&nbsp;&nbsp;<input type="radio" name="status" value="N" onChange={this.handleChange.bind(this, 'addAgent_status')}/> Not Active</td>
                          <td>Administrator: </td><td><input type="radio" name="admin" value="Y" className="indent" onChange={this.handleChange.bind(this, 'addAgent_admin')}/> Yes &nbsp;&nbsp;&nbsp;<input type="radio" name="admin" value="N" onChange={this.handleChange.bind(this, 'addAgent_admin')}/> No</td>
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
                  <span className="Text-1" id="updateAgentsTitle">Update Agent Status / Profile <span id="u16159-2">click name to edit profile</span></span>
                  <div id="agentsRegisteredSection">
                
                    <ul className="nav nav-tabs" role="tablist">
                      <li className="active"><a href="#activeAgents" aria-controls="activeAgents" role="tab" data-toggle="tab">Active Agents</a></li>
                      <li><a href="#archivedAgents" aria-controls="archivedAgents" role="tab" data-toggle="tab">Archived Agents</a></li>
                    </ul>

                    <div className="tab-content">
                      <div role="tabpanel" className="tab-pane active" id="activeAgents">
                        {this.state.registered_active_agents.length > 0 ?
                          <div>
                            <table id="activeAgentsTable">
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
                            <table id="archivedAgentsTable">
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
