/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: setting_tbl.sql,v 1.5 2004/07/04 14:10:50 jact Exp $
 */

/**
 * setting_tbl.sql
 ********************************************************************
 * Creation of setting_tbl structure
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE setting_tbl (
  clinic_name VARCHAR(128) NULL,
  clinic_image_url TEXT NULL,
  use_image ENUM('N','Y') NOT NULL DEFAULT 'N',
  clinic_hours VARCHAR(128) NULL,
  clinic_address TEXT NULL,
  clinic_phone VARCHAR(40) NULL,
  clinic_url TEXT NULL, /* web page of the clinic if exists */
  session_timeout SMALLINT UNSIGNED NOT NULL DEFAULT 20,
  items_per_page TINYINT UNSIGNED NOT NULL DEFAULT 10,
  version VARCHAR(14) NOT NULL,
  language VARCHAR(10) NOT NULL DEFAULT 'en',
  id_theme SMALLINT UNSIGNED NOT NULL DEFAULT 1,
  FOREIGN KEY (id_theme) REFERENCES theme_tbl(id_theme) ON DELETE SET DEFAULT
) MIN_ROWS=1 MAX_ROWS=1;

INSERT INTO setting_tbl VALUES (
  'My Clinic',
  '../images/openclinic-1.png',
  'Y',
  'L-V 9am-3pm, S 10am-1pm',
  'Sesame Street',
  '999 66 66 66',
  'http://www.example.com',
  20,
  10,
  '0.7.20040704',
  'en',
  1
);
