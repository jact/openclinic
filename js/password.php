<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: password.php,v 1.3 2006/03/26 15:24:51 jact Exp $
 */

/**
 * password.php
 *
 * Contains the function md5Login used in:
 *  - user_new_form.php
 *  - user_edit_form.php
 *  - user_pwd_reset_form.php
 *
 * @author jact <jachavar@gmail.com>
 */

require_once("../shared/read_settings.php");
header("Content-Type: text/javascript; charset=" . OPEN_CHARSET);
?>
/**
 * bool md5Login(string f)
 *
 * Translates plain text passwords to md5 passwords
 *
 * @param string form name
 * @return boolean true if ok, false otherwise
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

  return true;
} // end of the 'md5Login()' function
