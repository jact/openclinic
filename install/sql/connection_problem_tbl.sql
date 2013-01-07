/**
 * connection_problem_tbl.sql
 *
 * Creation of connection_problem_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: connection_problem_tbl.sql,v 1.7 2013/01/07 18:17:27 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE connection_problem_tbl (
  id_problem INT UNSIGNED NOT NULL,
  id_connection INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_problem,id_connection),
  FOREIGN KEY (id_problem) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE,
  FOREIGN KEY (id_connection) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE
) ENGINE=MyISAM;
