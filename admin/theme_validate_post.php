<?php
/**
 * theme_validate_post.php
 *
 * Validate post data of a theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_validate_post.php,v 1.11 2006/03/28 19:15:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

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

    $_SESSION["formVar"] = $_POST;
    $_SESSION["formError"] = $formError;

    header("Location: " . $errorLocation);
    exit();
  }
?>
