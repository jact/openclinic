<?php
/**
 * Registry.php
 *
 * Contains the class Registry
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Registry.php,v 1.2 2013/01/07 18:36:43 jact Exp $
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
  private static $_registry = null;

  /**
   * mixed getInstance(void)
   *
   * @return contents of registry
   * @access public
   * @static
   */
  public static function getInstance()
  {
    return self::$_registry;
  }

  /**
   * void unsetInstance(void)
   *
   * @return void
   * @access public
   * @static
   */
  public static function unsetInstance()
  {
    self::$_registry = null;
  }

  /**
   * mixed get(string $index)
   *
   * @param string $index
   * @return content of $index if exists, null otherwise
   * @access public
   * @static
   */
  public static function get($index)
  {
    return (self::isRegistered($index)) ? self::$_registry[$index] : null;
  }

  /**
   * bool isRegistered(string $index)
   *
   * @param string $index
   * @return bool
   * @access public
   * @static
   */
  public static function isRegistered($index)
  {
    return isset(self::$_registry[$index]);
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
  public static function set($index, $value)
  {
    self::$_registry[$index] = $value;
  }

  /**
   * void delete(string $index)
   *
   * @param string $index
   * @return void
   * @access public
   * @static
   */
  public static function delete($index)
  {
    unset(self::$_registry[$index]);
  }
}
?>
