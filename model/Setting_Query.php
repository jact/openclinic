<?php
/**
 * Setting_Query.php
 *
 * Contains the class Setting_Query
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Setting_Query.php,v 1.12 2006/03/28 19:06:40 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../classes/Query.php");
require_once("../classes/Setting.php");

/**
 * Setting_Query data access component for setting table
 *
 * Methods:
 *  void Setting_Query(void)
 *  bool select(void)
 *  mixed fetch(void)
 *  bool update(Setting $set)
 *  bool updateTheme(int $idTheme)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Setting_Query extends Query
{
  /**
   * void Setting_Query(void)
   *
   * Constructor function
   *
   * @return void
   * @access public
   */
  function Setting_Query()
  {
    $this->_table = "setting_tbl";
    $this->_primaryKey = null;

    $this->_map = array(
      'clinic_name' => array('mutator' => 'setClinicName'),
      'clinic_image_url' => array('mutator' => 'setClinicImageUrl'),
      'use_image' => array('mutator' => 'setUseImage'),
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
  }

  /**
   * bool select(void)
   *
   * Executes a query
   *
   * @return boolean returns false, if error occurs
   * @access public
   */
  function select()
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
  function fetch()
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
  function update($set)
  {
    if (function_exists("is_a") && !is_a($set, "Setting") ) // SF.net DEMO version PHP 4.1.2
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET "
         . "clinic_name=?, "
         . "clinic_image_url=?, "
         . "use_image=?, "
         . "clinic_hours=?, "
         . "clinic_address=?, "
         . "clinic_phone=?, "
         . "clinic_url=?, "
         . "language=?, "
         . "session_timeout=?, "
         . "items_per_page=?;";

    $params = array(
      urlencode($set->getClinicName()),
      urlencode($set->getClinicImageUrl()),
      ($set->isUseImageSet() ? "Y" : "N"),
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
  function updateTheme($idTheme)
  {
    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "id_theme=" . intval($idTheme) . ";";

    return $this->exec($sql);
  }
} // end class
?>
