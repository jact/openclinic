<?php
/**
 * get_form_vars.php
 *
 * To retrieve formVar and formError from session and variables
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: get_form_vars.php,v 1.9 2007/10/28 11:34:59 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/Form.php");

  /**
   * Getting form errors and previous form variables from session
   */
  $formSession = Form::getSession();
  $formVar = (isset($formSession['var'])) ? $formSession['var'] : null;
  $formError = (isset($formSession['error'])) ? $formSession['error'] : null;
  unset($formSession);
?>
