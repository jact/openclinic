/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: connection_problem_tbl.sql,v 1.1 2004/01/29 14:42:34 jact Exp $
 */

/**
 * connection_problem_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 15:42
 */

CREATE TABLE connection_problem_tbl (
  id_problem INT UNSIGNED NOT NULL,
  id_connection INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_problem,id_connection),
  FOREIGN KEY (id_problem) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE,
  FOREIGN KEY (id_connection) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE
);
