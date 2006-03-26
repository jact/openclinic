/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: checkboxes.js,v 1.3 2006/03/26 15:33:35 jact Exp $
 */

/**
 * checkboxes.js
 *
 * JavaScript functions for the check/uncheck checkboxes
 *
 * @author jact <jachavar@gmail.com>
 */

/**
 * bool setCheckboxes(int indexForm, string elementName, bool doCheck)
 *
 * Checks/unchecks all checkboxes of a form
 *
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
