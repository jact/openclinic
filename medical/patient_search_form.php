<?php
/**
 * patient_search_form.php
 *
 * Search patient screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: patient_search_form.php,v 1.20 2007/12/15 15:05:01 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "searchform";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../lib/Form.php");

  /**
   * Show page
   */
  $title = _("Search Patient");
  $focusFormField = "search_text"; // to avoid JavaScript mistakes in demo version
  require_once("../layout/header.php");

  $headerWording2 = _("Search Patient by Medical Problem");
  $returnLocation = "../medical/index.php";

  /**
   * Bread crumb
   */
  $links = array(
    _("Medical Records") => $returnLocation,
    $title => ""
  );
  HTML::breadCrumb($links, "icon icon_search");
  unset($links);

  $tokenForm = Form::generateToken(); // for 2 forms (patient, problem)

  /**
   * Patient search form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/patient_search.php'));
  require_once("../medical/patient_search_fields.php");
  HTML::end('form');

  HTML::rule();

  /**
   * Problem search form
   */
  HTML::start('form', array('method' => 'post', 'action' => '../medical/problem_search.php'));
  require_once("../medical/problem_search_fields.php");
  HTML::end('form');

  Msg::hint('* ' . _("Note: Empty search to see all results."));

  require_once("../layout/footer.php");
?>
