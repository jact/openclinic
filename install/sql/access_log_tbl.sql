/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: access_log_tbl.sql,v 1.4 2004/09/22 18:19:08 jact Exp $
 */

/**
 * access_log_tbl.sql
 ********************************************************************
 * Creation of access_log_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * @since 0.3
 */

CREATE TABLE access_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  id_profile SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (id_user,access_date)
);
