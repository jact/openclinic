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
 * @version   CVS: $Id: get_form_vars.php,v 1.10 2007/10/29 20:07:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");

  /**
   * Getting form errors and previous form variables from session
   */
  $formSession = Form::getSession();
  $formVar = (isset($formSession['var'])) ? $formSession['var'] : null;
  $formError = (isset($formSession['error'])) ? $formSession['error'] : null;
  unset($formSession);
?>
