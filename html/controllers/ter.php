<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false&libraries=geometry&v=3.7"></script>
<script src="http://code.jquery.com/jquery-1.9.0.min.js"></script>
<script src="/js/map.js" type="text/javascript"></script>
<script src="/js/ui.core.js" type="text/javascript"></script>
<script src="/js/jquery.cookie.js" type="text/javascript"></script>
<script>
  $(window).bind('orientationchange', function(event) {
    if (window.orientation == 90 || window.orientation == -90 || window.orientation == 270) {
      $('meta[name="viewport"]').attr('content', 'height=device-width,width=device-height,initial-scale=1.0,maximum-scale=1.0');
      $(window).resize();
      $('meta[name="viewport"]').attr('content', 'height=device-width,width=device-height,initial-scale=1.0,maximum-scale=2.0');
      $(window).resize();
    } else {
      $('meta[name="viewport"]').attr('content', 'height=device-height,width=device-width,initial-scale=1.0,maximum-scale=1.0');
      $(window).resize();
      $('meta[name="viewport"]').attr('content', 'height=device-height,width=device-width,initial-scale=1.0,maximum-scale=2.0');
      $(window).resize();
    }
  }).trigger('orientationchange');  
</script>