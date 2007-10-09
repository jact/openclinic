<?php
/**
 * password.php
 *
 * Contains the function md5Login used in:
 *  - admin/user_new_form.php
 *  - admin/user_edit_form.php
 *  - admin/user_pwd_reset_form.php
 *  - auth/login_form.php
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: password.php,v 1.8 2007/10/09 18:33:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

require_once("../config/environment.php");
header("Content-Type: text/javascript; charset=" . OPEN_CHARSET);
?>
if (typeof addEvent == 'function')
{
  addEvent(window, 'load', initMd5Login, false); // event.js included!
}

/**
 * void initMd5Login(void)
 */
function initMd5Login()
{
  f = document.getElementById('loginForm');
  if (f == null)
  {
    f = document.getElementById('userNew');
  }
  if (f == null)
  {
    f = document.getElementById('userEdit');
  }
  if (f == null)
  {
    f = document.getElementById('userPwd');
  }
  if (f != null)
  {
    f.onsubmit = function () {return md5Login(this);};
  }
}

/**
 * bool md5Login(string f)
 *
 * Translates plain text passwords to md5 passwords
 *
 * @param string form name
 * @return boolean true if ok to submit form, false otherwise
 */
function md5Login(f)
{
  if (f['old_pwd'] == null || (f['old_pwd'] != null && f['old_pwd'].value.length > 0))
  {
    if (f['pwd'] != null && f['pwd'].value != null && f['pwd'].value.length < 4)
    {
      alert("<?php echo sprintf(_("Password must be at least %d characters."), 4); ?>");
      return false;
    }

    if (f['pwd2'] != null && f['pwd2'].value != null && f['pwd2'].value.length < 4)
    {
      alert("<?php echo sprintf(_("Confirmation password must be at least %d characters."), 4); ?>");
      return false;
    }
  }

  if (f['md5_old'] != null)
  {
    f['md5_old'].value = hex_md5(f['old_pwd'].value);
    f['old_pwd'].value = '';
  }

  if (f['md5'] != null)
  {
    f['md5'].value = hex_md5(f['pwd'].value);
    f['pwd'].value = '';
  }

  if (f['md5_confirm'] != null)
  {
    f['md5_confirm'].value = hex_md5(f['pwd2'].value);
    f['pwd2'].value = '';
  }

  if (f['pwd_session'] != null)
  {
    f['md5_session'].value = hex_md5(f['pwd_session'].value);
    f['pwd_session'].value = '';
  }

  return true;
} // end of the 'md5Login()' function
