<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: footer.php,v 1.7 2004/08/02 09:24:19 jact Exp $
 */

/**
 * footer.php
 ********************************************************************
 * Contains the common foot of the web pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }

  //debug($_SESSION);
  //debug($_SERVER);
?>
</div><!-- End #mainZone -->
<!-- End Main Zone -->

<hr class="noPrint" />

<!-- Footer -->
<div id="footer">
  <div id="footerLinks">
    <?php echo '<a href="../home/index.php" accesskey="1">' . _("Clinic Home") . '</a> |'; ?>

    <a href="../index.html"><?php echo _("OpenClinic Readme"); ?></a> |

    <a href="../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../doc/index.php?tab=<?php echo $tab; ?>&amp;nav=<?php echo $nav; ?>')"><?php echo _("Help"); ?></a>

    <?php
      if ($_SESSION["userId"] == 1 || ($_SESSION["userId"] > 0 && $_SESSION["userId"] < 3 && !OPEN_DEMO))
      {
    ?>
        | <a href="../shared/view_source.php?file=<?php echo $_SERVER['PHP_SELF']; ?>&amp;tab=<?php echo $tab; ?>" title="<?php echo _("Opens a new window"); ?>" onclick="return popSecondary('../shared/view_source.php?file=<?php echo $_SERVER['PHP_SELF']; ?>&amp;tab=<?php echo $tab; ?>')"><?php echo _("View source code"); ?></a>
    <?php
      }

      if (defined("OPEN_DEMO") && OPEN_DEMO)
      {
        echo ' | <a href="../demo_version.html">' . _("Demo version features") . "</a>\n";
        showMessage(_("This is a demo version"), OPEN_MSG_INFO);
      }
    ?>
  </div><!-- End #footerLinks -->

  <div class="subFooter">
    <?php
      echo _("Powered by OpenClinic");
      if (defined("OPEN_VERSION"))
      {
        echo ' ' . _("version") . ' ' . OPEN_VERSION;
      }
    ?>
    <br />
    Copyright &copy; 2002-2004 <a href="mailto:CUT-THIS.jachavar&#64;terra.es" accesskey="9">Jose Antonio Chavarría</a>
    <br />
    <?php echo _("under the"); ?>
    <a href="../home/license.php">GNU General Public License</a>
  </div><!-- End .subFooter -->
</div><!-- End #footer -->
<!-- End Footer -->
</body>
</html>
