/**
 * theme_tbl.sql
 *
 * Creation of theme_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2006 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_tbl.sql,v 1.7 2006/03/28 19:01:43 jact Exp $
 * @author    jact <jachavar@gmail.com>
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
