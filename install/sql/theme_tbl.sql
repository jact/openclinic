/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_tbl.sql,v 1.2 2004/04/18 14:22:25 jact Exp $
 */

/**
 * theme_tbl.sql
 ********************************************************************
 * Change this
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 */

CREATE TABLE theme_tbl (
  id_theme SMALLINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  theme_name VARCHAR(60) NOT NULL,
  title_bg_color VARCHAR(30) NOT NULL,
  title_font_family TEXT NOT NULL,
  title_font_size TINYINT UNSIGNED NOT NULL DEFAULT 14,
  title_font_bold ENUM('N','Y') NOT NULL DEFAULT 'N',
  title_font_color VARCHAR(30) NOT NULL,
  title_align ENUM('left','right','center') NOT NULL DEFAULT 'left',
  body_bg_color VARCHAR(30) NOT NULL,
  body_font_family TEXT NOT NULL,
  body_font_size TINYINT UNSIGNED NOT NULL DEFAULT 10,
  body_font_color VARCHAR(30) NOT NULL,
  body_link_color VARCHAR(30) NOT NULL,
  error_color VARCHAR(30) NOT NULL,
  navbar_bg_color VARCHAR(30) NOT NULL,
  navbar_font_family TEXT NOT NULL,
  navbar_font_size TINYINT UNSIGNED NOT NULL DEFAULT 10,
  navbar_font_color VARCHAR(30) NOT NULL,
  navbar_link_color VARCHAR(30) NOT NULL,
  tab_bg_color VARCHAR(30) NOT NULL,
  tab_font_family TEXT NOT NULL,
  tab_font_size TINYINT UNSIGNED NOT NULL DEFAULT 12,
  tab_font_bold ENUM('N','Y') NOT NULL DEFAULT 'N',
  tab_font_color VARCHAR(30) NOT NULL,
  tab_link_color VARCHAR(30) NOT NULL,
  table_border_color VARCHAR(30) NOT NULL,
  table_border_width TINYINT UNSIGNED NOT NULL DEFAULT 1,
  table_cell_padding TINYINT UNSIGNED NOT NULL DEFAULT 1
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Metalic Grey',
  '#efefef', 'arial,helvetica,sans-serif', 20, 'N', '#000000', 'left',
  '#f0f0f0', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa', '#990000',
  '#e0e0e0', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa',
  '#c9cfde', 'verdana,arial,helvetica,sans-serif', 9, 'Y', '#000000', '#000000',
  '#000000', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Mossy Blue',
  '#7695C0', 'arial,helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#ffffff', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa', '#990000',
  '#CCCC99', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa',
  '#003366', 'verdana,arial,helvetica,sans-serif', 9, 'Y', '#ffffff', '#ffffff',
  '#000000', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Arizona Dessert',
  '#dfa955', 'arial,helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#ffffff', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#af6622', '#990000',
  '#c0c0c0', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#bf7733',
  '#c05232', 'verdana,arial,helvetica,sans-serif', 9, 'Y', '#ffffff', '#ffffff',
  '#000000', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Blue and Yellow',
  '#ffffff', 'arial,helvetica,sans-serif', 20, 'N', '#000000', 'left',
  '#ffffff', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa', '#990000',
  '#f0f0d5', 'verdana,arial,helvetica,sans-serif', 9, '#000000', '#0000aa',
  '#495fa8', 'verdana,arial,helvetica,sans-serif', 9, 'Y', '#ffffdb', '#ffffdb',
  '#000000', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Dark Wood',
  '#551122', 'arial,helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#000000', 'arial,helvetica,sans-serif', 9, '#ffffff', '#ffff99', 'crimsom',
  '#393333', 'arial,helvetica,sans-serif', 9, '#ffffff', '#ffff99',
  '#999080', 'arial,helvetica,sans-serif', 9, 'Y', '#ffffff', '#ffffff',
  '#a9a090', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Midnight',
  '#222255', 'arial,helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#000000', 'arial,helvetica,sans-serif', 9, '#b5b5db', '#ffff99', '#00ff00',
  '#999999', 'arial,helvetica,sans-serif', 9, '#ffffff', '#ffff99',
  '#8585ab', 'arial,helvetica,sans-serif', 9, 'N', '#ffffff', '#ffffff',
  '#b5b5db', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'XP Style',
  '#ebebee', 'Trebuchet MS,arial,serif', 21, 'N', '#4d9fe1', 'left',
  '#ebebee', 'tahoma,verdana,sans-serif', 9, '#4977b4', '#31a431', '#de5c2f',
  '#c9c7ba', 'verdana,serif', 8, '#ebebee', '#ffff9b',
  '#6487dc', 'verdana,serif', 10, 'Y', '#ebebee', '#e6ead8',
  '#0046d5', 2, 3
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Autumn Violets',
  '#494994', 'arial,helvetica,sans-serif', 20, 'N', '#ffffff', 'left',
  '#494994', 'arial,helvetica,sans-serif', 9, '#ffffff', '#ffcc00', '#de5c2f',
  '#000080', 'verdana,arial,helvetica,sans-serif', 9, '#ffffff', '#ffcc00',
  '#0058b0', 'verdana,arial,helvetica,sans-serif', 9, 'Y', '#ffffff', '#ffffff',
  'black', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Thai AppServ',
  'white', 'arial,helvetica.sans-serif', 18, 'Y', 'black', 'left',
  'white', 'verdana,arial,helvetica,sans-serif', 8, 'black', '#0000a0', '#ff0000',
  '#f4faff', 'arial,helvetica,sans-serif', 10, 'black', '#0000a0',
  '#d3d9ee', 'arial,helvetica,sans-serif', 10, 'Y', 'black', '#0000a0',
  '#a3a3d1', 1, 1
);

INSERT INTO theme_tbl VALUES (
  NULL, 'OpenClinic Wizard',
  '#3299cc', 'verdana,helvetica,sans-serif', 18, 'N', 'white', 'left',
  '#3299cc', 'arial,helvetica,sans-serif', 9, 'white', 'white', 'yellow',
  '#dcdcdc', 'verdana,helvetica,sans-serif', 10, 'black', '#8f8fbd',
  '#99cc32', 'verdana,helvetica,sans-serif', 11, 'Y', 'white', '#8f8fbd',
  '#3cb371', 2, 5
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Izhal',
  '#004284', 'verdana,helvetica,sans-serif', 24, 'N', 'white', 'left',
  '#004284', 'verdana,helvetica,sans-serif', 8, 'white', '#f5f5dc', '#ffcc00',
  '#336699', 'verdana,helvetica,sans-serif', 9, 'white', '#f5f5dc',
  '#80a3c5', 'verdana,helvetica,sans-serif', 9, 'Y', '#f8f8ff', '#f8f8ff',
  '#ffcc00', 1, 2
);

INSERT INTO theme_tbl VALUES (
  NULL, 'Invision',
  '#f8f8ff', 'verdana,helvetica,sans-serif', 24, 'N', 'black', 'left',
  '#e8f0f8', 'verdana,helvetica,sans-serif', 8, 'black', '#8a2be2', '#d2691e',
  '#e8ecf8', 'verdana,helvetica,sans-serif', 9, 'black', '#8a2be2',
  '#a0bce0', 'verdana,helvetica,sans-serif', 9, 'Y', 'black', 'black',
  '#385488', 1, 2
);

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

INSERT INTO theme_tbl VALUES (
  NULL, 'mezzoblue',
  '#ff4931', 'verdana,tahoma,sans-serif', 18, 'Y', '#fff', 'left',
  '#b5cff7', 'verdana,tahoma,sans-serif', 8, '#214973', '#48618b', '#fff',
  '#dee8f8', 'verdana,tahoma,sans-serif', 9, '#214973', '#48618b',
  '#7da4d4', 'verdana,tahoma,sans-serif', 9, 'N', '#fff', '#e5ecf8',
  '#a5baf7', 1, 3
);
