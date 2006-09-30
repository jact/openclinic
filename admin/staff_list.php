<?php
/**
 * staff_list.php
 *
 * List of defined staff members screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_list.php,v 1.21 2006/09/30 16:42:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");

  /**
   * Retrieving get vars
   */
  $memberType = (isset($_GET["type"]) ? Check::safeText($_GET["type"]) : "");
  $info = (isset($_GET["info"]) ? urldecode(Check::safeText($_GET["info"])) : "");

  /**
   * Show page
   */
  $title = _("Staff Members");
  require_once("../shared/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  HTML::breadCrumb($links, "icon staffIcon");
  unset($links);

  /**
   * Display insertion message if coming from new with a successful insert.
   */
  if (isset($_GET["added"]) && !empty($info))
  {
    HTML::message(sprintf(_("Staff member, %s, has been added."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display update message if coming from edit with a successful update.
   */
  if (isset($_GET["updated"]) && !empty($info))
  {
    HTML::message(sprintf(_("Staff member, %s, has been updated."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display deletion message if coming from del with a successful delete.
   */
  if (isset($_GET["deleted"]) && !empty($info))
  {
    HTML::message(sprintf(_("Staff member, %s, has been deleted."), $info), OPEN_MSG_INFO);
  }

  /**
   * Display login used message.
   */
  if (isset($_GET["login"]) && !empty($info))
  {
    HTML::message(sprintf(_("Login, %s, already exists. The changes have no effect."), $info), OPEN_MSG_INFO);
  }

  $staffQ = new Staff_Query();
  $staffQ->connect();

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

  HTML::para(
    HTML::strLink(_("Add New Administrative"), '../admin/staff_new_form.php', array('type' => 'A'))
    . ' | '
    . HTML::strLink(_("Add New Doctor"), '../admin/staff_new_form.php', array('type' => 'D'))
  );

  HTML::rule();

  HTML::section(3, $listTitle);

  $relatedLinks = "";
  if ( !empty($memberType) )
  {
    $relatedLinks .= HTML::strLink(_("View all staff members"), '../admin/staff_list.php');
  }
  else
  {
    $relatedLinks .= _("View all staff members");
  }
  $relatedLinks .= ' | ';
  if ($memberType != 'A')
  {
    $relatedLinks .= HTML::strLink(_("View only administratives"), '../admin/staff_list.php', array('type' => 'A'));
  }
  else
  {
    $relatedLinks .= _("View only administratives");
  }
  $relatedLinks .= ' | ';
  if ($memberType != 'D')
  {
    $relatedLinks .= HTML::strLink(_("View only doctors"), '../admin/staff_list.php', array('type' => 'D'));
  }
  else
  {
    $relatedLinks .= _("View only doctors");
  }
  HTML::para($relatedLinks);

  if ( !$numRows )
  {
    $staffQ->close();
    HTML::message(_("No results found."), OPEN_MSG_INFO);
    include_once("../shared/footer.php");
    exit();
  }

  $thead = array(
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
  while ($staff = $staffQ->fetch())
  {
    // to protect admin users
    if ($staff->getIdMember() < 2) //3)
    {
      continue;
    }

    $row = HTML::strLink(_("edit"), '../admin/staff_edit_form.php', array('key' => $staff->getIdMember()));
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdMember() == $_SESSION["memberUser"])
    {
      $row .= "** " . _("del");
    }
    else
    {
      $row .= HTML::strLink(_("del"), '../admin/staff_del_confirm.php',
        array(
          'key' => $staff->getIdMember(),
          'sur1' => $staff->getSurname1(),
          'sur2' => $staff->getSurname2(),
          'first' => $staff->getFirstName()
        )
      );
    } // end if
    $row .= OPEN_SEPARATOR;

    if ($staff->getIdUser() == 0 && $staff->getLogin() == "")
    {
      $row .= '* ' . _("create user");
    }
    elseif ($staff->getIdUser() == 0)
    {
      $row .= HTML::strLink(_("create user"), '../admin/user_new_form.php',
        array(
          'id_member' => $staff->getIdMember(),
          'login' => $staff->getLogin()
        )
      );
    }
    else
    {
      $row .= HTML::strLink(_("edit user"), '../admin/user_edit_form.php', array('key' => $staff->getIdUser()));
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

  HTML::table($thead, $tbody, null);

  HTML::message('* ' . _("Note: To the create user function must have a correct login."));
  HTML::message('** ' . _("Note: The del function will not be applicated to the session user."));

  require_once("../shared/footer.php");
?>
