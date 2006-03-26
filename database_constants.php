<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: database_constants.php,v 1.6 2006/03/26 14:45:11 jact Exp $
 */

/**
 * database_constants.php
 *
 * Definition of database connection variables
 *
 * @author jact <jachavar@gmail.com>
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
