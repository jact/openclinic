<?php
/**
 * Registry.php
 *
 * Contains the class Registry
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Registry.php,v 1.1 2008/02/10 12:35:36 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

/**
 * Registry is a generic storage class that helps to manage global data (inspired in Zend_Registry)
 *
 * Methods:
 *  mixed getInstance(void)
 *  void unsetInstance(void)
 *  mixed get(string $index)
 *  bool isRegistered(string $index)
 *  void set(string $index, mixed $value)
 *  void delete(string $index)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Registry
{
  //private static $_registry = null; // PHP5

  /**
   * mixed getInstance(void)
   *
   * @return contents of registry
   * @access public
   * @static
   */
  function getInstance()
  {
    return $_SESSION['registry'];
    //return self::$_registry; // PHP5
  }

  /**
   * void unsetInstance(void)
   *
   * @return void
   * @access public
   * @static
   */
  function unsetInstance()
  {
    $_SESSION['registry'] = null;
    //self::$_registry = null; // PHP5
  }

  /**
   * mixed get(string $index)
   *
   * @param string $index
   * @return content of $index if exists, null otherwise
   * @access public
   * @static
   */
  function get($index)
  {
    return (Registry::isRegistered($index)) ? $_SESSION['registry'][$index] : null;
    //return (self::isRegistered($index)) ? self::$_registry[$index] : null; // PHP5
  }

  /**
   * bool isRegistered(string $index)
   *
   * @param string $index
   * @return bool
   * @access public
   * @static
   */
  function isRegistered($index)
  {
    return isset($_SESSION['registry'][$index]);
    //return isset(self::$_registry[$index]); // PHP5
  }

  /**
   * void set(string $index, mixed $value)
   *
   * @param string $index
   * @param mixed $value
   * @return void
   * @access public
   * @static
   */
  function set($index, $value)
  {
    $_SESSION['registry'][$index] = $value;
    //self::$_registry[$index] = $value; // PHP5
  }

  /**
   * void delete(string $index)
   *
   * @param string $index
   * @return void
   * @access public
   * @static
   */
  function delete($index)
  {
    unset($_SESSION['registry'][$index]);
    //unset(self::$_registry[$index]); // PHP5
  }
}
?>