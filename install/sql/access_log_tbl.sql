/**
 * access_log_tbl.sql
 *
 * Creation of access_log_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: access_log_tbl.sql,v 1.8 2013/01/07 18:17:06 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.3
 */

CREATE TABLE access_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  id_profile SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (id_user,access_date)
) ENGINE=MyISAM;
