<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.17 2005/08/15 16:36:33 jact Exp $
 */

/**
 * index.php
 *
 * Index page of installation process
 *
 * Author: jact <jachavar@gmail.com>
 */

  error_reporting(E_ALL & ~E_NOTICE); // normal mode
  //error_reporting(E_ALL); // debug mode

  require_once("../install/header.php"); // i18n l10n
  require_once("../install/parse_sql_file.php");
  require_once("../lib/Form.php");
  require_once("../lib/Error.php");
  require_once("../lib/Check.php");

  //Error::debug($_POST);

  if (isset($_POST['install_file']))
  {
    $table = basename($_POST['sql_file']);
    $table = str_replace('.sql', '', $table);

    if (get_magic_quotes_gpc())
    {
      $_POST['sql_query'] = stripslashes($_POST['sql_query']);
    }
    // @fixme gecko browsers (Mozilla 1.7.8) cause to disappear CR/LF (and I don't know why)
    $_POST['sql_query'] = Check::safeText($_POST['sql_query'], false);

    $tmpFile = tempnam(dirname(realpath(__FILE__)), "foo");
    $handle = fopen($tmpFile, "w"); // as text, not binary
    fwrite($handle, $_POST['sql_query']);
    fclose($handle);
    chmod($tmpFile, 0644); // without execution permissions if it is possible

    if ( !parseSQLFile($tmpFile, $table, isset($_POST['drop'])) )
    {
      HTML::message(_("Parse failed."), OPEN_MSG_ERROR);
      echo '<p><a href="' . $_SERVER['PHP_SELF'] . '">' . _("Back to installation main page") . "</a></p>\n";
      include_once("../install/footer.php");
      unlink($tmpFile);
      exit();
    }
    else
    {
      HTML::message(_("File installed correctly."), OPEN_MSG_INFO);
      echo '<p><a href="../home/index.php">' . _("Go to OpenClinic") . "</a></p>\n";
      echo "<hr />\n";
      unlink($tmpFile);
    }
  }

  /**
   * To Opera navigators
   */
  if (isset($_POST['sql_file']))
  {
    $_POST['sql_file'] = str_replace('\"', '', $_POST['sql_file']);
  }
  if (isset($_POST['secret_file']))
  {
    $_POST['secret_file'] = str_replace('\"', '', $_POST['secret_file']);
  }

  /**
   * If JavaScript is actived and works fine, we prevent Mozilla's problem
   */
  if (isset($_POST['secret_file']))
  {
    if (strlen($_POST['secret_file']) > 0 && $_POST['secret_file'] != $_POST['sql_file'])
    {
      $_POST['sql_file'] = $_POST['secret_file'];
    }
  }

  /**
   * In Mozilla there no path file, only name and extension. Why? Is it an error?
   */
  if (isset($_POST['view_file']) && !empty($_FILES['sql_file']['name']) && $_FILES['sql_file']['size'] > 0)
  {
    $fp = fopen($_FILES['sql_file']['tmp_name'], 'r');
    $sqlQuery = fread($fp, $_FILES['sql_file']['size']);
    fclose($fp);
    $sqlQuery = Check::safeText($sqlQuery, false);

    echo '<pre>';
    echo $sqlQuery;
    echo "</pre>\n";
?>
    <hr />

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div>
        <?php
          Form::hidden("sql_file", "sql_file", Check::safeText($_POST['sql_file']));
          Form::hidden("sql_query", "sql_query", $sqlQuery);
        ?>
      </div>

      <?php
        $filename = explode("-", $_FILES['sql_file']['name']);
        if (in_array($filename[0], $tables))
        {
          echo '<p>';
          Form::checkBox("drop", "drop", "true");
          Form::label("drop", _("Add 'DROP table' sentence"));
          echo "</p>\n";
        }
      ?>

      <p>
        <?php
          Form::button("install_file", "install_file", _("Install file"));
          Form::button("cancel_install", "cancel_install", _("Cancel"), "button", 'onclick="parent.location=\'./index.php\'"');
        ?>
      </p>
    </form>
<?php
    include_once("../install/footer.php");
    exit();
  } // end if

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

  require_once("../classes/Query.php");

  $installQ = new Query();
  $installQ->connect();
  if ($installQ->isError())
  {
?>
    <p>
      <?php echo _("The connection to the database failed with the following error:"); ?>
    </p>

    <pre class="error"><?php echo $installQ->getDbError(); ?></pre>

    <hr />

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
        <?php echo sprintf(_("Update %s with your new database username and password (%sstep 8%s of the install instructions)"), "<strong>openclinic/database_constants.php</strong>", '<a href="../install.html#step8">', "</a>"); ?>
      </li>
    </ol>

    <p>
      <?php echo sprintf(_("See %sInstall Instructions%s for more details."), '<a href="../install.html">', "</a>"); ?>
    </p>

<?php
    include_once("../install/footer.php");
    exit();
  } // end if
  echo '<p>' . _("Database connection is good.") . "</p>\n";

  $installQ->close();
?>

<p><a href="./install.php"><?php echo _("Create OpenClinic tables"); ?></a></p>

<hr />

<h2><?php echo _("Install a SQL file:"); ?></h2>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" onsubmit="this.secret_file.value = this.sql_file.value; return true;">
  <div>
    <?php Form::hidden("secret_file", "secret_file"); ?>
  </div>

  <p><?php Form::file("sql_file", "sql_file", "", 50); ?></p>

  <p><?php Form::button("view_file", "view_file", _("View file")); ?></p>
</form>

<hr />

<?php require_once("../install/footer.php"); ?>
