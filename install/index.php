<?php
/**
 * index.php
 *
 * Index page of installation process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: index.php,v 1.32 2008/01/07 14:29:46 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  $tab = "install";
  $nav = "index";

  require_once("../config/environment.php");

  $title = _("OpenClinic Install");
  require_once("../layout/header.php");
  HTML::section(1, $title);

  require_once(dirname(__FILE__) . "/parse_sql_file.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  //Error::debug($_POST);
  //Error::debug($_FILES);

  if (isset($_POST['install_file']))
  {
    Form::compareToken('./index.php');

    // @fixme gecko browsers (Mozilla 1.7.8) cause to disappear CR/LF (and I don't know why)
    /*$_POST['sql_query'] = Check::safeText($_POST['sql_query'], false);
    if (get_magic_quotes_gpc())
    {
      $_POST['sql_query'] = stripslashes($_POST['sql_query']);
    }*/

    if ( !parseSql($_POST['sql_query']) )
    {
      Msg::error(_("Parse failed."));
      HTML::para(HTML::strLink(_("Back to installation main page"), $_SERVER['PHP_SELF']));
      include_once("../layout/footer.php");
      exit();
    }
    else
    {
      // to prevent ghosts...
      $_SESSION = array();
      session_destroy();

      Msg::info(_("File installed correctly."));
      HTML::para(HTML::strLink(_("Go to OpenClinic"), '../home/index.php'));
      HTML::rule();
    }
  }

  /**
   * In Mozilla there no path file, only name and extension. Why? Is it an error?
   */
  if (isset($_POST['view_file']) && !empty($_FILES['sql_file']['name']) && $_FILES['sql_file']['size'] > 0)
  {
    $sqlQuery = file_get_contents($_FILES['sql_file']['tmp_name']);
    //$sqlQuery = Check::safeText($sqlQuery, false);

    HTML::start('form', array('method' => 'post', 'action' => $_SERVER['PHP_SELF']));

    $body = array();
    $body[] = Form::strTextArea("sql_query", 15, 75, $sqlQuery, array('readonly' => true));

    $foot = array(
      Form::strButton("install_file", _("Install file"))
      . Form::generateToken()
    );

    Form::fieldset(_("Install file"), $body, $foot);
    HTML::end('form');

    HTML::para(HTML::strLink(_("Cancel"), './index.php'));

    include_once("../layout/footer.php");
    exit();
  } // end if

  require_once("../model/Query.php");

  $installQ = new Query();
  $installQ->captureError(true);
  if ($installQ->isError())
  {
    HTML::para(_("The connection to the database failed with the following error:"));
    Msg::error($installQ->getDbError());
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

    include_once("../layout/footer.php");
    exit();
  } // end if
  $installQ->close();

  Msg::info(_("Database connection is good."));

  HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'],
      'enctype' => 'multipart/form-data' // input[file]
    )
  );

  $body = array();
  $body[] = Form::strFile("sql_file", "", 50);

  $foot = array(Form::strButton("view_file", _("View file")));

  Form::fieldset(_("Install a SQL file"), $body, $foot);
  HTML::end('form');

  require_once("../layout/footer.php");
?>
