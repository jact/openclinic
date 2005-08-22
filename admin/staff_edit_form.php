<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_edit_form.php,v 1.14 2005/08/22 15:12:08 jact Exp $
 */

/**
 * staff_edit_form.php
 *
 * Edition screen of a staff member
 *
 * Author: jact <jachavar@gmail.com>
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
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../lib/Form.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  /**
   * Retrieving get vars
   */
  $idMember = intval($_GET["key"]);

  /**
   * Checking for query string flag to read data from database
   */
  if (isset($_GET["reset"]))
  {
    include_once("../classes/Staff_Query.php");

    /**
     * Search database
     */
    $staffQ = new Staff_Query();
    $staffQ->connect();
    if ($staffQ->isError())
    {
      Error::query($staffQ);
    }

    $numRows = $staffQ->select($idMember);
    if ($staffQ->isError())
    {
      $staffQ->close();
      Error::query($staffQ);
    }

    if ( !$numRows )
    {
      $staffQ->close();
      include_once("../shared/header.php");

      HTML::message(_("That staff member does not exist."), OPEN_MSG_ERROR);

      include_once("../shared/footer.php");
      exit();
    }

    $staff = $staffQ->fetch();
    if ($staffQ->isError())
    {
      Error::fetch($staffQ, false);
    }
    else
    {
      $postVars["id_member"] = $idMember;
      $postVars["member_type"] = $staff->getMemberType();
      $postVars["collegiate_number"] = $staff->getCollegiateNumber();
      $postVars["nif"] = $staff->getNIF();
      $postVars["first_name"] = $staff->getFirstName();
      $postVars["surname1"] = $staff->getSurname1();
      $postVars["surname2"] = $staff->getSurname2();
      $postVars["address"] = $staff->getAddress();
      $postVars["phone_contact"] = $staff->getPhone();
      $postVars["login"] = $staff->getLogin();
    }
    $staffQ->freeResult();
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  /**
   * Show page
   */
  switch (substr($postVars["member_type"], 0, 1))
  {
    case "A":
      $title = _("Edit Administrative Information");
      break;

    case "D":
      $title = _("Edit Doctor Information");
      break;

    default:
      header("Location: ../admin/no_authorization.php");
      exit();
      break;
  }

  $focusFormField = "nif"; // to avoid JavaScript mistakes in demo version
  require_once("../shared/header.php");

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
  echo '<form method="post" action="../admin/staff_edit.php">' . "\n";
  echo "<div>\n";

  Form::hidden("id_member", "id_member", $postVars["id_member"]);
  Form::hidden("member_type", "member_type", $postVars["member_type"]);

  require_once("../admin/staff_fields.php");

  echo "</div>\n</form>\n";

  HTML::message('* ' . _("Note: The fields with * are required."));

  require_once("../shared/footer.php");
?>
