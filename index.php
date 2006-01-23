<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: index.php,v 1.3 2006/01/23 22:48:05 jact Exp $
 */

/**
 * index.php
 *
 * Index page of the project
 *
 * Author: jact <jachavar@gmail.com>
 */

  require_once("./database_constants.php");

  function message()
  {
    $no = mysql_errno();
    $msg = mysql_error();
    echo $no . '<br />' . $msg . '<hr />';
    echo 'This Server is not ready to work. Contact admin and ask to start MySQL server.<br />';
    echo 'If it is your first use <a href="./install/wizard.php">go to the new installation process</a>.';
    echo '<br />Or if you prefer <a href="./install/index.php">go to normal install script</a>.';
  }

  if ( !extension_loaded("mysql") )
  {
    echo 'It is impossible execute OpenClinic without MySQL support.' . '<br />';
    echo 'When you installed it, try again.' . '<br />';
    echo 'For more details, see <a href="./install.html">Install instructions</a>.';
    exit();
  }

  $db = @mysql_connect(OPEN_HOST . (defined("OPEN_PORT") ? ':' . OPEN_PORT : ''), OPEN_USERNAME, OPEN_PWD);
  if ( !$db )
  {
    message();
    exit();
  }

  $selectResult = mysql_select_db(OPEN_DATABASE, $db);
  if ( !$selectResult )
  {
    message();
    exit();
  }

  @mysql_close($db);

  header("Location: home/index.php");
  exit();
?>
