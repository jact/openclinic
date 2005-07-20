<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_del.php,v 1.8 2005/07/20 20:24:33 jact Exp $
 */

/**
 * user_del.php
 *
 * User deletion process
 *
 * Author: jact <jachavar@gmail.com>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  //$restrictInDemo = true;
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to users list if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $returnLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/Check.php");

  ////////////////////////////////////////////////////////////////////
  // Retrieving post vars
  ////////////////////////////////////////////////////////////////////
  $idUser = intval($_POST["id_user"]);
  $login = Check::safeText($_POST["login"]);

  ////////////////////////////////////////////////////////////////////
  // Delete user
  ////////////////////////////////////////////////////////////////////
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->isError())
  {
    Error::query($userQ);
  }

  $userQ->delete($idUser);
  if ($userQ->isError())
  {
    $userQ->close();
    Error::query($userQ);
  }
  $userQ->close();
  unset($userQ);

  ////////////////////////////////////////////////////////////////////
  // Redirect to user list to avoid reload problem
  ////////////////////////////////////////////////////////////////////
  $info = urlencode($login);
  header("Location: " . $returnLocation . "?deleted=Y&info=" . $info);
?>
