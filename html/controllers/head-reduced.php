<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyright" content="Licensed to Bellmarc Realty LLC" />
    <meta name="author" content="Bellmarc"/>
    <meta name="description" content="Patented apartment listing comparison tool." />
    <meta name="keywords" content="real estate, real estate ny, real estate manhattan, real estate new york, real estate services, real estate agents" />
    <meta name="keywords" content="Manhattan Apartments, Manhattan Condos, Manhattan apartments for rent, manhattan condos for rent, nyc apartments, ny apartments, apartments nyc, apartments in nyc, manhattan apartments for sale, manhattan condos for sale, luxury living nyc, manhattan luxury apartments"/>
    <link rel="profile" href="http://homepik.com">

    <link rel="stylesheet" href="/js/jquery/theme/jquery-ui-1.8.6.custom.min.css"/>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">

    <script src="https://webfonts.creativecloud.com/lato:n3,n7:default.js" type="text/javascript"></script>
    <script type="text/javascript"> touch = false;</script>
    <script src="/js/jquery/jquery-1.10.2.min.js"></script>
    <?php include_javascript("/static/js/bundle/app.js"); ?>
    <script src="/js/jquery/jquery.ui.ipad.alt.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery-ui.touchpunch.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery.qtip.min.js" type="text/javascript"></script>
    <script src="/js/jqgrid/js/i18n/grid.locale-en.js" type="text/javascript"></script>
    <script>
      $.ajax({
        type: "POST",
        url: "http://homepik.com/controllers/get-sessions.php",
        data: {"getLoadSaved": "true"},
        success: function (data) {
          var info = JSON.parse(data);
          if (info == true) { var loadSaved = true; }
          else { var loadSaved = false; }
        }
      });

      if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)) || (navigator.userAgent.match(/iPad/i))) {
        document.write('<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1" />');
      }
    </script>
