# File to upgrade OpenClinic from 0.6 to 0.7
# After use this, you can delete it

UPDATE setting_tbl SET version='0.7.20040424';

INSERT INTO theme_tbl VALUES (
  NULL, 'mezzoblue',
  '#ff4931', 'verdana,tahoma,sans-serif', 18, 'Y', '#fff', 'left',
  '#b5cff7', 'verdana,tahoma,sans-serif', 8, '#214973', '#48618b', '#fff',
  '#dee8f8', 'verdana,tahoma,sans-serif', 9, '#214973', '#48618b',
  '#7da4d4', 'verdana,tahoma,sans-serif', 9, 'N', '#fff', '#e5ecf8',
  '#a5baf7', 1, 3
);
