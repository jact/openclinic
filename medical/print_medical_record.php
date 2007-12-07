<?php
/**
 * print_medical_record.php
 *
 * Medical record of a patient screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: print_medical_record.php,v 1.33 2007/12/07 16:51:45 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "print";

  /**
   * Checking permissions
   */
  require_once("../auth/login_check.php");
  loginCheck(OPEN_PROFILE_ADMINISTRATIVE);

  require_once("../model/Query/Page/Patient.php");
  require_once("../model/Query/Staff.php");
  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Query/History.php");
  require_once("../lib/Msg.php");

  $style = HTML::strStart('link',
    array(
      'rel' => 'stylesheet',
      'type' => 'text/css',
      'href' => '../css/style.css'
    ),
    true
  );

  /**
   * Checking for get vars. Close window if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["id_patient"]))
  {
    include_once("../layout/xhtml_start.php");
    echo $style;
    HTML::end('head');
    HTML::start('body');

    Msg::error(_("No patient selected."));
    HTML::para(HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));

    HTML::end('body');
    HTML::end('html');
    exit();
  }

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["id_patient"]);

  /**
   * Search database for patient
   */
  $patQ = new Query_Page_Patient();
  if ( !$patQ->select($idPatient) )
  {
    $patQ->close();
    include_once("../layout/xhtml_start.php");
    echo $style;
    HTML::end('head');
    HTML::start('body');

    Msg::error(_("That patient does not exist."));
    HTML::para(HTML::strLink(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));

    HTML::end('body');
    HTML::end('html');
    exit();
  }

  $pat = $patQ->fetch();
  if ( !$pat )
  {
    $patQ->close();
    Error::fetch($patQ);
  }
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

  /**
   * Show medical record
   */
  $title = $patName . " " . date(_("Y-m-d H:i:s"));
  require_once("../layout/xhtml_start.php");
  echo $style;
  HTML::end('head');
  HTML::start('body', array('id' => 'medicalRecord'));

  /**
   * Show social data
   */
  HTML::section(2, _("Social Data"));
  HTML::section(3, _("Patient"));
  HTML::para($pat->getSurname1() . ' ' . $pat->getSurname2() . ', ' . $pat->getFirstName());

  if ($pat->getNIF())
  {
    HTML::section(3, _("Tax Identification Number (TIN)"));
    HTML::para($pat->getNIF());
  }

  if ($pat->getAddress())
  {
    HTML::section(3, _("Address"));
    HTML::para(nl2br($pat->getAddress()));
  }

  if ($pat->getPhone())
  {
    HTML::section(3, _("Phone Contact"));
    HTML::para(nl2br($pat->getPhone()));
  }

  HTML::section(3, _("Sex"));
  HTML::para((($pat->getSex() == 'V') ? _("Male") : _("Female")));

  if ($pat->getRace())
  {
    HTML::section(3, _("Race"));
    HTML::para($pat->getRace());
  }

  if ($pat->getBirthDate() != "" && $pat->getBirthDate() != "0000-00-00")
  {
    HTML::section(3, _("Race"));
    HTML::para(I18n::localDate($pat->getBirthDate()));

    HTML::section(3, _("Age"));
    HTML::para($pat->getAge());
  }

  if ($pat->getBirthPlace())
  {
    HTML::section(3, _("Birth Place"));
    HTML::para($pat->getBirthPlace());
  }

  if ($pat->getDeceaseDate() != "" && $pat->getDeceaseDate() != "0000-00-00")
  {
    HTML::section(3, _("Decease Date"));
    HTML::para(I18n::localDate($pat->getDeceaseDate()));
  }

  if ($pat->getNTS())
  {
    HTML::section(3, _("Sanitary Card Number (SCN)"));
    HTML::para($pat->getNTS());
  }

  if ($pat->getNSS())
  {
    HTML::section(3, _("National Health Service Number (NHSN)"));
    HTML::para($pat->getNSS());
  }

  if ($pat->getFamilySituation())
  {
    HTML::section(3, _("Family Situation"));
    HTML::para(nl2br($pat->getFamilySituation()));
  }

  if ($pat->getLabourSituation())
  {
    HTML::section(3, _("Labour Situation"));
    HTML::para(nl2br($pat->getLabourSituation()));
  }

  if ($pat->getEducation())
  {
    HTML::section(3, _("Education"));
    HTML::para(nl2br($pat->getEducation()));
  }

  if ($pat->getInsuranceCompany())
  {
    HTML::section(3, _("Insurance Company"));
    HTML::para($pat->getInsuranceCompany());
  }

  if ($pat->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($pat->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        HTML::section(3, _("Doctor you are assigned to"));
        HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  unset($pat);

  HTML::rule();

  /**
   * Show medical problems
   */
  HTML::section(2, _("Medical Problems List:"));

  $problemQ = new Query_Page_Problem();
  if ( !$problemQ->selectProblems($idPatient) )
  {
    Msg::info(_("No medical problems defined for this patient."));
  }

  while ($problem = $problemQ->fetch())
  {
    HTML::section(3, _("Order Number"));
    HTML::para($problem->getOrderNumber());

    if ($problem->getIdMember())
    {
      $staffQ = new Query_Staff();
      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          HTML::section(3, _("Attending Physician"));
          HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    HTML::section(3, _("Opening Date"));
    HTML::para(I18n::localDate($problem->getOpeningDate()));

    HTML::section(3, _("Last Update Date"));
    HTML::para(I18n::localDate($problem->getLastUpdateDate()));

    if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
    {
      HTML::section(3, _("Closing Date"));
      HTML::para(I18n::localDate($problem->getClosingDate()));
    }

    if ($problem->getMeetingPlace())
    {
      HTML::section(3, _("Meeting Place"));
      HTML::para($problem->getMeetingPlace());
    }

    HTML::section(3, _("Wording"));
    HTML::para(nl2br($problem->getWording()));

    if ($problem->getSubjective())
    {
      HTML::section(3, _("Subjective"));
      HTML::para(nl2br($problem->getSubjective()));
    }

    if ($problem->getObjective())
    {
      HTML::section(3, _("Objective"));
      HTML::para(nl2br($problem->getObjective()));
    }

    if ($problem->getAppreciation())
    {
      HTML::section(3, _("Appreciation"));
      HTML::para(nl2br($problem->getAppreciation()));
    }

    if ($problem->getActionPlan())
    {
      HTML::section(3, _("Action Plan"));
      HTML::para(nl2br($problem->getActionPlan()));
    }

    if ($problem->getPrescription())
    {
      HTML::section(3, _("Prescription"));
      HTML::para(nl2br($problem->getPrescription()));
    }

    HTML::rule();
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Show personal antecedents
   */
  $historyQ = new Query_History();
  $historyQ->selectPersonal($idPatient);

  $history = $historyQ->fetch();
  if ( !$history )
  {
    Error::fetch($historyQ);
  }

  HTML::section(2, _("Personal Antecedents"));

  if ($history->getBirthGrowth())
  {
    HTML::section(3, _("Birth and Growth"));
    HTML::para(nl2br($history->getBirthGrowth()));
  }

  if ($history->getGrowthSexuality())
  {
    HTML::section(3, _("Growth and Sexuality"));
    HTML::para(nl2br($history->getGrowthSexuality()));
  }

  if ($history->getFeed())
  {
    HTML::section(3, _("Feed"));
    HTML::para(nl2br($history->getFeed()));
  }

  if ($history->getHabits())
  {
    HTML::section(3, _("Habits"));
    HTML::para(nl2br($history->getHabits()));
  }

  if ($history->getPeristalticConditions())
  {
    HTML::section(3, _("Peristaltic Conditions"));
    HTML::para(nl2br($history->getPeristalticConditions()));
  }

  if ($history->getPsychological())
  {
    HTML::section(3, _("Psychological Conditions"));
    HTML::para(nl2br($history->getPsychological()));
  }

  if ($history->getChildrenComplaint())
  {
    HTML::section(3, _("Children Complaint"));
    HTML::para(nl2br($history->getChildrenComplaint()));
  }

  if ($history->getVenerealDisease())
  {
    HTML::section(3, _("Venereal Disease"));
    HTML::para(nl2br($history->getVenerealDisease()));
  }

  if ($history->getAccidentSurgicalOperation())
  {
    HTML::section(3, _("Accidents and Surgical Operations"));
    HTML::para(nl2br($history->getAccidentSurgicalOperation()));
  }

  if ($history->getMedicinalIntolerance())
  {
    HTML::section(3, _("Medicinal Intolerance"));
    HTML::para(nl2br($history->getMedicinalIntolerance()));
  }

  if ($history->getMentalIllness())
  {
    HTML::section(3, _("Mental Illness"));
    HTML::para(nl2br($history->getMentalIllness()));
  }

  HTML::rule();

  /**
   * Show family antecedents
   */
  $historyQ->selectFamily($idPatient);

  $history = $historyQ->fetch();
  if ( !$history )
  {
    Error::fetch($historyQ);
  }
  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);

  HTML::section(2, _("Family Antecedents"));

  if ($history->getParentsStatusHealth())
  {
    HTML::section(3, _("Parents Status Health"));
    HTML::para(nl2br($history->getParentsStatusHealth()));
  }

  if ($history->getBrothersStatusHealth())
  {
    HTML::section(3, _("Brothers and Sisters Status Health"));
    HTML::para(nl2br($history->getBrothersStatusHealth()));
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    HTML::section(3, _("Spouse and Childs Status Health"));
    HTML::para(nl2br($history->getSpouseChildsStatusHealth()));
  }

  if ($history->getFamilyIllness())
  {
    HTML::section(3, _("Family Illness"));
    HTML::para(nl2br($history->getFamilyIllness()));
  }

  HTML::rule();

  /**
   * Show closed medical problems
   */
  HTML::section(2, _("Closed Medical Problems List:"));

  $problemQ = new Query_Page_Problem();
  if ( !$problemQ->selectProblems($idPatient, true) )
  {
    Msg::info(_("No closed medical problems defined for this patient."));
    HTML::rule();
  }

  while ($problem = $problemQ->fetch())
  {
    HTML::section(3, _("Order Number"));
    HTML::para($problem->getOrderNumber());

    if ($problem->getIdMember())
    {
      $staffQ = new Query_Staff();
      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          HTML::section(3, _("Attending Physician"));
          HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    HTML::section(3, _("Opening Date"));
    HTML::para(I18n::localDate($problem->getOpeningDate()));

    HTML::section(3, _("Last Update Date"));
    HTML::para(I18n::localDate($problem->getLastUpdateDate()));

    if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
    {
      HTML::section(3, _("Closing Date"));
      HTML::para(I18n::localDate($problem->getClosingDate()));
    }

    if ($problem->getMeetingPlace())
    {
      HTML::section(3, _("Meeting Place"));
      HTML::para($problem->getMeetingPlace());
    }

    HTML::section(3, _("Wording"));
    HTML::para(nl2br($problem->getWording()));

    if ($problem->getSubjective())
    {
      HTML::section(3, _("Subjective"));
      HTML::para(nl2br($problem->getSubjective()));
    }

    if ($problem->getObjective())
    {
      HTML::section(3, _("Objective"));
      HTML::para(nl2br($problem->getObjective()));
    }

    if ($problem->getAppreciation())
    {
      HTML::section(3, _("Appreciation"));
      HTML::para(nl2br($problem->getAppreciation()));
    }

    if ($problem->getActionPlan())
    {
      HTML::section(3, _("Action Plan"));
      HTML::para(nl2br($problem->getActionPlan()));
    }

    if ($problem->getPrescription())
    {
      HTML::section(3, _("Prescription"));
      HTML::para(nl2br($problem->getPrescription()));
    }

    HTML::rule();
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Do print the page
   */
  HTML::start('script', array('type' => 'text/javascript'));
  echo "\n<!--/*--><![CDATA[/*<!--*/\n";
  echo 'if (typeof(window.print) != "undefined")' . "\n";
  echo "{\n";
  echo '  window.print();' . "\n";
  echo "}\n";
  echo "/*]]>*///-->\n";
  HTML::end('script');

  /**
   * Show footer page
   */
  HTML::end('body');
  HTML::end('html');
?>
