# File to upgrade OpenClinic from 0.5 to 0.6
# After use this, you can delete it and don't forget change all user's passwords
# Password of admin user is admin again

ALTER TABLE setting_tbl MODIFY version VARCHAR(14) NOT NULL;

UPDATE setting_tbl SET version='0.6.20031218';

ALTER TABLE access_log_tbl ADD PRIMARY KEY (id_user,access_date);

ALTER TABLE record_log_tbl ADD KEY id_user (id_user);

ALTER TABLE problem_tbl ADD last_update_date DATE NOT NULL AFTER id_problem;

UPDATE problem_tbl SET last_update_date=curdate() WHERE last_update_date='0000-00-00';

ALTER TABLE deleted_problem_tbl ADD last_update_date DATE NOT NULL AFTER id_problem;

UPDATE deleted_problem_tbl SET last_update_date=curdate() WHERE last_update_date='0000-00-00';

ALTER TABLE deleted_patient_tbl CHANGE wife_childs_status_health spouse_childs_status_health TEXT NULL;

ALTER TABLE history_tbl CHANGE wife_childs_status_health spouse_childs_status_health TEXT NULL;

ALTER TABLE problem_tbl CHANGE subjetive subjective TEXT NULL;

ALTER TABLE deleted_problem_tbl CHANGE subjetive subjective TEXT NULL;

ALTER TABLE problem_tbl CHANGE objetive objective TEXT NULL;

ALTER TABLE deleted_problem_tbl CHANGE objetive objective TEXT NULL;

ALTER TABLE staff_tbl CHANGE sur_name1 surname1 VARCHAR(30) NOT NULL;

ALTER TABLE staff_tbl CHANGE sur_name2 surname2 VARCHAR(30) NOT NULL;

ALTER TABLE patient_tbl CHANGE sur_name1 surname1 VARCHAR(30) NOT NULL;

ALTER TABLE patient_tbl CHANGE sur_name2 surname2 VARCHAR(30) NOT NULL;

ALTER TABLE deleted_patient_tbl CHANGE sur_name1 surname1 VARCHAR(30) NOT NULL;

ALTER TABLE deleted_patient_tbl CHANGE sur_name2 surname2 VARCHAR(30) NOT NULL;

ALTER TABLE theme_tbl
  MODIFY theme_name VARCHAR(60) NOT NULL,
  MODIFY title_bg_color VARCHAR(30) NOT NULL,
  MODIFY title_font_family TEXT NOT NULL,
  MODIFY title_font_size TINYINT UNSIGNED NOT NULL DEFAULT 14,
  MODIFY title_font_color VARCHAR(30) NOT NULL,
  MODIFY title_align ENUM('left','right','center') NOT NULL DEFAULT 'left',
  MODIFY body_bg_color VARCHAR(30) NOT NULL,
  MODIFY body_font_family TEXT NOT NULL,
  MODIFY body_font_size TINYINT UNSIGNED NOT NULL DEFAULT 10,
  MODIFY body_font_color VARCHAR(30) NOT NULL,
  MODIFY body_link_color VARCHAR(30) NOT NULL,
  MODIFY error_color VARCHAR(30) NOT NULL,
  MODIFY navbar_bg_color VARCHAR(30) NOT NULL,
  MODIFY navbar_font_family TEXT NOT NULL,
  MODIFY navbar_font_size TINYINT UNSIGNED NOT NULL DEFAULT 10,
  MODIFY navbar_font_color VARCHAR(30) NOT NULL,
  MODIFY navbar_link_color VARCHAR(30) NOT NULL,
  MODIFY tab_bg_color VARCHAR(30) NOT NULL,
  MODIFY tab_font_family TEXT NOT NULL,
  MODIFY tab_font_size TINYINT UNSIGNED NOT NULL DEFAULT 12,
  MODIFY tab_font_color VARCHAR(30) NOT NULL,
  MODIFY tab_link_color VARCHAR(30) NOT NULL,
  MODIFY table_border_color VARCHAR(30) NOT NULL,
  MODIFY table_border_width TINYINT UNSIGNED NOT NULL DEFAULT 1,
  MODIFY table_cell_padding TINYINT UNSIGNED NOT NULL DEFAULT 1;

ALTER TABLE user_tbl MODIFY pwd VARCHAR(32) NOT NULL;

UPDATE user_tbl SET pwd='73850afb68a28c03ef4d2e426634e041' WHERE id_user=1;

UPDATE user_tbl SET pwd=md5('admin') WHERE id_user=2;

INSERT INTO theme_tbl VALUES (
  NULL, 'LibXML',
  '#8fa0c3', 'verdana,helvetica,sans-serif', 14, 'N', '#e6e8fa', 'left',
  '#8fa0c3', 'verdana,helvetica,sans-serif', 8, '#eaeaae', '#ffe4b5', '#f5fffa',
  '#db9370', 'verdana,helvetica,sans-serif', 9, '#eaeaae', '#f5fffa',
  '#ebc79e', 'verdana,helvetica,sans-serif', 9, 'Y', '#7c98d3', '#7c98d3',
  '#e8e5e8', 2, 4
);

INSERT INTO theme_tbl VALUES (
  NULL, 'SerialZ',
  '#9ea985', 'Trebuchet MS,arial,helvetica,sans-serif', 18, 'Y', 'white', 'left',
  '#9ea985', 'Trebuchet MS,arial,helvetica,sans-serif', 10, 'beige', 'white', 'white',
  '#bbc2a9', 'Trebuchet MS,arial,helvetica,sans-serif', 10, 'beige', 'white',
  '#374611', 'Trebuchet MS,arial,helvetica,sans-serif', 10, 'Y', 'beige', 'white',
  '#dce0d3', 1, 3
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Closer',
  '#829eb4', 'verdana,sans-serif', 16, 'N', 'beige', 'left',
  '#829eb4', 'verdana,sans-serif', 8, 'beige', 'yellow', 'white',
  '#99afc1', 'verdana,sans-serif', 8, 'beige', 'yellow',
  '#67859d', 'verdana,sans-serif', 8, 'Y', 'beige', 'gold',
  '#172739', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'GG Interactive',
  'black', 'verdana,arial,sans-serif', 24, 'N', 'white', 'left',
  'white', 'verdana,arial,sans-serif', 10, 'black', '#8784c6', 'crimson',
  '#e9e9f8', 'verdana,arial,sans-serif', 10, 'black', '#8784c6',
  '#c1c1e8', 'verdana,arial,sans-serif', 10, 'Y', 'black', '#8784c6',
  '#8784c6', 1, 4
);
