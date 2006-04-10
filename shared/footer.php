<?php
/**
 * footer.php
 *
 * Contains the common foot of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: footer.php,v 1.25 2006/04/10 19:03:33 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  //Error::debug($_SESSION);
  //Error::debug($_SERVER);
?>
</div><!-- End #mainZone -->

<hr />

<div id="footer">
  <ul id="footerLinks">
    <li><?php HTML::link(_("Clinic Home"), '../home/index.php', null, array('accesskey' => 1)); ?></li>

    <li><?php HTML::link(_("OpenClinic Readme"), '../index.html'); ?></li>

<?php
  if (isset($tab) && isset($nav))
  {
    echo '<li>';
    HTML::link(_("Help"), '../doc/index.php',
      array(
        'tab' => $tab,
        'nav' => $nav
      ),
      array(
        'title' => _("Opens a new window"),
        'onclick' => "return popSecondary('../doc/index.php?tab=" . $tab . '&amp;nav=' . $nav . "')"
      )
    );
    echo "</li>\n";
  }

  if (isset($_SESSION["hasAdminAuth"]) && ($_SESSION["hasAdminAuth"] === true && !OPEN_DEMO))
  {
    echo '<li>';
    HTML::link(_("View source code"), '../shared/view_source.php',
      array(
        'file' => $_SERVER['PHP_SELF'],
        'tab' => $tab
      ),
      array(
        'title' => _("Opens a new window"),
        'onclick' => "return popSecondary('../shared/view_source.php?file=" . $_SERVER['PHP_SELF'] . '&amp;tab=' . $tab . "')"
      )
    );
    echo "</li>\n";
  }

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    echo '<li>' . HTML::strLink(_("Demo version features"), '../demo_version.html') . "</li>\n";
  }
?>
  </ul><!-- End #footerLinks -->

  <p>
    <?php
      echo _("Powered by OpenClinic");
      if (defined("OPEN_VERSION"))
      {
        echo ' ' . _("version") . ' ' . OPEN_VERSION;
      }
    ?>
  </p>

<?php
  echo '<p>';
  echo sprintf('Copyright &copy; 2002-2006 %s',
    HTML::strLink('Jose Antonio Chavarría', 'mailto:CUT-THIS.jachavar&#64;gmail.com', null,
      array('accesskey' => 9)
    )
  );
  echo "</p>\n";

  echo '<p>' . sprintf(_("Under the %s"), HTML::strLink('GNU General Public License', '../home/license.php', null, array('rel' => 'license'))) . "</p>\n";

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
    echo '<p>' . $totalTime . "</p>\n";
  }
?>
</div><!-- End #footer -->
</body>
</html>
<?php
  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    ob_end_flush();
    flush();
  }
?>
