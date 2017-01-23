<?php
session_start();
include_once('functions.php');
include_once('basicHead.php');
if(isset($_GET['saved']) && $_GET['saved'] == true){ $_SESSION['loadSaved'] = true; }
?>

<html>
<head>
  <meta name="robots" content="noindex">
  <title>HomePik - Verification Setup</title>
  <?php include_css("/views/css/verification.css"); ?>
  <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
</head>
<body class="verification_page">
  <div id="verificationBackground2"></div>
  <div id="verification"></div>
  <div id='ajax-box'></div>

<script type="text/babel">
  var VerificationSetup = React.createClass({
    getInitialState: function() {
      return{
        secQues: "default",
        secAns: ""
      };
    },
	  componentDidMount: function(){
      if (navigator.userAgent.indexOf('Mac OS X') != -1 && navigator.userAgent.indexOf('Firefox') != -1) {
        $("#formQuestion").addClass("macFF");
      }
	  },
    handleChange: function (name, event) {
      var change = {};
      change[name] = event.target.value;
      this.setState(change);
    },
    checkInput: function(){
      if( this.state.secQues != "default" && this.state.secAns != "" ){ return true; }
      else{ return false; }
    },
    submit: function(e){
      if( this.state.secQues == "default" || this.state.secAns == "" ){
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: 'auto',
          autoOpen: false,
          dialogClass: 'ajaxbox errorMessage verificationBlank',
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
        $('#ajax-box').load('messages.php #verification_setup_blank',function(){
          $('#ajax-box').dialog('open');
        });
        e.preventDefault();
      }
    },
    render: function(){
      return(
        <div id="wrapper">
          <div className="container-fluid">
            <div className="row">
              <div className="col-md-2 col-sm-2"></div>
              <div className="col-md-7 col-sm-10 col-xs-11">
                <div id="verificationArea" className='short'>
                  <div id="verificationTop">
                    <span id="verificationHeader" className="text-popups">Verification Setup</span>
                  </div>
                  <div id="verificationBorder">
                    <form onSubmit={this.validate} action="users/verification-setup-process.php" id="validate" method="post" autoComplete="off">
                      <table cellPadding="2" cellSpacing="0" border="0">
                        <colgroup>
                          <col width="250"/><col width="350"/>
                        </colgroup>
                        <tbody>
                          <tr>
                            <td className="text-popups" colSpan="2">
                              <div style={{textAlign: "justify"}}>Please select a security question and answer to use to access your account if you forget your password.<br/><br/></div>
                            </td>
                          </tr>
                          <tr id="question">
                            <td className="text-popups">Security Question:</td>
                            <td className="text-popups">                              
                              <select id="formQuestion" name="security-question" onChange={this.handleChange.bind(this, 'secQues')}>
                                <option value="default" selected="selected">Select A Security Question</option>
                                <option value="1">What is your middle name?</option>
                                <option value="2">What is your mother's maiden name?</option>
                                <option value="3">What was the name of the street where you grew up?</option>
                                <option value="4">What is your favorite food?</option>
                              </select>
                              {this.state.secQues != "default" ? null : <strong className="pink"> {'\u002A'}</strong> }
                            </td>
                          </tr>
                          <tr id="answer">
                            <td className="text-popups">Security Answer:</td>
                            <td className="text-popups"><input type="text" id="formAnswer" className="grade_desc input1" name="security-answer" onChange={this.handleChange.bind(this, 'secAns')}/>{this.state.secAns != "" ? null : <strong className="pink"> {'\u002A'}</strong> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2" id="fieldsAlert" className="text-popups">{this.checkInput() ? <span className="pink right"> {'All Fields Filled'}</span> : <span className="pink right"> {'\u002A Required Fields'}</span> }</td>
                          </tr>
                          <tr>
                            <td colSpan="2">
                              <input type="hidden" name="formStep" value="verification" />
                              <button type="submit" name="submit" id="verificationSubmit" className="text-popups" onClick={this.submit}>Submit <i id="arrow" className="fa fa-chevron-right"></i></button>
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

  ReactDOM.render(
    <VerificationSetup/>,
    document.getElementById("verification")
  );

</script>
</body>
</html>
