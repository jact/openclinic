<?php
/**
 * Theme.php
 *
 * Contains the class Query_Theme
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Theme.php,v 1.4 2013/01/07 18:04:08 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");
require_once(dirname(__FILE__) . "/../Theme.php");

/**
 * Query_Theme data access component for themes
 *
 * Methods:
 *  bool Query_Theme(array $dsn = null)
 *  mixed select(int $id = 0)
 *  mixed selectWithStats(int $id = 0)
 *  mixed fetch(void)
 *  bool existCssFile(string $file, int $id = 0)
 *  bool _writeCssFile(string $cssFile, string $cssRules)
 *  bool insert(Theme $theme)
 *  bool update(Theme $theme)
 *  bool delete(int $id)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 */
class Query_Theme extends Query
{
  /**
   * bool Query_Theme(array $dsn = null)
   *
   * Constructor function
   *
   * @param array $dsn (optional) Data Source Name
   * @return boolean returns false, if error occurs
   * @access public
   */
  function Query_Theme($dsn = null)
  {
    $this->_table = "theme_tbl";
    $this->_primaryKey = array("id_theme");

    $this->_map = array(
      'id_theme' => array(/*'accessor' => 'getId',*/ 'mutator' => 'setId'),
      'theme_name' => array(/*'accessor' => 'getName',*/ 'mutator' => 'setName'),
      'css_file' => array(/*'accessor' => 'getCssFile',*/ 'mutator' => 'setCssFile'),
      'row_count' => array(/*'accessor' => 'getCount',*/ 'mutator' => 'setCount')
    );

    return parent::Query($dsn);
  }

  /**
   * mixed select(int $id = 0)
   *
   * Executes a query
   *
   * @param int $id key of theme to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function select($id = 0)
  {
    $sql = "SELECT *";
    $sql .= " FROM " . $this->_table;
    if ($id)
    {
      $sql .= " WHERE id_theme=" . intval($id);
    }
    $sql .= " ORDER BY theme_name";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed selectWithStats(int $id = 0)
   *
   * Executes a query
   *
   * @param int $id key of theme to select
   * @return mixed if error occurs returns false, else number of rows in the result
   * @access public
   */
  function selectWithStats($id = 0)
  {
    $sql = "SELECT " . $this->_table . ".*, COUNT(user_tbl.id_user) AS row_count";
    $sql .= " FROM " . $this->_table . " LEFT JOIN user_tbl ON " . $this->_table . ".id_theme=user_tbl.id_theme";
    if ($id)
    {
      $sql .= " WHERE " . $this->_table . ".id_theme=" . intval($id);
    }
    $sql .= " GROUP BY 1,2,3";
    $sql .= " ORDER BY " . $this->_table . ".theme_name;";

    return ($this->exec($sql) ? $this->numRows() : false);
  }

  /**
   * mixed fetch(void)
   *
   * Fetches a row from the query result and populates the Theme object.
   *
   * @return Theme returns theme or false if no more themes to fetch
   * @access public
   */
  function fetch()
  {
    $array = parent::fetchRow();
    if ($array == false)
    {
      return false;
    }

    $theme = new Theme();
    foreach ($array as $key => $value)
    {
      $setProp = $this->_map[$key]['mutator'];
      if ($setProp && $value)
      {
        $theme->$setProp(urldecode($value));
      }
    }

    return $theme;
  }

  /**
   * bool existCssFile(string $file, int $id = 0)
   *
   * Executes a query
   *
   * @param string $file filename to know if exists
   * @param int $id (optional) key of theme
   * @return boolean returns true if file already exists or false if error occurs
   * @access public
   */
  function existCssFile($file, $id = 0)
  {
    $theme = new Theme();
    if ($theme->isCssReserved($file))
    {
      $this->_error = "That filename is reserved for internal use.";

      return false;
    }
    unset($theme);

    $sql = "SELECT COUNT(css_file)";
    $sql .= " FROM " . $this->_table;
    $sql .= " WHERE css_file='" . urlencode($file) . "'";
    if ($id)
    {
      $sql .= " AND id_theme<>" . intval($id);
    }

    if ( !$this->exec($sql) )
    {
      return false;
    }

    $array = parent::fetchRow(MYSQL_NUM);

    return ($array[0] > 0);
  }

  /**
   * bool _writeCssFile(string $cssFile, string $cssRules)
   *
   * @param string $cssFile
   * @param string $cssRules
   * @return bool
   * @access private
   */
  function _writeCssFile($cssFile, $cssRules)
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
   *
   * Inserts a new theme into the theme table.
   *
   * @param Theme $theme theme to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function insert($theme)
  {
    if ( !$theme instanceof Theme )
    {
      $this->_error = "Argument is an inappropriate object.";
      return false;
    }

    if ( !$this->_writeCssFile($theme->getCssFile(), $theme->getCssRules()) )
    {
      return false;
    }

    $sql = "INSERT INTO " . $this->_table . " VALUES (NULL, ";
    $sql .= "'" . urlencode($theme->getName()) . "', ";
    $sql .= "'" . urlencode($theme->getCssFile()) . "');";

    return $this->exec($sql);
  }

  /**
   * bool update(Theme $theme)
   *
   * Update a theme in the theme table.
   *
   * @param Theme $theme theme to insert
   * @return boolean returns false, if error occurs
   * @access public
   */
  function update($theme)
  {
    if ( !$theme instanceof Theme )
    {
      $this->_error = "Argument is an inappropriate object.";

      return false;
    }

    if ($this->existCssFile($theme->getCssFile(), $theme->getId()))
    {
      $this->_isError = true;
      $this->_error = "File is already in use.";

      return false;
    }

    if ( !$this->_writeCssFile($theme->getCssFile(), $theme->getCssRules()) )
    {
      return false;
    }

    $sql = "UPDATE " . $this->_table . " SET ";
    $sql .= "theme_name='" . urlencode($theme->getName()) . "', ";
    $sql .= "css_file='" . urlencode($theme->getCssFile()) . "'";
    $sql .= " WHERE id_theme=" . $theme->getId();

    return $this->exec($sql);
  }

  /**
   * bool delete(int $id)
   *
   * Deletes a theme from the theme table.
   *
   * @param int $id key of theme to delete
   * @return boolean returns false, if error occurs
   * @access public
   */
  function delete($id)
  {
    $sql = "DELETE FROM " . $this->_table;
    $sql .= " WHERE id_theme=" . intval($id);

    return $this->exec($sql);
  }
} // end class
?>
