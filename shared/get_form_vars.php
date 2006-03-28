<?php
/**
 * get_form_vars.php
 *
 * To retrieve formVar and formError from session and variables
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: get_form_vars.php,v 1.8 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
