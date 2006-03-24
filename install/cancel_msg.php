<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: cancel_msg.php,v 1.5 2006/03/24 20:22:15 jact Exp $
 */

/**
 * cancel_msg.php
 *
 * Installation cancelled screen
 *
 * @author jact <jachavar@gmail.com>
 */

  require_once("../install/header.php"); // i18n l10n

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

  HTML::message(_("OpenClinic install process has been cancelled."));

  echo '<p>' . HTML::strLink(_("View Install Instructions"), '../install.html') . "</p>\n";

  echo '<p>' . HTML::strLink(_("Back to installation main page"), './index.php') . "</p>\n";

  require_once("../install/footer.php");
?>
