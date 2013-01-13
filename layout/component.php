<?php
/**
 * component.php
 *
 * Contains functions to return layout components of the web pages
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: component.php,v 1.11 2013/01/13 16:28:39 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.8
 */

  require_once(dirname(__FILE__) . "/../lib/exe_protect.php");
  executionProtection(__FILE__);

  require_once(dirname(__FILE__) . "/../lib/HTML.php");

/**
 * Functions:
 *  string appLogo(void)
 *  string menuBar(string $tab)
 *  string logos(void)
 *  string miniLogos(void)
 *  array patientLinks(int $idPatient, string $nav)
 *  string sfLinks(void)
 *  string shortcuts(string $tab = null, string $nav = null)
 *  string navigation(array $links)
 *  string clinicInfo(void)
 */

  /**
   * string appLogo(void)
   *
   * Returns a paragraph with the application's logo
   *
   * @return string p#logo
   * @access public
   */
  function appLogo()
  {
    $logo = '../img/' . 'openclinic-1.png'; // @fixme OPEN_APP_LOGO
    list($width, $height, $type, $attr) = getimagesize($logo);
    $logo = HTML::image($logo, 'OpenClinic' /* @fixme OPEN_APP_NAME */, array('width' => $width, 'height' => $height));
    $logo = HTML::link($logo, '../index.php', null, array('accesskey' => 1));

    $html = HTML::para($logo, array('id' => 'logo'));

    return $html;
  }

  /**
   * string menuBar(string $tab)
   *
   * Returns a layer called tabs with a section menu
   *
   * @param string $tab selected section
   * @param array $section options list
   * @return string layer with a section menu
   * @access public
   */
  function menuBar($tab)
  {
    $_links = array(
      "home" => array(_("Home"), "../home/index.php"),
      "medical" => array(_("Medical Records"), "../medical/index.php"),
      //"stats" => array("Statistics", "../stats/index.php"),
      "admin" => array(_("Admin"), "../admin/index.php")
    );
    /*if ( !isset($_SESSION['auth']['invalid_token'])
      && isset($_SESSION['auth']['is_admin']) && $_SESSION['auth']['is_admin'])
    {
      $_links["admin"] = array(_("Admin"), "../admin/index.php");
    }*/

    $_html = HTML::start('div', array('id' => 'tabs'));

    $_array = null;
    $_sentinel = true;
    foreach ($_links as $_key => $_value)
    {
      $_options = null;
      if ($_sentinel)
      {
        $_sentinel = false;
        $_options = array('class' => 'first');
      }
      if ($tab == $_key)
      {
        $_options['class'] = (isset($_options['class'])) ? $_options['class'] . ' selected' : 'selected';
      }

      $_array[] = HTML::link($_value[0], $_value[1], null, $_options);
    }
    $_html .= HTML::itemList($_array);
    $_html .= HTML::end('div'); // #tabs

    return $_html;
  }

  /**
   * string logos(void)
   *
   * Returns ul#logos with links
   *
   * @return string ul#logos
   * @access public
   */
  function logos()
  {
    $_links = null;

    $_links[] = HTML::link(
      HTML::image(
        '../img/openclinic-2.png',
        _("Powered by OpenClinic"),
        array('width' => 130, 'height' => 29)
      ),
      'http://openclinic.sourceforge.net'
    );

    $thankCoresis = HTML::image('../img/thank.png', 'OpenClinic Logo thanks to Coresis',
      array('width' => 65, 'height' => 30)
    );
    $thankCoresis .= HTML::image('../img/coresis.png', 'OpenClinic Logo thanks to Coresis',
      array('width' => 65, 'height' => 30)
    );
    $thankCoresis = str_replace(PHP_EOL, '', $thankCoresis);
    $_links[] = HTML::link($thankCoresis, 'http://www.coresis.com');
    unset($thankCoresis);

    $_links[] = HTML::link(
      HTML::image(
        '../img/sf-logo.png',
        'Project hosted in SourceForge.net',
        array('width' => 130, 'height' => 37)
      ),
      'http://sourceforge.net'
    );

    return HTML::itemList($_links, array('id' => 'logos'));
  }

  /**
   * string miniLogos(void)
   *
   * @return string ul#mini_logos
   * @access public
   */
  function miniLogos()
  {
    $_links = null;

    $_links[] = HTML::link(
      HTML::image(
        '../img/php-logo.gif',
        'Powered by PHP',
        array('width' => 80, 'height' => 15)
      ),
      'http://www.php.net'
    );

    $_links[] = HTML::link(
      HTML::image(
        '../img/mysql-logo.png',
        'Works with MySQL',
        array('width' => 80, 'height' => 15)
      ),
      'http://www.mysql.com'
    );

    $_links[] = HTML::link(
      HTML::image(
        '../img/valid-xhtml11.png',
        'Valid XHTML 1.1',
        array('width' => 80, 'height' => 15)
      ),
      'http://validator.w3.org/check/referer'
    );

    $_links[] = HTML::link(
      HTML::image(
        '../img/valid-css.png',
        'Valid CSS',
        array('width' => 80, 'height' => 15)
      ),
      'http://jigsaw.w3.org/css-validator',
      array('uri' => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'])
    );

    return HTML::itemList($_links, array('id' => 'mini_logos'));
  }

  /**
   * array patientLinks(int $idPatient, string $nav)
   *
   * Returns an array with links about a patient
   *
   * @param int $idPatient
   * @param string $nav
   * @return array
   * @access public
   * @since 0.8
   */
  function patientLinks($idPatient, $nav)
  {
    $linkList = array(
      "relatives" => array(_("View Relatives"), "../medical/relative_list.php"),
      //"preventive" => array(_("Datos Preventivos"), ""), // I don't know how implement it
      "history" => array(_("Clinic History"), "../medical/history_list.php"),
      "problems" => array(_("Medical Problems Report"), "../medical/problem_list.php")
    );

    $array = null;
    foreach ($linkList as $key => $value)
    {
      $array[] = HTML::link($value[0], $value[1], array('id_patient' => $idPatient),
        $nav == $key ? array('class' => 'selected') : null
      );
    }
    unset($linkList);

    $array[] = ($nav == "print")
      ? array(_("Print Medical Record"), array('class' => 'selected'))
      : HTML::link(_("Print Medical Record"), '../medical/print_medical_record.php',
          array('id_patient' => $idPatient),
          array('class' => 'popup')
        );

    return $array;
  }

  /**
   * string sfLinks(void)
   *
   * Returns a layer called sf_links with a links list
   *
   * @return string layer with a links list
   * @access public
   */
  function sfLinks()
  {
    $_sfLinks = array(
      _("Project Page") => 'http://sourceforge.net/projects/openclinic/',
      //_("Mailing Lists") => 'http://sourceforge.net/mail/?group_id=70742',
      _("Downloads") => 'http://sourceforge.net/project/showfiles.php?group_id=70742',
      _("Report Bugs") => 'http://sourceforge.net/tracker/?group_id=70742&atid=528857',
      //_("Tasks") => 'http://sourceforge.net/pm/?group_id=70742',
      _("Forums") => 'http://sourceforge.net/forum/?group_id=70742',
      //_("Developers"), 'http://sourceforge.net/project/memberlist.php?group_id=70742'
    );

    $_array = null;
    foreach ($_sfLinks as $_key => $_value)
    {
      $_array[] = HTML::link($_key, $_value);
    }
    $_html = HTML::itemList($_array, array('id' => 'sf_links'));

    return $_html;
  }

  /**
   * string shortcuts(string $tab = null, string $nav = null)
   *
   * Returns an ul#shortcuts with links
   *
   * @param string $tab (optional)
   * @param string $nav (optional)
   * @return string ul#shortcuts
   * @access public
   * @see OPEN_DEMO
   */
  function shortcuts($tab = null, $nav = null)
  {
    $_links = null;

    if (defined("OPEN_DEMO") && !OPEN_DEMO)
    {
      $sessLogin = isset($_SESSION['auth']['login_session']) ? $_SESSION['auth']['login_session'] : '';
      if ( !empty($sessLogin) && !isset($_SESSION['auth']['invalid_token']) )
      {
        $_links[] = HTML::link(_("Logout"), '../auth/logout.php')
          . ' ['
          . HTML::link($sessLogin, '../admin/user_edit_form.php',
            array(
              'id_user' => $_SESSION['auth']['user_id'],
              'all' => 'Y'
            ),
            array('title' => _("manage your user account"))
          )
          . ']';
      }
      else
      {
        $_links[] = HTML::link(_("Log in"), '../auth/login_form.php'); // @fixme login
      }
    }

    $_links[] = HTML::link(_("OpenClinic Readme"), '../index.html');

    /*if (isset($tab) && isset($nav))
    {
      $_links[] = HTML::link(_("Help"), '../doc/index.php',
        array(
          'tab' => $tab,
          'nav' => $nav
        ),
        array(
          'title' => _("Opens a new window"),
          'class' => 'popup'
        )
      );
    }*/


    if (isset($_SESSION['auth']['is_admin']) && ($_SESSION['auth']['is_admin'] === true && !OPEN_DEMO))
    {
      $_serverVar = (strpos(PHP_SAPI, 'cgi') !== false)
        ? $_SERVER['PATH_TRANSLATED']
        : $_SERVER['SCRIPT_FILENAME'];
      $_links[] = HTML::link(_("View source code"), '../shared/view_source.php',
        array(
          'file' => $_serverVar
        ),
        array(
          'title' => _("Opens a new window"),
          'class' => 'popup'
        )
      );
    }

    if (defined("OPEN_DEMO") && OPEN_DEMO)
    {
      $_links[] = HTML::link(_("Demo version features"), '../demo_version.html');
    }

    $_html = HTML::itemList($_links, array('id' => 'shortcuts'));

    return $_html;
  }

  /**
   * string navigation(array $links)
   *
   * @param array $links
   *  array(
   *    string,
   *    array(
   *      string,
   *      string,
   *    ),
   *    string
   *  )
   * @return string list of links
   * @access public
   */
  function navigation($links)
  {
    $_html = HTML::start('ul');
    foreach ($links as $value)
    {
      if (is_array($value))
      {
        $_html .= navigation($value);
      }
      else
      {
        $_html .= HTML::start('li') . $value;
      }
      if ( !is_array(next($links)) )
      {
        $_html .= HTML::end('li');
      }
    }
    $_html .= HTML::end('ul');

    return $_html;
  }

  /**
   * string clinicInfo(void)
   *
   * @return string div#clinic_info (microformat hCard)
   * @access public
   * @see OPEN_CLINIC_NAME
   * @see OPEN_CLINIC_URL
   * @see OPEN_CLINIC_HOURS
   * @see OPEN_CLINIC_ADDRESS
   * @see OPEN_CLINIC_PHONE
   */
  function clinicInfo()
  {
    if ( !defined("OPEN_CLINIC_NAME") || !OPEN_CLINIC_NAME )
    {
      return;
    }

    $_html = HTML::start('div', array('id' => 'clinic_info', 'class' => 'vcard contact'));

    $_name = OPEN_CLINIC_NAME;
    if (defined("OPEN_CLINIC_URL") && OPEN_CLINIC_URL)
    {
      $_name = HTML::link($_name, OPEN_CLINIC_URL, null, array('class' => 'url'));
    }

    $_html .= HTML::para($_name, array('class' => 'fn org'));

    if (defined("OPEN_CLINIC_HOURS") && OPEN_CLINIC_HOURS)
    {
      $_html .= HTML::para(sprintf(_("Clinic hours: %s"), OPEN_CLINIC_HOURS));
    }

    if ((defined("OPEN_CLINIC_ADDRESS") && OPEN_CLINIC_ADDRESS)
      || (defined("OPEN_CLINIC_PHONE") && OPEN_CLINIC_PHONE))
    {
      $_html .= HTML::start('address', array('class' => 'adr'));

      if (defined("OPEN_CLINIC_ADDRESS") && OPEN_CLINIC_ADDRESS)
      {
        $_html .= HTML::para(sprintf(_("Clinic address: %s"), HTML::tag('span', OPEN_CLINIC_ADDRESS,
          array('class' => 'street-address')))
        );
      }

      if (defined("OPEN_CLINIC_PHONE") && OPEN_CLINIC_PHONE)
      {
        $_html .= HTML::para(sprintf(_("Clinic phone: %s"),
          HTML::tag('span', OPEN_CLINIC_PHONE, array('class' => 'tel value')))
        );
      }

      $_html .= HTML::end('address');
    }

    $_html .= HTML::end('div');

    return $_html;
  }
?>
