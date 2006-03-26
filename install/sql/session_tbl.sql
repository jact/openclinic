/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: session_tbl.sql,v 1.5 2006/03/26 15:36:40 jact Exp $
 */

/**
 * session_tbl.sql
 *
 * Creation of session_tbl structure
 *
 * @author jact <jachavar@gmail.com>
 */

CREATE TABLE session_tbl (
  login VARCHAR(20) NOT NULL,
  last_updated_date DATETIME NOT NULL,
  token INT NOT NULL
);
