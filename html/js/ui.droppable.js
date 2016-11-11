Index: ui.droppable.js
===================================================================
--- ui.droppable.js (revision 2077)
+++ ui.droppable.js (working copy)
@@ -211,7 +211,7 @@
                         m[i].offset = m[i].element.offset();
                         m[i].proportions = { width: m[i].element[0].offsetWidth, height: m[i].element[0].offsetHeight };
 
-                        if(type == "mousedown") m[i]._activate.call(m[i], event); //Activate the droppable if used directly from draggables
+                        if(type == t._eventNames.start) m[i]._activate.call(m[i], event); //Activate the droppable if used directly from draggables
 
                 }// JavaScript Document
