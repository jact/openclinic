<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: profile_list.php,v 1.1 2004/03/24 19:59:54 jact Exp $
 */

/**
 * profile_list.php
 ********************************************************************
 * List of defined profiles screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 20:59
 */

  $tab = "admin";
  $nav = "profiles";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Description_Query.php");
  require_once("../lib/error_lib.php");

  $desQ = new Description_Query();
  $desQ->connect();
  if ($desQ->errorOccurred())
  {
    showQueryError($desQ);
  }

  $desQ->select("profile_tbl", "id_profile", "description");
  if ($desQ->errorOccurred())
  {
    $desQ->close();
    showQueryError($desQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show page
  ////////////////////////////////////////////////////////////////////
  $title = _("Profiles");
  require_once("../shared/header.php");

  ////////////////////////////////////////////////////////////////////
  // Navigation links
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/navigation_links.php");
  $links = array(
    _("Admin") => "../admin/index.php",
    $title => ""
  );
  showNavLinks($links, "profiles.png");
  unset($links);
?>

<h3><?php echo _("Profiles List:"); ?></h3>

<table>
  <thead>
    <tr>
      <th>
        <?php echo _("Function"); ?>
      </th>

      <th>
        <?php echo _("Description"); ?>
      </th>
    </tr>
  </thead>

  <tbody>
<?php
  $rowClass = "odd";
  while ($des = $desQ->fetchDescription())
  {
?>
    <tr class="<?php echo $rowClass; ?>">
      <td>
        <a href="../admin/profile_edit_form.php?key=<?php echo $des->getCode(); ?>&amp;reset=Y"><?php echo _("edit"); ?></a>
      </td>

      <td>
        <?php echo $des->getDescription(); ?>
      </td>
    </tr>
<?php
    // swap row color
    ($rowClass == "odd") ? $rowClass = "even" : $rowClass = "odd";
  } // end while
  $desQ->freeResult();
  $desQ->close();
  unset($des);
  unset($desQ);
?>
  </tbody>
</table>

<?php require_once("../shared/footer.php"); ?>
