<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_pwd_reset_form.php,v 1.15 2005/07/30 18:57:26 jact Exp $
 */

/**
 * user_pwd_reset_form.php
 *
 * Reset screen of a password's user
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for get vars. Go back to users list if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idUser = intval($_GET["key"]);

  /**
   * Search database
   */
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->isError())
  {
    Error::query($userQ);
  }

  $numRows = $userQ->select($idUser);
  if ($userQ->isError())
  {
    $userQ->close();
    Error::query($userQ);
  }

  if ( !$numRows )
  {
    $userQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That user does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $user = $userQ->fetch();
  if ($userQ->isError())
  {
    Error::fetch($userQ, false);
  }
  else
  {
    $postVars["id_user"] = $idUser;
    $postVars["login"] = $user->getLogin();
    $postVars["pwd"] = $postVars["pwd2"] = "";
    //$postVars["pwd"] = $postVars["pwd2"] = $user->getPwd(); // no because it's encoded
    //Error::debug($user->getPwd());
  }
  $userQ->freeResult();
  $userQ->close();
  unset($userQ);
  unset($user);

  /**
   * Show page
   */
  $title = _("Reset User Password");
  // to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "pwd";
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

<form method="post" action="../admin/user_pwd_reset.php" onsubmit="return md5Login(this);">
  <div class="center">
<?php
  Form::hidden("id_user", "id_user", $postVars["id_user"]);
  Form::hidden("login", "login", $postVars["login"]);

  Form::hidden("md5", "md5");
  Form::hidden("md5_confirm", "md5_confirm");

  $thead = array(
    _("Reset User Password") => array('colspan' => 2)
  );

  $tbody = array();

  $row = _("Login") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= $postVars["login"];

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("pwd", _("Password") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("pwd", "pwd", 20, 20, $postVars["pwd"], $pageErrors["pwd"], "password");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = Form::strLabel("pwd2", _("Re-enter Password") . ":");
  $row .= OPEN_SEPARATOR;
  $row .= Form::strText("pwd2", "pwd2", 20, 20, $postVars["pwd2"], $pageErrors["pwd2"], "password");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    Form::strButton("button1", "button1", _("Submit"))
    . Form::strButton("return", "return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  HTML::table($thead, $tbody, $tfoot, $options);
?>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
