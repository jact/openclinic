<?php
/**
 * footer.php
 *
 * Contains the common foot of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: footer.php,v 1.9 2007/12/01 12:59:44 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Msg.php");

  //Error::debug($_SESSION);
  //Error::debug($_SERVER);

  HTML::end('div'); // #content
  HTML::end('div'); // #main

  HTML::rule();

  HTML::start('div', array('id' => 'navigation'));
  if (isset($tab) && is_file('../layout/' . $tab . '.php'))
  {
    include_once("../layout/" . $tab . ".php"); // ul
  }
  echo clinicInfo();
  HTML::end('div'); // #navigation

  HTML::rule();

  HTML::start('div', array('id' => 'footer'));

  echo logos();
  echo sfLinks();
  echo miniLogos();

  HTML::start('div', array('id' => 'app_info'));

  $text = HTML::strLink(_("Powered by OpenClinic"), 'http://openclinic.sourceforge.net/');
  if (defined("OPEN_VERSION"))
  {
    $text .= ' ' . _("version") . ' ' . OPEN_VERSION;
  }
  HTML::para($text);

  HTML::para(
    sprintf('Copyright &copy; 2002-%d %s',
      date("Y"),
      HTML::strLink('Jose Antonio Chavarría', 'mailto:CUT-THIS.openclinic&#64;gmail.com', null,
        array('accesskey' => 9)
      )
    )
  );

  HTML::para(
    sprintf(_("Under the %s"),
      HTML::strLink('GNU General Public License', '../home/license.php', null, array('rel' => 'license'))
    )
  );

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    Msg::info(_("This is a demo version"));
  }

  /**
   * End server page generation time
   */
  if (defined("OPEN_DEBUG") && OPEN_DEBUG)
  {
    $microTime = explode(" ", microtime());
    $endTime = $microTime[1] + $microTime[0];
    $totalTime = sprintf(_("Page generation: %s seconds"), substr(($endTime - $startTime), 0, 6));
    HTML::para($totalTime);
  }

  HTML::end('div'); // #app_info
  HTML::end('div'); // #footer
  HTML::end('div'); // #wrap
  HTML::end('body');
  HTML::end('html');

  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    ob_end_flush();
    flush();
  }
?>
