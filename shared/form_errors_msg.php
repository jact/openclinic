<?php
/**
 * form_errors_msg.php
 *
 * Show message of form errors if it is necessary
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: form_errors_msg.php,v 1.9 2006/03/28 19:20:42 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (isset($formError) && count($formError) > 0)
  {
    echo '<div class="error">';
    echo '<p>' . _("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message.") . "</p>\n";

    echo "<ul>\n";
    foreach ($formError as $key => $value)
    {
      if ($value)
      {
        echo '<li>';
        HTML::link($value, '#' . $key, null,
          array('onclick' => "document.getElementById('" . $key . "').focus();")
        );
        echo "</li>\n";
      }
    }
    echo "</ul>\n";
    echo "</div>\n";
  }
?>
