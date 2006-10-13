<?php
/**
 * index.php
 *
 * Index page of installation process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.22 2006/10/13 20:14:21 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
      HTML::para(HTML::strLink(_("Back to installation main page"), $_SERVER['PHP_SELF']));
      include_once("../install/footer.php");
      unlink($tmpFile);
      exit();
    }
    else
    {
      HTML::message(_("File installed correctly."), OPEN_MSG_INFO);
      HTML::para(HTML::strLink(_("Go to OpenClinic"), '../home/index.php'));
      HTML::rule();
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

    HTML::tag('pre', $sqlQuery);

    HTML::rule();

    HTML::start('form', array('method' => 'post', 'action' => $_SERVER['PHP_SELF']));
    Form::hidden("sql_file", Check::safeText($_POST['sql_file']));
    Form::hidden("sql_query", $sqlQuery);

    $filename = explode("-", $_FILES['sql_file']['name']);
    if (in_array($filename[0], $tables))
    {
      HTML::para(
        Form::strCheckBox("drop", "true")
        . Form::strLabel("drop", _("Add 'DROP table' sentence"))
      );
    }

    HTML::para(
      Form::strButton("install_file", _("Install file"))
      . Form::strButton("cancel_install", _("Cancel"), "button", array('onclick' => "parent.location='./index.php'"))
    );

    HTML::end('form');

    include_once("../install/footer.php");
    exit();
  } // end if

  HTML::section(1, _("OpenClinic Installation:"));

  require_once("../model/Query.php");

  $installQ = new Query();
  $installQ->captureError(true);

  $installQ->connect();
  if ($installQ->isError())
  {
    HTML::para(_("The connection to the database failed with the following error:"));
    HTML::tag('pre', $installQ->getDbError(), array('class' => 'error'));
    HTML::rule();

    HTML::para(_("Please make sure the following has been done before running this install script."));

    $array = array(
      sprintf(_("Create OpenClinic database (%s of the install instructions)"),
        HTML::strLink(sprintf(_("step %d"), 4), '../install.html#step4')
      ),
      sprintf(_("Create OpenClinic database user (%s of the install instructions)"),
        HTML::strLink(sprintf(_("step %d"), 5), '../install.html#step5')
      ),
      sprintf(_("Update %s with your new database username and password (%s of the install instructions)"),
        HTML::strTag('strong', 'openclinic/database_constants.php'),
        HTML::strLink(sprintf(_("step %d"), 8), '../install.html#step8')
      )
    );
    HTML::itemList($array, null, true);

    HTML::para(sprintf(_("See %s for more details."), HTML::strLink(_("Install Instructions"), '../install.html')));

    include_once("../install/footer.php");
    exit();
  } // end if
  HTML::para(_("Database connection is good."));

  $installQ->close();

  HTML::para(HTML::strLink(_("Create OpenClinic tables"), './install.php'));

  HTML::rule();

  HTML::section(2, _("Install a SQL file:"));

  // @todo use fieldset
  HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'],
      'onsubmit' => 'this.secret_file.value = this.sql_file.value; return true;'
    )
  );
  Form::hidden("secret_file");

  HTML::para(Form::strFile("sql_file", "", 50));

  HTML::para(Form::strButton("view_file", _("View file")));
  HTML::end('form');

  HTML::rule();

  require_once("../install/footer.php");
?>
