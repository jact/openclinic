<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: form_errors_msg.php,v 1.2 2004/04/18 14:02:25 jact Exp $
 */

/**
 * form_errors_msg.php
 ********************************************************************
 * Show message of form errors if is necessary
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (count($pageErrors) > 0)
  {
    echo '<p class="error">' . _("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message.") . "</p>\n";
  }
?>
