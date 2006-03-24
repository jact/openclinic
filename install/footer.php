<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: footer.php,v 1.6 2006/03/24 20:22:42 jact Exp $
 */

/**
 * footer.php
 *
 * Contains the common foot of the installation pages
 *
 * @author jact <jachavar@gmail.com>
 */
?>

</div><!-- End #content -->

<div id="left">
<?php
  echo '<p>';
  HTML::link(_("OpenClinic web site"), 'http://openclinic.sourceforge.net', null,
    array(
      'id' => 'logo',
      'title' => _("OpenClinic web site")
    )
  );
  echo "</p>\n";
?>

  <ul class="linkList">
    <li><?php HTML::link(_("Install Instructions"), '../install.html'); ?></li>

    <li><?php HTML::link(_("OpenClinic Readme"), '../index.html'); ?></li>

    <li class="bold"><?php HTML::link(_("Start OpenClinic"), '../home/index.php'); ?></li>
  </ul>
</div><!-- End #left -->

<div id="footer">
<?php
  echo '<p>' . _("Powered by OpenClinic") . "</p>\n";

  echo '<p>';
  echo sprintf('Copyright &copy; 2002-2006 %s',
    HTML::strLink('Jose Antonio Chavarría', 'mailto:CUT-THIS.jachavar&#64;gmail.com', null,
      array('accesskey' => 9)
    )
  );
  echo "</p>\n";

  echo '<p>' . sprintf(_("Under the %s"), HTML::strLink('GNU General Public License', '../home/license.php', null, array('rel' => 'license'))) . "</p>\n";

  echo '<p>';
  HTML::link('Valid XHTML 1.1', 'http://validator.w3.org/check/referer', null,
    array(
      'id' => 'xhtml11',
      'title' => 'Valid XHTML 1.1'
    )
  );
  echo "</p>\n";
?>
</div><!-- End #footer -->
</body>
</html>
