<?php
/**
 * Form.php
 *
 * Contains the class Form
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: Form.php,v 1.12 2006/03/26 17:41:14 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../lib/HTML.php");
if (file_exists("../classes/Description_Query.php"))
{
  include_once("../classes/Description_Query.php");
}

/**
 * Form set of HTML form tags functions
 *
 * Methods:
 *  string strInput(array $options)
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
 *
 * @package OpenClinic
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 */
class Form
{
  /**
   * string strInput(array $options)
   *
   * Returns input html tag.
   *
   * @param array $options
   *  example:
   *    $options = array(
   *      'id' => 'address',
   *      'name' => 'address',
   *      'type' => 'text',
   *      'readonly' => true,
   *      'disabled' => true,
   *      'class' => 'required',
   *      'onclick' => '...'
   *    );
   * @return string input html tag
   * @access public
   * @since 0.8
   */
  function strInput($options)
  {
    $html = '<input';
    foreach ($options as $key => $value)
    {
      if ($key == 'error')
      {
        continue;
      }

      $html .= ' ' . $key . '="' . (($value === true) ? $key : $value) . '"';
    }
    $html .= " />\n";

    return $html;
  }

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

    $html = Form::strInput($addendum);

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
   * @since 0.7
   */
  function strSelect($name, &$array, $defaultValue = null, $addendum = null)
  {
    $html = '<select';
    $html .= ' id="' . (isset($addendum['id']) ? $addendum['id'] : $name) . '"';
    $html .= ' name="' . $name;
    if (isset($addendum['size']) && $addendum['size'] > 0)
    {
      $html .= '[]" multiple="multiple" size="' . intval($addendum['size']);
    }
    else
    {
      $addendum['size'] = 0;
    }
    $html .= '"';
    if (is_array($addendum))
    {
      foreach ($addendum as $key => $value)
      {
        if ($key == 'size' || $key == 'id' || $key == 'error')
        {
          continue;
        }

        $html .= ' ' . $key . '="' . $value . '"';
      }
    }
    $html .= ">\n";
    foreach ($array as $key => $value)
    {
      if (is_array($value))
      {
        $html .= '<optgroup label="' . $key . '">';
        foreach ($value as $optKey => $optValue)
        {
          $html .= '<option value="' . $optKey . '"';
          if ($addendum['size'] > 0 && is_array($defaultValue) && in_array($optKey, $defaultValue))
          {
            $html .= ' selected="selected"';
          }
          elseif ($defaultValue == $optKey)
          {
            $html .= ' selected="selected"';
          }
          $html .= ">" . (($value !== "") ? /*htmlspecialchars(*/$optValue/*)*/ : "&nbsp;") . "</option>\n"; // @fixme use htmlspecialchars
        }
        $html .= "</optgroup>\n";
      }
      else
      {
        $html .= '<option value="' . $key . '"';
        if ($addendum['size'] > 0 && is_array($defaultValue) && in_array($key, $defaultValue))
        {
          $html .= ' selected="selected"';
        }
        elseif ($defaultValue == $key)
        {
          $html .= ' selected="selected"';
        }
        $html .= ">" . (($value !== "") ? /*htmlspecialchars(*/$value/*)*/ : "&nbsp;") . "</option>\n"; // @fixme use htmlspecialchars
      }
    }
    $html .= "</select>\n";

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
   * @since 0.7
   */
  function strTextArea($name, $rows, $cols, $value = "", $addendum = null)
  {
    $html = '<textarea';
    $html .= ' id="' . (isset($addendum['id']) ? $addendum['id'] : $name) . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' rows="' . $rows . '"';
    $html .= ' cols="' . $cols . '"';
    if (is_array($addendum))
    {
      foreach ($addendum as $key => $val)
      {
        if ($key == 'id' || $key == 'error')
        {
          continue;
        }

        $html .= ' ' . $key . '="' . $val . '"';
      }
    }
    $html .= '>' . htmlspecialchars($value) . "</textarea>\n";

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
   * @since 0.7
   */
  function strHidden($name, $value = "", $addendum = null)
  {
    $addendum['type'] = 'hidden';
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);

    return Form::strInput($addendum);
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

    return Form::strInput($addendum);
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

    return Form::strInput($addendum);
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
   * @since 0.7
   */
  function strButton($name, $value, $type = "submit", $addendum = "")
  {
    $addendum['type'] = $type;
    $addendum['id'] = (isset($addendum['id']) ? $addendum['id'] : $name);
    $addendum['name'] = $name;
    $addendum['value'] = htmlspecialchars($value);

    return Form::strInput($addendum);
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

    $html = Form::strInput($addendum);

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

    $html = '<select id="' . $fieldCode . '"';
    $html .= ' name="' . $fieldCode;
    $html .= (($size > 0) ? '[]" multiple="multiple" size="' . intval($size) . '">' : '">');
    $html .= "\n";
    while ($aux = $desQ->fetch())
    {
      $html .= '<option value="' . $aux->getCode() . '"';
      if ($aux->getCode() == $defaultValue)
      {
        $html .= ' selected="selected"';
      }
      $html .= ">";
      if ($fieldDescription != "")
      {
        $html .= htmlspecialchars($aux->getDescription());
      }
      $html .= "</option>\n";
    }
    $html .= "</select>\n";

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
   * @since 0.8
   */
  function strLabel($field, $text, $required = false)
  {
    $html = "";
    $html .= '<label';
    $html .= ' for="' . $field . '"';
    if ($required)
    {
      $html .= ' class="' . "requiredField" . '"';
    }
    $html .= '>';
    if ($required)
    {
      $html .= '* ';
    }
    $html .= $text . '</label>' . "\n";

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
   */
  function strFieldset($legend, &$body, $foot = null, $options = null)
  {
    $html = "";
    if (count($body) == 0)
    {
      return $html; // no data, no fieldset
    }

    $html .= '<fieldset';
    if (isset($options['class']))
    {
      $html .= ' class="' . $options['class'] . '"';
    }
    $html .= ">\n";

    if ( !empty($legend) )
    {
      $html .= '<legend>' . trim($legend) . "</legend>\n";
    }

    if (count($body) > 0)
    {
      $numRow = 0;
      foreach ($body as $row)
      {
        $html .= '<p';
        if (isset($options['r' . $numRow]['class']))
        {
          $html .= ' class="' . $options['r' . $numRow]['class'] . '"';
        }
        $html .= ">\n";

        $html .= $row;

        $html .= "</p>\n";
        $numRow++;
      }
    }

    if (count($foot) > 0)
    {
      $html .= '<p class="formButton">' . "\n";
      foreach ($foot as $row)
      {
        $html .= $row;
      }
      $html .= "</p>\n";
    }

    $html .= "</fieldset>\n";

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
   */
  function fieldset($legend, &$body, $foot = null, $options = null)
  {
    echo Form::strFieldset($legend, $body, isset($foot) ? $foot : null, isset($options) ? $options : null);
  }
} // end class
?>
