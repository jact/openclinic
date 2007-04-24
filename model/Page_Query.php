<?php
/**
 * Page_Query.php
 *
 * Contains the class Page_Query (pagination methods)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Page_Query.php,v 1.8 2007/04/24 21:30:20 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @author    Jorge López Herranz <lopez.herranz@gmail.com>
 */

require_once("../model/Query.php");

/**
 * Page_Query contains pagination methods and properties
 *
 * Methods:
 *  void setItemsPerPage(int $value)
 *  int getCurrentRow(void)
 *  int getRowCount(void)
 *  int getPageCount(void)
 *  void _resetStats(int $page)
 *  void _incrementRow(void)
 *  void _calculateStats(int $rowCount, int $limitFrom = 0)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @author Jorge López Herranz <lopez.herranz@gmail.com>
 * @access public
 * @see OPEN_SETTING_ITEMS_PER_PAGE
 * @since 0.8
 */
class Page_Query extends Query
{
  // (These variables are 'protected'. Only can be used by inherited classes)
  var $_itemsPerPage = OPEN_SETTING_ITEMS_PER_PAGE;
  var $_rowNumber = 0;
  var $_currentRow = 0;
  var $_currentPage = 0;
  var $_rowCount = 0;
  var $_pageCount = 0;

  /**
   * void setItemsPerPage(int $value)
   *
   * @param int $value
   * @access public
   */
  function setItemsPerPage($value)
  {
    $this->_itemsPerPage = intval($value);
  }

  /**
   * int getCurrentRow(void)
   *
   * @return int
   * @access public
   */
  function getCurrentRow()
  {
    return intval($this->_currentRow);
  }

  /**
   * int getRowCount(void)
   *
   * @return int
   * @access public
   */
  function getRowCount()
  {
    return intval($this->_rowCount);
  }

  /**
   * int getPageCount(void)
   *
   * @return int
   * @access public
   */
  function getPageCount()
  {
    return intval($this->_pageCount);
  }

  /**
   * void _resetStats(int $page)
   *
   * @param int $page
   * @access private
   */
  function _resetStats($page)
  {
    $this->_rowNumber = 0;
    $this->_currentRow = 0;
    $this->_currentPage = ($page > 1) ? intval($page) : 1;
    $this->_rowCount = 0;
    $this->_pageCount = 0;
  }

  /**
   * void _incrementRow(void)
   *
   * @access private
   */
  function _incrementRow()
  {
    $this->_rowNumber = $this->_rowNumber + 1;
    $this->_currentRow = $this->_rowNumber + (($this->_currentPage - 1) * $this->_itemsPerPage);
  }

  /**
   * void _calculateStats(int $rowCount, int $limitFrom = 0)
   *
   * Calculate stats based on row count
   *
   * @param int $rowCount
   * @param int $limitFrom (optional)
   * @access private
   */
  function _calculateStats($rowCount, $limitFrom = 0)
  {
    $this->_rowCount = $rowCount;
    if ($limitFrom > 0 && $limitFrom < $this->_rowCount)
    {
      $this->_rowCount = $limitFrom;
    }
    $this->_pageCount = (intval($this->_itemsPerPage) > 0) ? ceil($this->_rowCount / $this->_itemsPerPage) : 1;
  }
} // end class
?>
