<?php
/**
 * test_validate_post.php
 *
 * Validate post data of a medical problem test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: test_validate_post.php,v 1.15 2013/01/13 14:29:49 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");
  Form::compareToken($errorLocation);

  require_once("../lib/File.php");

  $test->setIdProblem($_POST["id_problem"]);
  $_POST["id_problem"] = $test->getIdProblem();

  $test->setDocumentType($_POST["document_type"]);
  $_POST["document_type"] = $test->getDocumentType();

  if ( !isset($_POST["upload_file"]) )
  {
    $_POST["upload_file"] = "";
  }
  if ( !isset($_POST["previous"]) )
  {
    $_POST["previous"] = "";
  }
  $aux = trim(($_POST["upload_file"] != "") ? $_POST["upload_file"]: $_POST["previous"]);
  if ($aux != $_POST["previous"])
  {
    // upload file
    if (trim($_FILES['path_filename']['name']))
    {
      if (File::upload($_FILES['path_filename'], dirname(realpath(__FILE__)) . '/../tests'))
      {
        $test->setPathFilename('../tests/' . $_FILES['path_filename']['name']);
      }
    }
  }
  else
  {
    if ($aux)
    {
      $aux = str_replace("\\", "/", $aux);
      $aux = preg_replace("/[\/]+/", "/", $aux);
      $test->setPathFilename($aux);
    }
  }
  $_POST["upload_file"] = $test->getPathFilename();

  if ( !$test->validateData() )
  {
    $formError["path_filename"] = $test->getPathFilenameError();

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }
?>
