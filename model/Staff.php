<?php
/**
 * Staff.php
 *
 * Contains the class Staff
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Staff.php,v 1.15 2013/01/20 12:48:10 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

/*
 * Staff represents a clinic staff member.
 *
 * Methods:
 *  bool validateData(void)
 *  int getIdMember(void)
 *  void setIdMember(int $value)
 *  int getIdUser(void)
 *  void setIdUser(int $value)
 *  string getMemberType(void)
 *  void setMemberType(string $value)
 *  string getCollegiateNumber(void)
 *  string getCollegiateNumberError(void)
 *  void setCollegiateNumber(string $value)
 *  string getNIF(void)
 *  string getNIFError(void)
 *  void setNIF(string $value)
 *  string getFirstName(void)
 *  string getFirstNameError(void)
 *  void setFirstName(string $value)
 *  string getSurname1(void)
 *  string getSurname1Error(void)
 *  void setSurname1(string $value)
 *  string getSurname2(void)
 *  string getSurname2Error(void)
 *  void setSurname2(string $value)
 *  string getLogin(void)
 *  string getLoginError(void)
 *  void setLogin(string $value)
 *  string getAddress(void)
 *  void setAddress(string $value)
 *  string getPhone(void)
 *  void setPhone(string $value)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */

class Staff
{
  private $_idMember = 0;
  private $_idUser = 0;
  private $_collegiateNumber = "";
  private $_collegiateNumberError = "";
  private $_memberType = "";
  private $_nif = "";
  private $_nifError = "";
  private $_firstName = "";
  private $_firstNameError = "";
  private $_surname1 = "";
  private $_surname1Error = "";
  private $_surname2 = "";
  private $_surname2Error = "";
  private $_address = "";
  private $_phone = "";
  private $_login = "";
  private $_loginError = "";

  private $_trans; // to translate htmlspecialchars()

  public function Staff()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /**
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  public function validateData()
  {
    $valid = true;

    /*if ($this->_nif == "")
    {
      $valid = false;
      $this->_nifError = _("This is a required field.");
    }*/

    if ($this->_firstName == "")
    {
      $valid = false;
      $this->_firstNameError = _("This is a required field.");
    }

    if ($this->_surname1 == "")
    {
      $valid = false;
      $this->_surname1Error = _("This is a required field.");
    }

    /*if ($this->_surname2 == "")
    {
      $valid = false;
      $this->_surname2Error = _("This is a required field.");
    }*/

    if (strlen($this->_login) > 0 && strlen($this->_login) < 4)
    {
      $valid = false;
      $this->_loginError = sprintf(_("This field must be at least %d characters."), 4);
    }
    elseif (substr_count($this->_login, " ") > 0)
    {
      $valid = false;
      $this->_loginError = _("This field must not contain any spaces.");
    }
    elseif (preg_match("/['\\\\]/", $this->_login))
    {
      $valid = false;
      $this->_loginError = sprintf(_("This field can't contain the symbols %s"), "'\\");
    }

    //Error::debug($this, "", true); // debug
    if ($this->_memberType == "Doctor" && $this->_collegiateNumber == "")
    {
      $valid = false;
      $this->_collegiateNumberError = _("This is a required field.");
    }

    return $valid;
  }

  /**
   * int getIdMember(void)
   *
   * @return int Staff Id Member
   * @access public
   */
  public function getIdMember()
  {
    return intval($this->_idMember);
  }

  /**
   * void setIdMember(int $value)
   *
   * @param int $value
   * @return void
   * @access public
   */
  public function setIdMember($value)
  {
    $this->_idMember = intval($value);
  }

  /**
   * int getIdUser(void)
   *
   * @return int Id User
   * @access public
   */
  public function getIdUser()
  {
    return intval($this->_idUser);
  }

  /**
   * void setIdUser(int $value)
   *
   * @param int $value
   * @return void
   * @access public
   */
  public function setIdUser($value)
  {
    $this->_idUser = intval($value);
  }

  /**
   * string getMemberType(void)
   *
   * @return string Staff Member Type
   * @access public
   */
  public function getMemberType()
  {
    return stripslashes(strtr($this->_memberType, $this->_trans));
  }

  /**
   * void setMemberType(string $value)
   *
   * @param string $value
   * @return void
   * @access public
   */
  public function setMemberType($value)
  {
    $temp = trim($value);
    $this->_memberType = (($temp == "") ? OPEN_ADMINISTRATIVE : $temp);
  }

  /**
   * string getCollegiateNumber(void)
   *
   * @return string doctor collegiate number
   * @access public
   */
  public function getCollegiateNumber()
  {
    return stripslashes(strtr($this->_collegiateNumber, $this->_trans));
  }

  /**
   * string getCollegiateNumberError(void)
   *
   * @return string Collegiate Number error text
   * @access public
   */
  public function getCollegiateNumberError()
  {
    return $this->_collegiateNumberError;
  }

  /**
   * void setCollegiateNumber(string $value)
   *
   * @param string $value Collegiate Number of a doctor
   * @return void
   * @access public
   */
  public function setCollegiateNumber($value)
  {
    $this->_collegiateNumber = Check::safeText($value);
  }

  /**
   * string getNIF(void)
   *
   * @return string Staff NIF
   * @access public
   */
  public function getNIF()
  {
    return stripslashes(strtr($this->_nif, $this->_trans));
  }

  /**
   * string getNIFError(void)
   *
   * @return string NIF error text
   * @access public
   */
  public function getNIFError()
  {
    return $this->_nifError;
  }

  /**
   * void setNIF(string $value)
   *
   * @param string $value NIF of staff member
   * @return void
   * @access public
   */
  public function setNIF($value)
  {
    $this->_nif = Check::safeText($value);
  }

  /**
   * string getFirstName(void)
   *
   * @return string Staff first name
   * @access public
   */
  public function getFirstName()
  {
    return stripslashes(strtr($this->_firstName, $this->_trans));
  }

  /**
   * string getFirstNameError(void)
   *
   * @return string first name error text
   * @access public
   */
  public function getFirstNameError()
  {
    return $this->_firstNameError;
  }

  /**
   * void setFirstName(string $value)
   *
   * @param string $value first name of staff member
   * @return void
   * @access public
   */
  public function setFirstName($value)
  {
    $this->_firstName = Check::safeText($value);
  }

  /**
   * string getSurname1(void)
   *
   * @return string surname1 of staff member
   * @access public
   */
  public function getSurname1()
  {
    return stripslashes(strtr($this->_surname1, $this->_trans));
  }

  /**
   * string getSurname1Error(void)
   *
   * @return string surname1 error text
   * @access public
   */
  public function getSurname1Error()
  {
    return $this->_surname1Error;
  }

  /**
   * void setSurname1(string $value)
   *
   * @param string $value surname1 of staff member
   * @return void
   * @access public
   */
  public function setSurname1($value)
  {
    $this->_surname1 = Check::safeText($value);
  }

  /**
   * string getSurname2(void)
   *
   * @return string surname2 of staff member
   * @access public
   */
  public function getSurname2()
  {
    return stripslashes(strtr($this->_surname2, $this->_trans));
  }

  /**
   * string getSurname2Error(void)
   *
   * @return string surname2 error text
   * @access public
   */
  public function getSurname2Error()
  {
    return $this->_surname2Error;
  }

  /**
   * void setSurname2(string $value)
   *
   * @param string $value surname2 of staff member
   * @return void
   * @access public
   */
  public function setSurname2($value)
  {
    $this->_surname2 = Check::safeText($value);
  }

  /**
   * string getLogin(void)
   *
   * @return string Staff login
   * @access public
   */
  public function getLogin()
  {
    return stripslashes(strtr($this->_login, $this->_trans));
  }

  /**
   * string getLoginError(void)
   *
   * @return string Login error text
   * @access public
   */
  public function getLoginError()
  {
    return $this->_loginError;
  }

  /**
   * void setLogin(string $value)
   *
   * @param string $value login of staff member
   * @return void
   * @access public
   */
  public function setLogin($value)
  {
    $value = strtolower($value); // sure?
    $this->_login = Check::safeText($value);
  }

  /**
   * string getAddress(void)
   *
   * @return string Staff address
   * @access public
   */
  public function getAddress()
  {
    return stripslashes(strtr($this->_address, $this->_trans));
  }

  /**
   * void setAddress(string $value)
   *
   * @param string $value address of staff member
   * @return void
   * @access public
   */
  public function setAddress($value)
  {
    $this->_address = Check::safeText($value);
  }

  /**
   * string getPhone(void)
   *
   * @return string Staff phone contact
   * @access public
   */
  public function getPhone()
  {
    return stripslashes(strtr($this->_phone, $this->_trans));
  }

  /**
   * void setPhone(string $value)
   *
   * @param string $value phone contact of staff member
   * @return void
   * @access public
   */
  public function setPhone($value)
  {
    $this->_phone = Check::safeText($value);
  }

  /**
   * string __toString(void)
   *
   * @return string class name
   * @access public
   * @since 0.8
   */
  public function __toString()
  {
    return trim($this->getFirstName() . ' ' . $this->getSurname1() . ' ' . $this->getSurname2());
  }
} // end class
?>
