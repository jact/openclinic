<?php
/**
 * i18n.php
 *
 * Defines i18n l10n constants and initializes OPEN_LANGUAGE settings
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: i18n.php,v 1.3 2008/04/02 19:14:19 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

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

  define("OPEN_CHARSET",
    (isset($nls['charset'][OPEN_LANGUAGE])
      ? $nls['charset'][OPEN_LANGUAGE]
      : $nls['default']['charset']
    )
  );
  define("OPEN_DIRECTION",
    (isset($nls['direction'][OPEN_LANGUAGE])
      ? $nls['charset'][OPEN_LANGUAGE]
      : $nls['default']['direction']
    )
  );
  define("OPEN_ENCODING",
    (isset($nls['encoding'][OPEN_LANGUAGE])
      ? $nls['encoding'][OPEN_LANGUAGE]
      : $nls['default']['encoding']
    )
  );

  I18n::initLanguage(OPEN_LANGUAGE);
?>
