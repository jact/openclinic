<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: get_form_vars.php,v 1.6 2006/03/15 20:09:50 jact Exp $
 */

/**
 * get_form_vars.php
 *
 * To retrieve formVar and formError from session and variables
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Getting form errors and previous form variables from session
   */
  (isset($_SESSION["formVar"]))
    ? $formVar = $_SESSION["formVar"]
    : $formVar = null;

  (isset($_SESSION["formError"]))
    ? $formError = $_SESSION["formError"]
    : $formError = null;
?>
