/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_tbl.sql,v 1.4 2004/08/12 11:37:27 jact Exp $
 */

/**
 * theme_tbl.sql
 ********************************************************************
 * Creation of theme_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE theme_tbl (
  id_theme SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  theme_name VARCHAR(50) NOT NULL,
  css_file VARCHAR(50) NOT NULL
);

INSERT INTO theme_tbl VALUES (
  NULL, 'SerialZ', 'serialz.css'
);

INSERT INTO theme_tbl VALUES (
  NULL, 'SuperfluousBanter', 'banter.css'
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Sinorca', 'sinorca.css'
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Gazetteer Alternate', 'gazetteer_alt.css'
);
