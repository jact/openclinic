<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_new.php,v 1.3 2004/04/24 16:46:36 jact Exp $
 */

/**
 * user_new.php
 ********************************************************************
 * User addition process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "users";
  //$restrictInDemo = true;
  $errorLocation = "../admin/user_new_form.php";
  $returnLocation = "../admin/user_list.php";

  ////////////////////////////////////////////////////////////////////
  // Checking for post vars. Go back to form if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_POST) == 0)
  {
    header("Location: " . $errorLocation);
    exit();
  }

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/User_Query.php");
  require_once("../lib/error_lib.php");

  ////////////////////////////////////////////////////////////////////
  // Validate data
  ////////////////////////////////////////////////////////////////////
  $user = new User();

  require_once("../admin/user_validate_post.php");

  ////////////////////////////////////////////////////////////////////
  // Insert new user
  ////////////////////////////////////////////////////////////////////
  $userQ = new User_Query();
  $userQ->connect();
  if ($userQ->errorOccurred())
  {
    showQueryError($userQ);
  }

  print_r($user);
  if ($userQ->existLogin($user->getLogin(), $user->getIdMember()))
  {
    $loginUsed = true;
  }
  else
  {
    if ( !$userQ->insert($user) )
    {
      $userQ->close();
      showQueryError($userQ);
    }
  }
  $userQ->close();
  unset($userQ);

  ////////////////////////////////////////////////////////////////////
  // Destroy form values and errors
  ////////////////////////////////////////////////////////////////////
  unset($_SESSION["postVars"]);
  unset($_SESSION["pageErrors"]);

  ////////////////////////////////////////////////////////////////////
  // Show success page
  ////////////////////////////////////////////////////////////////////
  $title = _("Add New User");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Users") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "users.png");
  unset($links);

  echo '<p>';
  echo (isset($loginUsed) && $loginUsed)
    ? sprintf(_("Login, %s, already exists. The changes have no effect."), $user->getLogin())
    : sprintf(_("User, %s, has been added."), $user->getLogin());
  echo "</p>\n";

  unset($user);

  echo '<p><a href="' . $returnLocation . '">' . _("Return to users list") . "</a></p>\n";

  require_once("../shared/footer.php");
?>
