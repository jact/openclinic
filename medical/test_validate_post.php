<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: test_validate_post.php,v 1.10 2006/03/26 15:20:50 jact Exp $
 */

/**
 * test_validate_post.php
 *
 * Validate post data of a medical problem test
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.6
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

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
      $aux = ereg_replace("[/]+", "/", $aux);
      $test->setPathFilename($aux);
    }
  }
  $_POST["upload_file"] = $test->getPathFilename();

  if ( !$test->validateData() )
  {
    $formError["path_filename"] = $test->getPathFilenameError();

    $_SESSION["formVar"] = $_POST;
    $_SESSION["formError"] = $formError;

    header("Location: " . $errorLocation);
    exit();
  }
?>
