# File to upgrade OpenClinic from 0.1 to 0.2
# After use this, you can delete it

UPDATE setting_tbl SET version='0.2';

CREATE TABLE deleted_patient_tbl (
  id_patient INTEGER NOT NULL,
  nif VARCHAR(20) NULL,
  first_name VARCHAR(25) NOT NULL,
  sur_name1 VARCHAR(30) NOT NULL,
  sur_name2 VARCHAR(30) NOT NULL,
  address TEXT NULL DEFAULT '',
  phone_contact TEXT NULL DEFAULT '',
  sex ENUM('V','H') NOT NULL DEFAULT 'V',
  race VARCHAR(25) NULL,
  birth_date DATE NULL,
  birth_place VARCHAR(40) NULL,
  decease_date DATE NULL,
  nts VARCHAR(30) NULL,
  nss VARCHAR(30) NULL,
  family_situation TEXT NULL,
  labour_situation TEXT NULL,
  education TEXT NULL,
  insurance_company VARCHAR(30) NULL,
  collegiate_number VARCHAR(20) NULL,
  birth_growth TEXT NULL,
  growth_sexuality TEXT NULL,
  feed TEXT NULL,
  habits TEXT NULL,
  peristaltic_conditions TEXT NULL,
  psychological TEXT NULL,
  children_complaint TEXT NULL,
  venereal_disease TEXT NULL,
  accident_surgical_operation TEXT NULL,
  medicinal_intolerance TEXT NULL,
  mental_illness TEXT NULL,
  parents_status_health TEXT NULL,
  brothers_status_health TEXT NULL,
  wife_childs_status_health TEXT NULL,
  family_illness TEXT NULL,
  create_date DATETIME NOT NULL,
  id_user INTEGER(5) NOT NULL,
  login VARCHAR(20) NOT NULL
);

CREATE TABLE deleted_problem_tbl (
  id_problem INTEGER NOT NULL,
  id_patient INTEGER NOT NULL,
  collegiate_number VARCHAR(20) NULL,
  order_number TINYINT NOT NULL,
  opening_date DATE NOT NULL,
  closing_date DATE NULL,
  meeting_place VARCHAR(40) NULL,
  wording TEXT NOT NULL,
  subjetive TEXT NULL,
  objetive TEXT NULL,
  appreciation TEXT NULL,
  action_plan TEXT NULL,
  prescription TEXT NULL,
  create_date DATETIME NOT NULL,
  id_user INTEGER(5) NOT NULL,
  login VARCHAR(20) NOT NULL
);
