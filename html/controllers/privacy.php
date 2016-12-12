<?php
session_start();
include_once('functions.php');
include_once("basicHead.php");

$mainPage = (isset($_GET['MP']) ? $_GET['MP'] : "index");
?>

  <title>HomePik - Privacy Policy</title>
  <?php include_css("/views/css/privacy.css"); ?>
</head>
<body>
  <div id="privacy"></div>
  <div id="footer"></div>
  <div id="overlay"></div>
  <div id='ajax-box'></div>
  <div id='ajax-box2'></div>
<script type="text/babel">
	var Privacy = React.createClass({
	  render: function(){
      return(
        <div className="clearfix" id="page">
          <div className="position_content" id="page_position_content">
            <Header />
            <NavBar />
            <AddressSearch />
            <div id="privacySection">
              <div className="Text-1 clearfix colelem" id="u22654-4">
                <p>Privacy Policy</p>
              </div>
              <div className="clearfix colelem" id="privacyText">
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">(also referred to herein as "we" or "us") is committed to protecting the privacy of the users of its web site.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">This statement discloses our privacy practices. The purpose of this statement is to inform you:</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* What kinds of information we collect from users of the HomePik web site at HomePik.com (the "Web Site" ) and how such information is collected;</p>
                <p className="Text-1 indent">* How the information is used by us;</p>
                <p className="Text-1 indent">* Whether we disclose any user information to third parties;</p>
                <p className="Text-1 indent">* How you can access, update or delete any information that we collect about you; and</p>
                <p className="Text-1 indent">* The security procedures which we use to protect your personal information.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">By using the Web Site, you signify your assent to the Privacy Policy. We reserve the right to change, modify, add, or remove portions of our Privacy Policy at any time. If so, any such changes will be posted on this page. Please check this page periodically for changes. Your continued use of this Web Site following the posting of changes to the Privacy Policy will mean you accept those changes.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 boldText">How We Collect Information</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">Generally, you can visit the Web Site without revealing any personal information. Like many websites, our web servers collect the Internet Protocol addresses, but not the e-mail addresses, of visitors. This information can measure the number of visits, average time spent on the site, pages viewed, and other such statistics.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">In addition, in order for us to provide certain services to you, you may be asked for information that identifies you, including your name, e-mail address, mailing address, zip code, telephone number, fax (collectively, "Personal Information" ). You may also elect to provide such information to us by sending or responding to e-mails regarding certain listings or services offered on our Web Site. Additionally, you may ask us to store information concerning the type, price, location and features of properties that you are interested in.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 boldText">How We Use and Disclose Your Information</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">We collect, generate, retain and use your Personal Information for our own internal purposes in connection with the facilitation, recording and processing of any requests, communications, or interactions you may have with our Web Site. We also automatically collect and store statistics and other information about you and your online activities on an aggregated, non-personally identifiable basis and in a manner that may allow us or our affiliated or related entities to improve our services to you.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">In addition, your Personal Information may be also be used by us to provide you with information regarding our products and services. For example, we may use your e-mail address to send you special announcements and notifications of new real estate listings, services or promotions that may be of interest to you.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 boldText">Cookies</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">Cookies are small pieces of information that are stores by your browser on your computer's hard drive. Cookies allow web sites to recognize you when you return and can keep track of information specific to you. Consistent with standard practices in the Internet industry, you may occasionally receive cookies from third parties to which you link from our Web Site. We do not control these third party cookies, and they are not subject to this Privacy Policy. If you prefer, you may set your browser to ask for your permission before your receive a cookie.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 boldText">Information Sharing and Disclosure</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">Information about our customers is important to our business, and we are not in the business of selling it to others. We share customer information only with our related or affiliated entities and or in the circumstances described below:</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* Agents</p>
                <p className="Text-1 extra-indent">We may share your information with our experienced team of agents, who may use this information to contact you about our sales, leasing, financing and other services.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* Business Transfers</p>
                <p className="Text-1 extra-indent">Over time, it is possible that a portion or all of our business may be sold. Customer information is generally one of the transferred assets. In the unlikely event that substantially all of our asserts are acquired, your customer information may be transferred, subject to all applicable laws and governmental regulations regarding the transfer and sale of such personal data.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* Affiliated Businesses</p>
                <p className="Text-1 extra-indent">We work closely with our affiliated businesses, such as Vanderbilt Appraisal Company, a real estate appraisal company, Management, a real estate management company. To the extent that you have provided information indicating that services of our affiliated businesses might be useful to you, we may share such information with such businesses.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* Protection of the Company and Others</p>
                <p className="Text-1 extra-indent">We release personal information when we believe such release is necessary to comply with law; enforce our Terms and Conditions of Use; or protect the rights, property, or safety of , its and their officers, directors, employees, agents, and others.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 indent">* With Your Consent</p>
                <p className="Text-1 extra-indent">Other than as set out above, you will receive notice when your Personal Information may be shared with third parties, and you will have the opportunity to elect not to share such information.</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1 boldText">Data Security</p>
                <p className="Text-1">&nbsp;</p>
                <p className="Text-1">Data security is critical to us and thus all Personal Information is held in a secured database. While it is impossible to guarantee the complete security of any computer system and the data contained therein, we maintain and implement robust security policies and procedures that combine with available technologies in accordance with prevailing industry standards, all of which are designed to protect the confidentiality, integrity, and availability of your Personal Information. To the extent we are provided with social security numbers or personal financial information, we comply with all applicable regulations regarding the confidentiality and safe disposal of such information.</p>
                <p className="Text-1">&nbsp;</p>
              </div>
            </div>
          </div>
        </div>
      );
	  }
	});

	ReactDOM.render(
    <Privacy/>,
    document.getElementById("privacy")
  );

  ReactDOM.render(
	  <Footer mainPage={"<?php echo $mainPage ?>"} />,
	  document.getElementById("footer")
	);

</script>
<?php include_once('autoLogout.php'); ?>
</body>
</html>
