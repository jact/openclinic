<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: input_lib.php,v 1.1 2004/03/24 20:00:58 jact Exp $
 */

/**
 * input_lib.php
 ********************************************************************
 * Set of HTML input tags functions
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 24/03/04 21:00
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

/**
 * Functions:
 *  void showInputText(string $name, int $size, int $max, string $value = "", string $error = "", string $type = "text", bool $readOnly = false, string $addendum = "")
 *  void showSelect(string $tableName, string $fieldCode, string $defaultValue = "", string $fieldDescription = "", int $size = 0)
 *  void showSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "")
 *  void showTextArea(string $name, int $rows, int $cols, string $value = "", string $error = "", bool $disabled = false, string $addendum = "")
 *  void showInputHidden(string $name, string $value = "")
 *  void showCheckBox(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 *  void showInputButton(string $name, string $value, string $type = "submit", string $addendum = "")
 *  void showInputFile(string $name, string $value = "", int $size = 0, string $addendum = "")
 *  void showRadioButton(string $id, string $name, mixed $value, bool $checked = false, bool $readOnly = false, bool $disabled = false, string $addendum = "")
 */

require_once("../lib/error_lib.php");
if (file_exists("../classes/Description_Query.php"))
{
  include_once("../classes/Description_Query.php");
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
  echo '<input';
  echo ' type="' . $type . '"';
  echo ' id="' . $name . '"';
  echo ' name="' . $name . '"';
  echo ' size="' . intval($size) . '"';
  echo ' maxlength="' . intval($max) . '"';
  if ($readOnly)
  {
    echo ' readonly="readonly"';
  }
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo ' value="' . htmlspecialchars($value) . '" />' . "\n";

  if ($error != "")
  {
    echo '<div class="error">' . $error . "</div>\n";
  }
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
  $desQ = new Description_Query();
  $desQ->connect();
  if ($desQ->errorOccurred())
  {
    showQueryError($desQ);
  }

  $desQ->select($tableName, $fieldCode, $fieldDescription);
  if ($desQ->errorOccurred())
  {
    $desQ->close();
    showQueryError($desQ);
  }

  echo '<select id="' . $fieldCode . '"';
  echo ' name="' . $fieldCode;
  echo (($size > 0) ? '[]" multiple="multiple" size="' . intval($size) . '">' : '">');
  echo "\n";
  while ($aux = $desQ->fetchDescription())
  {
    echo '<option value="' . $aux->getCode() . '"';
    if ($aux->getCode() == $defaultValue)
    {
      echo ' selected="selected"';
    }
    echo ">";
    if ($fieldDescription != "")
    {
      echo htmlspecialchars($aux->getDescription());
    }
    echo "</option>\n";
  }
  echo "</select>\n";
  $desQ->close();
}

/**
 * void showSelectArray(string $name, array &$array, string $defaultValue = "", int $size = 0, string $addendum = "")
 ********************************************************************
 * Draws select html tag based in an array.
 ********************************************************************
 * @param string $name name of the select tag
 * @param array $array data of the select tag
 * @param string $defaultValue (optional)
 * @param int $size (optional) size of the select html tag
 * @param string $addendum (optional) JavaScript event handlers, class attribute, etc
 * @return void
 * @access public
 */
function showSelectArray($name, &$array, $defaultValue = "", $size = 0, $addendum = "")
{
  echo '<select id="' . $name . '"';
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo ' name="' . $name;
  echo (($size > 0) ? '[]" multiple="multiple" size="' . intval($size) . '">' : '">');
  echo "\n";
  foreach ($array as $key => $value)
  {
    echo '<option value="' . $key . '"';
    if ($defaultValue == $key)
    {
      echo ' selected="selected"';
    }
    echo ">" . htmlspecialchars($value) . "</option>\n";
  }
  echo "</select>\n";
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
function showTextArea($name, $rows, $cols, $value = "", $error = "", $disabled = false, $addendum= "")
{
  echo '<textarea id="' . $name . '"';
  echo ' name="' . $name . '"';
  echo ' rows="' . $rows . '"';
  echo ' cols="' . $cols . '"';
  if ($disabled)
  {
    echo ' disabled="disabled"';
  }
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo ">" . htmlspecialchars($value) . "</textarea>\n";

  if ($error != "")
  {
    echo '<div class="error">' . $error . "</div>\n";
  }
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
 */
function showInputHidden($name, $value = "")
{
  echo '<input type="hidden"';
  echo ' id="' . $name . '"';
  echo ' name="' . $name . '"';
  echo ' value="' . htmlspecialchars($value) . '" />' . "\n";
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
 */
function showCheckBox($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
{
  echo '<input type="checkbox"';
  echo ' id="' . $id . '"';
  echo ' name="' . $name . '"';
  echo ' value="' . htmlspecialchars($value) . '"';
  if ($checked)
  {
    echo ' checked="checked"';
  }
  if ($readOnly)
  {
    echo ' readonly="readonly"';
  }
  if ($disabled)
  {
    echo ' disabled="disabled"';
  }
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo " />\n";
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
 */
function showInputButton($name, $value, $type = "submit", $addendum = "")
{
  echo '<input type="' . $type . '"';
  echo ' id="' . $name . '"';
  echo ' name="' . $name . '"';
  echo ' value="' . $value . '"';
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo " />\n";
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
 */
function showInputFile($name, $value = "", $size = 0, $addendum = "")
{
  echo '<input type="file"';
  echo ' id="' . $name . '"';
  echo ' name="' . $name . '"';
  if ($size > 0)
  {
    echo ' size="' . intval($size) . '"';
  }
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo ' value="' . htmlspecialchars($value) . '" />' . "\n";
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
 */
function showRadioButton($id, $name, $value, $checked = false, $readOnly = false, $disabled = false, $addendum = "")
{
  echo '<input type="radio"';
  echo ' id="' . $id . '"';
  echo ' name="' . $name . '"';
  echo ' value="' . htmlspecialchars($value) . '"';
  if ($checked)
  {
    echo ' checked="checked"';
  }
  if ($readOnly)
  {
    echo ' readonly="readonly"';
  }
  if ($disabled)
  {
    echo ' disabled="disabled"';
  }
  if ($addendum)
  {
    echo ' ' . $addendum;
  }
  echo " />\n";
}
?>
