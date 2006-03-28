/**
 * problem_tbl.sql
 *
 * Creation of problem_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: problem_tbl.sql,v 1.7 2006/03/28 19:01:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE problem_tbl (
  id_problem INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  last_update_date DATE NOT NULL, /* fecha de última actualización */
  id_patient INT UNSIGNED NOT NULL,
  id_member INT UNSIGNED NULL, /* clave del médico que atiende el problema */
  order_number TINYINT UNSIGNED NOT NULL, /* número de orden relativo a cada paciente */
  opening_date DATE NOT NULL, /* fecha de apertura */
  closing_date DATE NULL, /* fecha de cierre */
  meeting_place VARCHAR(40) NULL, /* lugar de encuentro */
  wording TEXT NOT NULL, /* enunciado del problema */
  subjective TEXT NULL, /* subjetivo */
  objective TEXT NULL, /* objetivo */
  appreciation TEXT NULL, /* valoración */
  action_plan TEXT NULL, /* plan de actuación */
  prescription TEXT NULL, /* prescripción (por prescripción facultativa, on doctor's orders) */
  FOREIGN KEY (id_patient) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE,
  FOREIGN KEY (id_member) REFERENCES staff_tbl(id_member) ON DELETE SET NULL
);
