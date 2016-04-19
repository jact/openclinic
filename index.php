<?php
/**
 * index.php
 *
 * Index page of the project
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2016 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @author    jact <jachavar@gmail.com>
 * @todo      i18n and HTML.php inclusion
 */

  // Ensuring a minimum version of PHP
  define("OPEN_PHP_VERSION", '5.3.1'); // @fixme in global_constants.php
  if (version_compare(phpversion(), OPEN_PHP_VERSION) < 0)
  {
    exit(sprintf('PHP %s or higher is required.', OPEN_PHP_VERSION));
  }

  require_once("./config/database_constants.php");

  if ( !extension_loaded("mysqli") )
  {
    echo 'It is impossible execute OpenClinic without MySQL support.' . '<br />';
    echo 'When you installed it, try again.' . '<br />';
    echo 'For more details, see <a href="./install.html">Install instructions</a>.';
    exit();
  }

  $conn = new mysqli(
    OPEN_HOST,
    OPEN_USERNAME,
    OPEN_PWD,
    OPEN_DATABASE,
    OPEN_PORT
  );
  if ($conn->connect_errno)
  {
    echo $conn->connect_errno . '<br />' . $conn->connect_error . '<hr />';
    echo 'This Server is not ready to work. Contact admin and ask to start MySQL server.<br />';
    echo 'If it is your first use <a href="./install/wizard.php">go to the new installation process</a>.';
    echo '<br />Or if you prefer <a href="./install/index.php">go to normal install script</a>.';
    exit();
  }

  @mysqli_close($conn);

  header("Location: home/index.php");
?>
