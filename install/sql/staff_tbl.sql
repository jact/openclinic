/**
 * staff_tbl.sql
 *
 * Creation of staff_tbl structure
 *
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * @package   OpenClinic
 * @copyright 2002-2013 jact
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL
 * @version   CVS: $Id: staff_tbl.sql,v 1.8 2013/01/13 14:19:38 jact Exp $
 * @author    jact <jachavar@gmail.com>
 */

CREATE TABLE staff_tbl (
  id_member INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  member_type ENUM('Administrative','Doctor') NOT NULL DEFAULT 'Administrative',
  collegiate_number VARCHAR(20) NULL, /* número de colegiado */
  nif VARCHAR(20) NULL,
  first_name VARCHAR(25) NOT NULL,
  surname1 VARCHAR(30) NOT NULL,
  surname2 VARCHAR(30) NULL DEFAULT '',
  address TEXT NULL,
  phone_contact TEXT NULL,
  id_user INT UNSIGNED NULL,
  login VARCHAR(20) NULL,
  FOREIGN KEY (id_user) REFERENCES user_tbl(id_user) ON DELETE SET NULL
) ENGINE=MyISAM;

INSERT INTO staff_tbl VALUES (
  4,
  'Administrative',
  NULL,
  '123456785',
  'Benito',
  'Camelas',
  'Unmontón',
  'Camino de las Torres 777',
  '555-45 45 45',
  NULL,
  'benito'
);

INSERT INTO staff_tbl VALUES (
  3,
  'Doctor',
  '342343445',
  '34567123',
  'Carmelo',
  'Cotón',
  'Cotón',
  'Plaza España 222',
  '555-23 24 23',
  NULL,
  'carmelo'
);

INSERT INTO staff_tbl VALUES (
  2,
  'Administrative',
  NULL,
  'no importa',
  'Admin',
  'Admin',
  'Admin',
  'No importa',
  'No importa',
  2,
  'admin'
);

INSERT INTO staff_tbl VALUES (
  1,
  'Administrative',
  NULL,
  'no importa',
  'Admin',
  'Admin',
  'Admin',
  'No importa',
  'No importa',
  1,
  'sysop'
);
