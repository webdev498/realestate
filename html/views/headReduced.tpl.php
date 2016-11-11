

    <link rel="stylesheet" href="/js/jquery/theme/jquery.ui.multiselect.css" />
    <link rel="stylesheet" href="/js/jquery/theme/spinner.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="/js/jqgrid/css/ui.jqgrid.css" />
    {php}
      include_css("/views/css/print.css");
      include_css("/views/css/printing.css");
    {/php}
    <link href='//fonts.googleapis.com/css?family=Lato:300' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.multi-select.js" type="text/javascript"></script>
    <script type="text/javascript"> 
      touch = false;
      $.curCSS = function (element, attrib, val) {
        $(element).css(attrib, val);
      };
    </script> 

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
    {php}
      include_javascript("/static/js/bundle/search.js");
    {/php}
    <script src="/js/jquery.cookie.js" type="text/javascript"></script>
    <script type="text/javascript" src="/js/jquery/bbq.jquery.js"></script>
    <script src="/js/jquery.idletimer.min.js" type="text/javascript"></script>
    <script src="/js/jquery.idletimeout.min.js" type="text/javascript"></script>
    <script type="text/javascript">
      {if $tplvar['loadSaved'] == true}
        var loadSaved = true;
      {else}
        var loadSaved = false;
      {/if}

      // SHOW RESULTS
      function show_results2(address) {
        $("#address-box").addClass('results');
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
