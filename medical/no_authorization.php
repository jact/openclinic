<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: no_authorization.php,v 1.2 2004/04/24 14:52:14 jact Exp $
 */

/**
 * no_authorization.php
 ********************************************************************
 * No authorization screen for Medical Records tab
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "";

  require_once("../shared/read_settings.php");

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Not Authorization");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";
  echo '<p class="center">';
  echo sprintf(_("You are not authorized to use %s tab."), _("Medical Records")) . "</p>\n";

  require_once("../shared/footer.php");
?>
