# File to upgrade OpenClinic from 0.4 to 0.5
# After use this, you can delete it

UPDATE setting_tbl SET version='0.5';

ALTER TABLE access_log_tbl MODIFY id_user INT UNSIGNED NOT NULL, MODIFY id_profile SMALLINT UNSIGNED NOT NULL;

ALTER TABLE connection_problem_tbl MODIFY id_problem INT UNSIGNED NOT NULL, MODIFY id_connection INT UNSIGNED NOT NULL;

ALTER TABLE deleted_patient_tbl MODIFY id_patient INT UNSIGNED NOT NULL, MODIFY id_user INT UNSIGNED NOT NULL;

ALTER TABLE deleted_problem_tbl MODIFY id_problem INT UNSIGNED NOT NULL, MODIFY id_patient INT UNSIGNED NOT NULL, MODIFY order_number TINYINT UNSIGNED NOT NULL, MODIFY id_user INT UNSIGNED NOT NULL;

ALTER TABLE history_tbl MODIFY id_patient INT UNSIGNED NOT NULL;

ALTER TABLE medical_test_tbl MODIFY id_test INT UNSIGNED AUTO_INCREMENT, MODIFY id_problem INT UNSIGNED NOT NULL;

ALTER TABLE patient_tbl MODIFY id_patient INT UNSIGNED AUTO_INCREMENT;

ALTER TABLE problem_tbl MODIFY id_problem INT UNSIGNED AUTO_INCREMENT, MODIFY id_patient INT UNSIGNED NOT NULL, MODIFY order_number TINYINT UNSIGNED NOT NULL;

ALTER TABLE profile_tbl MODIFY id_profile SMALLINT UNSIGNED AUTO_INCREMENT;

ALTER TABLE record_log_tbl MODIFY id_user INT UNSIGNED NOT NULL, MODIFY id_key1 INT UNSIGNED NOT NULL, MODIFY id_key2 INT UNSIGNED NULL;

ALTER TABLE relative_tbl MODIFY id_patient INT UNSIGNED NOT NULL, MODIFY id_relative INT
UNSIGNED NOT NULL;

ALTER TABLE session_tbl MODIFY token INT NOT NULL;

ALTER TABLE setting_tbl MODIFY session_timeout SMALLINT UNSIGNED NOT NULL DEFAULT 20, MODIFY items_per_page TINYINT UNSIGNED NOT NULL DEFAULT 10, MODIFY id_theme SMALLINT UNSIGNED NOT NULL DEFAULT 1;

ALTER TABLE staff_tbl MODIFY id_member INT UNSIGNED AUTO_INCREMENT, MODIFY id_user INT UNSIGNED NULL;

ALTER TABLE theme_tbl MODIFY id_theme SMALLINT UNSIGNED AUTO_INCREMENT, MODIFY title_font_size TINYINT UNSIGNED NOT NULL, MODIFY body_font_size TINYINT UNSIGNED NOT NULL, MODIFY navbar_font_size TINYINT UNSIGNED NOT NULL, MODIFY tab_font_size TINYINT UNSIGNED NOT NULL, MODIFY table_border_width TINYINT UNSIGNED NOT NULL, MODIFY table_cell_padding TINYINT UNSIGNED NOT NULL;

ALTER TABLE user_tbl MODIFY id_user INT UNSIGNED AUTO_INCREMENT, MODIFY id_theme SMALLINT UNSIGNED NOT NULL DEFAULT 1, MODIFY id_profile SMALLINT UNSIGNED NOT NULL DEFAULT 3;

INSERT INTO theme_tbl VALUES (
  NULL, 'Thai AppServ',
  'white', 'arial,helvetica.sans-serif', 18, 'Y', 'black', 'left',
  'white', 'verdana,arial,helvetica,sans-serif', 8, 'black', '#0000a0', '#ff0000',
  '#f4faff', 'arial,helvetica,sans-serif', 10, 'black', '#0000a0',
  '#d3d9ee', 'verdana,arial,helvetica,sans-serif', 10, 'Y', 'black', '#0000a0',
  '#a3a3d1', 1, 1
);

INSERT INTO theme_tbl VALUES (
  NULL, 'OpenClinic Wizard',
  '#3299cc', 'verdana, helvetica, sans-serif', 18, 'N', 'white', 'left',
  '#3299cc', 'arial, helvetica, sans-serif', 9, 'white', 'white', 'yellow',
  '#dcdcdc', 'verdana, helvetica, sans-serif', 10, 'black', '#8f8fbd',
  '#99cc32', 'arial, helvetica, sans-serif', 9, 'Y', 'white', '#8f8fbd',
  '#3cb371', 2, 5
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Izhal', '#004284',
  'verdana, helvetica, sans-serif', 24, 'N', 'white', 'left',
  '#004284', 'verdana, helvetica, sans-serif', 8, 'white', '#f5f5dc', '#ffcc00',
  '#336699', 'verdana, helvetica, sans-serif', 9, 'white', '#f5f5dc',
  '#80a3c5', 'verdana, helvetica, sans-serif', 9, 'Y', '#f8f8ff', '#f8f8ff',
  '#ffcc00', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Invision', '#f8f8ff',
  'verdana, helvetica, sans-serif', 24, 'N', 'black', 'left',
  '#e8f0f8', 'verdana, helvetica, sans-serif', 8, 'black', '#8a2be2', '#d2691e',
  '#e8ecf8', 'verdana, helvetica, sans-serif', 9, 'black', '#8a2be2',
  '#a0bce0', 'verdana, helvetica, sans-serif', 9, 'Y', 'black', 'black',
  '#385488', 1, 2
);
