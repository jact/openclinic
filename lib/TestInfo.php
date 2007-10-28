<?php
/**
 * TestInfo.php
 *
 * Contains the class TestInfo
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: TestInfo.php,v 1.2 2007/10/28 20:57:46 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../model/Query/Test.php");

/**
 * TestInfo data access component for a medical test of a medical problem
 *
 * Methods:
 *  void TestInfo(int $idProblem, int $idTest)
 *  mixed getObject(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class TestInfo
{
  var $_test = null; // test object

  /**
   * void TestInfo(int $idProblem, int $idTest)
   *
   * Constructor
   *
   * @param int $idProblem medical problem identificator
   * @param int $idTest medical test identificator
   * @return void
   * @access public
   * @see fieldPreview
   */
  function TestInfo($idProblem, $idTest)
  {
    $_testQ = new Query_Test();
    $_testQ->connect();

    if ( !$_testQ->select($idProblem, $idTest) )
    {
      $_testQ->close();

      return;
    }

    $this->_test = $_testQ->fetch();
    if ( !$this->_test )
    {
      $_testQ->close();

      return;
    }

    $_testQ->freeResult();
    $_testQ->close();
  }

  /**
   * mixed getObject(void)
   *
   * Returns medical test object if not null
   *
   * @return mixed medical test object or null
   * @access public
   */
  function getObject()
  {
    return $this->_test;
  }
} // end class
?>
