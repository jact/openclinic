/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: deleted_problem_tbl.sql,v 1.3 2004/04/24 15:12:21 jact Exp $
 */

/**
 * deleted_problem_tbl.sql
 ********************************************************************
 * Creation of deleted_problem_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE deleted_problem_tbl (
  id_problem INT UNSIGNED NOT NULL,
  last_update_date DATE NOT NULL, /* fecha de última actualización */
  id_patient INT UNSIGNED NOT NULL,
  collegiate_number VARCHAR(20) NULL, /* número de colegiado (clave del médico que anota el problema) */
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
