# File to upgrade OpenClinic from 0.2 to 0.3
# After use this, you can delete it

UPDATE setting_tbl SET version='0.3';

CREATE TABLE access_log_tbl (
  id_user INTEGER(5) NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  id_profile SMALLINT NOT NULL
);

CREATE TABLE record_log_tbl (
  id_user INTEGER(5) NOT NULL,
  login VARCHAR(20) NOT NULL,
  access_date DATETIME NOT NULL,
  table_name VARCHAR(25) NOT NULL,
  operation VARCHAR(10) NOT NULL,
  id_key1 INTEGER NOT NULL,
  id_key2 INTEGER NULL
);
