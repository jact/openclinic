<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Theme_Query.php,v 1.4 2004/07/24 16:34:23 jact Exp $
 */

/**
 * Theme_Query.php
 ********************************************************************
 * Contains the class Theme_Query
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

require_once("../classes/Query.php");
require_once("../classes/Theme.php");

/**
 * Theme_Query data access component for themes
 ********************************************************************
 * @author jact <jachavar@terra.es>
 * @access public
 ********************************************************************
 * Methods:
 *  void Theme_Query(void)
 *  mixed select(int $idTheme = 0)
 *  mixed selectWithStats(int $idTheme = 0)
 *  mixed fetch(void)
 *  bool insert(Theme $theme)
 *  bool update(Theme $theme)
 *  bool delete(int $idTheme)
 */
class Theme_Query extends Query
{
  /**
   * void Theme_Query(void)
   ********************************************************************
   * Constructor function
   ********************************************************************
   * @return void
   * @access public
   */
  function Theme_Query()
  {
    $this->_table = "theme_tbl";
  }

  /**
   * mixed select(int $idTheme = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idTheme key of theme to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($idTheme = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    if ($idTheme > 0)
    {
      $sql .= " WHERE id_theme=" . intval($idTheme);
    }
    $sql .= " ORDER BY theme_name";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing theme information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed selectWithStats(int $idTheme = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param int $idTheme key of theme to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectWithStats($idTheme = 0)
  {
    $sql = "SELECT " . $this->_table . ".*, count(user_tbl.id_user) AS row_count";
    $sql .= " FROM " . $this->_table . " LEFT JOIN user_tbl ON " . $this->_table . ".id_theme=user_tbl.id_theme";
    if ($idTheme > 0)
    {
      $sql .= " WHERE " . $this->_table . ".id_theme=" . intval($idTheme);
    }
    $sql .= " GROUP BY 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27";
    $sql .= " ORDER BY " . $this->_table . ".theme_name;";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error accessing theme information.";
      return false;
    }

    return $this->numRows();
  }

  /**
   * mixed fetch(void)
   ********************************************************************
   * Fetches a row from the query result and populates the Theme object.
   ********************************************************************
   * @return Theme returns theme or false if no more themes to fetch
   * @access public
   */
  function fetch()
  {
    $array = $this->fetchRow();
    if ($array == false)
    {
      return false;
    }

    $theme = new Theme();
    $theme->setIdTheme(intval($array["id_theme"]));
    $theme->setThemeName(urldecode($array["theme_name"]));

    $theme->setTitleBgColor(urldecode($array["title_bg_color"]));
    $theme->setTitleFontFamily(urldecode($array["title_font_family"]));
    $theme->setTitleFontSize(intval($array["title_font_size"]));
    $theme->setTitleFontBold($array["title_font_bold"] == "Y");
    $theme->setTitleFontColor(urldecode($array["title_font_color"]));
    $theme->setTitleAlign(urldecode($array["title_align"]));

    $theme->setBodyBgColor(urldecode($array["body_bg_color"]));
    $theme->setBodyFontFamily(urldecode($array["body_font_family"]));
    $theme->setBodyFontSize(intval($array["body_font_size"]));
    $theme->setBodyFontColor(urldecode($array["body_font_color"]));
    $theme->setBodyLinkColor(urldecode($array["body_link_color"]));

    $theme->setErrorColor(urldecode($array["error_color"]));

    $theme->setNavbarBgColor(urldecode($array["navbar_bg_color"]));
    $theme->setNavbarFontFamily(urldecode($array["navbar_font_family"]));
    $theme->setNavbarFontSize(intval($array["navbar_font_size"]));
    $theme->setNavbarFontColor(urldecode($array["navbar_font_color"]));
    $theme->setNavbarLinkColor(urldecode($array["navbar_link_color"]));

    $theme->setTabBgColor(urldecode($array["tab_bg_color"]));
    $theme->setTabFontFamily(urldecode($array["tab_font_family"]));
    $theme->setTabFontSize(intval($array["tab_font_size"]));
    $theme->setTabFontColor(urldecode($array["tab_font_color"]));
    $theme->setTabLinkColor(urldecode($array["tab_link_color"]));
    $theme->setTabFontBold($array["tab_font_bold"] == "Y");

    $theme->setTableBorderColor(urldecode($array["table_border_color"]));
    $theme->setTableBorderWidth(intval($array["table_border_width"]));
    $theme->setTableCellPadding(intval($array["table_cell_padding"]));

    if (isset($array["row_count"]))
    {
      $theme->setCount(intval($array["row_count"]));
    }

    return $theme;
  }

  /**
   * bool insert(Theme $theme)
   ********************************************************************
   * Inserts a new theme into the theme table.
   ********************************************************************
   * @param Theme $theme theme to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($theme)
  {
    $sql = "INSERT INTO " . $this->_table . " VALUES (NULL, ";
    $sql .= "'" . urlencode($theme->getThemeName()) . "', ";

    $sql .= "'" . urlencode($theme->getTitleBgColor()) . "', ";
    $sql .= "'" . urlencode($theme->getTitleFontFamily()) . "', ";
    $sql .= $theme->getTitleFontSize() . ", ";
    $sql .= ($theme->isTitleFontBold() ? "'Y', " : "'N', ");
    $sql .= "'" . urlencode($theme->getTitleFontColor()) . "', ";
    $sql .= "'" . $theme->getTitleAlign() . "', ";

    $sql .= "'" . urlencode($theme->getBodyBgColor()) . "', ";
    $sql .= "'" . urlencode($theme->getBodyFontFamily()) . "', ";
    $sql .= $theme->getBodyFontSize() . ", ";
    $sql .= "'" . urlencode($theme->getBodyFontColor()) . "', ";
    $sql .= "'" . urlencode($theme->getBodyLinkColor()) . "', ";

    $sql .= "'" . urlencode($theme->getErrorColor()) . "', ";

    $sql .= "'" . urlencode($theme->getNavbarBgColor()) . "', ";
    $sql .= "'" . urlencode($theme->getNavbarFontFamily()) . "', ";
    $sql .= $theme->getNavbarFontSize() . ", ";
    $sql .= "'" . urlencode($theme->getNavbarFontColor()) . "', ";
    $sql .= "'" . urlencode($theme->getNavbarLinkColor()) . "', ";

    $sql .= "'" . urlencode($theme->getTabBgColor()) . "', ";
    $sql .= "'" . urlencode($theme->getTabFontFamily()) . "', ";
    $sql .= $theme->getTabFontSize() . ", ";
    $sql .= ($theme->isTabFontBold() ? "'Y', " : "'N', ");
    $sql .= "'" . urlencode($theme->getTabFontColor()) . "', ";
    $sql .= "'" . urlencode($theme->getTabLinkColor()) . "', ";

    $sql .= "'" . urlencode($theme->getTableBorderColor()) . "', ";
    $sql .= $theme->getTableBorderWidth() . ", ";
    $sql .= $theme->getTableCellPadding() . ");";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error inserting new look and feel theme.";
    }

    return $result;
  }

  /**
   * bool update(Theme $theme)
   ********************************************************************
   * Update a theme in the theme table.
   ********************************************************************
   * @param Theme $theme theme to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($theme)
  {
    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "theme_name='" . urlencode($theme->getThemeName()) . "', ";

    $sql .= "title_bg_color='" . urlencode($theme->getTitleBgColor()) . "', ";
    $sql .= "title_font_family='" . urlencode($theme->getTitleFontFamily()) . "', ";
    $sql .= "title_font_size=" . $theme->getTitleFontSize() . ", ";
    $sql .= "title_font_bold=" . ($theme->isTitleFontBold() ? "'Y', " : "'N', ");
    $sql .= "title_font_color='" . urlencode($theme->getTitleFontColor()) . "', ";
    $sql .= "title_align='" . $theme->getTitleAlign() . "', ";

    $sql .= "body_bg_color='" . urlencode($theme->getBodyBgColor()) . "', ";
    $sql .= "body_font_family='" . urlencode($theme->getBodyFontFamily()) . "', ";
    $sql .= "body_font_size=" . $theme->getBodyFontSize() . ", ";
    $sql .= "body_font_color='" . urlencode($theme->getBodyFontColor()) . "', ";
    $sql .= "body_link_color='" . urlencode($theme->getBodyLinkColor()) . "', ";

    $sql .= "error_color='" . urlencode($theme->getErrorColor()) . "', ";

    $sql .= "navbar_bg_color='" . urlencode($theme->getNavbarBgColor()) . "', ";
    $sql .= "navbar_font_family='" . urlencode($theme->getNavbarFontFamily()) . "', ";
    $sql .= "navbar_font_size=" . $theme->getNavbarFontSize() . ", ";
    $sql .= "navbar_font_color='" . urlencode($theme->getNavbarFontColor()) . "', ";
    $sql .= "navbar_link_color='" . urlencode($theme->getNavbarLinkColor()) . "', ";

    $sql .= "tab_bg_color='" . urlencode($theme->getTabBgColor()) . "', ";
    $sql .= "tab_font_family='" . urlencode($theme->getTabFontFamily()) . "', ";
    $sql .= "tab_font_size=" . $theme->getTabFontSize() . ", ";
    $sql .= "tab_font_bold=" . ($theme->isTabFontBold() ? "'Y', " : "'N', ");
    $sql .= "tab_font_color='" . urlencode($theme->getTabFontColor()) . "', ";
    $sql .= "tab_link_color='" . urlencode($theme->getTabLinkColor()) . "', ";

    $sql .= "table_border_color='" . urlencode($theme->getTableBorderColor()) . "', ";
    $sql .= "table_border_width=" . $theme->getTableBorderWidth() . ", ";
    $sql .= "table_cell_padding=" . $theme->getTableCellPadding();
    $sql .= " WHERE id_theme=" . $theme->getIdTheme() . ";";

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error updating look and feel theme.";
    }

    return $result;
  }

  /**
   * bool delete(int $idTheme)
   ********************************************************************
   * Deletes a theme from the theme table.
   ********************************************************************
   * @param int $idTheme key of theme to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($idTheme)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_theme=" . intval($idTheme);

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error deleting look and feel theme.";
    }

    return $result;
  }
} // end class
?>
