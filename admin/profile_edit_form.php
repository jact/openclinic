<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: profile_edit_form.php,v 1.2 2004/04/23 20:36:50 jact Exp $
 */

/**
 * profile_edit_form.php
 ********************************************************************
 * Edition screen of profile information
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "admin";
  $nav = "profiles";

  require_once("../shared/read_settings.php");
  require_once("../lib/input_lib.php");
  require_once("../shared/login_check.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "description";

  ////////////////////////////////////////////////////////////////////
  // Checking for query string flag to read data from database.
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["key"]))
  {
    $key = $_GET["key"];
    $postVars["id_profile"] = $key;

    include_once("../classes/Description_Query.php");
    include_once("../lib/error_lib.php");

    $desQ = new Description_Query();
    $desQ->connect();
    if ($desQ->errorOccurred())
    {
      showQueryError($desQ);
    }

    $desQ->select("profile_tbl", "id_profile", "description", $key);
    if ($desQ->errorOccurred())
    {
      $desQ->close();
      showQueryError($desQ);
    }

    $des = $desQ->fetchDescription();
    if ( !$des )
    {
      showQueryError($desQ, false);
    }
    else
    {
      $postVars["description"] = $des->getDescription();
    }
    $desQ->freeResult();
    $desQ->close();
    unset($desQ);
    unset($des);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Edit Profile");
  require_once("../shared/header.php");

  $returnLocation = "../admin/profile_list.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Profiles") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "profiles.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../admin/profile_edit.php">
  <div>
<?php
  showInputHidden("id_profile", $postVars["id_profile"]);

  require_once("../admin/profile_fields.php");
?>
  </div>
</form>

<?php require_once("../shared/footer.php"); ?>
