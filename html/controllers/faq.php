<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
$_SESSION['viewingBuyer'] = 'false';
$_SESSION['loadSaved'] = false;

if(!isset($_SESSION['email'])){
  $_SESSION['email'] = 'guest@email.com';
  $_SESSION['role'] = 'guest';
}

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "");
$section = (isset($_GET['section']) ? $_GET['section'] : "general");
?>

  <title>HomePik - FAQs</title>
  <?php include_css("/views/css/faqs.css");
  include_once("analyticstracking.php") ?>
</head>
<body>
  <div id="faq"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id="ajax-box"></div>
  <div id="ajax-box2"></div>
<script type="text/babel">
  var General = React.createClass({
    getInitialState: function(){
      return{
        Q1: false,
        Q2: false,
        Q3: false,
        Q4: false,
        Q5: false,
        Q6: false
      };
    },
    handleClick: function(question){
      if(question == "Q1"){ this.setState({Q1: !this.state.Q1}); }
      else if(question == "Q2"){ this.setState({Q2: !this.state.Q2}); }
      else if(question == "Q3"){ this.setState({Q3: !this.state.Q3}); }
      else if(question == "Q4"){ this.setState({Q4: !this.state.Q4}); }
      else if(question == "Q5"){ this.setState({Q5: !this.state.Q5}); }
      else if(question == "Q6"){ this.setState({Q6: !this.state.Q6}); }
    },
    render: function(){
      return(
        <div className="clearfix grpelem" id="u6391-26">
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q1")}>How is HomePik better than other real estate search sites?</p>
          {this.state.Q1 ? <p className="FAQ-answer" >Other real estate search engines give you an abundance of information but failed to give you a means to compare properties in order to discover which is best. They are information accumulators while HomePik.com focuses on being an information definer.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q2")}>Why does HomePik's patent matter?</p>
          {this.state.Q2 ? <p className="FAQ-answer" id="answerG2">The patent gives HomePik the exclusive right to engage in grading, averaging and comparing real estate. Accordingly, this invention provides you with a special and inventive way to explore what is the best choice for the lowest cost.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q3")}>Why should I create a HomePik account?</p>
          {this.state.Q3 ? <p className="FAQ-answer" id="answerG3">If you create an account, it is with HomePik Inc. who is the exclusive licensed broker serving Manhattan. You therefore are creating a nonexclusive relationship with HomePik to serve your real estate needs. HomePik’s listing book is the largest in the industry because it actively searches all other sites for properties it doesn’t have. Thus, in a quick recent comparative, HomePik’s listing book had over 6,000 properties for sale while Streeteasy had slightly over 5,000. When you become a registered buyer you are looking at HomePik’s listing book which is not public information.</p>  : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q4")}>Is there a limit to the number of search folders I can have?</p>
          {this.state.Q4 ? <p className="FAQ-answer" id="answerG4">No. However, it would probably be best to create folders in a logical way  to efficiently evaluate what you are seeking to do. This might be done by neighborhood or by a property type. A folder is automatically set up whenever you save listings and will either have a title you give it or the date it was initiated.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q5")}>How long will HomePik save my searches?</p>
          {this.state.Q5 ? <p className="FAQ-answer">The system will maintain your searches until there is no activity for a defined length of time which has not yet been determined but will probably be six months. Thereafter it will be put in archive.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q6")}>How does HomePik use my data, including my searches?</p>
          {this.state.Q6 ? <p className="FAQ-answer">The only use of HomePik is to assist you in buying a home. We do not sell names and do not reveal them to third parties.</p> : null}
        </div>
      );
    }
  });

  var Listings = React.createClass({
    getInitialState: function(){
      return{
        Q1: false,
        Q2: false,
        Q3: false,
        Q4: false,
        Q5: false,
        Q6: false
      };
    },
    handleClick: function(question){
      if(question == "Q1"){ this.setState({Q1: !this.state.Q1}); }
      else if(question == "Q2"){ this.setState({Q2: !this.state.Q2}); }
      else if(question == "Q3"){ this.setState({Q3: !this.state.Q3}); }
      else if(question == "Q4"){ this.setState({Q4: !this.state.Q4}); }
      else if(question == "Q5"){ this.setState({Q5: !this.state.Q5}); }
      else if(question == "Q6"){ this.setState({Q6: !this.state.Q6}); }
    },
    render: function(){
      return(
        <div className="clearfix grpelem" id="u6391-26">
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q1")}>Does HomePik offer more or different listing information than other search sites?</p>
          {this.state.Q1 ? <p className="FAQ-answer" >When you are browsing you are put into a public portal that affords you to look at a portion of the listings as authorized by the Real Estate Board of New York under its “IDX” system. However, HomePik offers the opportunity to look at its full listing book if you are willing to be registered as a buyer on a non exclusive basis. In this case, you are no longer looking at a public portal rather you are being treated according to the sales practice of the company which permits agents to display to clients its listing book information to assist each one in identify the best properties to see.  This is not a public portal and is available only to registered buyers working with HomePik.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q2")}>Does HomePik show me every apartment on the market?</p>
          {this.state.Q2 ? <p className="FAQ-answer" id="answerG2">HomePik utilizes the services of a large number of listing agents in addition to its sales force that engages in an ongoing search for properties for sale. This permits the company to accumulate a significant number of properties for sale which exceeds all other search engines including Streeteasy.com</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q3")}>I've looked for an apartment that I know is on the market but I can't find it. Why?</p>
          {this.state.Q3 ? <p className="FAQ-answer" id="answerG3">Typically buyers identify the apartment they desire by evaluating their present home and the features they would like to have as well as those they want to avoid. From this, they formulate a “visualization” which represents the accumulation of the attributes in the perfect home. In turn, buyers often seek to obtain this property on favorable terms so the price they explore is often less than the market price for this kind of property.  The result is that every property they see is compared to this visualization benchmark and the price is not consistent with their expectations. The net effect is they see a large number of properties before they have a revelation that they must adjust their expectations.  The alternative means of buying an apartment is to identify the best apartment which represents the best quality existing in the market at a given point of time based on your needs and economic limitations. This will always exist since the beginning point for the buyers search consists of all apartments available for sale.</p>  : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q4")}>How were the grades for location, building, view and space determined?</p>
          {this.state.Q4 ? <p className="FAQ-answer" id="answerG4">The grades were formulated by HomePik after an extensive evaluation of properties in the marketplace in order to create meaningful distinctions between one increment to the other.  All properties are then evaluated by people who are not in real estate so that preferences to certain properties or neighborhoods are not infused into the evaluation.  On an ongoing and periodic basis grades are re-evaluated to insure that any change in condition is properly recognized.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q5")}>I don't see every neighborhood listed. Why?</p>
          {this.state.Q5 ? <p className="FAQ-answer">The purpose of HomePik is to have the customer view a large number of properties which can be compared and then to ascertain which specific choices best meet the customer’s needs. There are many properties which might have certain features which are better than the customer’s expectations and might be less in an other attribute.  For example, a customer might want a large apartment in a certain neighborhood and a property appears that is larger than he/she might expect to obtain but is in another neighborhood. Is it worth considering? HomePik encourages customers to broaden their search range in order to explore an array of possibilities. The larger the number of choices the better chance you have of finding something that might be interesting.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q6")}>Will I get better results if I set my buying formula a bit lower?</p>
          {this.state.Q6 ? <p className="FAQ-answer">You will get a larger selection of properties which will offer you a greater chance to find interesting possibilities that might not have been presented if you set your criteria too high. The mere fact that you set a low value to one of the buying formula elements only means that you have expanded the parameters you are evaluating. Everything you see is that minimum criteria and all properties that have higher values.</p> : null}
        </div>
      );
    }
  });

  var Manage = React.createClass({
    getInitialState: function(){
      return{
        Q1: false,
        Q2: false,
        Q3: false,
        Q4: false,
        Q5: false
      };
    },
    handleClick: function(question){
      if(question == "Q1"){ this.setState({Q1: !this.state.Q1}); }
      else if(question == "Q2"){ this.setState({Q2: !this.state.Q2}); }
      else if(question == "Q3"){ this.setState({Q3: !this.state.Q3}); }
      else if(question == "Q4"){ this.setState({Q4: !this.state.Q4}); }
      else if(question == "Q5"){ this.setState({Q5: !this.state.Q5}); }
    },
    render: function(){
      return(
        <div className="clearfix grpelem" id="u6391-26">
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q1")}>I've saved some listings to my folder but now they're unavailable. Why?</p>
          {this.state.Q1 ? <p className="FAQ-answer">When you save listings you automatically set up a folder which you can name or which will have the date that the save was performed.  When these properties are placed into a saved listings folder, these selected properties are re-compared to each other in order to give you a fuller perspective of their comparative value  relative to other saved listings. If you subsequently do another evaluation and then seek to save these listings to the same folder the system will automatically merge the apartments in your second search with those in your first search and re-determine the comparative relationship. However, the real estate market is constantly changing and apartments that you might have found interesting at one point in time may now no longer be available either because they sold or have been withdrawn from the market.  These will be marked as such and removed from any comparative evaluation.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q2")}>I have a lot of folders. Can I consolidate them?</p>
          {this.state.Q2 ? <p className="FAQ-answer" id="answerG2">Yes. All  you need to do is change the name on a folder to match the name of the target folder and the two will automatically merge.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q3")}>The blue and gold bubbles in my folders seem to change from time to time. Why?</p>
          {this.state.Q3 ? <p className="FAQ-answer" id="answerG3">The blue bubbles represent the comparison of properties within the large population of alternatives you initially selected when creating your buying formula. The gold bubbles represent a comparison of only those properties that you have saved. Since these are two different populations, the comparative results will be different.</p>  : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q4")}>Is there a way to mark my particular favorites?</p>
          {this.state.Q4 ? <p className="FAQ-answer" id="answerG4">Your favorites can be isolated by pressing the heart just to the left of the listing information. This automatically sets up a folder for you to evaluate these properties separately. You can then select  “Listing Folders,” which will portray all the folders you have created in date order or with the designated Title you gave the folder. You can set up as many folders as you want and you can merge them together as well.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q5")}>When asking prices change, are they updated in my folders?</p>
          {this.state.Q5 ? <p className="FAQ-answer">Information in your folders are maintained dynamically. Any change in the asking price or other material change would create a change in the information relating to the listing throughout the system including in your folders and would redefine the saved listings as a result of the change.</p> : null}
        </div>
      );
    }
  });

  var Agents = React.createClass({
    getInitialState: function(){
      return{
        Q1: false,
        Q2: false,
        Q3: false,
        Q4: false,
        Q5: false
      };
    },
    handleClick: function(question){
      if(question == "Q1"){ this.setState({Q1: !this.state.Q1}); }
      else if(question == "Q2"){this.setState({Q2: !this.state.Q2}); }
      else if(question == "Q3"){ this.setState({Q3: !this.state.Q3}); }
      else if(question == "Q4"){ this.setState({Q4: !this.state.Q4}); }
      else if(question == "Q5"){ this.setState({Q5: !this.state.Q5}); }
    },
    render: function(){
      return(
        <div className="clearfix grpelem" id="u6391-26">
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q1")}>Why should I pick a Primary Agent?</p>
          {this.state.Q1 ? <p className="FAQ-answer">When you enter into the HomePik Listing system as a registered buyer, you will initially see that there are different agents associated with each listing. These agents have either selected to be building specialist for this building or they have associated themselves with the given neighborhood where this property is located. Thus, if you send an email to this agent, he/she will be happy to help you with this property and with others that may be of interest.
          <br/><br/>
          There are often cases, however, where a client wishes to develop a relationship with one agent who he/she can regularly contact and engage in an ongoing communication relating to seeing properties and gaining advise. In this case, the client should select an agent to be his/her primary agent.  This selection is totally based on the client’s discretion and a primary agent can be changed if the client wishes to do so at any time. When a primary agent is selected, all listings in the HomePik Listing system will portray only information about the primary agent as the contact person so that when the client wishes to see a given property the primary agent will automatically be the party contacted.  When working with a primary agent, the client and the primary agent will be the only parties that have access to the client’s folders as a means to develop a “conversation” with the other party by messaging.
          <br/><br/>
          There are times when a client might perceive that one primary agent would be desirable for searching for one kind of property and another primary agent would be better for an alternative neighborhood or product type. A client has the ability to have up to two primary agents that he/she can work with at any given time to accommodate this need. Each primary agent will have separate folders relating to their activities with the customer that create a unique communication with each.
          </p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q2")}>How can I find out more about the Agents so I know who to choose?</p>
          {this.state.Q2 ? <p className="FAQ-answer" id="answerG2">Primary agents are selected based on the properties they represent and the client’s feeling that the agent is accommodating to his/her wishes and would be a good advisor in assisting the customer in finding the right home. Clients can also call up the agent’s profile to identify his/her credentials in order to determine if the agent has suitable background knowledge. It is worth noting that agents that are “Selection Portfolio Certified” have engaged in extensive training in using the selection portfolio selling technique utilized by HomePik.com.  A buyer who initially meets an agent from outside of the HomePik.com system will automatically be designated to that party as a primary agent.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q3")}>Do agents specialize in certain neighborhoods or types of buildings, and how can I find who does what?</p>
          {this.state.Q3 ? <p className="FAQ-answer" id="answerG3">An agent working with HomePik has the ability to identify selected buildings where he/she is identified as a building specialist. These agents have a preference for all customers calling on those properties given their additional level of competence.  All agents identify a neighborhood that they wish to focus their attention on and these agents will also have their names associated with any buildings contained in that neighborhood. There can be up to four agents associated with any building and the system engages in a rotation program providing opportunities to all agents assigned to a given building with building specialists being given primary position. You can find out what buildings an agent has identified as his/her building specialty as well as the primary neighborhood the agent seeks to represent by going to their agent profile. It should be noted that as a result of the power of selection portfolio, even if an agent does not primarily represent a given neighborhood he/she is very capable of showing properties outside of that area given the guidance offered by the selection portfolio system.</p>  : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q4")}>What if I don't want to select a Primary Agent?</p>
          {this.state.Q4 ? <p className="FAQ-answer" id="answerG4">You are under no obligation to select a primary agent. In this case, each time you inquire about a given property, the party who will serve you is the agent designated to that property. Thus, as you go from one property to another you will identify different agents and each one will be happy to work with you relating to your specific request.  In this case, any folders you save will be available to any agent to evaluate in order to give you information to assist in your effort.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q5")}>If I change my Primary Agent, will the first agent still be able to see my data?</p>
          {this.state.Q5 ? <p className="FAQ-answer">No. Each primary agent maintains its own communication with the client. However, if you wish to have them combined you can do so by identifying one folder with both names.  If you change a primary agent to another, the new primary agent will only be able to see the information relating to folders associated with the primary agent who was removed when the new agent's name was entered.</p> : null}
        </div>
      );
    }
  });

  var Communication = React.createClass({
    getInitialState: function(){
      return{
        Q1: false,
        Q2: false,
        Q3: false,
        Q4: false,
        Q5: false,
        Q6: false
      };
    },
    handleClick: function(question){
      if(question == "Q1"){ this.setState({Q1: !this.state.Q1}); }
      else if(question == "Q2"){ this.setState({Q2: !this.state.Q2}); }
      else if(question == "Q3"){ this.setState({Q3: !this.state.Q3}); }
      else if(question == "Q4"){ this.setState({Q4: !this.state.Q4}); }
      else if(question == "Q5"){ this.setState({Q5: !this.state.Q5}); }
      else if(question == "Q6"){ this.setState({Q6: !this.state.Q6}); }
    },
    render: function(){
      return(
        <div className="clearfix grpelem" id="u6391-26">
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q1")}>What is the best way to communicate with my Agents?</p>
          {this.state.Q1 ? <p className="FAQ-answer">HomePik.com has a number of ways to quickly communicate with an agent. Every individual listing permits the client to directly communicate with the agent serving the listing as well as to any third party by pressing the email icon on the listing. Communication can also take place from the save listing folders to the agent on the saved listings selected. In turn, the agent can communicate with the client through the client’s listing folder as well.  In addition to the information going into the folder, the client and agents email account will also provide a notification that a HomePik.com folder was updated with a new message.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q2")}>Can I use my own email to contact my agents, instead of the HomePik contact page?</p>
          {this.state.Q2 ? <p className="FAQ-answer" id="answerG2">Yes. However, keep in mind that we only keep track of messages within our system. Any email sent outside the system will not appear in the HomePik.com system.</p> : null}
          <p>&nbsp;</p>
          <p className="FAQ-question" onClick={this.handleClick.bind(this, "Q3")}>If I become an active bidder for an apartment, will I continue to use HomePik to manage my transaction?</p>
          {this.state.Q3 ? <p className="FAQ-answer" id="answerG3">It is likely that you will want to engage in a more active framework of communication by means of telephone or conventional email though HomePik.com will still be a resource  you can use to monitor any new properties appearing that might be of interest.</p>  : null}
        </div>
      );
    }
  });

  var FAQ = React.createClass({
    getInitialState: function() {
      return{
        section: "<?php echo (isset($section) ? $section : "") ?>",
        mainPage: "<?php echo (isset($mainPage) ? $mainPage : "") ?>"
      };
    },
    handleClick: function(name, event){
      this.setState({section: name});
    },
    checkSection: function(section){
      if(this.state.section == section){ return true; }
      else{ return false; }
		},
		render: function(){
			return(
				<div className="clearfix" id="page">
					<Header />
					<NavBar mainPage={this.state.mainPage} />
					<AddressSearch mainPage={this.state.mainPage} />
					<div className="verticalspacer"></div>
					<div className="container-fluid titleContainer">
						<div className="row">
							<div className="col-md-12">
								<div className="clearfix colelem" id="u6396-4">
									<p>FAQs: Answers to frequently asked questions</p>
								</div>
							</div>
						</div>
					</div>
					<div className="container-fluid qaContainer">
						<div className="row">
							<div className="col-lg-2 col-sm-3 col-xs-12 selectionSection">
								<div className="Text-2-ex-lead clearfix grpelem" id="u6390-14">
									{this.checkSection('general') ? <h6 id="u6390-4"><i id="u6390" className="fa fa-chevron-right"> &nbsp;</i>General information</h6> : <h6 style={{cursor: "pointer"}} onClick={this.handleClick.bind(this, 'general')}>General information</h6> }
									{this.checkSection('listings') ? <h6 id="u6390-4"><i id="u6390" className="fa fa-chevron-right"> &nbsp;</i>About Our Listings</h6> : <h6 style={{cursor: "pointer"}} onClick={this.handleClick.bind(this, 'listings')}>About Our Listings</h6> }
									{this.checkSection('manage') ? <h6 id="u6390-4"><i id="u6390" className="fa fa-chevron-right"> &nbsp;</i>Managing Saved Listings</h6> : <h6 style={{cursor: "pointer"}} onClick={this.handleClick.bind(this, 'manage')}>Managing Saved Listings</h6> }
									{this.checkSection('agents') ? <h6 id="u6390-4"><i id="u6390" className="fa fa-chevron-right"> &nbsp;</i>About Primary Agents</h6> : <h6 style={{cursor: "pointer"}} onClick={this.handleClick.bind(this, 'agents')}>About Primary Agents</h6> }
									{this.checkSection('communication') ? <h6 id="u6390-4"><i id="u6390" className="fa fa-chevron-right"> &nbsp;</i>Communication</h6> : <h6 style={{cursor: "pointer"}} onClick={this.handleClick.bind(this, 'communication')}>Communication</h6> }
								</div>
							</div>
							<div className="col-lg-10 col-md-9 col-sm-9 col-xs-12 answerSection">
								<div className="grpelm" id="u6389"></div>
								{this.checkSection('general') ? <General /> : null}
								{this.checkSection('listings') ? <Listings /> : null}
								{this.checkSection('manage') ? <Manage /> : null}
								{this.checkSection('agents') ? <Agents /> : null}
								{this.checkSection('communication') ? <Communication /> : null}
							</div>
						</div>
					</div>
					<div className="verticalspacer"></div>
				</div>
			);
		}
  });

  ReactDOM.render(
     <FAQ />,
     document.getElementById("faq")
  );

	ReactDOM.render(
    <Footer mainPage={"<?php echo $mainPage ?>"} />,
    document.getElementById("footer")
  );
</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
