<?php
/**
 * This file is part of OpenClinic
 *
 * Copyright (c) 2002-2004 jact
 * Licensed under the GNU GPL. For full terms see the file LICENSE.
 *
 * $Id: theme_fields.php,v 1.1 2004/03/20 20:48:08 jact Exp $
 */

/**
 * theme_fields.php
 ********************************************************************
 * Fields of theme data
 ********************************************************************
 * Author: jact <jachavar@terra.es>
 * Last modified: 20/03/04 21:48
 */

  if (str_replace("\\", "/", __FILE__) == $_SERVER['SCRIPT_FILENAME'])
  {
    header("Location: ../index.php");
    exit();
  }
?>

<table>
  <thead>
    <tr>
      <th colspan="5">
        <?php echo $title; ?>
      </th>
    </tr>
  </thead>

  <tbody>
    <tr>
      <td>
        <label for="theme_name"><?php echo _("Theme Name") . ":"; ?></label>
      </td>

      <td colspan="4">
        <?php showInputText("theme_name", 40, 60, $postVars["theme_name"], $pageErrors["theme_name"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="error_color"><?php echo _("Error Color") . ":"; ?></label>
      </td>

      <td colspan="4">
        <?php showInputText("error_color", 10, 30, $postVars["error_color"], $pageErrors["error_color"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="table_border_color"><?php echo _("Table Border Color") . ":"; ?></label>
      </td>

      <td colspan="4">
        <?php showInputText("table_border_color", 10, 30, $postVars["table_border_color"], $pageErrors["table_border_color"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="table_border_width"><?php echo _("Table Border Width") . ":"; ?></label>
      </td>

      <td colspan="4">
        <?php showInputText("table_border_width", 2, 2, $postVars["table_border_width"], $pageErrors["table_border_width"]); ?> px
      </td>
    </tr>

    <tr>
      <td>
        <label for="table_cell_padding"><?php echo _("Table Cell Padding") . ":"; ?></label>
      </td>

      <td colspan="4">
        <?php showInputText("table_cell_padding", 2, 2, $postVars["table_cell_padding"], $pageErrors["table_cell_padding"]); ?> px
      </td>
    </tr>

    <tr>
      <th>
        &nbsp;
      </th>

      <th>
        <?php echo _("Title"); ?>
      </th>

      <th>
        <?php echo _("Tabs"); ?>
      </th>

      <th>
        <?php echo _("Navigation"); ?>
      </th>

      <th>
        <?php echo _("Body"); ?>
      </th>
    </tr>

    <tr>
      <td>
        <label for="title_font_family"><?php echo _("Font Family") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("title_font_family", 10, 200, $postVars["title_font_family"], $pageErrors["title_font_family"]); ?>
      </td>

      <td>
        <?php showInputText("tab_font_family", 10, 200, $postVars["tab_font_family"], $pageErrors["tab_font_family"]); ?>
      </td>

      <td>
        <?php showInputText("navbar_font_family", 10, 200, $postVars["navbar_font_family"], $pageErrors["navbar_font_family"]); ?>
      </td>

      <td>
        <?php showInputText("body_font_family", 10, 200, $postVars["body_font_family"], $pageErrors["body_font_family"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="title_font_size"><?php echo _("Font Size") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("title_font_size", 2, 2, $postVars["title_font_size"], $pageErrors["title_font_size"]); ?> pt

        <?php
          showCheckBox("title_font_bold", "title_font_bold", 1, $postVars["title_font_bold"] != "");
          echo _("bold");
        ?>
      </td>

      <td>
        <?php showInputText("tab_font_size", 2, 2, $postVars["tab_font_size"], $pageErrors["tab_font_size"]); ?> pt

        <?php
          showCheckBox("tab_font_bold", "tab_font_bold", 1, $postVars["tab_font_bold"] != "");
          echo _("bold");
        ?>
      </td>

      <td>
        <?php showInputText("navbar_font_size", 2, 2, $postVars["navbar_font_size"], $pageErrors["navbar_font_size"]); ?> pt
      </td>

      <td>
        <?php showInputText("body_font_size", 2, 2, $postVars["body_font_size"], $pageErrors["body_font_size"]); ?> pt
      </td>
    </tr>

    <tr>
      <td>
        <label for="title_bg_color"><?php echo _("Background Color") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("title_bg_color", 10, 30, $postVars["title_bg_color"], $pageErrors["title_bg_color"]); ?>
      </td>

      <td>
        <?php showInputText("tab_bg_color", 10, 30, $postVars["tab_bg_color"], $pageErrors["tab_bg_color"]); ?>
      </td>

      <td>
        <?php showInputText("navbar_bg_color", 10, 30, $postVars["navbar_bg_color"], $pageErrors["navbar_bg_color"]); ?>
      </td>

      <td>
        <?php showInputText("body_bg_color", 10, 30, $postVars["body_bg_color"], $pageErrors["body_bg_color"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="title_font_color"><?php echo _("Font Color") . ":"; ?></label>
      </td>

      <td>
        <?php showInputText("title_font_color", 10, 30, $postVars["title_font_color"], $pageErrors["title_font_color"]); ?>
      </td>

      <td>
        <?php showInputText("tab_font_color", 10, 30, $postVars["tab_font_color"], $pageErrors["tab_font_color"]); ?>
      </td>

      <td>
        <?php showInputText("navbar_font_color", 10, 30, $postVars["navbar_font_color"], $pageErrors["navbar_font_color"]); ?>
      </td>

      <td>
        <?php showInputText("body_font_color", 10, 30, $postVars["body_font_color"], $pageErrors["body_font_color"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="tab_link_color"><?php echo _("Link Color") . ":"; ?></label>
      </td>

      <td>
        &nbsp;
      </td>

      <td>
        <?php showInputText("tab_link_color", 10, 30, $postVars["tab_link_color"], $pageErrors["tab_link_color"]); ?>
      </td>

      <td>
        <?php showInputText("navbar_link_color", 10, 30, $postVars["navbar_link_color"], $pageErrors["navbar_link_color"]); ?>
      </td>

      <td>
        <?php showInputText("body_link_color", 10, 30, $postVars["body_link_color"], $pageErrors["body_link_color"]); ?>
      </td>
    </tr>

    <tr>
      <td>
        <label for="title_align"><?php echo _("Text Align") . ":"; ?></label>
      </td>

      <td>
        <?php
          $align = null;
          $align = array(
            "left" => "left",
            "center" => "center",
            "right" => "right"
          );

          showSelectArray("title_align", $align, $postVars["title_align"]);
          unset($align);
        ?>
      </td>

      <td colspan="3">
        &nbsp;
      </td>
    </tr>

    <tr>
      <td class="center" colspan="5">
        <?php
          showInputButton("button1", _("Submit"), "button", 'onclick="editTheme()"');
          showInputButton("button2", _("Reset"), "reset");
          showInputButton("return", _("Return"), "button", 'onclick="parent.location=\'' . $returnLocation . '\'"');
        ?>
      </td>
    </tr>
  </tbody>
</table>
