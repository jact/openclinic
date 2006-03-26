<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_del.php,v 1.14 2006/03/26 14:47:34 jact Exp $
 */

/**
 * theme_del.php
 *
 * Theme deletion process
 *
 * @author jact <jachavar@gmail.com>
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

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");

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
