<?php
/**
 * theme_use.php
 *
 * Theme by default updating process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_use.php,v 1.17 2007/12/07 16:50:50 jact Exp $
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

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../lib/Form.php");

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Update theme in use
   */
  $idTheme = intval($_POST["id_theme"]);

  require_once("../model/Query/Setting.php");
  $setQ = new Query_Setting();
  $setQ->updateTheme($idTheme);

  $setQ->close();
  unset($setQ);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  FlashMsg::add(_("Default theme has been changed."));
  header("Location: " . $returnLocation);
?>
