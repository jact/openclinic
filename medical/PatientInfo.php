<?php
/**
 * PatientInfo.php
 *
 * Contains the class PatientInfo
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: PatientInfo.php,v 1.1 2007/10/25 21:55:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../model/Patient_Page_Query.php");
require_once("../lib/HTML.php");

/**
 * PatientInfo data access component for a patient
 *
 * Methods:
 *  void PatientInfo(int $id)
 *  mixed getObject(void)
 *  string getName(void)
 *  void showHeader(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class PatientInfo
{
  var $_patient = null; // patient object
  var $_name = ''; // complete name

  /**
   * void PatientInfo(int $id)
   *
   * Constructor
   *
   * @param int $id patient identificator
   * @return void
   * @access public
   */
  function PatientInfo($id)
  {
    $_patQ = new Patient_Page_Query();
    $_patQ->connect();

    if ( !$_patQ->select($id) )
    {
      $_patQ->close();

      return;
    }

    $this->_patient = $_patQ->fetch();
    if ( !$this->_patient )
    {
      $_patQ->close();
      Error::fetch($_patQ);
    }

    $this->_name = $this->_patient->getFirstName()
      . ' ' . $this->_patient->getSurname1()
      . ' ' . $this->_patient->getSurname2();

    $_patQ->freeResult();
    $_patQ->close();
  }

  /**
   * mixed getObject(void)
   *
   * Returns patient object if not null
   *
   * @return mixed patient object or null
   * @access public
   */
  function getObject()
  {
    return $this->_patient;
  }

  /**
   * string getName(void)
   *
   * Returns complete name of the patient
   *
   * @return string complete name of the patient
   * @access public
   */
  function getName()
  {
    return $this->_name;
  }

  /**
   * void showHeader(void)
   *
   * Draws a header with patient information
   *
   * @return void
   * @access public
   */
  function showHeader()
  {
    if ( !$this->_patient )
    {
      return;
    }

    HTML::start('div', array('id' => 'patientHeader', 'class' => 'clearfix'));
    HTML::para(_("Patient") . ': ' . $this->_name);
    HTML::para(_("Sex") . ': ' . ($this->_patient->getSex() == 'V' ? _("Male") : _("Female")));
    HTML::para(_("Age") . ': ' . $this->_patient->getAge(), array('class' => 'right'));
    HTML::end('div');
  }
} // end class
?>
