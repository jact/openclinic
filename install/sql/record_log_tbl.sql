/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log_tbl.sql,v 1.2 2004/04/18 14:22:25 jact Exp $
 */

/**
 * record_log_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE record_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  table_name VARCHAR(25) NOT NULL,
  operation VARCHAR(10) NOT NULL,
  id_key1 INT UNSIGNED NOT NULL,
  id_key2 INT UNSIGNED NULL,
  KEY id_user (id_user)
);
