<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: print_medical_record.php,v 1.2 2004/04/24 14:52:14 jact Exp $
 */

/**
 * print_medical_record.php
 ********************************************************************
 * Medical record of a patient screen
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

  ////////////////////////////////////////////////////////////////////
  // Controlling vars
  ////////////////////////////////////////////////////////////////////
  $tab = "medical";
  $nav = "print";
  $onlyDoctor = true;

  require_once("../shared/read_settings.php");
  require_once("../shared/login_check.php");
  require_once("../classes/Patient_Query.php");
  require_once("../classes/Staff_Query.php");
  require_once("../classes/Problem_Query.php");
  require_once("../classes/History_Query.php");

  ////////////////////////////////////////////////////////////////////
  // Show medical record
  ////////////////////////////////////////////////////////////////////
  echo '<html><head>' . "\n";
  echo '<link rel="stylesheet" type="text/css" href="../css/style.css" />' . "\n";
  echo '<style type="text/css">' . "body {background: #fff; border: 0; padding: 0; }" . "</style>\n";

  ////////////////////////////////////////////////////////////////////
  // Checking for get vars. Close window if none found.
  ////////////////////////////////////////////////////////////////////
  if (count($_GET) == 0 || empty($_GET["key"]))
  {
    echo "</head><body>\n";
    echo '<p>' . _("No patient selected.") . "</p>\n";
    echo '<p><a href="#" onclick="window.close(); return false;">' . _("Close Window") . "</a></p>\n";
    echo "</body></html>\n";
    exit();
  }

  ////////////////////////////////////////////////////////////////////
  // Retrieving get var
  ////////////////////////////////////////////////////////////////////
  $idPatient = intval($_GET["key"]);

  ////////////////////////////////////////////////////////////////////
  // Search database for patient
  ////////////////////////////////////////////////////////////////////
  $patQ = new Patient_Query();
  $patQ->connect();
  if ($patQ->errorOccurred())
  {
    showQueryError($patQ);
  }

  $numRows = $patQ->select($idPatient);
  if ($patQ->errorOccurred())
  {
    $patQ->close();
    showQueryError($patQ);
  }

  if ( !$numRows )
  {
    $patQ->close();
    echo "</head><body>\n";
    echo '<p>' . _("That patient does not exist.") . "</p>\n";
    echo "</body></html>\n";
    exit();
  }

  $pat = $patQ->fetchPatient();
  if ( !$pat )
  {
    showQueryError($patQ);
  }
  $patQ->freeResult();
  $patQ->close();
  unset($patQ);
  $patName = $pat->getFirstName() . " " . $pat->getSurname1() . " " . $pat->getSurname2();

  echo '<title>' . $patName . date(" d-m-Y H:i:s") . "</title>\n";
  echo "</head><body>\n";

  ////////////////////////////////////////////////////////////////////
  // Show social data
  ////////////////////////////////////////////////////////////////////
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
    echo '<p>' . $pat->getAddress() . "</p>\n";
  }

  if ($pat->getPhone())
  {
    echo '<h3>' . _("Phone Contact") . "</h3>\n";
    echo '<p>' . $pat->getPhone() . "</p>\n";
  }

  echo '<h3>' . _("Sex") . "</h3>\n";
  echo '<p>' . (($pat->getSex() == 'V') ? _("Male") : _("Female")) . "</p>\n";

  if ($pat->getRace())
  {
    echo '<h3>' . _("Race") . "</h3>\n";
    echo '<p>' . $pat->getRace() . "</p>\n";
  }

  if ($pat->getBirthDate() != "")
  {
    echo '<h3>' . _("Birth Date") . "</h3>\n";
    echo '<p>' . $pat->getBirthDate() . "</p>\n";

    echo '<h3>' . _("Age") . "</h3>\n";
    echo '<p>' . $pat->getAge() . "</p>\n";
  }

  if ($pat->getBirthPlace())
  {
    echo '<h3>' . _("Birth Place") . "</h3>\n";
    echo '<p>' . $pat->getBirthPlace() . "</p>\n";
  }

  if ($pat->getDeceaseDate() != "")
  {
    echo '<h3>' . _("Decease Date") . "</h3>\n";
    echo '<p>' . $pat->getDeceaseDate() . "</p>\n";
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

  if ($pat->getCollegiateNumber())
  {
    $staffQ = new Staff_Query();
    $staffQ->connect();
    if ($staffQ->errorOccurred())
    {
      showQueryError($staffQ);
    }

    $numRows = $staffQ->selectDoctor($pat->getCollegiateNumber());
    if ($numRows)
    {
      $staff = $staffQ->fetchStaff();
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

  ////////////////////////////////////////////////////////////////////
  // Show medical problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
  }

  $count = $problemQ->selectProblems($idPatient);
  if ($problemQ->errorOccurred())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show list
  ////////////////////////////////////////////////////////////////////
  echo '<h2>' . _("Medical Problems List:") . "</h2>\n";

  if ($count == 0)
  {
    echo '<p>' . _("No medical problems defined for this patient.") . "</p>\n";
  }

  while ($problem = $problemQ->fetchProblem())
  {
    echo '<h3>' . _("Order Number") . "</h3>\n";
    echo '<p>' . $problem->getOrderNumber() . "</p>\n";

    if ($problem->getCollegiateNumber())
    {
      $staffQ = new Staff_Query();
      $staffQ->connect();
      if ($staffQ->errorOccurred())
      {
        showQueryError($staffQ);
      }

      $numRows = $staffQ->selectDoctor($problem->getCollegiateNumber());
      if ($numRows)
      {
        $staff = $staffQ->fetchStaff();
        if ($staff)
        {
          echo '<h3>' . _("Doctor who treated you") . "</h3>\n";
          echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
        }
        $staffQ->freeResult();
      }
      //$staffQ->close(); // don't delete comment marks
      unset($staffQ);
      unset($staff);
    }

    echo '<h3>' . _("Opening Date") . "</h3>\n";
    echo '<p>' . $problem->getOpeningDate() . "</p>\n";

    echo '<h3>' . _("Last Update Date") . "</h3>\n";
    echo '<p>' . $problem->getLastUpdateDate() . "</p>\n";

    if ($problem->getClosingDate() != "")
    {
      echo '<h3>' . _("Closing Date") . "</h3>\n";
      echo '<p>' . $problem->getClosingDate() . "</p>\n";
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

  ////////////////////////////////////////////////////////////////////
  // Show personal antecedents
  ////////////////////////////////////////////////////////////////////
  $historyQ = new History_Query();
  $historyQ->connect();
  if ($historyQ->errorOccurred())
  {
    showQueryError($historyQ);
  }

  if ( !$historyQ->selectPersonal($idPatient) )
  {
    $historyQ->close();
    showQueryError($historyQ);
  }

  $history = $historyQ->fetchPersonal();
  if ( !$history )
  {
    showQueryError($historyQ);
  }

  echo '<h2>' . _("Personal Antecedents") . "</h2>\n";

  if ($history->getBirthGrowth())
  {
    echo '<h3>' . _("Birth and Growth") . "</h3>\n";
    echo '<p>' . $history->getBirthGrowth() . "</p>\n";
  }

  if ($history->getGrowthSexuality())
  {
    echo '<h3>' . _("Growth and Sexuality") . "</h3>\n";
    echo '<p>' . $history->getGrowthSexuality() . "</p>\n";
  }

  if ($history->getFeed())
  {
    echo '<h3>' . _("Feed") . "</h3>\n";
    echo '<p>' . $history->getFeed() . "</p>\n";
  }

  if ($history->getHabits())
  {
    echo '<h3>' . _("Habits") . "</h3>\n";
    echo '<p>' . $history->getHabits() . "</p>\n";
  }

  if ($history->getPeristalticConditions())
  {
    echo '<h3>' . _("Peristaltic Conditions") . "</h3>\n";
    echo '<p>' . $history->getPeristalticConditions() . "</p>\n";
  }

  if ($history->getPsychological())
  {
    echo '<h3>' . _("Psychological Conditions") . "</h3>\n";
    echo '<p>' . $history->getPsychological() . "</p>\n";
  }

  if ($history->getChildrenComplaint())
  {
    echo '<h3>' . _("Children Complaint") . "</h3>\n";
    echo '<p>' . $history->getChildrenComplaint() . "</p>\n";
  }

  if ($history->getVenerealDisease())
  {
    echo '<h3>' . _("Venereal Disease") . "</h3>\n";
    echo '<p>' . $history->getVenerealDisease() . "</p>\n";
  }

  if ($history->getAccidentSurgicalOperation())
  {
    echo '<h3>' . _("Accidents and Surgical Operations") . "</h3>\n";
    echo '<p>' . $history->getAccidentSurgicalOperation() . "</p>\n";
  }

  if ($history->getMedicinalIntolerance())
  {
    echo '<h3>' . _("Medicinal Intolerance") . "</h3>\n";
    echo '<p>' . $history->getMedicinalIntolerance() . "</p>\n";
  }

  if ($history->getMentalIllness())
  {
    echo '<h3>' . _("Mental Illness") . "</h3>\n";
    echo '<p>' . $history->getMentalIllness() . "</p>\n";
  }

  echo "<hr />\n";

  ////////////////////////////////////////////////////////////////////
  // Show family antecedents
  ////////////////////////////////////////////////////////////////////
  if ( !$historyQ->selectFamily($idPatient) )
  {
    $historyQ->close();
    showQueryError($historyQ);
  }

  $history = $historyQ->fetchFamily();
  if ( !$history )
  {
    showQueryError($historyQ);
  }
  $historyQ->freeResult();
  $historyQ->close();
  unset($historyQ);

  echo '<h2>' . _("Family Antecedents") . "</h2>\n";

  if ($history->getParentsStatusHealth())
  {
    echo '<h3>' . _("Parents Status Health") . "</h3>\n";
    echo '<p>' . $history->getParentsStatusHealth() . "</p>\n";
  }

  if ($history->getBrothersStatusHealth())
  {
    echo '<h3>' . _("Brothers and Sisters Status Health") . "</h3>\n";
    echo '<p>' . $history->getBrothersStatusHealth() . "</p>\n";
  }

  if ($history->getSpouseChildsStatusHealth())
  {
    echo '<h3>' . _("Spouse and Childs Status Health") . "</h3>\n";
    echo '<p>' . $history->getSpouseChildsStatusHealth() . "</p>\n";
  }

  if ($history->getFamilyIllness())
  {
    echo '<h3>' . _("Family Illness") . "</h3>\n";
    echo '<p>' . $history->getFamilyIllness() . "</p>\n";
  }

  echo "<hr />\n";

  ////////////////////////////////////////////////////////////////////
  // Show closed medical problems
  ////////////////////////////////////////////////////////////////////
  $problemQ = new Problem_Query();
  $problemQ->connect();
  if ($problemQ->errorOccurred())
  {
    showQueryError($problemQ);
  }

  $count = $problemQ->selectProblems($idPatient, true);
  if ($problemQ->errorOccurred())
  {
    $problemQ->close();
    showQueryError($problemQ);
  }

  ////////////////////////////////////////////////////////////////////
  // Show list
  ////////////////////////////////////////////////////////////////////
  echo '<h2>' . _("Closed Medical Problems List:") . "</h2>\n";

  if ($count == 0)
  {
    echo '<p>' . _("No closed medical problems defined for this patient.") . "</p>\n";
    echo '<hr />' . "\n";
  }

  while ($problem = $problemQ->fetchProblem())
  {
    echo '<h3>' . _("Order Number") . "</h3>\n";
    echo '<p>' . $problem->getOrderNumber() . "</p>\n";

    if ($problem->getCollegiateNumber())
    {
      $auxQ = new Staff_Query();
      $auxQ->connect();
      if ($auxQ->errorOccurred())
      {
        showQueryError($auxQ);
      }

      $numRows = $auxQ->selectDoctor($problem->getCollegiateNumber());
      if ($numRows)
      {
        $staff = $auxQ->fetchStaff();
        echo '<h3>' . _("Doctor who treated you") . "</h3>\n";
        echo '<p>' . $staff->getSurname1() . ' ' . $staff->getSurname2() . ', ' . $staff->getFirstName() . "</p>\n";
        $auxQ->freeResult();
      }
      //$auxQ->close(); // don't delete comment marks
      unset($auxQ);
      unset($staff);
    }

    echo '<h3>' . _("Opening Date") . "</h3>\n";
    echo '<p>' . $problem->getOpeningDate() . "</p>\n";

    echo '<h3>' . _("Last Update Date") . "</h3>\n";
    echo '<p>' . $problem->getLastUpdateDate() . "</p>\n";

    if ($problem->getClosingDate() != "")
    {
      echo '<h3>' . _("Closing Date") . "</h3>\n";
      echo '<p>' . $problem->getClosingDate() . "</p>\n";
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

  ////////////////////////////////////////////////////////////////////
  // Do print the page
  ////////////////////////////////////////////////////////////////////
  echo '<script type="text/javascript">' . "\n";
  echo "<!--\n";
  echo 'if (typeof(window.print) != "undefined")' . "\n";
  echo "{\n";
  echo '  window.print();' . "\n";
  echo "}\n";
  echo "//-->\n";
  echo "</script>\n";

  ////////////////////////////////////////////////////////////////////
  // Show footer page
  ////////////////////////////////////////////////////////////////////
  echo "</body></html>\n";
?>
