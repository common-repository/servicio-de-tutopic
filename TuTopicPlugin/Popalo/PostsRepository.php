<?php namespace TuTopicPlugin\Popalo;

/**
* @package     PostsRepository
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

use TuTopicPlugin\Popalo\UsersRepository;

class PostsRepository {

  public static function createAssocDraft($title, $content, $postType = 'post', $status = 'draft', $autorOriginal = null) {

    $user = null;

    if (get_option('contribuite_to_original_author')) {
      if ($autorOriginal) {
        $user = UsersRepository::systemCreate($autorOriginal);
      }
    }

    if (!$user) {
      $user = get_option('association_user');
      if (!$user) $user = get_current_user_id();
    }

    $my_post = array(
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => $content,
      'post_status'   => $status,
      'post_author'   => $user,
      //'post_category' => null,
      'post_type'     => $postType
    );

    // Insert the post into the database
    $wpInsertedPost = wp_insert_post( $my_post );
    return $wpInsertedPost;

  }

  public static function updateAssocDraft($postID, $title, $content) {

    $my_post = array(
      'ID'            => $postID,
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => $content,
    );

    $wpEditedPost = wp_update_post( $my_post );
    return $wpEditedPost;

  }

}

?>
