<?php
/**
 * index.php
 *
 * Summary page of the Home tab
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.12 2006/03/27 18:35:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "home";
  $nav = "home";

  require_once("../shared/read_settings.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("Welcome to OpenClinic");
  require_once("../shared/header.php");

  echo '<h1>' . $title . "</h1>\n";

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("User, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display login used message.
   */
  if (isset($_GET["login"]) && !empty($info))
  {
    HTML::message(sprintf(_("Login, %s, already exists. The changes have no effect."), $info), OPEN_MSG_INFO);
  }

  echo '<p>' . _("OpenClinic is an easy to use, open source, medical records system.") . "</p>\n";
  echo '<p>' . _("When you select any of the following tabs you will be prompted to login.") . "</p>\n";

  echo '<h2 class="bigIcon medicalIcon">' . HTML::strLink(_("Medical Records"), '../medical/index.php') . "</h2>\n";
  echo '<p>' . _("Use this tab to manage your patient's medical records.") . "</p>\n";
  echo '<p>' . _("Patient's Administration:") . "</p>\n";

  echo "<ul>\n";
  echo '<li>' . _("Search, new, delete, edit") . "</li>\n";
  echo '<li>' . _("Social Data") . "</li>\n";
  echo '<li>' . _("Clinic History") . "</li>\n";
  echo '<li>' . _("Problem Reports") . "</li>\n";
  echo "</ul>\n";

  echo "<hr />\n";

  echo '<h2 class="bigIcon adminIcon">' . HTML::strLink(_("Admin"), '../admin/index.php') . "</h2>\n";
  echo '<p>' . _("Use this tab to manage administrative options.") . "</p>\n";

  echo "<ul>\n";
  echo '<li>' . _("Staff members") . "</li>\n";
  echo '<li>' . _("Config settings") . "</li>\n";
  echo '<li>' . _("Clinic themes editor") . "</li>\n";
  echo '<li>' . _("System users") . "</li>\n";
  echo '<li>' . _("Dumps") . "</li>\n";
  echo '<li>' . _("Logs") . "</li>\n";
  echo "</ul>\n";

  require_once("../shared/footer.php");
?>
