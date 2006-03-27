/**
 * pop_window.php
 *
 * Contains functions to open new windows
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: pop_window.js,v 1.6 2006/03/27 18:32:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * bool popSecondary(string url)
 *
 * Show a new pop window
 *
 * @param string the url
 * @return bool always false
 */
function popSecondary(url)
{
  var secondaryWin = window.open(url, "_blank", "width=680,height=450,resizable=yes,scrollbars=yes");

  return false;
} // end of the 'popSecondary()' function
