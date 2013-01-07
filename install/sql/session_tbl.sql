/**
 * session_tbl.sql
 *
 * Creation of session_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: session_tbl.sql,v 1.7 2013/01/07 18:19:34 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE session_tbl (
  login VARCHAR(20) NOT NULL,
  last_updated_date DATETIME NOT NULL,
  token INT NOT NULL
) ENGINE=MyISAM;
