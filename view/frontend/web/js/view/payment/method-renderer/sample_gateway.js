/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default',
        "jquery",
        "jquery-ui"
    ],
    function (Component, $, ui) {
        'use strict';  

        

        return Component.extend({
            defaults: {
                template: 'SDMagentoModules_SDPurchaseOrder/payment/form',
                transactionResult: ''
            },

            startUpload : function()
            {
                let element = document.getElementById("po_order_field");
                element.onchange = (event)=>
                {
                    console.log("input change", event);
                    let fullPath = element.value;
                    console.log("file: " + fullPath);


                    $("#po_order_pick_button").hide();
                }
                element.click();
            },

            initObservable: function () {

                this._super()
                    .observe([
                        'transactionResult'
                    ]);
                return this;
            },

            getCode: function() {
                return 'sample_gateway';
            },

            getData: function() {
                return {
                    'method': this.item.method,
                    'additional_data': {
                        'transaction_result': this.transactionResult()
                    }
                };
            },

            getTransactionResults: function() {
                return _.map(window.checkoutConfig.payment.sample_gateway.transactionResults, function(value, key) {
                    return {
                        'value': key,
                        'transaction_result': value
                    }
                });
            }
        });
    }
);