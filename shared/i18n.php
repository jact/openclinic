<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: i18n.php,v 1.7 2005/08/03 17:40:49 jact Exp $
 */

/**
 * i18n.php
 *
 * Defines i18n l10n constants and initializes OPEN_LANGUAGE settings
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/I18n.php");

  $nls = I18n::getNLS();
  if ( !defined("OPEN_LANGUAGE") )
  {
    define("OPEN_LANGUAGE", I18n::setLanguage());
  }
  else
  {
    I18n::setLanguage(OPEN_LANGUAGE);
  }
  I18n::initLanguage(OPEN_LANGUAGE);

  define("OPEN_CHARSET", (isset($nls['charset'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['charset']));
  define("OPEN_DIRECTION", (isset($nls['direction'][OPEN_LANGUAGE]) ? $nls['charset'][OPEN_LANGUAGE] : $nls['default']['direction']));
  define("OPEN_ENCODING", (isset($nls['encoding'][OPEN_LANGUAGE]) ? $nls['encoding'][OPEN_LANGUAGE] : $nls['default']['encoding']));
?>
