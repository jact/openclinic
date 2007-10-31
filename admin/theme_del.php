<?php
/**
 * theme_del.php
 *
 * Theme deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_del.php,v 1.21 2007/10/31 19:18:19 jact Exp $
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
  require_once("../lib/Form.php");

  Form::compareToken($returnLocation);

  require_once("../model/Query/Theme.php");

  /**
   * Retrieving post vars
   */
  $idTheme = intval($_POST["id_theme"]);

  /**
   * Delete theme
   */
  $themeQ = new Query_Theme();
  $themeQ->connect();

  if ( !$themeQ->select($idTheme) )
  {
    FlashMsg::add(_("That theme does not exist."), OPEN_MSG_ERROR);
    header("Location: " . $returnLocation);
    exit();
  }

  $theme = $themeQ->fetch();

  $themeQ->delete($idTheme);

  $themeQ->close();
  unset($themeQ);

  if ( !in_array($theme->getCSSFile(), $reservedCSSFiles) )
  {
    @unlink(dirname(__FILE__) . '/../css/' . basename($theme->getCSSFile()));
  }

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(sprintf(_("Theme, %s, has been deleted."), $theme->getName()));
  header("Location: " . $returnLocation);
?>
