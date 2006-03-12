<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2006 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: print_medical_record.php,v 1.20 2006/03/12 18:49:30 jact Exp $
 */

/**
 * print_medical_record.php
 *
 * Medical record of a patient screen
 *
 * Author: jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $tab = "medical";
  $nav = "print";
  $onlyDoctor = true;
  $style = '<link rel="stylesheet" type="text/css" href="../css/style.css" />' . "\n";

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Page_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../classes/Problem_Page_Query.php");
  require_once("../classes/History_Query.php");
  require_once("../lib/HTML.php");

  /**
   * Checking for get vars. Close window if none found.
   */
  if (count($_GET) == 0 || !is_numeric($_GET["key"]))
  {
    include_once("../shared/xhtml_start.php");
    echo $style;
    echo "</head><body>\n";
    HTML::message(_("No patient selected."), OPEN_MSG_ERROR);
    echo '<p><a href="#" onclick="window.close(); return false;">' . _("Close Window") . "</a></p>\n";
    echo "</body></html>\n";
    exit();
  }

  /**
   * Retrieving get var
   */
  $idPatient = intval($_GET["key"]);

  /**
   * Search database for patient
   */
  $patQ = new Patient_Page_Query();
  $patQ->connect();

  if ( !$patQ->select($idPatient) )
  {
    $patQ->close();
    include_once("../shared/xhtml_start.php");
    echo $style;
    echo "</head><body>\n";
    HTML::message(_("That patient does not exist."), OPEN_MSG_ERROR);
    echo '<p><a href="#" onclick="window.close(); return false;">' . _("Close Window") . "</a></p>\n";
    echo "</body></html>\n";
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
  require_once("../shared/xhtml_start.php");
  echo $style;
  echo "</head><body id='medicalRecord'>\n";

  /**
   * Show social data
   */
  echo '<h2>' . _("Social Data") . "</h2>\n";
  echo '<h3>' . _("Patient") . "</h3>\n";
  echo '<p>' . $pat->getSurname1() . ' ' . $pat->getSurname2() . ', ' . $pat->getFirstName() . "</p>\n";

  if ($pat->getNIF())
  {
    echo '<h3>' . _("Tax Identification Number (TIN)") . "</h3>\n";
    echo '<p>' . $pat->getNIF() . "</p>\n";
  }

  if ($pat->getAddress())
  {
    echo '<h3>' . _("Address") . "</h3>\n";
    echo '<p>' . nl2br($pat->getAddress()) . "</p>\n";
  }

  if ($pat->getPhone())
  {
    echo '<h3>' . _("Phone Contact") . "</h3>\n";
    echo '<p>' . nl2br($pat->getPhone()) . "</p>\n";
  }

  echo '<h3>' . _("Sex") . "</h3>\n";
  echo '<p>' . (($pat->getSex() == 'V') ? _("Male") : _("Female")) . "</p>\n";

  if ($pat->getRace())
  {
    echo '<h3>' . _("Race") . "</h3>\n";
    echo '<p>' . $pat->getRace() . "</p>\n";
  }

  if ($pat->getBirthDate() != "" && $pat->getBirthDate() != "0000-00-00")
  {
    echo '<h3>' . _("Birth Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($pat->getBirthDate()) . "</p>\n";

    echo '<h3>' . _("Age") . "</h3>\n";
    echo '<p>' . $pat->getAge() . "</p>\n";
  }

  if ($pat->getBirthPlace())
  {
    echo '<h3>' . _("Birth Place") . "</h3>\n";
    echo '<p>' . $pat->getBirthPlace() . "</p>\n";
  }

  if ($pat->getDeceaseDate() != "" && $pat->getDeceaseDate() != "0000-00-00")
  {
    echo '<h3>' . _("Decease Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($pat->getDeceaseDate()) . "</p>\n";
  }

  if ($pat->getNTS())
  {
    echo '<h3>' . _("Sanitary Card Number (SCN)") . "</h3>\n";
    echo '<p>' . $pat->getNTS() . "</p>\n";
  }

  if ($pat->getNSS())
  {
    echo '<h3>' . _("National Health Service Number (NHSN)") . "</h3>\n";
    echo '<p>' . $pat->getNSS() . "</p>\n";
  }

  if ($pat->getFamilySituation())
  {
    echo '<h3>' . _("Family Situation") . "</h3>\n";
    echo '<p>' . nl2br($pat->getFamilySituation()) . "</p>\n";
  }

  if ($pat->getLabourSituation())
  {
    echo '<h3>' . _("Labour Situation") . "</h3>\n";
    echo '<p>' . nl2br($pat->getLabourSituation()) . "</p>\n";
  }

  if ($pat->getEducation())
  {
    echo '<h3>' . _("Education") . "</h3>\n";
    echo '<p>' . nl2br($pat->getEducation()) . "</p>\n";
  }

  if ($pat->getInsuranceCompany())
  {
    echo '<h3>' . _("Insurance Company") . "</h3>\n";
    echo '<p>' . $pat->getInsuranceCompany() . "</p>\n";
  }

  if ($pat->getIdMember())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();

    if ($staffQ->select($pat->getIdMember()))
    {
      $staff = $staffQ->fetch();
      if ($staff)
      {
        echo '<h3>' . _("Doctor you are assigned to") . "</h3>\n";
        echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
      }
      $staffQ->freeResult();
    }
    $staffQ->close();
    unset($staffQ);
    unset($staff);
  }

  unset($pat);

  echo "<hr />\n";

  /**
   * Show medical problems
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  /**
   * Show list
   */
  echo '<h2>' . _("Medical Problems List:") . "</h2>\n";

  if ( !$problemQ->selectProblems($idPatient) )
  {
    echo '<p>' . _("No medical problems defined for this patient.") . "</p>\n";
  }

  while ($problem = $problemQ->fetch())
  {
    echo '<h3>' . _("Order Number") . "</h3>\n";
    echo '<p>' . $problem->getOrderNumber() . "</p>\n";

    if ($problem->getIdMember())
    {
      $staffQ = new Staff_Query();
      $staffQ->connect();

      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          echo '<h3>' . _("Attending Physician") . "</h3>\n";
          echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    echo '<h3>' . _("Opening Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getOpeningDate()) . "</p>\n";

    echo '<h3>' . _("Last Update Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getLastUpdateDate()) . "</p>\n";

    if (I18n::localDate($problem->getClosingDate()) != "")
    {
      echo '<h3>' . _("Closing Date") . "</h3>\n";
      echo '<p>' . I18n::localDate($problem->getClosingDate()) . "</p>\n";
    }

    if ($problem->getMeetingPlace())
    {
      echo '<h3>' . _("Meeting Place") . "</h3>\n";
      echo '<p>' . $problem->getMeetingPlace() . "</p>\n";
    }

    echo '<h3>' . _("Wording") . "</h3>\n";
    echo '<p>' . nl2br($problem->getWording()) . "</p>\n";

    if ($problem->getSubjective())
    {
      echo '<h3>' . _("Subjective") . "</h3>\n";
      echo '<p>' . nl2br($problem->getSubjective()) . "</p>\n";
    }

    if ($problem->getObjective())
    {
      echo '<h3>' . _("Objective") . "</h3>\n";
      echo '<p>' . nl2br($problem->getObjective()) . "</p>\n";
    }

    if ($problem->getAppreciation())
    {
      echo '<h3>' . _("Appreciation") . "</h3>\n";
      echo '<p>' . nl2br($problem->getAppreciation()) . "</p>\n";
    }

    if ($problem->getActionPlan())
    {
      echo '<h3>' . _("Action Plan") . "</h3>\n";
      echo '<p>' . nl2br($problem->getActionPlan()) . "</p>\n";
    }

    if ($problem->getPrescription())
    {
      echo '<h3>' . _("Prescription") . "</h3>\n";
      echo '<p>' . nl2br($problem->getPrescription()) . "</p>\n";
    }

    echo "<hr />\n";
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Show personal antecedents
   */
  $historyQ = new History_Query();
  $historyQ->connect();

  $historyQ->selectPersonal($idPatient);

  $history = $historyQ->fetch();
  if ( !$history )
  {
    Error::fetch($historyQ);
  }

  echo '<h2>' . _("Personal Antecedents") . "</h2>\n";

  if ($history->getBirthGrowth())
  {
    echo '<h3>' . _("Birth and Growth") . "</h3>\n";
    echo '<p>' . nl2br($history->getBirthGrowth()) . "</p>\n";
  }

  if ($history->getGrowthSexuality())
  {
    echo '<h3>' . _("Growth and Sexuality") . "</h3>\n";
    echo '<p>' . nl2br($history->getGrowthSexuality()) . "</p>\n";
  }

  if ($history->getFeed())
  {
    echo '<h3>' . _("Feed") . "</h3>\n";
    echo '<p>' . nl2br($history->getFeed()) . "</p>\n";
  }

  if ($history->getHabits())
  {
    echo '<h3>' . _("Habits") . "</h3>\n";
    echo '<p>' . nl2br($history->getHabits()) . "</p>\n";
  }

  if ($history->getPeristalticConditions())
  {
    echo '<h3>' . _("Peristaltic Conditions") . "</h3>\n";
    echo '<p>' . nl2br($history->getPeristalticConditions()) . "</p>\n";
  }

  if ($history->getPsychological())
  {
    echo '<h3>' . _("Psychological Conditions") . "</h3>\n";
    echo '<p>' . nl2br($history->getPsychological()) . "</p>\n";
  }

  if ($history->getChildrenComplaint())
  {
    echo '<h3>' . _("Children Complaint") . "</h3>\n";
    echo '<p>' . nl2br($history->getChildrenComplaint()) . "</p>\n";
  }

  if ($history->getVenerealDisease())
  {
    echo '<h3>' . _("Venereal Disease") . "</h3>\n";
    echo '<p>' . nl2br($history->getVenerealDisease()) . "</p>\n";
  }

  if ($history->getAccidentSurgicalOperation())
  {
    echo '<h3>' . _("Accidents and Surgical Operations") . "</h3>\n";
    echo '<p>' . nl2br($history->getAccidentSurgicalOperation()) . "</p>\n";
  }

  if ($history->getMedicinalIntolerance())
  {
    echo '<h3>' . _("Medicinal Intolerance") . "</h3>\n";
    echo '<p>' . nl2br($history->getMedicinalIntolerance()) . "</p>\n";
  }

  if ($history->getMentalIllness())
  {
    echo '<h3>' . _("Mental Illness") . "</h3>\n";
    echo '<p>' . nl2br($history->getMentalIllness()) . "</p>\n";
  }

  echo "<hr />\n";

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

  echo '<h2>' . _("Family Antecedents") . "</h2>\n";

  if ($history->getParentsStatusHealth())
  {
    echo '<h3>' . _("Parents Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getParentsStatusHealth()) . "</p>\n";
  }

  if ($history->getBrothersStatusHealth())
  {
    echo '<h3>' . _("Brothers and Sisters Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getBrothersStatusHealth()) . "</p>\n";
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    echo '<h3>' . _("Spouse and Childs Status Health") . "</h3>\n";
    echo '<p>' . nl2br($history->getSpouseChildsStatusHealth()) . "</p>\n";
  }

  if ($history->getFamilyIllness())
  {
    echo '<h3>' . _("Family Illness") . "</h3>\n";
    echo '<p>' . nl2br($history->getFamilyIllness()) . "</p>\n";
  }

  echo "<hr />\n";

  /**
   * Show closed medical problems
   */
  $problemQ = new Problem_Page_Query();
  $problemQ->connect();

  /**
   * Show list
   */
  echo '<h2>' . _("Closed Medical Problems List:") . "</h2>\n";

  if ( !$problemQ->selectProblems($idPatient, true) )
  {
    echo '<p>' . _("No closed medical problems defined for this patient.") . "</p>\n";
    echo '<hr />' . "\n";
  }

  while ($problem = $problemQ->fetch())
  {
    echo '<h3>' . _("Order Number") . "</h3>\n";
    echo '<p>' . $problem->getOrderNumber() . "</p>\n";

    if ($problem->getIdMember())
    {
      $staffQ = new Staff_Query();
      $staffQ->connect();

      if ($staffQ->select($problem->getIdMember()))
      {
        $staff = $staffQ->fetch();
        if ($staff)
        {
          echo '<h3>' . _("Attending Physician") . "</h3>\n";
          echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
        }
        $staffQ->freeResult();
      }
      $staffQ->close();
      unset($staffQ);
      unset($staff);
    }

    echo '<h3>' . _("Opening Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getOpeningDate()) . "</p>\n";

    echo '<h3>' . _("Last Update Date") . "</h3>\n";
    echo '<p>' . I18n::localDate($problem->getLastUpdateDate()) . "</p>\n";

    if (I18n::localDate($problem->getClosingDate()) != "")
    {
      echo '<h3>' . _("Closing Date") . "</h3>\n";
      echo '<p>' . I18n::localDate($problem->getClosingDate()) . "</p>\n";
    }

    if ($problem->getMeetingPlace())
    {
      echo '<h3>' . _("Meeting Place") . "</h3>\n";
      echo '<p>' . $problem->getMeetingPlace() . "</p>\n";
    }

    echo '<h3>' . _("Wording") . "</h3>\n";
    echo '<p>' . $problem->getWording() . "</p>\n";

    if ($problem->getSubjective())
    {
      echo '<h3>' . _("Subjective") . "</h3>\n";
      echo '<p>' . $problem->getSubjective() . "</p>\n";
    }

    if ($problem->getObjective())
    {
      echo '<h3>' . _("Objective") . "</h3>\n";
      echo '<p>' . $problem->getObjective() . "</p>\n";
    }

    if ($problem->getAppreciation())
    {
      echo '<h3>' . _("Appreciation") . "</h3>\n";
      echo '<p>' . $problem->getAppreciation() . "</p>\n";
    }

    if ($problem->getActionPlan())
    {
      echo '<h3>' . _("Action Plan") . "</h3>\n";
      echo '<p>' . $problem->getActionPlan() . "</p>\n";
    }

    if ($problem->getPrescription())
    {
      echo '<h3>' . _("Prescription") . "</h3>\n";
      echo '<p>' . $problem->getPrescription() . "</p>\n";
    }

    echo "<hr />\n";
  } // end while
  $problemQ->freeResult();
  $problemQ->close();
  unset($problemQ);
  unset($problem);

  /**
   * Do print the page
   */
  echo '<script type="text/javascript">' . "\n";
  echo "<!--/*--><![CDATA[/*<!--*/\n";
  echo 'if (typeof(window.print) != "undefined")' . "\n";
  echo "{\n";
  echo '  window.print();' . "\n";
  echo "}\n";
  echo "/*]]>*///-->\n";
  echo "</script>\n";

  /**
   * Show footer page
   */
  echo "</body></html>\n";
?>
