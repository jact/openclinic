<?php
/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: header.php,v 1.8 2006/03/25 20:04:10 jact Exp $
 */

/**
 * header.php
 *
 * Contains the common header of the installation pages
 *
 * @author jact <jachavar@gmail.com>
 */

  /**
   * i18n l10n
   */
  require_once("../shared/i18n.php");

  require_once("../lib/HTML.php");

  /**
   * XHTML Start (XML prolog, DOCTYPE, title page and meta data)
   */
  $title = _("OpenClinic Install");
  require_once("../shared/xhtml_start.php");
?>

<link rel="icon" type="image/png" href="../images/miniopc.png" />

<link rel="shortcut icon" type="image/png" href="../images/miniopc.png" />

<link rel="bookmark icon" type="image/png" href="../images/miniopc.png" />

<link rel="stylesheet" type="text/css" href="../css/style.css" media="all" title="OpenClinic" />
</head>
<body id="top">
<!-- beginning of main body -->
<div id="content">
