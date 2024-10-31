<?php  namespace TuTopicPlugin\App\Helpers;

/**
* @package     MediasLocal
* @copyright   (C) 2020 - 2021 Tutopic
* @license     GNU General Public License v2 or later
* @license     http://www.gnu.org/licenses/gpl-2.0.html
* @author      Tutopic
*
* This file is part of "Tutopic para WordPress".
* "Tutopic para WordPress" is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License or (at your option) any later version.
* "Tutopic para WordPress" is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
*
*/

use TuTopicPlugin\App\Helpers\MLocal;
//use TuTopicPlugin\App\Helpers\MediasLocal;

class MediasLocal
{


    // Inicializar
    public static function initialize() {
      return MLocal::initialize();
    }

    // Obtener todas
    public static function get() {
      return MLocal::get();
    }

    // Agregar elemento
    public static function add($item) {
      return MLocal::add($item);
    }

    // Obtener "Media" por medio de su link de origen
    public static function getMedia($origin) {
      $media = MLocal::getMedia($origin);
      if ($media && isset($media['attachment_id']) && $media['attachment_id'] && is_attachment($media['attachment_id']))
        return $media;
      return null;
    }

    // Agregar Origin de "Media" [Args]
    public static function putMedia($media) {
      return MLocal::putMedia($media);
    }

}
