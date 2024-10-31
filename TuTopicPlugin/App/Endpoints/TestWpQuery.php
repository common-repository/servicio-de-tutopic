<?php  namespace TuTopicPlugin\App\Endpoints;

/**
* @package     TestWpQuery
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
use TuTopicPlugin\Service\PostService;

use WP_Query;

class TestWpQuery
{

	public function handler($data)
	{
		$query 			= new WP_Query([
									'post_type'   => 'tutopic_orders',
									'meta_key'    => 'tt_order',
									'meta_query'  => [
									[
										'key'     => 'tt_order',
										'compare' => '=',
										'value'   => '100',
									]],
									'post_status' => array('publish', 'pending', 'draft', 'auto-draft', 'future', 'private', 'inherit', 'trash')
								]);

		if ($query->have_posts()) {
			echo 'HAVE';
		}else{
			echo 'NOT HAVE';
		}

    return ['testing'];

	}

}
