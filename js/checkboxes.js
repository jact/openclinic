/**
 * checkboxes.js
 *
 * JavaScript functions for the check/uncheck checkboxes
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: checkboxes.js,v 1.4 2006/03/27 18:32:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
