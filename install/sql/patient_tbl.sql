/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: patient_tbl.sql,v 1.3 2004/04/24 15:12:21 jact Exp $
 */

/**
 * patient_tbl.sql
 ********************************************************************
 * Creation of patient_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE patient_tbl (
  id_patient INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  /*last_update_date DATE NOT NULL,*/ /* fecha de última actualización */
  nif VARCHAR(20) NULL,
  first_name VARCHAR(25) NOT NULL,
  surname1 VARCHAR(30) NOT NULL,
  surname2 VARCHAR(30) NOT NULL,
  address TEXT NULL DEFAULT '',
  phone_contact TEXT NULL DEFAULT '',
  sex ENUM('V','H') NOT NULL DEFAULT 'V',
  race VARCHAR(25) NULL, /* raza: amarilla, blanca, cobriza, negra */
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
  FOREIGN KEY (collegiate_number) REFERENCES staff_tbl(collegiate_number) ON DELETE SET NULL
);
