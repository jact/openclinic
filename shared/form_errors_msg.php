<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: form_errors_msg.php,v 1.7 2006/03/15 20:05:48 jact Exp $
 */

/**
 * form_errors_msg.php
 *
 * Show message of form errors if it is necessary
 *
 * Author: jact <jachavar@gmail.com>
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
        echo '<li><a href="#' . $key . '" onclick="document.getElementById(\'' . $key . '\').focus();">' . $value . "</a></li>\n";
      }
    }
    echo "</ul>\n";
    echo "</div>\n";
  }
?>
