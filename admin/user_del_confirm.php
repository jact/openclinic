<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del_confirm.php,v 1.13 2006/03/26 14:47:45 jact Exp $
 */

/**
 * user_del_confirm.php
 *
 * Confirmation screen of an user deletion process
 *
 * @author jact <jachavar@gmail.com>
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
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["login"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["key"]);
  $login = Check::safeText($_GET["login"]);

  /**
   * Show page
   */
  $title = _("Delete User");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  /**
   * Form
   */
  echo '<form method="post" action="../admin/user_del.php">' . "\n";

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete user, %s?"), $login), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_user", $idUser);
  $row .= Form::strHidden("login", $login);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  echo "</form>\n";

  require_once("../shared/footer.php");
?>
