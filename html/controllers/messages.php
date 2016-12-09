<?php session_start(); ?>
<div id="no-formula" class="text-popups"> <?if($_SESSION['agent']){ ?> <p>This buyer does not have a buyer formula saved.</p> <? }else{ ?> <p>You do not have a buyer formula saved. Go to new search to create one.</p> <? } ?> </div>
<div id="addBuyerInfo" class="text-popups"><p>You must enter all information to add a new buyer.</p></div>
<div id="invalidInput" class="text-popups"><p>The information entered did not match the information of file.</p></div>
<div id="invalidBuyerEmail" class="text-popups"><p>Please enter a valid email address.</p></div>
<div id="invalidBuyerPhone" class="text-popups"><p>Please enter a valid phone number.</p></div>
<div id="addBuyerEmail" class="text-popups"><p>Accounts with @bellmarc.com can not be added as buyers.</p></div>
<div id="addBuyerPrimary" class="text-popups"><p>An account already exists for this buyer. You have been made their primary agent.</p></div>
<div id="addBuyerPrimary2" class="text-popups"><p>An account already exists for this buyer and they have one primary agent. You have been made their second primary agent.</p></div>
<div id="addBuyerExists" class="text-popups"><p>An account already exists for this buyer. They already have the limited amount of primary agents.</p></div>
<div id="addBuyerConfirmation" class="text-popups"><p>Buyer has been added and an email containing an invitation to HomePik and the New York State Disclosure Form for Buyer and Seller has been sent.</p></div>
<div id="buyerAdded" class="text-popups"><p>An invitation has been sent to <span id="buyerName"></span> to join HomePik.</p></div>
<div id="alreadyAgent" class="text-popups"><p>You are already this buyer's agent.</p></div>
<div id="clearListing" class="text-popups"><p>Are you sure you want to delete this listing?</p></div>
<div id="clearListings" class="text-popups"><p>Are you sure you want to delete all the listings in this folder?</p></div>
<div id="deleteFormula" class="text-popups"><p>Are you sure you want to delete formula: <b><span id='formulaName'></span></b> ?</p></div>
<div id="deleteBuyer" class="text-popups"><p>Are you sure you want to permanently delete the account and all related information for this buyer: <b><span id="name"></span></b> ?</p></div>
<div id="noRegistration" class="text-popups"><p>You did not consent to the New York State Disclosure Form for Buyer and Seller, thus you have not been registered with HomePik.</p></div>
<div id="invalidName" class="text-popups" style="width:200px;"><p>Your first and last name cannot have numeric values.<p></div>
<div id="registerRquirements" class="text-popups" style="width:200px;"><p>You need to fill in all of the requirements.</p></div>
<div id="registerDisclosure" class="text-popups" style="width:200px;"><p>You must accept the disclosure form to create an account.</p></div>
<div id="passwordRequirement" class="text-popups" style="width:200px;"><p>Your password must be at least five characters long.</p></div>
<div id="passwordsMatch" class="text-popups" style="width:200px;"><p>New password and confirm password do not match.</p></div>
<div id="invalidPrice" class="text-popups"><p>The price you entered is invalid. Your input has been adjusted to the next lowest value.</div>
<div id="priceRange" class="text-popups"><p>The maximum price can not be less than or equal to the minimum price.</p></div>
<div id="editEmail" class="text-popups"><p>This email is already associated with an account.</p></div>
<div id="changeEmail" class="text-popups"><p>Are you sure you want to change your email to <b><span id="email"></span></b>? This will be your email when logging in.</p></div>
<div id="loading" class="text-popups"><p>Loading...</p></div>
<div id="addPrimary" class="text-popups"><p>Invalid agent code.</p></div>
<div id="addSecondary" class="text-popups"><p>Can not add that agent twice.</p></div>
<div id="blankName" class="text-popups"><p>Name can not be blank</p></div>
<div id="blankPhone" class="text-popups"><p>Phone can not be blank</p></div>
<div id="blankEmail" class="text-popups"><p>Email can not be blank</p></div>
<div id="buyer-note" class="text-popups"><p><b>Note:</b> Users with @bellmarc accounts cannot be added as buyers.</p></div>
<div id="browse-search" class="text-popups"><button>BROWSE</button><br><button>BROWSE</button></div>
<div id="change-buyer" class="text-popups"><div style="margin: 0px auto 20px;font-size:1.1em;"><b>Redo search using buyer formula?</b></div>  <button class="change-yes" style="width:20%;color:#ffffff;">Yes</button><br><button class="change-no" style="width:20%;color:#ffffff;margin-top:5%">No</button></div>
<div id="moveListing" class="text-popups"><p>You must select at least one listing to move.</p></div>
<div id="deleteListing" class="text-popups"><p>You must select at least one listing to delete.</p></div>
<div id="deleteListingConfirm" class="text-popups"><p>Are you sure you want to delete these listings?</p></div>
<div id="saveListing" class="text-popups"><p class="Text-1">You must select at least one folder to save the listing to.</p></div>
<div id="saveListingAgent" class="text-popups"><p class="Text-1">You must select at least one person to save the listing to.</p></div>
<div id="loggedOut" class="text-popups"><p class="Text-1">You have been logged off due to inactivity.</p></div>
<div id="saveError" class="text-popups"><p>Please select at least one folder.</p></div>
<div id="errorMessage" class="text-popups"><p>Could not locate save point. Please close the save popup and try again.</p></div>
<div id="guestSave" class='text-popups need-to-signup-first-div bubble speech'><span class='fa fa-times need-to-signup-first-div-closer'></span>This listing has been temporarily saved. All saved listings will be removed when you logout.<br/><span class="bottomCloser need-to-signup-first-div-closer">Ok</span></div>
<div id="agentAdded" class="text-popups"><p>An agent account for <span id="agentName"></span> has been created and an email has been sent.</p></div>
<div id="needDate" class="text-popups"><p>You must select at least a month and a year.</p></div>
<div id="invalidDate" class="text-popups"><p>The date entered is invalid, please check.</p></div>
<div id="loadingAnimation" class="text-popups"><img src='../images/ajax-loader-large.gif' alt='loading, please wait...' height='50'></div>