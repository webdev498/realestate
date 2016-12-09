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
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="/views/css/shared_components_bootstrap.css"/>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js" type="text/javascript"></script>
    <script src="https://webfonts.creativecloud.com/lato:n3,n7:default.js" type="text/javascript"></script>
    <script type="text/javascript"> touch = false;</script>
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
    <script src="/js/jquery/jquery-ui.min.js" type="text/javascript"></script>
    <script src="/js/jquery.cookie.min.js" type="text/javascript"></script>
    <script src="/js/jquery.idletimer.min.js" type="text/javascript"></script>
    <script src="/js/jquery.idletimeout.min.js" type="text/javascript"></script>
    <script src="/js/react/build/react.min.js"></script>
    <script src="/js/react/build/react-dom.min.js"></script>
    <script src="/js/react/build/browser.min.js"></script>
    <script src="/js/shared_components_bootstrap.js" type="text/babel"></script>
    <script>
      $.ajax({
        type: "POST",
        url: "/controllers/get-sessions.php",
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
