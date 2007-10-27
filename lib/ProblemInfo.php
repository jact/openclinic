<?php
/**
 * ProblemInfo.php
 *
 * Contains the class ProblemInfo
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: ProblemInfo.php,v 1.1 2007/10/27 17:27:35 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../model/Problem_Page_Query.php");
require_once("../lib/HTML.php");
require_once("../lib/misc_lib.php");
require_once("../lib/I18n.php");

/**
 * ProblemInfo data access component for a medical problem of a patient
 *
 * Methods:
 *  void ProblemInfo(int $id)
 *  mixed getObject(void)
 *  string getWording(void)
 *  void showHeader(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class ProblemInfo
{
  var $_problem = null; // problem object
  var $_wording = ''; // preview wording

  /**
   * void ProblemInfo(int $id)
   *
   * Constructor
   *
   * @param int $id medical problem identificator
   * @return void
   * @access public
   * @see fieldPreview
   */
  function ProblemInfo($id)
  {
    $_problemQ = new Problem_Page_Query();
    $_problemQ->connect();

    if ( !$_problemQ->select($id) )
    {
      $_problemQ->close();

      return;
    }

    $this->_problem = $_problemQ->fetch();
    if ( !$this->_problem )
    {
      $_problemQ->close();

      return;
    }

    $this->_wording = fieldPreview($this->_problem->getWording());

    $_problemQ->freeResult();
    $_problemQ->close();
  }

  /**
   * mixed getObject(void)
   *
   * Returns medical problem object if not null
   *
   * @return mixed medical problem object or null
   * @access public
   */
  function getObject()
  {
    return $this->_problem;
  }

  /**
   * string getWording(void)
   *
   * Returns preview wording of the medical problem
   *
   * @return string preview wording of the medical problem
   * @access public
   */
  function getWording()
  {
    return $this->_wording;
  }

  /**
   * void showHeader(void)
   *
   * Draws a header with medical problem information
   *
   * @return void
   * @access public
   */
  function showHeader()
  {
    if ( !$this->_problem )
    {
      return;
    }

    HTML::start('div', array('id' => 'problemHeader', 'class' => 'clearfix'));
    HTML::para(_("Wording") . ': ' . $this->_wording);
    HTML::para(
      _("Opening Date") . ': ' . I18n::localDate($this->_problem->getOpeningDate()),
      array('class' => 'right')
    );
    HTML::para(
      _("Last Update Date") . ': ' . I18n::localDate($this->_problem->getLastUpdateDate()),
      array('class' => 'right')
    );
    HTML::end('div');
  }
} // end class
?>
