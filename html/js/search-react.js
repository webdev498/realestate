/* @jsx React.DOM */
window.SearchHeader = React.createClass({
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");
    location.assign("/users/logout.php");
  },
  render : function(){
    return(
      <div id="header-content" className="searchHeader container-fluid header-container">
        <div className="row">
          <div className="clip_frame colelem" id="u25521">
            <div className="col-md-3 col-sm-3 col-xs-5" id="first-section">
              {this.props.role == "user" || this.props.role == "agent" ? <a href='../controllers/menu.php'><img className="block" id="u25521_img_1" src="../images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a> : <a onClick={this.logout} style={{cursor: 'pointer'}}><img className="block" id="u25521_img_1" src="../images/homepik_logo_bubbles_legend_7_part_1.png" alt=""/></a> }
            </div>
            <div className="col-md-4 col-sm-4 col-xs-7" id="second-section">
              <img className="block" id="u25521_img_2" src="../images/homepik_logo_bubbles_legend_7_part_2.png" alt=""/>
            </div>
            <div className="col-md-5 col-sm-5 col-xs-12" id="third-section">
              <img className="block" id="u25521_img_3" src="../images/homepik_logo_bubbles_legend_7_part_3.png" alt=""/>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

window.SearchResultsHeader = React.createClass({
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");
    location.assign("/users/logout.php");
  },
  render : function(){
    return(
      <div>
        {this.props.role == "guest" ?
          <div id="header-content" className="searchResultsHeader">
            <a onClick={this.logout}><img src='/images/homepik_logo_bubbles_legend_7_part_1_v_2.png' id='logo' alt='Homepik Logo'/></a>
            <a><img src='/images/what_symbols_means_lighter.png' id='what_symbol_means' alt='Homepik Logo'/></a>
          </div>
        :
          <div id="header-content" className="searchResultsHeader">
            <a href='/controllers/menu.php'><img src='/images/homepik_logo_bubbles_legend_7_part_1_v_2.png' id='logo' alt='Homepik Logo'/></a>
            <a href='/controllers/menu.php'><img src='/images/what_symbols_means_lighter.png' id='what_symbol_means' alt='Homepik Logo'/></a>
          </div>
        }
      </div>
    );
  }
});

var ClearListings = React.createClass({
  getInitialState: function(){
    return{
      rememberListings: "true",
      clearListings: "false"
    };
  },
  handleChange: function(option){
    if(option == "rememberListings"){
      this.setState({"rememberListings": "true"});
      this.setState({"clearListings": "false"});
    }
    else if (option == "clearListings") {
      this.setState({"rememberListings": "false"});
      this.setState({"clearListings": "true"});
    }
  },
  logout: function(){
    if(this.state.rememberListings == "true") { location.assign("/users/logout.php"); }
    else if(this.state.clearListings == "true") { location.assign("/users/logout.php?listings=clear"); }
  },
  closePopup: function(){
    {this.props.closeDialog()}    
  },
  render: function(){
    return(
      <div>
        <h4 onClick={this.closePopup} id="closeClearListingPopup"><i className="fa fa-times" title="close"></i></h4>
        <h2 id="title" className="Subhead-2">Viewed Listings</h2>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 className="text-popups">Would you like HomePik to remember which listings you have viewed?</h4>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 id="firstOption" className="text-popups">{this.state.rememberListings == "true" ? <i className="fa fa-check"></i> : <i className="fa fa-circle-o" style={{cursor: "pointer"}} onClick={this.handleChange.bind(this, 'rememberListings')}></i>} Remember viewed listings</h4>
        <h4 className="text-popups">{this.state.clearListings == "true" ? <i className="fa fa-check"></i> : <i className="fa fa-circle-o" style={{cursor: "pointer"}} onClick={this.handleChange.bind(this, 'clearListings')}></i>} Clear viewed listings</h4>
        <h4 className="text-popups">&nbsp;</h4>
        <h4 id="submit" className="text-popups" onClick={this.logout}>Submit <i id="arrow" className="fa fa-chevron-right"></i></h4>
      </div>
    );
  }
});

window.CriteriaNavBar = React.createClass({
  getInitialState: function() {
    return{
      name: this.props.name,
      email: this.props.email,
      role: this.props.role,
      guestID: this.props.guestID,
      agent: "",
      searchName: "",
      messages: this.props.messages
    };
  },
  checkGuest: function(){
    if(this.state.role == "guest"){ return true; }
    else{ return false; }
  },
  checkBuyer: function(){
    if(this.state.role == "buyer"){ return true; }
    else{ return false; }
  },
  checkAgent: function(){
    if(this.state.role == 'agent'){ return true; }
    else{ return false; }
  },
  newSearch : function(){
    window.location = "/search.php#newSearch";
    window.location.reload(true);
  },
  addBuyer: function (){
    var $dialog =  $("#add-buyer-box").dialog({
      width: 580,
      modal:true,
      draggable:false,
      resizable:false,
      dialogClass: "addBuyerPopup",
      close: function(){
        ReactDOM.unmountComponentAtNode($dialog);
        $( this ).remove();
      },
      open: function(){
        $(".ui-widget-overlay").bind("click", function(){
          $("#add-buyer-box").dialog('close');
        });
      },
      create: function(e, ui) {
        $(this).dialog('widget').addClass('add-buyer-box-container');
      }
    });

    var closeDialog = function(){
      $dialog.dialog('close');
    }

    ReactDOM.render(<AddBuyer email={this.props.email} closeDialog={closeDialog} mainPage={'criteria'}/>, $dialog[0]);
  },
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    var $dialog =  $("#ajax-box").dialog({
      modal: true,
	  width: 580,
      dialogClass: 'ajaxbox clearListingsPopup',
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

    ReactDOM.render(<ClearListings closeDialog={closeDialog}/>, $dialog[0]);
  },
  guestlogout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    location.assign("/users/logout.php");
  },
  render: function(){
    return(
      <div>
        <nav className="navbar navbar-default menuNavBar criteriaNavBar">
          <div className="container-fluid">
            <div className="navbar-header">
              <p className="navbar-text visible-xs-inline-block">Navigation Menu</p>
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".menu-navbar-collapse" aria-expanded="false">
                <span className="sr-only">Toggle navigation</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
            </div>

            <div className="collapse navbar-collapse menu-navbar-collapse" id="bs-example-navbar-collapse-1">
              {this.checkGuest() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a onClick={this.guestlogout}>Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li><a href="/controllers/saved.php?MP=criteria">Guest Folder</a></li>
                  </ul>
              : null }
              {this.checkBuyer() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/controllers/menu.php">Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li><a href="/controllers/buyer-profile.php?MP=criteria" className="my-profile">My Profile</a></li>
                    <li><a href="/controllers/saved.php?MP=criteria">Listing Folders</a></li>
                    <li><a href="/controllers/my-messages.php?MP=criteria">Messages {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</a></li>
                  </ul>
              : null }
              {this.checkAgent() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/controllers/menu.php">Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li><a style={{cursor: "pointer"}} onClick={this.addBuyer}>Add New Buyer</a></li>
                    <li><a href="/controllers/buyers.php?MP=criteria">Manage Buyers</a></li>
                    <li><a href="/controllers/agent-listings.php?MP=criteria">Saved Listings</a></li>
                    <li><a href="/controllers/my-messages.php?MP=criteria">Messages {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</a></li>
                  </ul>
              : null }

              <ul className="nav navbar-nav navbar-right">
                {this.state.role == "agent" ?
                  <li id="name" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>{this.state.name} <i className="fa fa-angle-down"></i></span></a>
										<ul className="dropdown-menu">
                      <li><a href="../controllers/agent-profile.php?MP=menu">Account Settings</a></li>
										</ul>
									</li>
                :
                  <li id="name">{this.state.name}</li>
                }
                {this.state.role == "guest" ? <li id="signup"><a href="/controllers/guest-register.php?r=search"><span id="u2688">Sign<br/>up</span></a></li> : null}
                {this.state.role == "guest" ?
                  <li id="logout" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="u2688">Log<br/>in</span></a>
										<ul className="dropdown-menu">
											<li><a href="/controllers/signin.php">Log in as a buyer</a></li>
											<li><a href="/controllers/agent-signin.php">Log in as an agent</a></li>
										</ul>
									</li>
                :
                  <li id="logout"><a><span id="u2688" style={{cursor: "pointer"}} onClick={this.logout}>Log<br/>out</span></a></li>
                } 
              </ul>
            </div>
          </div>
        </nav>
      </div>
    );
  }
});
    
window.SearchNavBar = React.createClass({
  getInitialState: function() {
    return{
      name: this.props.name,
      email: this.props.email,
      role: this.props.role,
      guestID: this.props.guestID,
      agent: "",
      searchName: "",
      messages: this.props.messages
    };
  },
  checkGuest: function(){
    if(this.state.role == "guest"){ return true; }
    else{ return false; }
  },
  checkBuyer: function(){
    if(this.state.role == "buyer"){ return true; }
    else{ return false; }
  },
  checkAgent: function(){
    if(this.state.role == 'agent'){ return true; }
    else{ return false; }
  },
  newSearch : function(){
    window.location = "/search.php#newSearch";
    window.location.reload(true);
  },
  editSearch: function(){    
    var $dialog =  $("#ajax-box3").dialog({
      modal: true,
	  width: 1115,
      dialogClass: "editFormula",
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box3'));
        var div = document.createElement('div');
        div.id = 'ajax-box3';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $(".ui-widget-overlay2").bind("click", function(){
          $("#ajax-box3").dialog('close');
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');      
      $(".ui-widget-overlay2").hide(); 
    }.bind(this)    
       
    ReactDOM.render(<EditCriteria closeDialog={closeDialog} initial={true}/>, $dialog[0]);
  },
  addBuyer: function (){
    var $dialog =  $("#add-buyer-box").dialog({
      width: 580,
      modal:true,
      draggable:false,
      resizable:false,
      dialogClass: "addBuyerPopup",
      close: function(){
        ReactDOM.unmountComponentAtNode($dialog);
        $( this ).remove();
      },
      open: function(){
        $(".ui-widget-overlay").bind("click", function(){
          $("#add-buyer-box").dialog('close');
        });
      },
      create: function(e, ui) {
        $(this).dialog('widget').addClass('add-buyer-box-container');
      },
    });

    var closeDialog = function(){
      $dialog.dialog('close');
    }

    ReactDOM.render(<AddBuyer email={this.props.email} closeDialog={closeDialog} mainPage={'results'}/>, $dialog[0]);
  },
  logout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    var $dialog =  $("#ajax-box").dialog({
      modal: true,
	  width: 580,
      dialogClass: 'ajaxbox clearListingsPopup',
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

    ReactDOM.render(<ClearListings closeDialog={closeDialog}/>, $dialog[0]);
  },
  guestlogout: function(){
    $.removeCookie("previousPage");
    $.removeCookie("minPrice");
    $.removeCookie("maxPrice");
    $.removeCookie('location');
    $.removeCookie("building");
    $.removeCookie("views");
    $.removeCookie("bedroom");
    $.removeCookie("living");
    $.removeCookie("minBedroom");
    $.removeCookie("North");
    $.removeCookie("Westside");
    $.removeCookie("Eastside");
    $.removeCookie("Chelsea");
    $.removeCookie("SMG");
    $.removeCookie("Village");
    $.removeCookie("Lower");
    $.removeCookie("Coop");
    $.removeCookie("Condo");
    $.removeCookie("House");
    $.removeCookie("Condop");
    $.removeCookie("garage");
    $.removeCookie("pool");
    $.removeCookie("laundry");
    $.removeCookie("doorman");
    $.removeCookie("elevator");
    $.removeCookie("pets");
    $.removeCookie("fireplace");
    $.removeCookie("healthclub");
    $.removeCookie("prewar");
    $.removeCookie("outdoor");
    $.removeCookie("wheelchair");
    $.removeCookie("newconstruction");

    location.assign("/users/logout.php");
  },
  render: function(){
    return(
      <div>
        <nav className="navbar navbar-default menuNavBar searchNavBar">
          <div className="container-fluid">
            <div className="navbar-header">
              <p className="navbar-text visible-xs-inline-block">Navigation Menu</p>
              <button type="button" className="navbar-toggle collapsed" data-toggle="collapse" data-target=".menu-navbar-collapse" aria-expanded="false">
                <span className="sr-only">Toggle navigation</span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
                <span className="icon-bar"></span>
              </button>
            </div>

            <div className="collapse navbar-collapse menu-navbar-collapse" id="bs-example-navbar-collapse-1">
              {this.checkGuest() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a onClick={this.guestlogout}>Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li id="editCriteriaOption"><a onClick={this.editSearch}>Edit Search Criteria</a></li>
                    <li><a href="/controllers/saved.php?MP=results">Guest Folder</a></li>
                  </ul>
              : null }
              {this.checkBuyer() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/controllers/menu.php">Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li id="editCriteriaOption"><a onClick={this.editSearch}>Edit Search Criteria</a></li>
                    <li><a href="/controllers/buyer-profile.php?MP=results" className="my-profile">My Profile</a></li>
                    <li><a href="/controllers/saved.php?MP=results">Listing Folders</a></li>
                    <li><a href="/controllers/my-messages.php?MP=results">Messages {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</a></li>
                  </ul>
              : null }
              {this.checkAgent() ?
                  <ul className="nav navbar-nav">
                    <li id="firstOption"><a href="/controllers/menu.php">Home</a></li>
                    <li><a onClick={this.newSearch}>New Search</a></li>
                    <li id="editCriteriaOption"><a onClick={this.editSearch}>Edit Search Criteria</a></li>
                    <li><a style={{cursor: "pointer"}} onClick={this.addBuyer}>Add New Buyer</a></li>
                    <li><a href="/controllers/buyers.php?MP=results">Manage Buyers</a></li>
                    <li><a href="/controllers/agent-listings.php?MP=results">Saved Listings</a></li>
                    <li><a href="/controllers/my-messages.php?MP=results">Messages {this.state.messages != 0 && this.state.messages != "" ?<sup id="unreadMessages"> {this.state.messages}</sup> : null}</a></li>
                  </ul>
              : null }

              <ul className="nav navbar-nav navbar-right">
                {this.state.role == "agent" ?
                  <li id="name" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>{this.state.name} <i className="fa fa-angle-down"></i></span></a>
										<ul className="dropdown-menu">
                      <li><a href="../controllers/agent-profile.php?MP=menu">Account Settings</a></li>
										</ul>
									</li>
                :
                  <li id="name">{this.state.name}</li>
                }
                {this.state.role == "guest" ? <li id="signup"><a href="/controllers/guest-register.php?r=search"><span id="u2688">Sign<br/>up</span></a></li> : null}
                {this.state.role == "guest" ?
                  <li id="logout" className="dropdown">
										<a href="#" className="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span id="u2688">Log<br/>in</span></a>
										<ul className="dropdown-menu">
											<li><a href="/controllers/signin.php">Log in as a buyer</a></li>
											<li><a href="/controllers/agent-signin.php">Log in as an agent</a></li>
										</ul>
									</li>
                :
                  <li id="logout"><a><span id="u2688" style={{cursor: "pointer"}} onClick={this.logout}>Log<br/>out</span></a></li>
                } 
              </ul>
            </div>
          </div>
        </nav>
      </div>
    );
  }
});

var RentalsComingSoon = React.createClass({
  goBack: function(){
    if (this.props.role) { window.location = "/controllers/index.php"; }
    else{ window.location = '/controllers/menu.php'; }
  },
  render: function(){
    return(
      <div>
        <div>
          <p className="comingSoon1">Rentals site is under construction.</p>
          <p className="comingSoon2">Coming soon.</p>
          {this.props.role == 'guest' ? <p><center><a className="comingSoonBack" onClick={this.goBack}>Go Back to Homepage</a></center></p> : <p><center><a className="comingSoonBack" onClick={this.goBack}>Go Back to Menu</a></center></p> }
        </div>
      </div>
    );
  }
});

var RegOrNot = React.createClass({
  render: function(){
    return(
      <div id="reg-or-not-popup" data-history="false">
        <div className="c-header">
          Create an account?
          <img alt="Create Account" src="/images/button_housekey_states-housekey_normal.png" />
        </div>
        <div className='regOrNotBody'>
          <p>The Real Estate Board of New York restricts the number of listings a guest can be shown.</p><br/>
          <p>If you want to see the full population of listings you must become a registered buyer with our brokerage firm, HomePik Inc. This will give you access to their listing book as one of their buyers. This will not limit your ability to be a buyer with any other company.</p><br/>
          <p>Do you want to become a registered buyer?</p><br/>
        </div>
        <div className='regOrNotFoot'>
          <label>
            <input style={{display:'none'}} type='radio' name='regOrNotRadio' checked='checked' value='1'/>
            <i className={this.state.yesIcon}></i>
            Yes
          </label>
          <label>
            <input style={{display:'none'}} type='radio' name='regOrNotRadio' value='0'/>
            <i className={this.state.noIcon}></i>
            No
          </label>
          <div className='regOrNotSubmit'>
            <span>submit <i className='fa fa-chevron-right color-blue'></i></span>
          </div>
        </div>
        <span className='fa fa-times need-to-signup-first-div-closer reg-or-not-popup-closer' id='reg-or-not-popup-closer' title="close"></span>
      </div>
    );
  }
});

var Browse = React.createClass({
  browseSales: function(){
    $("#browseSelection").html("sales");
    $(".ui-widget-overlay").hide();
    {this.props.closeDialog()}
  },
  browseRentals: function(){
    $("#browseSelection").html("rentals");
    $(".ui-widget-overlay").hide();
    {this.props.closeDialog()}
  },
  closePopup: function(){
    $(".ui-widget-overlay").hide();
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div >
        <div className="museBGSize grpelem" id="u21295b" onClick={this.browseSales}></div>
        <div className="museBGSize grpelem" id="u21296b" onClick={this.browseRentals}></div>
        <div className="text-popups clearfix grpelem" id="u26451-5b">
          <h4 style={{cursor: "pointer"}} onClick={this.closePopup}></h4>
        </div>
      </div>
    );
  }
});

var AddressSearch = React.createClass({
  getInitialState: function() {
    return{
      searchAddress: ""
    };
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  handleKeyPress: function(event){
    if (event.key === "Enter"){
      this.searchAddress();
    }
  },
  searchAddress: function(){
    var address = this.state.searchAddress;

    $("#addressSearch").attr("data-address", address);

    address = address.toUpperCase();
    address = address.replace(".", "");
    address = address.split(' ');

    if(address[address.length - 1] == 'AVE' || address[address.length - 1] == 'ST' || address[address.length - 1] == 'BLVD' || address[address.length - 1] == 'CT' || address[address.length - 1] == 'RD' || address[address.length - 1] == 'DR'){
      address[address.length - 1] = address[address.length - 1].replace("AVE", "avenue");
      address[address.length - 1] = address[address.length - 1].replace("ST", "street");
      address[address.length - 1] = address[address.length - 1].replace("BLVD", "boulevard");
      address[address.length - 1] = address[address.length - 1].replace("CT", "court");
      address[address.length - 1] = address[address.length - 1].replace("RD", "road");
      address[address.length - 1] = address[address.length - 1].replace("DR", "drive");
    }
    if(address[1] == 'N' || address[1] == 'E' || address[1] == 'S' || address[1] == 'W'){
      address[1] = address[1].replace("N", "north");
      address[1] = address[1].replace("E", "east");
      address[1] = address[1].replace("S", "south");
      address[1] = address[1].replace("W", "west");
    }

    address = address.join(' ');
    address = address.toLowerCase();
    var ad = address.split(' ').join('_');

    $.cookie("previousPage", window.location.href);
    window.location = "http://homepik.com/addressSearch.php#address?"+ad;
  },
  render: function(){
		if (this.props.role == 'guest'){
      var clickCompare =  <span className='toBeHiddenOnListDetail clickCompare clickGuestCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className='icon-heart color-c2'> <span id="compare-text">Click to compare saved listings</span></span></a></span>;
    }
    if (this.props.role == 'agent'){
      var clickCompare = <span className='toBeHiddenOnListDetail clickCompare clickAgentCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className='icon-heart color-c2'> <span id="compare-text">Click to compare saved listings</span></span></a></span>
    }
    if (this.props.role == 'buyer'){
      var clickCompare = <span className='toBeHiddenOnListDetail clickCompare clickBuyerCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className='icon-heart color-c2'> <span id="compare-text">Click to compare saved listings</span></span></a></span>;
    }
    return(
      <div id="addressSearch" className="addressSearchResults" >
        {clickCompare}
        <img width="28" height="28" alt="" src="/images/raster_grey.png" id="u2683_img search" className="searchAddress block" onClick={this.searchAddress} style={{cursor:"pointer"}} title='search'/>
        <input id="searchAddress" type="text" name="address" placeholder="Search for an address" value={this.state.searchAddress} onChange={this.handleChange.bind(this, 'searchAddress')} onKeyPress={this.handleKeyPress}/>
        <div style={{clear:'both'}}></div>
      </div>
    );
  }
});

var ListContentOnGridItemClick = React.createClass({
  getInitialState : function (){
    return {
      html: '',
    };
  },
  componentWillMount: function (){
    var loader = "<div style='width:500px;margin:auto;text-align:center'>"
    +"<br>"+"<br>"+"<img className='loader' src='/images/ajax-loader-large.gif' alt='loading, please wait...'>"
    +"</div>";
    this.setState({html:loader})
    $.ajax({
      url: '/controllers/profile.php?list_numb='+ this.props.listNumb + '&code=' + this.props.code,
      method: 'get',
      success: function(data) {
        this.setState({html: data});
        this.injectScripts(data);
        var listNumb = this.props.listNumb;
        if( $('.heart-tabHead-'+listNumb).hasClass('tab-saved') ){
            $('.heart-tabInner-'+listNumb).addClass('color-blue');
        } else if( $('.heart-list-'+listNumb).hasClass('color-blue') ){
            $('.heart-tabHead-'+listNumb).addClass('color-blue');
            $('.heart-tabInner-'+listNumb).addClass('color-blue');
        }
      }.bind(this),
      error: function(a,b,c) {
        console.log("failed");
        console.log(a);
        console.log(b);
        console.log(c);
      }.bind(this)
    });
  },
  injectScripts: function(html_str){
    eval($('<div>',{html:html_str}).find('#tobeInjected').text());
  },
  componentDidMount: function (){
    var node = $(ReactDOM.findDOMNode(this));
  },
  render: function (){
    var self = this;
    function createMarkup() {
      return {
        __html: self.state.html
      };
    }
    return(<div id='mycustom' data-tab-index={this.props.tabIndex} dangerouslySetInnerHTML={createMarkup()} ></div>)
  }
});
var Tabs = React.createClass({
  getInitialState : function (){
    return {
      showPopup: true,
      numSearches: this.props.numSearches,
      justRegSaveForm: this.props.justRegSaveForm
    };
  },
	componentDidMount: function() {

    /*initializing create an account popup to use later*/
    $("#reg-or-not-popup").dialog({
      autoOpen: false,
      width: 500,
      height: 500,
      modal: true,
      draggable:false,
      resizable:false
    });

    //Set global variables
    var amenities = {}; // used to store selected amenities requirements

    //Get tasks from URL
    var hash = (window.location.hash).replace('#', '');
    var str = hash.split("?"); // Split the URL on ?

    if(str[0] == "newSearch") {
      if (str[1] != "undefined" && str[1] != undefined && str[1] != '') {
        lastBuyersSavedTo.push(str[1]);
        $.get("/controllers/ajax.php", {
          removeLastBuyer:'true'
        }, "json");
      }
      else{
        $.get("/controllers/ajax.php", {
          getLastBuyer:'true'
        },
        function (buyers) {
          lastBuyersSavedTo = buyers;
        }, "json");
      }
      storePage("tab-1");
      $.cookie("searchName", "");
      $.cookie("minPrice", 0);
      $.cookie("maxPrice", 32);
      $.cookie('location', 1);
      $.cookie("building", 1);
      $.cookie("views", 1);
      $.cookie("bedroom", 1);
      $.cookie("living", 1);
      $.cookie("minBedroom", 0);
      $.cookie("North", "false");
      $.cookie("Westside", "false");
      $.cookie("Eastside", "false");
      $.cookie("Chelsea", "false");
      $.cookie("SMG", "false");
      $.cookie("Village", "false");
      $.cookie("Lower", "false");
      $.cookie("Coop", "false");
      $.cookie("Condo", "false");
      $.cookie("House", "false");
      $.cookie("Condop", "false");
      $.cookie("garage", "false");
      $.cookie("pool", "false");
      $.cookie("laundry", "false");
      $.cookie("doorman", "false");
      $.cookie("elevator", "false");
      $.cookie("pets", "false");
      $.cookie("fireplace", "false");
      $.cookie("healthclub", "false");
      $.cookie("prewar", "false");
      $.cookie("outdoor", "false");
      $.cookie("wheelchair", "false");
      $.cookie("timeshare", "false");
      $.cookie("newconstruction", "false");
    }
    else if (str[0] == "buyerFormula") {
      if(typeof(str[2]) === 'undefined'){ str[2] = false; }
      else{ str[2] = str[2].split('_').join(' '); }
      lastBuyersSavedTo.push(str[1]);
      $.get("/controllers/ajax.php", {
        removeLastBuyerAndFolder:'true'
      }, "json");
      buyerEmail = str[1];
      formulaName = str[2];
      $.ajax({
        type: "POST",
        url: "controllers/get-search-criteria.php",
        data: {"email": str[1], "name": str[2]},
        success: function(data){
          var criteria = JSON.parse(data);
          $.cookie("garage", "false");
          $.cookie("pool", "false");
          $.cookie("laundry", "false");
          $.cookie("doorman", "false");
          $.cookie("elevator", "false");
          $.cookie("pets", "false");
          $.cookie("fireplace", "false");
          $.cookie("healthclub", "false");
          $.cookie("prewar", "false");
          $.cookie("outdoor", "false");
          $.cookie("wheelchair", "false");
          $.cookie("timeshare", "false");
          $.cookie("newconstruction", "false");
          $("#garage").attr("src", "images/amenities/garage.png").removeClass("selected");
          $("#pool").attr("src", "images/amenities/pool.png").removeClass("selected");
          $("#laundry").attr("src", "images/amenities/laundry.png").removeClass("selected");
          $("#doorman").attr("src", "images/amenities/doorman.png").removeClass("selected");
          $("#elevator").attr("src", "images/amenities/elevator.png").removeClass("selected");
          $("#pets").attr("src", "images/amenities/pets.png").removeClass("selected");
          $("#fireplace").attr("src", "images/amenities/fireplace.png").removeClass("selected");
          $("#healthclub").attr("src", "images/amenities/healthclub.png").removeClass("selected");
          $("#prewar").attr("src", "images/amenities/prewar.png").removeClass("selected");
          $("#outdoor").attr("src", "images/amenities/roofdeck.png").removeClass("selected");
          $("#wheelchair").attr("src", "images/amenities/wheelchair.png").removeClass("selected");          
          $("#timeshare").attr("src", "images/amenities/roofdeck.png").removeClass("selected");
          $("#newconstruction").attr("src", "images/amenities/wheelchair.png").removeClass("selected");
          $.cookie("searchName", criteria.name);
          $.cookie("minPrice", criteria.min_price);
          $.cookie("maxPrice", criteria.max_price);
          $.cookie("location", criteria.location_grade);
          $.cookie("building", criteria.building_grade);
          $.cookie("views", criteria.view_grade);
          $.cookie("bedroom", criteria.bedroom_area);
          $.cookie("living", criteria.living_area);
          $.cookie("minBedroom", criteria.bedrooms);
          if(criteria.neighborhoods.length < 7){
            criteria.neighborhoods.forEach(function(entry) {
              $.cookie(entry, "true");
            });
          }
          if(criteria.prop_type.length < 4){
            criteria.prop_type.forEach(function(entry) {
              $.cookie(entry, "true");
            });
          }
          criteria.amenities.forEach(function(entry){
            entry = entry.toLowerCase();
            $.cookie(entry, "true");
          });
          $( "#min_price" ).text(prices[criteria.min_price]["display"]).attr('data-price',prices[criteria.min_price]["price"]);
          $( "#max_price" ).text(prices[criteria.max_price]["display"]).attr('data-price',prices[criteria.max_price]["price"]);
          $("#price").slider('values',0,criteria.min_price);
          $("#price").slider('values',1,criteria.max_price);
          $("#price").slider('refresh');
          $("#bedrooms_slider").find("span").html(criteria.bedrooms);
          if(criteria.bedrooms == 0){
            $('#bedrooms_box .grade_desc input').hide();
            $('#bedrooms_box .grade_desc span').text('Studio');
          } else if(criteria.bedrooms == 1){
            $('#bedrooms_box .grade_desc input').show();
            $('#bedrooms_box .grade_desc span').text(' Bedroom');
          } else {
            $('#bedrooms_box .grade_desc input').show();
            $('#bedrooms_box .grade_desc span').text(' Bedrooms');
          }
          $( "#bedrooms" ).val( criteria.bedrooms );
          $("#bedrooms_slider").slider('value',criteria.bedrooms);
          $( "#bedrooms_slider" ).slider('refresh');
          $("#neighborhoods").multiselect("uncheckAll");
          $("#prop_type").multiselect("uncheckAll");

          if(criteria.neighborhoods.length < 7){
            criteria.neighborhoods.forEach(function(entry) {
              if(entry == "North"){ $("input[value=North]").attr("checked", "true"); }
              else if(entry == "Westside"){ $("input[value=Westside]").attr("checked", "true"); }
              else if(entry == "Eastside"){ $("input[value=Eastside]").attr("checked", "true"); }
              else if(entry == "Chelsea"){ $("input[value=Chelsea]").attr("checked", "true"); }
              else if(entry == "SMG"){ $("input[value=SMG]").attr("checked", "true"); }
              else if(entry == "Village"){ $("input[value=Vilage]").attr("checked", "true"); }
              else if(entry == "Lower"){ $("input[value=Lower]").attr("checked", "true"); }
              else{ /* Do nothing */ }
            });
            $("#neighborhoods").val(criteria.neighborhoods);
            $("#neighborhoods-container .ui-multiselect").find(".ui-icon").next().html(criteria.neighborhoods.length + " neighborhoods");
          }
          if(criteria.neighborhoods.length < 7){
            criteria.neighborhoods.forEach(function(entry) {
              if(entry == "North"){ $("input[value=North]").attr("checked", "true"); }
              else if(entry == "Westside"){ $("input[value=Westside]").attr("checked", "true"); }
              else if(entry == "Eastside"){ $("input[value=Eastside]").attr("checked", "true"); }
              else if(entry == "Chelsea"){ $("input[value=Chelsea]").attr("checked", "true"); }
              else if(entry == "SMG"){ $("input[value=SMG]").attr("checked", "true"); }
              else if(entry == "Village"){ $("input[value=Village]").attr("checked", "true"); }
              else if(entry == "Lower"){ $("input[value=Lower]").attr("checked", "true"); }
              else{ /* Do nothing */ }
            });
            $("#neighborhoods").val(criteria.neighborhoods);
            $("#neighborhoods-container .ui-multiselect").find(".ui-icon").next().html(criteria.neighborhoods.length + " neighborhoods");
          }
          if(criteria.prop_type.length < 4){
            var props = [];
            criteria.prop_type.forEach(function(entry) {
              if(entry == "Coop"){
                $("input[value=1]").attr("checked", "true");
                props.push("1");
              }
              else if(entry == "Condo"){
                $("input[value=2]").attr("checked", "true");
                props.push("2");
              }
              else if(entry == "House/Townhouse"){
                $("input[value=4]").attr("checked", "true");
                props.push("4");
              }
              else if(entry == "Condop"){
                $("input[value=5]").attr("checked", "true");
                props.push("5");
              }
              else{
                // Do nothing
              }
            });
            $("#prop_type").val(props);
            $("#prop-container .ui-multiselect").find(".ui-icon").next().html(criteria.prop_type.length + " selected");
          }
          $('#loc_desc').text($("#loc_"+criteria.location_grade).text());
          $("#location_grade").slider('value',criteria.location_grade);
          $("#location_grade").slider('refresh');
          $('#buil_desc').text($("#buil_"+criteria.building_grade).text());
          $("#building_grade").slider('value',criteria.building_grade);
          $("#building_grade").slider('refresh');
          $('#views_desc').text($("#views_"+criteria.view_grade).text());
          $("#views_grade").slider('value',criteria.view_grade);
          $("#views_grade").slider('refresh');
          if(size_grades[criteria.bedroom_area] !== 'XL'){ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]); }
          else{ $("#bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]).addClass("smaller"); }
          $('#bedroom_desc').text($("#bedroom_"+criteria.bedroom_area).text());
          $("#bedroom_grade").slider('value',criteria.bedroom_area);
          $("#bedroom_grade").slider('refresh');
          if(size_grades[criteria.living_area] !== 'XL'){ $("#living_grade").find("span").html(size_grades[criteria.living_area]); }
          else{ $("#living_grade").find("span").html(size_grades[criteria.living_area]).addClass("smaller"); }
          $('#living_desc').text($("#living_"+criteria.living_area).text());
          $("#living_grade").slider('value',criteria.living_area);
          $("#living_grade").slider('refresh');
          if (criteria.amenities != "") {
            criteria.amenities.forEach(function(entry){
              entry = entry.toLowerCase();
              $("#"+entry).attr("src", "images/amenities/"+entry+"b.png").addClass("selected");
            });
          }

          $('#listings').tabs('select',0);
          $.cookie('listActive', "no");
          var active = $.cookie('listActive');
          var state = {},
          // Get the id of this tab widget.
          id = "tabs",
          tabs = $('#tabs').tabs(),
          // Get the index of this tab.
          current = tabs.tabs('option', 'selected');
          current = (current + 1);
          var next = (current + 1);
          if (next == 0 || next == 1 || next == 2  ) {
            state[ id ] = current; // Set the state!
            $.bbq.pushState( state );
          }
          show_results(); // this function is in head.tpl.php
          $(".ui-jqgrid-bdiv").focus();
          storePage("results");
          if (previousOpenListings > 0) {
            $('#listings').removeClass('fixedTop').css('height', 'unset');
            $('#listings .ui-tabs-nav').removeClass('fixedTop');
            $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
          }
        },
        error: function() {
          console.log("failed");
        }
      });
    }
    else{
      var action = getStoredPage(this.state.justRegSaveForm);    
      if (action == "save") {
        this.setState({numSearches: 1});
        this.setState({justRegSaveForm: 'false'});
      }
    }

    window.location.hash = '';
    $("#listings").hide();
    $("#new-thead").hide();

    $( "#tabs" ).tabs();
    var tmplt = "<li><a href='#{href}'>#{label}</a> "
    +"<span class='ui-icon ui-icon-close' title='close'>Remove Tab</span>";
    var $listings = $( "#listings" ).tabs({ // initialize the tabs for the search results/listings pages
      tabTemplate: tmplt,

      load: function( event, ui ) {
        var current = ui.index;
        var id = ui.tab.attributes[0].nodeValue;
        var contentDiv = $(id+' div');
        id = id.substr(1, id.length-1);
        var listNumb = contentDiv.attr('data-list-numb');
        var code = contentDiv.attr('data-code');

        if( $('.heart-list-'+listNumb).hasClass('color-blue') ){
          $('.heart-tabHead-'+listNumb).addClass('color-blue');
        }

        ReactDOM.render(
          <ListContentOnGridItemClick tabIndex={current} listNumb={listNumb} code={code} contentDiv={contentDiv}/>,
          document.getElementById(id)
        );
      },
      add: function(event, ui) { /* function trriggered when a new listing tab is created */
        var state = {};
        /*  Get the id of this tab widget. */
        var id = 'listings',
        tabs = $('#tabs').tabs(),

        /* Get the index of this tab */
        current =  ui.index;
        $('#listings').tabs('select',current);

        state[ id ] = current;
        $.bbq.pushState( state ); /* Switch to the new tab by changing the url hash (#) (bbq allows browser history back and forward buttons to work with using javascript) */
      },
      select: function(event, ui) {
        var current = ui.index;
        var id = ui.tab.attributes[0].nodeValue;
        var contentDiv = $(id+' div');
        contentDiv.hide();
        setTimeout(function(){
          contentDiv.show();
        },2000);
        if(current){
          /*to hide static symbols and listing numbers on list details page*/
          $('#listings').removeClass('fixedTop').css('height', 'unset');
          $('#listings .ui-tabs-nav').removeClass('fixedTop');
          $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
          $('.thead-container').css('display', 'none');
          $('.toBeHiddenOnListDetail').hide();
          $('.static-content-search .sr_right').addClass("hideBorder");
          $("#backBtn.backToSearchCriteria").hide();
          $('.toBeDisplayedOnListDetail').show();
          setTimeout(function(){ $(window).scrollTop(0); },200);
        } else{
          /*to show static symbols and listing numbers on list details page*/
          $('.toBeHiddenOnListDetail').show();
          $('.static-content-search .sr_right').removeClass("hideBorder");
          $("#backBtn.backToSearchCriteria").show();
          $('.toBeDisplayedOnListDetail').hide();
          $('.thead-container').css('display', 'block');
        }
        if(current == 0){
          $('#scrollwrapper').css('display','block');
          /* for IE */
          $('#new-thead .ui-jqgrid-htable').css('display','block');
          $('#new-thead .ui-jqgrid-htable').css('display','table');
          $('.ui-jqgrid .ui-jqgrid-hbox').css('border-bottom','1px solid #CCCCCC');
          $('.table-header').css('position','absolute');
          $('#address-box').addClass('results');
          $('#key').show();
          $('#results-tab a').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Results');
          window.scroll(0, scroll_to_y);
        }else{
          $('#scrollwrapper').css('display','none');
          $('#new-thead .ui-jqgrid-htable').css('display','none');
          $('.ui-jqgrid .ui-jqgrid-hbox').css('border-bottom','0px');
          $('.table-header').css('position','absolute');
          //$('#address-box').removeClass('results');
          $('#results-tab a').html('<span style="font-size:13px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Results</span>');
        }
      }
    });
    var minPrice = $.cookie("minPrice");
    var maxPrice = $.cookie("maxPrice");
    var locationGrade = $.cookie("location");
    var buildingGrade = $.cookie("building");
    var viewsGrade = $.cookie("views");
    var bedroomGrade = $.cookie("bedroom");
    var livingGrade = $.cookie("living");
    var minBedroom = $.cookie("minBedroom");
    var nhNorth = $.cookie("North");
    var nhWestside = $.cookie("Westside");
    var nhEastside = $.cookie("Eastside");
    var nhChelsea = $.cookie("Chelsea");
    var nhSMG = $.cookie("SMG");
    var nhVillage = $.cookie("Village");
    var nhLower = $.cookie("Lower");
    var propCoop = $.cookie("Coop");
    var propCondo = $.cookie("Condo");
    var propHouse = $.cookie("House");
    var propCondop = $.cookie("Condop");
    var garage = $.cookie("garage");
    var pool = $.cookie("pool");
    var laundry = $.cookie("laundry");
    var doorman = $.cookie("doorman");
    var elevator = $.cookie("elevator");
    var pets = $.cookie("pets");
    var fireplace = $.cookie("fireplace");
    var healthclub = $.cookie("healthclub");
    var prewar = $.cookie("prewar");
    var outdoor = $.cookie("outdoor");
    var wheelchair = $.cookie("wheelchair");
    var timeshare = $.cookie("timeshare");
    var newconstruction = $.cookie("newconstruction");
    
  	// IF NO STORED VAULES SET TO DEFAULT
    if(!minPrice){ minPrice = 0; }
    if(!maxPrice){ maxPrice = 32; }
    if(!locationGrade){ locationGrade = 1; }
    if(!buildingGrade){ buildingGrade = 1; }
    if(!viewsGrade){ viewsGrade = 1; }
    if(!bedroomGrade){ bedroomGrade = 1; }
    if(!livingGrade){ livingGrade = 1; }
    if(!minBedroom){ minBedroom = 0; }

    if (nhNorth == "true")		{
		  $("#ui-multiselect-neighborhoods-option-0").attr("checked",true);
      $("option[value='North']").attr('selected','selected');
		}
		if (nhWestside == "true"){
      $("#ui-multiselect-neighborhoods-option-1").attr("checked",true);
      $("option[value='Westside']").attr('selected','selected');
    }
		if (nhEastside == "true"){
      $("#ui-multiselect-neighborhoods-option-2").attr("checked",true);
      $("option[value='Eastside']").attr('selected','selected');
    }
		if (nhChelsea == "true"){
      $("#ui-multiselect-neighborhoods-option-3").attr("checked",true);
      $("option[value='Chelsea']").attr('selected','selected');
    }
		if (nhSMG == "true"){
      $("#ui-multiselect-neighborhoods-option-4").attr("checked",true);
      $("option[value='SMG']").attr('selected','selected');
    }
		if (nhVillage == "true"){
      $("#ui-multiselect-neighborhoods-option-5").attr("checked",true);
      $("option[value='Village']").attr('selected','selected');
    }
		if (nhLower == "true"){
      $("#ui-multiselect-neighborhoods-option-6").attr("checked",true);
      $("option[value='Lower']").attr('selected','selected');
    }
		if (propCoop == "true"){
      $("#ui-multiselect-prop_type-option-0").attr("checked",true);
      $("option[value='1']").attr('selected','selected');
    }
		if (propCondo == "true"){
      $("#ui-multiselect-prop_type-option-1").attr("checked",true);
      $("option[value='2']").attr('selected','selected');
    }
		if (propHouse == "true"){
      $("#ui-multiselect-prop_type-option-2").attr("checked",true);
      $("option[value='4']").attr('selected','selected');
    }
		if (propCondop == "true"){
      $("#ui-multiselect-prop_type-option-3").attr("checked",true);
      $("option[value='5']").attr('selected','selected');
    }

    if (garage == "true"){
    	amenities["garage"] = true;
      $('img.amenity_icons[rel=garage]').attr('src','images/amenities/garageb.png').addClass('selected');
    }
    if (pool == "true"){
    	amenities["pool"] = true;
      $('img.amenity_icons[rel=pool]').attr('src','images/amenities/poolb.png').addClass('selected');
    }
    if (laundry == "true"){
    	amenities["laundry"] = true;
      $('img.amenity_icons[rel=laundry]').attr('src','images/amenities/laundryb.png').addClass('selected');
    }
    if (doorman == "true"){
    	amenities["doorman"] = true;
      $('img.amenity_icons[rel=doorman]').attr('src','images/amenities/doormanb.png').addClass('selected');
    }
    if (elevator == "true"){
    	amenities["elevator"] = true;
      $('img.amenity_icons[rel=elevator]').attr('src','images/amenities/elevatorb.png').addClass('selected');
    }
    if (pets == "true"){
    	amenities["pets"] = true;
      $('img.amenity_icons[rel=pets]').attr('src','images/amenities/petsb.png').addClass('selected');
    }
    if (fireplace == "true"){
    	amenities["fireplace"] = true;
      $('img.amenity_icons[rel=fireplace]').attr('src','images/amenities/fireplaceb.png').addClass('selected');
    }
    if (healthclub == "true"){
    	amenities["healthclub"] = true;
      $('img.amenity_icons[rel=healthclub]').attr('src','images/amenities/healthclubb.png').addClass('selected');
    }
    if (prewar == "true"){
    	amenities["prewar"] = true;
      $('img.amenity_icons[rel=prewar]').attr('src','images/amenities/prewarb.png').addClass('selected');
    }
    if (outdoor == "true"){
    	amenities["outdoor"] = true;
      $('img.amenity_icons[rel=outdoor]').attr('src','images/amenities/outdoorb.png').addClass('selected');
    }
    if (wheelchair == "true"){
        amenities["wheelchair"] = true;
      $('img.amenity_icons[rel=wheelchair]').attr('src','images/amenities/wheelchairb.png').addClass('selected');
    }
    if (timeshare == "true"){
        amenities["timeshare"] = true;
      $('img.amenity_icons[rel=timeshare]').attr('src','images/amenities/timeshareb.png').addClass('selected');
    }
    if (newconstruction == "true"){
    	amenities["newconstruction"] = true;
      $('img.amenity_icons[rel=newconstruction]').attr('src','images/amenities/newconstructionb.png').addClass('selected');
    }
    // LOCATION SLIDER
    $("#location_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: locationGrade, /* default grade is 1 */
      slide: function(event, ui) { /* Function to execute when sliding the location slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        var current_grade = '#loc_' + ui.value; /* get the selected grade */
        var locationGrade = ui.value;
        var current_desc = $(current_grade).text(); /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $.cookie("location", locationGrade);
        $('#loc_desc').text(current_desc); /* insert the grade description into the text box */
        var chev_right = "<img src='/images/blue-tick.png'/>";
        if(ui.value < '10'){ ui.sliderText = '<span>'+chev_right+'</span>'; }
        else { ui.sliderText = '<span>'+chev_right+'</span>'; }
        $('#location_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) { /*Function to execute when initializing slider */
        var chev_right = "<img src='/images/blue-tick.png'/>";
        if(!locationGrade){ ui.value = 1; }
        else{ ui.value = locationGrade; }
        $('#location_grade .ui-slider-handle').html('<span>'+chev_right+'</span>');
        var current_grade = '#loc_' + ui.value; /* get the selected grade */
        var current_desc = $(current_grade).text(); /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $('#loc_desc').text(current_desc);
      }
    }).addTouch();
    // BUILDING SLIDER
    $("#building_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: buildingGrade, /* default grade is 1 */
      slide: function(event, ui) { /* Function to execute when sliding the location slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        var current_grade = '#buil_' + ui.value; /* get the selected grade */
        var buildingGrade = ui.value;
        var current_desc = $(current_grade).text(); /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $.cookie("building", buildingGrade);
        $('#buil_desc').text(current_desc); /* insert the grade description into the text box */
        var chev_right = "<img src='/images/purple-tick.png'/>";
        if(ui.value < '10'){ ui.sliderText = '<span>'+chev_right+'</span>'; }
        else { ui.sliderText = '<span>'+chev_right+'</span>'; }
        $('#building_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) { /*Function to execute when initializing slider */
        if(!buildingGrade){ ui.value = 1; }
        else{ ui.value = buildingGrade; }
        var chev_right = "<img src='/images/purple-tick.png'/>";
        $('#building_grade .ui-slider-handle').html('<span>'+chev_right+'</span>');
        var current_grade = '#buil_' + ui.value; /* get the selected grade */
        var current_desc = $(current_grade).text(); /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $('#buil_desc').text(current_desc);
      }
    }).addTouch();
    // VIEWS SLIDER
    $("#views_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: viewsGrade,
      slide: function(event, ui) { /* Function to execute when sliding the views slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        var current_grade = '#views_' + ui.value; /* get the selected grade */
        var viewsGrade = ui.value;
        var current_desc = $(current_grade).text();  /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $.cookie("views", viewsGrade);
        $('#views_desc').text(current_desc); /* insert the grade description into the text box */
        var chev_right = "<img src='/images/pink-tick.png'/>";
        if(ui.value < '10'){ ui.sliderText = '<span>'+chev_right+'</span>'; }
        else { ui.sliderText = '<span>'+chev_right+'</span>'; }
        $('#views_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        if(!viewsGrade){ ui.value = 1; }
        else{ ui.value = viewsGrade; }
        var chev_right = "<img src='/images/pink-tick.png'/>";
        $('#views_grade .ui-slider-handle').html('<span>'+chev_right+'</span>');
        var current_grade = '#views_' + ui.value; /* get the selected grade */
        var current_desc = $(current_grade).text();  /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $('#views_desc').text(current_desc);
        return false;
      }
    }).addTouch();
    /* Bedroom and living room grades use letters (M for medium, etc.) instead of numbers */
    var size_grades = ['','S','M','L','XL'];
    // MASTER BEDROOM SIZE SLIDER
    $("#bedroom_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: bedroomGrade,
      slide: function(event, ui) { /* Function to execute when sliding the bedroom slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        var current_grade = '#bedroom_' + ui.value; /* get the selected grade */
        var bedroomGrade = ui.value;
        var current_desc = $(current_grade).text();  /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $.cookie("bedroom", bedroomGrade);
        $('#bedroom_desc').text(current_desc); /* insert the grade description into the text box */
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('#bedroom_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) {	/* Function to execute when initializing slider */
        if(!bedroomGrade){ ui.value = 1; }
        else{ ui.value = bedroomGrade; }
        $('#bedroom_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        var current_grade = '#bedroom_' + ui.value; /* get the selected grade */
        var current_desc = $(current_grade).text();  /* get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $('#bedroom_desc').text(current_desc);
        return false;
      }
    }).addTouch();
    // LIVING ROOM SIZE SLIDER
    $("#living_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: livingGrade,
      slide: function(event, ui) { /* Function to execute when sliding the living room slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        var current_grade = '#living_' + ui.value; /* get the selected grade */
        var livingGrade = ui.value;
        var current_desc = $(current_grade).text();  // get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $.cookie("living", livingGrade);
        $('#living_desc').text(current_desc); /* insert the grade description into the text box */
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('#living_grade .ui-slider-handle').html(ui.sliderText);/* show the new grade in the slider handle */
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        if(!livingGrade){ ui.value = 1; }
        else{ ui.value = livingGrade; }
        $('#living_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        var current_grade = '#living_' + ui.value; /* get the selected grade */
        var current_desc = $(current_grade).text();  // get the description for the selected grade (e.g. 'a safe residential street near a park') */
        $('#living_desc').text(current_desc);
        return false;
      }
    }).addTouch();
    // NEIGHBORHOODS SELECTOR
    $("#neighborhoods").multiselect({
      noneSelectedText: "All of Manhattan",
      selectedText: "# neighborhoods",
      height:'auto',
      multiple:true,
      header:false,
      position: {
        my: 'left top',
        at: 'left bottom',
        collision: 'none'
      },
      open: function(){
        $('#amenities').addClass('shift');
        $(this).parent().parent().parent().addClass("active");
        localStorage.sportive = $("#sportive").val();        
        $('#neighborhoods-container').find("input[value='hidden']").parent().find("span").addClass('neighborhood-explaination');
        $('#neighborhoods-container').find("input[value='hidden']").parent().parent().css("margin-bottom", "9px");
        $('#neighborhoods-container').find("input[value='hidden2']").parent().find("span").addClass('neighborhood-explaination');
        $('#neighborhoods-container').find("input[value='hidden2']").parent().parent().css("margin-bottom", "23px");
      },
      close:  function(){
        $('#amenities').removeClass('shift');
        $(this).parent().parent().parent().removeClass("active");
      }
    });
    $('#neighborhoods-container').find(".ui-multiselect-checkboxes").change(function() {
      $.cookie('neighborhoodChoice', $(this).val(), {path: '/controllers/', expires: 365});
      $("#neighborhoods").val($.cookie('neighborhoodChoice'));
      setTimeout(function () { getListingCount(); }, 200);
    }.bind(this));
    // PROPERTY TYPE SELECTOR
    $("#prop_type").multiselect({
      noneSelectedText: "Any Property Type",
      selectedText: "# selected",
      height:"auto",
      multiple:true,
      header:false,
      position: {
        my: 'left top',
        at: 'left bottom',
        collision: 'none'
      },
      open: function(){
        $('.continue').addClass('shift1');
        localStorage.property = $("#prop_type").val();
      },
     	close:  function(){
    	  $('.continue').removeClass('shift1');
      }
    });
    $('#prop-container').find(".ui-multiselect-checkboxes").change(function() {
      $.cookie('propertyChoice', $(this).val(), {expires: 365, path: '/controllers/' });
      $("#prop_type").val($.cookie('propertyChoice'));
      setTimeout(function () { getListingCount(); }, 200);
    }.bind(this));
    // MINIMUM FLOOR (disabled)
    $("#min_floor").spinner();
    // PRICE
    // Translate the slider value into price increments. "price" is the value submitted in the search request;
    // "display" is what's shown to the user.
    var	prices = [
			//'', // no slider value of 0
			{"price":0,"display":"0"}, // if slider value is 0
			{"price":100000,"display":"100K"}, // if slider value is 1
			{"price":200000,"display":"200K"}, // if slider value is 2
			{"price":300000,"display":"300K"}, // if slider value is 3
			{"price":400000,"display":"400K"}, // if slider value is 4
			{"price":500000,"display":"500K"}, // if slider value is 5
			{"price":600000,"display":"600K"}, // if slider value is 6
			{"price":700000,"display":"700K"}, // if slider value is 7
			{"price":800000,"display":"800K"}, // if slider value is 8
			{"price":900000,"display":"900K"}, // if slider value is 9
			{"price":1000000,"display":"1M"}, // if slider value is 10
			{"price":1100000,"display":"1.1M"}, // if slider value is 11
			{"price":1200000,"display":"1.2M"}, // if slider value is 12
			{"price":1300000,"display":"1.3M"}, // if slider value is 13
			{"price":1400000,"display":"1.4M"}, // if slider value is 14
			{"price":1500000,"display":"1.5M"}, // if slider value is 15
			{"price":1600000,"display":"1.6M"}, // if slider value is 16
			{"price":1700000,"display":"1.7M"}, // if slider value is 17
			{"price":1800000,"display":"1.8M"}, // if slider value is 18
			{"price":1900000,"display":"1.9M"}, // if slider value is 19
			{"price":2000000,"display":"2M"}, // if slider value is 20
			{"price":2250000,"display":"2.25M"}, // if slider value is 21
			{"price":2500000,"display":"2.5M"}, // if slider value is 22
			{"price":2750000,"display":"2.75M"}, // if slider value is 23
			{"price":3000000,"display":"3M"}, // if slider value is 24
			{"price":3500000,"display":"3.5M"}, // if slider value is 25
			{"price":4000000,"display":"4M"}, // if slider value is 26
			{"price":6000000,"display":"6M"}, // if slider value is 27
			{"price":8000000,"display":"8M"}, // if slider value is 28
			{"price":12000000,"display":"12M"}, // if slider value is 29
			{"price":25000000,"display":"25M"}, // if slider value is 30
			{"price":50000000,"display":"50M"}, // if slider value is 31
			{"price":99000000,"display":"99M"},	 // if slider value is 32
		];

    $( "#price" ).slider({
      animate: true,
      range: 'true',
      min: 0,
      max: 32,
      values: [minPrice, maxPrice ],
      slide: function( event, ui ) { /* Function to execute when sliding the price slider */
        if(event.which == 38 || event.which == 40){ movepage_onpressarrowkey(event.which); return false; }
        if( ( ui.values[ 0 ] + 0 ) >= ui.values[ 1 ] ) { return false; } //Make sure both arrows have at least a .1 gap
        var min_value = ui.values[0], max_value = ui.values[1];
        $.cookie("minPrice", min_value);
        $.cookie("maxPrice", max_value);
        /* set the display price for the user (e.g. 2.5M), and the data-price attribute will contain the value collected by the search function (e.g. 2500000) */
        $( "#min_price" ).text(prices[min_value]["display"]).attr('data-price',prices[min_value]["price"]);
        $( "#max_price" ).text(prices[max_value]["display"]).attr('data-price',prices[max_value]["price"]);
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) {	/* Function to execute when initializing slider */
        $('#price .ui-slider-handle').first().html('<span>&#155;</span');
        $('#price .ui-slider-handle').first().attr("id", "min_price_slider");
        $('#price .ui-slider-handle').last().html('<span>&#139;</span');
        $('#price .ui-slider-handle').last().attr("id", "max_price_slider");
        return false;
      }
    }).addTouch();
    if(minPrice != "null"){ $( "#min_price" ).text(prices[minPrice]["display"]).attr('data-price',prices[minPrice]["price"]); }
    else{ $( "#min_price" ).text(prices[0]["display"]).attr('data-price',prices[0]["price"]); }
    if(maxPrice != "null"){ $( "#max_price" ).text(prices[maxPrice]["display"]).attr('data-price',prices[maxPrice]["price"]); }
    else{ $( "#max_price" ).text(prices[32]["display"]).attr('data-price',prices[32]["price"]); }

    // NUMBER OF BEDROOMS
    $( "#bedrooms_slider" ).slider({
      range: 'max',
      min: 0,
      max: 8,
      value: minBedroom,
      slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider    */
        if(event.which == 38 || event.which == 40) { movepage_onpressarrowkey(event.which); return false; }
        var bedrooms = ui.value;
        $( "#bedrooms" ).val( bedrooms);
        var minBedroom = bedrooms;
        $.cookie("minBedroom", minBedroom);
        ui.sliderText =	'<span>'+ui.value+'</span>';
  			$('#bedrooms_box .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
  			if(bedrooms == 0){
  				$('#bedrooms_box .grade_desc input').hide();
      		$('#bedrooms_box .grade_desc span').text('Studio');
    		} else if(bedrooms == 1){
      		$('#bedrooms_box .grade_desc input').show();
    			$('#bedrooms_box .grade_desc span').text(' Bedroom');
    		} else {
        	$('#bedrooms_box .grade_desc input').show();
      		$('#bedrooms_box .grade_desc span').text(' Bedrooms');
      	}
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        if(!minBedroom){ ui.value = 0; }
        else{ ui.value = minBedroom; }
        $( "#bedrooms" ).val(minBedroom);
        $('#bedrooms_box .ui-slider-handle').html('<span>'+ui.value+'</span>');
        if(minBedroom == 0){
          $('#bedrooms_box .grade_desc input').hide();
          $('#bedrooms_box .grade_desc span').text('Studio');
        } else if(minBedroom == 1){
    			$('#bedrooms_box .grade_desc input').show();
    			$('#bedrooms_box .grade_desc span').text(' Bedroom');
    		} else {
    			$('#bedrooms_box .grade_desc input').show();
    			$('#bedrooms_box .grade_desc span').text(' Bedrooms');
    		}
    		return false;
      }
    }).addTouch();

    $( "#bedrooms_slider_register" ).slider({
      range: 'max',
      min: 0,
      max: 8,
      value: minBedroom,
      slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider    */
        var bedrooms = ui.value;
        $( "#bedrooms" ).val(bedrooms);
        var minBedroom = bedrooms;
        $.cookie("minBedroom", minBedroom);
        ui.sliderText =	'<span>'+ui.value+'</span>';
        $('#bedrooms_box .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
        if(bedrooms == 0){
          $('#bedrooms_box .grade_desc input').hide();
          $('#bedrooms_box .grade_desc span').text('Studio');
        } else if(bedrooms == 1){
          $('#bedrooms_box .grade_desc input').show();
          $('#bedrooms_box .grade_desc span').text(' Bedroom');
        } else {
          $('#bedrooms_box .grade_desc input').show();
          $('#bedrooms_box .grade_desc span').text(' Bedrooms');
        }
        setTimeout(function () { getListingCount(); }, 200);
      }.bind(this),
      create: function( event, ui ) {	/* Function to execute when initializing slider */
        ui.value = 0;
      	$('#bedrooms_box .ui-slider-handle').html('<span>'+ui.value+'</span>');
       	if(minBedroom == 0){
    			$('#bedrooms_box .grade_desc input').hide();
    			$('#bedrooms_box .grade_desc span').text('Studio');
    		} else if(minBedroom == 1){
        	$('#bedrooms_box .grade_desc input').show();
    			$('#bedrooms_box .grade_desc span').text(' Bedroom');
    		} else {
    			$('#bedrooms_box .grade_desc input').show();
    			$('#bedrooms_box .grade_desc span').text(' Bedrooms');
    		}
    		return false;
      }
    }).addTouch();
    $( "#bedrooms" ).val(minBedroom);
  },
  startOnSearch: function(){
    $('#listings').tabs('select',0);
    $.cookie('listActive', "no");
    var active = $.cookie('listActive');
  },
  continueStep: function(id, event){
    $("body").scrollTop(0);
    $(window).scrollTop(0);
    if(id =='tabs-2'){
      if($('#role').text() == 'guest'){
      	$("#reg-or-not-popup").dialog('open'); /*show a popup*/
      };

      if (this.props.role == "buyer" && this.state.numSearches == 0) {
        var location_grade = $('#location_grade').slider("value"),
        neighborhoods = $( 'input[name="multiselect_neighborhoods"]:checked' ),
        prop_type = $( 'input[name="multiselect_prop_type"]:checked' ),
        building_grade = $('#building_grade').slider("value"),
        views_grade = $('#views_grade').slider("value"),
        min_floor = $('#min_floor').val(),
        bedrooms = $( "#bedrooms_slider" ).slider("value"),
        min_price = $('#min_price').attr('data-price'),
        max_price = $('#max_price').attr('data-price'),
        living_area = $('#living_grade').slider("value"),
        bedroom_area = $('#bedroom_grade').slider("value"),
        building_address = $('#address-search').val(),
        n = [],
        p = [],
        amen = [];

        for(var i=0; i < neighborhoods.length; i++){ n.push(neighborhoods[i].value); }
        for(var i=0; i < prop_type.length; i++){ p.push(prop_type[i].value); }

        if(n.length == 0){
          n.push("North");
          n.push("Westside");
          n.push("Eastside");
          n.push("Chelsea");
          n.push("SMG");
          n.push("Village");
          n.push("Lower");
        }
        if(p.length == 0){
          p.push("1");
          p.push("2");
          p.push("4");
          p.push("5");
        }
        if(amenities['garage'] === true){ amen.push(1); }
        if(amenities['pool'] === true){ amen.push(2); }
        if(amenities['laundry'] === true){ amen.push(3); }
        if(amenities['doorman'] === true){ amen.push(4); }
        if(amenities['elevator'] === true){ amen.push(5); }
        if(amenities['pets'] === true){ amen.push(6); }
        if(amenities['fireplace'] === true){ amen.push(7); }
        if(amenities['healthclub'] === true){ amen.push(8); }
        if(amenities['prewar'] === true){ amen.push(9); }
        if(amenities['outdoor'] === true){ amen.push(10); }
        if(amenities['wheelchair'] === true){ amen.push(11); }

        $.ajax({
          type: "POST",
          url: "/controllers/save-criteria-auto.php",
          data: {"location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : n, "prop_type":p, "amenities":amen},
          success: function(data){
            console.log("formula saved");
            this.setState({numSearches: 1});
          }.bind(this),
          error: function(){
            console.log("formula save failed");
          }
        });
      }

      setTimeout(function (){
        $('#listings').show();
        $('#new-thead').show();
      },1000);
      var state = {},
      // Get the id of this tab widget.
      id = "tabs",
      tabs = $('#tabs').tabs(),
      // Get the index of this tab.
      current = tabs.tabs('option', 'selected');
      current = (current + 1);
      var next = (current + 1);
      if (next == 0 || next == 1 || next == 2  ) {
        state[ id ] = current; // Set the state!
        $.bbq.pushState( state );
        tabs.tabs('select', current); // switch to next tab
      } else {
        storePage("results");
        this.startOnSearch(); // To prevent starting on a previously opened tab that's been re-opened.
        show_results(); // this function is in head.tpl.php
        $(".ui-jqgrid-bdiv").focus();
        if (previousOpenListings > 0) {
          $('#listings').removeClass('fixedTop').css('height', 'unset');
          $('#listings .ui-tabs-nav').removeClass('fixedTop');
          $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
        }
      }
    }
	  else{
      storePage("tab-2");
		  var state = {},
      // Get the id of this tab widget.
		  id = "tabs",
			tabs = $('#tabs').tabs(),
			// Get the index of this tab.
			current = tabs.tabs('option', 'selected');
			current = (current + 1);
			var next = (current + 1);
			if (next == 0 || next == 1 || next == 2  ) {
				state[ id ] = current; // Set the state!
				$.bbq.pushState( state );
				tabs.tabs('select', current); // switch to next tab
			} else {
        storePage("results");
			  this.startOnSearch(); // To prevent starting on a previously opened tab that's been re-opened.
        show_results(); // this function is in head.tpl.php
        $(".ui-jqgrid-bdiv").focus();
        if (previousOpenListings > 0) {
          $('#listings').removeClass('fixedTop').css('height', 'unset');
          $('#listings .ui-tabs-nav').removeClass('fixedTop');
          $('.thead-container').removeClass('fixedTheadTop').css('top', 'auto');
        }
			}
	  }
  },
	previousTab: function(){
    storePage("tab-1");
    // Get the id of this tab widget.
    var id ="tabs",
    tabs = $('#tabs').tabs(),
    // Get the index of this tab.
    current = tabs.tabs('option', 'selected');
    current = (current - 1);
    tabs.tabs('select', current); // switch to next tab
  },
  render : function(){
    var role = this.props.role;
    var loginOrRegister_lnk
    var listingCounters;
    var counterNote;
    if (this.props.role == 'guest'){
      listingCounters = <div id="listing-counters-div"><span id="listing-count" className="grade_desc hide"><span className='custom-listing-counter'><h4><span id="l_count"></span> Listings on public portal</h4> &mdash; for guests</span></span><span id="listing-count_homepik" className="grade_desc homepik_listing_cnt double-counter"><span className='custom-listing-counter'><h4><span id="lm_count"></span> Listings in HomePik</h4> <span id="loginOrRegister_lnk">&mdash; <span id="registeredOnly">for registered buyers </span><span id="loginOrRegister"><a href="/signin.php">log in</a> or <a href="javascript:void(0)" className='regOrNotSubmit'>register</a></span></span></span></span></div>;
      counterNote = <div id="listing-count_note" className="grade_desc homepik_listing_cnt double-counter"><div className='custom-listing-counter'><h4>Note:</h4> Results will change as sliders are moved.</div></div>
    }
    else{
      listingCounters = <div id="listing-counters-div"><span id="listing-count_homepik" className="grade_desc homepik_listing_cnt single-counter "><span className='custom-listing-counter'><h4><span id="lm_count"></span> Listings in HomePik</h4></span></span></div>;
      counterNote = <div id="listing-count_note" className="grade_desc homepik_listing_cnt single-counter "><div className='custom-listing-counter'><h4>Note:</h4> Results will change as sliders are moved.</div></div>
    }
    var disclaimer;
    if (this.props.role == 'guest'){ disclaimer = <div id="disclaimer">Guest public portal</div>; }
    else{ disclaimer = <div id="disclaimer">This is not a public portal. For registered buyers only.</div>; }
    return(
      <div id="tabs" className="search-criteria prevStepsHeader tab-menu">
        <ul style={{display: "none"}}>
          <li><a href="#tabs-1">Basic Details</a><span className="tabarrow">&rsaquo;</span></li>
          <li><a href="#tabs-2">Buying Formula</a><span className="tabarrow">&rsaquo;</span></li>
          <li id="submit-search-tab"><a href="#">Search Results</a><span className="tabarrow">&rsaquo;</span></li>
          <li id="submit-search-tab"><a href="#">Listing Detail</a></li>
        </ul>
        <div id="tabs-1" className="data-part-1">
          {listingCounters}
          {counterNote}
          <div id="bedrooms_box" className='criterion break'>
            <div className="label">Minimum Bedrooms</div>
            <div className="mini" id="bedrooms_wrapper">
              <div className="bedrooms mini">
                <span className="grade_desc">
                  <input disabled style={{display:'none'}} id="bedrooms" className="bedrooms_desc numbersOnly"/>
                  <span>Studio</span>
                </span>
                <div className="bedroom_slider_div">
                  <div id="bedrooms_slider" className="mini slider"></div>
                  <img src="http://homepik.com/images/bedroomsslider2.png" className="numberline" alt="Bedroom Slider"  />
                </div>
                <div className="tapTab1">(tap or slide)</div>
              </div>
            </div>
          </div>
          <div id="price_box" className="criterion">
            <div className="label">Price </div>
            <div className="price mini">
              <span className="grade_desc">$
                <span id="min_price"></span> to <span id="max_price"></span>
              </span>
              <div className="mini ui-slider" id="price"></div>
              <img src="http://homepik.com/images/homepik_price_slider_c.png" className="numberline" />
            </div>
            <div className="tapTab1">(tap or slide)</div>
          </div>
          <div id="nbhood_box" className='criterion select'>
            <div className="label">Neighborhoods</div><div id="neighborhoods-container">
              <select id="neighborhoods" name="neighborhoods" multiple="multiple">
                <option value="North">Far Uptown</option>
                <option value="hidden">116th St. and north, including Harlem, Hudson Heights, Washington Heights, Inwood, Spanish Harlem</option>
                <option value="Westside" >Upper West Side</option>
                <option value="hidden">59th St. up to 116th St., west of Central Park including Lincoln Center, Upper West Side, Manhattan Valley</option>
                <option value="Eastside" >Upper East Side</option>
                <option value="hidden">59th St. to 116th St., east of Central Park, including Lenox Hill, Yorkville, Carnegie Hill, Roosevelt Island</option>
                <option value="Chelsea" >Midtown West</option>
                <option value="hidden">14th St. to 59th St., west, including Flatiron, Chelsea, Hudson Yards, Clinton</option>
                <option value="SMG" >Midtown East</option>
                <option value="hidden">From 14th St. to 59th St., east, including Gramercy Park, Murray Hill, Sutton Place</option>
                <option value="Village" >East/West Village</option>
                <option value="hidden2">From Houston St. to 14th St., east and west, including Noho, Greenwich Village</option>
                <option value="Lower" >Downtown</option>
                <option value="hidden">Below Houston St. including Soho, Tribeca, Little Italy, Lower East Side, Chinatown, Financial District, Battery Park City, South Street Seaport</option>
              </select>
            </div>
            <div className="selectborder">&nbsp;</div>
          </div>
          <div id="floor_box" className='criterion select'>
            <div className="label">Property Type</div>
            <div id="prop-container" >
              <select id="prop_type" name="prop_type" multiple="multiple">
                <option value="1" >Co-op</option>
                <option value="2" >Condo</option>
                <option value="4" >House/Townhouse</option>
                <option value="5" >Condop</option>
              </select>
            </div>
            <div className="selectborder">&nbsp;</div>
            <div id="floor-container" style={{display:'none'}} ><input  max="99" defaultValue="0" id="min_floor" /></div>
          </div>
          <div id="browseSelection" style={{display: "none"}}></div>
          <div className="previous_nxt_btns">
            <div className='continue details' onClick={this.continueStep.bind(this,"tabs-1")} custom = "tabs-1">CONTINUE <span className="nextIcon">›</span></div>
          </div>
          <div id="criteria_footer_tab-1">{disclaimer}</div>
        </div>
        <div id="tabs-2" className="data-part-2">
          <span id="backBtn" className="Text-1 clearfix colelem criteriaBtn" onClick={this.previousTab}><span className="fa fa-chevron-left"></span> Back</span>
          {listingCounters}
          {counterNote}
          <div id="location" className='criterion'>
            <h5 className="label"><span> Minimum Location Grade</span></h5>
            <div id="loc_desc" className="grade_desc">Active commercial street</div>
            <div className="location-slider" >
              <div id="location_grade" className="mini slider"></div>
              <img src="http://homepik.com/images/locationslider2.png" className="numberline" alt="Location Slider"  />
            </div>
            <div className="tapTab2">(tap or slide)</div>
            <div id="loc_1" className="hidden">All locations</div>
            <div id="loc_2" className="hidden">New developing market</div>
            <div id="loc_3" className="hidden">Emerging residential area</div>
            <div id="loc_4" className="hidden">Active commercial street</div>
            <div id="loc_5" className="hidden">Active commercial street / Residential Area</div>
            <div id="loc_6" className="hidden">Quiet residential street</div>
            <div id="loc_7" className="hidden">Residential area / neighborhood amenities</div>
            <div id="loc_8" className="hidden">Residential area / near local park or river</div>
            <div id="loc_9" className="hidden">Residential area close to major park</div>
            <div id="loc_10" className="hidden">Internationally renown / near major park</div>
          </div>
          <div id="building" className='criterion'>
            <h5 className="label" ><span > Minimum Building Grade</span></h5>
            <div id="buil_desc" className="grade_desc">Elevator building in fair condition</div>
            <div className="building-slider">
              <div id="building_grade" className="mini slider"></div>
              <img src="http://homepik.com/images/buildingslider2.png" className="numberline" alt="Building Slider"  />
            </div>
            <div className="tapTab2">(tap or slide)</div>
            <div id="buil_1" className="hidden">All buildings</div>
            <div id="buil_2" className="hidden">Walkup in fair condition</div>
            <div id="buil_3" className="hidden">Walkup in good condition </div>
            <div id="buil_4" className="hidden">Elevator building in fair condition</div>
            <div id="buil_5" className="hidden">Elevator building in good condition</div>
            <div id="buil_6" className="hidden">Doorman building &mdash;no amenities</div>
            <div id="buil_7" className="hidden">Doorman building with amenities</div>
            <div id="buil_8" className="hidden">Full service building with amenities</div>
            <div id="buil_9" className="hidden">Locally renowned building or new construction with full services</div>
            <div id="buil_10" className="hidden">International renown</div>
          </div>
          <div id="view" className='criterion'>
            <h5 className="label"><span> Minimum View Grade </span></h5>
            <div id="views_desc" className="grade_desc">Interior courtyard or area without view but bright</div>
            <div className="view-slider">
              <div id="views_grade" className="mini slider"></div>
              <img src="http://homepik.com/images/viewsslider2.png" className="numberline" alt="Views Slider"  />
            </div>
            <div className="tapTab2">(tap or slide)</div>
            <div id="views_1" className="hidden">All properties</div>
            <div id="views_2" className="hidden">Indirect light</div>
            <div id="views_3" className="hidden">Interior courtyard or area with moderate light</div>
            <div id="views_4" className="hidden">Interior courtyard or area without view but bright</div>
            <div id="views_5" className="hidden">Street view or interior garden, moderate light</div>
            <div id="views_6" className="hidden">Street view or interior garden, bright</div>
            <div id="views_7" className="hidden">Rooftop views</div>
            <div id="views_8" className="hidden">Cityscape views</div>
            <div id="views_9" className="hidden">Cityscape and river or park views</div>
            <div id="views_10" className="hidden">Cityscape and Central Park views</div>
          </div>
          <div id="bedroom" className='criterion'>
            <h5 className="label"><span> Master Bedroom </span></h5>
            <div id="bedroom_desc" className="grade_desc">Any bedroom size is okay</div>
            <div className="master-room-slider">
              <div id="bedroom_grade" className="mini slider"></div>
              <img src="http://homepik.com/images/bedroomslider2.png" className="numberline" alt="Bedroom Slider"  />
            </div>
            <div className="tapTab2">(tap or slide)</div>
            <div id="bedroom_1" className="hidden">Any bedroom size is okay</div>
            <div id="bedroom_2" className="hidden">A medium master bedroom or larger: at least 13 ft by 11 ft</div>
            <div id="bedroom_3" className="hidden">A large master bedroom or larger: at least 16 ft by 11 ft</div>
            <div id="bedroom_4" className="hidden">An extra-large master bedroom: at least 19 ft by 11 ft</div>
          </div>
          <div id="living" className='criterion'>
            <h5 className="label"><span> Living Room </span></h5>
            <div id="living_desc" className="grade_desc">Any living room size is okay</div>
            <div className="living-room-slider">
              <div id="living_grade" className="mini slider"></div>
              <img src="http://homepik.com/images/livingslider2.png" className="numberline" alt="Living Room Slider"  />
            </div>
            <div className="tapTab2">(tap or slide)</div>
            <div id="living_1" className="hidden">Any living room size is okay</div>
            <div id="living_2" className="hidden">A medium-sized living room or larger: at least 18 ft by 12 ft</div>
            <div id="living_3" className="hidden">A large-sized living room or larger: at least 22 ft by 12 ft</div>
            <div id="living_4" className="hidden">A extra-large living room or larger: at least 27 ft by 12 ft</div>
            <div className="selectborder">&nbsp;</div>
          </div>
          <div id="amenities" className='criterion select'>
            <div className="label">Amenities</div>
            <div style={{height: 27 + 'px', padding: 0}} className="grade_desc">Click icons to select amenities</div>
            <div id="amenities-container">
              <div className="amenitiesmenu criterion">
                <table>
                  <tbody>
                    <tr>
                      <td rel="elevator" className="amenity_icons22">
                      <div rel="elevator" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/elevator.png" id="elevator" title="elevator" rel="elevator" name="5" className="amenity_icons" alt="Elevator" />
                          <span className="amenity_icons_text">Elevator</span>
                        </div>
                      </td>
                      <td rel="doorman" className="amenity_icons22">
                      <div rel="doorman" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/doorman.png" id="doorman" title="doorman" rel="doorman" name="4" className="amenity_icons" alt="Doorman" />
                          <span className="amenity_icons_text">Doorman</span>
                        </div>
                      </td>
                      <td rel="laundry" className="amenity_icons22">
                      <div rel="laundry" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/laundry.png" id="laundry" title="laundry" rel="laundry" name="3" className="amenity_icons" alt="Laundry" />
                          <span className="amenity_icons_text">Laundry</span>
                        </div>
                      </td>
                      <td rel="pets" className="amenity_icons22">
                      <div rel="pets" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/pets.png" id="pets" title="pets" rel="pets" name="6" className="amenity_icons" alt="Pets" />
                          <span className="amenity_icons_text">Pets</span>
                        </div>
                      </td>
                      <td rel="fireplace" className="amenity_icons22">
                        <div rel="fireplace" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/fireplace.png" id="fireplace" title="fireplace" rel="fireplace" name="5" alt="Fireplace" className="amenity_icons" />
                          <span className="amenity_icons_text">Fireplace</span>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td rel="pool" className="amenity_icons22">
                      <div rel="pool" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/pool.png" id="pool" title="pool" rel="pool" name="2" className="amenity_icons" alt="Pool" />
                          <span className="amenity_icons_text">Pool</span>
                        </div>
                      </td>
                      <td rel="garage" className="amenity_icons22">
                      <div rel="garage" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/garage.png"  id="garage" title="garage" rel="garage" name="1" className="amenity_icons" alt="Garage" />
                          <span className="amenity_icons_text">Garage</span>
                        </div>
                      </td>
                      <td rel="healthclub" className="amenity_icons22">
                      <div rel="healthclub" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/healthclub.png" id="healthclub" title="healthclub" rel="healthclub" name="1" alt="Healthclub" className="amenity_icons" />
                          <span className="amenity_icons_text">Health club</span>
                        </div>
                      </td>
                      <td rel="outdoor" className="amenity_icons22">
                       <div rel="outdoor" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/outdoor.png" title="outdoor space" rel="outdoor" name="4" className="amenity_icons" alt="Roofdeck" />
                          <span className="amenity_icons_text">Outdoor space</span>
                        </div>
                      </td>
                      <td rel="wheelchair" className="amenity_icons22">
                       <div rel="wheelchair" className="amenity_icons_block">
                          <img src="http://homepik.com/images/amenities/wheelchair.png" id="wheelchair" title="wheelchair" rel="wheelchair" alt="Wheelchair" name="6" className="amenity_icons" />
                          <span className="amenity_icons_text">Handicap accessible</span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <div className='b_left'>
                  <table>
                    <tbody>
                      <tr>

                        <td rel="prewar" className="amenity_icons22">
                        <div rel="prewar" className="amenity_icons_block">
                            <img src="http://homepik.com/images/amenities/prewar.png" id="prewar" title="prewar" rel="prewar" name="3" className="amenity_icons" alt="Prewar" />
                            <span className="amenity_icons_text">Prewar</span>
                          </div>
                        </td>
                        <td rel="timeshare" className="amenity_icons22">
                         <div rel="timeshare" className="amenity_icons_block">
                            <img src="http://homepik.com/images/amenities/timeshare.png" id="timeshare" title="timeshare" rel="timeshare" name="3" className="amenity_icons" alt="Time share" />
                            <span className="amenity_icons_text">Time Shares</span>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td rel="newconstruction" className="amenity_icons22">
                         <div rel="newconstruction" className="amenity_icons_block">
                            <img src="http://homepik.com/images/amenities/newconstruction.png" id="newconstruction" title="newconstruction" rel="newconstruction" name="3" className="amenity_icons" alt="New Construction" />
                            <span className="amenity_icons_text">New construction</span>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div className="previous_nxt_btns">
            <a href="#tabs-1" className='no-underline'><div id="previous" className='previous' onClick ={this.previousTab}><span className="nextIcon">‹</span> PREVIOUS</div></a>
            <div id="searchbtn" className='continue' custom="tabs-2" data-buyer="{this.props.email}" onClick={this.continueStep.bind(this,"tabs-2")}>CONTINUE <span className="nextIcon">›</span></div>
          </div>
          <div id="criteria_footer_tab-2">{disclaimer}</div>
        </div>
      </div>
    );
  }
});

var SearchResultHeader = React.createClass({
	render: function(){
    return(
      <div className='thead-container'>
        <div id="new-thead" className="ui-jqgrid-hbox">
          <table className="ui-jqgrid-htable">
            <thead>
              <tr role="rowheader" className="ui-jqgrid-labels">
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_address">
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_address" className="ui-jqgrid-sortable"><span>loading</span></div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_price">
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_price" className="ui-jqgrid-sortable jqgh_sorter" data-column='price'>
                    Price
                    <span className="s-ico">
                      <span className="ui-grid-ico-sort ui-icon-asc ui-icon ui-icon-triangle-1-n ui-sort-ltr" sort="asc"></span>
                      <span className="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr" sort="desc"></span>
                    </span>
                  </div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_loc" >
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_amen" className="ui-jqgrid-sortable"><a href="#" id="jqgh_amen">Amenities</a></div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_loc2" style={{width: 95 + "px"}}>
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_loc" className="ui-jqgrid-sortable jqgh_sorter" data-column='loc'>Location
                    <span className="s-ico">
                      <span className="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr" sort="asc"></span>
                      <span className="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr" sort="desc"></span>
                    </span>
                  </div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_bld" style={{width: 95 + "px"}}>
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_bld" className="ui-jqgrid-sortable jqgh_sorter" data-column='bld'>Building
                    <span  className="s-ico">
                      <span className="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr" sort="asc"></span>
                      <span className="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr" sort="desc"></span>
                    </span>
                  </div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_vws" style={{width: 90 + "px"}}>
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr">&nbsp;</span>
                  <div id="jqgh_vws" className="ui-jqgrid-sortable jqgh_sorter" data-column='vws'>Views
                    <span className="s-ico">
                      <span className="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr" sort="asc"></span>
                      <span className="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr" sort="desc"></span>
                    </span>
                  </div>
                </th>
                <th className="ui-state-default ui-th-column ui-th-ltr" role="columnheader" id="list_vroom_sqf" style={{width: 95 + "px"}}>
                  <span className="ui-jqgrid-resize ui-jqgrid-resize-ltr" >&nbsp;</span>
                  <div id="jqgh_vroom_sqf" className="ui-jqgrid-sortable jqgh_sorter" data-column='vroom_sqf'>Space
                    <span  className="s-ico">
                      <span className="ui-grid-ico-sort ui-icon-asc ui-state-disabled ui-icon ui-icon-triangle-1-n ui-sort-ltr" sort="asc"></span>
                      <span className="ui-grid-ico-sort ui-icon-desc ui-state-disabled ui-icon ui-icon-triangle-1-s ui-sort-ltr" sort="desc"></span>
                    </span>
                  </div>
                </th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    );
	}
});

window.Content = React.createClass({
  logout: function(){
    $.cookie("minPrice", 1);
    $.cookie("maxPrice", 20);
    $.cookie('location', 1);
    $.cookie("building", 1);
    $.cookie("views", 1);
    $.cookie("bedroom", 1);
    $.cookie("living", 1);
    $.cookie("minBedroom", 0);
    $.cookie("North", "false");
    $.cookie("Westside", "false");
    $.cookie("Eastside", "false");
    $.cookie("Chelsea", "false");
    $.cookie("SMG", "false");
    $.cookie("Village", "false");
    $.cookie("Lower", "false");
    $.cookie("Coop", "false");
    $.cookie("Condo", "false");
    $.cookie("House", "false");
    $.cookie("Condop", "false");
    $.cookie("garage", "false");
    $.cookie("pool", "false");
    $.cookie("laundry", "false");
    $.cookie("doorman", "false");
    $.cookie("elevator", "false");
    $.cookie("pets", "false");
    $.cookie("fireplace", "false");
    $.cookie("healthclub", "false");
    $.cookie("prewar", "false");
    $.cookie("outdoor", "false");
    $.cookie("wheelchair", "false");

    var $dialog =  $("#ajax-box").dialog({
      modal: true,
	  width: 580,
      dialogClass: 'ajaxbox clearListingsPopup',
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

    ReactDOM.render(<ClearListings closeDialog={closeDialog}/>, $dialog[0]);
  },
  gobackToSearch: function (){
    $('#listings').tabs('select',0);
    $.cookie('listActive', "no");
    var active = $.cookie('listActive');
  },
  componentDidMount:function (){
    $("#new-thead").hide();
    $("#browseSelection").html("sales"); // Comment out when using the browse popup below.
    /*
    var $dialog =  $("#new-search-popup").dialog({
      width: 355,
      dialogClass: 'newSearchPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode($dialog);
        $( this ).remove();
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    $("#overlay").show();
    ReactDOM.render(<Browse closeDialog={closeDialog}/>, $dialog[0]);
    */
    getListingCount();
    reOpenListingTabs();
  },
  render : function(){
    var self = this;
	  var logout_html;
    var disclaimer;
    var viewOption;
    var saveListingGuestClass = 'icon-heart';
		if (this.props.role == 'guest'){
      logout_html = <div className="logout-outer" > <span className="name">{this.props.name}</span> <a href="/controllers/guest-register.php"><span className="signup">Sign<br/>up</span></a> <a href="/controllers/signin.php"><span className='logout logoutBtn' style={{height: 37 + 'px'}}>Log <br/> in</span></a></div>;
      disclaimer = <div id="disclaimer">Guest public portal</div>;
      saveListingGuestClass += " color-c2";
      viewOption =  <p className='toBeHiddenOnListDetail clickCompare clickGuestCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className={saveListingGuestClass}> <span id="compare-text">Click to compare saved listings</span></span></a></p>;
    }
    if (this.props.role == 'agent'){
      logout_html = <a className="logout-outer"><span className="name">{this.props.name}</span> <span className='logout logoutBtn' onClick={this.logout}>Log <br/> out</span></a>;
      disclaimer = <div id="disclaimer">This is not a public portal. For registered buyers only.</div>;
      viewOption = <p className='toBeHiddenOnListDetail clickCompare clickAgentCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className={saveListingGuestClass}> <span id="compare-text">Click to compare saved listings</span></span></a></p>
    }
    if (this.props.role == 'buyer'){
      logout_html = <a className="logout-outer"><span className="name">{this.props.name}</span> <span className='logout logoutBtn' onClick={this.logout}>Log <br/> out</span></a>;
      disclaimer = <div id="disclaimer">This is not a public portal. For registered buyers only.</div>;
      viewOption = <p className='toBeHiddenOnListDetail clickCompare clickBuyerCompare'><a style={{textDecoration: "none", color: "inherit"}}><span data-text='compare' className={saveListingGuestClass}> <span id="compare-text">Click to compare saved listings</span></span></a></p>;
    }
    return(
      <div>
        <div id="new-search-popup"></div>
        <div id="role">{this.props.role}</div>
        <div id="email">{this.props.email}</div>
        <div id="guestID">{this.props.guestID}</div>
        <div id="agentID">{this.props.agentID}</div>
        <div id="main" style={{position:'relative', height:'530px'}} className='search-step-3 search-page-block'>
          <RegisterOrNot/>
          <div id='registration-div'>
            <div id='signup-popup'></div>
            <PrimaryAgent/>
          </div>
          <CriteriaNavBar role={this.props.role} name={this.props.name} email={this.props.email} messages={this.props.messages}/>
          <Tabs email={this.props.email} role={this.props.role} numSearches={this.props.numSearches} justRegSaveForm={this.props.justRegSaveForm} />
          <div className="ui-jqgrid table-header">
            <SearchNavBar role={this.props.role} name={this.props.name} email={this.props.email} messages={this.props.messages}/>
            <AddressSearch role={this.props.role} />
            <div className="static-content-search">
              <div className="col-xs-12 col-sm-5 sr_count">
                <div className="col-xs-12 col-sm-4 text-left">
                  <a style={{display:'none', cursor:'pointer'}} className="b_test toBeDisplayedOnListDetail" onClick={self.gobackToSearch.bind(self, '')} id='searchResultTabBtnTrigger'> <span id="backArrow"> </span> Search results</a>
                </div>
              </div>
            </div>
          </div>
          <div id="listings">
            <ul>
              <li id="results-tab" style={{display:'none'}}>
                <a href="#listings-1"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Search Results </a>
              </li>
            </ul>
            <div id="listings-1"></div>
          </div>
        <div className="thread_scrollwrapper_block">
          <SearchResultHeader role={this.props.role} name={this.props.name} email={this.props.email}/>
          <div id="scrollwrapper2" >
            <div id="scrollwrapper">
              <div id="scroller">
                <div id="jqGrid_container">
                  <div id="noListings">No listings found that match criteria.</div>
                  <table id="list"></table>
                  <menu type='context' id='customMenu'>
                    <menuitem id="openNewTab" label='Open in New Tab'></menuitem>
                  </menu>
                </div>
                <div id="pager"></div>
              </div>
            </div>
          </div>
        </div>
          <div id='add-buyer-box' className='add-buyer-custom'></div>
          <div id='ajax-box'></div>
          <div id='ajax-box2'></div>
          <div id='ajax-box3'></div>
          <div id="agent-list"></div>
          <div id='compare-listings'></div>
          <div id='email-listing-folder'></div>
          <div id='add-edit-listing-comment'></div>
          <div id='save-listing'></div>
          <div id='load-save-listing'></div>
          <div id='delete-listing'></div>
          <div id="agentSave" title='Save to Buyer Folder'></div>
          <div id="footer" style={{display: "none"}}>
            {disclaimer}
          </div>
        </div>
      </div>
    );
  }
});


/*guest resgister popup*/
var RegisterOrNot = React.createClass({
  getInitialState:function (){
    return {
      yesIcon: 'fa fa-check yesIcon',
      noIcon: 'fa fa-circle-o noIcon',
    };
  },
  componentDidMount:function (){
    var self = this;
    $('input[type=radio][name=regOrNotRadio]').change(function() {
      if (this.value == '0') {
        $('.yesIcon').removeClass('fa-check');
        $('.yesIcon').addClass('fa-circle-o');
        $('.noIcon').removeClass('fa-circle-o');
        $('.noIcon').addClass('fa-check');
      }
      else {
        $('.noIcon').removeClass('fa-check');
        $('.noIcon').addClass('fa-circle-o');
        $('.yesIcon').removeClass('fa-circle-o');
        $('.yesIcon').addClass('fa-check');
      }
    });
  },
  render: function(){
    return(
      <div id="reg-or-not-popup" data-history="false">
        <div className="c-header">
          Create an account?
          <img alt="Create Account" src="/images/button_housekey_states-housekey_normal.png" />
        </div>
        <div className='regOrNotBody'>
          <p>The Real Estate Board of New York restricts the number of listings a guest can be shown.</p><br/>
          <p>If you want to see the full population of listings you must become a registered buyer with our brokerage firm, HomePik Inc. This will give you access to their listing book as one of their buyers. This will not limit your ability to be a buyer with any other company.</p><br/>
          <p>Do you want to become a registered buyer?</p><br/><br/>
        </div>
        <div className='regOrNotFoot'>
          <label>
            <input style={{display:'none'}} type='radio' name='regOrNotRadio' checked='checked' value='1'/>
            <i className={this.state.yesIcon}></i>
            Yes
          </label>
          <label>
            <input style={{display:'none'}} type='radio' name='regOrNotRadio' value='0'/>
            <i className={this.state.noIcon}></i>
            No
          </label>
          <br/><br/>
          <div className='regOrNotSubmit'>
            <span>Submit <i className='fa fa-chevron-right color-blue'></i></span>
          </div>
        </div>
        <span className='fa fa-times need-to-signup-first-div-closer reg-or-not-popup-closer' id='reg-or-not-popup-closer' title="close"></span>
      </div>
    );
  }
});

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
  closePopup: function(){
    $("#primaryAgent").hide();
    $("#primAgentPopupOverlay").hide();
  },
  componentDidMount:function (){
    $('#closeAgentPopup').click(function (){
      $("#primaryAgent").hide();
      $("#primAgentPopupOverlay").hide();
    });
  },
  listAgents: function(event){
    event.preventDefault();
    
    var $dialog =  $("#agent-list").dialog({
      modal: true,
	  width: 350,
      dialogClass: 'agentListPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('agent-list'));
        var div = document.createElement('div');
        div.id = 'agent-list';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $(".ui-widget-overlay").bind("click", function(){
          $("#agent-list").dialog('close');
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
      <div id="primaryAgent" style={{display:"none"}} className='bubble speech'>
        <img id="closeAgentPopup" src="/images/close-x.png" onClick={this.closePopup} title="close"/>
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
      firstname: "",
      lastname: "",
      email: "",
      pass: "",
      phone: "",
      secQues: "default",
      secAns: "",
      agent: ""
    };
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  checkFirstName: function(){
    if(this.state.firstname != ""){ return true; }
    else{ return false; }
  },
  checkLastName: function(){
    if(this.state.lastname != ""){ return true; }
    else{ return false; }
  },
  checkEmail: function(){
    if(this.state.email != ""){ return true; }
    else{ return false; }
  },
  checkPassword: function(){
    if(this.state.pass != ""){ return true; }
    else{ return false; }
  },
  checkPQ: function(){
	if(this.state.secQues != "default" && this.state.secAns != ""){ return true; }
		else{ return false; }
  },
  checkInput: function(){
    if( this.state.firstname != "" &&  this.state.lastname != "" && this.state.email != "" && this.state.pass != "" &&  this.state.secQues != "default" && this.state.secAns != "") { return true; }
	    else{ return false }
  },

  getAgents: function(){
    $.ajax({
      type: "POST",
      url: "/controllers/get-agents.php",
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
        url: "http://homepik.com/controllers/check-agent.php",
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
		} else {
		  if(x.length > 0){
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
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #invalidBuyerPhone',function(){
          $('#ajax-box').dialog( "option", "title", "Invalid Phone Number" ).dialog('open');
        });
		  }
		}
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
        dialogClass: 'ajaxbox',
        buttons : {
          Ok: function(){
            $(this).dialog("close");
          }
        },
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      $('#ajax-box').load('/controllers/messages.php #registerRquirements',function(){
        $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
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
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      $('#ajax-box').load('/controllers/messages.php #invalidName',function(){
        $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
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
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      $('#ajax-box').load('/controllers/messages.php #invalidBuyerEmail',function(){
        $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
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
        open: function(){
          $(".ui-widget-overlay").bind("click", function(){
            $("#ajax-box").dialog('close');
          });
        }
      });
      $('#ajax-box').load('/controllers/messages.php #passwordRequirement',function(){
        $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
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
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #registerDisclosure',function(){
          $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
        });
        e.preventDefault();
      }
      else if($('input[value=no]:checked').length > 0){
        e.preventDefault();
        {this.props.closeDialog()}
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox noRegistration',
          buttons : {
            Ok: function(){
              $("#ajax-box").dialog("close");
            }
          },
          open: function(){
            $(".ui-widget-overlay").bind("click", function(){
              $("#ajax-box").dialog('close');
            });
          }
        });
        $('#ajax-box').load('/controllers/messages.php #noRegistration',function(){
          $('#ajax-box').dialog( "option", "title", "Notification" ).dialog('open');
        });
      }
      else{ /* continue to processing page */ }
    }
  },
  showPopup: function(){
    $(window).scrollTop(200);
    $("#primAgentPopupOverlay").show();
    $("#primaryAgent").show();
  },
  closePopup: function(){
    $("#primAgentPopupOverlay").hide();
    $("#primaryAgent").hide();
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div id="wrapper">
        <div className="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-above ui-dialogue-signup-popup-closer" onClick={this.closePopup} title="close">
          <span className="fa fa-times"></span>
        </div>
        <div id="container">
          <div className="row">
            <div className="col-md-12 col-sm-12 col-xs-12">
              <div id="registrationArea">
                <div id="registrationTop" className='c-header'>
                  <span id="registrationHeader">Buyer Registration</span>
                  <img src="/images/button_pen_states.png" style={{float:"right"}}/>
                </div>
                <div id="registrationBorder">
                  <form onSubmit={this.validate} action="http://homepik.com/controllers/users/guest-register-process.php" id="validate" className="validate" method="post" autoComplete="off" data-bind="nextFieldOnEnter:true">
                    <table cellPadding="2" cellSpacing="0" border="0">
                      <colgroup>
                        <col width="160"/><col width="260"/>
                      </colgroup>
                      <tbody>
                        <tr>
                          <td className="text-popups">First Name:</td>
                          <td className="text-popups"><input type="text" id="formFirstName" className="text-popups input1" name="firstName" value={this.state.firstname} autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.checkFirstName() ? null : <strong id="firstnameMark" className="asterisk"> {'\u002A'}</strong> }</td>
                        </tr>
                        <tr>
                          <td className="text-popups">Last Name:</td>
                          <td className="text-popups"><input type="text" id="formLastName" className="text-popups input1" name="lastName" value={this.state.lastname} onChange={this.handleChange.bind(this, 'lastname')} />{this.checkLastName() ? null : <strong id="lastnameMark" className="asterisk"> {'\u002A'}</strong> }</td>
                        </tr>
                        <tr>
                          <td className="text-popups">Email:</td>
                          <td className="text-popups"><input type="text" autoCapitalize="off" id="formEmail" className="text-popups input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} />{this.checkEmail() ? null : <strong id="emailMark" className="asterisk"> {'\u002A'}</strong> }</td>
                        </tr>
                        <tr>
                          <td className="text-popups">Create Password:</td>
                          <td className="text-popups"><input type="password" id="formPass" className="text-popups input1" name="password" autoComplete="new-password" onChange={this.handleChange.bind(this, 'pass')} />{this.checkPassword() ? null : <strong id="passwordMark" className="asterisk"> {'\u002A'}</strong> }</td>
                        </tr>
                        <tr>
                          <td className="text-popups">Agent Code:<br/><i>(optional)</i>&nbsp;
                            <img src="/images/circledQuestionMark.png" style={{width:15+"px", cursor: "pointer"}} onClick={this.showPopup} id='showAgentPopup' title='more info'/>
                          </td>
                          <td className="text-popups"><input type="text" id="formAgent" className="agent-code text-popups input1" name="agent-code" value={this.state.agent} onChange={this.handleChange.bind(this, 'agent')} onFocus={this.getAgents} onBlur={this.switchAgent}/></td>
                        </tr>
						<tr className="phone1">
                          <td className="text-popups">Phone:</td>
                          <td className="text-popups"><input type="text" id="formPhone" className="text-popups input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></td>
                        </tr>
                        <tr>
                          <td colSpan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td colSpan='2' id="phoneStatement" className="text-popups">Please select a security question.{this.checkPQ() ? null : <strong id="phoneMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                        </tr>
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
                          <td className="text-popups"><input type="text" id="formAnswer" className="text-popups input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/></td>
                        </tr>
                        <tr>
                          <td className="text-popups" colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong style={{color:'#D2008F', fontSize: 1.1 + 'em', float:"right"}}> {'All Fields Filled'}</strong> : <strong style={{color:'#D2008F'}}> {'\u002A Required Fields'}</strong> }</td>
                        </tr>
                        <tr>
                          <td colSpan="2">&nbsp;</td>
                        </tr>
                        <tr>
                          <td className="text-popups consent" colSpan="2"><input type="radio" className="required" name="terms" value="yes"/><label for="terms"> &nbsp;&nbsp;I acknowledge and consent to the New York State Disclosure Form for Buyer and Seller</label></td>
                        </tr>
                        <tr>
                          <td className="text-popups consent" colSpan="2"><input type="radio" className="required" name="terms" value="no"/><label for="terms"> &nbsp;&nbsp; I <span className="underline">do not</span> consent to the New York State Disclosure Form for Buyer and Seller (will not be registered)</label></td>
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
                            <input type="hidden" name="referrer" value="search" />
                            <input type="hidden" name="code" value="<?=$password?>" /><br />
                            <button type="submit" name="submit" id="registrationSubmit">Submit <span id="arrow">{'\u276F'}</span></button>
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
    );
  }
});

$('body').delegate('.regOrNotSubmit', 'click', function (e){
  var selected = $("input[type='radio'][name='regOrNotRadio']:checked").val();
  if(selected==1){
    $("#reg-or-not-popup").dialog('close');
    var $dialog =  $("#signup-popup").dialog({
      modal: true,
	  width: 550,
      height: 550,
      dialogClass: 'signupPopup',
      close: function(){
        ReactDOM.unmountComponentAtNode(document.getElementById('signup-popup'));
        var div = document.createElement('div');
        div.id = 'signup-popup';
        document.getElementsByTagName('body')[0].appendChild(div);
        $( this ).remove();
      },
      open: function(){
        $(".ui-widget-overlay").bind("click", function(){
          $("#signup-popup").dialog('close');
          $("#primAgentPopupOverlay").hide();
          $("#primaryAgent").hide();
        });
      }
    });
    var closeDialog = function(){
      $dialog.dialog('close');
    }

    ReactDOM.render(<Register closeDialog={closeDialog}/>, $dialog[0]);
  } else{
    $("#reg-or-not-popup").dialog('close');
  }
});

$('body').delegate('.need-to-signup-first-div-link', 'click', function (e){
  e.preventDefault();
  $('#ajax-box').dialog('destroy');
  var $dialog =  $("#signup-popup").dialog({
    modal: true,
	width: 567,
    dialogClass: "registrationPopup signupPopup",
    close: function(){
      ReactDOM.unmountComponentAtNode($dialog);
      $( this ).remove();
    }
  });
  var closeDialog = function(){
    $dialog.dialog('close');
  }

  ReactDOM.render(<Register closeDialog={closeDialog}/>, $dialog[0]);

});

$('body').delegate('#reg-or-not-popup-closer', 'click', function (e){
  $("#reg-or-not-popup").dialog('close');
});
/*guest resgister popup ends*/

var AddBuyer = React.createClass({
  getInitialState: function() {
    return{
      agent_email: this.props.email,
      firstname: "",
      lastname: "",
      email: ""
    };
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  checkFirstName: function(){
    if(this.state.firstname != ""){ return true; }
    else{ return false; }
  },
  checkLastName: function(){
    if(this.state.lastname != ""){ return true; }
    else{ return false; }
  },
  checkEmail: function(){
    if(this.state.email != ""){ return true; }
    else{ return false; }
  },
  checkInput: function(){
    if( this.state.firstname != "" && this.state.lastname != "" && this.state.email != "" ){ return true; }
    else{ return false; }
  },
  updatePhone: function(){
    var number = this.state.phone;
    var x = number.replace(/\D/g,'');

    if (x.length > 0) {
      if (x.length == 10 && !isNaN(x)){
        var y = '('+x[0]+x[1]+x[2]+')'+x[3]+x[4]+x[5]+'-'+x[6]+x[7]+x[8]+x[9]; // Reformat phone number
        this.setState({ phone: y }); // Replace number with formatted number
      } else {
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
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
        $('#ajax-box2').load('/controllers/messages.php #invalidBuyerPhone',function(){
          $('#ajax-box2').dialog( "option", "title", "Invalid Phone Number" ).dialog('open');
        });
      }
    }
  },
  addBuyer: function(event){
    event.preventDefault();
    var firstname = this.state.firstname,
    lastname = this.state.lastname,
    email = this.state.email,
    phone = this.state.phone,
    agentEmail = this.state.agent_email;
    var emailReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    var emailValid = emailReg.test(email);

    // CHECK IF ANY OF THE INPUTS ARE BLANK IF SO DISPLAY ERROR POPUP
    if(firstname == "" || lastname == "" || email == ""){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
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
      $('#ajax-box2').load('messages.php #addBuyerInfo',function(){
          $('#ajax-box2').dialog( "option", "title", "Buyer Information" ).dialog('open');
      });
    }
    // CHECK IF THE EMAIL IS VALID IF NOT DISPLAY ERROR POPUP
    else if (!emailValid) {
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
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
      $('#ajax-box2').load('/controllers/messages.php #invalidBuyerEmail',function(){
        $('#ajax-box2').dialog( "option", "title", "Invalid Email" ).dialog('open');
      });
    }
    // CHECK IF THE EMAIIL IS A BELLMARC EMAIL IF SO DISPLAY ERROR POPUP
    else if(email.indexOf("@bellmarc.com") != -1){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox errorMessage',
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
      $('#ajax-box2').load('/controllers/messages.php #addBuyerEmail',function(){
        $('#ajax-box2').dialog( "option", "title", "Buyer Email" ).dialog('open');
      });
    }
    else{
      $.ajax({
        type: "POST",
        url: "/controllers/check-buyer.php",
        data: {"email": email},
        success: function(data){
          var info = jQuery.parseJSON(data);

          // CHECK IF ACCOUNT EXISTS IF NOT CREATE ONE
          if(info == null || info == false){
            $.get("/controllers/ajax.php", {
              addBuyer: 'true',
              firstname: firstname,
              lastname: lastname,
              email: email,
              phone: phone,
              success: function(result){
                $("#ajax-box2").dialog({
                  modal: true,
                  height: 'auto',
                  width: '275px',
                  autoOpen: false,
                  dialogClass: 'ajaxbox confirmationMessage',
                  buttons: {
                    "View Buyer": function(){
                      window.location = "http://homepik.com/controllers/buyers.php?buyer="+email+"&fn="+firstname+"&ln="+lastname+"MP="+this.props.mainPage;
                    }.bind(this),
                    "Close": function(){
                      $( "#ajax-box2" ).dialog( "destroy" );
                    }
                  },
                  close: function() {
                    $( "#ajax-box2" ).dialog( "destroy" );
                  },
                  open: function(){
                    $(".ui-widget-overlay").bind("click", function(){
                      $("#ajax-box2").dialog('close');
                    });
                  }
                });
                $('#ajax-box2').load('/controllers/messages.php #addBuyerConfirmation',function(){
                  $('#ajax-box2').dialog( "option", "title", "Buyer Email" ).dialog('open');
                });
              }.bind(this)
            });

            {this.props.closeDialog()}
          }
          else{
            // IF ACCOUNT EXISTS CHECK IF THEY HAVE AT LEAST ONE AGENT
            // IF NOT ASSIGN AGENT AS ONE OF BUYER'S AGENTS
            if(info.P_agent == "" || info.P_agent == null){
              $.get("/controllers/ajax.php", {
                AddPrimary: 'true',
                email: email
              });

              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '265px',
                autoOpen: false,
                dialogClass: 'ajaxbox errorMessage',
                buttons: {
                  Ok: function(){
                    $("#ajax-box2").dialog("destroy");
                    window.location = "http://homepik.com/controllers/buyers.php?MP="+this.props.mainPage;
                  }.bind(this)
                },
                close: function() {
                  $( "#ajax-box2" ).dialog( "destroy" );
                },
                open: function(){
                  $(".ui-widget-overlay").bind("click", function(){
                    $("#ajax-box2").dialog('close');
                  });
                }
              });
              $('#ajax-box2').load('/controllers/messages.php #addBuyerPrimary',function(){
                $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
              });

              {this.props.closeDialog()}
            }
            // IF THEY HAVE ONE AGENT CHECK IF THEY HAVE TWO AGENTS
            // IF ONLY HAVE ONE ASSIGN AGENT AS SECOND AGENT
            else if((info.P_agent != '' && info.P_agent != null) && (info.P_agent2 == '' || info.P_agent2 == null)){
              $.ajax({
               type: "POST",
               url: "/controllers/check-agent.php",
               data: {"getEmail":"true", "id":info.P_agent},
               success: function(data){
                  var aEmail = JSON.parse(data);

                  // CHECK IF EMAIL OF AGENT TRYING TO ADD IS ALREADY ASSIGNED
                  // IF NOT ADDED AGENT, IF SO DISPLAY POPUP WITH MESSAGE
                  if(agentEmail != aEmail){
                    $.get("/controllers/ajax.php", {
                      AddPrimary2: 'true',
                      email: email
                    });

                    $("#ajax-box2").dialog({
                      modal: true,
                      height: 'auto',
                      width: '265px',
                      autoOpen: false,
                      dialogClass: 'ajaxbox errorMessage',
                      buttons: {
                        Ok: function(){
                          $("#ajax-box2").dialog("destroy");
                          window.location = "http://homepik.com/controllers/buyers.php?MP="+this.props.mainPage;
                        }.bind(this)
                      },
                      close: function() {
                        $( "#ajax-box2" ).dialog( "destroy" );
                      },
                      open: function(){
                        $(".ui-widget-overlay").bind("click", function(){
                          $("#ajax-box2").dialog('close');
                        });
                      }
                    });
                    $('#ajax-box2').load('/controllers/messages.php #addBuyerPrimary2',function(){
                      $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
                    });

                    {this.props.closeDialog()}
                  }
                  else{
                    $("#ajax-box2").dialog({
                      modal: true,
                      height: 'auto',
                      width: '265px',
                      autoOpen: false,
                      dialogClass: 'ajaxbox errorMessage',
                      buttons: {
                        Ok: function(){
                          $("#ajax-box2").dialog("destroy");
                          window.location = "http://homepik.com/controllers/buyers.php?MP="+this.props.mainPage;
                        }.bind(this)
                      },
                      close: function() {
                        $( "#ajax-box2" ).dialog( "destroy" );
                      },
                      open: function(){
                        $(".ui-widget-overlay").bind("click", function(){
                          $("#ajax-box2").dialog('close');
                        });
                      }
                    });
                    $('#ajax-box2').load('/controllers/messages.php #alreadyAgent',function(){
                      $('#ajax-box2').dialog( "option", "title", "Adding Buyer" ).dialog('open');
                    });

                    {this.props.closeDialog()}
                  }
                }.bind(this)
              });
            }
            // IF THEY HAVE TWO AGENTS DISPLAY POPUP WITH ALERT
            else{
              $("#ajax-box2").dialog({
                modal: true,
                height: 'auto',
                width: '265px',
                autoOpen: false,
                dialogClass: 'ajaxbox errorMessage',
                buttons: {
                  Ok: function(){
                    $(this).dialog("destroy");
                    $('#ajax-box').dialog('destroy');
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
              $('#ajax-box2').load('/controllers/messages.php #addBuyerExists',function(){
                $('#ajax-box2').dialog( "option", "title", "Buyer Exists" ).dialog('open');
              });

              {this.props.closeDialog()}
            }
          }
        }.bind(this),
        error: function(){
          console.log("failed");
        }
      });
    }
  },
  closePopup: function(){
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div>
        <div id="addBuyerTop">
          <span className="text-popups" id="addBuyerHeader">Add New Buyer</span>
          <img src="/images/manage_buyers_normal.png" width="65" height="65" style={{float: "right"}}/>
          <h4 id="closePopup" onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
        </div>
        <div id="addBuyerBorder">
          <form onSubmit={this.addBuyer} autoComplete="off" data-bind="nextFieldOnEnter:true">
            <table cellPadding="2" cellSpacing="0" border="0">
              <colgroup>
                <col width="250"/>
                <col width="350"/>
              </colgroup>
              <tbody>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>First Name:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formFirstName" className="grade_desc input1" name="firstName" value={this.state.firstname} autoFocus onChange={this.handleChange.bind(this, 'firstname')} />{this.checkFirstName() ? null : <strong id="firstnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Last Name:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formLastName" className="grade_desc input1" name="lastName" value={this.state.lastname} onChange={this.handleChange.bind(this, 'lastname')} />{this.checkLastName() ? null : <strong id="lastnameMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }<br/></td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Email:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" autoCapitalize="off" id="formEmail" className="grade_desc input1" value={this.state.email} name="email" onChange={this.handleChange.bind(this, 'email')} />{this.checkEmail() ? null : <strong id="emailMark" style={{color: "#D2008F"}}> {'\u002A'}</strong> }</td>
                </tr>
                <tr>
                  <td className="text-popups" style={{width: 250 + "px"}}>Phone:</td>
                  <td className="text-popups" style={{width: 350 + "px"}}><input type="text" id="formPhone" className="grade_desc input1" name="phone" value={this.state.phone} onChange={this.handleChange.bind(this, 'phone')} onBlur={this.updatePhone}/></td>
                </tr>
                <tr><td></td></tr>
                <tr>
                  <td className="text-popups" colSpan="2" id="fieldsAlert">{this.checkInput() ? <strong style={{color:'#D2008F', fontSize: 1.1 + 'em', float:"right", fontWeight: 600}}> {'All Required Fields Filled'}</strong> : <strong style={{color:'#D2008F', fontSize: 1.1 + 'em', float:"right", fontWeight: 600}}> {'\u002A Required Fields'}</strong> }</td>
                </tr>
                <tr>
                  <td className="text-popups" colSpan="2">&nbsp;</td>
                </tr>
                <tr>
                  <td colSpan="2">
                    <button type="submit" name="addBuyer" className="text-popups" id="addBuyerSubmit" onClick={this.addBuyer}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    );
  }
});

$('body').delegate('.view-edit-buyer-formula','click',function(e){
  var email = $(this).attr('data-user');
  
  var $dialog =  $("#ajax-box").dialog({
    modal: true,
	width: 795,
    height: 550,
    dialogClass: 'viewBuyingFormula',
    close: function(){
      ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
      var div = document.createElement('div');
      div.id = 'ajax-box';
      document.getElementsByTagName('body')[0].appendChild(div);
      $( this ).remove();
    }
  });
  var closeDialog = function(){
    $dialog.dialog('close');
  }

  ReactDOM.render(<ViewFormulas closeDialog={closeDialog} email={email}/>, $dialog[0]);
});
  
var ViewFormulas = React.createClass({
	getInitialState: function() {
    return{
      buyer_email: this.props.email,
      buyer_formulas: [],
      num_formulas: "",
      searchName: ""
    };
  },
	componentDidMount: function(){
	  this.getFormulas();
	},
	getFormulas: function(){
	  $.ajax({
      type: "POST",
      url: "/controllers/get-buyer-formulas.php",
      data: {"buyer": this.state.buyer_email},
      success: function(data){
        var formulas = JSON.parse(data);
        var ajaxStop = 0;
        $(document).ajaxStop(function() {
          if(ajaxStop == 0){
            ajaxStop++;
            this.setState({buyer_formulas: formulas});
            this.setState({num_formulas: formulas.length});
            if(formulas.length > 0){
              this.setState({formula_selected: formulas[0]['name']});
            }
            $("#loadingFormulas").hide();
            $("#noFormulas").show();
          }
        }.bind(this));
      }.bind(this),
      error: function(){
        console.log("failed");
      }
	  });
	},
	checkFormula: function(id){
	  if(this.state.num_formulas > id){ return true; }
    else{ return false; }
	},
	closePopup: function(){
	  {this.props.closeDialog()}
	},
  EditFormula: function(name){
    this.setState({searchName: name});
    var initial = true;
    var $dialog =  $("#ajax-box3").dialog({
      modal: true,
	  width: 1115,
      dialogClass: "editFormula",
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
      this.getFormulas();
      $dialog.dialog('close');
    }.bind(this)
    
    ReactDOM.render(<EditSearch closeDialog={closeDialog} searchName={name} email={this.state.buyer_email} initial={initial}/>, $dialog[0]);
  },
	render: function(){
	  var formulas = this.state.buyer_formulas.map(function(formula){
      return(
        <div>
          <table id="formulaTable" key={formula.id}>
            <colgroup>
              <col width="250"/>
              <col width="500"/>
            </colgroup>
            <tbody>
              <tr>
                <td><p id="x22476-34"><span id="x22476-5" onClick={this.EditFormula.bind(this, formula.name)}>{formula.name}</span></p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-10">Price</p></td>
                <td><p className="Text-1" id="x22477-6">{formula.min_price} to {formula.max_price}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-12">Minimum bedrooms</p></td>
                <td><p className="Text-1" id="x22477-8">{formula.bedrooms}</p></td>
              </tr>
              <tr>
                <td valign="top"><p className="Text-1" id="x22476-14">Neighborhoods</p></td>
                <td><p className="Text-1" id="x22477-10">{formula.neighborhoods}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-16">Property types</p></td>
                <td><p className="Text-1" id="x22477-12">{formula.properties}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-18">Minimum location grade</p></td>
                <td><p className="Text-1" id="x22477-14">{formula.location_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-20">Minimum building grade</p></td>
                <td><p className="Text-1" id="x22477-16">{formula.building_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-22">Minimum view grade</p></td>
                <td><p className="Text-1" id="x22477-18">{formula.view_grade}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-24">Master bedroom</p></td>
                <td><p className="Text-1" id="x22477-20">{formula.bedroom_area}</p></td>
              </tr>
              <tr>
                <td><p className="Text-1" id="x22476-26">Living room</p></td>
                <td><p className="Text-1" id="x22477-22">{formula.living_room}</p></td>
              </tr>
              <tr>
                <td valign="top"><p className="Text-1" id="x22476-28">Amenities</p></td>
                <td><p className="Text-1" id="x22477-24">{formula.amenities}</p></td>
              </tr>
            </tbody>
          </table>
          <p className="Text-1">{'\u00A0'}</p>
          <p className="Text-1">{'\u00A0'}</p>
        </div>
      );
    }.bind(this));
    return(
      <div>
        <div className="text-popups clearfix grpelem" id="x26457-4">
          <h4 style={{cursor: "pointer"}} onClick={this.closePopup} title="close"><i className="fa fa-times"></i></h4>
        </div>
        <div className="clearfix grpelem" id="px22476-84">
          {this.checkFormula(0) ?
            <div className="clearfix grpelem" id="x22476-84">
              <p className="Text-1">&nbsp;</p>
              <p className="Text-1" id="x22476-4"><span id="x22476-2">Choose a buying formula</span><span id="x22476-3" style={{fontStyle: "italic"}}> click name to edit</span></p>
              {formulas}
              <button type="submit" name="search" id="searchFormulaSubmit" onClick={this.closePopup}><span id="submit">Close</span></button>
              <p className="Text-1">&nbsp;</p>
              <p className="Text-1">&nbsp;</p>
              <p className="Text-1">&nbsp;</p>
              <p className="Text-1">&nbsp;</p>
            </div>
          :
            <div className="clearfix grpelem" id="x22476-84">
              <p className="Text-1">&nbsp;</p>
              <p className="Text-1" id="x22476-4"><span id="x22476-2">Choose a buying formula</span><span id="x22476-3" style={{fontStyle: "italic"}}> click name to edit</span></p>
              <p className="Text-1" id="loadingFormulas" style={{display: "inline"}}>Loading formulas...</p>
              <p className="Text-1" id="noFormulas" style={{display: "none"}}>No formulas saved.</p>
            </div>
          }
        </div>
      </div>
	  );
	}
});
  
var EditSearch = React.createClass({
  getInitialState: function() {
    return{
      buyer_email: this.props.email,
      searchName: this.props.searchName,
      name: "",
      bedrooms: "0",
      location: "1",
      building: "1",
      views: "1",
      bedroomArea: "1",
      livingArea: "1",
      minPrice: "100000",
      maxPrice: "2000000",
      minPriceStart: "1",
      maxPriceStart: "20",
      elevator: false,
      doorman: false,
      laundry: false,
      pets: false,
      fireplace: false,
      pool: false,
      garage: false,
      healthclub: false,
      outdoor: false,
      handicap: false,
      prewar: false,
      timeshare: false,
      newconstruction: false,
      n_all: false,
      n_fu: false,
      n_uws: false,
      n_ues: false,
      n_mw: false,
      n_me: false,
      n_v: false,
      n_d: false,
      coop: false,
      condo: false,
      house: false,
      condop: false,
      initial: this.props.initial
    };
  },
  componentDidMount: function(){

    var size_grades = ['','S','M','L','XL'];

    var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
    700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
    1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
    2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
    50000000, 99000000];

    this.getCriteria();

    $( ".editFormula #bedrooms_slider" ).slider({
      range: 'max',
      min: 0,
      max: 8,
      value: this.state.bedrooms,
      slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider    */
        var bedrooms = ui.value;
        this.setState({bedrooms: bedrooms});
        var minBedroom = bedrooms;
        ui.sliderText =	'<span>'+ui.value+'</span>';
        $('.editFormula #bedrooms_slider .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedrooms;
        $('.editFormula #bedrooms_slider .ui-slider-handle').html('<span>'+ui.value+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #location_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.location,
      slide: function(event, ui) { /* Function to execute when sliding the location slider */
        var locationGrade = ui.value;
        this.setState({location: locationGrade});
        ui.sliderText =	"<span><img src='/images/blue-tick.png'/></span>";
        $('.editFormula #location_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /*Function to execute when initializing slider */
        ui.value = this.state.location;
        $('.editFormula #location_grade .ui-slider-handle').html("<span><img src='/images/blue-tick.png'/></span>");
      }.bind(this)
    }).addTouch();

    $(".editFormula #building_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.building,
      slide: function(event, ui) { /* Function to execute when sliding the building slider */
        var buildingGrade = ui.value;
        this.setState({building: buildingGrade});
        ui.sliderText =	"<span><img src='/images/purple-tick.png'/></span>";
        $('.editFormula #building_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.building;
        $('.editFormula #building_grade .ui-slider-handle').html("<span><img src='/images/purple-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #views_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.views,
      slide: function(event, ui) { /* Function to execute when sliding the views slider */
        var viewsGrade = ui.value;
        this.setState({views: viewsGrade});
        ui.sliderText =	"<span><img src='/images/pink-tick.png'/></span>";
        $('.editFormula #views_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.views;
        $('.editFormula #views_grade .ui-slider-handle').html("<span><img src='/images/pink-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #bedroom_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.bedroomArea,
      slide: function(event, ui) { /* Function to execute when sliding the bedroom slider */
        var bedroomGrade = ui.value;
        this.setState({bedroomArea: bedroomGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('.editFormula #bedroom_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedroomArea;
        $('.editFormula #bedroom_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #living_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.livingArea,
      slide: function(event, ui) { /* Function to execute when sliding the living room slider */
        var livingGrade = ui.value;
        this.setState({livingArea: livingGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('.editFormula #living_grade .ui-slider-handle').html(ui.sliderText);/* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) {
        /* Function to execute when initializing slider */
        ui.value = this.state.livingArea;
        $('.editFormula #living_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

     $( ".editFormula #price" ).slider({
      animate: true,
      range: 'true',
      min: 1,
      max: 32,
      values: [this.state.minPriceStart, this.state.maxPriceStart ],
      slide: function( event, ui ) { /* Function to execute when sliding the price slider */
        if ( ( ui.values[ 0 ] + 0 ) >= ui.values[ 1 ] ) { return false; } //Make sure both arrows have at least a .1 gap
        var min_value = ui.values[0], max_value = ui.values[1];
        this.setState({minPrice: prices[min_value]});
        this.setState({maxPrice: prices[max_value]});
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        $('.editFormula #price .ui-slider-handle').first().html('<span>&#155;</span');
        $('.editFormula #price .ui-slider-handle').first().attr("id", "min_price_slider");
        $('.editFormula #price .ui-slider-handle').last().html('<span>&#139;</span');
        $('.editFormula #price .ui-slider-handle').last().attr("id", "max_price_slider");
        return false;
      }
    }).addTouch();
  },
  getCriteria: function(){
    var size_grades = ['','S','M','L','XL'];

    var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
    700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
    1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
    2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
    50000000, 99000000];

    $.ajax({
      type: "POST",
      url: "/controllers/get-search-criteria2.php",
      data: {"email": this.state.buyer_email, "name": this.props.searchName},
      success: function(data){
        var criteria = JSON.parse(data);
          
        if(criteria.name != null){
          if(criteria.bedrooms != ""){ this.setState({bedrooms: criteria.bedrooms}); }
          if(criteria.location_grade != ""){ this.setState({location: criteria.location_grade}); }
          if(criteria.building_grade != ""){ this.setState({building: criteria.building_grade}); }
          if(criteria.view_grade != ""){ this.setState({views: criteria.view_grade}); }
          if(criteria.bedroom_area != ""){ this.setState({bedroomArea: criteria.bedroom_area}); }
          if(criteria.living_area != ""){ this.setState({livingArea: criteria.living_area}); }
          if(criteria.min_price != ""){ this.setState({minPrice: criteria.min_price}); }
          if(criteria.max_price != ""){ this.setState({maxPrice: criteria.max_price}); }
          if(criteria.min_price_start != ""){ this.setState({minPriceStart: criteria.min_price_start}); }
          if(criteria.max_price_start != ""){ this.setState({maxPriceStart: criteria.max_price_start}); }
          if(criteria.amenities.indexOf("Elevator") > -1 || criteria.amenities.indexOf("elevator") > -1){ this.setState({elevator: true}); }else{ this.setState({elevator: false}); }
          if(criteria.amenities.indexOf("Doorman") > -1 || criteria.amenities.indexOf("doorman") > -1){ this.setState({doorman: true}); }else{ this.setState({doorman: false}); }
          if(criteria.amenities.indexOf("Laundry") > -1 || criteria.amenities.indexOf("laundry") > -1){ this.setState({laundry: true}); }else{ this.setState({laundry: false}); }
          if(criteria.amenities.indexOf("Pets") > -1 || criteria.amenities.indexOf("pets") > -1){ this.setState({pets: true}); }else{ this.setState({pets: false}); }
          if(criteria.amenities.indexOf("Fireplace") > -1 || criteria.amenities.indexOf("fireplace") > -1){ this.setState({fireplace: true}); }else{ this.setState({fireplace: false}); }
          if(criteria.amenities.indexOf("Pool") > -1 || criteria.amenities.indexOf("pool") > -1){ this.setState({pool: true}); }else{ this.setState({pool: false}); }
          if(criteria.amenities.indexOf("Garage") > -1 || criteria.amenities.indexOf("garage") > -1){ this.setState({garage: true}); }else{ this.setState({garage: false}); }
          if(criteria.amenities.indexOf("Healthclub") > -1 || criteria.amenities.indexOf("healthclub") > -1){ this.setState({healthclub: true}); }else{ this.setState({healthclub: false}); }
          if(criteria.amenities.indexOf("Outdoor") > -1 || criteria.amenities.indexOf("outdoor") > -1){ this.setState({outdoor: true}); }else{ this.setState({outdoor: false}); }
          if(criteria.amenities.indexOf("Wheelchair") > -1 || criteria.amenities.indexOf("wheelchair") > -1){ this.setState({handicap: true}); }else{ this.setState({handicap: false}); }
          if(criteria.amenities.indexOf("Prewar") > -1 || criteria.amenities.indexOf("prewar") > -1){ this.setState({prewar: true}); }else{ this.setState({prewar: false}); }
          if(criteria.amenities.indexOf("Timeshare") > -1 || criteria.amenities.indexOf("timeshare") > -1){ this.setState({timeshare: true}); }else{ this.setState({timeshare: false}); }
          if(criteria.amenities.indexOf("Newconstruction") > -1 || criteria.amenities.indexOf("newconstruction") > -1){ this.setState({newconstruction: true}); }else{ this.setState({newconstruction: false}); }
          if(criteria.neighborhoods.length == 7){ this.setState({n_all: true}); }else{ this.setState({n_all: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("North") > -1){ console.log("true"); this.setState({n_fu: true}); }else{ console.log("false"); this.setState({n_fu: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Westside") > -1){ this.setState({n_uws: true}); }else{ this.setState({n_uws: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Eastside") > -1){ this.setState({n_ues: true}); }else{ this.setState({n_ues: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Chelsea") > -1){ this.setState({n_mw: true}); }else{ this.setState({n_mw: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("SMG") > -1){ this.setState({n_me: true}); }else{ this.setState({n_me: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Village") > -1){ this.setState({n_v: true}); }else{ this.setState({n_v: false}); }
          if(criteria.neighborhoods.length < 7 && criteria.neighborhoods.indexOf("Lower") > -1){ this.setState({n_d: true}); }else{ this.setState({n_d: false}); }
          if(criteria.prop_type.indexOf("Coop") > -1 || criteria.prop_type.indexOf("coop") > -1){ this.setState({coop: true}); }else{ this.setState({coop: false}); }
          if(criteria.prop_type.indexOf("Condo") > -1 || criteria.prop_type.indexOf("condo") > -1){ this.setState({condo: true}); }else{ this.setState({condo: false}); }
          if(criteria.prop_type.indexOf("House/Townhouse") > -1 || criteria.prop_type.indexOf("house/townhouse") > -1){ this.setState({house: true}); }else{ this.setState({house: false}); }
          if(criteria.prop_type.indexOf("Condop") > -1 || criteria.prop_type.indexOf("condop") > -1){ this.setState({condop: true}); }else{ this.setState({condop: false}); }
  
          $(".editFormula #price").slider('values',0,criteria.min_price_start);
          $(".editFormula #price").slider('values',1,criteria.max_price_start);
          $(".editFormula #price").slider('refresh');
          $(".editFormula #bedrooms_slider").find("span").html(criteria.bedrooms);
          $(".editFormula #bedrooms_slider").slider('value',criteria.bedrooms);
          $(".editFormula #bedrooms_slider" ).slider('refresh');
          $(".editFormula #location_grade").slider('value',criteria.location_grade);
          $(".editFormula #location_grade").slider('refresh');
          $(".editFormula #building_grade").slider('value',criteria.building_grade);
          $(".editFormula #building_grade").slider('refresh');
          $(".editFormula #views_grade").slider('value',criteria.view_grade);
          $(".editFormula #views_grade").slider('refresh');
          if(size_grades[criteria.bedroom_area] !== 'XL'){ $(".editFormula #bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]); }else{ $(".editFormula #bedroom_grade").find("span").html(size_grades[criteria.bedroom_area]).addClass("smaller");}
          $(".editFormula #bedroom_grade").slider('value',criteria.bedroom_area);
          $(".editFormula #bedroom_grade").slider('refresh');
          if(size_grades[criteria.living_area] !== 'XL'){ $(".editFormula #living_grade").find("span").html(size_grades[criteria.living_area]); }else{ $(".editFormula #living_grade").find("span").html(size_grades[criteria.living_area]).addClass("smaller");}
          $(".editFormula #living_grade").slider('value',criteria.living_area);
          $(".editFormula #living_grade").slider('refresh');
        }
      }.bind(this),
      error: function(){
        console.log("failed");
      }
    });
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  handleMinPriceChange: function(event){
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    this.setState({minPrice: price});
  },
  handleMaxPriceChange: function(event){
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    this.setState({maxPrice: price});
  },
  checkMinPrice: function(event){
    setTimeout(function(){
      var price = this.state.minPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }

      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
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
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });

        if(price < 100000){
          this.setState({minPrice: 100000});
          $(".editFormula #price").slider('values',0,1);
          $(".editFormula #price").slider('refresh');
        }
        else{
          this.setState({minPrice: prices[shift]});
          shift++;
          $(".editFormula #price").slider('values',0,shift);
          $(".editFormula #price").slider('refresh');
        }
      }
      else{
        index++;
        $(".editFormula #price").slider('values',0,index);
          $(".editFormula #price").slider('refresh');
      }
    }.bind(this), 1000);
  },
  checkMaxPrice: function(event){
    setTimeout(function(){
      var price = this.state.maxPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }

      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
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
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });

        if(price > 99000000){
          this.setState({maxPrice: 99000000});
          $(".editFormula #price").slider('values',1,32);
          $(".editFormula #price").slider('refresh');
        }
        else{
          this.setState({maxPrice: prices[shift]});
          shift++;
          $(".editFormula #price").slider('values',1,shift);
          $(".editFormula #price").slider('refresh');
        }
      }
      else{
        index++;
        $(".editFormula #price").slider('values',1,index);
        $(".editFormula #price").slider('refresh');
      }
    }.bind(this), 1000);
  },
  handleAmenityChange: function(name, event){
    if(name == "elevator"){ this.setState({elevator: !this.state.elevator}); }
    else if(name == "doorman"){ this.setState({doorman: !this.state.doorman}); }
    else if(name == "laundry"){ this.setState({laundry: !this.state.laundry}); }
    else if(name == "pets"){ this.setState({pets: !this.state.pets}); }
    else if(name == "fireplace"){ this.setState({fireplace: !this.state.fireplace}); }
    else if(name == "pool"){ this.setState({pool: !this.state.pool}); }
    else if(name == "garage"){ this.setState({garage: !this.state.garage}); }
    else if(name == "healthclub"){ this.setState({healthclub: !this.state.healthclub}); }
    else if(name == "outdoor"){ this.setState({outdoor: !this.state.outdoor}); }
    else if(name == "handicap"){ this.setState({handicap: !this.state.handicap}); }
    else if(name == "prewar"){ this.setState({prewar: !this.state.prewar}); }
    else if(name == "timeshare"){ this.setState({timeshare: !this.state.timeshare}); }
    else if(name == "newconstruction"){ this.setState({newconstruction: !this.state.newconstruction}); }
    else{ /* Do nothing */ }
  },
  handleNeighborhoodChange: function(name, event){
    if(name == "n_all"){
      this.setState({n_all: !this.state.n_all});
      this.setState({n_fu: false});
      this.setState({n_uws: false});
      this.setState({n_ues: false});
      this.setState({n_mw: false});
      this.setState({n_me: false});
      this.setState({n_v: false});
      this.setState({n_d: false});
    }
    else if(name == "n_fu"){
      this.setState({n_fu: !this.state.n_fu});
      this.setState({n_all: false});
    }
    else if(name == "n_uws"){
      this.setState({n_uws: !this.state.n_uws});
      this.setState({n_all: false});
    }
    else if(name == "n_ues"){
      this.setState({n_ues: !this.state.n_ues});
      this.setState({n_all: false});
    }
    else if(name == "n_mw"){
      this.setState({n_mw: !this.state.n_mw});
      this.setState({n_all: false});
    }
    else if(name == "n_me"){
      this.setState({n_me: !this.state.n_me});
      this.setState({n_all: false});
    }
    else if(name == "n_v"){
      this.setState({n_v: !this.state.n_v});
      this.setState({n_all: false});
    }
    else if(name == "n_d"){
      this.setState({n_d: !this.state.n_d});
      this.setState({n_all: false});
    }
    else{
      // Do nothing
    }
  },
  handlePropertyChange: function(name, event){
    if(name == "coop"){ this.setState({coop: !this.state.coop}); }
    else if(name == "condo"){ this.setState({condo: !this.state.condo}); }
    else if(name == "house"){ this.setState({house: !this.state.house}); }
    else if(name == "condop"){ this.setState({condop: !this.state.condop}); }
    else{ /* Do nothing */ }
  },
  bedroomText: function(){
    if(this.state.bedrooms == 0){ return(<span>Studio</span>); }
    else if(this.state.bedrooms == 1){ return(<span>{this.state.bedrooms} bedroom</span>); }
    else{ return(<span>{this.state.bedrooms} bedrooms</span>); }
  },
  minPriceText: function(){
    if(this.state.minPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.minPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.minPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.minPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.minPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.minPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.minPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.minPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.minPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.minPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.minPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.minPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.minPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.minPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.minPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.minPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.minPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.minPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.minPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.minPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.minPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.minPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.minPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.minPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.minPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.minPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.minPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.minPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.minPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.minPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.minPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.minPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  maxPriceText: function(){
    if(this.state.maxPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.maxPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.maxPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.maxPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.maxPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.maxPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.maxPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.maxPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.maxPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.maxPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.maxPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.maxPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.maxPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.maxPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.maxPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.maxPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.maxPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.maxPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.maxPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.maxPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.maxPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.maxPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.maxPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.maxPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.maxPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.maxPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.maxPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.maxPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.maxPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.maxPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.maxPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.maxPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  locationText: function(){
    if(this.state.location == 1){ return(<div><p id="y25213-6">All locations</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 2){ return(<div><p id="y25213-6">New developing market.</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 3){ return(<div><p id="y25213-6">Emerging residential area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 4){ return(<div><p id="y25213-6">Active commercial street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 5){ return(<div><p id="y25213-6">Active commercial street / Residential Area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 6){ return(<div><p id="y25213-6">Quiet residential street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 7){ return(<div><p id="y25213-6">Residential area / neighborhood amenities</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 8){ return(<div><p id="y25213-6">Residential area / near local park or river</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 9){ return(<div><p id="y25213-6">Residential area cose to major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 10){ return(<div><p id="y25213-6">Internationally renown / near major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  buildingText: function(){
    if(this.state.building == 1){ return(<div><p id="y25213-14">All buildings</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 2){ return(<div><p id="y25213-14">Walkup in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 3){ return(<div><p id="y25213-14">Walkup in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 4){ return(<div><p id="y25213-14">Elevator building in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 5){ return(<div><p id="y25213-14">Elevator building in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 6){ return(<div><p id="y25213-14">Doorman building &mdash;no amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 7){ return(<div><p id="y25213-14">Doorman building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 8){ return(<div><p id="y25213-14">Full service building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 9){ return(<div><p id="y25213-14">Locally renowned building or new construction with full services</p></div>); }
    else if(this.state.building == 10){ return(<div><p id="y25213-14">International renown</p><p id="y25213-15">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  viewText: function(){
    if(this.state.views == 1){ return(<div><p id="y25213-22">All properties</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 2){ return(<div><p id="y25213-22">Indirect light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 3){ return(<div><p id="y25213-22">Interior courtyard or area with moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 4){ return(<div><p id="y25213-22">Interior courtyard or area without view but bright</p></div>); }
    else if(this.state.views == 5){ return(<div><p id="y25213-22">Street view or interior garden, moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 6){ return(<div><p id="y25213-22">Street view or interior garden, bright</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 7){ return(<div><p id="y25213-22">Rooftop views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 8){ return(<div><p id="y25213-22">Cityscape views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 9){ return(<div><p id="y25213-22">Cityscape and river or park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 10){ return(<div><p id="y25213-22">Cityscape and Central Park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  bedroomAreaText: function(){
    if(this.state.bedroomArea == 1){ return(<div><p id="y25213-30">Any bedroom size is okay</p><p id="y25213-31">&nbsp;</p></div>); }
    else if(this.state.bedroomArea == 2){ return(<div><p id="y25213-30">A medium master bedroom or larger: at least 13 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 3){ return(<div><p id="y25213-30">A large master bedroom or larger: at least 16 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 4){ return(<div><p id="y25213-30">An extra-large master bedroom: at least 19 ft by 11 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  livingAreaText: function(){
    if(this.state.livingArea == 1){ return(<div><p id="y25213-38">Any living room size is okay</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.livingArea == 2){ return(<div><p id="y25213-38">A medium-sized living room or larger: at least 18 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 3){ return(<div><p id="y25213-38">A large-sized living room or larger: at least 22 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 4){ return(<div><p id="y25213-38">A extra-large living room or larger: at least 27 ft by 12 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  minPriceInput: function(){
    var val = this.state.minPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  maxPriceInput: function(){
    var val = this.state.maxPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  getPrices: function(){
    var prices = [{label:"100,000", value:"100000"},{label:"200,000", value:"200000"}, {label:"300,000", value:"300000"}, {label:"400,000", value:"400000"},
      {label:"500,000", value:"500000"}, {label:"600,000", value:"600000"}, {label:"700,000", value:"700000"}, {label:"800,000", value:"800000"},
      {label:"900,000", value:"900000"}, {label:"1,000,000", value:"1000000"}, {label:"1,100,000", value:"1100000"}, {label:"1,200,000", value:"1200000"},
      {label:"1,300,000", value:"1300000"}, {label:"1,400,000", value:"1400000"},{label:"1,500,000", value:"1500000"}, {label:"1,600,000", value:"1600000"},
      {label:"1,700,000", value:"1700000"}, {label:"1,800,000", value:"1800000"}, {label:"1,900,000", value:"1900000"}, {label:"2,000,000", value:"2000000"},
      {label:"2,250,000", value:"2250000"}, {label:"2,500,000", value:"2500000"}, {label:"2,750,000", value:"2750000"}, {label:"3,000,000", value:"3000000"},
      {label:"4,000,000", value:"4000000"}, {label:"6,000,000", value:"6000000"}, {label:"8,000,000", value:"8000000"}, {label:"12,000,000", value:"12000000"},
      {label:"25,000,000", value:"25000000"}, {label:"50,000,000", value:"50000000"}, {label:"99,000,000", value:"99000000"}];

    var pricesCompare = ["100000","200000", "300000", "400000", "500000", "600000", "700000", "800000", "900000", "1000000", "1100000", "1200000",
      "1300000", "1400000", "1500000", "1600000", "1700000", "1800000", "1900000", "2000000", "2250000", "2500000", "2750000", "3000000", "3500000",
      "4000000", "6000000", "8000000", "12000000", "25000000", "50000000", "99000000"];

    $( ".editFormula .priceInput" ).autocomplete({
      source: prices,
      select: function(e, ui){
        if(e.target.name == "minPrice"){
          this.setState({minPrice: ui.item.value});
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $(".editFormula #price").slider('values',0,index);
        }
        if(e.target.name == "maxPrice"){
          this.setState({maxPrice: ui.item.value});
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $(".editFormula #price").slider('values',1,index);
        }
      }.bind(this)
    });
  },
  closeDialog: function(){
    $dialog.dialog('close');
  },
  saveChanges: function(){
    var email = this.state.buyer_email;
    var searchName = this.state.searchName;
    var oldName = this.state.searchName;
    var min_price = this.state.minPrice;
    var max_price = this.state.maxPrice;
    var bedrooms = this.state.bedrooms;
    var location_grade = this.state.location;
    var building_grade = this.state.building;
    var views_grade = this.state.views;
    var bedroom_area = this.state.bedroomArea;
    var living_area = this.state.livingArea;
    var min_floor = "";
    var neighborhoods = [];
    var prop_type = [];
    var amen = [];

    if(this.state.n_all == true){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"];}
    if(this.state.n_fu == true){ neighborhoods.push("North");}
    if(this.state.n_uws == true){ neighborhoods.push("Westside");}
    if(this.state.n_ues == true){ neighborhoods.push("Eastside");}
    if(this.state.n_mw == true){ neighborhoods.push("Chelsea");}
    if(this.state.n_me == true){ neighborhoods.push("SMG");}
    if(this.state.n_v == true){ neighborhoods.push("Village");}
    if(this.state.n_d == true){ neighborhoods.push("Lower");}

    if(neighborhoods.length == 0){ neighborhoods = ["North", "Westside", "Eastside", "Chelsea", "SMG", "Village", "Lower"]; }

    if(this.state.coop == true){ prop_type.push("1");}
    if(this.state.condo == true){ prop_type.push("2");}
    if(this.state.house == true){ prop_type.push("4");}
    if(this.state.condop == true){ prop_type.push("5");}

    if(prop_type.length == 0){ prop_type = ["1", "2", "4", "5"]; }

    if(this.state.elevator == true){ amen.push("Elevator");}
    if(this.state.doorman == true){ amen.push("Doorman");}
    if(this.state.laundry == true){ amen.push("Laundry");}
    if(this.state.pets == true){ amen.push("Pets");}
    if(this.state.fireplace == true){ amen.push("Fireplace");}
    if(this.state.pool == true){ amen.push("Pool");}
    if(this.state.garage == true){ amen.push("Garage");}
    if(this.state.healthclub == true){ amen.push("Healthclub");}
    if(this.state.outdoor == true){ amen.push("Outdoor");}
    if(this.state.handicap == true){ amen.push("Wheelchair");}
    if(this.state.prewar == true){ amen.push("Prewar");}
    if(this.state.timeshare == true){ amen.push("Timeshare");}
    if(this.state.newconstruction == true){ amen.push("Newconstruction");}

    if(Number(min_price) >= Number(max_price)){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox priceRangePopup',
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
      $('#ajax-box2').load('/controllers/messages.php #priceRange',function(){
        $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
      });
    }
    else{
      $.ajax({
        type: "POST",
        url: "/controllers/save-criteria.php",
        data: {"email":email, "oldname":oldName, "name":searchName, "location":location_grade, "building":building_grade, "view":views_grade, "floor":min_floor, "bedrooms":bedrooms, "min_price":min_price, "max_price":max_price, "living_area":living_area, "bedroom_area":bedroom_area, "neighborhoods" : neighborhoods, "prop_type":prop_type, "amenities":amen},
        success: function(data){
          $.get("/controllers/ajax.php", {
            editFormulaEmail: 'true', //Call the PHP function
            email: email,
            success: function(result){
            console.log("email sent");
            }
          });

          var ajaxStop = 0;
          $(document).ajaxStop(function() {
            if(ajaxStop == 0){
              ajaxStop++;
              {this.props.closeDialog()}
            }
          }.bind(this));
        }.bind(this),
        error: function() {
          console.log("failed");
        }
      });
    }
  },
  cancel: function(){
    {this.props.closeDialog()}
  },
  updateData: function(){
    if(this.props.searchName != this.state.name){
      this.setState({name: this.props.searchName});
      this.getCriteria();
    }
  },
  render: function(){
    return(
      <div className="clearfix" id="editPage">
        <i className="fa fa-times close-edit-buyer-formula-popup" onClick={this.cancel} title="close"></i>
        {this.updateData()}
        <div className="clearfix grpelem" id="y25246-3">
          <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="y25241-3">
         <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="py2678">
          <div className="clearfix grpelem" id="py25217-6">
            <div className="clearfix colelem" id="y25217-6">
              <p id="y25217-4"><span id="y25217">Search name: </span><span id="y25217-2">{this.props.searchName}&nbsp; </span><span id="y25217-3"><a style={{cursor: "pointer"}} onClick = {this.cancel}>Cancel</a></span></p>
            </div>
            <div className="clearfix colelem" id="py25231-53">
              <div className="clearfix grpelem" id="y25231-53">
                <p id="y25231-2">Minimum Bedroom</p>
                <p id="y25231-3">&nbsp;</p>
                <p id="y25231-4">&nbsp;</p>
                <p id="y25231-6">{this.bedroomText()}</p>
                <p id="y25231-7">&nbsp;</p>
                <p id="y25231-8">&nbsp;</p>
                <p id="y25231-10">Price Range</p>
                <p id="y25231-11">&nbsp;</p>
                <p id="y25231-12">&nbsp;</p>
                <p id="y25231-14">$ {this.minPriceText()} to {this.maxPriceText()}</p>
                <p id="y25231-16">Or, fill in a range: $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to $&nbsp;&nbsp;</p>
                <p id="y25231-17">&nbsp;</p>
                <p id="y25231-18">&nbsp;</p>
                <p id="y25231-20">Neighborhoods</p>
                <p id="y25231-23"><span id="y25231-21">{this.state.n_all ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}} onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> }</span><span id="y25231-22">&nbsp;&nbsp; All of Manhattan</span></p>
                <p id="y25231-26"><span id="y25231-24">{this.state.n_fu ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> }</span><span id="y25231-25">&nbsp;&nbsp; Far Uptown</span></p>
                <p id="y25231-29"><span id="y25231-27">{this.state.n_uws ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> }</span><span id="y25231-28">&nbsp;&nbsp; Upper West Side</span></p>
                <p id="y25231-32"><span id="y25231-30">{this.state.n_ues ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> }</span><span id="y25231-31">&nbsp;&nbsp; Upper East Side</span></p>
                <p id="y25231-36">&nbsp;</p>
                <p id="y25231-37">&nbsp;</p>
                <p id="y25231-39">Property Type</p>
                <p id="y25231-42"><span id="y25231-40">{this.state.coop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> }</span><span id="y25231-41">&nbsp;&nbsp; Co-op</span></p>
                <p id="y25231-45"><span id="y25231-43">{this.state.condo ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> }</span><span id="y25231-44">&nbsp;&nbsp; Condo</span></p>
                <p id="y25231-48"><span id="y25231-46">{this.state.house ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> }</span><span id="y25231-47">&nbsp;&nbsp; House/Townhouse</span></p>
                <p id="y25231-51"><span id="y25231-49">{this.state.condop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> }</span><span id="y25231-50">&nbsp;&nbsp; Condop</span></p>
              </div>
              <div className="clearfix grpelem" id="y25224-14">
                <p id="y25224-3"><span id="y25224">{this.state.n_mw ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> }</span><span id="y25224-2">&nbsp;&nbsp; Midtown West</span></p>
                <p id="y25224-6"><span id="y25224-4">{this.state.n_me ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> }</span><span id="y25224-5">&nbsp;&nbsp; Midtown East</span></p>
                <p id="y25224-9"><span id="y25224-7">{this.state.n_v ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> }</span><span id="y25224-8">&nbsp;&nbsp; East/West Village</span></p>
                <p id="y25224-12"><span id="y25224-10">{this.state.n_d ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> }</span><span id="y25224-11">&nbsp;&nbsp; Downtown</span></p>
              </div>
              <div id="bedrooms_slider"></div>
              <div id="bedroomSliderText"><span className="leftNum">0</span><span className="rightNum">8</span></div>
              <div className="clip_frame grpelem" id="y25220">
                <img className="block" id="y25220_img" src="/images/slider_purple_short.png" alt="" width="455" height="37"/>
              </div>
              <div id="price"></div>
              <div id="priceSliderText"><span className="leftNum">0</span><span className="rightNum">99</span></div>
              <div className="clip_frame grpelem" id="y25211">
                <img className="block" id="y25211_img" src="/images/slider_blue_short.png" alt="" width="455" height="37"/>
              </div>
              <div className="clearfix grpelem" id="y25385">
                <div className="grpelem" id="y25398"><input type="text" id="minPrice" className="priceInput" name="minPrice" value={this.state.minPrice} onBlur={this.checkMinPrice} onChange={this.handleMinPriceChange} onFocus={this.getPrices}/></div>
                <div className="grpelem" id="y25386"><input type="text" id="maxPrice" className="priceInput" name="maxPrice" value={this.state.maxPrice} onBlur={this.checkMaxPrice} onChange={this.handleMaxPriceChange} onFocus={this.getPrices}/></div>
              </div>
            </div>
          </div>
          <div className="grpelem" id="y25216"></div>
          <div className="clearfix grpelem" id="py25213-56">
            <div className="clearfix grpelem" id="y25213-56">
              <p id="y25213-2">Minimum Location Grade</p>
              <p id="y25213-3">&nbsp;</p>
              <p id="y25213-4">&nbsp;</p>
              {this.locationText()}
              <p id="y25213-8">&nbsp;</p>
              <p id="y25213-10">Minimum Building Grade</p>
              <p id="y25213-11">&nbsp;</p>
              <p id="y25213-12">&nbsp;</p>
              {this.buildingText()}
              <p id="y25213-16">&nbsp;</p>
              <p id="y25213-18">Minimum view Grade</p>
              <p id="y25213-19">&nbsp;</p>
              <p id="y25213-20">&nbsp;</p>
              {this.viewText()}
              <p id="y25213-24">&nbsp;</p>
              <p id="y25213-26">Master Bedroom</p>
              <p id="y25213-27">&nbsp;</p>
              <p id="y25213-28">&nbsp;</p>
              {this.bedroomAreaText()}
              <p id="y25213-32">&nbsp;</p>
              <p id="y25213-34">Living Room</p>
              <p id="y25213-35">&nbsp;</p>
              <p id="y25213-36">&nbsp;</p>
              {this.livingAreaText()}
              <p id="y25213-40">&nbsp;</p>
              <p id="y25213-44"><span id="y25213-41">Amenities</span><span id="y25213-42">&nbsp; </span><span id="y25213-43">Click icons to select amenities</span></p>
              <p id="y25213-45">&nbsp;</p>
              <p id="y25213-46">&nbsp;</p>
              <p id="y25213-47">&nbsp;</p>
              <p id="y25213-48">&nbsp;</p>
              <p id="y25213-49">&nbsp;</p>
              <p id="y25213-50">&nbsp;</p>
              <p id="y25213-51">&nbsp;</p>
              <p id="y25213-54"><span id="y25213-52" onClick={this.saveChanges} style={{cursor: "pointer"}}>Save changes and exit </span><span id="y25213-53"><i className="fa fa-chevron-right"></i></span></p>
            </div>
            <div id="location_grade"></div>
            <div className="clip_frame grpelem" id="y25229">
              <img className="block" id="y25229_img" src="/images/locationslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="building_grade"></div>
            <div className="clip_frame grpelem" id="y25214">
              <img className="block" id="y25214_img" src="/images/buildingslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="views_grade"></div>
            <div className="clip_frame grpelem" id="y25227">
              <img className="block" id="y25227_img" src="/images/viewsslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="bedroom_grade"></div>
            <div id="bedroomAreaSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25222">
              <img className="block" id="y25222_img" src="/images/slider_orange_short.png" alt="" width="455" height="37"/>
            </div>
            <div id="living_grade"></div>
            <div id="livingRoomSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25225">
              <img className="block" id="y25225_img" src="/images/slider_green_short.png" alt="" width="455" height="37"/>
            </div>
            <div className="clearfix grpelem" id="y25374">
              <div className="clip_frame grpelem" id="y25208">
                <table id="amenities">
                  <tbody>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'elevator')}>{this.state.elevator ? <img className="amenity" src="/images/amenities/elevatorb.png" alt="elevator" width="30" height="30"/> : <img className="amenity" src="/images/amenities/elevator.png" alt="elevator" width="30" height="30" />}<div className="y25210-11">Elevator</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'doorman')}>{this.state.doorman ? <img className="amenity" src="/images/amenities/doormanb.png" alt="doorman" width="30" height="30"/> : <img className="amenity" src="/images/amenities/doorman.png" alt="doorman" width="30" height="30" />}<div className="y25210-11">Doorman</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'laundry')}>{this.state.laundry ? <img className="amenity" src="/images/amenities/laundryb.png" alt="laundry" width="30" height="30"/> : <img className="amenity" src="/images/amenities/laundry.png" alt="laundry" width="30" height="30" />}<div className="y25210-11">Laundry</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'pets')}>{this.state.pets ? <img className="amenity" src="/images/amenities/petsb.png" alt="pets" width="30" height="30"/> : <img className="amenity" src="/images/amenities/pets.png" alt="pets" width="30" height="30"/>}<div className="y25210-11">Pets</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'fireplace')}>{this.state.fireplace ? <img className="amenity" src="/images/amenities/fireplaceb.png" alt="fireplace" width="30" height="30"/> : <img className="amenity" src="/images/amenities/fireplace.png" alt="fireplace" width="30" height="30"/>}<div className="y25210-11">Fireplace</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'prewar')}>{this.state.prewar ? <img className="amenity" src="/images/amenities/prewar2b.png" alt="prewar" width="30" height="30" /> : <img className="amenity" src="/images/amenities/prewar2.png" alt="prewar" width="30" height="30" />}<div className="y25210-11">Prewar</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'timeshare')}>{this.state.timeshare ? <img className="amenity" src="/images/amenities/timeshareb.png" alt="timeshare" width="30" height="30"/> : <img className="amenity" src="/images/amenities/timeshare.png" alt="timeshare" width="30" height="30" />}<div className="y25210-11">Time shares</div></td>
                    </tr>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'pool')}>{this.state.pool ? <img className="amenity" src="/images/amenities/poolb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/pool.png" alt="pool" width="30" height="30" />}<div className="y25210-11">Pool</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'garage')}>{this.state.garage ? <img className="amenity" src="/images/amenities/garageb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/garage.png" alt="garage" width="30" height="30" />}<div className="y25210-11">Garage</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'healthclub')}>{this.state.healthclub ? <img className="amenity" src="/images/amenities/healthclubb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/healthclub.png" alt="healthclub" width="30" height="30"/>}<div className="y25210-11">Health club</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'outdoor')}>{this.state.outdoor ? <img className="amenity" src="/images/amenities/outdoorb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/outdoor.png" alt="outdoor" width="30" height="30" />}<div className="y25210-11">Outdoor space</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'handicap')}>{this.state.handicap ? <img className="amenity" src="/images/amenities/wheelchairb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/wheelchair.png" alt="wheelchair" width="30" height="30" />}<div className="y25210-11">Handicap accessible</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'newconstruction')}>{this.state.newconstruction ? <img className="amenity" src="/images/amenities/newconstructionb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/newconstruction.png" alt="newconstruction" width="30" height="30" />}<div className="y25210-11">New construction</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div id="amenitiesSeparator"></div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

var EditCriteria = React.createClass({
  getInitialState: function() {
    return{
      bedrooms: $("#bedrooms_slider" ).slider("value"),
      location: $('#location_grade').slider("value"),
      building: $('#building_grade').slider("value"),
      views: $('#views_grade').slider("value"),
      bedroomArea: $('#bedroom_grade').slider("value"),
      livingArea: $('#living_grade').slider("value"),
      minPrice: $('#min_price').attr('data-price'),
      maxPrice: $('#max_price').attr('data-price'),
      minPriceStart: $('#price').slider('values',0),
      maxPriceStart: $('#price').slider('values',1),
      elevator: false,
      doorman: false,
      laundry: false,
      pets: false,
      fireplace: false,
      pool: false,
      garage: false,
      healthclub: false,
      outdoor: false,
      handicap: false,
      prewar: false,
      timeshare: false,
      newconstruction: false,
      n_all: false,
      n_fu: false,
      n_uws: false,
      n_ues: false,
      n_mw: false,
      n_me: false,
      n_v: false,
      n_d: false,
      coop: $('input[value=1]').attr("checked"),
      condo: $('input[value=2]').attr("checked"),
      house: $('input[value=4]').attr("checked"),
      condop: $('input[value=5]').attr("checked"),
      initial: this.props.initial
    };
  },
  componentDidMount: function(){

    var size_grades = ['','S','M','L','XL'];

    var prices = ['',100000,200000, 300000, 400000, 500000, 600000,
    700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
    1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
    2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
    50000000, 99000000];

    this.getCriteria();

    $( ".editFormula #bedrooms_slider" ).slider({
      range: 'max',
      min: 0,
      max: 8,
      value: this.state.bedrooms,
      slide: function( event, ui ) { /* Function to execute when sliding the bedrooms slider    */
        var bedrooms = ui.value;
        this.setState({bedrooms: bedrooms});
        var minBedroom = bedrooms;
        ui.sliderText =	'<span>'+ui.value+'</span>';
        $('.editFormula #bedrooms_slider .ui-slider-handle').html(ui.sliderText); //* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedrooms;
        $('.editFormula #bedrooms_slider .ui-slider-handle').html('<span>'+ui.value+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #location_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.location,
      slide: function(event, ui) { /* Function to execute when sliding the location slider */
        var locationGrade = ui.value;
        this.setState({location: locationGrade});
        ui.sliderText =	"<span><img src='/images/blue-tick.png'/></span>";
        $('.editFormula #location_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /*Function to execute when initializing slider */
        ui.value = this.state.location;
        $('.editFormula #location_grade .ui-slider-handle').html("<span><img src='/images/blue-tick.png'/></span>");
      }.bind(this)
    }).addTouch();

    $(".editFormula #building_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.building,
      slide: function(event, ui) { /* Function to execute when sliding the building slider */
        var buildingGrade = ui.value;
        this.setState({building: buildingGrade});
        ui.sliderText =	"<span><img src='/images/purple-tick.png'/></span>";
        $('.editFormula #building_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.building;
        $('.editFormula #building_grade .ui-slider-handle').html("<span><img src='/images/purple-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #views_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 10,
      value: this.state.views,
      slide: function(event, ui) { /* Function to execute when sliding the views slider */
        var viewsGrade = ui.value;
        this.setState({views: viewsGrade});
        ui.sliderText =	"<span><img src='/images/pink-tick.png'/></span>";
        $('.editFormula #views_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.views;
        $('.editFormula #views_grade .ui-slider-handle').html("<span><img src='/images/pink-tick.png'/></span>");
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #bedroom_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.bedroomArea,
      slide: function(event, ui) { /* Function to execute when sliding the bedroom slider */
        var bedroomGrade = ui.value;
        this.setState({bedroomArea: bedroomGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('.editFormula #bedroom_grade .ui-slider-handle').html(ui.sliderText); /* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        ui.value = this.state.bedroomArea;
        $('.editFormula #bedroom_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

    $(".editFormula #living_grade").slider({
      animate: true,
      range: 'max',
      min: 1,
      max: 4,
      value: this.state.livingArea,
      slide: function(event, ui) { /* Function to execute when sliding the living room slider */
        var livingGrade = ui.value;
        this.setState({livingArea: livingGrade});
        if(size_grades[ui.value] !== 'XL'){ ui.sliderText =	'<span>'+size_grades[ui.value]+'</span>'; }
        else { ui.sliderText =	'<span class="smaller">'+size_grades[ui.value]+'</span>'; }
        $('.editFormula #living_grade .ui-slider-handle').html(ui.sliderText);/* show the new grade in the slider handle */
      }.bind(this),
      create: function( event, ui ) {
        /* Function to execute when initializing slider */
        ui.value = this.state.livingArea;
        $('.editFormula #living_grade .ui-slider-handle').html('<span>'+size_grades[ui.value]+'</span>');
        return false;
      }.bind(this)
    }).addTouch();

     $( ".editFormula #price" ).slider({
      animate: true,
      range: 'true',
      min: 1,
      max: 32,
      values: [this.state.minPriceStart, this.state.maxPriceStart ],
      slide: function( event, ui ) { /* Function to execute when sliding the price slider */
        if ( ( ui.values[ 0 ] + 0 ) >= ui.values[ 1 ] ) { return false; } //Make sure both arrows have at least a .1 gap
        var min_value = ui.values[0], max_value = ui.values[1];
        this.setState({minPrice: prices[min_value]});
        this.setState({maxPrice: prices[max_value]});
        this.setState({minPriceStart: min_value});
        this.setState({maxPriceStart: max_value});
      }.bind(this),
      create: function( event, ui ) { /* Function to execute when initializing slider */
        $('.editFormula #price .ui-slider-handle').first().html('<span>&#155;</span');
        $('.editFormula #price .ui-slider-handle').first().attr("id", "min_price_slider");
        $('.editFormula #price .ui-slider-handle').last().html('<span>&#139;</span');
        $('.editFormula #price .ui-slider-handle').last().attr("id", "max_price_slider");
        return false;
      }
    }).addTouch();
  },
  getCriteria: function(){    
    var	neighborhoods = $('input[name="multiselect_neighborhoods"]:checked' ),
    north = $('input[value=North]').attr("checked"),
    westside = $('input[value=Westside]').attr("checked"),
    eastside = $('input[value=Eastside]').attr("checked"),
    chelsea = $('input[value=Chelsea]').attr("checked"),
    smg = $('input[value=SMG]').attr("checked"),
    village = $('input[value=Village]').attr("checked"),
    lower = $('input[value=Lower]').attr("checked");
    
    if (neighborhoods.length == 0 || neighborhoods.length == 7) {
      this.setState({n_all: true});
    }
    else{
      this.setState({n_fu: north});
      this.setState({n_uws: westside});
      this.setState({n_ues: eastside});
      this.setState({n_mw: chelsea});
      this.setState({n_me: smg});
      this.setState({n_v: village});
      this.setState({n_d: lower});
    }
    
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=garage]').hasClass('selected')){ this.setState({garage: true}); }    
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pool]').hasClass('selected')){ this.setState({pool: true}); }    
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=laundry]').hasClass('selected')){ this.setState({laundry: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=doorman]').hasClass('selected')){ this.setState({doorman: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=elevator]').hasClass('selected')){ this.setState({elevator: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=pets]').hasClass('selected')){ this.setState({pets: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=fireplace]').hasClass('selected')){ this.setState({fireplace: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=healthclub]').hasClass('selected')){ this.setState({healthclub: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=prewar]').hasClass('selected')){ this.setState({prewar: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=outdoor]').hasClass('selected')){ this.setState({outdoor: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=wheelchair]').hasClass('selected')){ this.setState({handicap: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=timeshare]').hasClass('selected')){ this.setState({timeshare: true}); }
    if($('.amenitiesmenu.criterion').find('img.amenity_icons[rel=newconstruction]').hasClass('selected')){ this.setState({newconstruction: true}); }    
  },
  handleChange: function (name, event) {
    var change = {};
    change[name] = event.target.value;
    this.setState(change);
  },
  handleMinPriceChange: function(event){
    var prices = ['',100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];
    
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    var index = prices.indexOf(price);
    this.setState({minPrice: price});
    this.setState({minPriceStart: index});
  },
  handleMaxPriceChange: function(event){
    var prices = ['',100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];
    
    var price = event.target.value;
    if(price.length > 0){
      price = price.replace(",", "");
      price = parseInt(price);
    }
    var index = prices.indexOf(price);
    this.setState({maxPrice: price});
    this.setState({maxPriceStart: index});
  },
  checkMinPrice: function(event){
    setTimeout(function(){
      var price = this.state.minPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }

      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
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
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });

        if(price < 100000){
          this.setState({minPrice: 100000});
          $(".editFormula #price").slider('values',0,1);
        }
        else{
          this.setState({minPrice: prices[shift]});
          shift++;
          $(".editFormula #price").slider('values',0,shift);
        }
      }
      else{
        index++;
        $(".editFormula #price").slider('values',0,index);
      }
    }.bind(this), 3000);
  },
  checkMaxPrice: function(event){
    setTimeout(function(){
      var price = this.state.maxPrice;
      var index = -1;
      var shift = 0;
      var prices = [100000, 200000, 300000, 400000, 500000, 600000,
      700000, 800000, 900000, 1000000, 1100000, 1200000, 1300000, 1400000,
      1500000, 1600000, 1700000, 1800000, 1900000, 2000000, 2250000, 2500000,
      2750000, 3000000, 3500000, 4000000, 6000000, 8000000, 12000000, 25000000,
      50000000, 99000000];

      for(var i = 0; i < prices.length; i++){
        if(prices[i] == price){
          index = i;
          break;
        }
        else{
          if(prices[i] < price){ shift = i; }
        }
      }

      if(index < 0){
        $("#ajax-box2").dialog({
          modal: true,
          height: 'auto',
          width: '275px',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage',
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
        $('#ajax-box2').load('/controllers/messages.php #invalidPrice',function(){
          $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
        });

        if(price > 99000000){
          this.setState({maxPrice: 99000000});
          $(".editFormula #price").slider('values',1,32);
        }
        else{
          this.setState({maxPrice: prices[shift]});
          shift++;
          $(".editFormula #price").slider('values',1,shift);
        }
      }
      else{
        index++;
        $(".editFormula #price").slider('values',1,index);
      }
    }.bind(this), 3000);
  },
  handleAmenityChange: function(name, event){
    if(name == "elevator"){ this.setState({elevator: !this.state.elevator}); }
    else if(name == "doorman"){ this.setState({doorman: !this.state.doorman}); }
    else if(name == "laundry"){ this.setState({laundry: !this.state.laundry}); }
    else if(name == "pets"){ this.setState({pets: !this.state.pets}); }
    else if(name == "fireplace"){ this.setState({fireplace: !this.state.fireplace}); }
    else if(name == "pool"){ this.setState({pool: !this.state.pool}); }
    else if(name == "garage"){ this.setState({garage: !this.state.garage}); }
    else if(name == "healthclub"){ this.setState({healthclub: !this.state.healthclub}); }
    else if(name == "outdoor"){ this.setState({outdoor: !this.state.outdoor}); }
    else if(name == "handicap"){ this.setState({handicap: !this.state.handicap}); }
    else if(name == "prewar"){ this.setState({prewar: !this.state.prewar}); }
    else if(name == "timeshare"){ this.setState({timeshare: !this.state.timeshare}); }
    else if(name == "newconstruction"){ this.setState({newconstruction: !this.state.newconstruction}); }
    else{ /* Do nothing */ }
  },
  handleNeighborhoodChange: function(name, event){
    if(name == "n_all"){
      this.setState({n_all: !this.state.n_all});
      this.setState({n_fu: false});
      this.setState({n_uws: false});
      this.setState({n_ues: false});
      this.setState({n_mw: false});
      this.setState({n_me: false});
      this.setState({n_v: false});
      this.setState({n_d: false});
    }
    else if(name == "n_fu"){
      this.setState({n_fu: !this.state.n_fu});
      this.setState({n_all: false});
    }
    else if(name == "n_uws"){
      this.setState({n_uws: !this.state.n_uws});
      this.setState({n_all: false});
    }
    else if(name == "n_ues"){
      this.setState({n_ues: !this.state.n_ues});
      this.setState({n_all: false});
    }
    else if(name == "n_mw"){
      this.setState({n_mw: !this.state.n_mw});
      this.setState({n_all: false});
    }
    else if(name == "n_me"){
      this.setState({n_me: !this.state.n_me});
      this.setState({n_all: false});
    }
    else if(name == "n_v"){
      this.setState({n_v: !this.state.n_v});
      this.setState({n_all: false});
    }
    else if(name == "n_d"){
      this.setState({n_d: !this.state.n_d});
      this.setState({n_all: false});
    }
    else{
      // Do nothing
    }
  },
  handlePropertyChange: function(name, event){
    if(name == "coop"){ this.setState({coop: !this.state.coop}); }
    else if(name == "condo"){ this.setState({condo: !this.state.condo}); }
    else if(name == "house"){ this.setState({house: !this.state.house}); }
    else if(name == "condop"){ this.setState({condop: !this.state.condop}); }
    else{ /* Do nothing */ }
  },
  bedroomText: function(){
    if(this.state.bedrooms == 0){ return(<span>Studio</span>); }
    else if(this.state.bedrooms == 1){ return(<span>{this.state.bedrooms} bedroom</span>); }
    else{ return(<span>{this.state.bedrooms} bedrooms</span>); }
  },
  minPriceText: function(){
    if(this.state.minPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.minPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.minPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.minPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.minPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.minPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.minPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.minPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.minPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.minPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.minPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.minPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.minPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.minPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.minPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.minPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.minPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.minPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.minPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.minPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.minPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.minPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.minPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.minPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.minPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.minPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.minPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.minPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.minPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.minPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.minPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.minPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  maxPriceText: function(){
    if(this.state.maxPrice == 100000){ return(<span>100K</span>); }
    else if(this.state.maxPrice == 200000){ return(<span>200K</span>); }
    else if(this.state.maxPrice == 300000){ return(<span>300K</span>); }
    else if(this.state.maxPrice == 400000){ return(<span>400K</span>); }
    else if(this.state.maxPrice == 500000){ return(<span>500K</span>); }
    else if(this.state.maxPrice == 600000){ return(<span>600K</span>); }
    else if(this.state.maxPrice == 700000){ return(<span>700K</span>); }
    else if(this.state.maxPrice == 800000){ return(<span>800K</span>); }
    else if(this.state.maxPrice == 900000){ return(<span>900K</span>); }
    else if(this.state.maxPrice == 1000000){ return(<span>1M</span>); }
    else if(this.state.maxPrice == 1100000){ return(<span>1.1M</span>); }
    else if(this.state.maxPrice == 1200000){ return(<span>1.2M</span>); }
    else if(this.state.maxPrice == 1300000){ return(<span>1.3M</span>); }
    else if(this.state.maxPrice == 1400000){ return(<span>1.4M</span>); }
    else if(this.state.maxPrice == 1500000){ return(<span>1.5M</span>); }
    else if(this.state.maxPrice == 1600000){ return(<span>1.6M</span>); }
    else if(this.state.maxPrice == 1700000){ return(<span>1.7M</span>); }
    else if(this.state.maxPrice == 1800000){ return(<span>1.8M</span>); }
    else if(this.state.maxPrice == 1900000){ return(<span>1.9M</span>); }
    else if(this.state.maxPrice == 2000000){ return(<span>2M</span>); }
    else if(this.state.maxPrice == 2250000){ return(<span>2.25M</span>); }
    else if(this.state.maxPrice == 2500000){ return(<span>2.5M</span>); }
    else if(this.state.maxPrice == 2750000){ return(<span>2.75M</span>); }
    else if(this.state.maxPrice == 3000000){ return(<span>3M</span>); }
    else if(this.state.maxPrice == 3500000){ return(<span>3.5M</span>); }
    else if(this.state.maxPrice == 4000000){ return(<span>4M</span>); }
    else if(this.state.maxPrice == 6000000){ return(<span>6M</span>); }
    else if(this.state.maxPrice == 8000000){ return(<span>8M</span>); }
    else if(this.state.maxPrice == 12000000){ return(<span>12M</span>); }
    else if(this.state.maxPrice == 25000000){ return(<span>25M</span>); }
    else if(this.state.maxPrice == 50000000){ return(<span>50M</span>); }
    else if(this.state.maxPrice == 99000000){ return(<span>99M</span>); }
    else{ /* Do nothing */ }
  },
  locationText: function(){
    if(this.state.location == 1){ return(<div><p id="y25213-6">All locations</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 2){ return(<div><p id="y25213-6">New developing market.</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 3){ return(<div><p id="y25213-6">Emerging residential area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 4){ return(<div><p id="y25213-6">Active commercial street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 5){ return(<div><p id="y25213-6">Active commercial street / Residential Area</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 6){ return(<div><p id="y25213-6">Quiet residential street</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 7){ return(<div><p id="y25213-6">Residential area / neighborhood amenities</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 8){ return(<div><p id="y25213-6">Residential area / near local park or river</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 9){ return(<div><p id="y25213-6">Residential area cose to major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else if(this.state.location == 10){ return(<div><p id="y25213-6">Internationally renown / near major park</p><p id="y25213-7">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  buildingText: function(){
    if(this.state.building == 1){ return(<div><p id="y25213-14">All buildings</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 2){ return(<div><p id="y25213-14">Walkup in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 3){ return(<div><p id="y25213-14">Walkup in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 4){ return(<div><p id="y25213-14">Elevator building in fair condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 5){ return(<div><p id="y25213-14">Elevator building in good condition</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 6){ return(<div><p id="y25213-14">Doorman building &mdash;no amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 7){ return(<div><p id="y25213-14">Doorman building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 8){ return(<div><p id="y25213-14">Full service building with amenities</p><p id="y25213-15">&nbsp;</p></div>); }
    else if(this.state.building == 9){ return(<div><p id="y25213-14">Locally renowned building or new construction with full services</p></div>); }
    else if(this.state.building == 10){ return(<div><p id="y25213-14">International renown</p><p id="y25213-15">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  viewText: function(){
    if(this.state.views == 1){ return(<div><p id="y25213-22">All properties</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 2){ return(<div><p id="y25213-22">Indirect light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 3){ return(<div><p id="y25213-22">Interior courtyard or area with moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 4){ return(<div><p id="y25213-22">Interior courtyard or area without view but bright</p></div>); }
    else if(this.state.views == 5){ return(<div><p id="y25213-22">Street view or interior garden, moderate light</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 6){ return(<div><p id="y25213-22">Street view or interior garden, bright</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 7){ return(<div><p id="y25213-22">Rooftop views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 8){ return(<div><p id="y25213-22">Cityscape views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 9){ return(<div><p id="y25213-22">Cityscape and river or park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.views == 10){ return(<div><p id="y25213-22">Cityscape and Central Park views</p><p id="y25213-23">&nbsp;</p></div>); }
    else{ /* Do nothing */ }
  },
  bedroomAreaText: function(){
    if(this.state.bedroomArea == 1){ return(<div><p id="y25213-30">Any bedroom size is okay</p><p id="y25213-31">&nbsp;</p></div>); }
    else if(this.state.bedroomArea == 2){ return(<div><p id="y25213-30">A medium master bedroom or larger: at least 13 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 3){ return(<div><p id="y25213-30">A large master bedroom or larger: at least 16 ft by 11 ft</p></div>); }
    else if(this.state.bedroomArea == 4){ return(<div><p id="y25213-30">An extra-large master bedroom: at least 19 ft by 11 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  livingAreaText: function(){
    if(this.state.livingArea == 1){ return(<div><p id="y25213-38">Any living room size is okay</p><p id="y25213-23">&nbsp;</p></div>); }
    else if(this.state.livingArea == 2){ return(<div><p id="y25213-38">A medium-sized living room or larger: at least 18 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 3){ return(<div><p id="y25213-38">A large-sized living room or larger: at least 22 ft by 12 ft</p></div>); }
    else if(this.state.livingArea == 4){ return(<div><p id="y25213-38">A extra-large living room or larger: at least 27 ft by 12 ft</p></div>); }
    else{ /* Do nothing */ }
  },
  minPriceInput: function(){
    var val = this.state.minPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  maxPriceInput: function(){
    var val = this.state.maxPrice;
    if(val.toString().length % 3 == 0){
      while (/(\d+)(\d{3})/.test(val.toString())){
        val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
      }
    }
    return val;
  },
  getPrices: function(){
    var prices = [{label:"100,000", value:"100000"},{label:"200,000", value:"200000"}, {label:"300,000", value:"300000"}, {label:"400,000", value:"400000"},
      {label:"500,000", value:"500000"}, {label:"600,000", value:"600000"}, {label:"700,000", value:"700000"}, {label:"800,000", value:"800000"},
      {label:"900,000", value:"900000"}, {label:"1,000,000", value:"1000000"}, {label:"1,100,000", value:"1100000"}, {label:"1,200,000", value:"1200000"},
      {label:"1,300,000", value:"1300000"}, {label:"1,400,000", value:"1400000"},{label:"1,500,000", value:"1500000"}, {label:"1,600,000", value:"1600000"},
      {label:"1,700,000", value:"1700000"}, {label:"1,800,000", value:"1800000"}, {label:"1,900,000", value:"1900000"}, {label:"2,000,000", value:"2000000"},
      {label:"2,250,000", value:"2250000"}, {label:"2,500,000", value:"2500000"}, {label:"2,750,000", value:"2750000"}, {label:"3,000,000", value:"3000000"},
      {label:"4,000,000", value:"4000000"}, {label:"6,000,000", value:"6000000"}, {label:"8,000,000", value:"8000000"}, {label:"12,000,000", value:"12000000"},
      {label:"25,000,000", value:"25000000"}, {label:"50,000,000", value:"50000000"}, {label:"99,000,000", value:"99000000"}];

    var pricesCompare = ["100000","200000", "300000", "400000", "500000", "600000", "700000", "800000", "900000", "1000000", "1100000", "1200000",
      "1300000", "1400000", "1500000", "1600000", "1700000", "1800000", "1900000", "2000000", "2250000", "2500000", "2750000", "3000000", "3500000",
      "4000000", "6000000", "8000000", "12000000", "25000000", "50000000", "99000000"];

    $( ".editFormula .priceInput" ).autocomplete({
      source: prices,
      select: function(e, ui){
        if(e.target.name == "minPrice"){
          this.setState({minPrice: ui.item.value});
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $(".editFormula #price").slider('values',0,index);
        }
        if(e.target.name == "maxPrice"){
          this.setState({maxPrice: ui.item.value});
          var index = pricesCompare.indexOf(ui.item.value);
          index++;
          $(".editFormula #price").slider('values',1,index);
        }
      }.bind(this)
    });
  },
  closeDialog: function(){
    $dialog.dialog('close');
  },
  updateResults: function(){
    var min_price = this.state.minPrice;
    var max_price = this.state.maxPrice;
    var min_price_index = this.state.minPriceStart;
    var max_price_index = this.state.maxPriceStart;
    var bedrooms = this.state.bedrooms;
    var north = this.state.n_fu;
    var westside = this.state.n_uws;
    var eastside = this.state.n_ues;
    var chelsea = this.state.n_mw;
    var smg = this.state.n_me;
    var village = this.state.n_v;
    var lower = this.state.n_d;
    var coop = this.state.coop;
    var condo = this.state.condo;
    var house = this.state.house;
    var condop = this.state.condop;
    var location_grade = this.state.location;
    var building_grade = this.state.building;
    var views_grade = this.state.views;
    var bedroom_area = this.state.bedroomArea;
    var living_area = this.state.livingArea;
    
    var	prices = [ '', {"price":100000,"display":"100K"}, {"price":200000,"display":"200K"}, {"price":300000,"display":"300K"}, {"price":400000,"display":"400K"}, 
			{"price":500000,"display":"500K"}, {"price":600000,"display":"600K"}, {"price":700000,"display":"700K"}, {"price":800000,"display":"800K"}, 
			{"price":900000,"display":"900K"}, {"price":1000000,"display":"1M"}, {"price":1100000,"display":"1.1M"}, {"price":1200000,"display":"1.2M"}, 
			{"price":1300000,"display":"1.3M"}, {"price":1400000,"display":"1.4M"}, {"price":1500000,"display":"1.5M"}, {"price":1600000,"display":"1.6M"},
			{"price":1700000,"display":"1.7M"}, {"price":1800000,"display":"1.8M"}, {"price":1900000,"display":"1.9M"}, {"price":2000000,"display":"2M"}, 
			{"price":2250000,"display":"2.25M"}, {"price":2500000,"display":"2.5M"}, {"price":2750000,"display":"2.75M"}, {"price":3000000,"display":"3M"}, 
			{"price":3500000,"display":"3.5M"}, {"price":4000000,"display":"4M"}, {"price":6000000,"display":"6M"}, {"price":8000000,"display":"8M"}, 
			{"price":12000000,"display":"12M"}, {"price":25000000,"display":"25M"}, {"price":50000000,"display":"50M"}, {"price":99000000,"display":"99M"} ];

    var size_grades = ['','S','M','L','XL'];
    
    if(Number(min_price) >= Number(max_price)){
      $("#ajax-box2").dialog({
        modal: true,
        height: 'auto',
        width: '275px',
        autoOpen: false,
        dialogClass: 'ajaxbox priceRangePopup',
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
      $('#ajax-box2').load('/controllers/messages.php #priceRange',function(){
        $('#ajax-box2').dialog( "option", "title", "Price Range" ).dialog('open');
      });
    }
    else{      
      $.cookie("minPrice", min_price);
      $.cookie("maxPrice", max_price);
      $( "#min_price" ).text(prices[min_price_index]["display"]).attr('data-price',prices[min_price_index]["price"]);
      $( "#max_price" ).text(prices[max_price_index]["display"]).attr('data-price',prices[max_price_index]["price"]);
      $("#price").slider('values',0,min_price_index);
      $("#price").slider('values',1,max_price_index);
      $("#price").slider('refresh');
      $.cookie("minBedroom", bedrooms);
      $("#bedrooms_slider").find("span").html(bedrooms);
      if(bedrooms == 0){
        $('#bedrooms_box .grade_desc input').hide();
        $('#bedrooms_box .grade_desc span').text('Studio');
      } else if(bedrooms == 1){
        $('#bedrooms_box .grade_desc input').show();
        $('#bedrooms_box .grade_desc span').text(' Bedroom');
      } else {
        $('#bedrooms_box .grade_desc input').show();
        $('#bedrooms_box .grade_desc span').text(' Bedrooms');
      }
      $( "#bedrooms" ).val( bedrooms );
      $("#bedrooms_slider").slider('value',bedrooms);
      $( "#bedrooms_slider" ).slider('refresh');      
      $("#neighborhoods").multiselect("uncheckAll");
      var neighs = []
      if(this.state.n_fu == true){ $("input[value=North]").attr("checked", "true"); neighs.push('North'); $.cookie("North", "true"); }else{ $.cookie("North", "false"); }
      if(this.state.n_uws == true){ $("input[value=Westside]").attr("checked", "true"); neighs.push('Westside'); $.cookie("Westside", "true"); }else{ $.cookie("Westside", "false"); }
      if(this.state.n_ues == true){ $("input[value=Eastside]").attr("checked", "true"); neighs.push('Eastside'); $.cookie("Eastside", "true"); }else{ $.cookie("Eastside", "false"); }
      if(this.state.n_mw == true){ $("input[value=Chelsea]").attr("checked", "true"); neighs.push('Chelsea'); $.cookie("Chelsea", "true"); }else{ $.cookie("Chelsea", "false"); }
      if(this.state.n_me == true){ $("input[value=SMG]").attr("checked", "true"); neighs.push('SMG'); $.cookie("SMG", "true"); }else{ $.cookie("SMG", "false"); }
      if(this.state.n_v == true){ $("input[value=Village]").attr("checked", "true"); neighs.push('Village'); $.cookie("Village", "true"); }else{ $.cookie("Village", "false"); }
      if(this.state.n_d == true){ $("input[value=Lower]").attr("checked", "true"); neighs.push('Lower'); $.cookie("Lower", "true"); }else{ $.cookie("Lower", "false"); }
      $("#neighborhoods").val(neighs);
      $("#neighborhoods-container .ui-multiselect").find(".ui-icon").next().html(neighs.length + " neighborhoods");
      $("#prop_type").multiselect("uncheckAll");
      var props = [];
      if(this.state.coop == true){ $("input[value=1]").attr("checked", "true"); props.push("1"); $.cookie("Coop", "true"); }else{ $.cookie("Coop", "false"); }
      if(this.state.condo == true){ $("input[value=2]").attr("checked", "true"); props.push("2"); $.cookie("Condo", "true"); }else{ $.cookie("Condo", "false"); }
      if(this.state.house == true){ $("input[value=4]").attr("checked", "true"); props.push("4"); $.cookie("House", "true"); }else{ $.cookie("House", "false"); }
      if(this.state.condop == true){ $("input[value=5]").attr("checked", "true"); props.push("5"); $.cookie("Condop", "true"); }else{ $.cookie("Condop", "false"); }
      $("#prop_type").val(props);
      $("#prop-container .ui-multiselect").find(".ui-icon").next().html(props.length + " selected");
      $.cookie("location", location_grade);
      $('#loc_desc').text($("#loc_"+ location_grade).text());
      $("#location_grade").slider('value', location_grade);
      $("#location_grade").slider('refresh');
      $.cookie("building", building_grade);
      $('#buil_desc').text($("#buil_"+ building_grade).text());
      $("#building_grade").slider('value', building_grade);
      $("#building_grade").slider('refresh');
      $.cookie("views", views_grade);
      $('#views_desc').text($("#views_"+ views_grade).text());
      $("#views_grade").slider('value', views_grade);
      $("#views_grade").slider('refresh');
      $.cookie("bedroom", bedroom_area);
      if(size_grades[bedroom_area] !== 'XL'){ $("#bedroom_grade").find("span").html(size_grades[bedroom_area]); }
      else{ $("#bedroom_grade").find("span").html(size_grades[bedroom_area]).addClass("smaller"); }
      $('#bedroom_desc').text($("#bedroom_"+ bedroom_area).text());
      $("#bedroom_grade").slider('value', bedroom_area);
      $("#bedroom_grade").slider('refresh');
      $.cookie("living", living_area);
      if(size_grades[living_area] !== 'XL'){ $("#living_grade").find("span").html(size_grades[living_area]); }
      else{ $("#living_grade").find("span").html(size_grades[living_area]).addClass("smaller"); }
      $('#living_desc').text($("#living_"+living_area).text());
      $("#living_grade").slider('value',living_area);
      $("#living_grade").slider('refresh');      
      if(this.state.elevator == true){ $.cookie("elevator", "true"); $("#elevator").attr("src", "images/amenities/elevatorb.png").addClass("selected"); }
      else{ $.cookie("elevator", "false"); $("#elevator").attr("src", "images/amenities/elevator.png").removeClass("selected"); }
      if(this.state.doorman == true){ $.cookie("doorman", "true"); $("#doorman").attr("src", "images/amenities/doormanb.png").addClass("selected"); }
      else{ $.cookie("doorman", "false"); $("#doorman").attr("src", "images/amenities/doorman.png").removeClass("selected"); }
      if(this.state.laundry == true){ $.cookie("laundry", "true"); $("#laundry").attr("src", "images/amenities/laundryb.png").addClass("selected"); }
      else{ $.cookie("laundry", "false"); $("#laundry").attr("src", "images/amenities/laundry.png").removeClass("selected"); }
      if(this.state.pets == true){ $.cookie("pets", "true"); $("#pets").attr("src", "images/amenities/petsb.png").addClass("selected"); }
      else{ $.cookie("pets", "false"); $("#pets").attr("src", "images/amenities/pets.png").removeClass("selected"); }
      if(this.state.fireplace == true){ $.cookie("fireplace", "true"); $("#fireplace").attr("src", "images/amenities/fireplaceb.png").addClass("selected"); }
      else{ $.cookie("fireplace", "false"); $("#fireplace").attr("src", "images/amenities/fireplace.png").removeClass("selected"); }
      if(this.state.pool == true){ $.cookie("pool", "true"); $("#pool").attr("src", "images/amenities/poolb.png").addClass("selected"); }
      else{ $.cookie("pool", "false"); $("#pool").attr("src", "images/amenities/pool.png").removeClass("selected"); }
      if(this.state.garage == true){ $.cookie("garage", "true"); $("#garage").attr("src", "images/amenities/garageb.png").addClass("selected"); }
      else{ $.cookie("garage", "false"); $("#garage").attr("src", "images/amenities/garage.png").removeClass("selected"); }
      if(this.state.healthclub == true){ $.cookie("healthclub", "true"); $("#healthclub").attr("src", "images/amenities/healthclubb.png").addClass("selected"); }
      else{ $.cookie("healthclub", "false"); $("#healthclub").attr("src", "images/amenities/healthclub.png").removeClass("selected"); }
      if(this.state.outdoor == true){ $.cookie("outdoor", "true"); $("#outdoor").attr("src", "images/amenities/roofdeckb.png").addClass("selected"); }
      else{ $.cookie("outdoor", "false"); $("#outdoor").attr("src", "images/amenities/roofdeck.png").removeClass("selected"); }
      if(this.state.handicap == true){ $.cookie("wheelchair", "true"); $("#wheelchair").attr("src", "images/amenities/wheelchairb.png").addClass("selected"); }
      else{ $.cookie("wheelchair", "false"); $("#wheelchair").attr("src", "images/amenities/wheelchair.png").removeClass("selected"); }
      if(this.state.prewar == true){ $.cookie("prewar", "true"); $("#prewar").attr("src", "images/amenities/prewarb.png").addClass("selected"); }
      else{ $.cookie("prewar", "false"); $("#prewar").attr("src", "images/amenities/prewar.png").removeClass("selected"); }
      if(this.state.timeshare == true){ $.cookie("timeshare", "true"); $("#newconstruction").attr("src", "images/amenities/timeshareb.png").addClass("selected"); }
      else{ $.cookie("timeshare", "false"); $("#newconstruction").attr("src", "images/amenities/timeshare.png").removeClass("selected"); }
      if(this.state.newconstruction == true){ $.cookie("newconstruction", "true"); $("#newconstruction").attr("src", "images/amenities/newconstructionb.png").addClass("selected"); }
      else{ $.cookie("newconstruction", "false"); $("#newconstruction").attr("src", "images/amenities/newconstruction.png").removeClass("selected"); }

      jqgridReload();      
      {this.props.closeDialog()}
    }
  },
  cancel: function(){
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div className="clearfix" id="editPage">
        <i className="fa fa-times close-edit-buyer-formula-popup" onClick={this.cancel} title="close"></i>
        <div className="clearfix grpelem" id="y25246-3">
          <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="y25241-3">
         <p>&nbsp;</p>
        </div>
        <div className="clearfix grpelem" id="py2678">
          <div className="clearfix grpelem" id="py25217-6">
            <div className="clearfix colelem" id="y25217-6">
              <p id="y25217-4"><span id="y25217">Edit search criteria </span><span id="y25217-3"><a style={{cursor: "pointer"}} onClick = {this.cancel}>Cancel</a></span></p>
            </div>
            <div className="clearfix colelem" id="py25231-53">
              <div className="clearfix grpelem" id="y25231-53">
                <p id="y25231-2">Minimum Bedroom</p>
                <p id="y25231-3">&nbsp;</p>
                <p id="y25231-4">&nbsp;</p>
                <p id="y25231-6">{this.bedroomText()}</p>
                <p id="y25231-7">&nbsp;</p>
                <p id="y25231-8">&nbsp;</p>
                <p id="y25231-10">Price Range</p>
                <p id="y25231-11">&nbsp;</p>
                <p id="y25231-12">&nbsp;</p>
                <p id="y25231-14">$ {this.minPriceText()} to {this.maxPriceText()}</p>
                <p id="y25231-16">Or, fill in a range: $&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to $&nbsp;&nbsp;</p>
                <p id="y25231-17">&nbsp;</p>
                <p id="y25231-18">&nbsp;</p>
                <p id="y25231-20">Neighborhoods</p>
                <p id="y25231-23"><span id="y25231-21">{this.state.n_all ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}} onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_all')}/> }</span><span id="y25231-22">&nbsp;&nbsp; All of Manhattan</span></p>
                <p id="y25231-26"><span id="y25231-24">{this.state.n_fu ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_fu')}/> }</span><span id="y25231-25">&nbsp;&nbsp; Far Uptown</span></p>
                <p id="y25231-29"><span id="y25231-27">{this.state.n_uws ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_uws')}/> }</span><span id="y25231-28">&nbsp;&nbsp; Upper West Side</span></p>
                <p id="y25231-32"><span id="y25231-30">{this.state.n_ues ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_ues')}/> }</span><span id="y25231-31">&nbsp;&nbsp; Upper East Side</span></p>
                <p id="y25231-36">&nbsp;</p>
                <p id="y25231-37">&nbsp;</p>
                <p id="y25231-39">Property Type</p>
                <p id="y25231-42"><span id="y25231-40">{this.state.coop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'coop')}/> }</span><span id="y25231-41">&nbsp;&nbsp; Co-op</span></p>
                <p id="y25231-45"><span id="y25231-43">{this.state.condo ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condo')}/> }</span><span id="y25231-44">&nbsp;&nbsp; Condo</span></p>
                <p id="y25231-48"><span id="y25231-46">{this.state.house ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'house')}/> }</span><span id="y25231-47">&nbsp;&nbsp; House/Townhouse</span></p>
                <p id="y25231-51"><span id="y25231-49">{this.state.condop ? <img src="/images/select_box_filled_orange.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handlePropertyChange.bind(this,'condop')}/> }</span><span id="y25231-50">&nbsp;&nbsp; Condop</span></p>
              </div>
              <div className="clearfix grpelem" id="y25224-14">
                <p id="y25224-3"><span id="y25224">{this.state.n_mw ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_mw')}/> }</span><span id="y25224-2">&nbsp;&nbsp; Midtown West</span></p>
                <p id="y25224-6"><span id="y25224-4">{this.state.n_me ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_me')}/> }</span><span id="y25224-5">&nbsp;&nbsp; Midtown East</span></p>
                <p id="y25224-9"><span id="y25224-7">{this.state.n_v ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_v')}/> }</span><span id="y25224-8">&nbsp;&nbsp; East/West Village</span></p>
                <p id="y25224-12"><span id="y25224-10">{this.state.n_d ? <img src="/images/select_box_filled_pink.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> : <img src="/images/select_box.png" alt="" style={{cursor: "pointer"}}  onClick={this.handleNeighborhoodChange.bind(this,'n_d')}/> }</span><span id="y25224-11">&nbsp;&nbsp; Downtown</span></p>
              </div>
              <div id="bedrooms_slider"></div>
              <div id="bedroomSliderText"><span className="leftNum">0</span><span className="rightNum">8</span></div>
              <div className="clip_frame grpelem" id="y25220">
                <img className="block" id="y25220_img" src="/images/slider_purple_short.png" alt="" width="455" height="37"/>
              </div>
              <div id="price"></div>
              <div id="priceSliderText"><span className="leftNum">0</span><span className="rightNum">99</span></div>
              <div className="clip_frame grpelem" id="y25211">
                <img className="block" id="y25211_img" src="/images/slider_blue_short.png" alt="" width="455" height="37"/>
              </div>
              <div className="clearfix grpelem" id="y25385">
                <div className="grpelem" id="y25398"><input type="text" id="minPrice" className="priceInput" name="minPrice" value={this.state.minPrice} onBlur={this.checkMinPrice} onChange={this.handleMinPriceChange} onFocus={this.getPrices}/></div>
                <div className="grpelem" id="y25386"><input type="text" id="maxPrice" className="priceInput" name="maxPrice" value={this.state.maxPrice} onBlur={this.checkMaxPrice} onChange={this.handleMaxPriceChange} onFocus={this.getPrices}/></div>
              </div>
            </div>
          </div>
          <div className="grpelem" id="y25216"></div>
          <div className="clearfix grpelem" id="py25213-56">
            <div className="clearfix grpelem" id="y25213-56">
              <p id="y25213-2">Minimum Location Grade</p>
              <p id="y25213-3">&nbsp;</p>
              <p id="y25213-4">&nbsp;</p>
              {this.locationText()}
              <p id="y25213-8">&nbsp;</p>
              <p id="y25213-10">Minimum Building Grade</p>
              <p id="y25213-11">&nbsp;</p>
              <p id="y25213-12">&nbsp;</p>
              {this.buildingText()}
              <p id="y25213-16">&nbsp;</p>
              <p id="y25213-18">Minimum view Grade</p>
              <p id="y25213-19">&nbsp;</p>
              <p id="y25213-20">&nbsp;</p>
              {this.viewText()}
              <p id="y25213-24">&nbsp;</p>
              <p id="y25213-26">Master Bedroom</p>
              <p id="y25213-27">&nbsp;</p>
              <p id="y25213-28">&nbsp;</p>
              {this.bedroomAreaText()}
              <p id="y25213-32">&nbsp;</p>
              <p id="y25213-34">Living Room</p>
              <p id="y25213-35">&nbsp;</p>
              <p id="y25213-36">&nbsp;</p>
              {this.livingAreaText()}
              <p id="y25213-40">&nbsp;</p>
              <p id="y25213-44"><span id="y25213-41">Amenities</span><span id="y25213-42">&nbsp; </span><span id="y25213-43">Click icons to select amenities</span></p>
              <p id="y25213-45">&nbsp;</p>
              <p id="y25213-46">&nbsp;</p>
              <p id="y25213-47">&nbsp;</p>
              <p id="y25213-48">&nbsp;</p>
              <p id="y25213-49">&nbsp;</p>
              <p id="y25213-50">&nbsp;</p>
              <p id="y25213-51">&nbsp;</p>
              <p id="y25213-54"><span id="y25213-52" onClick={this.updateResults} style={{cursor: "pointer"}}>Search </span><span id="y25213-53"><i className="fa fa-chevron-right"></i></span></p>
            </div>
            <div id="location_grade"></div>
            <div className="clip_frame grpelem" id="y25229">
              <img className="block" id="y25229_img" src="/images/locationslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="building_grade"></div>
            <div className="clip_frame grpelem" id="y25214">
              <img className="block" id="y25214_img" src="/images/buildingslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="views_grade"></div>
            <div className="clip_frame grpelem" id="y25227">
              <img className="block" id="y25227_img" src="/images/viewsslider2.png" alt="" width="455" height="37"/>
            </div>
            <div id="bedroom_grade"></div>
            <div id="bedroomAreaSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25222">
              <img className="block" id="y25222_img" src="/images/slider_orange_short.png" alt="" width="455" height="37"/>
            </div>
            <div id="living_grade"></div>
            <div id="livingRoomSliderText"><span className="leftNum">S</span><span className="rightNum">XL</span></div>
            <div className="clip_frame grpelem" id="y25225">
              <img className="block" id="y25225_img" src="/images/slider_green_short.png" alt="" width="455" height="37"/>
            </div>
            <div className="clearfix grpelem" id="y25374">
              <div className="clip_frame grpelem" id="y25208">
                <table id="amenities">
                  <tbody>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'elevator')}>{this.state.elevator ? <img className="amenity" src="/images/amenities/elevatorb.png" alt="elevator" width="30" height="30"/> : <img className="amenity" src="/images/amenities/elevator.png" alt="elevator" width="30" height="30" />}<div className="y25210-11">Elevator</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'doorman')}>{this.state.doorman ? <img className="amenity" src="/images/amenities/doormanb.png" alt="doorman" width="30" height="30"/> : <img className="amenity" src="/images/amenities/doorman.png" alt="doorman" width="30" height="30" />}<div className="y25210-11">Doorman</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'laundry')}>{this.state.laundry ? <img className="amenity" src="/images/amenities/laundryb.png" alt="laundry" width="30" height="30"/> : <img className="amenity" src="/images/amenities/laundry.png" alt="laundry" width="30" height="30" />}<div className="y25210-11">Laundry</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'pets')}>{this.state.pets ? <img className="amenity" src="/images/amenities/petsb.png" alt="pets" width="30" height="30"/> : <img className="amenity" src="/images/amenities/pets.png" alt="pets" width="30" height="30"/>}<div className="y25210-11">Pets</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'fireplace')}>{this.state.fireplace ? <img className="amenity" src="/images/amenities/fireplaceb.png" alt="fireplace" width="30" height="30"/> : <img className="amenity" src="/images/amenities/fireplace.png" alt="fireplace" width="30" height="30"/>}<div className="y25210-11">Fireplace</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'prewar')}>{this.state.prewar ? <img className="amenity" src="/images/amenities/prewar2b.png" alt="prewar" width="30" height="30" /> : <img className="amenity" src="/images/amenities/prewar2.png" alt="prewar" width="30" height="30" />}<div className="y25210-11">Prewar</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'timeshare')}>{this.state.timeshare ? <img className="amenity" src="/images/amenities/timeshareb.png" alt="timeshare" width="30" height="30"/> : <img className="amenity" src="/images/amenities/timeshare.png" alt="timeshare" width="30" height="30" />}<div className="y25210-11">Time shares</div></td>
                    </tr>
                    <tr>
                      <td onClick={this.handleAmenityChange.bind(this, 'pool')}>{this.state.pool ? <img className="amenity" src="/images/amenities/poolb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/pool.png" alt="pool" width="30" height="30" />}<div className="y25210-11">Pool</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'garage')}>{this.state.garage ? <img className="amenity" src="/images/amenities/garageb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/garage.png" alt="garage" width="30" height="30" />}<div className="y25210-11">Garage</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'healthclub')}>{this.state.healthclub ? <img className="amenity" src="/images/amenities/healthclubb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/healthclub.png" alt="healthclub" width="30" height="30"/>}<div className="y25210-11">Health club</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'outdoor')}>{this.state.outdoor ? <img className="amenity" src="/images/amenities/outdoorb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/outdoor.png" alt="outdoor" width="30" height="30" />}<div className="y25210-11">Outdoor space</div></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'handicap')}>{this.state.handicap ? <img className="amenity" src="/images/amenities/wheelchairb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/wheelchair.png" alt="wheelchair" width="30" height="30" />}<div className="y25210-11">Handicap accessible</div></td>
                      <td></td>
                      <td onClick={this.handleAmenityChange.bind(this, 'newconstruction')}>{this.state.newconstruction ? <img className="amenity" src="/images/amenities/newconstructionb.png" alt="" width="30" height="30" /> : <img className="amenity" src="/images/amenities/newconstruction.png" alt="newconstruction" width="30" height="30" />}<div className="y25210-11">New construction</div></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div id="amenitiesSeparator"></div>
            </div>
          </div>
        </div>
      </div>
    );
  }
});

$('body').delegate('.view-bubble-grades','click',function(e){
  var $dialog =  $("#ajax-box").dialog({
    width: 260,
    dialogClass: 'viewGradesPopup',
    modal: true,
    close: function(){
      ReactDOM.unmountComponentAtNode(document.getElementById('ajax-box'));
      var div = document.createElement('div');
      div.id = 'ajax-box';
      document.getElementsByTagName('body')[0].appendChild(div);
      $( this ).remove();
    },
    open: function(){
     // $(this).css("display", "block");
      $(".ui-widget-overlay").bind("click", function(){
        $("#ajax-box").dialog('close');
      });
    }
  });
  var closeDialog = function(){
    $dialog.dialog('close');
  }.bind(this)

  ReactDOM.render(<GradeBubbles closeDialog={closeDialog}/>, $dialog[0]);
});
  
var GradeBubbles = React.createClass({
  closePopup: function(){
    {this.props.closeDialog()}
  },
  render: function(){
    return(
      <div id="gradeBubbles">
        <i id="closeGradeBubblePopup" className="fa fa-times" onClick={this.closePopup} data-toggle='tooltip' title='close'></i>
        <h3>Grade Bubble Meanings</h3>
        <h6 className="Text-2-ex-lead" id="u334-12"><img className="viewImgs" src="../images/meets.png"/> = meets your criteria</h6>
        <h6 className="Text-2-ex-lead" id="u334-14"><img className="viewImgs" src="../images/exceeds.png"/> = exceeds your criteria</h6>
        <h6 className="Text-2-ex-lead"><img className="viewImgs" src="../images/greatly.png"/> = greatly exceeds your</h6>
      </div>
    );
  }
});