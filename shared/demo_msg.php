<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: demo_msg.php,v 1.3 2004/06/28 16:44:44 jact Exp $
 */

/**
 * demo_msg.php
 ********************************************************************
 * Screen with demo message
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
?>
