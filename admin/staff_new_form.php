<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: staff_new_form.php,v 1.4 2004/06/06 11:38:17 jact Exp $
 */

/**
 * staff_new_form.php
 ********************************************************************
 * Addition screen of a staff member
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
  require_once("../lib/input_lib.php");
  require_once("../shared/get_form_vars.php"); // to clean $postVars and $pageErrors

  // after login_check inclusion to avoid JavaScript mistakes in demo version
  $focusFormName = "forms[0]";
  $focusFormField = "nif";

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  (isset($_GET["type"]))
    ? $memberType = $_GET["type"]
    : $memberType = "A"; // Administrative by default

  switch ($memberType)
  {
    case "a":
    case "A":
      $title = _("Add New Administrative Information");
      $typeValue = OPEN_ADMINISTRATIVE;
      break;

    case "d":
    case "D":
      $title = _("Add New Doctor Information");
      $typeValue = OPEN_DOCTOR;
      break;
  }

  require_once("../shared/header.php");

  $returnLocation = "../admin/staff_list.php";

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    _("Staff Members") => $returnLocation,
    $title => ""
  );
  showNavLinks($links, "staff.png");
  unset($links);

  require_once("../shared/form_errors_msg.php");
?>

<form method="post" action="../admin/staff_new.php?type=<?php echo $memberType; ?>">
  <div>
<?php
  showInputHidden("member_type", $typeValue);

  require_once("../admin/staff_fields.php");
?>
  </div>
</form>

<?php
  echo '<p class="advice">* ' . _("Note: The fields with * are required.") . "</p>\n";

  require_once("../shared/footer.php");
?>
