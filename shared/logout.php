<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: logout.php,v 1.3 2004/06/20 12:04:02 jact Exp $
 */

/**
 * logout.php
 ********************************************************************
 * Session destruction process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  require_once("../shared/session_info.php");

  ////////////////////////////////////////////////////////////////////
  // Session destroy
  ////////////////////////////////////////////////////////////////////
  //echo session_encode(); // debug
  session_unset(); // works in PHP > 4.0
  $_SESSION = array(); // works in PHP >= 4.0.6
  session_destroy();

  ////////////////////////////////////////////////////////////////////
  // Cookie destroy
  ////////////////////////////////////////////////////////////////////
  $params = session_get_cookie_params();
  setcookie(session_name(), 0, 1, $params['path']);
  unset($params);

  header("Location: ../home/index.php");
  exit();
?>
