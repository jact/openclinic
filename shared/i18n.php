<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: i18n.php,v 1.3 2004/10/17 14:57:35 jact Exp $
 */

/**
 * i18n.php
 ********************************************************************
 * Defines i18n l10n constants and initializes OPEN_LANGUAGE settings
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['PATH_TRANSLATED'])
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/lang_lib.php");
  require_once("../lib/nls.php");

  $nls = getNLS();
  if ( !defined("OPEN_LANGUAGE") )
  {
    define("OPEN_LANGUAGE", setLanguage());
  }
  else
  {
    setLanguage(OPEN_LANGUAGE);
  }
  initLanguage(OPEN_LANGUAGE);

  define("OPEN_CHARSET", (isset($nls['charset'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['charset']));
  define("OPEN_DIRECTION", (isset($nls['direction'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['direction']));
  define("OPEN_ENCODING", (isset($nls['encoding'][OPEN_LANGUAGE]) ? $nls['encoding'][OPEN_LANGUAGE] : $nls['default']['encoding']));
?>
