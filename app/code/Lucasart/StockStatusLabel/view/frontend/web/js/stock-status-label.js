/**
 * This file is part of the StockStatusLabel package.
 *
 * (c) Luca Sculco <sculco.luca@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

define([
    'jquery',
    'Magento_Swatches/js/swatch-renderer'
], function ($) {

    var selectedOptions = {};
    var childsConfig = {};


    /**
     * Ritorno l'id del prodotto semplice, cosi da poter stampare
     * la stock status label corrispondente.
     * Se non sono state valorizzate tutte le opzioni non stampo nulla.
     */
    var _getSelectedChild = function () {

        var allOptionsSelected = true;

        // visualizzo l'etichetta solo se tutte le opzioni
        // sono state valorizzate
        $('.super-attribute-select').each(function () {

            var str = $(this).attr("name");
            var re = /super_attribute\[(\d+)\]/g;
            var matches = re.exec(str);
            var attributeId = matches[1];

            if (Object.keys(selectedOptions).indexOf(attributeId) === '-1') {
                allOptionsSelected = false;
            }

        });


        if (!allOptionsSelected) {

            return false;

        } else {

            var selectedChild,
                obj1,
                obj2;

            // recupero l'id del prodotto con le opzioni selezionate
            for (var property in childsConfig) {

                obj1 = JSON.stringify(childsConfig[property]);
                obj2 = JSON.stringify(selectedOptions)

                if (obj1 === obj2) {
                    var selectedChildId = property;
                    return selectedChildId;
                }

            }

        }

    }


    var stockStatusLabelComponent = function (options) {

        // console.log(options);

        if (options.productData.type === 'configurable') {

            // console.log('configurable');

            childsConfig = options.productOptions.index;
            var childsStatus = options.productData.childs;
            var subscriptions = options.productData.subscriptions;

            $('.super-attribute-select').on('change', function () {

                var str = $(this).attr("name");
                var re = /super_attribute\[(\d+)\]/g;
                var matches = re.exec(str);
                var attributeId = matches[1];

                if ($(this).val() === "") {

                    delete selectedOptions[attributeId];
                    $('#stock_status_label').removeClass();
                    $('#stock_status_label').html("");

                } else {


                    var optionId = $(this).val();
                    selectedOptions[attributeId] = optionId;

                    // recupero il child in base alle opzioni selezionate
                    var selectedChild = _getSelectedChild();

                    if (selectedChild) {
                        if (subscriptions[selectedChild]) {
                            $(".subscription-codes").show();
                        } else {
                            $(".subscription-codes").hide();
                        }
                    }

                }

            });
        }
    }

    return stockStatusLabelComponent;

});
