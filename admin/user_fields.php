<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_fields.php,v 1.3 2004/06/16 19:34:25 jact Exp $
 */

/**
 * user_fields.php
 ********************************************************************
 * Fields of user data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="2">
        <?php echo $title; ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <?php
          echo ($action == "new")
            ? _("Login") . ":"
            : '* <label for="login">' . _("Login") . ":" . "<label>\n";
        ?>
      </td>

      <td>
        <?php
          ($action == "new")
            ? print $postVars["login"]
            : showInputText("login", 20, 20, $postVars["login"], $pageErrors["login"]);
        ?>
      </td>
    </tr>

<?php
  if (isset($_GET["all"]))
  {
?>
    <tr>
      <td>
        <label for="old_pwd"><?php echo _("Current Password") . ":"; ?></label>
      </td>

      <td>
        <?php
          showInputText("old_pwd", 20, 20, $postVars["old_pwd"], $pageErrors["old_pwd"], "password");
          showInputHidden("md5_old");
        ?>
      </td>
    </tr>
<?php
  } // end if

  if ($action == "new" || isset($_GET["all"]))
  {
?>
    <tr>
      <td>
        <label for="pwd"><?php echo _("Password") . ":"; ?></label>
      </td>

      <td>
        <?php
          showInputText("pwd", 20, 20, $postVars["pwd"], $pageErrors["pwd"], "password");
          showInputHidden("md5");
        ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="pw2"><?php echo _("Re-enter Password") . ":"; ?></label>
      </td>

      <td>
        <?php
          showInputText("pwd2", 20, 20, $postVars["pwd2"], $pageErrors["pwd2"], "password");
          showInputHidden("md5_confirm");
        ?>
      </td>
    </tr>
<?php
  } // end if
?>

    <tr>
      <td>
        <label for="email"><?php echo _("Email") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("email", 40, 40, $postVars["email"], $pageErrors["email"]); ?>
      </td>
    </tr>

<?php
  if ( !isset($_GET["all"]) )
  {
?>
    <tr>
      <td>
        * <label for="actived"><?php echo _("Actived") . ":"; ?></label>
      </td>

      <td>
        <?php showCheckBox("actived", "actived", 1, $postVars["actived"] != ""); ?>
      </td>
    </tr>
<?php
  }
  else
  {
    showInputHidden("actived", "checked");
  }
?>

    <tr>
      <td>
        * <label for="id_theme"><?php echo _("Theme") . ":"; ?></label>
      </td>

      <td>
        <?php showSelect("theme_tbl", "id_theme", $postVars["id_theme"], "theme_name"); ?>
      </td>
    </tr>

<?php
  if ( !isset($_GET["all"]) )
  {
?>
    <tr>
      <td>
        * <label for="id_profile"><?php echo _("Profile") . ":"; ?></label>
      </td>

      <td>
        <?php
          if ($postVars["id_profile"] == "")
          {
            $postVars["id_profile"] = OPEN_PROFILE_DOCTOR; // by default doctor profile
          }

          $array = array(
            OPEN_PROFILE_ADMINISTRATOR => _("Administrator"),
            OPEN_PROFILE_ADMINISTRATIVE => _("Administrative"),
            OPEN_PROFILE_DOCTOR => _("Doctor")
          );

          showSelectArray("id_profile", $array, $postVars["id_profile"]);
          unset($array);
        ?>
      </td>
    </tr>
<?php
  }
  else
  {
    showInputHidden("id_profile", $postVars["id_profile"]);
  }
?>

    <tr>
      <td class="center" colspan="2">
        <?php
          showInputButton("button1", _("Submit"));
          showInputButton("button2", _("Reset"), "reset");
          showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
        ?>
      </td>
    </tr>
  </tbody>
</table>
