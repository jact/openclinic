/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: medical_test_tbl.sql,v 1.4 2005/08/03 18:02:29 jact Exp $
 */

/**
 * medical_test_tbl.sql
 *
 * Creation of medical_test_tbl structure
 *
 * Author: jact <jachavar@gmail.com>
 */

CREATE TABLE medical_test_tbl (
  id_test INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  id_problem INT UNSIGNED NOT NULL,
  document_type VARCHAR(128) NULL, /* MIME type */
  path_filename TEXT NOT NULL,
  FOREIGN KEY (id_problem) REFERENCES problem_tbl(id_problem) ON DELETE CASCADE
);
