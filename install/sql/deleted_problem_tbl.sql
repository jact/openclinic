/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: deleted_problem_tbl.sql,v 1.6 2005/08/03 18:02:29 jact Exp $
 */

/**
 * deleted_problem_tbl.sql
 *
 * Creation of deleted_problem_tbl structure
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.2
 */

CREATE TABLE deleted_problem_tbl (
  id_problem INT UNSIGNED NOT NULL,
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
  create_date DATETIME NOT NULL,
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL
);
