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
 * @version   CVS: $Id: form_errors_msg.php,v 1.10 2006/09/30 17:26:59 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (isset($formError) && count($formError) > 0)
  {
    HTML::start('div', array('class' => 'error'));
    HTML::para(_("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message."));

    $array = null;
    foreach ($formError as $key => $value)
    {
      if ($value)
      {
        $array[] = HTML::strLink($value, '#' . $key, null,
          array('onclick' => "document.getElementById('" . $key . "').focus();")
        );
      }
    }
    if (is_array($array))
    {
      HTML::itemList($array);
    }

    HTML::end('div');
  }
?>
