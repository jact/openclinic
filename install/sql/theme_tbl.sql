/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_tbl.sql,v 1.6 2006/03/26 15:36:40 jact Exp $
 */

/**
 * theme_tbl.sql
 *
 * Creation of theme_tbl structure
 *
 * @author jact <jachavar@gmail.com>
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
