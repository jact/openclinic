/**
 * setting_tbl.sql
 *
 * Creation of setting_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: setting_tbl.sql,v 1.17 2013/01/07 18:22:09 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE setting_tbl (
  clinic_name VARCHAR(128) NULL,
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
) MIN_ROWS=1 MAX_ROWS=1 ENGINE=MyISAM;

INSERT INTO setting_tbl VALUES (
  'My Clinic',
  'L-V 9am-3pm, S 10am-1pm',
  'Sesame Street',
  '999 66 66 66',
  'http://www.example.com',
  20,
  10,
  '0.8.20130107',
  'en',
  1
);
