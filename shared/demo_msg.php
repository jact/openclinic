<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: demo_msg.php,v 1.1 2004/01/29 15:10:54 jact Exp $
 */

/**
 * demo_msg.php
 ********************************************************************
 * Screen with demo message
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 16:10
 */

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("This is a demo version");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";
  echo '<p>' . _("This function is not available in this demo version of OpenClinic.") . "</p>\n";

  require_once("../shared/footer.php");
  exit();
?>
