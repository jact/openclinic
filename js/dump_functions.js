/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: dump_functions.js,v 1.2 2004/04/18 14:31:10 jact Exp $
 */

/**
 * dump_functions.js
 ********************************************************************
 * JavaScript functions for the dump process
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

/**
 ********************************************************************
 * Ensures a value submitted in a form is numeric and is in a range
 ********************************************************************
 * @param   object   the form
 * @param   string   the name of the form field to check
 * @param   integer  the minimum authorized value
 * @param   integer  the maximum authorized value
 * @return  boolean  whether a valid number has been submitted or not
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
 ********************************************************************
 * Checks/unchecks all options of a <select> element
 ********************************************************************
 * @param   int      the form index
 * @param   string   the element name
 * @param   boolean  whether to check or to uncheck the element
 * @return  boolean  always true
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
 ********************************************************************
 * Enable/disable all checkbox of a <form> element
 ********************************************************************
 * @param   int      the form index
 * @param   boolean  array to enable or disable checkboxes
 * @return  boolean  always true
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
