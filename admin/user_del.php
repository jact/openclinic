<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del.php,v 1.5 2004/07/10 16:00:08 jact Exp $
 */

/**
 * user_del.php
 ********************************************************************
 * User deletion process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  //$restrictInDemo = true;
  $returnLocation = "../admin/user_list.php";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_POST["id_user"]);
  $login = $_POST["login"];

  ////////////////////////////////////////////////////////////////////
  // Delete user
  ////////////////////////////////////////////////////////////////////
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->isError())
  {
    showQueryError($userQ);
  }

  $userQ->delete($idUser);
  if ($userQ->isError())
  {
    $userQ->close();
    showQueryError($userQ);
  }
  $userQ->close();
  unset($userQ);

  ////////////////////////////////////////////////////////////////////
  // Redirect to user list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($login);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
