/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: deleted_patient_tbl.sql,v 1.1 2004/01/29 14:42:54 jact Exp $
 */

/**
 * deleted_patient_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 15:42
 */

CREATE TABLE deleted_patient_tbl (
  id_patient INT UNSIGNED NOT NULL,
  nif VARCHAR(20) NULL,
  first_name VARCHAR(25) NOT NULL,
  surname1 VARCHAR(30) NOT NULL,
  surname2 VARCHAR(30) NOT NULL,
  address TEXT NULL DEFAULT '',
  phone_contact TEXT NULL DEFAULT '',
  sex ENUM('V','H') NOT NULL DEFAULT 'V',
  race VARCHAR(25) NULL, /* raza: blanca, amarilla, cobriza, negra */
  birth_date DATE NULL, /* fecha de nacimiento */
  birth_place VARCHAR(40) NULL, /* lugar de nacimiento */
  decease_date DATE NULL, /* fecha de defunción */
  nts VARCHAR(30) NULL, /* número de tarjeta sanitaria */
  nss VARCHAR(30) NULL, /* número de la seguridad social */
  family_situation TEXT NULL, /* situación familiar */
  labour_situation TEXT NULL, /* situación laboral */
  education TEXT NULL, /* estudios */
  insurance_company VARCHAR(30) NULL, /* entidad aseguradora */
  collegiate_number VARCHAR(20) NULL, /* numero de colegiado (clave del médico al que pertenece por cupo) */
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
  create_date DATETIME NOT NULL,
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL
);
