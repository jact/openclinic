<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: xhtml_start.php,v 1.17 2006/03/15 20:05:15 jact Exp $
 */

/**
 * xhtml_start.php
 *
 * Contains the common XHTML content of the web pages (XML prolog, DOCTYPE, title page and meta data)
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.7
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  /**
   * Content negotiation
   *
   * Author: Tommy Olsson <http://autisticcuckoo.net/>
   */
  $xhtml = false;
  if (preg_match('/application\/xhtml\+xml(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $matches))
  {
    $xhtmlQ = isset($matches[2]) ? $matches[2] : 1;
    if (preg_match('/text\/html(;q=(\d+\.\d+))?/i', $_SERVER['HTTP_ACCEPT'], $matches))
    {
      $htmlQ = isset($matches[2]) ? $matches[2] : 1;
      $xhtml = ($xhtmlQ >= $htmlQ);
    }
    else
    {
      $xhtml = true;
    }
  }
  $xhtml = ($xhtml && (defined("OPEN_XML_ACTIVED") ? OPEN_XML_ACTIVED : false));

  $contentType = ($xhtml) ? "application/xhtml+xml" : "text/html";
  $contentType .= "; charset=" . OPEN_CHARSET;

  header("Content-Type: " . $contentType); // force document encoding, ignore server configuration
  header("Vary: Accept");

  if (defined("OPEN_BUFFER") && OPEN_BUFFER)
  {
    if (eregi("gzip", $_SERVER['HTTP_ACCEPT_ENCODING']))
    {
      ob_start("ob_gzhandler");
    }
    else
    {
      ob_start();
    }
  }

  if (strpos($contentType, "application/xhtml+xml") !== false)
  {
    // To prevent 'short_open_tag = On' mistake
    echo '<?xml version="1.0" encoding="' . OPEN_ENCODING . '" standalone="no" ?>' . "\n";
  }

  if ($xhtml)
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">' . "\n";
  }
  else
  {
    echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">' . "\n";
  }
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo str_replace("_", "-", OPEN_LANGUAGE); ?>" dir="<?php echo OPEN_DIRECTION; ?>">
<head>
<meta http-equiv="Content-Type" content="<?php echo $contentType; ?>" />

<title><?php
  if (defined("OPEN_CLINIC_NAME") && OPEN_CLINIC_NAME)
  {
    echo OPEN_CLINIC_NAME . " : ";
  }
  echo ((isset($title) && $title != "") ? $title : "");

  /**
   * @since 0.8
   */
  echo ((isset($formError) && count($formError) > 0 && isset($focusFormField)) ? " : " . _("Error occurred") : "");
?></title>

<?php //<meta http-equiv="Content-Style-Type" content="text/css2" /> ?>
<meta http-equiv="Cache-Control" content="no-store,no-cache,must-revalidate" />

<meta http-equiv="Pragma" content="no-cache" />

<meta http-equiv="Expires" content="-1" />

<meta http-equiv="imagetoolbar" content="no" />

<meta name="robots" content="noindex,nofollow,noarchive" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />

<meta name="author" content="Jose Antonio Chavarría" />

<meta name="copyright" content="2002-2006 Jose Antonio Chavarría" />

<meta name="keywords" content="OpenClinic, open source, gpl, healthcare, php, mysql, coresis" />

<meta name="description" content="OpenClinic is an easy to use, open source, medical records system written in PHP" />
