<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.5 2004/06/20 12:06:31 jact Exp $
 */

/**
 * header.php
 ********************************************************************
 * Contains the common header of the installation pages
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // i18n l10n
  ////////////////////////////////////////////////////////////////////
  require_once("../shared/i18n.php");

  ////////////////////////////////////////////////////////////////////
  // XHTML Start (XML prolog, DOCTYPE, title page and meta data)
  ////////////////////////////////////////////////////////////////////
  $title = _("OpenClinic Install");
  require_once("../shared/xhtml_start.php");
?>

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" title="OpenClinic" />
</head>
<body>
<!-- OpenClinic logo and black background -->
<div id="header"><span class="headerLHS"><a href="http://openclinic.sourceforge.net"><img src="../images/openclinic-1.png" width="291" height="58" alt="<?php echo _("Welcome to OpenClinic"); ?>" title="<?php echo _("Welcome to OpenClinic"); ?>" /></a></span><!-- End .headerLHS --><a href="http://sourceforge.net" title="SourceForge.net Logo"><img src="../images/sf-logo2.png" width="210" height="58" alt="SourceForge.net Logo" /></a></div><!-- End #header -->

<!-- beginning of main body -->
<div id="content">
