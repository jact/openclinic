<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.7 2004/07/05 18:35:59 jact Exp $
 */

/**
 * index.php
 ********************************************************************
 * Index page of installation process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  require_once("../install/header.php"); // i18n l10n
  require_once("../install/parse_sql_file.php");
  require_once("../lib/input_lib.php");
  require_once("../lib/error_lib.php");
  require_once("../lib/debug_lib.php");
  require_once("../lib/validator_lib.php");

  //debug($_POST);

  if (isset($_POST['install_file']))
  {
    $table = basename($_POST['sql_file']);
    $table = str_replace('.sql', '', $table);

    if (get_magic_quotes_gpc())
    {
      $_POST['sql_query'] = stripslashes($_POST['sql_query']);
    }
    $_POST['sql_query'] = safeText($_POST['sql_query'], false);

    $tmpFile = tempnam(dirname(realpath(__FILE__)), "foo");
    $handle = fopen($tmpFile, "w"); // as text, not binary
    fwrite($handle, $_POST['sql_query']);
    fclose($handle);
    chmod($tmpFile, 0644); // without execution permissions if it is possible

    if ( !parseSQLFile($tmpFile, $table, isset($_POST['drop'])) )
    {
      echo '<p class="error">' . _("Parse failed.") . "</p>\n";
      echo '<p><a href="' . $_SERVER['PHP_SELF'] . '">' . _("Back to installation main page") . "</a></p>\n";
      include_once("../install/footer.php");
      unlink($tmpFile);
      exit();
    }
    else
    {
      echo '<p>' . _("File installed correctly.") . "</p>\n";
      echo '<p><a href="../home/index.php">' . _("Go to OpenClinic") . "</a></p>\n";
      echo "<hr />\n";
      unlink($tmpFile);
    }
  }

  ////////////////////////////////////////////////////////////////////
  // To Opera navigators
  ////////////////////////////////////////////////////////////////////
  if (isset($_POST['sql_file']))
  {
    $_POST['sql_file'] = str_replace('\"', '', $_POST['sql_file']);
  }
  if (isset($_POST['secret_file']))
  {
    $_POST['secret_file'] = str_replace('\"', '', $_POST['secret_file']);
  }

  ////////////////////////////////////////////////////////////////////
  // If JavaScript is actived and works fine, we prevent Mozilla's problem
  ////////////////////////////////////////////////////////////////////
  if (isset($_POST['secret_file']))
  {
    if (strlen($_POST['secret_file']) > 0 && $_POST['secret_file'] != $_POST['sql_file'])
    {
      $_POST['sql_file'] = $_POST['secret_file'];
    }
  }

  ////////////////////////////////////////////////////////////////////
  // In Mozilla there no path file, only name and extension. Why? Is it an error?
  ////////////////////////////////////////////////////////////////////
  if (isset($_POST['view_file']) && !empty($_FILES['sql_file']['name']) && $_FILES['sql_file']['size'] > 0)
  {
    $sqlQuery = fread(fopen($_FILES['sql_file']['tmp_name'], 'r'), $_FILES['sql_file']['size']);
    $sqlQuery = safeText($sqlQuery, false);

    echo '<pre>';
    echo $sqlQuery;
    echo "</pre>\n";
?>
    <hr />

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <?php
          showInputHidden("sql_file", $_POST['sql_file']);
          showInputHidden("sql_query", $sqlQuery);
        ?>
      </div>

      <?php
        $filename = explode(".", $_FILES['sql_file']['name']);
        if (in_array($filename[0], $tables))
        {
      ?>
      <p>
        <?php showCheckBox("drop", "drop", "true"); ?>
        <label for="drop"><?php echo _("Add 'DROP table' sentence"); ?></label>
      </p>
      <?php
      } //end if
      ?>

      <div>
        <?php
          showInputButton("install_file", _("Install file"));
          showInputButton("cancel_install", _("Cancel"), "button", 'onclick="parent.location=\'./index.php\'"');
        ?>
      </div>
    </form>
<?php
    include_once("../install/footer.php");
    exit();
  } // end if

  echo '<h2>' . _("OpenClinic Installation:") . "</h2>\n";

  require_once("../classes/Query.php");

  $installQ = new Query();
  $installQ->connect();
  if ($installQ->errorOccurred())
  {
?>
    <p>
      <?php echo _("The connection to the database failed with the following error."); ?>
    </p>

    <pre><?php echo $installQ->getDbError(); ?></pre>

    <p>
      <?php echo _("Please make sure the following has been done before running this install script."); ?>
    </p>

    <ol type="1">
      <li>
        <?php echo sprintf(_("Create OpenClinic database (%sstep 4%s of the install instructions)"), '<a href="../install.html#step4">', "</a>"); ?>
      </li>

      <li>
        <?php echo sprintf(_("Create OpenClinic database user (%sstep 5%s of the install instructions)"), '<a href="../install.html#step5">', "</a>"); ?>
      </li>

      <li>
        <?php echo sprintf(_("Update %sopenclinic/database_constants.php%s with your new database username and password (%sstep 8%s of the install instructions)"), "<strong>", "</strong>", '<a href="../install.html#step8">', "</a>"); ?>
      </li>
    </ol>

    <p>
      <?php echo sprintf(_("See %sInstall Instructions%s for more details."), '<a href="../install.html">', "</a>"); ?>
    </p>

<?php
    require_once("../install/footer.php");
    exit();
  }
  echo '<p>' . _("Database connection is good.") . "</p>\n";

  $installQ->close();
?>

<p><a href="./install.php"><?php echo _("Create OpenClinic tables"); ?></a></p>

<hr />

<h2><?php echo _("Install a SQL file:"); ?></h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onsubmit="this.secret_file.value = this.sql_file.value; return true;">
  <div>
    <?php showInputHidden("secret_file"); ?>
  </div>

  <p><?php showInputFile("sql_file", "", 50); ?></p>

  <p><?php showInputButton("view_file", _("View file")); ?></p>
</form>

<hr />

<?php require_once("../install/footer.php"); ?>
