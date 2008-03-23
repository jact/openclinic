<?php
/**
 * upgrade.php
 *
 * Upgrade BD using upgrade files
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: upgrade.php,v 1.11 2008/03/23 11:59:27 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  $tab = "install";
  $nav = "upgrade";
  $returnLocation = './index.php';

  require_once("../config/environment.php");
  require_once("../model/Query/Setting.php");
  require_once("../lib/File.php"); // File::getDirContent()

  $setQ = new Query_Setting();
  $setQ->captureError(true);
  $setQ->select();
  if ($setQ->isError())
  {
    FlashMsg::add(
      sprintf(_("The connection to the database failed with the following error: %s"), $setQ->getDbError()),
      OPEN_MSG_ERROR
    );
    $setQ->close(); // after getDbError
    header("Location: " . $returnLocation);
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
    FlashMsg::add(sprintf(_("Version field doesn't have a valid format (%s)."), $version), OPEN_MSG_ERROR);
    header("Location: " . $returnLocation);
    exit();
  }
  if (substr_count($version, '.') == 2)
  {
    $version = substr($version, 0, strrpos($version, '.')); // only 2 groups of ciphers: <number>.<number>
  }

  $upgrades = File::getDirContent('./upgrades', false, array('sql'));
  if ( !is_array($upgrades) )
  {
    FlashMsg::add(_("There aren't upgrade files."), OPEN_MSG_ERROR);
    header("Location: " . $returnLocation);
    exit();
  }

  $title = _("Upgrade Database");
  require_once("../layout/header.php");
  require_once(dirname(__FILE__) . "/parse_sql_file.php");

  echo HTML::section(1, $title);
  echo HTML::para(sprintf(_("Finded version: %s"), $version));

  foreach ($upgrades as $value)
  {
    $file = $value; // upgrade<initial_version>-<final_version>.sql
    $value = substr($value, strlen('upgrade')); // <initial_version>-<final_version>.sql
    $array = explode("-", $value); // 0 => <initial_version>, 1 => <final_version>.sql
    $initialVersion = $array[0]; // <number>.<number>
    if (version_compare($version, $initialVersion, '<='))
    {
      echo HTML::para(sprintf(_("Aplying %s file..."), HTML::tag('strong', $file)));
      if ( !parseSqlFile('./upgrades/' . $file) )
      {
        //Error::debug($file); // debug
        //echo Msg::error(sprintf(_("Error processing file: %s"), $value));

        include_once("../layout/footer.php");
        exit();
      }
    }
  }

  $setQ->captureError(false);
  $setQ->select();
  $set = $setQ->fetch();
  echo HTML::para(sprintf(_("Upgraded to version: %s"), $set->getVersion()));

  $setQ->close();
  unset($setQ);
  unset($set);

  echo Msg::info(_("Database upgrade finished correctly!"));

  echo HTML::para(HTML::link(_("Go to OpenClinic"), '../home/index.php'));

  require_once("../layout/footer.php");
?>
