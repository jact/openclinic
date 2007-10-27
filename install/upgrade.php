<?php
/**
 * upgrade.php
 *
 * Upgrade BD using upgrade files
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: upgrade.php,v 1.3 2007/10/27 17:52:57 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once(dirname(__FILE__) . "/header.php"); // i18n l10n
  require_once(dirname(__FILE__) . "/parse_sql_file.php");
  require_once("../model/Setting_Query.php");
  require_once("../lib/File.php"); // File::getDirContent()

  HTML::section(1, _("Upgrade OpenClinic database:"));

  $setQ = new Setting_Query();
  $setQ->connect();

  $setQ->captureError(true);
  $setQ->select();
  if ($setQ->isError())
  {
    $setQ->close();

    HTML::para(_("The connection to the database failed with the following error:"));
    Msg::error($setQ->getDbError());
    HTML::rule();
    HTML::para(HTML::strLink(_("Back to installation main page"), './index.php'));

    include_once(dirname(__FILE__) . "/footer.php");
    exit();
  }

  $set = $setQ->fetch();
  if ( !$set )
  {
    $setQ->close();
    Error::fetch($setQ);
  }

  $version = $set->getVersion();

  if ( !preg_match("/\d+(\.\d+){1,2}/", $version) )
  {
    Msg::error(sprintf(_("Version field doesn't have a valid format (%s)."), $version));
    HTML::rule();
    HTML::para(HTML::strLink(_("Back to installation main page"), './index.php'));

    include_once(dirname(__FILE__) . "/footer.php");
    exit();
  }
  HTML::para(sprintf(_("Finded version: %s"), $version));
  if (substr_count($version, '.') == 2)
  {
    $version = substr($version, 0, strrpos($version, '.')); // only 2 groups of ciphers: <number>.<number>
  }

  $upgrades = File::getDirContent('./upgrades', false, array('sql'));
  if ( !is_array($upgrades) )
  {
    Msg::error(_("There aren't upgrade files."));

    include_once(dirname(__FILE__) . "/footer.php");
    exit();
  }

  foreach ($upgrades as $value)
  {
    $file = $value; // upgrade<initial_version>-<final_version>.sql
    $value = substr($value, strlen('upgrade')); // <initial_version>-<final_version>.sql
    $array = explode("-", $value); // 0 => <initial_version>, 1 => <final_version>.sql
    $initialVersion = $array[0]; // <number>.<number>
    if (version_compare($version, $initialVersion, '<='))
    {
      HTML::para(sprintf(_("Aplying %s file..."), HTML::strTag('strong', $file)));
      if ( !parseSQLFile('./upgrades/' . $file, '', false) )
      {
        //Error::debug($file); // debug
        //Msg::error(sprintf(_("Error processing file: %s"), $value));

        include_once(dirname(__FILE__) . "/footer.php");
        exit();
      }
    }
  }

  $setQ->captureError(false);
  $setQ->select();
  $set = $setQ->fetch();
  HTML::para(sprintf(_("Upgraded to version: %s"), $set->getVersion()));

  $setQ->close();
  unset($setQ);
  unset($set);

  Msg::info(_("Database upgrade finished correctly!"));

  HTML::section(1, HTML::strLink(_("Start using OpenClinic"), '../home/index.php'));

  require_once(dirname(__FILE__) . "/footer.php");
?>
