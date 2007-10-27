<?php
/**
 * component.php
 *
 * Contains functions to return layout components of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: component.php,v 1.3 2007/10/27 16:25:05 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 */

  if (str_replace("\\", "/", __FILE__) == str_replace("\\", "/", $_SERVER['SCRIPT_FILENAME']))
  {
    header("Location: ../index.php");
    exit();
  }

  require_once("../lib/HTML.php");

/**
 * Functions:
 *  string menuBar(string $tab, array $section)
 *  string authInfo(void)
 *  string logoInfo(void)
 *  string patientLinks(int $idPatient, string $nav)
 */

  /**
   * string menuBar(string $tab, array $section)
   *
   * Returns a layer called menuBar with a section menu
   *
   * @param string $tab selected section
   * @param array $section options list
   * @return string layer with a section menu
   * @access public
   */
  function menuBar($tab, $section)
  {
    $html = '';
    if ( !is_array($section) )
    {
      return;
    }

    $html = HTML::strStart('div', array('class' => 'menuBar'));

    $array = null;
    $sentinel = true;
    foreach ($section as $key => $value)
    {
      $options = null;
      if ($sentinel)
      {
        $sentinel = false;
        $options = array('id' => 'first');
      }

      $item = ($tab == $key)
        ? HTML::strTag('span', $value[0])
        : HTML::strLink($value[0], $value[1]);
      $array[] = (is_null($options) ? $item : array($item, $options));
    }
    $html .= HTML::strItemList($array, array('id' => 'tabs'));
    $html .= HTML::strEnd('div'); // .menuBar

    return $html;
  }

  /**
   * string authInfo(void)
   *
   * Returns a paragraph .sideBarLogin with auth links
   *
   * @return string p.sideBarLogin
   * @access public
   * @see OPEN_DEMO
   */
  function authInfo()
  {
    $html = '';
    if ( !defined("OPEN_DEMO") || OPEN_DEMO)
    {
      return;
    }

    $sessLogin = isset($_SESSION["loginSession"]) ? $_SESSION["loginSession"] : "";
    if ( !empty($sessLogin) && !isset($_SESSION["invalidToken"]) )
    {
      $sideBarLogin = HTML::strLink(
          HTML::strStart('img',
            array(
              'src' => '../img/logout.png',
              'width' => 96,
              'height' => 22,
              'alt' => _("logout"),
              'title' => _("logout")
            ),
            true
          ),
          '../auth/logout.php'
        )
        . '<br />'
        . '[ '
        . HTML::strLink($sessLogin, '../admin/user_edit_form.php',
          array(
            'key' => $_SESSION["userId"],
            'all' => 'Y'
          ),
          array('title' => _("manage your user account"))
        )
        . ' ]';
    }
    else
    {
      $sideBarLogin = HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/login.png',
            'width' => 96,
            'height' => 22,
            'alt' => _("login"),
            'title' => _("login")
          ),
          true
        ),
        '../auth/login_form.php',
        array('ret' => '../home/index.php')
      );
    }
    $html = HTML::strPara($sideBarLogin, array('class' => 'sideBarLogin'));
    unset($sideBarLogin);
    $html .= HTML::strRule();

    return $html;
  }

  /**
   * string logoInfo(void)
   *
   * Returns a layer called sideBarLogo with logo information
   *
   * @return string div#sideBarLogo
   * @access public
   */
  function logoInfo()
  {
    $html = HTML::strStart('div', array('id' => 'sideBarLogo'));

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/openclinic-2.png',
            'width' => 130,
            'height' => 29,
            'alt' => _("Powered by OpenClinic"),
            'title' => _("Powered by OpenClinic")
          ),
          true
        ),
        'http://openclinic.sourceforge.net'
      )
    );

    $thankCoresis = HTML::strStart('img',
      array(
        'src' => '../img/thank.png',
        'width' => 65,
        'height' => 30,
        'alt' => 'OpenClinic Logo thanks to Coresis',
        'title' => 'OpenClinic Logo thanks to Coresis'
      ),
      true
    );
    $thankCoresis .= HTML::strStart('img',
      array(
        'src' => '../img/coresis.png',
        'width' => 65,
        'height' => 30,
        'alt' => 'OpenClinic Logo thanks to Coresis',
        'title' => 'OpenClinic Logo thanks to Coresis'
      ),
      true
    );
    $thankCoresis = str_replace(PHP_EOL, '', $thankCoresis);
    $html .= HTML::strPara(HTML::strLink($thankCoresis, 'http://www.coresis.com'));
    unset($thankCoresis);

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/sf-logo.png',
            'width' => 130,
            'height' => 37,
            'alt' => "Project hosted in SourceForge.net",
            'title' => "Project hosted in SourceForge.net"
          ),
          true
        ),
        'http://sourceforge.net'
      )
    );

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/php-logo.gif',
            'width' => 80,
            'height' => 15,
            'alt' => "Powered by PHP",
            'title' => "Powered by PHP"
          ),
          true
        ),
        'http://www.php.net'
      )
    );

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/mysql-logo.png',
            'width' => 80,
            'height' => 15,
            'alt' => "Works with MySQL",
            'title' => "Works with MySQL"
          ),
          true
        ),
        'http://www.mysql.com'
      )
    );

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/valid-xhtml11.png',
            'width' => 80,
            'height' => 15,
            'alt' => "Valid XHTML 1.1",
            'title' => "Valid XHTML 1.1"
          ),
          true
        ),
        'http://validator.w3.org/check/referer'
      )
    );

    $html .= HTML::strPara(
      HTML::strLink(
        HTML::strStart('img',
          array(
            'src' => '../img/valid-css.png',
            'width' => 80,
            'height' => 15,
            'alt' => "Valid CSS",
            'title' => "Valid CSS"
          ),
          true
        ),
        'http://jigsaw.w3.org/css-validator',
        array('uri' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])
      )
    );

    $html .= HTML::strEnd('div'); // #sideBarLogo

    return $html;
  }

  /**
   * string patientLinks(int $idPatient, string $nav)
   *
   * Returns a list with links about a patient
   *
   * @param int $idPatient
   * @param string $nav
   * @return string
   * @access public
   * @since 0.8
   */
  function patientLinks($idPatient, $nav)
  {
    $linkList = array(
      "relatives" => array(_("View Relatives"), "../medical/relative_list.php?id_patient=" . $idPatient),
      //"preventive" => array(_("Datos Preventivos"), ""), // I don't know how implement it
      "history" => array(_("Clinic History"), "../medical/history_list.php?id_patient=" . $idPatient),
      "problems" => array(_("Medical Problems Report"), "../medical/problem_list.php?id_patient=" . $idPatient)
    );

    $array = null;
    foreach ($linkList as $key => $value)
    {
      if ($nav == $key)
      {
        $array[] = array($value[0], array('class' => 'selected'));
      }
      else
      {
        $array[] = HTML::strLink($value[0], $value[1]);
      }
    }
    unset($linkList);

    $array[] = ($nav == "print")
      ? array(_("Print Medical Record"), array('class' => 'selected'))
      : HTML::strLink(_("Print Medical Record"), '../medical/print_medical_record.php',
          array('id_patient' => $idPatient),
          array('class' => 'popup')
        );

    return HTML::strItemList($array, array('class' => 'subnavbar'));
  }
?>
