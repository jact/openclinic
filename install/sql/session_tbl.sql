/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: session_tbl.sql,v 1.4 2005/08/03 18:02:29 jact Exp $
 */

/**
 * session_tbl.sql
 *
 * Creation of session_tbl structure
 *
 * Author: jact <jachavar@gmail.com>
 */

CREATE TABLE session_tbl (
  login VARCHAR(20) NOT NULL,
  last_updated_date DATETIME NOT NULL,
  token INT NOT NULL
);
