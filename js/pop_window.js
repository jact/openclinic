/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: pop_window.js,v 1.5 2006/03/26 15:33:35 jact Exp $
 */

/**
 * pop_window.php
 *
 * Contains functions to open new windows
 *
 * @author jact <jachavar@gmail.com>
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
