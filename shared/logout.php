<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: logout.php,v 1.2 2004/04/14 22:33:43 jact Exp $
 */

/**
 * logout.php
 ********************************************************************
 * Session destruction process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  session_name("OpenClinic");
  session_start();
  //echo session_encode(); // debug
  session_unset(); // works in PHP > 4.0
  $_SESSION = array(); // works in PHP >= 4.0.6
  //$HTTP_SESSION_VARS = array(); // works in PHP < 4.0.6
  session_destroy();

  header("Location: ../home/index.php");
  exit();
?>
