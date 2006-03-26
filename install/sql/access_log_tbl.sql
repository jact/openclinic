/**
 * @package OpenClinic
 *
 * @copyright Copyright (c) 2002-2006 jact
 * @license Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: access_log_tbl.sql,v 1.6 2006/03/26 15:36:40 jact Exp $
 */

/**
 * access_log_tbl.sql
 *
 * Creation of access_log_tbl structure
 *
 * @author jact <jachavar@gmail.com>
 * @since 0.3
 */

CREATE TABLE access_log_tbl (
  id_user INT UNSIGNED NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  id_profile SMALLINT UNSIGNED NOT NULL,
  PRIMARY KEY (id_user,access_date)
);
