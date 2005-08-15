<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: no_authorization.php,v 1.5 2005/08/15 16:32:00 jact Exp $
 */

/**
 * no_authorization.php
 *
 * No authorization screen for Admin tab
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "";

  require_once("../shared/read_settings.php");

  /**
   * Show page
   */
  $title = _("Not Authorization");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  HTML::message(sprintf(_("You are not authorized to use %s tab."), _("Admin")));

  require_once("../shared/footer.php");
?>
