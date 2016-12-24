<?php
  $broker = $tplvar['broker'];
?>
</head>
<body>
  <div id="{$tplvar['list_num']}" class="listNum">
    <p id="listActive"></p>
    <div id="{$tplvar['code2']}" class="code"></div>
    <div id="main">
      <div id="profile">
        <div class="listingtop1">
          <h1 class="blue">{$tplvar['address']} <span class='details'>#{$tplvar['apt']}</span></h1>
        </div>
        <br class="clear">
        <div class="listingmain1">
          <div class="images">
            <div class="imagebig">
              <a class='lightbox'>
                <img id="image1" class='image' alt='{$tplvar['address']} ' src='{$tplvar['default']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';">
              </a>
            </div>
            <div class="imagesgallery">
              <style type='text/css'>#" . {$tplvar['list_num']} . " #profile .images .imagesgallery .gallerysmallimage img { width: 120px !important; } </style>
              {if $tplvar['photo1'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image1" class="image" src='{$tplvar['photo1']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
                  </a>
                </div>
              {/if}
              {if $tplvar['photo2'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                   <img id="image2" class="image" src='{$tplvar['photo2']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
                  </a>
                </div>
              {/if}
              {if $tplvar['photo3'] != '' }
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image3" class="image" src='{$tplvar['photo3']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo4'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image4" class="image" src='{$tplvar['photo4']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo5'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image5" class="image" src='{$tplvar['photo5']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo6'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image6" class="image" src='{$tplvar['photo6']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo7'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image7" class="image" src='{$tplvar['photo7']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo8'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image8" class="image" src='{$tplvar['photo8']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo'/>
                  </a>
                </div>
              {/if}
              {if $tplvar['photo9'] != ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img id="image9" class="image" src='{$tplvar['photo9']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';"/>
                  </a>
                </div>
              {/if}
              {if isset($tplvar['flrplan']) && $tplvar['flrplan']!= ''}
                <div class='gallerysmallimage imageblock1'>
                  <a class='lightbox'>
                    <img class="image" src='{$tplvar['flrplan']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';"/>
                  </a>
                </div>
              {/if}
              <br style="clear: both;">
            </div>  {* /imagesgallery *}
          </div> {* /images *}
          <br><br>
        </div> <!-- /listingmain1  -->

        <div class="listingmain2">
          <div class="infobox">
            <h2>Essentials</h2>
            <table class="detailscontent">
              <tbody>
                <tr><th>Price</th><td style="font-size: 24px;">${$tplvar['price']}</td></tr>
                <tr><th>Neighborhood</th><td>{$tplvar['nbrhood']}</td></tr>
                <tr><th>Bedrooms</th><td>{$tplvar['bed']}</td></tr>
                <tr><th>Baths </th><td>{$tplvar['bath']}</td></tr>
                <tr><th style="width: 200px;">Maint/CC & Taxes </th><td>${$tplvar['monthly']}/mo</td></tr>
                <tr><td>&nbsp;</td></tr>
                {$tplvar['saved_to']}
                <tr class='icons'><th colspan="2">
                  <a class="listing-small-icons" id="{$tplvar['list_num']}emailListing" style="clear:both; color:#666666;text-decoration:underline;cursor:pointer;" title="Email Listing" >
                    <span> <i class="fa fa-envelope-o fa-2x"></i><span>Send</span></span>
                  </a>
                  {if $tplvar['auth'] == 'guest'}
                    <a class="listing-small-icons" id="no-guest" rel="save-listing" title="Save Listing" style="position: relative;" data-list-num="{$tplvar['list_num']}">
                      <span class="heart-tabInner-{$tplvar['list_num']} save-listing-guest-inner">
                        <i class="fa fa-heart fa-2x"></i>
                        <span>Save</span>
                      </span>
                    </a>
                    <a class="print listing-small-icons" title="Print">
                      <span><i class="fa fa-print fa-2x"></i><span>Print</span></span>
                    </a>
                    <a class="listing-small-icons listing-small-icons-cost cost-estimator-pop-up-trigger" id="cost-estimator-pop-up-trigger{$tplvar['list_num']}{$tplvar['agent_id_1']}" title="Cost Estimator">
                      <span><i class="fa fa-calculator fa-2x"></i><span>Cost Estimator</span></span>
                    </a>
                  {else}
                    {if $tplvar['auth'] == 'agent'}
                      <a class=".ajax-dialog listing-small-icons tab-head-div" rel="save-listing" title="Save Listing" data-list-num="{$tplvar['list_num']}">
                        <span class="heart-tabInner-{$tplvar['list_num']} save-listing-agent-tab-inner">
                          <i class="fa fa-heart fa-2x"></i>
                          <span>Save</span>
                        </span>
                      </a>
                    {else}
                      <a class=".ajax-dialog listing-small-icons tab-head-div" rel="save-listing" title="Save Listing" data-list-num="{$tplvar['list_num']}">
                        <span class="heart-tabInner-{$tplvar['list_num']} save-listing-buyer-tab-inner">
                          <i class="fa fa-heart fa-2x"></i>
                          <span>Save</span>
                        </span>
                      </a>
                    {/if}
                    <a class="print listing-small-icons" title="Print">
                      <span><i class="fa fa-print fa-2x"></i><span>Print</span></span>
                    </a>
                    <a class="listing-small-icons listing-small-icons-cost cost-estimator-pop-up-trigger" id="cost-estimator-pop-up-trigger{$tplvar['list_num']}{$tplvar['agent_id_1']}" title="Cost Estimator">
                      <span><i class="fa fa-calculator fa-2x"></i><span>Cost Estimator</span></span>
                    </a>
                    {if $tplvar['auth'] == 'agent'}
                      <a class="{$tplvar['list_num']}brokerDetails listing-small-icons listing-small-icons" title="Listing Broker">
                        <span>
                          <img class="icons" style="height: 1.8em; margin: 0px; opacity: 0.9;" src="http://homepik.com/images/Agent.png" alt="Broker Details">
                          <span>Broker</span>
                        </span>
                      </a>
                    {/if}
                  {/if}
                  <!-- popup -->
                  <div class='need-to-signup-first-div-inner bubble speech' style="display: none;">
                    <span class='fa fa-times need-to-signup-first-div-inner-closer'></span>
                    You cannot save listings without an account.
                    <br><br>To create an account,
                    <br>click <a class='need-to-signup-first-div-link' href='/controllers/guest-register.php'>here</a>.
                  </div>
                  <div class='need-to-signup-first-div-inner-folders bubble speech' style="display: none;">
                    <span class='fa fa-times need-to-signup-first-div-inner-closer'></span>
                    You cannot save listings without an account.
                    <br><br>To create an account,
                    <br>click <a class='need-to-signup-first-div-link' href='/controllers/guest-register.php'>here</a>.
                  </div>
                  <!-- popup ends -->
                </th></tr>
                <tr><th></th><td></td></tr>
                <tr><th></th><td></td></tr>
                <tr><th></th><td></td></tr>
              </tbody>
            </table>
          </div>
          <div class="infobox">
            <h2>Space</h2>
            <table class="detailscontent">
              <tbody>
                {if $tplvar['bedroom1'] != ''}
                  <tr><th>Master Bed</th><td> {$tplvar['bedroom1']}</td></tr>
                {/if}
                {if $tplvar['living_room'] != ''}
                  <tr><th>Living Room</th><td>{$tplvar['living_room']}</td></tr>
                {/if}
                {if $tplvar['dining_room'] != ''}
                  <tr><th>Dining Room</th><td>{$tplvar['dining_room']}</td></tr>
                {/if}
                {if $tplvar['bedroom2'] != ''}
                  <tr><th>Bedroom 2</th><td>{$tplvar['bedroom2']}</td></tr>
                {/if}
                {if $tplvar['bedroom3'] != ''}
                  <tr><th>Bedroom 3</th><td>{$tplvar['bedroom3']}</td></tr>
                {/if}
                {if $tplvar['bedroom4'] != ''}
                  <tr><th>Bedroom 4</th><td>{$tplvar['bedroom4']}</td></tr>
                {/if}
                {if $tplvar['den'] != ''}
                  <tr><th>Den</th><td>{$tplvar['den']}</td></tr>
                {/if}
                {if $tplvar['alcove'] != ''}
                  <tr><th>Alcove</th><td>{$tplvar['alcove']}</td></tr>
                {/if}
                {if $tplvar['kitchen'] != ''}
                  <tr><th>Kitchen</th><td>{$tplvar['kitchen']}</td></tr>
                {/if}
                {if $tplvar['maids_room'] != ''}
                  <tr><th>Maid's Room</th><td>{$tplvar['maids_room']}</td></tr>
                {/if}
                <tr>
                  <th class="factor-th">
                    Space factor
                    <div class="space-factor-popup-container">
                      <i class="fa fa-question-circle space-factor-popup-trigger"></i>
                    </div>
                  </th>
                  <td>
                    {if $tplvar['spac'] != '' && $tplvar['spac'] != 0 }
                      {$tplvar['spac']} sf
                    {else}
                      N/A
                    {/if}
                  </td>
                </tr>
                <tr>
                  <th>
                    Implied gross footage
                    <div class="implied-square-footage-popup-container">
                      <i class="fa fa-question-circle implied-square-footage-popup-trigger"></i>
                    </div>
                  </th>
                  <td>
                    {if $tplvar['spac'] != '' && $tplvar['spac'] != 0 }
                      {$tplvar['spac']*1.3} sf
                    {else}
                      N/A
                    {/if}
                  </td>
                </tr>
                <tr><td style="font-size: 0.5em">&nbsp;</td></tr>
                <tr><td colspan="2"><span class="disclaimer" style="text-align: justify; display: block; font-size: 13px; line-height: 18px;">All dimensions are approximate. <br> HomePik is not responsible for any errors or omissions.</span></td></tr>
                <tr><th></th><td></td></tr>
                <tr><th></th><td></td></tr>
                <tr><th></th><td></td></tr>
              </tbody>
            </table>
          </div>
          <div class="infobox">
            <h2 style="margin-bottom: 5px;">Features</h2>
            {$tplvar['amenities_html']}
          </div>
        </div> <!-- /listingmain2 -->

        <div class="listingmain2" id="lm3" style="margin-right:0px;margin-left:50px;">
          <div class="infobox" id="profileContactInfo" style="height:260px;">
            <h2>Contact</h2>
              <div class="contact" style="position: relative;">
                <table>
                {if $tplvar['agent_id_1'] != '' || $tplvar['agent_id_1'] != null}
                  <table><tr>
                    <td><img class="agent" alt="agent photo" onError="this.onerror=null;this.src='http://homepik.com/images/noagent.png';" src="http://www.bellmarc.com/pictures/agent/medium/{$tplvar['agent_id_1']}.jpg" style="float: left; width: 80px; margin: 0pt 2px 0pt 0pt; display:inline-block;"></td>
                    {* <div style="float: right; font-size: 1.1em; display:inline-block;"> *}
                    <td>
                      <div id="agentFirst" style="display:inline-block">{$tplvar['agent_firstname']}</div> <div id="agentLast" style="display:inline-block">{$tplvar['agent_lastname']}</div>
                      <div style="position: relative; line-height: 17px; margin: 1px 0pt 0pt;" >{$tplvar['agent_title']}</div>
                      {if $tplvar['agent_phone'] != ''}
                        <div class="phone" style="position: relative; line-height: 17px; margin: 8px 0pt 0pt;">{$tplvar['agent_phone']}</div>
                      {/if}
                    </td>
                  </tr></table>
                  <tr><td>
                    <button type="button" class="agent-info-list" id="{$tplvar['list_num']}{$tplvar['agent_id_1']}agentBio"><div>
                    <i class="fa fa-chevron-right"></i>
                    <span>View</span> agent information</div></button>
                  </td></tr>
                  <br>
                  {if $tplvar['auth'] == 'user' && $tplvar['agent-txt'] != ""}
                    <tr><td>
                      <div id="agent-btn"><a class="{$tplvar['list_num']}primary" data-agent="{$tplvar['agent_id_1']}"><button class="agent-info-list" type="button" >
                        <div id="agent-txt">
                          <i class="fa fa-chevron-right"></i>
                          {$tplvar['agent-txt']}
                        </div>
                      </button></a></div>
                    </td></tr>
                  {/if}
                  <tr><td>
                    <a class="{$tplvar['list_num']}contact"><button class="agent-info-list" type="button" id="{$tplvar['agent_email']}">
                      <div id="email-txt">
                        <i class="fa fa-chevron-right"></i>
                        <span>Email</span> agent
                        <br><span class="agentEmail">{$tplvar['agent_email']}</span>
                      </div>
                    </button></a>
                  </td></tr>
                </div>
                {/if}

                {if $tplvar['agent_id_2'] != ''}
                  <table id="agentSpacing"><tr>
                    <td><img class="agent" alt="agent photo" onError="this.onerror=null;this.src='http://homepik.com/images/noagent.png';" src="http://www.bellmarc.com/pictures/agent/medium/{$tplvar['agent_id_2']}.jpg" style="float: left; width: 80px; margin: 0pt 2px 0pt 0pt; display:inline-block;"></td>
                    <div style="float: right; font-size: 1.1em; display:inline-block;">
                    <td>
                      <div id="agentFirst" style="display:inline-block">{$tplvar['agent2_firstname']}</div> <div id="agentLast" style="display:inline-block">{$tplvar['agent2_lastname']}</div>
                      <div style="position: relative; line-height: 17px; margin: 1px 0pt 0pt;" >{$tplvar['agent2_title']}</div>
                      {if $tplvar['agent2_phone'] != ''}
                        <div class="phone" style="position: relative; line-height: 17px; margin: 8px 0pt 0pt;">{$tplvar['agent2_phone']}</div>
                      {/if}
                    </td>
                  </tr></table>
                  <tr><td>
                    <button type="button" class="agent-info-list" id="{$tplvar['list_num']}{$tplvar['agent_id_2']}agentBio"><div>
                      <i class="fa fa-chevron-right"></i>
                      <span>View</span> agent information
                    </div></button>
                  </td></tr>
                  <br>
                  {if $tplvar['auth'] == 'user' && $tplvar['agent-txt'] != ""}
                    <tr><td>
                      <div id="agent-btn"><a class="{$tplvar['list_num']}primary" data-agent="{$tplvar['agent_id_2']}"><button class="agent-info-list" type="button" >
                        <div id="agent-txt">
                          <i class="fa fa-chevron-right"></i>
                          {$tplvar['agent-txt2']}
                        </div>
                      </button></a></div>
                    </td></tr>
                  {/if}
                  <tr><td>
                    <a class="{$tplvar['list_num']}contact"><button class="agent-info-list" type="button" id="{$tplvar['agent2_email']}">
                      <div id="email-txt">
                        <i class="fa fa-chevron-right"></i>
                        <span>Email</span> agent
                        <br><span class="agentEmail">{$tplvar['agent2_email']}</span>
                      </div>
                    </button></a>
                  </td></tr>
                </div>
                {/if}              
                </table>
              </div>  <!-- /contact -->
            <br><br>
            {* 
            {if $tplvar['auth'] == 'agent'}
              <div class="infobox" style="height:185px;" id="profileHomePikOffice">
              <h2>HomePik Office</h2>
                <div style="position: relative;margin-top: 7px;" class="options">
                  {* <button type="button" class="agent-info-list" id="{$tplvar['list_num']}-spv"><div><i class="fa fa-chevron-right"></i>Selection Portfolio Valuation</div></button><br> *}
                  <button type="button" class="agent-info-list" id="{$tplvar['list_num']}-streeteasy"><div><i class="fa fa-chevron-right"></i>Streeteasy Listing</div></button><br>
                  <button type="button" class="agent-info-list" id="{$tplvar['list_num']}-olr"><div><i class="fa fa-chevron-right"></i>OLR Listing</div></button><br>
                  {* <button type="button" class="agent-info-list" id="{$tplvar['list_num']}-broker-website"><div><i class="fa fa-chevron-right"></i>Broker Website Listing</div></button> *}
                  </div>
                </div>
            {/if}
            *}
          </div>
        </div> {* /listingmain2 *}

        <br style="clear: both;">

        <div class="infobox">
          <h2>Property Description</h2>
          <table colspan="2" cellspacing="10">
            <tr>
              <td id="mkt_desc" width="645px;">
                {if $tplvar['mkt_desc'] != ''}
                  {$tplvar['mkt_desc']}
                {else}
                  <p>Description Unavailable</p>
                {/if}
                <br/><br/>
                {if $tplvar['role'] == 'guest'}
                  <h3> This listing has been provided courtesy of
                    {if $tplvar['broker'] != ''}
                      {$tplvar['broker']}
                    {else}
                      {$tplvar['contract']}
                    {/if}
                  </h3>
                {/if}
                <br/>
                {if $tplvar['RLS_id_match'] != '' && $tplvar['auth'] == 'guest'}
                  <p><img src="http://homepik.com/images/ListingService_Final.jpg" style="width: 100px; height: 50px;"></p>
                {/if}
                <br>
                <p id="listingDisclaimer">
                  {if $tplvar['auth'] == 'guest'} Guest public portal. {else} This is not a public portal and users who are not registered as HomePik buyers are prohibited from using this site. {/if}
                  <br>HomePik is a Licensed Real Estate Broker in the State of New York.
                </p>
              </td>
              <td width="215px;" style="padding-left:40px;">
                <iframe style="border: 7px solid #e6e6e6; -moz-border-radius: 5px 5px 5px 5px; border-radius:5px;" src="http://maps.google.com/maps?q={$tplvar['address']},+New+York,+NY+'&output=embed" width="200" height="210"></iframe>
              </td>
            </tr>
          </table>
        </div>
        <br>
        <br style="clear: both;">

        {* CONTACT FORM *}
        <div id="{$tplvar['list_num']}contact-form" title='Contact Bellmarc' class="new_popup_design ui-dialogue-custom-content">
          <form class="cont_f_n_p">
            <h3>Email Listing</h3>
            {if $tplvar['role'] == 'guest'}
              <div class="cont_f_n_p guestName"><label>Your Name:</label> <input type="text" id="guestName" name="guestName" value="" class="text ui-widget-content ui-corner-all"></input> </div>
              <div class="cont_f_n_p guestEmail"><label>Your Email:</label> <input type="text" id="guestEmail" name="guestEmail" value="" class="text ui-widget-content ui-corner-all"></input> </div>
            {/if}
            
            <div class="cont_f_n_p"><input id="friendSend" type="checkbox"/> <label>To a friend</label> <input type="text" name="friendEmail" value="" id="friendEmail" class="text ui-widget-content ui-corner-all"></input> </div>
            <div class="cont_f_n_p"><input id="agent1Send" type="checkbox"/> <label>To the agent</label> {$tplvar['agent_email']}<input type="hidden" id="agentEmail" name="agentEmail" value="{$tplvar['agent_email']}"></input></div>

            {if $tplvar['P_agent2'] != ''}
              <div class="cont_f_n_p"><input id="agent2Send" type="checkbox"/> <label>To the agent</label> {$tplvar['agent2_email']} <input type="hidden" id="agent2Email"  name="agent2Email" value="{$tplvar['agent2_email']}"></input> </div>
            {/if}

            <textarea name="contactComments" placeholder="enter your message here (optional)"  id="contactComments" class="text ui-widget-content ui-corner-all"></textarea>
            <div class="ui-dialogue-custom-closer ui-dialogue-agent-bio-closer-above"><span class="fa fa-times"></span></div>
            <div class="ui-dialogue-email-send-outer"><span class="ui-dialogue-email-send">Send <i class="fa fa-chevron-right color-blue"></i></span></div>
          </form>
        </div>

        <div id="{$tplvar['list_num']}agentSave" title='Save to Buyer Folder'></div>

        <div id="{$tplvar['list_num']}buyerSave" title='Save to My Folder'></div>

        <div id="{$tplvar['list_num']}{$tplvar['agent_id_1']}agentDialog" title='Agent Profile' class="ui-dialogue-agent-bio-content">
          <img class="ui-dialogue-agent-bio-img" onError="this.onerror=null;this.src='http://homepik.com/images/noagent.png';" src="http://www.bellmarc.com/pictures/agent/medium/{$tplvar['agent_id_1']}.jpg">
          <h3 class="ui-dialogue-agent-bio-heading">
            Bio:
            <span class="ui-dialogue-agent-bio-name">{$tplvar['agent_firstname']} {$tplvar['agent_lastname']}</span>
          </h3>
          <p class="ui-dialogue-agent-bio-desc">
            {if $tplvar['agent_bio_1'] != ''}
              {$tplvar['agent_bio_1']}
            {else}
              Profile unavailable.
            {/if}
          </p>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-above">
            <span class="fa fa-times"></span>
          </div>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-below">Close</div>
        </div>

        <div id="{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog" title='Agent Profile' class="ui-dialogue-agent-bio-content">
          <img class="ui-dialogue-agent-bio-img" onError="this.onerror=null;this.src='http://homepik.com/images/noagent.png';" src="http://www.bellmarc.com/pictures/agent/medium/{$tplvar['agent_id_2']}.jpg">
          <h3 class="ui-dialogue-agent-bio-heading">
            Bio:
            <span class="ui-dialogue-agent-bio-name">{$tplvar['agent2_firstname']} {$tplvar['agent2_lastname']}</span>
          </h3>
          <p class="ui-dialogue-agent-bio-desc">
            {if $tplvar['agent_bio_2'] != ''}
              {$tplvar['agent_bio_2']}
            {else}
              Profile unavailable.
            {/if}
          </p>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-above">
            <span class="fa fa-times"></span>
          </div>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-below">Close</div>
        </div>

        <div id="{$tplvar['list_num']}{$tplvar['agent_id_2']}agentDialog2" title='Agent Profile' class="ui-dialogue-agent-bio-content">
          <img class="ui-dialogue-agent-bio-img" onError="this.onerror=null;this.src='http://homepik.com/images/noagent.png';" src="http://www.bellmarc.com/pictures/agent/medium/{$tplvar['agent_id_2']}.jpg">
          <h3 class="ui-dialogue-agent-bio-heading">
            Bio:
            <span class="ui-dialogue-agent-bio-name">{$tplvar['agent2_firstname']} {$tplvar['agent2_lastname']}</span>
          </h3>
          <p class="ui-dialogue-agent-bio-desc">
            {if $tplvar['agent_bio_2'] != ''}
              {$tplvar['agent_bio_2']}
            {else}
              Profile unavailable.
            {/if}
          </p>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-above">
              <span class="fa fa-times"></span>
          </div>
          <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-below">Close</div>
        </div>

        <div id="{$tplvar['list_num']}brokerDetails" title="Broker Details">         
          <div class="ui-dialogue-broker-details-closer ui-dialogue-broker-details-closer-above">
            <span class="fa fa-times"></span>
          </div>
          <p class="text-popups"> This listing has been provided courtesy of
            {if $tplvar['broker'] != ''}
              {$tplvar['broker']}
            {else}
              {$tplvar['contract']}
            {/if}
            <br/><br/>
            Contact: {if $tplvar['contact'] != ""} {$tplvar['contact']} {else} <span>Not Available</span> {/if}
            <br>
            Email: {if $tplvar['contact_email'] != ""} {$tplvar['contact_email']} {else} <span>Not Available</span> {/if}
            <br>
            Phone: {if $tplvar['contact_phone'] != ""} {$tplvar['contact_phone']} {else} <span>Not Available</span> {/if}
            <br/><br/>
            Listing Number: {$tplvar['list_num']}
          </p> 
        </div>
      </div>

      <div id="{$tplvar['list_num']}overlay" class="overlay {$tplvar['list_num']}close" style="display:none;"></div>
      <div id="{$tplvar['list_num']}slideshowArea" class="slideshowArea" style="display: none;">
      <div class="{$tplvar['list_num']}close slideshowClose"><i class="fa fa-times"></i></div>
      <div id="{$tplvar['list_num']}previous" class="slideshowPrevious"><i class="fa fa-angle-left"></i></div>
      <div id="{$tplvar['list_num']}next" class="slideshowNext"><i class="fa fa-angle-right"></i></div>
      <div id="{$tplvar['list_num']}slideshow" class="slideshow">
        <img id="image0" style = "display:none;" />
        {if $tplvar['photo1'] != ''}
          <img id="image1" class="nonactive slideshowImg" src='{$tplvar['photo1']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo2'] != ''}
          <img id="image2" class="nonactive slideshowImg" src='{$tplvar['photo2']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo3'] != ''}
          <img id="image3" class="nonactive slideshowImg" src='{$tplvar['photo3']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo4'] != ''}
          <img id="image4" class="nonactive slideshowImg" src='{$tplvar['photo4']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo5'] != ''}
          <img id="image5" class="nonactive slideshowImg" src='{$tplvar['photo5']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo6'] != ''}
          <img id="image6" class="nonactive slideshowImg" src='{$tplvar['photo6']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo7'] != ''}
          <img id="image7" class="nonactive slideshowImg" src='{$tplvar['photo7']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo8'] != ''}
          <img id="image8" class="nonactive slideshowImg" src='{$tplvar['photo8']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
        {if $tplvar['photo9'] != ''}
          <img id="image9" class="nonactive slideshowImg" src='{$tplvar['photo9']}' onError="this.onerror=null;this.src='http://homepik.com/images/nopicture3.png';" alt='Listing Photo' />
        {/if}
      </div>
    </div>

    <div class="ui-dialogue-cost-estimator-popup" id="ui-dialogue-cost-estimator-popup{$tplvar['list_num']}{$tplvar['agent_id_1']}">
      <div class="c-header">
        Cost Estimator
        <img src="/images/button_calculator.jpg" alt='Cost Estimator'>
      </div>
      <div class="c-body">
        <div class="c-row">
          <div class="c-col-l">Apartment address</div>
          <div class="c-col-r"><span class="color-blue">{$tplvar['address']}</span></div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Asking price or your bid price</div>
          <div class="c-col-r">
            <span>$ &nbsp;</span>
            <input type="text" value="{$tplvar['price']}" id="bid" name="bid"/>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Monthly common/maintenance charge</div>
          <div class="c-col-r">
            <span>$ &nbsp;</span>
            <input type="text" value="{$tplvar['maint']}" id="monthly_charge" name="monthly_charge"/>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Monthly real estate tax (condo only)</div>
          <div class="c-col-r">
            <span>$ &nbsp;</span>
            <input type="text" value="{$tplvar['taxes']}" id="monthly_tax" name="monthly_tax"/>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Interest rate</div>
          <div class="c-col-r">
            <input type="text" value="4.25" id="interest_rate" name="interest_rate" style="float: none; width: 123px"/>
            <span>%</span>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Length of term</div>
          <div class="c-col-r">
            <input type="number" min="0" max="100" value="30" id="length_of_term" name="length_of_term" style="float: none; width: 95px"/>
            <span>years</span>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Cash down payment percent</div>
          <div class="c-col-r">
            <input type="number" min="0" max="100" value="25" id="payment_percent" name="payment_percent" style="float: none; width: 123px"/>
            <span>%</span>
          </div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Cash down payment in cash</div>
          <div class="c-col-r"><span id="cashDownPayment"></span></div>
        </div>
        <div class="c-row">
          <div class="c-col-l">Your estimated monthly cost</div>
          <div class="c-col-r"><span id="monthlyCost"></span></div>
        </div>
      </div>
      <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-above ui-dialogue-cost-estimator-popup-closer"><span class="fa fa-times"></span></div>
      <div class="ui-dialogue-agent-bio-closer ui-dialogue-agent-bio-closer-below ui-dialogue-cost-estimator-popup-closer ui-dialogue-cost-estimator-popup-closer-bottom">Close</div>
    </div>
    
    <div class="ui-dialogue-selection-portfolio-valutation-popup" id="ui-dialogue-selection-portfolio-valutation-popup{$tplvar['list_num']}">
      <div class="clearfix grpelem" id="SPV-div">
        <div class="clearfix grpelem" id="SPV-content">
          <h2 class="Subhead-2" id="u17925-2">Selection Portfolio Valuation</h2>
          <h4 class="text-popups" id="u17925-3">&nbsp;</h4>
          <form action="spv.php" method="POST" target="_blank">
            <div class="informationRow">
              <h4 class="text-popups" id="address"><span class="title">Address:</span> {$tplvar['address']}</h4>
              <h4 class="text-popups" id="listing_price"><span class="title">Price:</span> $<input type="text" id="priceInput" name="price" value={$tplvar['price']} /></h4>
              <h4 class="text-popups" id="bed"><span class="title">Bedrooms:</span> <input type="number" id="bedroomInput" name="min_bed" min="0" max="9" value={$tplvar['price']} /></h4>
            </div>
            <div class="informationRow">
              <h4 class="text-popups" id="apt"><span class="title">Apartment:</span> {$tplvar['apt']}</h4>
              <h4 class="text-popups" id="floor"><span class="title">Floor:</span> <input type="number" id="minFloorInput" name="min_floor" min="0" max="30" defaultValue={$tplvar['floor']}/></h4>
              <h4 class="text-popups" id="monthly"><span class="title">Monthly:</span> $<input type="text" id="monthlyInput" name="min_maint" value={$tplvar['monthly']} /></h4>
              <h4 class="text-popups" id="bath"><span class="title">Baths:</span> <input type="number" id="bathInput" name="min_bath" min="0" max="10" value={$tplvar['bath']} /></h4>
            </div>
              
            <h4 class="text-popups">&nbsp;</h4>
            <ul class="nav nav-tabs text-popups">
              <li role="presentation" class="active"><a href="#main" aria-controls="main" role="tab" data-toggle="tab">Main</a></li>
              <li role="presentation"><a href="#location" aria-controls="location" role="tab" data-toggle="tab">Location</a></li>
              <li role="presentation"><a href="#building" aria-controls="building" role="tab" data-toggle="tab">Building</a></li>
              <li role="presentation"><a href="#view" aria-controls="view" role="tab" data-toggle="tab">View</a></li>
              <li role="presentation"><a href="#size" aria-controls="size" role="tab" data-toggle="tab">Size</a></li>
            </ul>
            
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="main">
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="area">
                    <span class="title">Area:</span><br/>
                    <input type="radio" name="listing_location" value="Local"/> Local<br/>
                    <input type="radio" name="listing_location" value="All"/> All Manahattan<br/>
                    <input type="radio" name="listing_location" value="Zip"/> With zip {$tplvar['zip']}
                  </h4>
                  <h4 class="text-popups" id="type">
                    <span class="title">Listing Type:</span><br/>
                    <input type="radio" name="listing_type" value="Active"/> Active<br/>
                    <input type="radio" name="listing_type" value="Historical"/> Historical<br/>
                    <input type="radio" name="listing_type" value="Both"/> Both
                  </h4>
                  <h4 class="text-popups">
                    <span class="title">Price Range:</span><br/>
                    <span id="minPriceRange">$<span id="minPrice"></span> To <span id="maxPriceRange">$<span id="maxPrice"></span></span><br/>
                    <input type="number" id="priceRangeInterest" name="interest" min="5" max="100" defaultValue="10" step="5"/></h4>  
                </div>
                
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="condition">
                    <span class="title">Condition:</span><br/>
                    <input type="radio" name="condition" value="Level 1"/> Complete rehabilitation required <br/>
                    <input type="radio" name="condition" value="Level 2"/> Obsolete components in need of replacement <br/>
                    <input type="radio" name="condition" value="Level 3"/> Premium quality high luxury <br/>
                    <input type="radio" name="condition" value="Level 4"/> High quality decorator improvements <br/>
                    <input type="radio" name="condition" value="Level 5"/> Decorator showcase, triple mint condition.
                  </h4>
                  <h4 class="text-popups" id="demand">
                    <span class="title">Demand:</span><br/>
                    <input type="radio" name="demand" value="Level 1"/> Appreciating market <br/>
                    <input type="radio" name="demand" value="Level 2"/> Stable market <br/>
                    <input type="radio" name="demand" value="Level 3"/> Slightly declining market <br/>
                    <input type="radio" name="demand" value="Level 4"/> Dramatically declining market
                  </h4>
                </div>            
                <h4 class="text-popups" id="u17925-3">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="condos">
                    <span class="title">Condos only:</span><br/>
                    <input type="radio" name="condos_only" value="yes"/> Yes<br/>
                    <input type="radio" name="condos_only" value="no"/> No
                  </h4>
                  <h4 class="text-popups" id="amenity_checkboxes">
                    <span class="title">Amenities:</span><br/>
                    <input type="checkbox" name="amenities[]" value="fireplace"/> Fireplace<br/>
                    <input type="checkbox" name="amenities[]" value="elevator"/> Elevator
                  </h4>
                  <h4 class="text-popups" id="private_outdoors">
                    <span class="title">Private Outdoors:</span><br/>
                    <input type="radio" name="outdoors_amenity" value="none"/> None <br/>
                    <input type="radio" name="outdoors_amenity" value="balcony"/> Balcony <br/>
                    <input type="radio" name="outdoors_amenity" value="terrace"/> Terrace <br/>
                    <input type="radio" name="outdoors_amenity" value="setBack"/> Setback Terrace <br/>
                    <input type="radio" name="outdoors_amenity" value="garden"/> Private garden / Roof deck
                  </h4>
                </div>                    
              </div>
              <div role="tabpanel" class="tab-pane" id="location">
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="loc">Location:</h4>
                  <h4 class="text-popups" id="loc_grade"><span class="title">Grade:</span> <input type="text" id="minLocInputSet" name="min_loc" value={$tplvar['loc']} disabled="disabled" /></h4>
                </div>
                <div class="text-popups">
                  <input type="radio" name="location_grade" value="10"/> Internationally renown / near major park<br/>
                  <input type="radio" name="location_grade" value="9"/> Residential area close to major park<br/>
                  <input type="radio" name="location_grade" value="8"/> Residential area / near local park or river<br/>
                  <input type="radio" name="location_grade" value="7"/> Residential area / neighborhood amenities<br/>
                  <input type="radio" name="location_grade" value="6"/> Quiet residential street<br/>
                  <input type="radio" name="location_grade" value="5"/> Active commercial street / Residential Area<br/>
                  <input type="radio" name="location_grade" value="4"/> Active commercial street<br/>
                  <input type="radio" name="location_grade" value="3"/> Emerging residential area<br/>
                  <input type="radio" name="location_grade" value="2"/> New developing market<br/>
                  <input type="radio" name="location_grade" value="1"/> All locations
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="building">
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="bld">Building:</h4>
                  <h4 class="text-popups" id="bld_grade"><span class="title">Grade:</span> <input type="text" id="minBldInputSet" name="min_bld" value={$tplvar['bld']} disabled="disabled" /></h4>
                </div>
                <div class="text-popups">
                  <input type="radio" name="building_grade" value="10"/> International renown<br/>
                  <input type="radio" name="building_grade" value="9"/> Locally renowned building or new construction with full services<br/>
                  <input type="radio" name="building_grade" value="8"/> Full service building with amenities<br/>
                  <input type="radio" name="building_grade" value="7"/> Doorman building with amenities<br/>
                  <input type="radio" name="building_grade" value="6"/> Doorman building &mdash;no amenities<br/>
                  <input type="radio" name="building_grade" value="5"/> Elevator building in good condition<br/>
                  <input type="radio" name="building_grade" value="4"/> Elevator building in fair condition<br/>
                  <input type="radio" name="building_grade" value="3"/> Walkup in good condition<br/>
                  <input type="radio" name="building_grade" value="2"/> Walkup in fair condition<br/>
                  <input type="radio" name="building_grade" value="1"/> All buildings
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="view">
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 class="text-popups" id="vws">Views:</h4>
                  <h4 class="text-popups" id="vws_grade"><span class="title">Grade:</span> <input type="text" id="minVwsInputSet" name="min_vws" value={$tplvar['vws']} disabled="disabled" /></h4>
                </div>
                <div class="text-popups">
                  <input type="radio" name="view_grade" value="10"/> Cityscape and Central Park views<br/>
                  <input type="radio" name="view_grade" value="9"/> Cityscape and river or park views<br/>
                  <input type="radio" name="view_grade" value="8"/> Cityscape views<br/>
                  <input type="radio" name="view_grade" value="7"/> Rooftop views<br/>
                  <input type="radio" name="view_grade" value="6"/> Street view or interior garden, bright<br/>
                  <input type="radio" name="view_grade" value="5"/> Street view or interior garden, moderate light<br/>
                  <input type="radio" name="view_grade" value="4"/> Interior courtyard or area without view but bright<br/>
                  <input type="radio" name="view_grade" value="3"/> Interior courtyard or area with moderate light<br/>
                  <input type="radio" name="view_grade" value="2"/> Indirect light<br/>
                  <input type="radio" name="view_grade" value="1"/> All properties
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="size">
                <h4 class="text-popups">&nbsp;</h4>
                <h4 class="text-popups" id="value_rooms">Value Rooms</h4>
                <h4 class="text-popups">The space factor consists of an accumulation of the measurements in the Living Room, Dining Room, Master Bedroom
                and 2nd and 3rd Bedrooms. This is more accurate than gross footage since it is verifiable. The Space Factor generally accounts for approximately
                90% of the usable area within a studio or 1 Bedroom apartment. It accounts for approximately 85% of the usable area in two and three bedroom apartments.</h4>
                <h4 class="text-popups">&nbsp;</h4>
                <div class="informationRow">
                  <h4 id="label" class="text-popups">Primary<br/>Value<br/>Rooms</h4>
                  <table id="dimensionTable">
                    <colgroup><col width="175"/><col width="145"/><col width="70"/></colgroup>
                    <tbody>
                      <tr>
                        <td><span class="text-popups">&nbsp;</span></td>
                        <td><span class="text-popups">&nbsp;</span></td>
                        <td><span class="text-popups">SQ.Feet</span></td>
                      </tr>
                      <tr>
                        <td><span class="text-popups">Living Room</span></td>
                        <td><span class="text-popups"><input type="text" id="lrone" class="dimensionInput" name="living_dimension[]" value=""/> x <input type="text" id="lrtwo" class="dimensionInput" name="living_dimension[]" value=""/></span></td>
                        <td><span class="text-popups"><input type="text" id="lrt" class="dimensionTotal" name="living_dimension[]" value="" disabled="disabled"/></span></td>
                      </tr>
                      <tr>
                        <td><span class="text-popups">Master Bedroom</span></td>
                        <td><span class="text-popups"><input type="text" id="mbone" class="dimensionInput" name="master_dimension[]" value=""/> x <input type="text" id="mbtwo" class="dimensionInput" name="master_dimension[]" value=""/></span></td>
                        <td><span class="text-popups"><input type="text" id="mbt" class="dimensionTotal" name="master_dimension[]" value="" disabled="disabled"/></span></td>
                      </tr>
                      <tr>
                        <td><span class="text-popups">Dining Room</span></td>
                        <td><span class="text-popups"><input type="text" id="drone" class="dimensionInput" name="dining_dimension[]" value=""/> x <input type="text" id="drtwo" class="dimensionInput" name="dining_dimension[]" value=""/></span></td>
                        <td><span class="text-popups"><input type="text" class="dimensionTotal" name="dining_dimension[]" value="" disabled="disabled"/></span></td>
                      </tr>
                      <tr>
                        <td><span class="text-popups">2nd Bedroom</span></td>
                        <td><span class="text-popups"><input type="text" id="bbone" class="dimensionInput" name="2bedroom_dimension[]" value=""/> x <input type="text" id="bbtwo" class="dimensionInput" name="2bedroom_dimension[]" value=""/></span></td>
                        <td><span class="text-popups"><input type="text" id="bbt" class="dimensionTotal" name="2bedroom_dimension[]" value="" disabled="disabled"/></span></td>
                      </tr>
                      <tr>
                        <td><span class="text-popups">3rd Bedroom</span></td>
                        <td><span class="text-popups"><input type="text" id="bbbone" class="dimensionInput" name="3bedroom_dimension[]" value=""/> x <input type="text" id="bbbtwo" class="dimensionInput" name="3bedroom_dimension[]" value=""/></span></td>
                        <td><span class="text-popups"><input type="text" id="bbbt" class="dimensionTotal" name="3bedroom_dimension[]" value="" disabled="disabled"/></span></td>
                      </tr>
                    </tbody>
                  </table>
                  <h4 id="total" class="text-popups">Total Value<br/>Room SQ.<br/>Footage<br/><input type="text" id="total_sqf" name="min_space" value="" disabled="disabled" /></h4>
                </div>
              </div>
            </div>
            <h4 class="text-popups">&nbsp;</h4>
            <input type="hidden" name="listing" value={$tplvar['list_num']}/>
            <input type="hidden" id="minLocInputSet" name="min_loc" value={$tplvar['loc']}/>
            <input type="hidden" id="minBldInputSet" name="min_bld" value={$tplvar['bld']}/>
            <input type="hidden" id="minVwsInputSet" name="min_view" value={$tplvar['vws']}/>
            <input type="hidden" id="total_sqf" name="min_space" value={$tplvar['space']}/>
            <button type="submit" name="submit" class="text-popups" id="submit">Process <i class="fa fa-chevron-right"></i></button>
          </form>              
        </div>
      </div>
      <h4 id="{$tplvar['list_num']}closeSpvPopup"><i class="fa fa-times" title="close"></i></h4>
    </div>
</body>
</html>
