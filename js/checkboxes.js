/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: checkboxes.js,v 1.2 2004/04/18 14:31:10 jact Exp $
 */

/**
 * checkboxes.js
 ********************************************************************
 * JavaScript functions for the check/uncheck checkboxes
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

/**
 ********************************************************************
 * Checks/unchecks all checkboxes of a form
 ********************************************************************
 * @param int the form index
 * @param string the element name
 * @param bool whether to check or to uncheck the element
 * @return bool always true
 */
function setCheckboxes(indexForm, elementName, doCheck)
{
  var selectedObject = document.forms[indexForm].elements[elementName];
  var selectedCount  = selectedObject.length;

  for (var i = 0; i < selectedCount; i++)
  {
    selectedObject[i].checked = doCheck;
  } // end for

  return true;
} // end of the 'setCheckboxes()' function
