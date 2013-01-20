<?php
/**
 * User.php
 *
 * Contains the class User
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: User.php,v 1.14 2013/01/20 12:48:10 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

/*
 * User represents an application user.
 *
 * Methods:
 *  bool validateData(void)
 *  bool validatePwd(void)
 *  int getIdUser(void)
 *  void setIdUser(int $value)
 *  int getIdMember(void)
 *  void setIdMember(int $value)
 *  string getLogin(void)
 *  string getLoginError(void)
 *  void setLogin(string $value)
 *  string getPwd(void)
 *  string getPwdError(void)
 *  void setPwd(string $value)
 *  string getPwd2(void)
 *  void setPwd2(string $value)
 *  string getEmail(void)
 *  string getEmailError(void)
 *  void setEmail(string $value)
 *  bool isActived(void)
 *  void setActived(mixed $value)
 *  int getIdTheme(void)
 *  void setIdTheme(int $value)
 *  int getIdProfile(void)
 *  void setIdProfile(int $value)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class User
{
  private $_idUser = 0;
  private $_idMember = 0;
  private $_login = "";
  private $_loginError = "";
  private $_pwd = "";
  private $_pwdError = "";
  private $_pwd2 = "";
  private $_email = "";
  private $_emailError = "";
  private $_actived = false;
  private $_idTheme = 1;
  private $_idProfile = OPEN_PROFILE_DOCTOR; // by default doctor profile

  private $_trans; // to translate htmlspecialchars()

  public function User()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  public function validateData()
  {
    $valid = true;

    if ($this->_login == "")
    {
      $valid = false;
      $this->_loginError = _("This is a required field.");
    }

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

    if (trim($this->_email) != "")
    {
      if ( !preg_match("/@/", $this->_email) )
      {
        $valid = false;
        $this->_emailError = _("Invalid email, no @ symbol in string.");
      }
      else
      {
        list($user, $host) = preg_split("/@/", $this->_email);

        if ( (empty($user)) || (empty($host)) )
        {
          $valid = false;
          $this->_emailError = sprintf(_("Missing data [%s]@[%s]"), $user, $host);
        }

        if (preg_match("/[ \t]/", $user) || preg_match("/[ \t]/", $host))
        {
          $valid = false;
          $this->_emailError = sprintf(_("Whitespace in [%s]@[%s]"), $user, $host);
        }
      }
    }

    return $valid;
  }

  /**
   * bool validatePwd(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  public function validatePwd()
  {
    $valid = true;

    if (md5("") == $this->_pwd)
    {
      $valid = false;
      $this->_pwdError = _("This field can't be empty if you want to change password.");
    }
    /*elseif (strlen($this->_pwd) < 4)
    {
      $valid = false;
      $this->_pwdError = sprintf(_("This field must be at least %d characters."), 4);
    }
    elseif (substr_count($this->_pwd, " ") > 0)
    {
      $valid = false;
      $this->_pwdError = _("This field must not contain any spaces.");
    }*/
    elseif ($this->_pwd != $this->_pwd2)
    {
      $valid = false;
      $this->_pwdError = _("Passwords do not match.");
    }

    return $valid;
  }

  /**
   * int getIdUser(void)
   *
   * @return int id user
   * @access public
   */
  public function getIdUser()
  {
    return intval($this->_idUser);
  }

  /**
   * void setIdUser(int $value)
   *
   * @param int $value id user
   * @return void
   * @access public
   */
  public function setIdUser($value)
  {
    $this->_idUser = intval($value);
  }

  /**
   * int getIdMember(void)
   *
   * @return int id member user
   * @access public
   */
  public function getIdMember()
  {
    return intval($this->_idMember);
  }

  /**
   * void setIdMember(int $value)
   *
   * @param int $value id member user
   * @return void
   * @access public
   */
  public function setIdMember($value)
  {
    $this->_idMember = intval($value);
  }

  /**
   * string getLogin(void)
   *
   * @return string User login
   * @access public
   */
  public function getLogin()
  {
    return stripslashes(strtr($this->_login, $this->_trans));
  }

  /**
   * string getLoginError(void)
   *
   * @return string login error text
   * @access public
   */
  public function getLoginError()
  {
    return $this->_loginError;
  }

  /**
   * void setLogin(string $value)
   *
   * @param string $value login user
   * @return void
   * @access public
   */
  public function setLogin($value)
  {
    $value = strtolower($value); // sure?
    $this->_login = Check::safeText($value);
  }

  /**
   * string getPwd(void)
   *
   * @return string User password
   * @access public
   */
  public function getPwd()
  {
    return $this->_pwd;
  }

  /**
   * string getPwdError(void)
   *
   * @return string password error text
   * @access public
   */
  public function getPwdError()
  {
    return $this->_pwdError;
  }

  /**
   * void setPwd(string $value)
   *
   * @param string $value user's password
   * @return void
   * @access public
   */
  public function setPwd($value)
  {
    $this->_pwd = Check::safeText($value);
  }

  /**
   * string getPwd2(void)
   *
   * @return string user's password confirmation
   * @access public
   */
  public function getPwd2()
  {
    return $this->_pwd2;
  }

  /**
   * void setPwd2(string $value)
   *
   * @param string $value password confirmation
   * @return void
   * @access public
   */
  public function setPwd2($value)
  {
    $this->_pwd2 = Check::safeText($value);
  }

  /**
   * string getEmail(void)
   *
   * @return string user email
   * @access public
   */
  public function getEmail()
  {
    return stripslashes(strtr($this->_email, $this->_trans));
  }

  /**
   * string getEmailError(void)
   *
   * @return string email error text
   * @access public
   */
  public function getEmailError()
  {
    return $this->_emailError;
  }

  /**
   * void setEmail(string $value)
   *
   * @param string $value email of user
   * @return void
   * @access public
   */
  public function setEmail($value)
  {
    $this->_email = Check::safeText($value);
  }

  /**
   * bool isActived(void)
   *
   * @return boolean user is actived?
   * @access public
   */
  public function isActived()
  {
    return ($this->_actived == true);
  }

  /**
   * void setActived(mixed $value)
   *
   * @param mixed $value true if user is actived
   * @return void
   * @access public
   */
  public function setActived($value)
  {
    if (gettype($value) == 'string')
    {
      $value = strtolower($value);
    }
    $this->_actived = ($value == 1 || $value == 'on' || $value == 'y' || $value == 'yes');
  }

  /**
   * int getIdTheme(void)
   *
   * @return int id theme
   * @access public
   */
  public function getIdTheme()
  {
    return intval($this->_idTheme);
  }

  /**
   * void setIdTheme(int $value)
   *
   * @param int $value
   * @return void
   * @access public
   */
  public function setIdTheme($value)
  {
    $temp = intval($value);
    $this->_idTheme = (($temp == 0) ? 1 : $temp);
  }

  /**
   * int getIdProfile(void)
   *
   * @return int id profile
   * @access public
   */
  public function getIdProfile()
  {
    return intval($this->_idProfile);
  }

  /**
   * void setIdProfile(int $value)
   *
   * @param int $value
   * @return void
   * @access public
   */
  public function setIdProfile($value)
  {
    $temp = intval($value);
    $this->_idProfile = (($temp == 0) ? OPEN_PROFILE_DOCTOR : $temp);
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
    return $this->getLogin();
  }
} // end class
?>
