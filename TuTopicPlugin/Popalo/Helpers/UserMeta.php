<?php namespace TuTopicPlugin\Popalo\Helpers;

/**
* @package     UserMeta
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
* Opalo Disclaimer:
* Se uso a una derivacion no-oficial de "Opalo Framework" en una version inferior 1.3 (desarrollado por Nextscale) llamado "Popalo" adaptada a plugins para la realizacion del plugin "Servicio de TuTopic en Wordpress", sin embargo "Opalo Framework", aun no esta licenciado oficialmente y en un futuro se piensa oficalizar bajo la licencia MIT. este Disclaimer fue escrito originalmente en Español en 2021.
* Este archivo es una derivacion originalmente desarrollada para "Opalo Framework"
*
*/

/*
  UserMeta Helper
*/

use TuTopicPlugin\Popalo\Helpers\Utils;

class UserMeta {

  public static function get($key = null) {
    $current_user_id    = get_current_user_id();
    if (!$key)
      $result = get_user_meta($current_user_id);
    $result = get_user_meta($current_user_id, $key, true);
    return $result;
  }

  public static function set($key, $value) {
    if (Self::get($key)) {
      Self::update($key, $value);
    }else{
      Self::add($key, $value);
    }
  }

  public static function update($key, $value) {
    $current_user_id    = get_current_user_id();
    return update_user_meta($current_user_id, $key, Utils::sanitize_dictionary_or_text($value));
  }

  public static function add($key, $value) {
    $current_user_id    = get_current_user_id();
    return add_user_meta($current_user_id, $key, Utils::sanitize_dictionary_or_text($value), true);
  }

  public static function delete($key) {
    $current_user_id    = get_current_user_id();
    delete_user_meta($current_user_id, $key);
  }

  // Notices

  const metaNamespace = 'tutmeta_';

  public static function addNotices($type, $value) {
    $noticeName = Self::metaNamespace.'notice_'.$type;
    return Self::set($noticeName, $value);
  }

  public static function getNotices($type) {
    $noticeName = Self::metaNamespace.'notice_'.$type;
    return Self::get($noticeName);
  }

  public static function deleteNotices($type) {
    $noticeName = Self::metaNamespace.'notice_'.$type;
    return Self::delete($noticeName);
  }

}

?>
