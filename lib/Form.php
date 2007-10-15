<?php
/**
 * Form.php
 *
 * Contains the class Form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Form.php,v 1.19 2007/10/15 20:12:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../lib/HTML.php");
if (file_exists("../model/Description_Query.php"))
{
  include_once("../model/Description_Query.php");
}

/**
 * Form set of HTML form tags functions
 *
 * Methods:
 *  string strText(string $name, int $size, string $value = "", array $addendum = null)
 *  void text(string $name, int $size, string $value = "", array $addendum = null)
 *  string strPassword(string $name, int $size, string $value = "", array $addendum = null)
 *  void password(string $name, int $size, string $value = "", array $addendum = null)
 *  string strSelect(string $name, array &$array, mixed $defaultValue = null, array $addendum = null)
 *  void select(string $name, array &$array, mixed $defaultValue = null, array $addendum = null)
 *  string strTextArea(string $name, int $rows, int $cols, string $value = "", array $addendum = null)
 *  void textArea(string $name, int $rows, int $cols, string $value = "", array $addendum = null)
 *  string strHidden(string $name, string $value = "", array $addendum = null)
 *  void hidden(string $name, string $value = "", array $addendum = null)
 *  string strCheckBox(string $name, mixed $value, bool $checked = false, array $addendum = null)
 *  void checkBox(string $name, mixed $value, bool $checked = false, array $addendum = null)
 *  string strRadioButton(string $name, mixed $value, bool $checked = false, array $addendum = null)
 *  void radioButton(string $name, mixed $value, bool $checked = false, array $addendum = null)
 *  string strButton(string $name, string $value, string $type = "submit", array $addendum = null)
 *  void button(string $name, string $value, string $type = "submit", array $addendum = null)
 *  string strFile(string $name, string $value = "", int $size = 0, array $addendum = null)
 *  void file(string $name, string $value = "", int $size = 0, array $addendum = null)
 *  string strSelectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  void selectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  string strLabel(string $field, string $text, bool $required = false)
 *  void label(string $field, string $text, bool $required = false)
 *  string strFieldset(string $legend, array &$body, array $foot = null, $options = null)
 *  void fieldset(string $legend, array &$body, array $foot = null, $options = null)
 *  string generateToken(void)
 *  void compareToken(string $url, string $method = 'post')
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Form
{
  /**
   * string strText(string $name, int $size, string $value = "", array $addendum = null)
   *
   * Returns input html tag of type text or password.
   *
   * @param string $name name of input field
   * @param int $size size of text box
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *  example:
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
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
  function strText($name, $size, $value = "", $addendum = null)
  {
    $addendum['type'] = (isset($addendum['type']) ? $addendum['type'] : 'text');
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['size'] = intval($size);
    $addendum['maxlength'] = (isset($addendum['maxlength']) ? $addendum['maxlength'] : $size);
    $addendum['value'] = htmlspecialchars($value);
    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $addendum['class'] = (isset($addendum['class']) ? $addendum['class'] . ' error' : 'error');
    }

    $html = HTML::strStart('input', $addendum, true);

    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $html .= HTML::strMessage($addendum['error'], OPEN_MSG_ERROR, false);
    }

    return $html;
  }

  /**
   * void text(string $name, int $size, string $value = "", array $addendum = null)
   *
   * Draws input html tag of type text or password.
   *
   * @param string $name name of input field
   * @param int $size size of text box
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   */
  function text($name, $size, $value = "", $addendum = null)
  {
    echo Form::strText($name, $size, $value, $addendum);
  }

  /**
   * string strPassword(string $name, int $size, string $value = "", array $addendum = null)
   *
   * Returns input html tag of type password.
   *
   * @param string $name name of input field
   * @param int $size size of text box
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, ...
   * @return string input html tag
   * @access public
   * @static
   * @since 0.8
   */
  function strPassword($name, $size, $value = "", $addendum = null)
  {
    $addendum['type'] = 'password';

    return Form::strText($name, $size, $value, $addendum);
  }

  /**
   * void password(string $name, int $size, string $value = "", array $addendum = null)
   *
   * Draws input html tag of type password.
   *
   * @param string $name name of input field
   * @param int $size size of text box
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, ...
   * @return void
   * @access public
   * @static
   * @since 0.8
   */
  function password($name, $size, $value = "", $addendum = null)
  {
    $addendum['type'] = 'password';
    Form::text($name, $size, $value, $addendum);
  }

  /**
   * string strSelect(string $name, array &$array, mixed $defaultValue = null, array $addendum = null)
   *
   * Returns select html tag based in an array.
   *
   * @param string $name name of the select tag
   * @param array $array data of the select tag
   * @param mixed $defaultValue (optional) array or string or int
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
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
  function strSelect($name, &$array, $defaultValue = null, $addendum = null)
  {
    $size = isset($addendum['size']) ? $addendum['size'] : 0;
    $addendum['id'] = isset($addendum['id']) ? $addendum['id'] : $name;
    $addendum['name'] = $name . ($size > 0 ? '[]' : '');
    if (isset($addendum['size']) && $addendum['size'] > 0)
    {
      $addendum['multiple'] = true;
    }
    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $addendum['class'] = (isset($addendum['class']) ? $addendum['class'] . ' error' : 'error');
    }
    $html = HTML::strStart('select', $addendum) . PHP_EOL;

    foreach ($array as $key => $value)
    {
      $options = null;
      if (is_array($value))
      {
        $html .= HTML::strStart('optgroup', array('label' => $key));
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
          $html .= HTML::strTag('option', $value != '' ? $optValue : '&nbsp;', $options) . PHP_EOL;
        }
        $html .= HTML::strEnd('optgroup');
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
        $html .= HTML::strTag('option', $value != '' ? $value : '&nbsp;', $options) . PHP_EOL;
      }
    }
    $html .= HTML::strEnd('select');

    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $html .= HTML::strMessage($addendum['error'], OPEN_MSG_ERROR, false);
    }

    return $html;
  }

  /**
   * void select(string $name, array &$array, mixed $defaultValue = null, array $addendum = null)
   *
   * Draws select html tag based in an array.
   *
   * @param string $name name of the select tag
   * @param array $array data of the select tag
   * @param string $defaultValue (optional)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   */
  function select($name, &$array, $defaultValue = null, $addendum = null)
  {
    echo Form::strSelect($name, $array, isset($defaultValue) ? $defaultValue : null, isset($addendum) ? $addendum : null);
  }

  /**
   * string strTextArea(string $name, int $rows, int $cols, string $value = "", array $addendum = null)
   *
   * Returns textarea html tag.
   *
   * @param string $name name of the textarea tag
   * @param int $rows number of rows of the textarea tag
   * @param int $cols number of cols of the textarea tag
   * @param string $value (optional) value of the textarea tag
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
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
  function strTextArea($name, $rows, $cols, $value = "", $addendum = null)
  {
    $addendum['id'] = isset($addendum['id']) ? $addendum['id'] : $name;
    $addendum['name'] = $name;
    $addendum['rows'] = $rows;
    $addendum['cols'] = $cols;
    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $addendum['class'] = (isset($addendum['class']) ? $addendum['class'] . ' error' : 'error');
    }
    $html = HTML::strTag('textarea', $value, $addendum);

    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $html .= HTML::strMessage($addendum['error'], OPEN_MSG_ERROR, false);
    }

    return $html;
  }

  /**
   * void textArea(string $name, int $rows, int $cols, string $value = "", array $addendum = null)
   *
   * Draws textarea html tag.
   *
   * @param string $name name of the textarea tag
   * @param int $rows number of rows of the textarea tag
   * @param int $cols number of cols of the textarea tag
   * @param string $value (optional) value of the textarea tag
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   */
  function textArea($name, $rows, $cols, $value = "", $addendum = null)
  {
    echo Form::strTextArea($name, $rows, $cols, $value, $addendum);
  }

  /**
   * string strHidden(string $name, string $value = "", array $addendum = null)
   *
   * Returns input html tag of type hidden.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'class' => 'noPrint',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   */
  function strHidden($name, $value = "", $addendum = null)
  {
    $addendum['type'] = 'hidden';
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);

    return HTML::strStart('input', $addendum, true);
  }

  /**
   * void hidden(string $name, string $value = "", array $addendum = null)
   *
   * Draws input html tag of type hidden.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   * @since 0.2
   */
  function hidden($name, $value = "", $addendum = null)
  {
    echo Form::strHidden($name, $value, $addendum);
  }

  /**
   * string strCheckBox(string $name, mixed $value, bool $checked = false, array $addendum = null)
   *
   * Returns input html tag of type checkbox.
   *
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked (optional) if the field is checked or not (false by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   */
  function strCheckBox($name, $value, $checked = false, $addendum = null)
  {
    $addendum['type'] = 'checkbox';
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);
    if ($checked)
    {
      $addendum['checked'] = true;
    }

    return HTML::strStart('input', $addendum, true);
  }

  /**
   * void checkBox(string $name, mixed $value, bool $checked = false, array $addendum = null)
   *
   * Draws input html tag of type checkbox.
   *
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked (optional) if the field is checked or not (false by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   * @since 0.4
   */
  function checkBox($name, $value, $checked = false, $addendum = null)
  {
    echo Form::strCheckBox($name, $value, $checked, $addendum);
  }

  /**
   * string strRadioButton(string $name, mixed $value, bool $checked = false, array $addendum = null)
   *
   * Returns input html tag of type radio button.
   *
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked if the field is checked or not (false by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   */
  function strRadioButton($name, $value, $checked = false, $addendum = null)
  {
    $addendum['type'] = 'radio';
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);
    if ($checked)
    {
      $addendum['checked'] = true;
    }

    return HTML::strStart('input', $addendum, true);
  }

  /**
   * void radioButton(string $name, mixed $value, bool $checked = false, array $addendum = null)
   *
   * Draws input html tag of type radio button.
   *
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked if the field is checked or not (false by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   * @since 0.6
   */
  function radioButton($name, $value, $checked = false, $addendum = null)
  {
    echo Form::strRadioButton($name, $value, $checked, $addendum);
  }

  /**
   * string strButton(string $name, string $value, string $type = "submit", array $addendum = null)
   *
   * Returns input html tag of type button.
   *
   * @param string $name name of input field
   * @param string $value input value
   * @param string $type (optional) type of button (submit by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   */
  function strButton($name, $value, $type = "submit", $addendum = "")
  {
    $addendum['type'] = $type;
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);

    return HTML::strStart('input', $addendum, true);
  }

  /**
   * void button(string $name, string $value, string $type = "submit", array $addendum = null)
   *
   * Draws input html tag of type button.
   *
   * @param string $name name of input field
   * @param string $value input value
   * @param string $type (optional) type of button (submit by default)
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   * @since 0.6
   */
  function button($name, $value, $type = "submit", $addendum = "")
  {
    echo Form::strButton($name, $value, $type, $addendum);
  }

  /**
   * string strFile(string $name, string $value = "", int $size = 0, array $addendum = null)
   *
   * Returns input html tag of type file.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param int $size (optional) size of the input html tag
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   *    $addendum = array(
   *      'id' => 'address', // id = name by default
   *      'disabled' => true,
   *      'readonly' => true,
   *      'error' => 'This field is required',
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @static
   * @since 0.7
   * @todo $error
   */
  function strFile($name, $value = "", $size = 0, $addendum = null)
  {
    $addendum['type'] = 'file';
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);
    if ($size > 0)
    {
      $addendum['size'] = intval($size);
    }
    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $addendum['class'] = (isset($addendum['class']) ? $addendum['class'] . ' error' : 'error');
    }

    $html = HTML::strStart('input', $addendum, true);

    if (isset($addendum['error']) && !empty($addendum['error']))
    {
      $html .= HTML::strMessage($addendum['error'], OPEN_MSG_ERROR, false);
    }

    return $html;
  }

  /**
   * void file(string $name, string $value = "", int $size = 0, array $addendum = null)
   *
   * Draws input html tag of type file.
   *
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param int $size (optional) size of the input html tag
   * @param array $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @static
   * @since 0.6
   * @todo $error
   */
  function file($name, $value = "", $size = 0, $addendum = null)
  {
    echo Form::strFile($name, $value, $size, $addendum);
  }

  /**
   * string strSelectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
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
   * @since 0.7
   */
  function strSelectTable($tableName, $fieldCode, $defaultValue = "", $fieldDescription = "", $size = 0)
  {
    $desQ = new Description_Query();
    $desQ->connect();

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
    $html = HTML::strStart('select', $options) . PHP_EOL;

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
        $html .= HTML::strTag('option', $aux->getDescription(), $array) . PHP_EOL;
      }
    }
    $html .= HTML::strEnd('select');

    $desQ->close();
    unset($desQ);

    return $html;
  }

  /**
   * void selectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
   *
   * Draws select html tag.
   *
   * @param string $tableName
   * @param string $fieldCode
   * @param string $defaultValue (optional) selected value
   * @param string $fieldDescription (optional)
   * @param int $size (optional) size of the select html tag
   * @return void
   * @access public
   * @static
   */
  function selectTable($tableName, $fieldCode, $defaultValue = "", $fieldDescription = "", $size = 0)
  {
    echo Form::strSelectTable($tableName, $fieldCode, $defaultValue, $fieldDescription, $size);
  }

  /**
   * string strLabel(string $field, string $text, bool $required = false)
   *
   * Returns label html tag.
   *
   * @param string $field
   * @param string $text
   * @param bool $required (optional)
   * @return string label html tag
   * @access public
   * @static
   * @since 0.8
   */
  function strLabel($field, $text, $required = false)
  {
    $addendum['for'] = $field;
    if ($required)
    {
      $addendum['class'] = 'requiredField';
      $text = '* ' . $text;
    }
    $html = HTML::strTag('label', $text, $addendum) . PHP_EOL;

    return $html;
  }

  /**
   * void label(string $field, string $text, bool $required = false)
   *
   * Draws label html tag.
   *
   * @param string $field
   * @param string $text
   * @param bool $required (optional)
   * @return void
   * @access public
   * @static
   * @since 0.8
   */
  function label($field, $text, $required = false)
  {
    echo Form::strLabel($field, $text, $required);
  }

  /**
   * string strFieldset(string $legend, array &$body, array $foot = null, $options = null)
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
  function strFieldset($legend, &$body, $foot = null, $options = null)
  {
    $html = "";
    if (count($body) == 0)
    {
      return $html; // no data, no fieldset
    }

    $fieldsetOptions = null;
    if (isset($options['id']))
    {
      $fieldsetOptions['id'] = $options['id'];
    }
    if (isset($options['class']))
    {
      $fieldsetOptions['class'] = $options['class'];
    }
    $html .= HTML::strStart('fieldset', $fieldsetOptions) . PHP_EOL;

    if ( !empty($legend) )
    {
      $html .= HTML::strTag('legend', trim($legend)) . PHP_EOL;
    }

    if (count($body) > 0)
    {
      $numRow = 0;
      foreach ($body as $row)
      {
        $rowOptions = null;
        if (isset($options['r' . $numRow]['class']))
        {
          $rowOptions['class'] = $options['r' . $numRow]['class'];
        }
        $html .= HTML::strTag('div', $row, $rowOptions);
        $numRow++;
      }
    }

    if (count($foot) > 0)
    {
      $footText = '';
      foreach ($foot as $row)
      {
        $footText .= $row;
      }
      $html .= HTML::strTag('div', $footText, array('class' => 'formButton'));
    }

    $html .= HTML::strEnd('fieldset');

    unset($body);

    return $html;
  }

  /**
   * void fieldset(string $legend, array &$body, array $foot = null, $options = null)
   *
   * Draws html fieldset
   *
   * @param string $legend legend of fieldset
   * @param array &$body set of labels and fields
   * @param array $foot (optional) buttons or another thing
   * @param array $options (optional) options of fieldset
   * @return void
   * @access public
   * @static
   */
  function fieldset($legend, &$body, $foot = null, $options = null)
  {
    echo Form::strFieldset($legend, $body, isset($foot) ? $foot : null, isset($options) ? $options : null);
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
  function generateToken()
  {
    $token = md5(uniqid(rand(), true));
    $_SESSION['token_form'] = $token;

    return Form::strHidden('token_form', $token);
  }

  /**
   * void compareToken(string $url, string $method = 'post')
   *
   * Anti-form automation attacks and session hijacking system
   * Chris Shiflett (Essential PHP Security)
   *
   * @param string $url web address to redirect if fails
   * @param string $method (optional)
   * @return void
   * @access public
   * @static
   */
  function compareToken($url, $method = 'post')
  {
    if ($method != 'post' && $method != 'get')
    {
      header("Location: " . $url);
      exit();
    }

    $token = ($method == 'post') ? $_POST['token_form'] : $_GET['token_form'];

    if ( !isset($_SESSION['token_form']) )
    {
      $_SESSION['token_form'] = md5(uniqid(rand(), true));
    }

    if ($_SESSION['token_form'] != $token)
    {
      unset($_SESSION['token_form']);
      header("Location: " . $url);
      exit();
    }

    unset($_SESSION['token_form']);
  }
} // end class
?>
