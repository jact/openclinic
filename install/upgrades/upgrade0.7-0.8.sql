# File to upgrade OpenClinic from 0.7 to 0.8
# After use this, you can delete it

ALTER TABLE deleted_patient_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

ALTER TABLE deleted_problem_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

DELETE FROM theme_tbl;

INSERT INTO theme_tbl (id_theme, theme_name, css_file) VALUES (
  1, 'OpenClinic', 'openclinic.css'
);

ALTER TABLE setting_tbl DROP clinic_image_url, DROP use_image;

UPDATE setting_tbl SET version='0.8.20071212', id_theme=1;

UPDATE user_tbl SET id_theme=1;
