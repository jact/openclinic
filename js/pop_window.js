/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: pop_window.js,v 1.1 2004/01/29 15:10:16 jact Exp $
 */

/**
 * pop_window.php
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 16:10
 */

/**
 ********************************************************************
 * Show a new pop window
 ********************************************************************
 * @param string the url
 */
function popSecondary(url)
{
  var secondaryWin = window.open(url, "_blank", "width=535,height=400,resizable=yes,scrollbars=yes");

  return false;
} // end of the 'popSecondary()' function
