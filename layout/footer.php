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
 * @version   CVS: $Id: footer.php,v 1.3 2007/10/03 19:37:23 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  HTML::end('div'); // #mainZone

  //Error::debug($_SESSION);
  //Error::debug($_SERVER);

  HTML::rule();

  HTML::start('div', array('id' => 'footer'));

  $footLinks = array(
    HTML::strLink(_("Clinic Home"), '../home/index.php', null, array('accesskey' => 1)),
    HTML::strLink(_("OpenClinic Readme"), '../index.html')
  );

  if (isset($tab) && isset($nav))
  {
    $footLinks[] = HTML::strLink(_("Help"), '../doc/index.php',
      array(
        'tab' => $tab,
        'nav' => $nav
      ),
      array(
        'title' => _("Opens a new window"),
        'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&nav=' . $nav . "')"
      )
    );
  }

  if (isset($_SESSION["hasAdminAuth"]) && ($_SESSION["hasAdminAuth"] === true && !OPEN_DEMO))
  {
    $footLinks[] = HTML::strLink(_("View source code"), '../shared/view_source.php',
      array(
        'file' => $_SERVER['PHP_SELF'],
        'tab' => $tab
      ),
      array(
        'title' => _("Opens a new window"),
        'onclick' => "return popSecondary('../shared/view_source.php?file=" . $_SERVER['PHP_SELF'] . '&tab=' . $tab . "')"
      )
    );
  }

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    $footLinks[] = HTML::strLink(_("Demo version features"), '../demo_version.html');
  }

  HTML::itemList($footLinks, array('id' => 'footerLinks'));

  $text = _("Powered by OpenClinic");
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
    HTML::message(_("This is a demo version"), OPEN_MSG_INFO);
  }

  /**
   * End server page generation time
   */
  $microTime = explode(" ", microtime());
  $endTime = $microTime[1] + $microTime[0];
  $totalTime = sprintf(_("Page generation: %s seconds"), substr(($endTime - $startTime), 0, 6));

  if (defined("OPEN_DEBUG") && OPEN_DEBUG)
  {
    HTML::para($totalTime);
  }

  HTML::end('div'); // #footer
  HTML::end('body');
  HTML::end('html');

  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    ob_end_flush();
    flush();
  }
?>
