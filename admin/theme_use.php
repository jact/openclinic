<?php
/**
 * theme_use.php
 *
 * Theme by default updating process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_use.php,v 1.10 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Setting_Query.php");

  /**
   * Update theme in use
   */
  $idTheme = intval($_POST["id_theme"]);

  $setQ = new Setting_Query();
  $setQ->connect();

  $setQ->updateTheme($idTheme);

  $setQ->close();
  unset($setQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  header("Location: " . $returnLocation);
?>
