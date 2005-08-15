<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.7 2005/08/15 16:36:17 jact Exp $
 */

/**
 * header.php
 *
 * Contains the common header of the installation pages
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * i18n l10n
   */
  require_once("../shared/i18n.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install");
  require_once("../shared/xhtml_start.php");
?>

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" title="OpenClinic" />
</head>
<body id="top">
<!-- beginning of main body -->
<div id="content">
