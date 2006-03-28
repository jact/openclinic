/**
 * relative_tbl.sql
 *
 * Creation of relative_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: relative_tbl.sql,v 1.6 2006/03/28 19:01:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE relative_tbl (
  id_patient INT UNSIGNED NOT NULL,
  id_relative INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_patient,id_relative),
  FOREIGN KEY (id_patient) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE,
  FOREIGN KEY (id_relative) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE
);
