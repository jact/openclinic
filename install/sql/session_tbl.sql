/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: session_tbl.sql,v 1.2 2004/04/18 14:22:25 jact Exp $
 */

/**
 * session_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE session_tbl (
  login VARCHAR(20) NOT NULL,
  last_updated_date DATETIME NOT NULL,
  token INT NOT NULL
);
