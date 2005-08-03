/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2005 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log_tbl.sql,v 1.6 2005/08/03 18:02:29 jact Exp $
 */

/**
 * record_log_tbl.sql
 *
 * Creation of record_log_tbl structure
 *
 * Author: jact <jachavar@gmail.com>
 * @since 0.3
 */

CREATE TABLE record_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  table_name VARCHAR(25) NOT NULL,
  operation VARCHAR(10) NOT NULL,
  affected_row TEXT NOT NULL,
  KEY id_user (id_user)
);
