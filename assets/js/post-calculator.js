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

console.log('TuTopicCalculatorPost Loaded!')

const TuTopicCalculatorPost = {

  // Nuevo algoritmo redactores
  getPostCostRedactor: (length, qualityLevel = 'junior')  => {
    return TuTopicCalculatorPost.getPostCost(qualityLevel, true);
  },

  // Nuevo algoritmo
  getPostCost: (length, qualityLevel = 'junior', isRedactor = false) => {
    // Creditos
    var credits = 0;
    console.log('CALCULATE TO ' + length + ' AND ' + qualityLevel)
    // Precio de cada palabra
    var wordPrice = TuTopicConfigDataHelper.priceWord(qualityLevel, isRedactor);
    console.log(wordPrice)

    // Calculo de creditos
    credits = wordPrice * length;

    // Retornar creditos
    return credits;
  },

  // Calcular % de recargo
  getRecargoPercentage: (day_less, quality_level_needed)  => {
    return TuTopicConfigDataHelper.surchargeByDayLess(day_less);
  },

  // Calcular coste con recargo
  getRecargoCost: (cost, recargo)  => {
    var new_cost = 0;
    var mp_recargo = recargo/100;
    new_cost = cost + cost*mp_recargo;
    return new_cost;
  },

  // Calcular coste Adicional de Redactor corrector
  getExtraCostCorrector: (need_corrector, quality_level) => {
    //TODO: Refactorizar agregar el 0.8 a un ajuste personalizado en tutopic.com que venga en public data
    let extraCost = 0;
    if (quality_level === 'junior') {
      // Si es de calidad Junior (Donde el corrector NO es gratis)
      if (need_corrector) extraCost += 0.8;
    }
    return extraCost;
  },

  // Redondear
  redondear: (number) => {
    return Math.round(number*100)/100;
  },

  // Obtener dias de trabajo
  getDaysToWork: (qualityLevel, day_less) => {
    return TuTopicConfigDataHelper.getBaseDaysToWork(qualityLevel) - day_less;
  }

};
