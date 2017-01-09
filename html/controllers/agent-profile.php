<?php
session_start();
include_once("dbconfig.php");
include_once("functions.php");
include_once("basicHead.php");
$db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("Connection Error: " . mysql_error());
mysql_select_db($database) or die("Error connecting to db.");

if(isset($_SESSION['agent'])){
  $first_name = $_SESSION['firstname'];
  $last_name = $_SESSION['lastname'];
  $agent_id = $_SESSION['agent_id'];
  $email = $_SESSION['email'];
  $phone = $_SESSION['phone'];
  
  $result = mysql_query( "SELECT title, active, admin, bio FROM `registered_agents` WHERE (email = '".$email."')" ) or die("Couldn't execute query.".mysql_error());
  $row = mysql_fetch_array($result,MYSQL_ASSOC);
  $title = $row['title'];
  $active = $row['active'];
  $admin = $row['admin'];
  $bio = $row['bio'];
}
else{ print "<script> window.location = '/users/logout.php' </script>"; }

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");
?>

  <title>HomePik - My Profile</title>
  <?php include_css("/views/css/agent-profile.css");
  include_once('autoLogout.php'); ?>
</head>
<body>
  <div id="agentProfile"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
  <div id='ajax-box3'></div>
<script type="text/babel">
	var Profile = React.createClass({
	  getInitialState: function() {
      return{
        name: "<?php echo (isset($first_name) ? $first_name : "") . " " . (isset($last_name) ? $last_name : "") ?>",
        title: "<?php echo (isset($title) ? $title : "") ?>",
        email: "<?php echo (isset($email) ? $email : "") ?>",
        old_email: "",
        phone: "<?php echo (isset($phone) ? $phone : "") ?>",
        agent_id: "<?php echo (isset($agent_id) ? $agent_id : "") ?>",
        active: "<?php echo (isset($active) ? $active : "") ?>",
        admin: "<?php echo (isset($admin) ? $admin : "") ?>",
        bio: "<?php echo (isset($bio) ? $bio : "") ?>",        
        month: "default",
        day: "default",
        year: "default",
        activity: [],
        years: [],
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>",
        editing: "false"
      };
	  },
	  componentDidMount: function(){
      var currentTime = new Date(); // Return today's date and time
      var year = parseInt(currentTime.getFullYear()); // returns the year (four digits) as integer
      var dates = [];
      for(var i=2015; i <= year; i++){
        dates.push(i);
      }
      this.setState({years: dates});
	  },
	  handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
	  },
	  Edit: function(){
      this.setState({editing: "true"});
      this.setState({old_email: this.state.email});
	  },
	  Save: function(){
      var name = this.state.name;
      var phone = this.state.phone;
      var email = this.state.email;
      var old_email = this.state.old_email;
      var bio = this.state.bio;

      if(name != "" && phone != "" && email != ""){ this.setState({editing: "false"}); }

      if(name == ""){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage blankName',
          buttons: {
            close: function(){
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
        $('#ajax-box2').load('messages.php #blank_name',function(){
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        var nameArray = name.split(" ");

        $.ajax({
          type: "POST",
          url: "change-agent-info.php",
          data: {"firstName":nameArray[0], "lastName":nameArray[1]},
          success: function(data){
            console.log("success");
          }
        });
      }

      if(phone == ""){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage blankPhone',
          buttons: {
            close: function(){
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
        $('#ajax-box2').load('messages.php #blank_phone',function(){
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        var x = phone.replace(/\D/g,'');
        if (x.length == 10){
          var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
          phone = y;
        }

        $.ajax({
          type: "POST",
          url: "change-agent-info.php",
          data: {"phone":phone},
          success: function(data){
            console.log("success")
          }
        });
      }

      if(email == ""){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage blankEmail',
          buttons: {
            close: function(){
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
        $('#ajax-box2').load('messages.php #blank_email',function(){
          $('#ajax-box2').dialog('open');
        });
      }
      else{
        $.ajax({
          type: "POST",
          url: "change-agent-info.php",
          data: {"email":email},
          success: function(data){
            console.log("success");
          }
        });
      }
      
      $.ajax({
        type: "POST",
        url: "change-agent-info.php",
        data: {"bio":this.state.bio},
        success: function(data){
          console.log("success");
        }
      });
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
            dialogClass: 'ajaxbox errorMessage invalidPhone',
            buttons : {
              close: function(){
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
					$('#ajax-box').load('messages.php #invalid_phone',function(){
						$('#ajax-box').dialog('open');
					});
				}
		  }
		},
    checkDate: function(){
      var m = this.state.month;
      var d = this.state.day;
      var y = this.state.year;
      var status = true;
      
      if( (m == 1 || m == 3 || m == 5 || m == 7 || m == 8 || m == 10 || m == 12) && (d <= 31 || d == "default") ){ status = true; } // Check if month has 31 days
      else if( (m == 4 || m == 6 || m == 9 || m == 11) && (d <= 30 || d == "default") ){ status = true; } // Check if month has 30 days
      else if( m == 2 && d == "default" ){ status = true; } // February only selected no day
      else if( m == 2 && d != "default" ){ // February and day selected 
        if( ( ( ( y % 4 == 0 ) && ( y % 100 != 0) ) || ( year % 400 == 0 ) ) && (d <= 29 || d == "default" ) ){ status = true; } // Leap year day <= 29
        else{
          if( d <= 28 ){ status = true; } // Not leap year day <= 28
          else{ status = false; } // Invalid date
        }
      }
      else{ status = false; } // Invalid date
      
      if(status){
        if(d == "default"){ d = 1; }
        var inputDate = new Date(m + "/" + d + "/" + y); // Create date from input value
        var todaysDate = new Date(); // Get today's date
              
        // setHours to 0 to remove time
        if(inputDate.setHours(0,0,0,0) <= todaysDate.setHours(0,0,0,0)){ status = true; }
        else{ status = false; }
      }
      
      return status;
    },
    getActivity: function(){
      if(this.state.month == "default" || this.state.year == "default"){ // Check if a date is defaulted
        $("#ajax-box").dialog({
					modal: true,
					height: 'auto',
					width: 'auto',
					autoOpen: false,
					dialogClass: 'ajaxbox errorMessage needDate',
					buttons : {
						close: function(){
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
        $('#ajax-box').load('messages.php #activity_report_need_date',function(){
          $('#ajax-box').dialog('open');
        });
      }
      else{
        if(this.checkDate()){
          $.ajax({
            type: "POST",
            url: "get-activity.php",
            data: {"user": "agent", "email": this.state.email, "id": this.state.agent_id, "month": this.state.month, "day": this.state.day, "year": this.state.year},
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
            dialogClass: 'ajaxbox errorMessage invalidDate',
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
          $('#ajax-box').load('messages.php #activity_report_invalid_date',function(){
            $('#ajax-box').dialog('open');
          });
        }
      }
    },
	  render:function(){
      var years = this.state.years.map(function(year) {
        return(
          <option value={year}>{year}</option>
        );
      }.bind(this));
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar mainPage={this.state.mainPage} />
            <AddressSearch mainPage={this.state.mainPage} />
            <div className="container-fluid agent-profile-container">
              <div className="row">
                <div className="col-md-4 col-sm-6 col-xs-6" id="profile-section">
                  <div className="clearfix grpelem" id="u7124-6">
                    <p id="u7124-4"><span id="u7124">My agent profile</span><span id="u7124-2">{'\u00A0'} </span><span id="u7124-3">{this.state.editing == "true" ? <a style={{cursor: "pointer"}} onClick={this.Save}>save</a> : <a style={{cursor: "pointer"}} onClick={this.Edit}>edit</a>}</span></p>
                  </div>
                  <div className="Text-1 clearfix grpelem" id="u7132-18">
                    <p>Name</p>
                    <p>Title</p>
                    <p>Phone</p>
                    <p>Email</p>
                    <p>Agent ID</p>
                    <p>Status</p>
                    <p>Administrator</p>
                    <p>Bio</p>
                  </div>

                  <div className="Text-1 clearfix grpelem" id="u7123-19">
                    {this.state.editing == "true" ?
                      <div>
                        <p><input type="text" name="agentName" id="agentName" className="agent-name input" value={this.state.name} onChange={this.handleChange.bind(this, 'name')}/></p>
                        <p>{this.state.title}</p>
                        <p><input type="text" name="agentPhone" id="agentPhone" className="agent-phone input" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></p>
                        <p><input type="text" name="agentEmail" id="agentEmail" className="agent-email input" value={this.state.email} onChange={this.handleChange.bind(this, 'email')}/></p>
                        <p>{this.state.agent_id}</p>
                        <p>{this.state.active == "Y" ? <span>Active</span> : <span>Not Active</span>}</p>
                        <p>{this.state.admin == "Y" ? <span>Yes</span> : <span>No</span>}</p>
                        <p><textarea type="text" name="bio" id="agentBio" className="agent-bio input" value={this.state.bio} onChange={this.handleChange.bind(this, 'bio')}/></p>
                      </div>
                    :
                      <div>
                        <p>{this.state.name}</p>
                        <p>{this.state.title}</p>
                        {this.state.phone != "" ? <p>{this.state.phone}</p> : <p>{'\u00A0'}</p> }
                        <p>{this.state.email}</p>
                        <p>{this.state.agent_id}</p>
                        <p>{this.state.active == "Y" ? <span>Active</span> : <span>Not Active</span>}</p>
                        <p>{this.state.admin == "Y" ? <span>Yes</span> : <span>No</span>}</p>
                        <p>{this.state.bio}</p>
                      </div>
                    }
                    <p id="u7132-20"><span id="u7132-19"><a href={"change-password.php?MP="+this.state.mainPage}>Change password</a></span></p>
                  </div>
                </div>

                <div className="col-md-8 col-sm-10 col-xs-12" id="activity-section">
                  <div className="clearfix grpelem" id="u7137-86">
                    <p className="Text-1" id="u7137-8"><span id="u7137-2">Activity</span></p>
                    <div id="userActivity" className="Text-1">
                      Select a date to view activity: 
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
                        {years}
                      </select>
                      <button onClick={this.getActivity}>View Activity</button>
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
                              <td>Listings Saved to Agent Folder: </td><td>{this.state.activity['listingsSavedFolder']} listings</td>
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
                </div>
              </div>
            </div>
          </div>
        </div>
      );
    }
	});

	ReactDOM.render(
	  <Profile/>,
	  document.getElementById("agentProfile")
	);

	ReactDOM.render(
	  <Footer mainPage={"<?php echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);
</script>
</body>
</html>
