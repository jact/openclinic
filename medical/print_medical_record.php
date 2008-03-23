<?php
/**
 * print_medical_record.php
 *
 * Medical record of a patient screen
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2008 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: print_medical_record.php,v 1.35 2008/03/23 12:00:17 jact Exp $
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
  loginCheck(OPEN_PROFILE_DOCTOR);

  require_once("../model/Query/Page/Patient.php");
  require_once("../model/Query/Staff.php");
  require_once("../model/Query/Page/Problem.php");
  require_once("../model/Query/History.php");
  require_once("../lib/Msg.php");

  $style = HTML::start('link',
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
    echo HTML::end('head');
    echo HTML::start('body');

    echo Msg::error(_("No patient selected."));
    echo HTML::para(HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));

    echo HTML::end('body');
    echo HTML::end('html');
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
    echo HTML::end('head');
    echo HTML::start('body');

    echo Msg::error(_("That patient does not exist."));
    echo HTML::para(HTML::link(_("Close Window"), '#', null, array('onclick' => 'window.close(); return false;')));

    echo HTML::end('body');
    echo HTML::end('html');
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
  echo HTML::end('head');
  echo HTML::start('body', array('id' => 'medicalRecord'));

  /**
   * Show social data
   */
  echo HTML::section(2, _("Social Data"));
  echo HTML::section(3, _("Patient"));
  echo HTML::para($pat->getSurname1() . ' ' . $pat->getSurname2() . ', ' . $pat->getFirstName());

  if ($pat->getNIF())
  {
    echo HTML::section(3, _("Tax Identification Number (TIN)"));
    echo HTML::para($pat->getNIF());
  }

  if ($pat->getAddress())
  {
    echo HTML::section(3, _("Address"));
    echo HTML::para(nl2br($pat->getAddress()));
  }

  if ($pat->getPhone())
  {
    echo HTML::section(3, _("Phone Contact"));
    echo HTML::para(nl2br($pat->getPhone()));
  }

  echo HTML::section(3, _("Sex"));
  echo HTML::para((($pat->getSex() == 'V') ? _("Male") : _("Female")));

  if ($pat->getRace())
  {
    echo HTML::section(3, _("Race"));
    echo HTML::para($pat->getRace());
  }

  if ($pat->getBirthDate() != "" && $pat->getBirthDate() != "0000-00-00")
  {
    echo HTML::section(3, _("Race"));
    echo HTML::para(I18n::localDate($pat->getBirthDate()));

    echo HTML::section(3, _("Age"));
    echo HTML::para($pat->getAge());
  }

  if ($pat->getBirthPlace())
  {
    echo HTML::section(3, _("Birth Place"));
    echo HTML::para($pat->getBirthPlace());
  }

  if ($pat->getDeceaseDate() != "" && $pat->getDeceaseDate() != "0000-00-00")
  {
    echo HTML::section(3, _("Decease Date"));
    echo HTML::para(I18n::localDate($pat->getDeceaseDate()));
  }

  if ($pat->getNTS())
  {
    echo HTML::section(3, _("Sanitary Card Number (SCN)"));
    echo HTML::para($pat->getNTS());
  }

  if ($pat->getNSS())
  {
    echo HTML::section(3, _("National Health Service Number (NHSN)"));
    echo HTML::para($pat->getNSS());
  }

  if ($pat->getFamilySituation())
  {
    echo HTML::section(3, _("Family Situation"));
    echo HTML::para(nl2br($pat->getFamilySituation()));
  }

  if ($pat->getLabourSituation())
  {
    echo HTML::section(3, _("Labour Situation"));
    echo HTML::para(nl2br($pat->getLabourSituation()));
  }

  if ($pat->getEducation())
  {
    echo HTML::section(3, _("Education"));
    echo HTML::para(nl2br($pat->getEducation()));
  }

  if ($pat->getInsuranceCompany())
  {
    echo HTML::section(3, _("Insurance Company"));
    echo HTML::para($pat->getInsuranceCompany());
  }

  if ($pat->getIdMember())
  {
    $staffQ = new Query_Staff();
    if ($staffQ->select($pat->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo HTML::section(3, _("Doctor you are assigned to"));
        echo HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  unset($pat);

  echo HTML::rule();

  /**
   * Show medical problems
   */
  echo HTML::section(2, _("Medical Problems List:"));

  $problemQ = new Query_Page_Problem();
  if ( !$problemQ->selectProblems($idPatient) )
  {
    echo Msg::info(_("No medical problems defined for this patient."));
  }

  while ($problem = $problemQ->fetch())
  {
    echo HTML::section(3, _("Order Number"));
    echo HTML::para($problem->getOrderNumber());

    if ($problem->getIdMember())
    {
      $staffQ = new Query_Staff();
      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          echo HTML::section(3, _("Attending Physician"));
          echo HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    echo HTML::section(3, _("Opening Date"));
    echo HTML::para(I18n::localDate($problem->getOpeningDate()));

    echo HTML::section(3, _("Last Update Date"));
    echo HTML::para(I18n::localDate($problem->getLastUpdateDate()));

    if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
    {
      echo HTML::section(3, _("Closing Date"));
      echo HTML::para(I18n::localDate($problem->getClosingDate()));
    }

    if ($problem->getMeetingPlace())
    {
      echo HTML::section(3, _("Meeting Place"));
      echo HTML::para($problem->getMeetingPlace());
    }

    echo HTML::section(3, _("Wording"));
    echo HTML::para(nl2br($problem->getWording()));

    if ($problem->getSubjective())
    {
      echo HTML::section(3, _("Subjective"));
      echo HTML::para(nl2br($problem->getSubjective()));
    }

    if ($problem->getObjective())
    {
      echo HTML::section(3, _("Objective"));
      echo HTML::para(nl2br($problem->getObjective()));
    }

    if ($problem->getAppreciation())
    {
      echo HTML::section(3, _("Appreciation"));
      echo HTML::para(nl2br($problem->getAppreciation()));
    }

    if ($problem->getActionPlan())
    {
      echo HTML::section(3, _("Action Plan"));
      echo HTML::para(nl2br($problem->getActionPlan()));
    }

    if ($problem->getPrescription())
    {
      echo HTML::section(3, _("Prescription"));
      echo HTML::para(nl2br($problem->getPrescription()));
    }

    echo HTML::rule();
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

  echo HTML::section(2, _("Personal Antecedents"));

  if ($history->getBirthGrowth())
  {
    echo HTML::section(3, _("Birth and Growth"));
    echo HTML::para(nl2br($history->getBirthGrowth()));
  }

  if ($history->getGrowthSexuality())
  {
    echo HTML::section(3, _("Growth and Sexuality"));
    echo HTML::para(nl2br($history->getGrowthSexuality()));
  }

  if ($history->getFeed())
  {
    echo HTML::section(3, _("Feed"));
    echo HTML::para(nl2br($history->getFeed()));
  }

  if ($history->getHabits())
  {
    echo HTML::section(3, _("Habits"));
    echo HTML::para(nl2br($history->getHabits()));
  }

  if ($history->getPeristalticConditions())
  {
    echo HTML::section(3, _("Peristaltic Conditions"));
    echo HTML::para(nl2br($history->getPeristalticConditions()));
  }

  if ($history->getPsychological())
  {
    echo HTML::section(3, _("Psychological Conditions"));
    echo HTML::para(nl2br($history->getPsychological()));
  }

  if ($history->getChildrenComplaint())
  {
    echo HTML::section(3, _("Children Complaint"));
    echo HTML::para(nl2br($history->getChildrenComplaint()));
  }

  if ($history->getVenerealDisease())
  {
    echo HTML::section(3, _("Venereal Disease"));
    echo HTML::para(nl2br($history->getVenerealDisease()));
  }

  if ($history->getAccidentSurgicalOperation())
  {
    echo HTML::section(3, _("Accidents and Surgical Operations"));
    echo HTML::para(nl2br($history->getAccidentSurgicalOperation()));
  }

  if ($history->getMedicinalIntolerance())
  {
    echo HTML::section(3, _("Medicinal Intolerance"));
    echo HTML::para(nl2br($history->getMedicinalIntolerance()));
  }

  if ($history->getMentalIllness())
  {
    echo HTML::section(3, _("Mental Illness"));
    echo HTML::para(nl2br($history->getMentalIllness()));
  }

  echo HTML::rule();

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

  echo HTML::section(2, _("Family Antecedents"));

  if ($history->getParentsStatusHealth())
  {
    echo HTML::section(3, _("Parents Status Health"));
    echo HTML::para(nl2br($history->getParentsStatusHealth()));
  }

  if ($history->getBrothersStatusHealth())
  {
    echo HTML::section(3, _("Brothers and Sisters Status Health"));
    echo HTML::para(nl2br($history->getBrothersStatusHealth()));
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    echo HTML::section(3, _("Spouse and Childs Status Health"));
    echo HTML::para(nl2br($history->getSpouseChildsStatusHealth()));
  }

  if ($history->getFamilyIllness())
  {
    echo HTML::section(3, _("Family Illness"));
    echo HTML::para(nl2br($history->getFamilyIllness()));
  }

  echo HTML::rule();

  /**
   * Show closed medical problems
   */
  echo HTML::section(2, _("Closed Medical Problems List:"));

  $problemQ = new Query_Page_Problem();
  if ( !$problemQ->selectProblems($idPatient, true) )
  {
    echo Msg::info(_("No closed medical problems defined for this patient."));
    echo HTML::rule();
  }

  while ($problem = $problemQ->fetch())
  {
    echo HTML::section(3, _("Order Number"));
    echo HTML::para($problem->getOrderNumber());

    if ($problem->getIdMember())
    {
      $staffQ = new Query_Staff();
      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          echo HTML::section(3, _("Attending Physician"));
          echo HTML::para($staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName());
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    echo HTML::section(3, _("Opening Date"));
    echo HTML::para(I18n::localDate($problem->getOpeningDate()));

    echo HTML::section(3, _("Last Update Date"));
    echo HTML::para(I18n::localDate($problem->getLastUpdateDate()));

    if ($problem->getClosingDate() != "" && $problem->getClosingDate() != "0000-00-00")
    {
      echo HTML::section(3, _("Closing Date"));
      echo HTML::para(I18n::localDate($problem->getClosingDate()));
    }

    if ($problem->getMeetingPlace())
    {
      echo HTML::section(3, _("Meeting Place"));
      echo HTML::para($problem->getMeetingPlace());
    }

    echo HTML::section(3, _("Wording"));
    echo HTML::para(nl2br($problem->getWording()));

    if ($problem->getSubjective())
    {
      echo HTML::section(3, _("Subjective"));
      echo HTML::para(nl2br($problem->getSubjective()));
    }

    if ($problem->getObjective())
    {
      echo HTML::section(3, _("Objective"));
      echo HTML::para(nl2br($problem->getObjective()));
    }

    if ($problem->getAppreciation())
    {
      echo HTML::section(3, _("Appreciation"));
      echo HTML::para(nl2br($problem->getAppreciation()));
    }

    if ($problem->getActionPlan())
    {
      echo HTML::section(3, _("Action Plan"));
      echo HTML::para(nl2br($problem->getActionPlan()));
    }

    if ($problem->getPrescription())
    {
      echo HTML::section(3, _("Prescription"));
      echo HTML::para(nl2br($problem->getPrescription()));
    }

    echo HTML::rule();
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Do print the page
   */
  echo HTML::start('script', array('type' => 'text/javascript'));
  echo "\n<!--/*--><![CDATA[/*<!--*/\n";
  echo 'if (typeof(window.print) != "undefined")' . "\n";
  echo "{\n";
  echo '  window.print();' . "\n";
  echo "}\n";
  echo "/*]]>*///-->\n";
  echo HTML::end('script');

  /**
   * Show footer page
   */
  echo HTML::end('body');
  echo HTML::end('html');
?>
