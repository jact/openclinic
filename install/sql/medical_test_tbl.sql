/**
 * medical_test_tbl.sql
 *
 * Creation of medical_test_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: medical_test_tbl.sql,v 1.7 2013/01/07 18:18:32 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE medical_test_tbl (
  id_test INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_problem INT UNSIGNED NOT NULL,
  document_type VARCHAR(128) NULL, /* MIME type */
  path_filename TEXT NOT NULL,
  FOREIGN KEY (id_problem) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE
) ENGINE=MyISAM;
