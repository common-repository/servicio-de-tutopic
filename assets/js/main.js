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

console.log('main.js loaded!');


jQuery(document).ready(function($) {

  $('.disabled-after-click').click(function(evt) {
    console.log('You click!, now disabled!.');
    setTimeout(() => {
      $(this).attr('disabled', true);
      setTimeout(() => {
        $(this).attr('disabled', false);
      }, 3000);
    });
  });

});
