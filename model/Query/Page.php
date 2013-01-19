<?php
/**
 * Page.php
 *
 * Contains the class Query_Page (pagination methods)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Page.php,v 1.2 2013/01/19 10:27:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @author    Jorge López Herranz <lopez.herranz@gmail.com>
 */

require_once(dirname(__FILE__) . "/../Query.php");

/**
 * Query_Page contains pagination methods and properties
 *
 * Methods:
 *  void setItemsPerPage(int $value)
 *  int getCurrentRow(void)
 *  int getRowCount(void)
 *  int getPageCount(void)
 *  void _resetStats(int $page)
 *  void _incrementRow(void)
 *  void _calculateStats(int $rowCount, int $limitFrom = 0)
 *  string __toString(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @author Jorge López Herranz <lopez.herranz@gmail.com>
 * @access public
 * @see OPEN_SETTING_ITEMS_PER_PAGE
 * @since 0.8
 */
class Query_Page extends Query
{
  // (These variables are 'protected'. Only can be used by inherited classes)
  protected $_itemsPerPage = OPEN_SETTING_ITEMS_PER_PAGE;
  protected $_rowNumber = 0;
  protected $_currentRow = 0;
  protected $_currentPage = 0;
  protected $_rowCount = 0;
  protected $_pageCount = 0;

  /**
   * void setItemsPerPage(int $value)
   *
   * @param int $value
   * @access public
   */
  public function setItemsPerPage($value)
  {
    $this->_itemsPerPage = intval($value);
  }

  /**
   * int getCurrentRow(void)
   *
   * @return int
   * @access public
   */
  public function getCurrentRow()
  {
    return intval($this->_currentRow);
  }

  /**
   * int getRowCount(void)
   *
   * @return int
   * @access public
   */
  public function getRowCount()
  {
    return intval($this->_rowCount);
  }

  /**
   * int getPageCount(void)
   *
   * @return int
   * @access public
   */
  public function getPageCount()
  {
    return intval($this->_pageCount);
  }

  /**
   * void _resetStats(int $page)
   *
   * @param int $page
   * @access protected
   */
  protected function _resetStats($page)
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
   * @access protected
   */
  protected function _incrementRow()
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
   * @access protected
   */
  protected function _calculateStats($rowCount, $limitFrom = 0)
  {
    $this->_rowCount = $rowCount;
    if ($limitFrom > 0 && $limitFrom < $this->_rowCount)
    {
      $this->_rowCount = $limitFrom;
    }
    $this->_pageCount = (intval($this->_itemsPerPage) > 0) ? ceil($this->_rowCount / $this->_itemsPerPage) : 1;
  }

  /**
   * string __toString(void)
   *
   * @return string class name
   * @access public
   */
  public function __toString()
  {
    return __CLASS__;
  }
} // end class
?>
