<?php
session_start();
include_once("functions.php");
include_once("basicHeadOld.php");

if((authentication() == 'agent') OR (authentication() == 'user')){ header('Location: menu.php'); };
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
$referrer = (isset($_GET['r']) ? $_GET['r'] : "registrationPage");
?>

	<title>HomePik - Buyer Registration</title>
	<meta name="robots" content="noindex">
  <?php include_css("/views/css/registration.css"); ?>
	<link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
	<?php include_once("analyticstracking.php") ?>
</head>
<body>
	<div id="registerBackground"></div>
	<div id="guestRegister"></div>
	<div id="primAgentPopupOverlay"></div>
	<div id="overlay"></div>
	<div id='ajax-box'></div>
<script type="text/babel">
	var AgentList = React.createClass({
		getInitialState: function() {
			return{
        agents: [],
      };
		},		
		componentDidMount: function(){
		  this.getAgents();
		},
		getAgents: function(){
		  $.ajax({
				type: "POST",
				url: "/controllers/get-agents.php",
				data: {"agent": "true", "new": "true"},
				success: function(data){
					var agents = JSON.parse(data);
					this.setState({agents: agents});
				}.bind(this),
				error: function(){
					console.log("failed");
				}.bind(this)
		  });
		},
    closePopup: function(){
		  $(".ui-widget-overlay").hide();
		  {this.props.closeDialog()}
		},
		render: function(){
			var agents = this.state.agents.map(function(agent) {
				return (
					<p>{agent}</p>
				);
			}.bind(this));
			return(
				<div>
          <div className="text-popups clearfix grpelem" id="closeAgentListPopup">
            <h4 style={{cursor: "pointer"}} onClick={this.closePopup}><i className="fa fa-times" title='close'></i></h4>
          </div>
          <h2 className="Subhead-2" id="u1330-2">List of Agents</h2>
          <h4 className="text-popups" id="u1330-3">&nbsp;</h4>
					<div id="agents" className="text-popups">{agents}</div>
        </div>
			);
		}
	});
	
	var PrimaryAgent = React.createClass({
    componentDidMount:function(){
      $("#primAgentPopupOverlay").bind('click',function(){
				$("#primaryAgent").hide();				
				$("#primAgentPopupOverlay").hide();
			});
    },
		closePopup: function(){
		  $("#primaryAgent").hide();
		  $("#primAgentPopupOverlay").hide();
		},
		listAgents: function(event){
		  event.preventDefault();
			
			var $dialog =  $("#ajax-box").dialog({
				modal: true,
				width: 350,
				dialogClass: 'agentListPopup',
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
			}.bind(this)

			ReactDOM.render(<AgentList closeDialog={closeDialog}/>, $dialog[0]);
		},
		render: function(){
		  return(
				<div id="primaryAgent" style={{display:"none"}}>
					<img id="closePopup" src="/images/close-x.png" style={{cursor: "pointer"}} onClick={this.closePopup} title='close'/>
					<div id="primAgentText" className="text-popups">
					By default, every listing in the system has a designated agent.
					You may elect to work with that agent or select a different agent, who will then become your Primary Agent for all listings.
					<br/><br/>
					<ul id="primAgentList">
						<li><p>You can change your primary agent at any time.</p></li>
						<li><p>You can have up to two primary agents.
							(For example, you may choose to work with one primary agent
							for the East Side and another primary agent for Downtown.)</p></li>
						<li><p>You can select an agent from any listing, or click <a id="agentList" style={{cursor: "pointer"}} onClick={this.listAgents}>here</a> to see a
							list of all agents.</p></li>
					</ul>
					<br/>
					It is not necessary to choose a primary agent. In that case, the agent associated with a listing will represent you on that property
					</div>
				</div>
		  );
		}
	});


	var Register = React.createClass({
		getInitialState: function() {
		  return{
				firstname: "<?php echo (isset($_GET['f']) ? $_GET['f'] : "") ?>",
				lastname: "<?php echo (isset($_GET['l']) ? $_GET['l'] : "") ?>",
				email: "<?php echo (isset($_GET['e']) ? $_GET['e'] : "") ?>",
				pass: "",
				phone: "<?php echo (isset($_GET['p']) ? $_GET['p'] : "") ?>",
				secQues: "default",
				secAns: "",
				agent: "<?php echo (isset($_GET['a']) ? $_GET['a'] : "") ?>"
		  };
		},
		componentDidMount: function() {
			window.addEventListener('scroll', this.handleScroll);
		},	
		componentWillUnmount: function() {
			window.removeEventListener('scroll', this.handleScroll);
		},
		handleScroll: function(){
			if($(window).scrollTop() + $(window).height() >= $(document).height()){ $("#moreInfoBox").hide(); }
			if($(window).scrollTop() + $(window).height() < $(document).height()){ $("#moreInfoBox").show(); }
		},
		handleChange: function (name, event) {
		  var change = {};
		  change[name] = event.target.value;
		  this.setState(change);
		},
		checkPQ: function(){
		  if(this.state.phone != ""){ return true; }
			else{
				if(this.state.secQues != "default" && this.state.secAns != ""){ return true; }
				else{ return false; }
		  }
		},
		checkInput: function(){
		  if( this.state.firstname != "" &&  this.state.lastname != "" && this.state.email != "" && this.state.pass != "" && (this.state.phone != "" || (this.state.secQues != "default" && this.state.secAns != "")) ){ return true; }
			else{ return false }
		},
		getAgents: function(){
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
		switchAgent: function(event){
		  this.setState({agent: event.target.value});
		  var code = event.target.value;

		  if(code.length > 3){
				var name = code.split(", ");
				var firstname = name[1].replace(" ", "");
				var lastname = name[0];

				$.ajax({
					type: "POST",
					url: "check-agent.php",
					data: {"getID":"true", "firstname": firstname, "lastname": lastname},
					success: function(data){
						var id = JSON.parse(data);
						this.setState({ agent: id });
					}.bind(this)
				});
		  }
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
		showPopup: function(){
			$(window).scrollTop(300);
		  $("#primAgentPopupOverlay").show();
		  $("#primaryAgent").show();
		},
		validate: function(e){
		  var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		  var emailValid = emailReg.test(this.state.email);

		  if( this.state.firstname == "" || this.state.lastname == "" || this.state.email == "" || this.state.pass == "" || (this.state.phone == "" && (this.state.secQues == "default" || this.state.secAns == "" ))){
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
				$('#ajax-box').load('messages.php #registerRquirements',function(){
					$('#ajax-box').dialog('open');
				});
				e.preventDefault();
		  }
		  else if(this.state.firstname.match(/[0-9]/g) != null || this.state.lastname.match(/[0-9]/g) != null){
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
				$('#ajax-box').load('messages.php #invalidName',function(){
					$('#ajax-box').dialog('open');
				});
				e.preventDefault();
		  }
		  else if(!emailValid){
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
				$('#ajax-box').load('messages.php #invalidBuyerEmail',function(){
					$('#ajax-box').dialog('open');
				});
				e.preventDefault();
		  }
		  else if(this.state.pass.length < 5){
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
					$('#ajax-box').dialog('open');
				});
				e.preventDefault();
		  }
		  else{
				if (!$('input[name=terms]:checked').length > 0) {
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
					$('#ajax-box').load('messages.php #registerDisclosure',function(){
						$('#ajax-box').dialog('open');
					});
					e.preventDefault();
				}
				else if($('input[value=no]:checked').length > 0){
					$("#ajax-box").dialog({
						modal: true,
						height: 'auto',
						width: 'auto',
						autoOpen: false,
						dialogClass: 'ajaxbox noRegistration',
						buttons : {
							Ok: function(){
								$(this).dialog("close");
								window.location.replace("http://www.homepik.com");
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
					$('#ajax-box').load('messages.php #noRegistration',function(){
						$('#ajax-box').dialog('open');
					});
					e.preventDefault();
				}
				else{ /* continue to processing page */ }
		  }
		},
		render: function(){
		  return(
				<div>
					<div id="wrapper">
						<div className="container-fluid">
							<div className="row">
								<div className="col-md-2 col-sm-2"></div>
								<div className="col-md-7 col-sm-10 col-xs-11">
									<div id="registrationArea">
										<div id="registrationTop">
											<span id="registrationHeader" className="text-popups">Buyer Registration</span>
											<img src="/images/button_pen_states.png" style={{float:"right"}}/>
										</div>
										<div id="registrationBorder">
											<form onSubmit={this.validate} action="http://homepik.com/controllers/users/guest-register-process.php" id="validate" className="validate" method="post" autoComplete="off" data-bind="nextFieldOnEnter:true">
												<table cellPadding="2" cellSpacing="0" border="0">
													<tbody>
														<tr>
															<td className="text-popups">First Name:</td>
															<td className="text-popups"><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" value={this.state.firstname} autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.state.firstname != "" ? null : <strong id="firstnameMark" className="asterisk"> {'\u002A'}</strong> }<br/></td>
														</tr>
														<tr>
															<td className="text-popups">Last Name:</td>
															<td className="text-popups"><input type="text" id="formLastName" className="grade_desc input1" name="lastName" value={this.state.lastname} onChange={this.handleChange.bind(this, 'lastname')} />{this.state.lastname != "" ? null : <strong id="lastnameMark" className="asterisk"> {'\u002A'}</strong> }<br/></td>
														</tr>
														<tr>
															<td className="text-popups">Email:</td>
															<td className="text-popups"><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} />{this.state.email != "" ? null : <strong id="emailMark" className="asterisk"> {'\u002A'}</strong> }</td>
														</tr>
														<tr>
															<td className="text-popups">Create Password:</td>
															<td className="text-popups"><input type="password" id="formPass" className="grade_desc input1" name="password" autoComplete="new-password" onChange={this.handleChange.bind(this, 'pass')} />{this.state.pass != "" ? null : <strong id="passwordMark" className="asterisk"> {'\u002A'}</strong> }</td>
														</tr>
														<tr>
															<td className="text-popups">Agent Code:<br/><i>(optional)</i>&nbsp;
															<i id="agentMoreInfo" className="fa fa-question-circle" style={{width:15+"px", cursor: "pointer"}} onClick={this.showPopup} title='more info'></i></td>
															<td className="text-popups"><input type="text" id="formAgent" className="agent-code input1" name="agent-code" value={this.state.agent} onChange={this.handleChange.bind(this, 'agent')} onFocus={this.getAgents} onBlur={this.switchAgent}/></td>
														</tr>
														<tr>
															<td colSpan="2">&nbsp;</td>
														</tr>
														<tr>
															<td className="text-popups" colSpan="2">Please enter your phone number or select a security question.{this.checkPQ() ? null : <strong id="phoneMark" className="asterisk"> {'\u002A'}</strong> }</td>
														</tr>
														<tr>
															<td className="text-popups" style={{paddingBottom: 0 + 'px !important'}}>Phone:</td>
															<td className="text-popups" style={{paddingBottom: 0 + 'px !important'}}><input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></td>
														</tr>
														<tr><td className="text-popups" style={{paddingBottom: 0 + 'px !important'}}>OR</td></tr>
														<tr>
															<td className="text-popups">Security Question:</td>
															<td className="text-popups"><select id="formQuestion" className="input2" name="security-question" onChange={this.handleChange.bind(this, 'secQues')}>
																<option value="default" selected="selected">Select A Security Question</option>
																<option value="1">What is your middle name?</option>
																<option value="2">What is your mother's maiden name?</option>
																<option value="3">What was the name of the street where you grew up?</option>
																<option value="4">What is your favorite food?</option>
															</select></td>
														</tr>
														<tr>
															<td className="text-popups">Security Answer:</td>
															<td className="text-popups"><input type="text" id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/></td>
														</tr>
														<tr>
															<td className="text-popups" colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong style={{color:'#D2008F', float:"right"}}> {'All Fields Filled'}</strong> : <strong style={{color:'#D2008F', float:"right"}}> {'\u002A Required Fields'}</strong> }</td>
														</tr>
														<tr>
															<td className="text-popups" colSpan="2"><input type="radio" className="required" name="terms" value="yes"/><label for="terms"> I acknowledge and consent to the New York State Disclosure Form for Buyer and Seller</label></td>
														</tr>
														<tr>
															<td className="text-popups" colSpan="2"><input type="radio" className="required" name="terms" value="no"/><label for="terms"> I <span className="underline">do not</span> consent to the New York State Disclosure Form for Buyer and Seller (will not be registered)</label></td>
														</tr>
														<tr>
															<td className="text-popups" colSpan="2"><div id="terms-box" className="terms-box">
																<span style={{fontSize: 1.2 + "em"}}>THIS IS NOT A CONTRACT</span><br/>
																<i>New York State law requires real estate licensees who are acting as agents of buyers or sellers of
																property to advise the potential buyers or sellers with whom they work of the nature of their agency
																relationship and the rights and obligations it creates. This disclosure will help you to make informed
																choices about your relationship with the real estate broker and its sales agents.<br/>
																Throughout the transaction you may receive more than one disclosure form. The law may require each agent
																assisting in the transaction to present you with this disclosure form. A real estate agent is a person
																qualified to advise about real estate.<br/>
																If you need legal, tax or other advice, consult with a professional in that field.</i><br/><br/>
						
																<b>Disclosure Regarding Real Estate Agency Relationships</b><br/><br/>
						
																<b>Seller’s Agent</b><br/>
																A seller’s agent is an agent who is engaged by a seller to represent the seller’s interests. The seller’s
																agent does this by securing a buyer for the seller’s home at a price and on terms acceptable to the seller.
																A seller’s agent has, without limitation, the following fiduciary duties to the seller: reasonable care,
																undivided loyalty, confidentiality, full disclosure, obedience and duty to account. A seller’s agent does
																not represent the interests of the buyer. The obligations of a seller’s agent are also subject to any specific
																provisions set forth in an agreement between the agent and the seller. In dealings with the buyer, a seller’s
																agent should (a) exercise reasonable skill and care in performance of the agent’s duties; (b) deal honestly,
																fairly and in good faith; and (c) disclose all facts known to the agent materially affecting the value or
																desirability of property, except as otherwise provided by law.<br/><br/>
						
																<b>Buyer’s Agent</b><br/>
																A buyer’s agent is an agent who is engaged by a buyer to represent the buyer’s interests. The buyer’s agent
																does this by negotiating the purchase of a home at a price and on terms acceptable to the buyer. A buyer’s
																agent has, without limitation, the following fiduciary duties to the buyer: reasonable care, undivided loyalty,
																confidentiality, full disclosure, obedience and duty to account. A buyer’s agent does not represent the interest
																of the seller. The obligations of a buyer’s agent are also subject to any specific provisions set forth in an
																agreement between the agent and the buyer. In dealings with the seller, a buyer’s agent should (a) exercise
																reasonable skill and care in performance of the agent’s duties; (b) deal honestly, fairly and in good faith;
																and (c) disclose all facts known to the agent materially affecting the buyer’s ability and/or willingness to
																perform a contract to acquire seller’s property that are not inconsistent with the agent’s fiduciary duties
																to the buyer.<br/><br/>
						
																<b>Broker’s Agents</b><br/>
																A broker’s agent is an agent that cooperates or is engaged by a listing agent or a buyer’s agent (but does not
																work for the same firm as the listing agent or buyer’s agent) to assist the listing agent or buyer’s agent in
																locating a property to sell or buy, respectively, for the listing agent’s seller or the buyer agent’s buyer.
																The broker’s agent does not have a direct relationship with the buyer or seller and the buyer or seller can not
																provide instructions or direction directly to the broker’s agent. The buyer and the seller therefore do not
																have vicarious liability for the acts of the broker’s agent. The listing agent or buyer’s agent do provide
																direction and instruction to the broker’s agent and therefore the listing agent or buyer’s agent will have
																liability for the acts of the broker’s agent.<br/><br/>
						
																<b>Dual Agent</b><br/>
																A real estate broker may represent both the buyer and seller if both the buyer and seller give their informed
																consent in writing. In such a dual agency situation, the agent will not be able to provide the full range of
																fiduciary duties to the buyer and seller. The obligations of an agent are also subject to any specific provisions
																set forth in an agreement between the agent, and the buyer and seller. An agent acting as a dual agent must explain
																carefully to both the buyer and seller that the agent is acting for the other party as well. The agent should also
																explain the possible effects of dual representation, including that by consenting to the dual agency relationship
																the buyer and seller are giving up their right to undivided loyalty. A buyer or seller should carefully consider
																the possible consequences of a dual agency relationship before agreeing to such representation. A seller or buyer
																may provide advance informed consent to dual agency by indicating the same on this form.<br/><br/>
						
																<b>Dual Agent with Designated Sales Agents</b><br/>
																If the buyer and seller provide their informed consent in writing, the principals and the real estate broker who
																represents both parties as a dual agent may designate a sales agent to represent the buyer and another sales agent
																to represent the seller to negotiate the purchase and sale of real estate. A sales agent works under the supervision
																of the real estate broker. With the informed consent of the buyer and the seller in writing, the designated sales
																agent for the buyer will function as the buyer’s agent representing the interests of and advocating on behalf of
																the buyer and the designated sales agent for the seller will function as the seller’s agent representing the interests
																of and advocating on behalf of the seller in the negotiations between the buyer and seller. A designated sales agent
																cannot provide the full range of fiduciary duties to the buyer or seller. The designated sales agent must explain that
																like the dual agent under whose supervision they function, they cannot provide undivided loyalty. A buyer or seller
																should carefully consider the possible consequences of a dual agency relationship with designated sales agents before
																agreeing to such representation. A seller or buyer may provide advance informed consent to dual agency with designated
																sales agents by indicating the same on this form.<br/><br/>
						
																This form was provided to me by Bellmarc Realty LLC & it's associated agents of Bellmarc Realty
																LLC, a licensed real estate broker acting in the interest of the:<br/>
																(__) Seller as a (check relationship below<br/>
																&nbsp;&nbsp;(__) Seller's agent<br/>
																&nbsp;&nbsp;(__) Broker's agent<br/>
						
																(X) Buyer as a (check relationship below)<br/>
																&nbsp;&nbsp;(X) Buyer's agent<br/>
																&nbsp;&nbsp;(__) Broker's agent<br/>
						
																(X) Dual Agent<br/>
																(__) Dual agent with designated sales agent<br/><br/>
						
																For advance informed consent to either dual agency or dual agency with designated sales agents complete section below:
																&nbsp;&nbsp; (X) Advance informed consent dual agency<br/>
																&nbsp;&nbsp; (__) Advance informed consent to dual agency with designatd sales agents<br/><br/>
						
																If dual agent with designated sales agents is indicated above: <span id="buyerPrintName">{this.state.firstname} {this.state.lastname}</span> is appointed to represent
																the buyer: and (N/A) is appointed to represent the seller in this transaction. (I)(We) <span id="buyerSignature">{this.state.firstname} {this.state.lastname} </span>
																acknowledge receipt of a copy of this disclosure form.
															</div></td>
														</tr>
														<tr>
															<td colSpan="2"  align="center">
																<input type="hidden" name="formStep" value="guest-register" />
																<input type="hidden" name="referrer" value="<?php echo $referrer?>" />
																<button type="submit" name="submit" id="registrationSubmit" className="text-popups">Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
															</td>
														</tr>
													</tbody>
												</table>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<PrimaryAgent/>
				</div>
		  );
		}
	});

	ReactDOM.render(
		<Register/>,
		document.getElementById("guestRegister")
	);
</script>
</body>
</html>
