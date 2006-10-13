<?php
/**
 * theme_del.php
 *
 * Theme deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_del.php,v 1.16 2006/10/13 19:49:47 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true; // To prevent users' malice
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
  require_once("../model/Theme_Query.php");

  /**
   * Retrieving post vars
   */
  $idTheme = intval($_POST["id_theme"]);
  $name = Check::safeText($_POST["name"]);
  $file = Check::safeText($_POST["file"]);

  /**
   * Delete theme
   */
  $themeQ = new Theme_Query();
  $themeQ->connect();

  $themeQ->delete($idTheme);

  $themeQ->close();
  unset($themeQ);

  if ( !in_array($file, $reservedCSSFiles) )
  {
    @unlink(dirname($_SERVER['SCRIPT_FILENAME']) . '/../css/' . basename($file));
  }

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($name);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
