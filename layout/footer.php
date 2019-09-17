<?php
/**
 * footer.php
 *
 * Contains the common foot of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2019 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once("../lib/Msg.php");

  //AppError::debug($_SESSION);
  //AppError::debug($_SERVER);

  echo HTML::end('div'); // #content
  echo HTML::end('div'); // #main

  echo HTML::rule();

  echo HTML::start('div', array('id' => 'navigation'));
  if (isset($tab) && is_file('../layout/' . $tab . '.php'))
  {
    include_once("../layout/" . $tab . ".php"); // ul
  }
  echo clinicInfo();
  echo HTML::end('div'); // #navigation

  echo HTML::rule();

  echo HTML::start('div', array('id' => 'footer'));

  echo logos();
  echo sfLinks();
  echo miniLogos();

  echo HTML::start('div', array('id' => 'app_info'));

  $text = HTML::link(_("Powered by OpenClinic"), 'http://openclinic.sourceforge.net/');
  if (defined("OPEN_VERSION"))
  {
    $text .= ' ' . _("version") . ' ' . OPEN_VERSION;
  }
  echo HTML::para($text);

  echo HTML::para(
    sprintf('Copyright &copy; 2002-%d %s',
      date("Y"),
      HTML::link('Jose Antonio Chavarr&iacute;a', 'mailto:CUT-THIS.openclinic&#64;gmail.com', null,
        array('accesskey' => 9)
      )
    )
  );

  echo HTML::para(
    sprintf(_("Under the %s"),
      HTML::link('GNU General Public License', '../home/license.php', null, array('rel' => 'license'))
    )
  );

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    echo Msg::info(_("This is a demo version"));
  }

  /**
   * End server page generation time
   */
  if (defined("OPEN_DEBUG") && OPEN_DEBUG)
  {
    $microTime = explode(" ", microtime());
    $endTime = $microTime[1] + $microTime[0];
    $totalTime = sprintf(_("Page generation: %s seconds"), substr(($endTime - $startTime), 0, 6));
    echo HTML::para($totalTime);
  }

  echo HTML::end('div'); // #app_info
  echo HTML::end('div'); // #footer
  echo HTML::end('div'); // #wrap
  echo HTML::end('body');
  echo HTML::end('html');

  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    ob_end_flush();
    flush();
  }
