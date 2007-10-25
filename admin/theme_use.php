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
 * @version   CVS: $Id: theme_use.php,v 1.12 2007/10/25 21:58:08 jact Exp $
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
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

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
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
