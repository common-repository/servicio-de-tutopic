<?php  namespace TuTopicPlugin\App\Endpoints;

/**
* @package     AddNotification
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

use WP_REST_Posts_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;
use TuTopicPlugin\Popalo\Notifications;

class AddNotification
{
	public function handler($data)
	{

		if (!get_option('association_ready'))
			return new WP_REST_Response([
				'success' => false,
				'message'	=> 'Este sitio no se encuentra vinculado a ninguna cuenta de TuTopic.',
				'desactive_site'	=> true	//TuTopic desactivara el sitio y dejara de enviarle notificaciones
			], 403);

		$apikey = $data->get_header('TuTopic-Api-Key');
		if (!$apikey)
			return new WP_REST_Response([
				'success' => false,
				'message'	=> 'No se encontro api key de tutopic en la peticion.'
			], 403);


		if ($apikey !== get_option('association_code'))
			return new WP_REST_Response([
	      'success' => false,
				'message'	=> 'La api Key de TuTopic no coincide',
				'desactive_site'	=> true	//TuTopic desactivara el sitio y dejara de enviarle notificaciones
	    ], 403);

		if (!isset($data['message']) || !isset($data['class']))
			return new WP_REST_Response([
				'success' => false,
				'message'	=> "Los campos 'message' y 'class' son requeridos."
			], 400);

		// Por lo pronto notificaciones deshabilitadas
		/*return [
			'success' => false,
			'message'	=> 'Notificaciones desactivadas!'
		];*/


		if (!is_string($data['message']))  {
			$messageNotification = (array) $data['message'];
			$messageNotification = array_map( 'sanitize_text_field', $messageNotification );
		}else{
			$messageNotification = sanitize_text_field($data['message']);
		}

		Notifications::create(
			$messageNotification,
			sanitize_text_field($data['class']),
			sanitize_text_field($data['message_full'])
		);

    return [
      'success' => true,
			'message'	=> 'Agregada con exito!'
    ];

	}
}
