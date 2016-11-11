<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="generator" content="2015.1.1.343"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="copyright" content="Licensed to Bellmarc Realty LLC" />
    <meta name="author" content="Bellmarc"/>
    <meta name="description" content="Patented apartment listing comparison tool." />
    <meta name="keywords" content="real estate, real estate ny, real estate manhattan, real estate new york, real estate services, real estate agents" />
    <meta name="keywords" content="Manhattan Apartments, Manhattan Condos, Manhattan apartments for rent, manhattan condos for rent, nyc apartments, ny apartments, apartments nyc, apartments in nyc, manhattan apartments for sale, manhattan condos for sale, luxury living nyc, manhattan luxury apartments"/>
    <link rel="profile" href="http://homepik.com">

    <link rel="stylesheet" href="/js/jquery/theme/jquery-ui-1.8.6.custom.min.css"/>
    <link rel="stylesheet" href="/js/jquery/theme/jquery.ui.multiselect.css" />
    <link rel="stylesheet" href="/js/jquery/theme/spinner.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/js/jqgrid/css/ui.jqgrid.css" />
    <link rel="stylesheet" type="text/css" media="print" href="/views/css/print.css" />
    <link rel="stylesheet" type="text/css" media="print" href="/views/css/printing.css" />
    <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.multi-select.js" type="text/javascript"></script>
    <script type="text/javascript"> touch = false;</script>
    <script src="http://code.jquery.com/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.8.17/jquery-ui.min.js"></script>
<script src="jquery.ui.touch-punch.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.ui.ipad.alt.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery-ui.touchpunch.js" type="text/javascript"></script>
    <script src="/js/jquery/spinner.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.qtip.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.ui.multiselect.min.js" type="text/javascript"></script>
    <script src="/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script src="/js/jqgrid/js/jquery.jqGrid.min.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery/jquery.validate.min.js"></script>
    <script src="/js/jquery.cookie.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery/bbq.jquery.js"></script>
    <script src="/js/jquery.idletimer.min.js" type="text/javascript"></script>
    <script src="/js/jquery.idletimeout.min.js" type="text/javascript"></script>
    <script src="/js/react/build/react.min.js"></script>
    <script src="/js/react/build/react-dom.min.js"></script>
    <script src="/js/react/build/JSXTransformer.js"></script>
    <!-- <script src="/js/react/build/browser.min.js"></script> -->
    <script type="text/javascript">
      {if $tplvar['loadSaved'] == true}
        var loadSaved = true;
      {else}
        var loadSaved = false;
      {/if}

      // SHOW RESULTS
      function show_results2(address) {
        $('#scroll-height').show();
        addressSearch(address);  // show the search results via AJAX
        $('#jqgh_price').css("font-weight", "bold").css("font-size", "20px").css("top","-2px");
        $("#thead-container").show();
        $(".thead-container.fixedTop").css('display', 'unset');
        $("#header").hide();
        $("#searchresultsheader").show();
        $("#addressSearch").attr("data-address", address);
        $("#scrollwrapper2").css( { 'position':'relative' } );
        var state = {};
        state[ 'listings' ] = 0;
        $.bbq.pushState( state );
      }

      // SHOW RESULTS
      function show_results() {
        $("#ajax-box").dialog({
          modal: true,
          height: 'auto',
          width: '600px',
          autoOpen: false,
          marginTop: '-10%',
          dialogClass: 'ajaxbox'
        });

        var name = $('.name').text();

        {if $tplvar['auth'] == 'agent' || $tplvar['auth'] == 'user' || $tplvar['auth'] == 'guest'}
          $(".search-criteria").hide();
          $(".criteriaNavBar").hide();
          $(".searchNavBar").show();
          $("#address-box").addClass('results');
          $('#scroll-height').show();
          $("#list").css("padding-top", "0px");
          jqgrid();  // show the search results via AJAX
          $('#jqgh_price').css("font-weight", "bold").css("font-size", "20px").css("top","-2px");
          $('#load_list').css("margin-top", "-235px");
          $(".thead-container").show();
          $(".thead-container.fixedTop").css('display', 'unset');
          $("#header").hide();
          $("#searchresultsheader").show();
          $("#editCriteriaOption").show();
          $("#criteriaFooter").hide();
          $("#searchResultsFooter").show();
          $("#new-thead").show();
          $(".table-header").show();
          $(".table-header").css("position", "absolute");
          $("#listings").show();
          $("#scrollwrapper2").show();
          $("#scrollwrapper2").css( { 'position':'relative' } );
          var state = {};
          state[ 'listings' ] = 0;
          $.bbq.pushState( state );
        {else}

        {/if}
      }
    </script>
    <script>
      window.onbeforeunload = function(){
        $( ".ui-dialog-overlay" ).addClass( "hide-div" );
      }
    </script>
    <script>
      if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {
        document.write('<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" />');
      }else{
        document.write('<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" />');
      }
    </script>
