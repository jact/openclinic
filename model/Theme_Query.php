<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Theme_Query.php,v 1.7 2004/08/05 14:18:51 jact Exp $
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
 *  bool existCSSFile(string $file, int $idTheme = 0)
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
    $sql .= " GROUP BY 1,2,3";
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
    $theme->setCSSFile(urldecode($array["css_file"]));

    if (isset($array["row_count"]))
    {
      $theme->setCount(intval($array["row_count"]));
    }

    return $theme;
  }

  /**
   * bool existCSSFile(string $file, int $idTheme = 0)
   ********************************************************************
   * Executes a query
   ********************************************************************
   * @param string $file filename to know if exists
   * @param int $idTheme (optional) key of theme
   * @global array $reservedCSSFiles
   * @return boolean returns true if file already exists or false if error occurs
   * @access public
   */
  function existCSSFile($file, $idTheme = 0)
  {
    global $reservedCSSFiles;

    if (in_array($file, $reservedCSSFiles))
    {
      $this->_error = "That filename is reserved for internal use.";
      return false;
    }

    $sql = "SELECT COUNT(css_file)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE css_file='" . urlencode($file) . "'";
    if ($idTheme > 0)
    {
      $sql .= " AND id_theme<>" . intval($idTheme);
    }

    $result = $this->exec($sql);
    if ($result == false)
    {
      $this->_error = "Error checking for dup file.";
      return false;
    }

    $array = $this->fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  function _writeCSSFile($cssFile, $cssRules)
  {
    $filename = '../css/' . $cssFile;
    $fp = fopen($filename, 'wb');
    if ( !$fp )
    {
      $this->_error = "Error creating css file.";
      return false;
    }

    $size = strlen($cssRules);
    if ( !fwrite($fp, $cssRules, $size) )
    {
      $this->_error = "Error writting css file.";
      return false;
    }
    fclose($fp);

    @chmod($filename, 0644); // without execution permissions if it is possible

    return true;
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
    if ( !is_a($theme, "Theme") )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    if ( !$this->_writeCSSFile($theme->getCSSFile(), $theme->getCSSRules()) )
    {
      return false;
    }

    $sql = "INSERT INTO " . $this->_table . " VALUES (NULL, ";
    $sql .= "'" . urlencode($theme->getThemeName()) . "', ";
    $sql .= "'" . urlencode($theme->getCSSFile()) . "');";

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
    if ( !is_a($theme, "Theme") )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    $isDupFile = $this->existCSSFile($theme->getCSSFile(), $theme->getIdTheme());
    if ($this->isError())
    {
      return false;
    }

    if ($isDupFile)
    {
      $this->_isError = true;
      $this->_error = "File is already in use.";
      return false;
    }

    if ( !$this->_writeCSSFile($theme->getCSSFile(), $theme->getCSSRules()) )
    {
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "theme_name='" . urlencode($theme->getThemeName()) . "', ";
    $sql .= "css_file='" . urlencode($theme->getCSSFile()) . "'";
    $sql .= " WHERE id_theme=" . $theme->getIdTheme();

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
