/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: medical_test_tbl.sql,v 1.3 2004/04/24 15:12:21 jact Exp $
 */

/**
 * medical_test_tbl.sql
 ********************************************************************
 * Creation of medical_test_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE medical_test_tbl (
  id_test INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_problem INT UNSIGNED NOT NULL,
  document_type VARCHAR(128) NULL, /* MIME type */
  path_filename TEXT NOT NULL,
  FOREIGN KEY (id_problem) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE
);
