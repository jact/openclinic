/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: record_log_tbl.sql,v 1.7 2006/03/26 15:36:40 jact Exp $
 */

/**
 * record_log_tbl.sql
 *
 * Creation of record_log_tbl structure
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.3
 */

CREATE TABLE record_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  table_name VARCHAR(25) NOT NULL,
  operation VARCHAR(10) NOT NULL,
  affected_row TEXT NOT NULL,
  KEY id_user (id_user)
);
