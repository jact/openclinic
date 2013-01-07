<?php
/**
 * Form.php
 *
 * Contains the class Form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Form.php,v 1.27 2013/01/07 18:38:35 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @todo more helpers (dates, files...)
 */

require_once(dirname(__FILE__) . "/HTML.php");
if (file_exists(dirname(__FILE__) . "/../model/Query/Description.php"))
{
  include_once(dirname(__FILE__) . "/../model/Query/Description.php");
}

/**
 * Form::unsetSession constants
 */
define("OPEN_UNSET_ALL",        0);
define("OPEN_UNSET_ONLY_ERROR", 1);
define("OPEN_UNSET_ONLY_VAR",   2);

/**
 * Form set of HTML form tags functions
 *
 * Methods:
 *  string text(string $name, string $value = null, array $attribs = null)
 *  string password(string $name, string $value = null, array $attribs = null)
 *  string select(string $name, array &$array, mixed $defaultValue = null, array $attribs = null)
 *  string textArea(string $name, string $value = null, array $attribs = null)
 *  string hidden(string $name, string $value = null, array $attribs = null)
 *  string checkBox(string $name, mixed $value = null, array $attribs = null)
 *  string radioButton(string $name, mixed $value = null, array $attribs = null)
 *  string button(string $name, string $value = null, array $attribs = null)
 *  string file(string $name, string $value = null, array $attribs = null)
 *  string selectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  string label(string $name, string $value = null, array $attribs = null)
 *  string fieldset(string $legend, array &$body, array $foot = null, $options = null)
 *  string generateToken(void)
 *  void compareToken(string $url, string $method = 'post')
 *  void unsetSession(int $option = OPEN_UNSET_ALL)
 *  void setSession(array $var, array $error = null)
 *  mixed getSession(void)
 *  string errorMsg(void)
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Form
{
  /**
   * string text(string $name, string $value = null, array $attribs = null)
   *
   * Returns input html tag of type text or password.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *  example:
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'size' => 20,
   *      'maxlength' => 20,
   *      'readonly' => true,
   *      'type' => 'password', // text by default
   *      'error' => 'The field is required',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   */
  public static function text($name, $value = null, $attribs = null)
  {
    $attribs['type']  = isset($attribs['type']) ? $attribs['type'] : 'text';
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);
    if (isset($attribs['size']) && !isset($attribs['maxlength']) )
    {
      $attribs['maxlength'] = $attribs['size'];
    }
    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $attribs['class'] = isset($attribs['class']) ? $attribs['class'] . ' error' : 'error';
    }

    $_html = HTML::start('input', $attribs, true);

    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $_html .= HTML::message($attribs['error'], OPEN_MSG_ERROR, false);
    }

    return $_html;
  }

  /**
   * string password(string $name, string $value = null, array $attribs = null)
   *
   * Returns input html tag of type password.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $attribs (optional) JavaScript event handlers, ...
   * @return string input html tag
   * @access public
   * @static
   * @since 0.8
   */
  public static function password($name, $value = null, $attribs = null)
  {
    $attribs['type'] = 'password';

    return self::text($name, $value, $attribs);
  }

  /**
   * string select(string $name, array &$array, mixed $defaultValue = null, array $attribs = null)
   *
   * Returns select html tag based in an array.
   *
   * @param string $name name of the select tag
   * @param array $array data of the select tag
   * @param mixed $defaultValue (optional) array or string or int
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $attribs = array(
   *      'id' => 'address', // id = name by default
   *      'size' => 20,
   *      'disabled' => true,
   *      'error' => 'The field is required',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string select html tag
   * @access public
   * @static
   * @since 0.7
   */
  public static function select($name, &$array, $defaultValue = null, $attribs = null)
  {
    $size = isset($attribs['size']) ? $attribs['size'] : 0;
    $attribs['id'] = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name'] = $name . ($size > 0 ? '[]' : '');
    if (isset($attribs['size']) && $attribs['size'] > 0)
    {
      $attribs['multiple'] = true;
    }
    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $attribs['class'] = (isset($attribs['class']) ? $attribs['class'] . ' error' : 'error');
    }
    $html = HTML::start('select', $attribs) . PHP_EOL;

    foreach ($array as $key => $value)
    {
      $options = null;
      if (is_array($value))
      {
        $html .= HTML::start('optgroup', array('label' => $key));
        foreach ($value as $optKey => $optValue)
        {
          $options['value'] = $optKey;
          if ($size > 0 && is_array($defaultValue) && in_array($optKey, $defaultValue))
          {
            $options['selected'] = true;
          }
          elseif ($defaultValue == $optKey)
          {
            $options['selected'] = true;
          }
          $html .= HTML::tag('option', $value != '' ? $optValue : '&nbsp;', $options) . PHP_EOL;
        }
        $html .= HTML::end('optgroup');
      }
      else
      {
        $options['value'] = $key;
        if ($size > 0 && is_array($defaultValue) && in_array($key, $defaultValue))
        {
          $options['selected'] = true;
        }
        elseif ($defaultValue == $key)
        {
          $options['selected'] = true;
        }
        $html .= HTML::tag('option', $value != '' ? $value : '&nbsp;', $options) . PHP_EOL;
      }
    }
    $html .= HTML::end('select');

    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $html .= HTML::message($attribs['error'], OPEN_MSG_ERROR, false);
    }

    return $html;
  }

  /**
   * string textArea(string $name, string $value = null, array $attribs = null)
   *
   * Returns textarea html tag.
   *
   * @param string $name name of the textarea tag
   * @param string $value (optional) value of the textarea tag
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'rows' => int, // 24 by default
   *      'cols' => int, // 80 by default
   *      'disabled' => true,
   *      'error' => 'The field is required',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string textarea html tag
   * @access public
   * @static
   * @since 0.7
   */
  public static function textArea($name, $value = null, $attribs = null)
  {
    $attribs['id']   = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name'] = $name;
    $attribs['rows'] = isset($attribs['rows']) ? intval($attribs['rows']) : 24;
    $attribs['cols'] = isset($attribs['cols']) ? intval($attribs['cols']) : 80;
    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $attribs['class'] = (isset($attribs['class']) ? $attribs['class'] . ' error' : 'error');
    }
    $_html = HTML::tag('textarea', $value, $attribs);

    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $_html .= HTML::message($attribs['error'], OPEN_MSG_ERROR, false);
    }

    return $_html;
  }

  /**
   * string hidden(string $name, string $value = null, array $attribs = null)
   *
   * Returns input html tag of type hidden.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'class' => 'no_print',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.2
   */
  public static function hidden($name, $value = null, $attribs = null)
  {
    $attribs['type']  = 'hidden';
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);

    return HTML::start('input', $attribs, true);
  }

  /**
   * string checkBox(string $name, mixed $value = null, array $attribs = null)
   *
   * Returns input html tag of type checkbox.
   *
   * @param string $name name of input field
   * @param mixed $value input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'checked' => bool, // false if not exists
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.4
   */
  public static function checkBox($name, $value = null, $attribs = null)
  {
    $attribs['type']  = 'checkbox';
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);
    if (isset($attribs['checked']) && $attribs['checked'] === true)
    {
      $attribs['checked'] = true;
    }
    else
    {
      unset($attribs['checked']);
    }

    return HTML::start('input', $attribs, true);
  }

  /**
   * string radioButton(string $name, mixed $value = null, array $attribs = null)
   *
   * Returns input html tag of type radio button.
   *
   * @param string $name name of input field
   * @param mixed $value (optional) input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'checked' => bool, // false if not exists
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.6
   */
  public static function radioButton($name, $value = null, $attribs = null)
  {
    $attribs['type']  = 'radio';
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);
    if (isset($attribs['checked']) && $attribs['checked'] === true)
    {
      $attribs['checked'] = true;
    }
    else
    {
      unset($attribs['checked']);
    }

    return HTML::start('input', $attribs, true);
  }

  /**
   * string button(string $name, string $value = null, array $attribs = null)
   *
   * Returns input html tag of type button.
   *
   * @param string $name name of input field
   * @param string $value input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $attribs = array(
   *      'id' => 'address', // id = name by default
   *      'type' => 'submit|reset|button', // 'submit' by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.6
   */
  public static function button($name, $value = null, $attribs = "")
  {
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);
    if ( !isset($attribs['type']) ) // possible values: 'submit', 'reset', 'button'
    {
      $attribs['type'] = 'submit';
    }

    return HTML::start('input', $attribs, true);
  }

  /**
   * string file(string $name, string $value = null, array $attribs = null)
   *
   * Returns input html tag of type file.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $attribs = array(
   *      'id' => 'address', // id = name by default
   *      'size' => int, // empty by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'error' => 'This field is required',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.6
   * @todo $error
   */
  public static function file($name, $value = "", $attribs = null)
  {
    $attribs['type']  = 'file';
    $attribs['id']    = isset($attribs['id']) ? $attribs['id'] : $name;
    $attribs['name']  = $name;
    $attribs['value'] = htmlspecialchars($value);
    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $attribs['class'] = isset($attribs['class']) ? $attribs['class'] . ' error' : 'error';
    }

    $_html = HTML::start('input', $attribs, true);

    if (isset($attribs['error']) && !empty($attribs['error']))
    {
      $_html .= HTML::message($attribs['error'], OPEN_MSG_ERROR, false);
    }

    return $_html;
  }

  /**
   * string selectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
   *
   * Returns select html tag.
   *
   * @param string $tableName
   * @param string $fieldCode
   * @param string $defaultValue (optional) selected value
   * @param string $fieldDescription (optional)
   * @param int $size (optional) size of the select html tag
   * @return string select html tag
   * @access public
   * @static
   * @since 0.1
   */
  public static function selectTable($tableName, $fieldCode, $defaultValue = "", $fieldDescription = "", $size = 0)
  {
    $desQ = new Query_Description();
    if ( !$desQ->select($tableName, $fieldCode, $fieldDescription) )
    {
      return; // no rows, no select
    }

    $options['id'] = $fieldCode;
    $options['name'] = $fieldCode . ($size > 0 ? '[]' : '');
    if ($size > 0)
    {
      $options['multiple'] = true;
      $options['size'] = intval($size);
    }
    $html = HTML::start('select', $options) . PHP_EOL;

    while ($aux = $desQ->fetch())
    {
      $array = null;
      if ( !empty($fieldDescription) )
      {
        $array['value'] = $aux->getCode();
        if ($aux->getCode() == $defaultValue)
        {
          $array['selected'] = true;
        }
        $html .= HTML::tag('option', $aux->getDescription(), $array) . PHP_EOL;
      }
    }
    $html .= HTML::end('select');

    $desQ->close();
    unset($desQ);

    return $html;
  }

  /**
   * string label(string $name, string $value = null, array $attribs = null)
   *
   * Returns label html tag.
   *
   * @param string $name field
   * @param string $value (optional)
   * @param array $attribs (optional) JavaScript event handlers, class attribute, etc
   *    $attribs = array(
   *      'id' => 'address',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string label html tag
   * @access public
   * @static
   * @since 0.8
   */
  public static function label($name, $value = null, $attribs = null)
  {
    $attribs['for'] = $name;
    if (isset($attribs['class']) && strpos($attribs['class'], 'required') !== false)
    {
      $value = '* ' . $value;
    }
    $_html = HTML::tag('label', $value, $attribs) . PHP_EOL;

    return $_html;
  }

  /**
   * string fieldset(string $legend, array &$body, array $foot = null, $options = null)
   *
   * Returns html fieldset
   * Options example:
   *   $options = array(
   *     'class' => 'center large', // fieldset class
   *    'r1' => array('class' => 'date'), // row number (starts in zero) class
   *   );
   *
   * @param string $legend legend of fieldset
   * @param array &$body set of labels and fields
   * @param array $foot (optional) buttons or another thing
   * @param array $options (optional) options of fieldset
   * @return string html fieldset
   * @access public
   * @static
   */
  public static function fieldset($legend, &$body, $foot = null, $options = null)
  {
    $_html = '';
    if (count($body) == 0)
    {
      return $_html; // no data, no fieldset
    }

    $_fieldsetOptions = null;
    if (isset($options['id']))
    {
      $_fieldsetOptions['id'] = $options['id'];
    }
    if (isset($options['class']))
    {
      $_fieldsetOptions['class'] = $options['class'];
    }
    $_html .= HTML::start('fieldset', $_fieldsetOptions) . PHP_EOL;

    if ( !empty($legend) )
    {
      $_html .= HTML::tag('legend', $legend) . PHP_EOL;
    }

    if (count($body) > 0)
    {
      $_numRow = 0;
      foreach ($body as $_row)
      {
        $_rowOptions = null;
        if (isset($options['r' . $_numRow]['class']))
        {
          $_rowOptions['class'] = $options['r' . $_numRow]['class'];
        }
        $_html .= HTML::tag('div', $_row, $_rowOptions);
        $_numRow++;
      }
    }

    if (count($foot) > 0)
    {
      $_footText = '';
      foreach ($foot as $_row)
      {
        $_footText .= $_row;
      }
      $_html .= HTML::tag('div', $_footText, array('class' => 'action'));
    }

    $_html .= HTML::end('fieldset');

    unset($body);

    return $_html;
  }

  /**
   * string generateToken(void)
   *
   * Anti-form automation attacks and session hijacking system
   * Chris Shiflett (Essential PHP Security)
   *
   * @return string input hidden field to include in form
   * @access public
   * @static
   */
  public static function generateToken()
  {
    $token = md5(uniqid(rand(), true));
    $_SESSION['form']['token'] = $token;

    return self::hidden('token_form', $token);
  }

  /**
   * void compareToken(string $url, string $method = 'post')
   *
   * Anti-form automation attacks and session hijacking system
   * Chris Shiflett (Essential PHP Security)
   *
   * @param string $url web address to redirect if fails
   * @param string $method (optional) form method
   * @return void
   * @access public
   * @static
   */
  public static function compareToken($url, $method = 'post')
  {
    if ($method != 'post' && $method != 'get')
    {
      header("Location: " . $url);
      exit();
    }

    $token = ($method == 'post') ? $_POST['token_form'] : $_GET['token_form'];

    if ( !isset($_SESSION['form']['token']) )
    {
      $_SESSION['form']['token'] = md5(uniqid(rand(), true));
    }

    if ($_SESSION['form']['token'] != $token)
    {
      unset($_SESSION['form']['token']);
      header("Location: " . $url);
      exit();
    }

    unset($_SESSION['form']['token']);
  }

  /**
   * void unsetSession(int $option = OPEN_UNSET_ALL)
   *
   * Unset form session variables
   *
   * @param int $option (optional)
   * @return void
   * @access public
   * @static
   */
  public static function unsetSession($option = OPEN_UNSET_ALL)
  {
    switch ($option)
    {
      case OPEN_UNSET_ONLY_VAR:
        unset($_SESSION['form']['var']);
        break;

      case OPEN_UNSET_ONLY_ERROR:
        unset($_SESSION['form']['error']);
        break;

      default:
        unset($_SESSION['form']['var']);
        unset($_SESSION['form']['error']);
        break;
    }
  }

  /**
   * void setSession(array $var, array $error = null)
   *
   * Set form session variables
   *
   * @param array $var
   * @param array $error (optional)
   * @return void
   * @access public
   * @static
   */
  public static function setSession($var, $error = null)
  {
    $_SESSION['form']['var'] = $var;
    if ($error != null)
    {
      $_SESSION['form']['error'] = $error;
    }
  }

  /**
   * mixed getSession(void)
   *
   * @return mixed array of form session variables or null if not exists
   * @access public
   * @static
   */
  public static function getSession()
  {
    return isset($_SESSION['form']) ? $_SESSION['form'] : null;
  }

  /**
   * string errorMsg(void)
   *
   * Returns message of form errors if it is necessary
   *
   * @return string
   * @access public
   * @static
   */
  public static function errorMsg()
  {
    $_formSession = self::getSession();
    if (isset($_formSession['error']) && count($_formSession['error']) > 0)
    {
      $_html = HTML::start('div', array('class' => 'error'));
      $_html .= HTML::para(_("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message."));

      $_array = null;
      foreach ($_formSession['error'] as $_key => $_value)
      {
        if ($_value)
        {
          $_array[] = self::label($_key, $_value);
        }
      }
      if (is_array($_array))
      {
        $_html .= HTML::itemList($_array);
      }

      $_html .= HTML::end('div');

      return $_html;
    }
  }
} // end class
?>
