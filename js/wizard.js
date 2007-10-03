/**
 * wizard.js
 *
 * Checks install settings
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: wizard.js,v 1.6 2007/10/03 19:35:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.5
 */

/**
 * bool validateInstall(void)
 *
 * Validates settings of install wizard
 *
 * @return boolean true if everything is ok, false otherwise
 */
function validateInstall()
{
  var f = document.forms[0];
  var msg = "";

  switch (f.buttonPressed.value)
  {
    // MySQL settings
    case "back2":
    case "next3":
      if (f.dbHost[1].value.replace(/\s+/, "") == "")
      {
        msg += "Database Host is empty.\n";
      }
      if (f.dbUser[1].value.replace(/\s+/, "") == "")
      {
        msg += "Database User is empty.\n";
      }
      if (f.dbName[1].value.replace(/\s+/, "") == "")
      {
        msg += "Database Name is empty.\n";
      }
      break;

    // Config settings
    case "back3":
    case "next4":
      if (f.timeout[1].value <= 0)
      {
        msg += "Session Timeout must be great than zero.\n";
      }
      if (f.itemsPage[1].value <= 0)
      {
        msg += "Items per page must be great than zero.\n";
      }
      break;

    // Admin data
    case "back4":
    case "next5":
      if (f.passwd[1].value.replace(/\s+/, "").length < 4)
      {
        msg += "Admin password must be at least 4 characters.\n";
      }
      break;
  }

  if (msg.length > 0)
  {
    alert(msg);
    return false;
  }
  else
  {
    return true;
  }
} // end of the 'validateInstall()' function
