<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.1 2004/03/19 17:58:28 jact Exp $
 */

/**
 * header.php
 ********************************************************************
 * Contains the common header of the installation pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 19/03/04 18:58
 */

  ////////////////////////////////////////////////////////////////////
  // i18n l10n
  ////////////////////////////////////////////////////////////////////
  require_once("../lib/lang_lib.php");
  require_once("../lib/nls.php");

  define("OPEN_LANGUAGE", setLanguage());
  initLanguage(OPEN_LANGUAGE);

  $nls = getNLS();
  define("OPEN_CHARSET", (isset($nls['charset'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['charset']));
  define("OPEN_DIRECTION", (isset($nls['direction'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['direction']));

  // To prevent 'short_open_tag = On' mistake
  echo '<?xml version="1.0" encoding="ISO-8859-1" standalone="no" ?>' . "\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo OPEN_LANGUAGE; ?>" dir="<?php echo OPEN_DIRECTION; ?>">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo OPEN_CHARSET; ?>" />

<meta http-equiv="Content-Style-Type" content="text/css2" />

<meta http-equiv="Cache-Control" content="no-cache" />

<meta http-equiv="Pragma" content="no-cache" />

<meta http-equiv="expires" content="-1" />

<meta http-equiv="imagetoolbar" content="no" />

<meta name="robots" content="noindex,nofollow" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="author" content="Jose Antonio Chavarría" />

<meta name="copyright" content="2002-2004 Jose Antonio Chavarría" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<style type="text/css">
<?php require_once("../css/style.css"); ?>
</style>

<title><?php echo _("OpenClinic Install"); ?></title>
</head>
<body>
<!-- OpenClinic logo and black background -->
<div id="header"><span class="headerLHS"><a href="http://openclinic.sourceforge.net"><img src="../images/openclinic-1.png" width="291" height="58" alt="<?php echo _("Welcome to OpenClinic"); ?>" title="<?php echo _("Welcome to OpenClinic"); ?>" /></a></span><!-- End .headerLHS --><a href="http://sourceforge.net" title="SourceForge.net Logo"><img src="../images/sf-logo2.png" width="210" height="58" alt="SourceForge.net Logo" /></a></div><!-- End #header -->

<!-- beginning of main body -->
<div id="content">
