<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: input_lib.php,v 1.9 2004/10/18 17:24:04 jact Exp $
 */

/**
 * input_lib.php
 ********************************************************************
 * Set of HTML input tags functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  string htmlInputText(string $name, int $size, int $max, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 *  void showInputText(string $name, int $size, int $max, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 *  string htmlSelect(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  void showSelect(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  string htmlSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "", string $error = "")
 *  void showSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "", string $error = "")
 *  string htmlTextArea(string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 *  void showTextArea(string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 *  string htmlInputHidden(string $name, string $value = "")
 *  void showInputHidden(string $name, string $value = "")
 *  string htmlCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  void showCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  string htmlInputButton(string $name, string $value, string $type = "submit", string $addendum = "")
 *  void showInputButton(string $name, string $value, string $type = "submit", string $addendum = "")
 *  string htmlInputFile(string $name, string $value = "", int $size = 0, string $addendum = "")
 *  void showInputFile(string $name, string $value = "", int $size = 0, string $addendum = "")
 *  string htmlRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  void showRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 */

require_once("../lib/html_lib.php");
require_once("../lib/error_lib.php");
if (file_exists("../classes/Description_Query.php"))
{
  include_once("../classes/Description_Query.php");
}

/**
 * string htmlInputText(string $name, int $size, int $max, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 ********************************************************************
 * Returns input html tag of type text or password.
 ********************************************************************
 * @param string $name name of input field
 * @param int $size size of text box
 * @param int $max max input length of text box
 * @param string $value (optional) input value
 * @param string $error (optional) input error
 * @param string $type (optional) type of the input field (text by default)
 * @param bool $readOnly (optional) if the field is read only (false by default)
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return string input html tag
 * @access public
 * @since 0.7
 */
function htmlInputText($name, $size, $max, $value = "", $error = "", $type = "text", $readOnly = false, $addendum = "")
{
  $html = '<input';
  $html .= ' type="' . $type . '"';
  $html .= ' id="' . $name . '"';
  $html .= ' name="' . $name . '"';
  $html .= ' size="' . intval($size) . '"';
  $html .= ' maxlength="' . intval($max) . '"';
  if ($readOnly)
  {
    $html .= ' readonly="readonly"';
  }
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= ' value="' . htmlspecialchars($value) . '" />' . "\n";

  if ($error != "")
  {
    $html .= htmlMessage($error, OPEN_MSG_ERROR);
  }

  return $html;
}

/**
 * void showInputText(string $name, int $size, int $max, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 ********************************************************************
 * Draws input html tag of type text or password.
 ********************************************************************
 * @param string $name name of input field
 * @param int $size size of text box
 * @param int $max max input length of text box
 * @param string $value (optional) input value
 * @param string $error (optional) input error
 * @param string $type (optional) type of the input field (text by default)
 * @param bool $readOnly (optional) if the field is read only (false by default)
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return void
 * @access public
 */
function showInputText($name, $size, $max, $value = "", $error = "", $type = "text", $readOnly = false, $addendum = "")
{
  echo htmlInputText($name, $size, $max, $value, $error, $type, $readOnly, $addendum);
}

/**
 * string htmlSelect(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 ********************************************************************
 * Returns select html tag.
 ********************************************************************
 * @param string $tableName
 * @param string $fieldCode
 * @param string $defaultValue (optional) selected value
 * @param string $fieldDescription (optional)
 * @param int $size (optional) size of the select html tag
 * @return string select html tag
 * @access public
 * @since 0.7
 */
function htmlSelect($tableName, $fieldCode, $defaultValue = "", $fieldDescription = "", $size = 0)
{
  $desQ = new Description_Query();
  $desQ->connect();
  if ($desQ->isError())
  {
    showQueryError($desQ);
  }

  $numRows = $desQ->select($tableName, $fieldCode, $fieldDescription);
  if ($desQ->isError())
  {
    $desQ->close();
    showQueryError($desQ);
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
 * void showSelect(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 ********************************************************************
 * Draws select html tag.
 ********************************************************************
 * @param string $tableName
 * @param string $fieldCode
 * @param string $defaultValue (optional) selected value
 * @param string $fieldDescription (optional)
 * @param int $size (optional) size of the select html tag
 * @return void
 * @access public
 */
function showSelect($tableName, $fieldCode, $defaultValue = "", $fieldDescription = "", $size = 0)
{
  echo htmlSelect($tableName, $fieldCode, $defaultValue, $fieldDescription, $size);
}

/**
 * string htmlSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "", string $error = "")
 ********************************************************************
 * Returns select html tag based in an array.
 ********************************************************************
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
function htmlSelectArray($name, &$array, $defaultValue = "", $size = 0, $addendum = "", $error = "")
{
  $html = '<select id="' . $name . '"';
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= ' name="' . $name;
  $html .= (($size > 0) ? '[]" multiple="multiple" size="' . intval($size) . '">' : '">');
  $html .= "\n";
  foreach ($array as $key => $value)
  {
    $html .= '<option value="' . $key . '"';
    if ($defaultValue == $key)
    {
      $html .= ' selected="selected"';
    }
    $html .= ">" . /*htmlspecialchars(*/$value/*)*/ . "</option>\n"; // FIXME
  }
  $html .= "</select>\n";

  if ($error != "")
  {
    $html .= htmlMessage($error, OPEN_MSG_ERROR);
  }

  return $html;
}

/**
 * void showSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "", string $error = "")
 ********************************************************************
 * Draws select html tag based in an array.
 ********************************************************************
 * @param string $name name of the select tag
 * @param array $array data of the select tag
 * @param string $defaultValue (optional)
 * @param int $size (optional) size of the select html tag
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @param string $error (optional) select error message
 * @return void
 * @access public
 */
function showSelectArray($name, &$array, $defaultValue = "", $size = 0, $addendum = "", $error = "")
{
  echo htmlSelectArray($name, $array, $defaultValue, $size, $addemdum, $error);
}

/**
 * string htmlTextArea(string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Returns textarea html tag.
 ********************************************************************
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
function htmlTextArea($name, $rows, $cols, $value = "", $error = "", $disabled = false, $addendum= "")
{
  $html = '<textarea id="' . $name . '"';
  $html .= ' name="' . $name . '"';
  $html .= ' rows="' . $rows . '"';
  $html .= ' cols="' . $cols . '"';
  if ($disabled)
  {
    $html .= ' disabled="disabled"';
  }
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= ">" . htmlspecialchars($value) . "</textarea>\n";

  if ($error != "")
  {
    $html .= htmlMessage($error, OPEN_MSG_ERROR);
  }

  return $html;
}

/**
 * void showTextArea(string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Draws textarea html tag.
 ********************************************************************
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
function showTextArea($name, $rows, $cols, $value = "", $error = "", $disabled = false, $addendum = "")
{
  echo htmlTextArea($name, $rows, $cols, $value, $error, $disabled, $addendum);
}

/**
 * string htmlInputHidden(string $name, string $value = "")
 ********************************************************************
 * Returns input html tag of type hidden.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value (optional) input value
 * @return string input html tag
 * @access public
 * @since 0.7
 */
function htmlInputHidden($name, $value = "")
{
  $html = '<input type="hidden"';
  $html .= ' id="' . $name . '"';
  $html .= ' name="' . $name . '"';
  $html .= ' value="' . htmlspecialchars($value) . '" />' . "\n";

  return $html;
}

/**
 * void showInputHidden(string $name, string $value = "")
 ********************************************************************
 * Draws input html tag of type hidden.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value (optional) input value
 * @return void
 * @access public
 * @since 0.2
 */
function showInputHidden($name, $value = "")
{
  echo htmlInputHidden($name, $value);
}

/**
 * string htmlCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Returns input html tag of type checkbox.
 ********************************************************************
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
function htmlCheckBox($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
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
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= " />\n";

  return $html;
}

/**
 * void showCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Draws input html tag of type checkbox.
 ********************************************************************
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
function showCheckBox($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
{
  echo htmlCheckBox($id, $name, $value, $checked, $readOnly, $disabled, $addendum);
}

/**
 * string htmlInputButton(string $name, string $value, string $type = "submit", string $addendum = "")
 ********************************************************************
 * Returns input html tag of type button.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value input value
 * @param string $type (optional) type of button (submit by default)
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return string input html tag
 * @access public
 * @since 0.7
 */
function htmlInputButton($name, $value, $type = "submit", $addendum = "")
{
  $html = '<input type="' . $type . '"';
  $html .= ' id="' . $name . '"';
  $html .= ' name="' . $name . '"';
  $html .= ' value="' . $value . '"';
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= " />\n";

  return $html;
}

/**
 * void showInputButton(string $name, string $value, string $type = "submit", string $addendum = "")
 ********************************************************************
 * Draws input html tag of type button.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value input value
 * @param string $type (optional) type of button (submit by default)
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return void
 * @access public
 * @since 0.6
 */
function showInputButton($name, $value, $type = "submit", $addendum = "")
{
  echo htmlInputButton($name, $value, $type, $addendum);
}

/**
 * string htmlInputFile(string $name, string $value = "", int $size = 0, string $addendum = "")
 ********************************************************************
 * Returns input html tag of type file.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value (optional) input value
 * @param int $size (optional) size of the input html tag
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return string input html tag
 * @access public
 * @since 0.7
 */
function htmlInputFile($name, $value = "", $size = 0, $addendum = "")
{
  $html = '<input type="file"';
  $html .= ' id="' . $name . '"';
  $html .= ' name="' . $name . '"';
  if ($size > 0)
  {
    $html .= ' size="' . intval($size) . '"';
  }
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= ' value="' . htmlspecialchars($value) . '" />' . "\n";

  return $html;
}

/**
 * void showInputFile(string $name, string $value = "", int $size = 0, string $addendum = "")
 ********************************************************************
 * Draws input html tag of type file.
 ********************************************************************
 * @param string $name name of input field
 * @param string $value (optional) input value
 * @param int $size (optional) size of the input html tag
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return void
 * @access public
 * @since 0.6
 */
function showInputFile($name, $value = "", $size = 0, $addendum = "")
{
  echo htmlInputFile($name, $value, $size, $addendum);
}

/**
 * string htmlRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Returns input html tag of type radio button.
 ********************************************************************
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
function htmlRadioButton($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
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
  if ($addendum)
  {
    $html .= ' ' . $addendum;
  }
  $html .= " />\n";

  return $html;
}

/**
 * void showRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 ********************************************************************
 * Draws input html tag of type radio button.
 ********************************************************************
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
function showRadioButton($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
{
  echo htmlRadioButton($id, $name, $value, $checked, $readOnly, $disabled, $addendum);
}
?>
