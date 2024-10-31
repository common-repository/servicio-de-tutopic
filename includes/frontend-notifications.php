<?php

/**
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


/*Notificaciones a nivel de frontend*/

// Notificaciones de Popalo a nivel de frontend
add_action( 'admin_footer', 'ttpcsvc_popalo_js_notifications_ajax' ); // Write our JS below here
function ttpcsvc_popalo_js_notifications_ajax() { ?>

	<script type="text/javascript" >

    	jQuery(document).ready(function($) {

        console.log('[TTPopalo] Notifications are ready.');

        /* El TimeOut es debido a que los dimissers aparecen hasta despues de
          un rato en wordpress */
        setTimeout(function () {

          // Click on "Close" Button
          $('.div-delete-notify').click((evt) => {

            //#console.log('popalo dimiss click', $(evt.target).parent().attr('popalopropid'))

            var idPopalo  = $(evt.target).parent().attr('popalopropid');
            var ttHeader  = $('#tt-header-'+idPopalo).addClass('d-none');
            var ttDiv     = $('#tt-div-'+idPopalo).addClass('d-none');

            var data = {
              'action': 'dimiss_in_popalo',
              'id': idPopalo
            };

            jQuery.post(ajaxurl, data, function(response) { console.log('closed sucessfuly notification!'); });

          });
        }, 10);

    	});
	</script> <?php

}

add_action( 'wp_ajax_dimiss_in_popalo', 'ttpcsvc_popalo_action_dimiss_ajax' );

function ttpcsvc_popalo_action_dimiss_ajax() {
  if (isset($_POST['id']))
    TuTopicPlugin\Popalo\Notifications::seeById(sanitize_key($_POST['id']));
	wp_die(); // this is required to terminate immediately and return a proper response
}

?>
