# File to upgrade OpenClinic from 0.7 to 0.8
# After use this, you can delete it

ALTER TABLE deleted_patient_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

ALTER TABLE deleted_problem_tbl ADD collegiate_number VARCHAR(20) NULL AFTER id_member;

UPDATE setting_tbl SET version='0.8.20070129';
