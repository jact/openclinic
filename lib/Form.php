<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: Form.php,v 1.1 2005/07/28 17:46:02 jact Exp $
 */

/**
 * Form.php
 *
 * Contains the class Form
 *
 * Author: jact <jachavar@gmail.com>
 */

require_once("../lib/HTML.php");
if (file_exists("../classes/Description_Query.php"))
{
  include_once("../classes/Description_Query.php");
  include_once("../lib/Error.php");
}

/**
 * Form set of HTML form tags functions
 *
 * @author jact <jachavar@gmail.com>
 * @access public
 * @since 0.8
 *
 * Methods:
 *  string strText(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 *  void text(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 *  string strPassword(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", bool $readOnly = false, string $addendum = "")
 *  void password(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", bool $readOnly = false, string $addendum = "")
 *  string strSelect(string $id, string $name, array &$array, string $defaultValue = "", int $size = 0, bool $disabled = false, string $addendum = "", string $error = "")
 *  void select(string $id, string $name, array &$array, string $defaultValue = "", int $size = 0, bool $disabled = false, string $addendum = "", string $error = "")
 *  string strTextArea(string $id, string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 *  void textArea(string $id, string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 *  string strHidden(string $id, string $name, string $value = "", string $addendum = "")
 *  void hidden(string $id, string $name, string $value = "", string $addendum = "")
 *  string strCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  void checkBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  string strRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  void radioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  string strButton(string $id, string $name, string $value, string $type = "submit", string $addendum = "")
 *  void button(string $id, string $name, string $value, string $type = "submit", string $addendum = "")
 *  string strFile(string $id, string $name, string $value = "", int $size = 0, string $addendum = "", string $error = "")
 *  void file(string $id, string $name, string $value = "", int $size = 0, string $addendum = "", string $error = "")
 *  string strSelectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  void selectTable(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  string strLabel(string $field, string $text, bool $required = false)
 *  void label(string $field, string $text, bool $required = false)
 */
class Form
{
  /**
   * string strText(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
   *
   * Returns input html tag of type text or password.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param int $size size of text box
   * @param int $max (optional) max input length of text box
   * @param string $value (optional) input value
   * @param string $error (optional) input error
   * @param string $type (optional) type of the input field (text by default)
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string input html tag
   * @access public
   * @since 0.7
   */
  function strText($id, $name, $size, $max = 0, $value = "", $error = "", $type = "text", $readOnly = false, $addendum = "")
  {
    $html = '<input';
    $html .= ' type="' . $type . '"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' size="' . intval($size) . '"';
    if (intval($max) > 0)
    {
      $html .= ' maxlength="' . intval($max) . '"';
    }
    if ($readOnly)
    {
      $html .= ' readonly="readonly"';
    }
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= ' value="' . htmlspecialchars($value) . '" />' . "\n";

    if ( !empty($error) )
    {
      $html .= HTML::strMessage($error, OPEN_MSG_ERROR);
    }

    return $html;
  }

  /**
   * void text(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
   *
   * Draws input html tag of type text or password.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param int $size size of text box
   * @param int $max (optional) max input length of text box
   * @param string $value (optional) input value
   * @param string $error (optional) input error
   * @param string $type (optional) type of the input field (text by default)
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   */
  function text($id, $name, $size, $max = 0, $value = "", $error = "", $type = "text", $readOnly = false, $addendum = "")
  {
    echo Form::strText($id, $name, $size, $max, $value, $error, $type, $readOnly, $addendum);
  }

  /**
   * string strPassword(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", bool $readOnly = false, string $addendum = "")
   *
   * Returns input html tag of type password.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param int $size size of text box
   * @param int $max (optional) max input length of text box
   * @param string $value (optional) input value
   * @param string $error (optional) input error
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param string $addendum (optional) javascript event handlers, ...
   * @return string input html tag
   * @access public
   * @since 0.8
   */
  function strPassword($id, $name, $size, $max = 0, $value = "", $error = "", $readOnly = false, $addendum = "")
  {
    return Form::strText($id, $name, $size, $max, $value, $error, "password", $readOnly, $addendum);
  }

  /**
   * void password(string $id, string $name, int $size, int $max = 0, string $value = "", string $error = "", bool $readOnly = false, string $addendum = "")
   *
   * Draws input html tag of type password.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param int $size size of text box
   * @param int $max (optional) max input length of text box
   * @param string $value (optional) input value
   * @param string $error (optional) input error
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param string $addendum (optional) javascript event handlers, ...
   * @return void
   * @access public
   * @since 0.8
   */
  function password($id, $name, $size, $max = 0, $value = "", $error = "", $readOnly = false, $addendum = "")
  {
    Form::text($id, $name, $size, $max, $value, $error, "password", $readOnly, $addendum);
  }

  /**
   * string strSelect(string $id, string $name, array &$array, string $defaultValue = "", int $size = 0, bool $disabled = false, string $addendum = "", string $error = "")
   *
   * Returns select html tag based in an array.
   *
   * @param string $id identifier of input field
   * @param string $name name of the select tag
   * @param array $array data of the select tag
   * @param string $defaultValue (optional)
   * @param int $size (optional) size of the select html tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @param string $error (optional) select error message
   * @return string select html tag
   * @access public
   * @since 0.7
   */
  function strSelect($id, $name, &$array, $defaultValue = "", $size = 0, $addendum = "", $error = "")
  {
    $html = '<select id="' . $id . '"';
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= ' name="' . $name;
    $html .= (($size > 0) ? '[]" multiple="multiple" size="' . intval($size) . '">' : '">');
    $html .= "\n";
    foreach ($array as $key => $value)
    {
      if (is_array($value))
      {
        $html .= '<optgroup>' . $key . '</optgroup>';
        foreach ($value as $optKey => $optValue)
        {
          $html .= '<option value="' . $optKey . '"';
          if ($defaultValue == $optKey)
          {
            $html .= ' selected="selected"';
          }
          $html .= ">" . /*htmlspecialchars(*/$optValue/*)*/ . "</option>\n"; // @fixme use htmlspecialchars
        }
      }
      else
      {
        $html .= '<option value="' . $key . '"';
        if ($defaultValue == $key)
        {
          $html .= ' selected="selected"';
        }
        $html .= ">" . /*htmlspecialchars(*/$value/*)*/ . "</option>\n"; // @fixme use htmlspecialchars
      }
    }
    $html .= "</select>\n";

    if ( !empty($error) )
    {
      $html .= HTML::strMessage($error, OPEN_MSG_ERROR);
    }

    return $html;
  }

  /**
   * void select(string $id, string $name, array &$array, string $defaultValue = "", int $size = 0, bool $disabled = false, string $addendum = "", string $error = "")
   *
   * Draws select html tag based in an array.
   *
   * @param string $id identifier of input field
   * @param string $name name of the select tag
   * @param array $array data of the select tag
   * @param string $defaultValue (optional)
   * @param int $size (optional) size of the select html tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @param string $error (optional) select error message
   * @return void
   * @access public
   */
  function select($id, $name, &$array, $defaultValue = "", $size = 0, $addendum = "", $error = "")
  {
    echo Form::strSelect($id, $name, $array, isset($defaultValue) ? $defaultValue : "", isset($size) ? $size : 0, isset($addemdum) ? $addemdum : "", isset($error) ? $error : "");
  }

  /**
   * string strTextArea(string $id, string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
   *
   * Returns textarea html tag.
   *
   * @param string $id identifier of input field
   * @param string $name name of the textarea tag
   * @param int $rows number of rows of the textarea tag
   * @param int $cols number of cols of the textarea tag
   * @param string $value (optional) value of the textarea tag
   * @param string $error (optional) textarea error message
   * @param bool $disabled (optional) state of the textarea tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string textarea html tag
   * @access public
   * @since 0.7
   */
  function strTextArea($id, $name, $rows, $cols, $value = "", $error = "", $disabled = false, $addendum= "")
  {
    $html = '<textarea id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' rows="' . $rows . '"';
    $html .= ' cols="' . $cols . '"';
    if ($disabled)
    {
      $html .= ' disabled="disabled"';
    }
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= ">" . htmlspecialchars($value) . "</textarea>\n";

    if ( !empty($error) )
    {
      $html .= HTML::strMessage($error, OPEN_MSG_ERROR);
    }

    return $html;
  }

  /**
   * void textArea(string $id, string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
   *
   * Draws textarea html tag.
   *
   * @param string $id identifier of input field
   * @param string $name name of the textarea tag
   * @param int $rows number of rows of the textarea tag
   * @param int $cols number of cols of the textarea tag
   * @param string $value (optional) value of the textarea tag
   * @param string $error (optional) textarea error message
   * @param bool $disabled (optional) state of the textarea tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   */
  function textArea($id, $name, $rows, $cols, $value = "", $error = "", $disabled = false, $addendum = "")
  {
    echo Form::strTextArea($id, $name, $rows, $cols, $value, $error, $disabled, $addendum);
  }

  /**
   * string strHidden(string $id, string $name, string $value = "", string $addendum = "")
   *
   * Returns input html tag of type hidden.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string input html tag
   * @access public
   * @since 0.7
   */
  function strHidden($id, $name, $value = "", $addendum = "")
  {
    $html = '<input type="hidden"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' value="' . htmlspecialchars($value) . '"';
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= ' />' . "\n";

    return $html;
  }

  /**
   * void hidden(string $id, string $name, string $value = "", string $addendum = "")
   *
   * Draws input html tag of type hidden.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @since 0.2
   */
  function hidden($id, $name, $value = "", $addendum = "")
  {
    echo Form::strHidden($id, $name, $value, $addendum);
  }

  /**
   * string strCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
   *
   * Returns input html tag of type checkbox.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked (optional) if the field is checked or not (false by default)
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param bool $disabled (optional) if the field is disabled (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string input html tag
   * @access public
   * @since 0.7
   */
  function strCheckBox($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
  {
    $html = '<input type="checkbox"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' value="' . htmlspecialchars($value) . '"';
    if ($checked)
    {
      $html .= ' checked="checked"';
    }
    if ($readOnly)
    {
      $html .= ' readonly="readonly"';
    }
    if ($disabled)
    {
      $html .= ' disabled="disabled"';
    }
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= " />\n";

    return $html;
  }

  /**
   * void checkBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
   *
   * Draws input html tag of type checkbox.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked (optional) if the field is checked or not (false by default)
   * @param bool $readOnly (optional) if the field is read only (false by default)
   * @param bool $disabled (optional) if the field is disabled (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @since 0.4
   */
  function checkBox($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
  {
    echo Form::strCheckBox($id, $name, $value, $checked, $readOnly, $disabled, $addendum);
  }

  /**
   * string strRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
   *
   * Returns input html tag of type radio button.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked if the field is checked or not (false by default)
   * @param bool $readOnly if the field is read only (false by default)
   * @param bool $disabled if the field is disabled (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string input html tag
   * @access public
   * @since 0.7
   */
  function strRadioButton($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
  {
    $html = '<input type="radio"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' value="' . htmlspecialchars($value) . '"';
    if ($checked)
    {
      $html .= ' checked="checked"';
    }
    if ($readOnly)
    {
      $html .= ' readonly="readonly"';
    }
    if ($disabled)
    {
      $html .= ' disabled="disabled"';
    }
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= " />\n";

    return $html;
  }

  /**
   * void radioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
   *
   * Draws input html tag of type radio button.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param mixed $value input value
   * @param bool $checked if the field is checked or not (false by default)
   * @param bool $readOnly if the field is read only (false by default)
   * @param bool $disabled if the field is disabled (false by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @since 0.6
   */
  function radioButton($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
  {
    echo Form::strRadioButton($id, $name, $value, $checked, $readOnly, $disabled, $addendum);
  }

  /**
   * string strButton(string $id, string $name, string $value, string $type = "submit", string $addendum = "")
   *
   * Returns input html tag of type button.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value input value
   * @param string $type (optional) type of button (submit by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return string input html tag
   * @access public
   * @since 0.7
   */
  function strButton($id, $name, $value, $type = "submit", $addendum = "")
  {
    $html = '<input type="' . $type . '"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    $html .= ' value="' . $value . '"';
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= " />\n";

    return $html;
  }

  /**
   * void button(string $id, string $name, string $value, string $type = "submit", string $addendum = "")
   *
   * Draws input html tag of type button.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value input value
   * @param string $type (optional) type of button (submit by default)
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @return void
   * @access public
   * @since 0.6
   */
  function button($id, $name, $value, $type = "submit", $addendum = "")
  {
    echo Form::strButton($id, $name, $value, $type, $addendum);
  }

  /**
   * string strFile(string $id, string $name, string $value = "", int $size = 0, string $addendum = "", string $error = "")
   *
   * Returns input html tag of type file.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param int $size (optional) size of the input html tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @param string $error (optional) input error message
   * @return string input html tag
   * @access public
   * @since 0.7
   * @todo $error
   */
  function strFile($id, $name, $value = "", $size = 0, $addendum = "", $error = "")
  {
    $html = '<input type="file"';
    $html .= ' id="' . $id . '"';
    $html .= ' name="' . $name . '"';
    if ($size > 0)
    {
      $html .= ' size="' . intval($size) . '"';
    }
    if ( !empty($addendum) )
    {
      $html .= ' ' . $addendum;
    }
    $html .= ' value="' . htmlspecialchars($value) . '" />' . "\n";

    if ( !empty($error) )
    {
      $html .= HTML::strMessage($error, OPEN_MSG_ERROR);
    }

    return $html;
  }

  /**
   * void file(string $id, string $name, string $value = "", int $size = 0, string $addendum = "", string $error = "")
   *
   * Draws input html tag of type file.
   *
   * @param string $id identifier of input field
   * @param string $name name of input field
   * @param string $value (optional) input value
   * @param int $size (optional) size of the input html tag
   * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
   * @param string $error (optional) input error message
   * @return void
   * @access public
   * @since 0.6
   * @todo $error
   */
  function file($id, $name, $value = "", $size = 0, $addendum = "", $error = "")
  {
    echo Form::strFile($id, $name, $value, $size, $addendum, $error);
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
    if ($desQ->isError())
    {
      Error::query($desQ);
    }

    $numRows = $desQ->select($tableName, $fieldCode, $fieldDescription);
    if ($desQ->isError())
    {
      $desQ->close();
      Error::query($desQ);
    }

    if ( !$numRows )
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
    if ($required)
    {
      $html .= '* ';
    }
    $html .= '<label';
    $html .= ' for="' . $field . '"';
    if ($required)
    {
      $html .= ' class="' . "requiredField" . '"';
    }
    $html .= '>';
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
} // end class
?>
