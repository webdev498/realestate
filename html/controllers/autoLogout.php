    <!-- dialog window markup -->
    <div id="dialog" title="Your session is about to expire!">
      <p style="display: none">
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>
        Your session will end in <span id="dialog-countdown" style="font-weight:bold"></span> seconds.<br/><br/>
      </p>

      <p style="display: none">Do you want to continue your session?</p>
    </div>

    <script type="text/javascript">
      // setup the dialog
      $("#dialog").dialog({
        autoOpen: false,
        modal: true,
        width: 430,
        height: 190,
        closeOnEscape: false,
        draggable: false,
        resizable: false,
        buttons: {
          'Yes, Keep Working': function(){
            $(this).dialog('close');
          },
          'No, End Session': function(){
            // fire whatever the configured onTimeout callback is.
            // using .call(this) keeps the default behavior of "this" being the warning
            // element (the dialog in this case) inside the callback.
            $.idleTimeout.options.onTimeout.call(this);
          }
        },
          open: function(){
            $("#dialog p").show();
          }
      });

      // cache a reference to the countdown element so we don't have to query the DOM for it on each ping.
      var $countdown = $("#dialog-countdown");

      // start the idle timer plugin
      $.idleTimeout('#dialog', 'div.ui-dialog-buttonpane button:first', {
        idleAfter: 1800, // 30 minutes
      	//idleAfter: 60, // 1 minute for testing
        pollingInterval: 2,
        keepAliveURL: 'keepalive.php',
        serverResponseEquals: 'OK',
        onTimeout: function(){
          window.location = "/users/logout.php";
        },
        onIdle: function(){
          $(this).dialog("open");
        },
        onCountdown: function(counter){
          $countdown.html(counter); // update the counter
        }
      });
    </script>
