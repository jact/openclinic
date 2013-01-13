<?php
/**
 * focus.php
 *
 * Contains the function focus
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: focus.php,v 1.3 2013/01/13 14:20:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 */

require_once("../lib/Check.php");
require_once("../config/i18n.php");

header("Content-Type: text/javascript; charset=" . OPEN_CHARSET);

$field = Check::safeText($_GET['field']);
?>
if (typeof addEvent == 'function')
{
  addEvent(window, 'load', focus, false); // event.js included!
}

/**
 * void focus(void)
 */
function focus()
{
  var field = document.getElementById('<?php echo $field; ?>');
  if (field != null)
  {
    field.focus();
  }
} // end of the 'focus()' function
