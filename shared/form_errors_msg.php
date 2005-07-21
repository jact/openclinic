<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: form_errors_msg.php,v 1.4 2005/07/21 16:57:13 jact Exp $
 */

/**
 * form_errors_msg.php
 *
 * Show message of form errors if it is necessary
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (count($pageErrors) > 0)
  {
    HTML::message(_("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message."), OPEN_MSG_ERROR);
  }
?>
