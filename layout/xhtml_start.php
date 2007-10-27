<?php
/**
 * xhtml_start.php
 *
 * Contains the common XHTML content of the web pages (XML prolog, DOCTYPE, title page and meta data)
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: xhtml_start.php,v 1.5 2007/10/27 14:06:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * string _convert2Utf8(string $buffer)
   *
   * Callback function for ob_start
   *
   * @param string $buffer
   * @return string $buffer utf8 converted
   * @access private
   * @since 0.8
   */
  function _convert2Utf8($buffer)
  {
    //return mb_convert_encoding($buffer, "UTF-8", OPEN_CHARSET);
    return utf8_encode($buffer);
  }

  /**
   * Content negotiation
   *
   * Author: Tommy Olsson <http://autisticcuckoo.net/>
   */
  $_xhtml = false;
  if (preg_match('/application\/xhtml\+xml(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $_matches))
  {
    $_xhtmlQ = isset($_matches[2]) ? $_matches[2] : 1;
    if (preg_match('/text\/html(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $_matches))
    {
      $_htmlQ = isset($_matches[2]) ? $_matches[2] : 1;
      $_xhtml = ($_xhtmlQ >= $_htmlQ);
    }
    else
    {
      $_xhtml = true;
    }
  }
  $_xhtml = ($_xhtml && (defined("OPEN_XML_ACTIVED") ? OPEN_XML_ACTIVED : false));

  $_contentType = ($_xhtml) ? "application/xhtml+xml" : "text/html";
  $_contentType .= "; charset=" . OPEN_CHARSET/*"UTF-8"*/;

  header("Content-Type: " . $_contentType); // force document encoding, ignore server configuration
  header("Vary: Accept");

  /**
   * Force not caching
   */
  header("Expires: -1");
  header("Etag: " . md5(uniqid(rand(), true)));

  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    if (eregi("gzip", $_SERVER['HTTP_ACCEPT_ENCODING']))
    {
      ob_start("ob_gzhandler");
    }
    else
    {
      ob_start(/*"_convert2Utf8"*/); // _convert2Utf8
    }
  }

  if (strpos($_contentType, "application/xhtml+xml") !== false)
  {
    // To prevent 'short_open_tag = On' mistake
    echo '<?xml version="1.0" encoding="' . OPEN_ENCODING/*"UTF-8"*/ . '" standalone="no" ?>' . PHP_EOL;
  }

  if ($_xhtml)
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . PHP_EOL;
  }
  else
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . PHP_EOL;
  }

  HTML::start('html',
    array(
      'xmlns' => 'http://www.w3.org/1999/xhtml',
      'xml:lang' => str_replace("_", "-", OPEN_LANGUAGE),
      'dir' => OPEN_DIRECTION
    )
  );
  HTML::start('head');
  HTML::start('meta', array('http-equiv' => 'Content-Type', 'content' => $_contentType), true);

  $_titlePage = (isset($titlePage)) ? $titlePage : ((isset($title) && !empty($title) ) ? $title : "");

  /**
   * @since 0.8
   */
  $_titlePage .= (isset($formError) && count($formError) > 0 && isset($focusFormField))
    ? " : " . _("Error occurred")
    : "";

  if (defined("OPEN_CLINIC_NAME") && OPEN_CLINIC_NAME)
  {
    $_titlePage .= ' : ' . OPEN_CLINIC_NAME;
  }

  HTML::tag('title', $_titlePage);

  //HTML::start('meta', array('http-equiv' => 'Content-Style-Type', 'content' => 'text/css2'), true);
  HTML::start('meta', array('http-equiv' => 'Cache-Control', 'content' => 'no-store,no-cache,must-revalidate'), true);
  HTML::start('meta', array('http-equiv' => 'Pragma', 'content' => 'no-cache'), true);
  HTML::start('meta', array('http-equiv' => 'Expires', 'content' => '-1'), true);
  HTML::start('meta', array('http-equiv' => 'imagetoolbar', 'content' => 'no'), true);

  HTML::start('meta', array('name' => 'robots', 'content' => 'noindex,nofollow,noarchive'), true);
  HTML::start('meta', array('name' => 'MSSmartTagsPreventParsing', 'content' => 'TRUE'), true);
  HTML::start('meta', array('name' => 'author', 'content' => 'Jose Antonio Chavarría'), true);
  HTML::start('meta', array('name' => 'copyright', 'content' => '2002-' . date("Y") . ' Jose Antonio Chavarría'), true);
  HTML::start('meta', array('name' => 'keywords', 'content' => 'OpenClinic, open source, gpl, healthcare, php, mysql, coresis'), true);
  HTML::start('meta', array('name' => 'description', 'content' => 'OpenClinic is an easy to use, open source, medical records system written in PHP'), true);
?>
