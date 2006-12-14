<?php
/**
 * theme_del_confirm.php
 *
 * Confirmation screen of a theme deletion process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_del_confirm.php,v 1.18 2006/12/14 22:29:25 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "themes";
  $returnLocation = "../admin/theme_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]) || empty($_GET["name"]) || empty($_GET["file"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  /**
   * Retrieving get vars
   */
  $idTheme = intval($_GET["key"]);
  $name = Check::safeText($_GET["name"]);
  $file = Check::safeText($_GET["file"]);

  /**
   * Show page
   */
  $title = _("Delete Theme");
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Themes") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon themeIcon");
  unset($links);

  /**
   * Form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/theme_del.php'));

  $tbody = array();

  $tbody[] = HTML::strMessage(sprintf(_("Are you sure you want to delete theme, %s?"), $name), OPEN_MSG_WARNING, false);

  $row = Form::strHidden("id_theme", $idTheme);
  $row .= Form::strHidden("name", $name);
  $row .= Form::strHidden("file", $file);
  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("delete", _("Delete"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
    . Form::generateToken()
  );

  $options = array('class' => 'center');

  Form::fieldset($title, $tbody, $tfoot, $options);

  HTML::end('form');

  require_once("../layout/footer.php");
?>
