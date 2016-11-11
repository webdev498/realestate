// JavaScript Document
Index: ui.core.js
===================================================================
--- ui.core.js (revision 2077)
+++ ui.core.js (working copy)
@@ -368,14 +368,42 @@
 
 
 /** Mouse Interaction Plugin **/
+var iPhone = navigator.userAgent.indexOf('iPhone') != -1;
 
 $.ui.mouse = {
+
+        _eventNames: {
+                start: 'mousedown',
+                drag: 'mousemove',
+                stop: 'mouseup'
+        },
+
+        _iPhoneEvent: function(event) {
+
+                if(event.type == 'touchend' && this._mouseStarted)
+                        event = this._prevEvent;
+
+                var t = event.originalEvent.touches;
+                return !iPhone || (t.length == 1 ? (this._prevEvent = $.extend(event, {
+                                target: t[0].target,
+                                pageX: t[0].pageX,
+                                pageY: t[0].pageY
+                        })) : false);
+
+        },
+        
         _mouseInit: function() {
                 var self = this;
 
+                iPhone && (this._eventNames = {
+                        start: 'touchstart',
+                        drag: 'touchmove',
+                        stop: 'touchend'
+                });
+
                 this.element
-                        .bind('mousedown.'+this.widgetName, function(event) {
-                                return self._mouseDown(event);
+                        .bind(this._eventNames.start+'.'+this.widgetName, function(event) {
+                                return self._iPhoneEvent(event) && self._mouseDown(event);
                         })
                         .bind('click.'+this.widgetName, function(event) {
                                 if(self._preventClickEvent) {
@@ -415,7 +443,7 @@
                 this._mouseDownEvent = event;
 
                 var self = this,
-                        btnIsLeft = (event.which == 1),
+                        btnIsLeft = (event.which == 1 || iPhone),
                         elIsCancel = (typeof this.options.cancel == "string" ? $(event.target).parents().add(event.target).filter(this.options.cancel).length : false);
                 if (!btnIsLeft || elIsCancel || !this._mouseCapture(event)) {
                         return true;
@@ -438,19 +466,19 @@
 
                 // these delegates are required to keep context
                 this._mouseMoveDelegate = function(event) {
-                        return self._mouseMove(event);
+                        return self._iPhoneEvent(event) && self._mouseMove(event);
                 };
                 this._mouseUpDelegate = function(event) {
-                        return self._mouseUp(event);
+                        return self._iPhoneEvent(event) && self._mouseUp(event);
                 };
                 $(document)
-                        .bind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
-                        .bind('mouseup.'+this.widgetName, this._mouseUpDelegate);
+                        .bind(this._eventNames.drag+'.'+this.widgetName, this._mouseMoveDelegate)
+                        .bind(this._eventNames.stop+'.'+this.widgetName, this._mouseUpDelegate);
 
                 // preventDefault() is used to prevent the selection of text here -
                 // however, in Safari, this causes select boxes not to be selectable
                 // anymore, so this fix is needed
-                ($.browser.safari || event.preventDefault());
+                (($.browser.safari && !iPhone) || event.preventDefault());
 
                 event.originalEvent.mouseHandled = true;
                 return true;
@@ -478,8 +506,8 @@
 
         _mouseUp: function(event) {
                 $(document)
-                        .unbind('mousemove.'+this.widgetName, this._mouseMoveDelegate)
-                        .unbind('mouseup.'+this.widgetName, this._mouseUpDelegate);
+                        .unbind(this._eventNames.drag+'.'+this.widgetName, this._mouseMoveDelegate)
+                        .unbind(this._eventNames.stop+'.'+this.widgetName, this._mouseUpDelegate);
 
                 if (this._mouseStarted) {
                         this._mouseStarted = false;