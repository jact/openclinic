<?php
/**
 * Test.php
 *
 * Contains the class Medical Test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Test.php,v 1.10 2007/10/28 19:42:58 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");

/*
 * Test represents a medical test for a medical problem
 *
 * Methods:
 *  bool validateData(void)
 *  int getIdTest(void)
 *  void setIdTest(string $value)
 *  int getIdProblem(void)
 *  void setIdProblem(string $value)
 *  string getDocumentType(void)
 *  void setDocumentType(string $value)
 *  string getPathFilename(boolean $withPath = true)
 *  string getPathFilenameError(void)
 *  void setPathFilename(string $value)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Test
{
  var $_idTest = 0;
  var $_idProblem = 0;
  var $_documentType = "";
  var $_pathFilename = "";
  var $_pathFilenameError = "";

  var $_trans; // to translate htmlspecialchars()

  function Test()
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

    if ($this->_pathFilename == "")
    {
      $valid = false;
      $this->_pathFilenameError = _("This is a required field.");
    }

    return $valid;
  }

  /**
   * int getIdTest(void)
   *
   * @return int id medical test
   * @access public
   */
  function getIdTest()
  {
    return intval($this->_idTest);
  }

  /**
   * void setIdTest(int $value)
   *
   * @param int $value id medical test
   * @return void
   * @access public
   */
  function setIdTest($value)
  {
    $this->_idTest = intval($value);
  }

  /**
   * int getIdProblem(void)
   *
   * @return int id medical problem
   * @access public
   */
  function getIdProblem()
  {
    return intval($this->_idProblem);
  }

  /**
   * void setIdProblem(int $value)
   *
   * @param int $value id medical problem
   * @return void
   * @access public
   */
  function setIdProblem($value)
  {
    $this->_idProblem = intval($value);
  }

  /**
   * string getDocumentType(void)
   *
   * @return string Document Type
   * @access public
   */
  function getDocumentType()
  {
    return stripslashes(strtr($this->_documentType, $this->_trans));
  }

  /**
   * void setDocumentType(string $value)
   *
   * @param string $value Document Type
   * @return void
   * @access public
   */
  function setDocumentType($value)
  {
    //$value = strtolower($value);
    $this->_documentType = Check::safeText($value);
  }

  /**
   * string getPathFilename(boolean $withPath = true)
   *
   * @param boolean $withPath indicates if path is also returned or not
   * @return string path file name
   * @access public
   */
  function getPathFilename($withPath = true)
  {
    /*if (eregi("\\\\", $this->_pathFilename))
    {
      return stripslashes($this->_pathFilename);
    }
    else
    {
      return $this->_pathFilename;
    }*/
    //return((eregi("\\\\", $this->_pathFilename) ? stripslashes($this->_pathFilename) : $this->_pathFilename));
    //return $this->_pathFilename;
    //return htmlspecialchars(ereg_replace('\\+', '\\', $this->_pathFilename)); // be care with &
    //return stripslashes($this->_pathFilename);

    $value = strtr(ereg_replace('\\+', '\\', $this->_pathFilename), $this->_trans); // be care with &
    if ( !$withPath )
    {
      $value = substr($value, strrpos($value, "/") + 1);
    }

    return $value;
  }

  /**
   * string getPathFilenameError(void)
   *
   * @return string path file name error text
   * @access public
   */
  function getPathFilenameError()
  {
    return $this->_pathFilenameError;
  }

  /**
   * void setPathFilename(string $value)
   *
   * @param string $value path file name
   * @return void
   * @access public
   */
  function setPathFilename($value)
  {
    $value = ereg_replace('\"', '', $value); // To Opera navigators
    $this->_pathFilename = trim(ereg_replace('\\+', '\\', $value));
  }
} // end class
?>
