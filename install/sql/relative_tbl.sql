/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: relative_tbl.sql,v 1.3 2004/04/24 15:12:21 jact Exp $
 */

/**
 * relative_tbl.sql
 ********************************************************************
 * Creation of relative_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE relative_tbl (
  id_patient INT UNSIGNED NOT NULL,
  id_relative INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_patient,id_relative),
  FOREIGN KEY (id_patient) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE,
  FOREIGN KEY (id_relative) REFERENCES patient_tbl(id_patient) ON DELETE CASCADE
);
