<?php
/**
 * staff_list.php
 *
 * List of defined staff members screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_list.php,v 1.31 2008/03/23 11:58:56 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATOR);

  require_once("../model/Query/Staff.php");

  /**
   * Retrieving get vars
   */
  $memberType = (isset($_GET["type"]) ? Check::safeText($_GET["type"]) : "");

  /**
   * Show page
   */
  $title = _("Staff Members");
  require_once("../layout/header.php");

  /**
   * Breadcrumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  echo HTML::breadcrumb($links, "icon icon_staff");
  unset($links);

  $staffQ = new Query_Staff();
  if ( !empty($memberType) )
  {
    $numRows = $staffQ->selectType($memberType);
    switch ($memberType)
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

  //Error::debug($_SESSION);

  echo HTML::para(
    HTML::link(_("Add New Administrative"), '../admin/staff_new_form.php', array('type' => 'A'))
    . ' | '
    . HTML::link(_("Add New Doctor"), '../admin/staff_new_form.php', array('type' => 'D'))
  );

  echo HTML::rule();

  echo HTML::section(3, $listTitle);

  $relatedLinks = "";
  if ( !empty($memberType) )
  {
    $relatedLinks .= HTML::link(_("View all staff members"), '../admin/staff_list.php');
  }
  else
  {
    $relatedLinks .= _("View all staff members");
  }
  $relatedLinks .= ' | ';
  if ($memberType != 'A')
  {
    $relatedLinks .= HTML::link(_("View only administratives"), '../admin/staff_list.php', array('type' => 'A'));
  }
  else
  {
    $relatedLinks .= _("View only administratives");
  }
  $relatedLinks .= ' | ';
  if ($memberType != 'D')
  {
    $relatedLinks .= HTML::link(_("View only doctors"), '../admin/staff_list.php', array('type' => 'D'));
  }
  else
  {
    $relatedLinks .= _("View only doctors");
  }
  echo HTML::para($relatedLinks);

  if ( !$numRows )
  {
    $staffQ->close();

    echo Msg::info(_("No results found."));
    include_once("../layout/footer.php");
    exit();
  }

  $thead = array(
    _("#"),
    _("Function") => array('colspan' => 3),
    _("First Name"),
    _("Surname 1"),
    _("Surname 2"),
    _("Login")
  );

  if ($viewType)
  {
    $thead[] = _("Type");
  }

  $tbody = array();
  $i = 0;
  while ($staff = $staffQ->fetch())
  {
    // to protect admin users
    if ($staff->getIdMember() < 2) //3)
    {
      continue;
    }

    $row = ++$i . '.';
    $row .= OPEN_SEPARATOR;

    $row .= HTML::link(
      HTML::image('../img/action_edit.png', _("edit")),
      '../admin/staff_edit_form.php',
      array('id_member' => $staff->getIdMember())
    );
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdMember() == $_SESSION['auth']['member_user'])
    {
      $row .= '**'; //"** " . _("del");
    }
    else
    {
      $row .= HTML::link(
        HTML::image('../img/action_delete.png', _("delete")),
        '../admin/staff_del_confirm.php',
        array(
          'id_member' => $staff->getIdMember(),
          'surname1' => $staff->getSurname1(),
          'surname2' => $staff->getSurname2(),
          'first_name' => $staff->getFirstName()
        )
      );
    } // end if
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdUser() == 0 && $staff->getLogin() == "")
    {
      $row .= '*'; //'* ' . _("create user");
    }
    elseif ($staff->getIdUser() == 0)
    {
      $row .= HTML::link(
        HTML::image('../img/action_add_user.png', _("create user")),
        '../admin/user_new_form.php',
        array(
          'id_member' => $staff->getIdMember(),
          'login' => $staff->getLogin()
        )
      );
    }
    else
    {
      $row .= HTML::link(
        HTML::image('../img/action_edit_user.png', _("edit user")),
        '../admin/user_edit_form.php',
        array('id_user' => $staff->getIdUser())
      );
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

  $options = array(
    0 => array('align' => 'right'),
    1 => array('align' => 'center'),
    2 => array('align' => 'center'),
    3 => array('align' => 'center')
  );

  echo HTML::table($thead, $tbody, null, $options);

  echo Msg::hint('* ' . _("Note: To the create user function must have a correct login."));
  echo Msg::hint('** ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../layout/footer.php");
?>
