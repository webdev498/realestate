<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta charset="UTF-8">
    <meta name="google-site-verification" content="9_HsDzaGzRzyK9eWUxt0VJuVv6vLAT-m1fBE5cSvaNU" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta name="generator" content="2015.1.1.343"/>
    <meta name="copyright" content="Licensed to Bellmarc Realty LLC" />
    <meta name="author" content="Bellmarc"/>
    <meta name="description" content="Patented apartment listing comparison tool." />
    <meta name="keywords" content="real estate, real estate ny, real estate manhattan, real estate new york, real estate services, real estate agents" />
    <meta name="keywords" content="Manhattan Apartments, Manhattan Condos, Manhattan apartments for rent, manhattan condos for rent, nyc apartments, ny apartments, apartments nyc, apartments in nyc, manhattan apartments for sale, manhattan condos for sale, luxury living nyc, manhattan luxury apartments"/>
    <meta name="robots" content="index, follow">
    <?php include_once("analyticstracking.php") ?>

    <link rel="profile" href="http://homepik.com">
    <link rel="stylesheet" href="/js/jquery/theme/jquery-ui-1.8.6.custom.min.css"/>
    <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
    <?php 
      include_css("/views/css/index-mobile.css");
      include_css("/views/css/shared_components_bootstrap.css");
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css">

    <script src="https://webfonts.creativecloud.com/lato:n3,n7:default.js" type="text/javascript"></script>
    <script type="text/javascript"> touch = false;</script>
    <script src="/js/jquery/jquery-1.10.2.min.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
    <script src="/js/jquery.cookie.js" type="text/javascript"></script>
    <?php include_javascript("/static/js/bundle/app.js"); ?>
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
