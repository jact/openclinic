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
 * @version   CVS: $Id: upgrade.php,v 1.1 2007/01/29 15:28:07 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  require_once("../install/header.php"); // i18n l10n
  require_once("../model/Setting_Query.php");
  require_once("../lib/File.php"); // File::getDirContent()
  require_once("../install/parse_sql_file.php");

  HTML::section(1, _("Upgrade OpenClinic database:"));

  $setQ = new Setting_Query();
  $setQ->connect();

  $setQ->captureError(true);
  $setQ->select();
  if ($setQ->isError())
  {
    $setQ->close();

    HTML::para(_("The connection to the database failed with the following error:"));
    HTML::message($setQ->getDbError(), OPEN_MSG_ERROR);
    HTML::rule();
    HTML::para(HTML::strLink(_("Back to installation main page"), './index.php'));

    include_once("../install/footer.php");
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
    HTML::message(sprintf(_("Version field doesn't have a valid format (%s)."), $version), OPEN_MSG_ERROR);
    HTML::rule();
    HTML::para(HTML::strLink(_("Back to installation main page"), './index.php'));

    include_once("../install/footer.php");
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
    HTML::message(_("There aren't upgrade files."), OPEN_MSG_ERROR);

    include_once("../install/footer.php");
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
        //HTML::message(sprintf(_("Error processing file: %s"), $value), OPEN_MSG_ERROR);

        include_once("../install/footer.php");
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

  HTML::message(_("Database upgrade finished correctly!"));

  HTML::section(1, HTML::strLink(_("Start using OpenClinic"), '../home/index.php'));

  require_once("../install/footer.php");
?>
