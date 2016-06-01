/**
 * pop_window.js
 *
 * Contains functions to open new windows
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2016 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

/**
 * bool popSecondary(string url)
 *
 * Show a new pop window
 *
 * @param string the url
 * @return bool always false
 */
function popSecondary(url)
{
  var secondaryWin = window.open(
    url,
    "_blank",
    "width=680,height=450,resizable=yes,scrollbars=yes"
  );

  return false;
}

/**
 * void doPopups(void)
 *
 * Adds event handlers to a.popup elements
 *
 * @return void
 * @author Jeremy Keith <http://adactio.com>
 * @since 0.8
 */
function doPopups()
{
  if (document.getElementsByTagName)
  {
    var links = document.getElementsByTagName("a");
    for (var i = 0; i < links.length; i++)
    {
      if (links[i].className.match("popup"))
      {
        links[i].onclick = function()
        {
          return popSecondary(this.getAttribute("href"));
        };
      }
    }
  }
}

if (typeof addLoadEvent == "function")
{
  addLoadEvent(doPopups); // event.js included!
}
