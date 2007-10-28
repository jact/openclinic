<?php
/**
 * theme_new.php
 *
 * Theme addition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_new.php,v 1.17 2007/10/28 20:06:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $errorLocation = "../admin/theme_new_form.php";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for post vars. Go back to form if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../model/Query/Theme.php");

  /**
   * Validate data
   */
  $theme = new Theme();

  require_once("../admin/theme_validate_post.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Insert new theme
   */
  $themeQ = new Query_Theme();
  $themeQ->connect();

  if ($themeQ->existCSSFile($theme->getCSSFile()))
  {
    FlashMsg:add(sprintf(_("Filename of theme, %s, already exists. The changes have no effect."), $theme->getName()));
  }
  else
  {
    $themeQ->insert($theme);
    FlashMsg::add(sprintf(_("Theme, %s, has been added."), $theme->getName()));
  }
  $themeQ->close();
  unset($themeQ);
  unset($theme);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
