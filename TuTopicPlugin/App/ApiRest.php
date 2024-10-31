<?php namespace TuTopicPlugin\App;

/**
* @package     ApiRest
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

use TuTopicPlugin\Popalo\Opalo\EndpointsManager;

/*
 *
 */

class ApiRest {

  protected static $items = [
    'notifications' => [//wp-json/v2/notifications
      'namespace' => 'v2',
      'path' => 'notifications',
      'method' => 'POST',
      'callback' => [\TuTopicPlugin\App\Endpoints\AddNotification::class,'handler']
    ],
    'delivery-approve' => [//wp-json/v2/delivery-approve
      'namespace' => 'v2',
      'path' => 'delivery-approve',
      'method' => 'POST',
      'callback' => [\TuTopicPlugin\App\Endpoints\ApproveDelivery::class,'handler']
    ],
    /*'test-wp-query' => [//wp-json/v2/test-wp-query
      'namespace' => 'v2',
      'path' => 'test-wp-query',
      'method' => 'POST',
      'callback' => [\TuTopicPlugin\App\Endpoints\TestWpQuery::class,'handler']
    ],*/
  ];

  public static function init() {
    $items = Self::$items;
    foreach ($items as $itemKey => $item) {
      $items[$itemKey]['callback'][0] = new $item['callback'][0]();
    }
    $endpoint_manager = new EndpointsManager($items);
    $endpoint_manager->executeEndpoints();
  }

}
