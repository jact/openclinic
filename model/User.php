<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: User.php,v 1.5 2005/07/20 20:24:48 jact Exp $
 */

/**
 * User.php
 *
 * Contains the class User
 *
 * Author: jact <jachavar@gmail.com>
 */

require_once("../lib/Check.php");

/*
 * User represents an application user.
 *
 * @author jact <jachavar@gmail.com>
 * @access public
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
 *  void setActived(bool $value)
 *  int getIdTheme(void)
 *  void setIdTheme(int $value)
 *  int getIdProfile(void)
 *  void setIdProfile(int $value)
 */
class User
{
  var $_idUser = 0;
  var $_idMember = 0;
  var $_login = "";
  var $_loginError = "";
  var $_pwd = "";
  var $_pwdError = "";
  var $_pwd2 = "";
  var $_email = "";
  var $_emailError = "";
  var $_actived = false;
  var $_idTheme = 1;
  var $_idProfile = OPEN_PROFILE_DOCTOR; // by default doctor profile

  var $_trans; // to translate htmlspecialchars()

  function User()
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
  }

  /*
   * bool validateData(void)
   *
   * @return boolean true if data is valid, otherwise false.
   * @access public
   */
  function validateData()
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
    elseif (ereg("['\\]", $this->_login))
    {
      $valid = false;
      $this->_loginError = sprintf(_("This field can't contain the symbols %s"), "'\\");
    }

    if (trim($this->_email) != "")
    {
      if ( !ereg("@", $this->_email) )
      {
        $valid = false;
        $this->_emailError = _("Invalid email, no @ symbol in string.");
      }
      else
      {
        list($user, $host) = split("@", $this->_email);

        if ( (empty($user)) || (empty($host)) )
        {
          $valid = false;
          $this->_emailError = sprintf(_("Missing data [%s]@[%s]"), $user, $host);
        }

        if(ereg("[ \t]", $user) || ereg("[ \t]", $host))
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
  function validatePwd()
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
  function getIdUser()
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
  function setIdUser($value)
  {
    $this->_idUser = intval($value);
  }

  /**
   * int getIdMember(void)
   *
   * @return int id member user
   * @access public
   */
  function getIdMember()
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
  function setIdMember($value)
  {
    $this->_idMember = intval($value);
  }

  /**
   * string getLogin(void)
   *
   * @return string User login
   * @access public
   */
  function getLogin()
  {
    return stripslashes(strtr($this->_login, $this->_trans));
  }

  /**
   * string getLoginError(void)
   *
   * @return string login error text
   * @access public
   */
  function getLoginError()
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
  function setLogin($value)
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
  function getPwd()
  {
    return $this->_pwd;
  }

  /**
   * string getPwdError(void)
   *
   * @return string password error text
   * @access public
   */
  function getPwdError()
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
  function setPwd($value)
  {
    $this->_pwd = Check::safeText($value);
  }

  /**
   * string getPwd2(void)
   *
   * @return string user's password confirmation
   * @access public
   */
  function getPwd2()
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
  function setPwd2($value)
  {
    $this->_pwd2 = Check::safeText($value);
  }

  /**
   * string getEmail(void)
   *
   * @return string user email
   * @access public
   */
  function getEmail()
  {
    return stripslashes(strtr($this->_email, $this->_trans));
  }

  /**
   * string getEmailError(void)
   *
   * @return string email error text
   * @access public
   */
  function getEmailError()
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
  function setEmail($value)
  {
    $this->_email = Check::safeText($value);
  }

  /**
   * bool isActived(void)
   *
   * @return boolean user is actived?
   * @access public
   */
  function isActived()
  {
    return ($this->_actived == true);
  }

  /**
   * void setActived(bool $value)
   *
   * @param boolean $value true if user is actived
   * @return void
   * @access public
   */
  function setActived($value)
  {
    $this->_actived = ($value == true);
  }

  /**
   * int getIdTheme(void)
   *
   * @return int id theme
   * @access public
   */
  function getIdTheme()
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
  function setIdTheme($value)
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
  function getIdProfile()
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
  function setIdProfile($value)
  {
    $temp = intval($value);
    $this->_idProfile = (($temp == 0) ? OPEN_PROFILE_DOCTOR : $temp);
  }
} // end class
?>
