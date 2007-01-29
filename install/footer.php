<?php
/**
 * footer.php
 *
 * Contains the common foot of the installation pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: footer.php,v 1.9 2007/01/29 15:25:27 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  HTML::end('div'); // #content

  HTML::start('div', array('id' => 'menu'));

  HTML::para(
    HTML::strLink(_("OpenClinic web site"), 'http://openclinic.sourceforge.net', null,
      array(
        'id' => 'logo',
        'title' => _("OpenClinic web site")
      )
    )
  );

  $array = array(
    HTML::strLink(_("Install Instructions"), '../install.html'),
    HTML::strLink(_("OpenClinic Readme"), '../index.html'),
    HTML::strLink(HTML::strTag('strong', _("Start OpenClinic")), '../home/index.php')
  );
  HTML::itemList($array);

  HTML::end('div'); // #menu

  HTML::start('div', array('id' => 'footer'));

  HTML::para(_("Powered by OpenClinic"));

  HTML::para(
    sprintf('Copyright &copy; 2002-%s %s',
      date("Y"),
      HTML::strLink('Jose Antonio Chavarría', 'mailto:CUT-THIS.openclinic&#64;gmail.com', null,
        array('accesskey' => 9)
      )
    )
  );

  HTML::para(
    sprintf(_("Under the %s"),
      HTML::strLink('GNU General Public License', '../LICENSE', null, array('rel' => 'license'))
    )
  );

  HTML::para(
    HTML::strLink('Valid XHTML 1.1', 'http://validator.w3.org/check/referer', null,
      array(
        'id' => 'xhtml11',
        'title' => 'Valid XHTML 1.1'
      )
    )
  );

  HTML::end('div'); // #footer
  HTML::end('body');
  HTML::end('html');
?>
