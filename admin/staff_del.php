<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_del.php,v 1.11 2006/01/23 22:59:03 jact Exp $
 */

/**
 * staff_del.php
 *
 * Staff member deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  //$restrictInDemo = true;
  $returnLocation = "../admin/staff_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Staff_Query.php");

  /**
   * Retrieving post var
   */
  $idMember = intval($_POST["id_member"]);

  /**
   * Delete staff member
   */
  $staffQ = new Staff_Query();
  $staffQ->connect();

  if ( !$staffQ->select($idMember) )
  {
    $staffQ->close();
    include_once("../shared/header.php");

    HTML::message(_("That staff member does not exist."), OPEN_MSG_ERROR);

    include_once("../shared/footer.php");
    exit();
  }

  $staff = $staffQ->fetch();
  if ( !$staff )
  {
    $staffQ->close();
    Error::fetch($staffQ);
  }

  $staffQ->delete($staff->getIdMember(), $staff->getIdUser());

  $staffQ->close();
  unset($staffQ);

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  $info = urlencode($staff->getFirstName() . " " . $staff->getSurname1() . " " . $staff->getSurname2());
  unset($staff);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
