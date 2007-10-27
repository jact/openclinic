<?php
/**
 * LastViewedPatient.php
 *
 * Contains the class LastViewedPatient
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: LastViewedPatient.php,v 1.1 2007/10/27 16:11:26 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * LastViewedPatient set of functions to manage visited patients array
 *
 * Methods:
 *  void add(int $idPatient, string $name)
 *  void delete(int $idPatient)
 *  mixed get(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class LastViewedPatient
{
  /**
   * void add(int $idPatient, string $name)
   *
   * Add a visited patient to the list
   *
   * @param int $idPatient key of patient
   * @param string $name complete name of patient
   * @return void
   * @access public
   * @static
   * @see OPEN_DEMO, OPEN_VISITED_ITEMS
   */
  function add($idPatient, $name)
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return;
    }

    $_SESSION['last_viewed_patient'][$idPatient] = $name;
    $_SESSION['last_viewed_patient'] = array_unique($_SESSION['last_viewed_patient']);

    if (sizeof($_SESSION['last_viewed_patient']) > OPEN_VISITED_ITEMS)
    {
      reset($_SESSION['last_viewed_patient']);
      $aux = array_keys($_SESSION['last_viewed_patient']);
      unset($_SESSION['last_viewed_patient'][$aux[0]]);
    }
  }

  /**
   * void delete(int $idPatient)
   *
   * Delete a visited patient from the list
   *
   * @param int $idPatient key of patient to show header
   * @return void
   * @access public
   * @static
   * @see OPEN_DEMO
   */
  function delete($idPatient)
  {
    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      return;
    }

    unset($_SESSION['last_viewed_patient'][$idPatient]);
  }

  /**
   * mixed get(void)
   *
   * Returns reverse array (FILO)
   *
   * @return mixed array of patients, or null if not exists
   * @access public
   * @static
   */
  function get()
  {
    if ( !isset($_SESSION['last_viewed_patient']) )
    {
      return null;
    }

    return array_reverse($_SESSION['last_viewed_patient'], true);
  }
} // end class
?>
