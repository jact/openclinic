<?php
/**
 * user_pwd_reset_form.php
 *
 * Reset screen of a password's user
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_pwd_reset_form.php,v 1.22 2006/03/28 19:15:33 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for get vars. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["key"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../classes/User_Query.php");

    /**
     * Search database
     */
    $userQ = new User_Query();
    $userQ->connect();

    if ( !$userQ->select($idUser) )
    {
      $userQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That user does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetch();
    if ($user)
    {
      $formVar["id_user"] = $idUser;
      $formVar["login"] = $user->getLogin();
      $formVar["pwd"] = $formVar["pwd2"] = "";
      //$formVar["pwd"] = $formVar["pwd2"] = $user->getPwd(); // no because it's encoded
      //Error::debug($user->getPwd());
    }
    else
    {
      Error::fetch($userQ, false);
    }
    $userQ->freeResult();
    $userQ->close();
    unset($userQ);
    unset($user);
  }

  /**
   * Show page
   */
  $title = _("Reset User Password");
  $focusFormField = "pwd"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

  /**
   * Bread crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon userIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script src="../scripts/password.php" type="text/javascript"></script>

<?php
  /**
   * Edit form
   * @todo use user_fields.php with some controlling var to display adecuated fields
   */
  echo '<form method="post" action="../admin/user_pwd_reset.php" onsubmit="return md5Login(this);">' . "\n";

  Form::hidden("id_user", $formVar["id_user"]);
  Form::hidden("login", $formVar["login"]);

  Form::hidden("md5");
  Form::hidden("md5_confirm");

  $tbody = array();

  $row = _("Login") . ": ";
  $row .= '<strong>' . $formVar["login"] . '</strong>';

  $tbody[] = $row;

  $row = Form::strLabel("pwd", _("Password") . ":");
  $row .= Form::strPassword("pwd", 20,
    isset($formVar["pwd"]) ? $formVar["pwd"] : null,
    isset($formError["pwd"]) ? array('error' => $formError["pwd"]) : null
  );

  $tbody[] = $row;

  $row = Form::strLabel("pwd2", _("Re-enter Password") . ":");
  $row .= Form::strPassword("pwd2", 20,
    isset($formVar["pwd2"]) ? $formVar["pwd2"] : null,
    isset($formError["pwd2"]) ? array('error' => $formError["pwd2"]) : null
  );

  $tbody[] = $row;

  $tfoot = array(
    Form::strButton("button1", _("Submit"))
    . Form::strButton("return", _("Return"), "button", array('onclick' => "parent.location='" . $returnLocation . "'"))
  );

  Form::fieldset($title, $tbody, $tfoot);

  echo "</form>\n";

  /**
   * Destroy form values and errors
   */
  unset($_SESSION["formVar"]);
  unset($_SESSION["formError"]);

  require_once("../shared/footer.php");
?>
