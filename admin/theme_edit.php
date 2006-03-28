<?php
/**
 * theme_edit.php
 *
 * Theme edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_edit.php,v 1.12 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_theme"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Theme_Query.php");

  /**
   * Validate data
   */
  $errorLocation = "../admin/theme_edit_form.php?key=" . intval($_POST["id_theme"]); // controlling var
  $theme = new Theme();

  $theme->setId($_POST["id_theme"]);
  $_POST["id_theme"] = $theme->getId();

  require_once("../admin/theme_validate_post.php");

  /**
   * Update theme
   */
  $themeQ = new Theme_Query();
  $themeQ->connect();

  if ($themeQ->existCSSFile($theme->getCSSFile(), $theme->getId()))
  {
    $fileUsed = true;
  }
  else
  {
    $themeQ->update($theme);
  }
  $themeQ->close();
  unset($themeQ);

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($theme->getName());
  $returnLocation .= ((isset($fileUsed) && $fileUsed) ? "?file" : "?updated") . "=Y&info=" . $info;
  unset($theme);
  header("Location: " . $returnLocation);
?>
