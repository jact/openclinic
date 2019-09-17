<?php
/**
 * index.php
 *
 * Index page of installation process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

  $tab = "install";
  $nav = "index";

  // Instead of include environment.php (because maybe database connection doesn't exists)
  define("OPEN_THEME_NAME",     "OpenClinic");
  define("OPEN_THEME_CSS_FILE", "openclinic.css");
  require_once("../config/i18n.php");
  require_once("../config/session_info.php");
  require_once("../lib/FlashMsg.php");

  $title = _("OpenClinic Install");
  require_once("../layout/header.php");
  echo HTML::section(1, $title);

  require_once(dirname(__FILE__) . "/parse_sql_file.php");
  require_once("../lib/Form.php");
  require_once("../lib/Check.php");

  //AppError::debug($_POST);
  //AppError::debug($_FILES);

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
      echo Msg::error(_("Parse failed."));
      echo HTML::para(HTML::link(_("Back to installation main page"), $_SERVER['PHP_SELF']));
      include_once("../layout/footer.php");
      exit();
    }
    else
    {
      // to prevent ghosts...
      $_SESSION = array();
      session_destroy();

      echo Msg::info(_("File installed correctly."));
      echo HTML::para(HTML::link(_("Go to OpenClinic"), '../home/index.php'));
      echo HTML::rule();
    }
  }

  /**
   * In Mozilla there no path file, only name and extension. Why? Is it an error?
   */
  if (isset($_POST['view_file']) && !empty($_FILES['sql_file']['name']) && $_FILES['sql_file']['size'] > 0)
  {
    $sqlQuery = file_get_contents($_FILES['sql_file']['tmp_name']);
    //$sqlQuery = Check::safeText($sqlQuery, false);

    echo HTML::start('form', array('method' => 'post', 'action' => $_SERVER['PHP_SELF']));

    $body = array();
    $body[] = Form::textArea("sql_query", $sqlQuery,
      array(
        'rows' => 15,
        'cols' => 75,
        'readonly' => true
      )
    );

    $foot = array(
      Form::button("install_file", _("Install file"))
      . Form::generateToken()
    );

    echo Form::fieldset(_("Install file"), $body, $foot);
    echo HTML::end('form');

    echo HTML::para(HTML::link(_("Cancel"), './index.php'));

    include_once("../layout/footer.php");
    exit();
  } // end if

  require_once("../model/Query.php");

  $installQ = new Query();
  $installQ->captureError(true);
  if ($installQ->isError())
  {
    echo HTML::para(_("The connection to the database failed with the following error:"));
    echo Msg::error($installQ->getDbError());
    echo HTML::rule();

    echo HTML::para(_("Please make sure the following has been done before running this install script."));

    $array = array(
      sprintf(_("Create OpenClinic database (%s of the install instructions)"),
        HTML::link(sprintf(_("step %d"), 4), '../install.html#step4')
      ),
      sprintf(_("Create OpenClinic database user (%s of the install instructions)"),
        HTML::link(sprintf(_("step %d"), 5), '../install.html#step5')
      ),
      sprintf(_("Update %s with your new database username and password (%s of the install instructions)"),
        HTML::tag('strong', 'openclinic/config/database_constants.php'),
        HTML::link(sprintf(_("step %d"), 8), '../install.html#step8')
      )
    );
    echo HTML::itemList($array, null, true);

    echo HTML::para(sprintf(_("See %s for more details."), HTML::link(_("Install Instructions"), '../install.html')));

    include_once("../layout/footer.php");
    exit();
  } // end if
  $installQ->close();

  echo Msg::info(_("Database connection is good."));

  echo HTML::start('form',
    array(
      'method' => 'post',
      'action' => $_SERVER['PHP_SELF'],
      'enctype' => 'multipart/form-data' // input[file]
    )
  );

  $body = array();
  $body[] = Form::file("sql_file", null, array('size' => 50));

  $foot = array(Form::button("view_file", _("View file")));

  echo Form::fieldset(_("Install a SQL file"), $body, $foot);
  echo HTML::end('form');

  require_once("../layout/footer.php");
