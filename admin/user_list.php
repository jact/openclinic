<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_list.php,v 1.3 2004/04/24 17:56:58 jact Exp $
 */

/**
 * user_list.php
 ********************************************************************
 * List of defined users screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * TODO: 2 acciones más: accesos y operaciones
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Description_Query.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/input_lib.php");

  $desQ = new Description_Query();
  $desQ->connect();
  if ($desQ->errorOccurred())
  {
    showQueryError($desQ);
  }

  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->errorOccurred())
  {
    showQueryError($userQ);
  }

  $userQ->selectLogins();
  if ($userQ->errorOccurred())
  {
    $userQ->close();
    showQueryError($userQ);
  }

  $array = null;
  while ($user = $userQ->fetchUser())
  {
    $array[$user->getIdMember() . '|' . $user->getLogin()] = $user->getLogin();
  }
  $userQ->freeResult();

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Users");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);
?>

<form method="post" action="../admin/user_new_form.php?reset=Y">
  <div>
    <table>
      <thead>
        <tr>
          <th>
            <?php echo _("Create New User"); ?>
          </th>
        </tr>
      </thead>

      <tbody>
        <tr>
          <td>
            <?php
              if (empty($array))
              {
                echo _("There no more users to create. You must create more staff members first.");
              }
              else
              {
                echo '<label for="id_member_login">';
                echo _("Select a login to create a new user") . ": ";
                echo "</label>\n";

                showSelectArray("id_member_login", $array);
                showInputButton("button1", _("Create"));
              }
            ?>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</form>

<?php
  $numRows = $userQ->select();
  if ($userQ->errorOccurred())
  {
    $userQ->close();
    showQueryError($userQ);
  }

  echo '<h3>' . _("Users List:") . "</h3>\n";

  if ($numRows == 0)
  {
    $userQ->close();
    echo '<p>' . _("No results found.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="4">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Login"); ?>
      </th>

      <th>
        <?php echo _("Email"); ?>
      </th>

      <th>
        <?php echo _("Actived"); ?>
      </th>

      <th>
        <?php echo _("Profile"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($user = $userQ->fetchUser())
  {
    // to protect 'big brother' user
    if ($user->getIdProfile() == 1 && $user->getIdUser() == 1)
    {
      continue;
    }
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../admin/user_edit_form.php?key=<?php echo $user->getIdUser(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <a href="../admin/user_pwd_reset_form.php?key=<?php echo $user->getIdUser(); ?>&amp;reset=Y"><?php echo _("pwd"); ?></a>
      </td>

      <td>
        <?php
          if (isset($_SESSION["userId"]) && $user->getIdUser() == $_SESSION["userId"])
          {
            echo '*' . _("del");
          }
          else
          {
        ?>
            <a href="../admin/user_del_confirm.php?key=<?php echo $user->getIdUser(); ?>&amp;login=<?php echo $user->getLogin(); ?>"><?php echo _("del"); ?></a>
        <?php
          } // end if
        ?>
      </td>

      <td>
        <a href="../admin/staff_edit_form.php?key=<?php echo $user->getIdMember(); ?>&amp;reset=Y"><?php echo _("edit member"); ?></a>
      </td>

      <td>
        <?php echo $user->getLogin(); ?>
      </td>

      <td>
        <?php echo $user->getEmail(); ?>
      </td>

      <td class="center">
        <?php echo ($user->isActived()) ? _("yes") : _("no"); ?>
      </td>

      <td class="center">
        <?php
          $desQ->select("profile_tbl", "id_profile", "description", $user->getIdProfile());
          if ($desQ->errorOccurred())
          {
            $desQ->close();
            showQueryError($desQ);
          }

          $des = $desQ->fetchDescription();
          if ($des)
          {
            echo $des->getDescription();
          }
        ?>
      </td>
    </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $userQ->freeResult();
  $userQ->close();
  unset($des);
  unset($desQ);
  unset($user);
  unset($userQ);
?>
  </tbody>
</table>

<?php
  echo '<p class="small">* ' . _("Note: The del function will not be applicated to the session user.") . "</p>\n";

  require_once("../shared/footer.php");
?>
