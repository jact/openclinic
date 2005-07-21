<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: demo_msg.php,v 1.5 2005/07/21 16:57:13 jact Exp $
 */

/**
 * demo_msg.php
 *
 * Screen with demo message
 *
 * Author: jact <jachavar@gmail.com>
 */

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("This is a demo version");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  HTML::message(_("This function is not available in this demo version of OpenClinic."), OPEN_MSG_INFO);

  require_once("../shared/footer.php");
?>
