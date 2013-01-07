/**
 * record_log_tbl.sql
 *
 * Creation of record_log_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: record_log_tbl.sql,v 1.9 2013/01/07 18:19:03 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.3
 */

CREATE TABLE record_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  table_name VARCHAR(25) NOT NULL,
  operation VARCHAR(10) NOT NULL,
  affected_row TEXT NOT NULL,
  KEY id_user (id_user)
) ENGINE=MyISAM;
