# File to upgrade OpenClinic from 0.3 to 0.4
# After use this, you can delete it

UPDATE setting_tbl SET version='0.4';

INSERT INTO theme_tbl VALUES (
  NULL, 'XP Style',
  '#ebebee', 'Trebuchet MS,Arial,serif', 21, 'N', '#4d9fe1', 'left',
  '#ebebee', 'Tahoma,verdana,sans-serif', 9, '#4977b4', '#31a431', '#de5c2f',
  '#c9c7ba', 'Verdana', 8, '#0046d5', '#ffff9b',
  '#6487dc', 'Franklin Gothic Medium,serif', 8, 'Y', '#ebebee', '#e6ead8',
  '#0046d5', 2, 3
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Autumn Violets',
  '#494994', 'Arial,Helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#494994', 'arial', 9, '#ffffff', '#ffcc00', '#de5c2f',
  '#000080', 'arial', 9, '#ffffff', '#ffcc00',
  '#0058b0', 'verdana,arial,helvetica', 9, 'Y', '#ffffff', '#ffffff',
  'black', 1, 2
);
