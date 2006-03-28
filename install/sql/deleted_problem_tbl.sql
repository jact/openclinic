/**
 * deleted_problem_tbl.sql
 *
 * Creation of deleted_problem_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: deleted_problem_tbl.sql,v 1.9 2006/03/28 19:01:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 * @since     0.2
 */

CREATE TABLE deleted_problem_tbl (
  id_problem INT UNSIGNED NOT NULL,
  last_update_date DATE NOT NULL, /* fecha de última actualización */
  id_patient INT UNSIGNED NOT NULL,
  id_member INT UNSIGNED NULL, /* clave del médico que atiende el problema */
  collegiate_number VARCHAR(20) NULL, /* numero de colegiado (del médico al que pertenece por cupo) */
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
  create_date DATETIME NOT NULL,
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL
);
