<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: cancel_msg.php,v 1.3 2004/07/28 16:33:08 jact Exp $
 */

/**
 * cancel_msg.php
 ********************************************************************
 * Installation cancelled screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  require_once("../install/header.php"); // i18n l10n

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

  echo '<p class="note">' . _("OpenClinic install process has been cancelled.") . "</p>\n";

  echo '<p><a href="../install.html">' . _("View Install Instructions") . "</a></p>\n";

  echo '<p><a href="./index.php">' . _("Back to installation main page") . "</a></p>\n";

  require_once("../install/footer.php");
?>
