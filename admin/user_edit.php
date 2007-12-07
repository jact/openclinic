<?php
/**
 * user_edit.php
 *
 * User edition process
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: user_edit.php,v 1.22 2007/12/07 16:50:50 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

  /**
   * Controlling vars
   */
  $returnLocation = "../admin/user_list.php";

  /**
   * Checking for post vars. Go back to $returnLocation if none found.
   */
  if (count($_POST) == 0 || !is_numeric($_POST["id_user"]))
  {
    header("Location: " . $returnLocation);
    exit();
  }

  /**
   * Controlling vars
   */
  $errorLocation = "../admin/user_edit_form.php?id_user=" . intval($_POST["id_user"]) . ((isset($_POST["all"])) ? "&all=Y" : "");
  // Redefinition if it is needed after count($_POST)
  $returnLocation = ((isset($_POST["all"])) ? "../home/index.php" : "../admin/user_list.php");

  require_once("../config/environment.php");
  if ( !isset($_POST["all"]) )
  {
    /**
     * Checking permissions
     */
    include_once("../auth/login_check.php");
    loginCheck(OPEN_PROFILE_ADMINISTRATOR);
  }

  /**
   * Validate data
   */
  require_once("../model/Query/User.php");
  $user = new User();

  $user->setIdUser($_POST["id_user"]);

  require_once("../admin/user_validate_post.php");

  /**
   * Update user
   */
  $userQ = new Query_User();
  if ($userQ->existLogin($user->getLogin(), $user->getIdMember()))
  {
    $loginUsed = true;
    FlashMsg::add(sprintf(_("Login, %s, already exists. The changes have no effect."), $user->getLogin()),
      OPEN_MSG_WARNING
    );
  }
  else
  {
    $userQ->update($user);
    FlashMsg::add(sprintf(_("User, %s, has been updated."), $user->getLogin()));

    /**
     * updating session variables if user is current user
     */
    if (isset($_POST["all"]))
    {
      $_SESSION['auth']['login_session'] = $user->getLogin();
      $_SESSION['auth']['user_theme'] = $user->getIdTheme();
    }
  }

  if ($changePwd && !$loginUsed)
  {
    if ( !$userQ->verifySignOn($_POST["login"], $_POST["md5_old"], true) )
    {
      $userQ->close();

      unset($formError);
      $formError["old_pwd"] = ((trim($_POST["md5_old"]) == "")
        ? _("This is a required field.")
        : _("This field is not correct."));

      Form::setSession($_POST, $formError);

      header("Location: " . $errorLocation);
      exit();
    }

    $userQ->resetPwd($user);
  }
  $userQ->close();
  unset($userQ);
  unset($user);

  /**
   * Destroy form values and errors
   */
  Form::unsetSession();

  /**
   * Redirect to $returnLocation to avoid reload problem
   */
  header("Location: " . $returnLocation);
?>
