# File to upgrade OpenClinic from 0.6 to 0.7
# After use this, you can delete it

ALTER TABLE patient_tbl ADD id_member INT UNSIGNED NULL AFTER insurance_company;
ALTER TABLE patient_tbl DROP collegiate_number;

ALTER TABLE deleted_patient_tbl ADD id_member INT UNSIGNED NULL AFTER insurance_company;

ALTER TABLE problem_tbl ADD id_member INT UNSIGNED NULL AFTER last_update_date;
ALTER TABLE problem_tbl DROP collegiate_number;

ALTER TABLE deleted_problem_tbl ADD id_member INT UNSIGNED NULL AFTER id_patient;

ALTER TABLE record_log_tbl CHANGE id_key1 affected_row TEXT NOT NULL;
ALTER TABLE record_log_tbl DROP id_key2;
UPDATE record_log_tbl SET affected_row='';

UPDATE setting_tbl SET version='0.7.20041104';

DROP TABLE IF EXISTS profile_tbl;

DROP TABLE IF EXISTS theme_tbl;

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

UPDATE user_tbl SET id_theme=1;

UPDATE setting_tbl SET id_theme=1;
