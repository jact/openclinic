<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_pwd_reset_form.php,v 1.5 2004/07/06 17:36:25 jact Exp $
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
    $idUser = $_GET["key"];
    $postVars["id_user"] = $idUser;

    include_once("../classes/User_Query.php");
    include_once("../lib/error_lib.php");

    $userQ = new User_Query();
    $userQ->connect();
    if ($userQ->errorOccurred())
    {
      showQueryError($userQ);
    }

    $numRows = $userQ->select($idUser);
    if ($userQ->errorOccurred())
    {
      $userQ->close();
      showQueryError($userQ);
    }

    if ( !$numRows )
    {
      $userQ->close();
      include_once("../shared/header.php");

      echo '<p>' . _("That user does not exist.") . "</p>\n";

      include_once("../shared/footer.php");
      exit();
    }

    $user = $userQ->fetch();
    if ( !$user )
    {
      showFetchError(false);
    }
    else
    {
      $postVars["login"] = $user->getLogin();
      $postVars["pwd"] = $postVars["pwd2"] = "";
      //$postVars["pwd"] = $postVars["pwd2"] = $user->getPwd(); // no because it's encoded
      debug($user->getPwd());
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

<script type="text/javascript">
<!--/*--><![CDATA[/*<!--*/
function md5Login(f)
{
  if (f['md5'] != null)
  {
    f['md5'].value = hex_md5(f['pwd'].value);
    f['pwd'].value = '';
  }

  if (f['md5_confirm'] != null)
  {
    f['md5_confirm'].value = hex_md5(f['pwd2'].value);
    f['pwd2'].value = '';
  }

  return true;
}
/*]]>*///-->
</script>

<form method="post" action="../admin/user_pwd_reset.php" onsubmit="return md5Login(this);">
  <div class="center">
    <?php
      showInputHidden("id_user", $postVars["id_user"]);
      showInputHidden("login", $postVars["login"]);

      showInputHidden("md5");
      showInputHidden("md5_confirm");
    ?>

    <table>
      <thead>
        <tr>
          <th colspan="2">
            <?php echo _("Reset User Password"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php echo _("Login") . ":"; ?>
          </td>

          <td>
            <?php echo $postVars["login"]; ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="pwd"><?php echo _("Password") . ":"; ?></label>
          </td>

          <td>
            <?php showInputText("pwd", 20, 20, $postVars["pwd"], $pageErrors["pwd"], "password"); ?>
          </td>
        </tr>

        <tr>
          <td>
            <label for="pwd2"><?php echo _("Re-enter Password") . ":"; ?></label>
          </td>

          <td>
            <?php showInputText("pwd2", 20, 20, $postVars["pwd2"], $pageErrors["pwd2"], "password"); ?>
          </td>
        </tr>

        <tr>
          <td class="center" colspan="2">
            <?php
              showInputButton("button1", _("Submit"));
              showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
