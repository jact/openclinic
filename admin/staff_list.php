<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_list.php,v 1.9 2004/07/28 17:40:00 jact Exp $
 */

/**
 * staff_list.php
 ********************************************************************
 * List of defined staff members screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
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
  if ($staffQ->isError())
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

  ////////////////////////////////////////////////////////////////////
  // Display insertion message if coming from new with a successful insert.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["added"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Staff member, %s, has been added."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display update message if coming from edit with a successful update.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["updated"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Staff member, %s, has been updated."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display deletion message if coming from del with a successful delete.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["deleted"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Staff member, %s, has been deleted."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // Display login used message.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["login"]) && isset($_GET["info"]))
  {
    showMessage(sprintf(_("Login, %s, already exists. The changes have no effect."), urldecode($_GET["info"])), OPEN_MSG_INFO);
  }

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

  if ($staffQ->isError())
  {
    $staffQ->close();
    showQueryError($staffQ);
  }

  //debug($_SESSION);

  echo '<p>';
  echo '<a href="../admin/staff_new_form.php?reset=Y&amp;type=A">';
  echo _("Add New Administrative") . '</a> | ';
  echo '<a href="../admin/staff_new_form.php?reset=Y&amp;type=D">';
  echo _("Add New Doctor") . '</a>';
  echo "</p>\n";

  echo "<hr />";

  echo '<h3>' . $listTitle . "</h3>\n";

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
  if ( !isset($_GET["type"]) || $_GET["type"] != 'A')
  {
    echo '<a href="../admin/staff_list.php?type=A">';
  }
  echo _("View only administratives");
  if ( !isset($_GET["type"]) || $_GET["type"] != 'A')
  {
    echo '</a>';
  }
  echo ' | ';
  if ( !isset($_GET["type"]) || $_GET["type"] != 'D')
  {
    echo '<a href="../admin/staff_list.php?type=D">';
  }
  echo _("View only doctors");
  if ( !isset($_GET["type"]) || $_GET["type"] != 'D')
  {
    echo '</a>';
  }
  echo "</p>\n";

  if ( !$numRows )
  {
    $staffQ->close();
    showMessage(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
    _("Function") => array('colspan' => 2),
    _("First Name"),
    _("Surname 1"),
    _("Surname 2"),
    _("Profile"),
    _("Login")
  );

  if ($viewType)
  {
    $thead[] = _("Type");
  }

  $tbody = array();
  while ($staff = $staffQ->fetch())
  {
    // to protect admin users
    if ($staff->getIdMember() < 2) //3)
    {
      continue;
    }

    $row = '<a href="../admin/staff_edit_form.php?key=' . $staff->getIdMember() . '&amp;reset=Y">' . _("edit") . '</a>';
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdMember() == $_SESSION["memberUser"])
    {
      $row .= "** " . _("del");
    }
    else
    {
      $row .= '<a href="../admin/staff_del_confirm.php?key=' . $staff->getIdMember() . '&amp;sur1=' . urlencode($staff->getSurname1()) . '&amp;sur2=' . urlencode($staff->getSurname2()) . '&amp;first=' . urlencode($staff->getFirstName()) . '">' . _("del") . '</a>';
    } // end if
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdUser() == 0 && $staff->getLogin() == "")
    {
      $row .= '* ' . _("create user");
    }
    elseif ($staff->getIdUser() == 0)
    {
      $row .= '<a href="../admin/user_new_form.php?id_member=' . $staff->getIdMember() . '&amp;login=' . $staff->getLogin() . '">' . _("create user") . '</a>';
    }
    else
    {
      $row .= '<a href="../admin/user_edit_form.php?key=' . $staff->getIdUser() . '&amp;reset=Y">' . _("edit user") . '</a>';
    } // end if
    $row .= OPEN_SEPARATOR;

    $row .= $staff->getFirstName();
    $row .= OPEN_SEPARATOR;

    $row .= $staff->getSurname1();
    $row .= OPEN_SEPARATOR;

    $row .= $staff->getSurname2();
    $row .= OPEN_SEPARATOR;

    $row .= $staff->getLogin();

    if ($viewType)
    {
      $row .= OPEN_SEPARATOR;
      switch ($staff->getMemberType())
      {
        case OPEN_ADMINISTRATIVE:
          $row .= _("Administrative");
          break;

        case OPEN_DOCTOR:
          $row .= _("Doctor");
          break;
      }
    }

    $tbody[] = explode(OPEN_SEPARATOR, $row);
  } // end while
  $staffQ->freeResult();
  $staffQ->close();
  unset($staffQ);
  unset($staff);

  showTable($thead, $tbody, null);

  showMessage('* ' . _("Note: To the create user function must have a correct login."));
  showMessage('** ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../shared/footer.php");
?>
