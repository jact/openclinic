<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_pwd_reset_form.php,v 1.10 2004/10/04 18:04:18 jact Exp $
 */

/**
 * user_pwd_reset_form.php
 ********************************************************************
 * Reset screen of a password's user
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "pwd";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $idUser = intval($_GET["key"]);
    $postVars["id_user"] = $idUser;

    include_once("../classes/User_Query.php");
    include_once("../lib/error_lib.php");

    $userQ = new User_Query();
    $userQ->connect();
    if ($userQ->isError())
    {
      showQueryError($userQ);
    }

    $numRows = $userQ->select($idUser);
    if ($userQ->isError())
    {
      $userQ->close();
      showQueryError($userQ);
    }

    if ( !$numRows )
    {
      $userQ->close();
      include_once("../shared/header.php");

      showMessage(_("That user does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetch();
    if ($userQ->isError())
    {
      showFetchError($userQ, false);
    }
    else
    {
      $postVars["login"] = $user->getLogin();
      $postVars["pwd"] = $postVars["pwd2"] = "";
      //$postVars["pwd"] = $postVars["pwd2"] = $user->getPwd(); // no because it's encoded
      //debug($user->getPwd());
    }
    $userQ->freeResult();
    $userQ->close();
    unset($userQ);
    unset($user);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Reset User Password");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<script src="../scripts/md5.js" type="text/javascript"></script>

<script src="../scripts/password.php" type="text/javascript"></script>

<form method="post" action="../admin/user_pwd_reset.php" onsubmit="return md5Login(this);">
  <div class="center">
<?php
  showInputHidden("id_user", $postVars["id_user"]);
  showInputHidden("login", $postVars["login"]);

  showInputHidden("md5");
  showInputHidden("md5_confirm");

  $thead = array(
    _("Reset User Password") => array('colspan' => 2)
  );

  $tbody = array();

  $row = _("Login") . ":";
  $row .= OPEN_SEPARATOR;
  $row .= $postVars["login"];

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="pwd">' . _("Password") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("pwd", 20, 20, $postVars["pwd"], $pageErrors["pwd"], "password");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $row = '<label for="pwd2">' . _("Re-enter Password") . ":" . "</label>\n";
  $row .= OPEN_SEPARATOR;
  $row .= htmlInputText("pwd2", 20, 20, $postVars["pwd2"], $pageErrors["pwd2"], "password");

  $tbody[] = explode(OPEN_SEPARATOR, $row);

  $tfoot = array(
    htmlInputButton("button1", _("Submit"))
    . htmlInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"')
  );

  $options = array(
    'shaded' => false,
    'tfoot' => array('align' => 'center')
  );

  showTable($thead, $tbody, $tfoot, $options);
?>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
