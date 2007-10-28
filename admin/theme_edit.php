<?php
/**
 * theme_edit.php
 *
 * Theme edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_edit.php,v 1.17 2007/10/28 20:06:56 jact Exp $
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

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/Theme.php");

  /**
   * Validate data
   */
  $errorLocation = "../admin/theme_edit_form.php?key=" . intval($_POST["id_theme"]); // controlling var
  $theme = new Theme();

  $theme->setId($_POST["id_theme"]);
  $_POST["id_theme"] = $theme->getId();

  require_once("../admin/theme_validate_post.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Update theme
   */
  $themeQ = new Query_Theme();
  $themeQ->connect();

  if ($themeQ->existCSSFile($theme->getCSSFile(), $theme->getId()))
  {
    FlashMsg:add(sprintf(_("Filename of theme, %s, already exists. The changes have no effect."), $theme->getName()));
  }
  else
  {
    $themeQ->update($theme);
    FlashMsg::add(sprintf(_("Theme, %s, has been updated."), $theme->getName()));
  }
  $themeQ->close();
  unset($themeQ);
  unset($theme);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
