<?php
/**
 * staff_edit_form.php
 *
 * Edition screen of a staff member
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_edit_form.php,v 1.27 2007/10/30 21:29:03 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "admin";
  $nav = "staff";
  $returnLocation = "../admin/staff_list.php";

  /**
   * Checking for query string. Go back to $returnLocation if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_member"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../config/environment.php");
  require_once("../auth/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to retrieve $formVar and $formError

  /**
   * Retrieving get vars
   */
  $idMember = intval($_GET["id_member"]);

  /**
   * Checking for $formError to read data from database
   */
  if ( !isset($formError) )
  {
    include_once("../model/Query/Staff.php");

    /**
     * Search database
     */
    $staffQ = new Query_Staff();
    $staffQ->connect();

    if ( !$staffQ->select($idMember) )
    {
      $staffQ->close();

      FlashMsg::add(_("That staff member does not exist."), OPEN_MSG_ERROR);
      header("Location: " . $returnLocation);
      exit();
    }

    $staff = $staffQ->fetch();
    if ($staff)
    {
      $formVar["id_member"] = $idMember;
      $formVar["member_type"] = $staff->getMemberType();
      $formVar["collegiate_number"] = $staff->getCollegiateNumber();
      $formVar["nif"] = $staff->getNIF();
      $formVar["first_name"] = $staff->getFirstName();
      $formVar["surname1"] = $staff->getSurname1();
      $formVar["surname2"] = $staff->getSurname2();
      $formVar["address"] = $staff->getAddress();
      $formVar["phone_contact"] = $staff->getPhone();
      $formVar["login"] = $staff->getLogin();
    }
    else
    {
      Error::fetch($staffQ, false);
    }
    $staffQ->freeResult();
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  /**
   * Show page
   */
  switch (substr($formVar["member_type"], 0, 1))
  {
    case "A":
      $title = _("Edit Administrative Information");
      break;

    case "D":
      $title = _("Edit Doctor Information");
      break;

    default:
      FlashMsg::add(sprintf(_("You are not authorized to use %s tab."), _("Admin")));
      header("Location: ../home/index.php");
      exit();
      break;
  }

  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  /**
   * Bread Crumb
   */
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon staffIcon");
  unset($links);

  require_once("../shared/form_errors_msg.php");

  /**
   * Edit form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../admin/staff_edit.php'));

  Form::hidden("id_member", $formVar["id_member"]);
  Form::hidden("member_type", $formVar["member_type"]);

  require_once("../admin/staff_fields.php");

  HTML::end('form');

  Msg::hint('* ' . _("Note: The fields with * are required."));

  HTML::para(HTML::strLink(_("Return"), $returnLocation));

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  require_once("../layout/footer.php");
?>
