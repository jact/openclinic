<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Setting_Query.php,v 1.2 2004/04/18 14:40:46 jact Exp $
 */

/**
 * Setting_Query.php
 ********************************************************************
 * Contains the class Setting_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");
require_once("../classes/Setting.php");

/**
 * Setting_Query data access component for setting table
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  bool select(void)
 *  mixed fetchSettings(void)
 *  bool update(Setting $set)
 *  bool updateTheme(int $idTheme)
 */
class Setting_Query extends Query
{
  /**
   * bool select(void)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @return boolean returns false, if error occurs
   * @access public
   */
  function select()
  {
    $sql = "SELECT * FROM setting_tbl";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing config settings information.";
    }

    return $result;
  }

  /**
   * mixed fetchSettings(void)
   ********************************************************************
   * Fetches a row from the query result and populates the Setting object.
   ********************************************************************
   * @return Setting returns settings object or false if no more rows to fetch
   * @access public
   */
  function fetchSettings()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $set = new Setting();
    $set->setClinicName(urldecode($array["clinic_name"]));
    $set->setClinicImageUrl(urldecode($array["clinic_image_url"]));
    $set->setUseImage($array["use_image"] == 'Y');
    $set->setClinicHours(urldecode($array["clinic_hours"]));
    $set->setClinicAddress(urldecode($array["clinic_address"]));
    $set->setClinicPhone(urldecode($array["clinic_phone"]));
    $set->setClinicUrl(urldecode($array["clinic_url"]));
    $set->setSessionTimeout($array["session_timeout"]);
    $set->setItemsPerPage($array["items_per_page"]);
    $set->setVersion($array["version"]);
    $set->setLanguage($array["language"]);
    $set->setIdTheme($array["id_theme"]);

    return $set;
  }

  /**
   * bool update(Setting $set)
   ********************************************************************
   * Update a the row in the setting table.
   ********************************************************************
   * @param Setting $set settings object to update
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($set)
  {
    $sql = "UPDATE setting_tbl SET ";
    $sql .= "clinic_name='" . urlencode($set->getClinicName()) . "', ";
    $sql .= "clinic_image_url='" . urlencode($set->getClinicImageUrl()) . "', ";
    $sql .= "use_image=" . ($set->isUseImageSet() ? "'Y', " : "'N', ");
    $sql .= "clinic_hours='" . urlencode($set->getClinicHours()) . "', ";
    $sql .= "clinic_address='" . urlencode($set->getClinicAddress()) . "', ";
    $sql .= "clinic_phone='" . urlencode($set->getClinicPhone()) . "', ";
    $sql .= "clinic_url='" . urlencode($set->getClinicUrl()) . "', ";
    $sql .= "language='" . $set->getLanguage() . "', ";
    $sql .= "session_timeout=" . $set->getSessionTimeout() . ", ";
    $sql .= "items_per_page=" . $set->getItemsPerPage() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating config settings information";
    }

    return $result;
  }

  /**
   * bool updateTheme(int $idTheme)
   ********************************************************************
   * Update theme in the row in the settings table.
   ********************************************************************
   * @param int $idTheme key of theme to make it default application theme
   * @return boolean returns false, if error occurs
   * @access public
   */
  function updateTheme($idTheme)
  {
    $sql = "UPDATE setting_tbl SET ";
    $sql .= "id_theme=" . intval($idTheme) . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating clinic theme in use";
    }

    return $result;
  }
} // end class
?>
