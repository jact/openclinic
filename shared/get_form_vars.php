<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: get_form_vars.php,v 1.5 2005/08/03 17:40:49 jact Exp $
 */

/**
 * get_form_vars.php
 *
 * To clean postVars and pageErrors from session and variables
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Reset all form values
   */
  if (isset($_GET["reset"]))
  {
    unset($_SESSION["postVars"]);
    unset($_SESSION["pageErrors"]);
  }

  /**
   * Getting page errors and previous post variables from session
   */
  (isset($_SESSION["postVars"]))
    ? $postVars = $_SESSION["postVars"]
    : $postVars = null;

  (isset($_SESSION["pageErrors"]))
    ? $pageErrors = $_SESSION["pageErrors"]
    : $pageErrors = null;
?>
