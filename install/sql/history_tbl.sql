/**
 * history_tbl.sql
 *
 * Creation of history_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: history_tbl.sql,v 1.6 2006/03/28 19:01:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE history_tbl (
  id_patient INT UNSIGNED PRIMARY KEY,
  birth_growth TEXT NULL, /* nacimiento y crecimiento (desarrollo) */
  growth_sexuality TEXT NULL, /* desarrollo y vida sexual */
  feed TEXT NULL, /* alimentación */
  habits TEXT NULL, /* hábitos */
  peristaltic_conditions TEXT NULL, /* condiciones peristáticas */
  psychological TEXT NULL, /* psicológicos */
  children_complaint TEXT NULL, /* enfermedades de la infancia */
  venereal_disease TEXT NULL, /* enfermedades de transmisión sexual */
  accident_surgical_operation TEXT NULL, /* accidentes e intervenciones quirúrgicas */
  medicinal_intolerance TEXT NULL, /* intolerancia medicamentosa */
  mental_illness TEXT NULL, /* enfermedades mentales y neuróticas */
  parents_status_health TEXT NULL, /* estado de salud de los padres */
  brothers_status_health TEXT NULL, /* estado de salud de los hermanos */
  spouse_childs_status_health TEXT NULL, /* estado de salud del cónyuge e hijos */
  family_illness TEXT NULL, /* enfermedades acumuladas en la familia */
  FOREIGN KEY (id_patient) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE
);
