<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: database_constants.php,v 1.5 2005/08/03 17:39:12 jact Exp $
 */

/**
 * database_constants.php
 *
 * Definition of database connection variables
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ./index.php");
    exit();
  }

/**
 * A T T E N T I O N !
 *
 * Please modify the following database connection variables to match
 * the MySQL database and user that you have created for OpenClinic.
 */
  define("OPEN_HOST",     "localhost");
  define("OPEN_DATABASE", "openclinic");
  define("OPEN_USERNAME", "root");
  define("OPEN_PWD",      "");
?>
