<?php
/**
 * Test.php
 *
 * Contains the class Query_Test
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Test.php,v 1.3 2013/01/07 18:03:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../Test.php");

/**
 * Query_Test data access component for medical tests
 *
 * Methods:
 *  bool Query_Test(array $dsn = null)
 *  mixed getLastId(void)
 *  mixed select(int $idProblem, int $idTest = 0)
 *  mixed fetch(void)
 *  bool insert(Test $test)
 *  bool update(Test $test)
 *  bool delete(int $idTest)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Test extends Query
{
  /**
   * bool Query_Test(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Test($dsn = null)
  {
    $this->_table = "medical_test_tbl";
    $this->_primaryKey = array("id_test");

    $this->_map = array(
      'id_test' => array('mutator' => 'setIdTest'),
      'id_problem' => array('mutator' => 'setIdProblem'),
      'document_type' => array('mutator' => 'setDocumentType'),
      'path_filename' => array('mutator' => 'setPathFilename')
    );

    return parent::Query($dsn);
  }

  /**
   * mixed getLastId(void)
   *
   * Executes a query
   *
   * @return mixed if error occurs returns false, else last insert id
   * @access public
   * @since 0.3
   */
  function getLastId()
  {
    $sql = "SELECT LAST_INSERT_ID() AS last_id";
    $sql .= " FROM " . $this->_table;

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow();
    return ($array == false ? 0 : $array["last_id"]);
  }

  /**
   * mixed select(int $idProblem, int $idTest = 0)
   *
   * Executes a query
   *
   * @param int $idProblem key of medical problem
   * @param int $idTest (optional) key of medical test
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idProblem, $idTest = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE id_problem=" . intval($idProblem);
    if ($idTest)
    {
      $sql .= " AND id_test=" . intval($idTest);
    }

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Test object.
   *
   * @return Test returns medical test or false if no more tests to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $test = new Test();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $test->$setProp(urldecode($value));
      }
    }

    return $test;
  }

  /**
   * bool insert(Test $test)
   *
   * Inserts a new medical test into the medical tests table.
   *
   * @param Test $test medical test to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($test)
  {
    if ( !$test instanceof Test )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "INSERT INTO " . $this->_table;
    $sql .= " (id_test, id_problem, document_type, path_filename) VALUES (NULL, ?, ?, ?);";

    $params = array(
      $test->getIdProblem(),
      urlencode($test->getDocumentType()),
      urlencode($test->getPathFilename())
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool update(Test $test)
   *
   * Update a medical test in the medical tests table.
   *
   * @param Test $test medical test to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($test)
  {
    if ( !$test instanceof Test )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "document_type=?, "
         . "path_filename=? "
         . "WHERE id_test=?;";

    $params = array(
      urlencode($test->getDocumentType()),
      urlencode($test->getPathFilename()),
      $test->getIdTest()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool delete(int $idTest)
   *
   * Delete a row in the medical test table.
   *
   * @param int $idTest
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idTest)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_test=" . intval($idTest);

    return $this->exec($sql);
  }
} // end class
?>
