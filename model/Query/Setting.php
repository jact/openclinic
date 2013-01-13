<?php
/**
 * Setting.php
 *
 * Contains the class Query_Setting
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Setting.php,v 1.5 2013/01/13 14:25:55 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../Setting.php");

/**
 * Query_Setting data access component for setting table
 *
 * Methods:
 *  bool Query_Setting(array $dsn = null)
 *  bool select(void)
 *  mixed fetch(void)
 *  bool update(Setting $set)
 *  bool updateTheme(int $idTheme)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Setting extends Query
{
  /**
   * bool Query_Setting(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function Query_Setting($dsn = null)
  {
    $this->_table = "setting_tbl";
    $this->_primaryKey = null;

    $this->_map = array(
      'clinic_name' => array('mutator' => 'setClinicName'),
      'clinic_hours' => array('mutator' => 'setClinicHours'),
      'clinic_address' => array('mutator' => 'setClinicAddress'),
      'clinic_phone' => array('mutator' => 'setClinicPhone'),
      'clinic_url' => array('mutator' => 'setClinicUrl'),
      'session_timeout' => array('mutator' => 'setSessionTimeout'),
      'items_per_page' => array('mutator' => 'setItemsPerPage'),
      'version' => array('mutator' => 'setVersion'),
      'language' => array('mutator' => 'setLanguage'),
      'id_theme' => array('mutator' => 'setIdTheme')
    );

    return parent::Query($dsn);
  }

  /**
   * bool select(void)
   *
   * Executes a query
   *
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function select()
  {
    $sql = "SELECT * FROM " . $this->_table;

    return $this->exec($sql);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Setting object.
   *
   * @return Setting returns settings object or false if no more rows to fetch
   * @access public
   */
  public function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $setting = new Setting();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $setting->$setProp(urldecode($value));
      }
    }

    return $setting;
  }

  /**
   * bool update(Setting $set)
   *
   * Update the row in the setting table.
   *
   * @param Setting $set settings object to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function update($set)
  {
    if ( !$set instanceof Setting )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "clinic_name=?, "
         . "clinic_hours=?, "
         . "clinic_address=?, "
         . "clinic_phone=?, "
         . "clinic_url=?, "
         . "language=?, "
         . "session_timeout=?, "
         . "items_per_page=?;";

    $params = array(
      urlencode($set->getClinicName()),
      urlencode($set->getClinicHours()),
      urlencode($set->getClinicAddress()),
      urlencode($set->getClinicPhone()),
      urlencode($set->getClinicUrl()),
      $set->getLanguage(),
      $set->getSessionTimeout(),
      $set->getItemsPerPage()
    );

    return $this->exec($sql, $params);
  }

  /**
   * bool updateTheme(int $idTheme)
   *
   * Update theme in the row in the settings table.
   *
   * @param int $idTheme key of theme to make it default application theme
   * @return boolean returns false, if error occurs
   * @access public
   */
  public function updateTheme($idTheme)
  {
    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "id_theme=" . intval($idTheme) . ";";

    return $this->exec($sql);
  }
} // end class
?>
