/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: pop_window.js,v 1.3 2004/04/24 15:12:28 jact Exp $
 */

/**
 * pop_window.php
 ********************************************************************
 * Contains functions to open new windows
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
