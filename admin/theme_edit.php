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
 * @version   CVS: $Id: theme_edit.php,v 1.20 2007/12/15 12:46:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_theme"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  /**
   * Validate data
   */
  $errorLocation = "../admin/theme_edit_form.php?key=" . intval($_POST["id_theme"]); // controlling var
  require_once("../model/Query/Theme.php");
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
  if ($themeQ->existCssFile($theme->getCssFile(), $theme->getId()))
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
