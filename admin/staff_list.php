<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_list.php,v 1.1 2004/03/24 19:53:46 jact Exp $
 */

/**
 * staff_list.php
 ********************************************************************
 * List of defined staff members screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:53
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "staff";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");
  require_once("../lib/error_lib.php");

  $staffQ = new Staff_Query();
  $staffQ->connect();
  if ($staffQ->errorOccurred())
  {
    showQueryError($staffQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Staff Members");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "staff.png");
  unset($links);

  if (isset($_GET["type"]))
  {
    $numRows = $staffQ->selectType($_GET["type"]);
    switch ($_GET["type"])
    {
      case 'A':
        $listTitle = _("Administratives:");
        break;

      case 'D':
        $listTitle = _("Doctors:");
        break;
    }
    $viewType = false;
  }
  else
  {
    $numRows = $staffQ->select();
    $listTitle = _("Staff Members") . ":";
    $viewType = true;
  }

  if ($staffQ->errorOccurred())
  {
    $staffQ->close();
    showQueryError($staffQ);
  }

  debug($_SESSION);

  echo '<p>';
  echo '<a href="../admin/staff_new_form.php?reset=Y&amp;type=A">';
  echo _("Add New Administrative") . '</a> | ';
  echo '<a href="../admin/staff_new_form.php?reset=Y&amp;type=D">';
  echo _("Add New Doctor") . '</a>';
  echo "</p>\n";

  echo '<p>';
  if (isset($_GET["type"]))
  {
    echo '<a href="../admin/staff_list.php">';
  }
  echo _("View all staff members");
  if (isset($_GET["type"]))
  {
    echo '</a>';
  }
  echo ' | ';
  if ($_GET["type"] != 'A')
  {
    echo '<a href="../admin/staff_list.php?type=A">';
  }
  echo _("View only administratives");
  if ($_GET["type"] != 'A')
  {
    echo '</a>';
  }
  echo ' | ';
  if ($_GET["type"] != 'D')
  {
    echo '<a href="../admin/staff_list.php?type=D">';
  }
  echo _("View only doctors");
  if ($_GET["type"] != 'D')
  {
    echo '</a>';
  }
  echo "</p>\n";

  echo '<h3>' . $listTitle . "</h3>\n";

  if ( !$numRows )
  {
    $staffQ->close();
    echo '<p>' . _("No results found.") . "</p>\n";
    include_once("../shared/footer.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="3">
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("First Name"); ?>
      </th>

      <th>
        <?php echo _("Surname 1"); ?>
      </th>

      <th>
        <?php echo _("Surname 2"); ?>
      </th>

      <th>
        <?php echo _("Login"); ?>
      </th>

<?php
  if ($viewType)
  {
    echo '<th>' . _("Type") . "</th>\n";
  }
?>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($staff = $staffQ->fetchStaff())
  {
    // to protect admin users
    if ($staff->getIdMember() < 2) //3)
    {
      continue;
    }
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../admin/staff_edit_form.php?key=<?php echo $staff->getIdMember(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <?php
          if ($staff->getIdMember() == $_SESSION["memberUser"])
          {
            echo "** " . _("del");
          }
          else
          {
        ?>
            <a href="../admin/staff_del_confirm.php?key=<?php echo $staff->getIdMember(); ?>&amp;sur1=<?php echo urlencode($staff->getSurname1()); ?>&amp;sur2=<?php echo urlencode($staff->getSurname2()); ?>&amp;first=<?php echo urlencode($staff->getFirstName()); ?>"><?php echo _("del"); ?></a>
        <?php
        } // end if
        ?>
      </td>

      <td>
        <?php
          if ($staff->getIdUser() == 0 && $staff->getLogin() == "")
          {
            echo '* ' . _("create user");
          }
          elseif ($staff->getIdUser() == 0)
          {
        ?>
            <a href="../admin/user_new_form.php?id_member=<?php echo $staff->getIdMember(); ?>&amp;login=<?php echo $staff->getLogin(); ?>"><?php echo _("create user"); ?></a>
        <?php
          }
          else
          {
        ?>
            <a href="../admin/user_edit_form.php?key=<?php echo $staff->getIdUser(); ?>&amp;reset=Y"><?php echo _("edit user"); ?></a>
        <?php
          } // end if
        ?>
      </td>

      <td>
        <?php echo $staff->getFirstName(); ?>
      </td>

      <td>
        <?php echo $staff->getSurname1(); ?>
      </td>

      <td>
        <?php echo $staff->getSurname2(); ?>
      </td>

      <td>
        <?php echo $staff->getLogin(); ?>
      </td>

<?php
    if ($viewType)
    {
      echo '<td>';
      switch ($staff->getMemberType())
      {
        case "Administrative":
          echo _("Administrative");
          break;

        case "Doctor":
          echo _("Doctor");
          break;
      }
      echo "</td>\n";
    }

    echo "</tr>\n";

    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $staffQ->freeResult();
  $staffQ->close();
  unset($staffQ);
  unset($staff);
?>
  </tbody>
</table>

<?php
  echo '<p class="small">* ' . _("Note: To the create user function must have a correct login.") . "</p>\n";
  echo '<p class="small">** ' . _("Note: The del function will not be applicated to the session user.") . "</p>\n";

  require_once("../shared/footer.php");
?>
