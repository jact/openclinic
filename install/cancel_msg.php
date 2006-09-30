<?php
/**
 * cancel_msg.php
 *
 * Installation cancelled screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: cancel_msg.php,v 1.6 2006/09/30 16:55:11 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../install/header.php"); // i18n l10n

  HTML::section(1, _("OpenClinic Installation:"));

  HTML::message(_("OpenClinic install process has been cancelled."));

  HTML::para(HTML::strLink(_("View Install Instructions"), '../install.html'));

  HTML::para(HTML::strLink(_("Back to installation main page"), './index.php'));

  require_once("../install/footer.php");
?>
