/**
 * theme_tbl.sql
 *
 * Creation of theme_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2007 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: theme_tbl.sql,v 1.8 2007/12/15 14:32:54 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE theme_tbl (
  id_theme SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  theme_name VARCHAR(50) NOT NULL,
  css_file VARCHAR(50) NOT NULL
);

INSERT INTO theme_tbl VALUES (
  1, 'OpenClinic', 'openclinic.css'
);
