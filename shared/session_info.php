<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: session_info.php,v 1.2 2004/09/22 18:20:49 jact Exp $
 */

/**
 * session_info.php
 ********************************************************************
 * Making session user info available on all pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  session_name("OpenClinic");
  session_cache_limiter("nocache");
  session_start();
?>
