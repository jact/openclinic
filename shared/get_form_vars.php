<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: get_form_vars.php,v 1.4 2004/10/18 17:24:04 jact Exp $
 */

/**
 * get_form_vars.php
 ********************************************************************
 * To clean postVars and pageErrors from session and variables
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  //session_start(); // makes it in read_settings.php

  ////////////////////////////////////////////////////////////////////
  // Reset all form values
  ////////////////////////////////////////////////////////////////////
  if (isset($_GET["reset"]))
  {
    unset($_SESSION["postVars"]);
    unset($_SESSION["pageErrors"]);
  }

  ////////////////////////////////////////////////////////////////////
  // Getting page errors and previous post variables from session.
  ////////////////////////////////////////////////////////////////////
  (isset($_SESSION["postVars"]))
    ? $postVars = $_SESSION["postVars"]
    : $postVars = null;

  (isset($_SESSION["pageErrors"]))
    ? $pageErrors = $_SESSION["pageErrors"]
    : $pageErrors = null;
?>
