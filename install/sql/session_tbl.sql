/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: session_tbl.sql,v 1.1 2004/01/29 14:46:14 jact Exp $
 */

/**
 * session_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 29/01/04 15:46
 */

CREATE TABLE session_tbl (
  login VARCHAR(20) NOT NULL,
  last_updated_date DATETIME NOT NULL,
  token INT NOT NULL
);
