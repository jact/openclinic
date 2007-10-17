<?php
/**
 * form_errors_msg.php
 *
 * Show message of form errors if it is necessary
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: form_errors_msg.php,v 1.11 2007/10/17 19:17:09 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (isset($formError) && count($formError) > 0)
  {
    echo HTML::insertScript('target_focus.js');

    HTML::start('div', array('class' => 'error'));
    HTML::para(_("ERROR: Some fields have been incorrectly filled. Please fix the fields and send the form again. Each incorrectly filled field is marked with specific error message."));

    $array = null;
    foreach ($formError as $key => $value)
    {
      if ($value)
      {
        $array[] = HTML::strLink($value, '#' . $key, null,
          array('class' => 'target') // unobtrusive JS
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
