<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: footer.php,v 1.19 2005/07/21 16:57:13 jact Exp $
 */

/**
 * footer.php
 *
 * Contains the common foot of the web pages
 *
 * Author: jact <jachavar@gmail.com>
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['PATH_TRANSLATED']))
  {
    header("Location: ../index.php");
    exit();
  }

  //Error::debug($_SESSION);
  //Error::debug($_SERVER);
?>
</div><!-- End #mainZone -->
<!-- End Main Zone -->

<hr class="noPrint" />

<!-- Footer -->
<div id="footer">
  <ul id="footerLinks">
    <li><a href="../home/index.php" accesskey="1"><?php echo _("Clinic Home"); ?></a></li>

    <li><a href="../index.html"><?php echo _("OpenClinic Readme"); ?></a></li>

<?php
  if (isset($tab) && isset($nav))
  {
    echo '<li><a href="../doc/index.php?tab=' . $tab . '&amp;nav=' . $nav . '" title="' . _("Opens a new window") . '" onclick="return popSecondary(\'../doc/index.php?tab=' . $tab . '&amp;nav=' . $nav . '\')">' . _("Help") . "</a></li>\n";
  }

  if (isset($_SESSION["userId"]) && ($_SESSION["userId"] == 1 || ($_SESSION["userId"] > 0 && $_SESSION["userId"] < 3 && !OPEN_DEMO)))
  {
    echo '<li><a href="../shared/view_source.php?file=' . $_SERVER['PHP_SELF'] . '&amp;tab=' . $tab . '" title="' . _("Opens a new window") . '" onclick="return popSecondary(\'../shared/view_source.php?file=' . $_SERVER['PHP_SELF'] . '&amp;tab=' . $tab . '\')">' . _("View source code") . "</a></li>\n";
  }

  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    echo '<li><a href="../demo_version.html">' . _("Demo version features") . "</a></li>\n";
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

  <p>
    Copyright &copy; 2002-2005 <a href="mailto:CUT-THIS.jachavar&#64;gmail.com" accesskey="9">Jose Antonio Chavarría</a>
    <br />
    <?php echo _("under the"); ?>
    <a href="../home/license.php" rel="license">GNU General Public License</a>
  </p>

<?php
  if (defined("OPEN_DEMO") && OPEN_DEMO)
  {
    HTML::message(_("This is a demo version"), OPEN_MSG_INFO);
  }

  ////////////////////////////////////////////////////////////////////
  // End server page generation time
  ////////////////////////////////////////////////////////////////////
  $microTime = explode(" ", microtime());
  $endTime = $microTime[1] + $microTime[0];
  $totalTime = sprintf(_("Page generation: %s seconds"), substr(($endTime - $startTime), 0, 6));

  if (defined("OPEN_DEBUG") && OPEN_DEBUG)
  {
    echo '<p>' . $totalTime . "</p>\n";
  }
?>
</div><!-- End #footer -->
<!-- End Footer -->
</body>
</html>
<?php
  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    ob_end_flush();
  }
?>
