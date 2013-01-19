<?php
/**
 * Test.php
 *
 * Contains the class Medical Test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Test.php,v 1.13 2013/01/19 10:28:37 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../lib/Check.php");
require_once(dirname(__FILE__) . "/Query/Test.php");

/*
 * Test represents a medical test for a medical problem
 *
 * Methods:
 *  mixed Test(int $idProblem = 0, int $idTest = 0)
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
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Test
{
  private $_idTest = 0;
  private $_idProblem = 0;
  private $_documentType = "";
  private $_pathFilename = "";
  private $_pathFilenameError = "";

  private $_trans; // to translate htmlspecialchars()

  /**
   * mixed Test(int $idProblem = 0, int $idTest = 0)
   *
   * Constructor
   *
   * @param int $idProblem (optional) medical problem identificator
   * @param int $idTest (optional) medical test identificator
   * @return mixed void if not argument, null if not exists problem, object otherwise
   * @access public
   */
  public function Test($idProblem = 0, $idTest = 0)
  {
    $this->_trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));

    if ($idProblem && $idTest)
    {
      $_testQ = new Query_Test();
      if ( !$_testQ->select($idProblem, $idTest) )
      {
        return null;
      }

      foreach (get_object_vars($_testQ->fetch()) as $key => $value)
      {
        $this->$key = $value;
      }

      $_testQ->freeResult();
      $_testQ->close();
    }
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
  public function getIdTest()
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
  public function setIdTest($value)
  {
    $this->_idTest = intval($value);
  }

  /**
   * int getIdProblem(void)
   *
   * @return int id medical problem
   * @access public
   */
  public function getIdProblem()
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
  public function setIdProblem($value)
  {
    $this->_idProblem = intval($value);
  }

  /**
   * string getDocumentType(void)
   *
   * @return string Document Type
   * @access public
   */
  public function getDocumentType()
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
  public function setDocumentType($value)
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
  public function getPathFilename($withPath = true)
  {
    /*if (preg_match("/\\\\/", $this->_pathFilename))
    {
      return stripslashes($this->_pathFilename);
    }
    else
    {
      return $this->_pathFilename;
    }*/
    //return((preg_match("/\\\\/", $this->_pathFilename) ? stripslashes($this->_pathFilename) : $this->_pathFilename));
    //return $this->_pathFilename;
    //return htmlspecialchars(preg_replace('/\\+/', '\\', $this->_pathFilename)); // be care with &
    //return stripslashes($this->_pathFilename);

    $value = strtr(preg_replace('/\\+/', '\\', $this->_pathFilename), $this->_trans); // be care with &
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
  public function getPathFilenameError()
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
  public function setPathFilename($value)
  {
    $value = preg_replace('/\"/', '', $value); // To Opera navigators
    $this->_pathFilename = trim(preg_replace('/\\+/', '\\', $value));
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
    return $this->getPathFilename();
  }
} // end class
?>
