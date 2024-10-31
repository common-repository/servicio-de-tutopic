<?php  namespace TuTopicPlugin\App\Helpers\OldImplementations;

/**
* @package     MediasLocal (Implementacion antigua)
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

//use TuTopicPlugin\App\Helpers\MediasLocal;
class MediasLocal
{

    // Obtener todas
    public static function get() {
      $data = get_option('medias_local');
      if (!$data || !is_array($data)) $data = [];
      return $data;
    }

    // Establecer todas
    public static function set($data = []) {
      return update_option('medias_local', $data);
    }

    // Agregar elemento
    public static function add($item) {
      $data   = Self::get();
      $data[] = $item;
      return update_option('medias_local', $data);
    }

    // Obtener "Media" por medio de su link de origen
    public static function getMedia($origin) {
      $items   = Self::get();

      foreach ($items as $item)
        if ($item['origin'] == $origin)
          return $item;

      return null;
    }

    // Agregar Origin de "Media" [Args]
    public static function putMedia($media) {
      if (Self::getMedia($media['origin'])) {
        //Existe
        $items = Self::get();
        foreach ($items as $itemKey => $item) {
          if ($item['origin'] == $media['origin']) {
            $items[$itemKey] = $media;
          }
        }
        return Self::set($items);
      }else{
        //No existe
        return Self::add($media);
      }
      return false;
    }

}
