<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: form_errors_msg.php,v 1.1 2004/01/29 15:14:04 jact Exp $
 */

/**
 * form_errors_msg.php
 ********************************************************************
 * Show message of form errors if is necessary
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 16:14
 */

  if (count($pageErrors) > 0)
  {
    echo '<p class="error">' . _("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message.") . "</p>\n";
  }
?>
