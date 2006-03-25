<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.20 2006/03/25 19:54:19 jact Exp $
 */

/**
 * index.php
 *
 * Index page of installation process
 *
 * @author jact <jachavar@gmail.com>
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
      echo '<p>' . HTML::strLink(_("Back to installation main page"), $_SERVER['PHP_SELF']) . "</p>\n";
      include_once("../install/footer.php");
      unlink($tmpFile);
      exit();
    }
    else
    {
      HTML::message(_("File installed correctly."), OPEN_MSG_INFO);
      echo '<p>' . HTML::strLink(_("Go to OpenClinic"), '../home/index.php') . "</p>\n";
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

    echo "<hr />\n";

    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">' . "\n";
    Form::hidden("sql_file", Check::safeText($_POST['sql_file']));
    Form::hidden("sql_query", $sqlQuery);

    $filename = explode("-", $_FILES['sql_file']['name']);
    if (in_array($filename[0], $tables))
    {
      echo '<p>';
      Form::checkBox("drop", "true");
      Form::label("drop", _("Add 'DROP table' sentence"));
      echo "</p>\n";
    }

    echo '<p>';
    Form::button("install_file", _("Install file"));
    Form::button("cancel_install", _("Cancel"), "button", array('onclick' => "parent.location='./index.php'"));
    echo "</p>\n";

    echo "</form>\n";

    include_once("../install/footer.php");
    exit();
  } // end if

  echo '<h1>' . _("OpenClinic Installation:") . "</h1>\n";

  require_once("../classes/Query.php");

  $installQ = new Query();
  $installQ->captureError(true);

  $installQ->connect();
  if ($installQ->isError())
  {
    echo '<p>' . _("The connection to the database failed with the following error:") . "</p>\n";
    echo '<pre class="error">' . $installQ->getDbError() . "</pre>\n";
    echo "<hr />\n";

    echo '<p>' . _("Please make sure the following has been done before running this install script.") . "</p>\n";

    echo "<ol>\n";
    echo '<li>' . sprintf(_("Create OpenClinic database (%sstep 4%s of the install instructions)"), '<a href="../install.html#step4">', "</a>") . "</li>\n";
    echo '<li>' . sprintf(_("Create OpenClinic database user (%sstep 5%s of the install instructions)"), '<a href="../install.html#step5">', "</a>") . "</li>\n";
    echo '<li>' . sprintf(_("Update %s with your new database username and password (%sstep 8%s of the install instructions)"), "<strong>openclinic/database_constants.php</strong>", '<a href="../install.html#step8">', "</a>") . "</li>\n";
    echo "</ol>\n";

    echo '<p>' . sprintf(_("See %sInstall Instructions%s for more details."), '<a href="../install.html">', "</a>") . "</p>\n";

    include_once("../install/footer.php");
    exit();
  } // end if
  echo '<p>' . _("Database connection is good.") . "</p>\n";

  $installQ->close();

  echo '<p>' . HTML::strLink(_("Create OpenClinic tables"), './install.php') . "</p>\n";

  echo "<hr />\n";

  echo '<h2>' . _("Install a SQL file:") . "</h2>\n";

  echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" enctype="multipart/form-data" onsubmit="this.secret_file.value = this.sql_file.value; return true;">' . "\n";
  Form::hidden("secret_file");

  echo '<p>' . Form::strFile("sql_file", "", 50) . "</p>\n";

  echo '<p>' . Form::strButton("view_file", _("View file")) . "</p>\n";
  echo "</form>\n";

  echo "<hr />\n";

  require_once("../install/footer.php");
?>
