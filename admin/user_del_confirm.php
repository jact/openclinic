<?php
/**
 * user_del_confirm.php
 *
 * Confirmation screen of an user deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_del_confirm.php,v 1.22 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_user"]) || empty($_GET["login"]))
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
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["id_user"]);
  $login = Check::safeText($_GET["login"]);

  /**
   * Show page
   */
  $title = _("Delete User");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_user");
  unset($links);

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/user_del.php'));

  $tbody = array();

  $tbody[] = Msg::strWarning(sprintf(_("Are you sure you want to delete user, %s?"), $login));

  $row = Form::strHidden("id_user", $idUser);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  require_once("../layout/footer.php");
?>
