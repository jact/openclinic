/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: user_tbl.sql,v 1.3 2004/04/24 15:12:21 jact Exp $
 */

/**
 * user_tbl.sql
 ********************************************************************
 * Creation of user_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE user_tbl (
  id_user INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pwd VARCHAR(32) NOT NULL, /* 32 caracteres por ser md5 */
  email VARCHAR(40) NULL,
  actived ENUM('N','Y') NOT NULL DEFAULT 'N',
  id_theme SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  id_profile SMALLINT UNSIGNED NOT NULL DEFAULT 3, /* por defecto perfil de médico */
  FOREIGN KEY (id_theme) REFERENCES theme_tbl(id_theme) ON DELETE SET DEFAULT,
  FOREIGN KEY (id_profile) REFERENCES profile_tbl(id_profile) ON DELETE SET DEFAULT
);

INSERT INTO user_tbl VALUES (
  1,
  '73850afb68a28c03ef4d2e426634e041',
  NULL,
  'Y',
  1,
  1
);

INSERT INTO user_tbl VALUES (
  2,
  '21232f297a57a5a743894a0e4a801fc3',
  NULL,
  'Y',
  1,
  1
);
