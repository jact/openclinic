<?php
/**
 * theme_validate_post.php
 *
 * Validate post data of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_validate_post.php,v 1.14 2007/10/29 20:03:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");
  Form::compareToken($errorLocation);

  $theme->setName($_POST["theme_name"]);
  $_POST["theme_name"] = $theme->getName();

  $theme->setCSSFile($_POST["css_file"]);
  $_POST["css_file"] = $theme->getCSSFile();

  $theme->setCSSRules($_POST["css_rules"]);
  $_POST["css_rules"] = $theme->getCSSRules();

  if ( !$theme->validateData() )
  {
    $formError["theme_name"] = $theme->getNameError();
    $formError["css_file"] = $theme->getCSSFileError();
    $formError["css_rules"] = $theme->getCSSRulesError();

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }
?>
