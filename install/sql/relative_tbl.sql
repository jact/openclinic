/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_tbl.sql,v 1.1 2004/01/29 14:45:50 jact Exp $
 */

/**
 * relative_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 15:45
 */

CREATE TABLE relative_tbl (
  id_patient INT UNSIGNED NOT NULL,
  id_relative INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_patient,id_relative),
  FOREIGN KEY (id_patient) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE,
  FOREIGN KEY (id_relative) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE
);
