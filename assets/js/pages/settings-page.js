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


console.log('settings-page.js loaded!');

jQuery(document).ready(function($) {

  function hideOrShowSelectUser() {
    console.log('is show/hiding select user');
    if ($("#contribuite_to_original_author").val() == 1) {
      $( "#association_user_div" ).addClass( "d-none" )
      $( "#contribuite_to_original_author_div" ).removeClass( "col-md-6" )//
    }else{
      $( "#association_user_div" ).removeClass( "d-none" )
      $( "#contribuite_to_original_author_div" ).addClass( "col-md-6" )//
    }
  }

  hideOrShowSelectUser();

  // Select Urgente
  $("#contribuite_to_original_author").on('change', (evt) => {
    hideOrShowSelectUser();
  });

});
