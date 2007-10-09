/**
 * dump_functions.js
 *
 * JavaScript functions for the dump process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: dump_functions.js,v 1.5 2007/10/09 19:31:33 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

if (typeof addLoadEvent == 'function')
{
  addLoadEvent(initDumpForm); // event.js included!
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
  var element = document.getElementById('select_all');
  if (element != null)
  {
    element.onclick = function()
    {
      setSelectOptions(0, 'table_select[]', true);

      return false;
    };
  }

  element = document.getElementById('unselect_all');
  if (element != null)
  {
    element.onclick = function()
    {
      setSelectOptions(0, 'table_select[]', false);

      return false;
    };
  }

  element = document.getElementById('radio_dump_data');
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(0, 0, 0, 0, 1, 0, 0, 0));
    };
  }

  element = document.getElementById('radio_dump_structure');
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(0, 1, 1, 0, 1, 0, 0, 0));
    };
  }

  element = document.getElementById('radio_dump_dataonly');
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(1, 0, 0, 0, 0, 0, 1, 0));
    };
  }

  element = document.getElementById('radio_dump_xml');
  if (element != null)
  {
    element.onclick = function()
    {
      updateChecks(0, new Array(1, 1, 1, 1, 1, 1, 1, 0));
    };
  }

  element = document.getElementById('radio_dump_csv');
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

  if (typeof(min) == 'undefined')
  {
    min = 0;
  }
  if (typeof(max) == 'undefined')
  {
    max = Number.MAX_VALUE;
  }

  // It's not a number
  if (isNaN(val))
  {
    theField.select();
    alert(errorMsg1);
    theField.focus();
    return false;
  }
  // It's a number but it is not between min and max
  else if (val < min || val > max)
  {
    theField.select();
    alert(val + errorMsg2);
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

/**
 * bool setSelectOptions(int indexForm, string elementSelect, bool doCheck)
 *
 * Checks/unchecks all options of a <select> element
 *
 * @param  int     the form index
 * @param  string  the element name
 * @param  boolean whether to check or to uncheck the element
 * @return boolean always true
 */
function setSelectOptions(indexForm, elementSelect, doCheck)
{
  var selectObject = document.forms[indexForm].elements[elementSelect];
  var selectCount  = selectObject.length;

  for (var i = 0; i < selectCount; i++)
  {
    selectObject.options[i].selected = doCheck;
  } // end for

  return true;
} // end of the 'setSelectOptions()' function

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
  } // end for

  return true;
} // end of the 'updateChecks()' function
