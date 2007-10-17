/**
 * target_focus.js
 *
 * JavaScript functions for the form errors messages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: target_focus.js,v 1.1 2007/10/17 19:15:59 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 */

if (typeof addLoadEvent == 'function')
{
  addLoadEvent(addTargetFocus); // event.js included!
}

/**
 * void addTargetFocus(void)
 *
 * Adds event handlers to a.target elements
 *
 * @return void
 */
function addTargetFocus()
{
  if ( !document.getElementById || !document.createTextNode )
  {
    return;
  }

  var links = document.getElementsByTagName('a');
  for (var i = 0; i < links.length; i++)
  {
    if (links[i].className.match('target'))
    {
      links[i].onclick = function ()
      {
        document.getElementById(this.getAttribute('href').substring(1)).focus(); // without #
        return false;
      };
    }
  }
} // end of the 'addTargetFocus()' function
