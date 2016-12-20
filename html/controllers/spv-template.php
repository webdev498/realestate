<?php
  include('functions.php');
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href='https://webfonts.creativecloud.com/c/be4972/1w;lato,2,c0p:W:n3,c0x:W:n7/l' rel='stylesheet' type='text/css'>
    <?php include_css("../views/css/spv-template.css"); ?>
  </head>
  <body>
    <div id="header">
      <img class="headerImg" src="../images/homepik_logo.png" alt=""/>
      <span class="headerSeperator"></span>
      <span class="headerText">Selection Portfolio<sup>TM</sup>&nbsp;&nbsp; Current Market Valuation</span>     
    </div>
    <div id="content">       
      <br class="whiteSpace"/>
      <div id="pageSeperator"></div>
      <table id="firstRow">
        <tbody>
          <tr>
            <td><span style="width: 230px" class="titleText">PROPERTY</span></td>
            <td><span style="width: 50px" class="titleText">APT.</span></td>
            <td align="right"><span style="width: 75px;" class="titleText">ASKING PRICE</span></td>
            <td><span style="width: 30px;">&nbsp;</span></td>
            <td align="center"><span style="width: 40px" class="titleText">BDRMS</span></td>
            <td align="center"><span style="width: 40px" class="titleText">BTHS</span></td>
            <td><span style="width: 25px;">&nbsp;</span></td>
            <td align="center"><span style="width: 50px" class="titleText">TYPE</span></td>
            <td><span style="width: 10px;">&nbsp;</span></td>
            <td align="center"><span style="width: 90px" class="titleText">DATE</span></td>
          </tr>
          <tr>
            <td><span style="width: 230px" class="infoText"><?php echo $address ?></span></td>
            <td><span style="width: 50px" class="infoText"><?php echo $apt ?></span></td>
            <td align="right" ><span style="width: 75px" class="infoText"><?php echo $price ?></span></td>
            <td><span style="width: 30px;">&nbsp;</span></td>
            <td align="center"><span style="width: 40px" class="infoText"><?php echo $bed ?></span></td>
            <td align="center"><span style="width: 40px" class="infoText"><?php echo $bath ?></span></td>
            <td><span style="width: 25px;">&nbsp;</span></td>
            <td align="center"><span style="width: 50px" class="infoText"><?php echo $property ?></span></td>
            <td><span style="width: 10px;">&nbsp;</span></td>
            <td align="center"><span style="width: 90px" class="infoText"><?php echo date('m/d/Y') ?></span></td>
          </tr>
        </tbody>
      </table>
      <br/>
      <table id="secondRow">
        <tbody>
          <tr>
            <td><span style="width: 230px" class="titleText">PRICE RANGE</span></td>
            <td><span style="width: 170px" class="titleText">NEIGHBORHOOD</span></td>
            <td><span style="width: 195px" class="titleText">DATA RESOURCES</span></td>
            <td align="center"><span style="width: 50px" class="titleText">NO FILTERS</span></td>
          </tr>
          <tr>
            <td><span style="width: 230px" class="infoText">$<?php echo $minPriceRange ?> <span class="titleText priceSeperator">TO</span> $<?php echo $maxPriceRange ?></span></td>
            <td><span style="width: 170px" class="infoText"><?php echo $nbrhood ?></span></td>
            <td><span style="width: 195px" class="infoText"><?php echo $listings .", " . $location ?></span></td>
            <td align="center"><div class="boxed"><p class="infoText"><?php echo $num_listings_no_filter ?></p></div></td>
          </tr>
        </tbody>
      </table>
      <br/>
      <table id="thirdRow">
        <tbody>
          <tr>
            <td><span style="width: 605px" class="titleText">FILTERS</span></td>
            <td align="center"><span class="titleText">WITH FILTERS</span></td>
          </tr>
          <tr>
            <td><span style="width: 605px" class="infoText"><?php echo $additional_filters ?></span></td>
            <td align="center"><div class="boxed"><p class="infoText"><?php echo $num_listings_filtered ?></p></div></td>
          </tr>
        </tbody>
      </table>
      <br style="line-height: 1"/>
      <div id="pageSeperator"></div>
      <table id="fourthRow">
        <tbody>
          <tr>
            <td><span style="width: 75px" class="titleText">EVALUTION</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="titleText">POPULATION<br/>AVERAGE</span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="titleText">SUBJECT<br/>PROPERTY</span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="titleText">DIFFERENCE</span></td>
          </tr>
          <tr>
            <td><span style="width: 75px" class="infoText">Location</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $avg_location ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $loc ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $location_diff ?></span></td>
          </tr>        
          <tr>
            <td><span style="width: 75px" class="infoText">Building</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $avg_building ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $bld ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $building_diff ?></span></td>
          </tr>        
          <tr>
            <td><span style="width: 75px" class="infoText">View</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $avg_views ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $vws ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $views_diff ?></span></td>
          </tr>        
          <tr>
            <td><span style="width: 75px" class="infoText">Floor</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $avg_floor ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $floor ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $floor_diff ?></span></td>
          </tr>        
          <tr>
            <td><span style="width: 75px" class="infoText">Space</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $avg_space ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $space ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $space_diff ?></span></td>
          </tr>        
          <tr>
            <td><span style="width: 75px" class="infoText">Monthly Charge</span></td>
            <td><span style="width: 74px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText">$<?php echo $avg_monthly ?></span></td>
            <td><span style="width: 64px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText">$<?php echo $monthly ?></span></td>
            <td><span style="width: 59px;">&nbsp;</span></td>
            <td align="center"><span style="width: 20px" class="infoText"><?php echo $monthly_diff ?></span></td>
          </tr>
        </tbody>
      </table>
      <br style="line-height: 1.3;"/>
      <table id="fifthRow">
        <tbody>
          <tr>
            <td><span style="width: 110px" class="titleText">EVALUTION DETAILS</span></td>
            <td><span style="width: 430px" class="titleText"></span></td>
            <td><span style="width: 3px" class="titleText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="titleText">ADJUSTMENT</span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Location</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $location_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $location_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Building</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $building_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $building_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">View</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $views_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $views_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Floor</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $floor_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $floor_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Space</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $space_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $space_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Monthly charge</span></td>
            <td><span style="width: 430px" class="infoText">Difference in the monthly charge is annualized and then multiplied by the current interest rate</span></td>
            <td><span style="width: 3px" class="titleText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText"></span></td>
            <td align="right"><span style="width: 430px" class="infoText">. .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  .  . <?php echo $monthly_equation ?> </span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $monthly_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Amenities</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $amenities ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText">$<?php echo $amenities_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Floor</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $condition_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $condition_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText"></span></td>
            <td align="right"><span style="width: 430px" class="infoText bold">Total property adjustments</span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText bold">$<?php echo $total_prop_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText"></span></td>
            <td align="right"><span style="width: 430px" class="infoText bold">Total adjusted propery value</span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText bold">$<?php echo $prop_val_adjustment ?></span></td>
          </tr>
        </tbody>
      </table>
      <br/>
      <table id="sixthRow">
        <tbody>
          <tr>
            <td><span style="width: 110px" class="titleText">MARKET PLACE NEGOTIABILITY</span></td>
            <td><span style="width: 430px" class="titleText">&nbsp;</span></td>
            <td><span style="width: 3px" class="titleText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="titleText">ADJUSTMENT</span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Inventory</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $inventory_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $inventory_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">Demand</span></td>
            <td><span style="width: 430px" class="infoText"><?php echo $demand_eval ?></span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText"><?php echo $demand_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 430px" class="infoText bold">Total adjustments</span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText bold">$<?php echo $total_adjustment ?></span></td>
          </tr>
          <tr>
            <td><span style="width: 110px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 430px" class="infoText bold">Adjusted value</span></td>
            <td><span style="width: 3px" class="infoText">&nbsp;</span></td>
            <td align="right"><span style="width: 50px" class="infoText bold">$<?php echo $val_adjustment ?></span></td>
          </tr>
        </tbody>
      </table>
      <div id="footer">
        <p class="dropDown pageNumber">PAGE <span id="num">1</span></p>
      </div>
      
      <?php
      $pages = ceil(count($compare_listings) / 40);      
      $index = 0;
      for($i=0; $i < $pages; $i++){ ?>
        <div style="page-break-before: always;"></div>
        <br class="whiteSpace"/>
        <div id="page2Seperator"></div>
        <table id="seventhRow" width="500">
          <tbody>
            <tr><td><div id="listing-details"><span class="titleText">PROPERTY</span><span id="address" class="infoText"><?php echo $address ?></span><span id="apt" class="infoText"><?php echo $apt ?></span></div></td></tr>
            <tr><td><div id="listing-details"><span class="titleText">SCAN RANGE</span><span id="minPrice" class="infoText">$1,795,000</span><span class="titleText priceSeperator">TO</span><span id="maxPrice" class="infoText">$1,995,000</span><span class="titleText interestSeperator">@</span><span id="interest" class="infoText">0.000</span><span class="titleText">%INTEREST</span></div></td></tr>
            <tr><td><div id="listing-details"><span class="titleText">AVERAGES</span><span id="location" class="titleText">LOCATION &nbsp;<span class="grade infoText"><?php echo $avg_location ?></span></span><span id="building" class="titleText">BUILDING &nbsp;<span class="grade infoText"><?php echo $avg_building ?></span></span><span id="floor" class="titleText">FLOOR &nbsp;<span class="grade infoText"><?php echo $avg_floor ?></span></span><span id="view" class="titleText">VIEW &nbsp;<span class="grade infoText"><?php echo $avg_views ?></span></span><span id="space" class="titleText">SPACE &nbsp;<span class="grade infoText"><?php echo $avg_space ?></span></span><span id="maint" class="titleText">MAINTENANCE &nbsp;<span class="grade infoText">$<?php echo $avg_monthly?></span></span></div></td></tr>
          </tbody>
        </table>      
        <div id="pageSeperator2"></div>
        <br/>
        
        <table id="eighthRow">
          <tbody>
            <tr>
              <td><span style="width: 16px" class="titleText">BRS</span></td><td><span style="width: 2px">&nbsp;</span></td><td><span style="width: 158px" class="titleText">APARTMENT</span></td><td class="smallLineHeight" align="center"><span style="width: 25px" class="titleText">SAME<br/>BLDG</span></td><td><span style="width: 1px">&nbsp;</span></td>
              <td><span style="width: 215px" class="titleText">FEATURES</span></td><td align="center"><span style="width: 30px" class="titleText">MAINT</span></td>
              <td><span style="width: 5px">&nbsp;</span></td><td align="center"><span style="width: 50px" class="titleText">PRICE</span></td><td><span style="width: 1px">&nbsp;</span></td><td class="smallLineHeight" align="center"><span style="width: 20px" class="titleText">L<br/>O<br/>C</span></td>
              <td class="smallLineHeight" align="center"><span style="width: 19px" class="titleText">B<br>L<br>D</span></td><td class="smallLineHeight" align="center"><span style="width: 25px" class="titleText">F<br>L<br>R</span></td>
              <td class="smallLineHeight" align="center"><span style="width: 8px" class="titleText">V<br/>W<br/>S</span></td><td class="smallLineHeight" align="center"><span style="width: 50px" class="titleText">SPACE<br/>FACTOR<br/>SQ FT</span></td>
            </tr>
          </tbody>
        </table>
        
        <table id="ninthRow" cellspacing="0" cellpadding="0">
          <tbody>
            <?php
            for($j=0; $j<40 && $index < count($compare_listings); $j++){ ?>
              
            <tr>
              <td align="center"><span style="width: 16px" class="smallText"><?php echo $compare_listings[$index]['bed'] ?></span></td><td><span style="width: 12px">&nbsp;</span></td><td><span style="width: 158px" class="smallText"><?php echo $compare_listings[$index]['address'] ?>, <?php echo $compare_listings[$index]['apt'] ?></span></td><td align="center"><span style="width: 25px" class="smallText"><?php echo $compare_listings[$index]['same_build'] ?></span></td><td><span style="width: 9px">&nbsp;</span></td>
              <td><span style="width: 218px" class="smallText"><?php echo $compare_listings[$index]['amenities'] ?></span></td><td align="center"><span style="width: 30px" class="smallText"><?php echo $compare_listings[$index]['monthly'] ?></span></td>
              <td><span style="width: 12px">&nbsp;</span></td><td align="center"><span style="width: 50px" class="smallText"><?php echo $compare_listings[$index]['price'] ?></span></td><td class="border"><span style="width: 5px">&nbsp;</span></td><td class="border" align="center"><span style="width: 22px" class="mediumText"><?php echo $compare_listings[$index]['loc'] ?></span></td>
              <td class="border" align="center"><span style="width: 22px" class="mediumText"><?php echo $compare_listings[$index]['bld'] ?></span></td><td class="border" align="center"><span style="width: 22px" class="mediumText"><?php echo $compare_listings[$index]['floor'] ?></span></td>
              <td class="border" align="center"><span style="width: 22px" class="mediumText"><?php echo $compare_listings[$index]['vws'] ?></span></td><td align="center"><span style="width: 35px" class="smallText"><span class="mediumText">-</span>&nbsp; <?php echo $compare_listings[$index]['space'] ?></span></td>
            </tr>
            
            <?php
            $index++;
            } ?>
          </tbody>
        </table>
        <div id="footer">
          <p class="dropDown pageNumber">PAGE <span id="num"><?php echo $i+2 ?></span></p>
        </div>
      <?php } ?>
    </div>
  </body>
</html>