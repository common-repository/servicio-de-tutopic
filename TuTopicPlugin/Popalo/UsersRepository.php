<?php namespace TuTopicPlugin\Popalo;

/**
* @package     UsersRepository
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

class UsersRepository {

  public static function create($username, $password, $email = null) {
    return wp_create_user($username, $password, $email);
  }

  public static function update($id, $data) {
    $data['ID'] = $id;
    return wp_update_user($data);
  }

  public static function createExtends($username, $password, $email = null, $data = null) {
    $id = Self::create($username, $password, $email);
    if ($data)
      Self::update($id, $data);
    return $id;
  }

  public static function createExtendsIfNoExists($username, $password, $email = null, $data = null) {

    if (!Self::existUsername($username) && (!$email || ($email && !Self::existEmail($email))) ) {

      return Self::createExtends($username, $password, $email, $data);

    }else if (Self::existUsername($username)) {

      $user = get_user_by('login', $username);
      if ($user && $user->ID) return $user->ID;

    }else if ($email && Self::existEmail($email)) {

      $user = get_user_by('email', $email);
      if ($user && $user->ID) return $user->ID;

    }

    return null;

  }

  public static function systemCreate($username, $email = null, $data = null) {

    $dataUser = [
      'user_url'      => TUTOPIC_BASE_URL,
      'first_name'    => $username,
      'user_nicename' => $username,
      //...$data
    ];

    return Self::createExtendsIfNoExists($username, sha1('password_secret_'.uniqid()), $email, $dataUser);

  }

  public static function existUsername($username) {
    return username_exists($username);
  }

  public static function existEmail($email) {
    return email_exists($email);
  }

}

?>
