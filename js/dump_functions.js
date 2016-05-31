/**
 * dump_functions.js
 *
 * JavaScript functions for the dump process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2016 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

/**
 * bool setSelectOptions(string elementSelect, bool doCheck)
 *
 * Checks/unchecks all options of a <select> element
 *
 * @param  string  the element name
 * @param  boolean whether to check or to uncheck the element
 * @return boolean always true
 */
function setSelectOptions(elementSelect, doCheck)
{
  var selectObject = document.getElementsByName(elementSelect);
  var selectCount  = selectObject.length;

  for (var i = 0; i < selectCount; i++)
  {
    selectObject.options[i].selected = doCheck;
  }

  return true;
}

/**
 * bool updateChecks(int indexForm, array array)
 *
 * Enable/disable all checkbox of a <form> element
 *
 * @param  int     the form index
 * @param  boolean array to enable or disable checkboxes
 * @return boolean always true
 */
function updateChecks(indexForm, array)
{
  var j = 0;
  var selectedObject = document.forms[indexForm];

  for (var i = 0; i < selectedObject.elements.length; i++)
  {
    if (selectedObject.elements[i].type == "checkbox")
    {
      selectedObject.elements[i].disabled = array[j];
      j++;
    }
  }

  return true;
}

/**
 * void initDumpForm(void)
 *
 * Adds event handlers to dump form elements
 *
 * @return void
 * @since 0.8
 */
function initDumpForm()
{
  var element = document.getElementById("select_all");
  if (element != null)
  {
    element.onclick = function()
    {
      setSelectOptions("table_select[]", true);

      return false;
    };
  }

  element = document.getElementById("unselect_all");
  if (element != null)
  {
    element.onclick = function()
    {
      setSelectOptions("table_select[]", false);

      return false;
    };
  }

  element = document.getElementById("radio_dump_data");
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(0, 0, 0, 0, 1, 0, 0, 0));
    };
  }

  element = document.getElementById("radio_dump_structure");
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(0, 1, 1, 0, 1, 0, 0, 0));
    };
  }

  element = document.getElementById("radio_dump_dataonly");
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(1, 0, 0, 0, 0, 0, 1, 0));
    };
  }

  element = document.getElementById("radio_dump_xml");
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));
    };
  }

  element = document.getElementById("radio_dump_csv");
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));
    };
  }
} // end of the 'initDumpForm()' function

/**
 * bool checkFormElementInRange(obj theForm, string theFieldName, int min, int max)
 *
 * Ensures a value submitted in a form is numeric and is in a range
 *
 * @param  object  the form
 * @param  string  the name of the form field to check
 * @param  integer the minimum authorized value
 * @param  integer the maximum authorized value
 * @return boolean whether a valid number has been submitted or not
 */
function checkFormElementInRange(theForm, theFieldName, min, max)
{
  var theField = theForm.elements[theFieldName];
  var val      = parseInt(theField.value);

  if (typeof(min) == "undefined")
  {
    min = 0;
  }
  if (typeof(max) == "undefined")
  {
    max = Number.MAX_VALUE;
  }

  // It's not a number
  if (isNaN(val))
  {
    theField.select();
    theField.focus();
    return false;
  }
  // It's a number but it is not between min and max
  else if (val < min || val > max)
  {
    theField.select();
    theField.focus();
    return false;
  }
  // It's a valid number
  else
  {
    theField.value = val;
  }

  return true;
} // end of the 'checkFormElementInRange()' function

if (typeof addLoadEvent == "function")
{
  addLoadEvent(initDumpForm); // event.js included!
}
