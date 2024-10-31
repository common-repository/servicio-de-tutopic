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


console.log('TuTopicConfigDataHelper Loaded!')

const TuTopicConfigDataHelper = {

  public_data: null,

  setPublicData: function (public_data) {
    this.public_data = JSON.parse(public_data);
    console.log(this.public_data);
  },

  // Obtener el precio de la palabra segun el nivel de calidad
  priceWord: function (qualityLevel = 'junior', isRedactor = false)
  {

    // Si no hay publicConfigData cargada...
    if (!this.public_data) return null;
    let pricesWords = null;

    // Busca el objeto de precios dependiente si es para el redactor o para el cliente
    if (isRedactor)
      pricesWords = Object.assign({}, this.public_data.orders.qualityWordPriceRedactor);
    else
      pricesWords = Object.assign({}, this.public_data.orders.qualityWordPrice);

    // Indexa segun el nivel de calidad
    return pricesWords[qualityLevel];
  },

  // Obtener el precio de la palabra segun el nivel de calidad
  surchargeByDayLess: function (day_less)
  {
    // Si no hay publicConfigData cargada...
    if (!this.public_data) return null;

    let pricesWords = null;

    // Busca el objeto de precios
    pricesWords = Object.assign({}, this.public_data.orders.days_less_surcharge);

    // Indexa segun los dias menos
    return pricesWords[day_less];
  },

  // Obtener dias de trabajo segun nivel de calidad
  getBaseDaysToWork: function (qualityLevel = 'junior') {
    return this.public_data.orders.days_to_work[qualityLevel];
  }

};
