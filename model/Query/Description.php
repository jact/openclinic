<?php
/**
 * Description.php
 *
 * Contains the class Query_Description
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Description.php,v 1.2 2013/01/07 18:02:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../Description.php");

/**
 * Query_Description data access component for domain tables
 *
 * Methods:
 *  bool select(string $tableName, string $fieldCode, string $fieldDescription = "", string $keyValue = "")
 *  mixed fetch(void)
 *  array fetchAll(string $col = "")
 *  bool update(string $tableName, Description $des)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Description extends Query
{
  /**
   * bool select(string $tableName, string $fieldCode, string $fieldDescription = "", string $keyValue = "")
   *
   * Executes a query
   *
   * @param string $tableName table name to query
   * @param string $fieldCode code of row to fetch
   * @param string $fieldDescription (optional) description of row to fetch
   * @param string $keyValue (optional) value of fieldCode to fetch
   * @return boolean returns false, if error occurs
   * @access public
   */
  function select($tableName, $fieldCode, $fieldDescription = "", $keyValue = "")
  {
    $sql = "SELECT " . $fieldCode;
    if ($fieldDescription)
    {
      $sql .= "," . $fieldDescription;
    }
    $sql .= " FROM ".$tableName." ";
    if ($keyValue)
    {
      $sql .= " WHERE " . $fieldCode . "='" . urlencode($keyValue) . "'"; // in BD it is urlencodeaded
    }
    $sql .= " ORDER BY " . ($fieldDescription != "" ? $fieldDescription : $fieldCode);

    return $this->exec($sql);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Description object.
   *
   * @return List returns list object or false if no more list rows to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow(MYSQL_NUM);
    if ($array == false)
    {
      return false;
    }

    $des = new Description();
    $des->setCode($array[0]);
    if (isset($array[1]))
    {
      $des->setDescription(urldecode($array[1]));
    }

    return $des;
  }

  /**
   * array fetchAll(string $col = "")
   *
   * Fetches all rows from the query result.
   *
   * @param $col (optional) name of the field result
   * @return assocArray returns associative array containing domain codes and values.
   * @access public
   */
  function fetchAll($col = "")
  {
    if ($col == "")
    {
      $col = "description";
    }

    $assoc = array();
    while ($result = parent::fetchRow())
    {
      $assoc[$result["code"]] = $result[$col];
    }

    return $assoc;
  }

  /**
   * bool update(string $tableName, Description $des)
   *
   * Update a row in a domain table.
   *
   * @param string $tableName table name of domain table to query
   * @param Description $des row to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($tableName, $des)
  {
    if ( !$des instanceof Description )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $tableName . " SET ";
    $sql .= "description='" . urlencode($des->getDescription()) . "' ";
    if ($tableName == "profile_tbl")
    {
      $sql .= "WHERE id_profile=" . $des->getCode();
    }

    return $this->exec($sql);
  }
} // end class
?>
