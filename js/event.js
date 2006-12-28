/**
 * event.js
 *
 * Contains functions to add and remove JS events
 * Cross-browser event handling for IE5+, NS6 and Mozilla
 *
 * @version   CVS: $Id: event.js,v 1.1 2006/12/28 16:32:48 jact Exp $
 * @author:   Scott Andrew LePera <scottandrew.com>
 * @author    Simon Willison <http://simon.incutio.com/archive/2004/05/26/addLoadEvent>
 * @author    Dean Edwards <http://dean.edwards.name/weblog/2005/12/js-tip1/>
 */

function addEvent(elm, evType, fn, useCapture)
{
  if (elm.addEventListener)
  {
    elm.addEventListener(evType, fn, useCapture);
    return true;
  }
  else if (elm.attachEvent)
  {
    var r = elm.attachEvent("on" + evType, fn);
    return r;
  }
  else
  {
    //alert("Handler could not be removed");
    return false;
  }
}

function removeEvent(elm, evType, fn, useCapture)
{
  if (elm.removeEventListener)
  {
    elm.removeEventListener(evType, fn, useCapture);
    return true;
  }
  else if (elm.detachEvent)
  {
    var r = elm.detachEvent("on" + evType, fn);
    return r;
  }
  else
  {
    //alert("Handler could not be removed");
    return false;
  }
}

/**
 * Simon Willison <http://simon.incutio.com/archive/2004/05/26/addLoadEvent>
 */
function addLoadEvent(func)
{
  var oldonload = window.onload;
  if (typeof window.onload != 'function')
  {
    window.onload = func;
  }
  else
  {
    window.onload = function()
    {
      oldonload();
      func();
    }
  }
}

/*
  Original idea by John Resig
  Tweaked by Scott Andrew LePera, Dean Edwards and Peter-Paul Koch
  Fixed for IE by Tino Zijdel (crisp)
    Note that in IE this will cause memory leaks and still doesn't quite function the same as in browsers that do support the W3C event model:
    - event execution order is not the same (LIFO in IE against FIFO)
    - functions attached to the same event on the same element multiple times will also get executed multiple times in IE
*/

/*function addEvent(obj, type, fn)
{
  if (obj.addEventListener)
  {
    obj.addEventListener(type, fn, false);
  }
  else if (obj.attachEvent)
  {
    obj["e" + type + fn] = fn;
    obj.attachEvent("on" + type, function() { obj["e" + type + fn](); });
  }
}

function removeEvent(obj, type, fn)
{
  if (obj.removeEventListener)
  {
    obj.removeEventListener(type, fn, false);
  }
  else if (obj.detachEvent)
  {
    obj.detachEvent("on" + type, obj["e" + type + fn]);
    obj["e" + type + fn] = null;
  }
}*/

/**
 * Dean Edwards <http://dean.edwards.name/weblog/2005/12/js-tip1/>
 */
/*var addEvent;
if (document.addEventListener)
{
  addEvent = function(element, type, handler)
  {
    element.addEventListener(type, handler, null);
  };
}
else if (document.attachEvent)
{
  addEvent = function(element, type, handler)
  {
    element.attachEvent("on" + type, handler);
  };
}
else
{
  addEvent = new Function; // not supported
}*/
