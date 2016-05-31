/**
 * checkboxes.js
 *
 * JavaScript functions for the check/uncheck checkboxes
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2016 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

/**
 * bool setCheckboxes(string elementName, bool doCheck)
 *
 * Checks/unchecks all checkboxes of a form
 *
 * @param string the element name
 * @param bool whether to check or to uncheck the element
 * @return bool always true
 */
function setCheckboxes(elementName, doCheck)
{
  var selectedObject = document.getElementsByName(elementName);
  var selectedCount  = selectedObject.length;

  for (var i = 0; i < selectedCount; i++)
  {
    selectedObject[i].checked = doCheck;
  }

  return true;
}

/**
 * void initRelativeForm(void)
 *
 * Adds event handlers to relative form elements
 *
 * @return void
 * @since 0.8
 */
function initRelativeForm()
{
  var element = document.getElementById("select_all_checks");
  if (element != null)
  {
    element.onclick = function()
    {
      setCheckboxes("check[]", true);

      return false;
    };
  }

  element = document.getElementById("unselect_all_checks");
  if (element != null)
  {
    element.onclick = function()
    {
      setCheckboxes("check[]", false);

      return false;
    };
  }
}

if (typeof addLoadEvent == "function")
{
  addLoadEvent(initRelativeForm); // event.js included!
}
