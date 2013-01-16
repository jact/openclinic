# File to upgrade OpenClinic from 0.7 to 0.8
# After use this, you can delete it

ALTER TABLE deleted_patient_tbl ADD id_member INT UNSIGNED NULL AFTER insurance_company;
ALTER TABLE deleted_patient_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

ALTER TABLE deleted_problem_tbl ADD id_member INT UNSIGNED NULL AFTER id_patient;
ALTER TABLE deleted_problem_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

DELETE FROM theme_tbl;

INSERT INTO theme_tbl (id_theme, theme_name, css_file) VALUES (
  1, 'OpenClinic', 'openclinic.css'
);

ALTER TABLE setting_tbl DROP clinic_image_url, DROP use_image;

UPDATE setting_tbl SET version='0.8.20130107', id_theme=1;

UPDATE user_tbl SET id_theme=1;

ALTER TABLE patient_tbl MODIFY surname2 VARCHAR(30) NULL DEFAULT '';
ALTER TABLE deleted_patient_tbl MODIFY surname2 VARCHAR(30) NULL DEFAULT '';
ALTER TABLE staff_tbl MODIFY surname2 VARCHAR(30) NULL DEFAULT '';

# BLOB/TEXT columns cannot have default values

ALTER TABLE deleted_patient_tbl MODIFY address TEXT NULL;
ALTER TABLE deleted_patient_tbl MODIFY phone_contact TEXT NULL;

ALTER TABLE patient_tbl MODIFY address TEXT NULL;
ALTER TABLE patient_tbl MODIFY phone_contact TEXT NULL;

ALTER TABLE staff_tbl MODIFY address TEXT NULL;
ALTER TABLE staff_tbl MODIFY phone_contact TEXT NULL;

# Change storage engine!!!

ALTER TABLE access_log_tbl ENGINE=MyISAM;
ALTER TABLE connection_problem_tbl ENGINE=MyISAM;
ALTER TABLE deleted_patient_tbl ENGINE=MyISAM;
ALTER TABLE deleted_problem_tbl ENGINE=MyISAM;
ALTER TABLE history_tbl ENGINE=MyISAM;
ALTER TABLE medical_test_tbl ENGINE=MyISAM;
ALTER TABLE patient_tbl ENGINE=MyISAM;
ALTER TABLE problem_tbl ENGINE=MyISAM;
ALTER TABLE record_log_tbl ENGINE=MyISAM;
ALTER TABLE relative_tbl ENGINE=MyISAM;
ALTER TABLE session_tbl ENGINE=MyISAM;
ALTER TABLE setting_tbl ENGINE=MyISAM;
ALTER TABLE staff_tbl ENGINE=MyISAM;
ALTER TABLE theme_tbl ENGINE=MyISAM;
ALTER TABLE user_tbl ENGINE=MyISAM;
