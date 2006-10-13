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
 * @version   CVS: $Id: theme_use.php,v 1.11 2006/10/13 19:49:47 jact Exp $
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

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Setting_Query.php");

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
