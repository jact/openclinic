<?php
/**
 * staff_validate_post.php
 *
 * Validate post data of a staff member
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_validate_post.php,v 1.14 2013/01/07 18:08:05 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.6
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Form.php");
  Form::compareToken($errorLocation);

  $staff->setMemberType($_POST["member_type"]);
  $_POST["member_type"] = $staff->getMemberType();

  if (isset($_POST["collegiate_number"]))
  {
    $staff->setCollegiateNumber($_POST["collegiate_number"]);
    $_POST["collegiate_number"] = $staff->getCollegiateNumber();
  }

  $staff->setNIF($_POST["nif"]);
  $_POST["nif"] = $staff->getNIF();

  $staff->setFirstName($_POST["first_name"]);
  $_POST["first_name"] = $staff->getFirstName();

  $staff->setSurname1($_POST["surname1"]);
  $_POST["surname1"] = $staff->getSurname1();

  $staff->setSurname2($_POST["surname2"]);
  $_POST["surname2"] = $staff->getSurname2();

  $staff->setAddress($_POST["address"]);
  $_POST["address"] = $staff->getAddress();

  $staff->setPhone($_POST["phone_contact"]);
  $_POST["phone_contact"] = $staff->getPhone();

  $staff->setLogin($_POST["login"]);
  $_POST["login"] = $staff->getLogin();

  if ( !$staff->validateData() )
  {
    $formError["collegiate_number"] = $staff->getCollegiateNumberError();
    $formError["nif"] = $staff->getNIFError();
    $formError["first_name"] = $staff->getFirstNameError();
    $formError["surname1"] = $staff->getSurname1Error();
    //$formError["surname2"] = $staff->getSurname2Error();
    $formError["login"] = $staff->getLoginError();

    Form::setSession($_POST, $formError);

    header("Location: " . $errorLocation);
    exit();
  }
?>
