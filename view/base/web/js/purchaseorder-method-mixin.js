define([
    'Magento_Checkout/js/view/payment/default',
    'uiLayout',
], function (Component, layout) {
    'use strict';
    return function (Component) {
        return Component.extend({
            defaults: 
            {
                template: 'SDMagentoModules_SDPurchaseOrder/payment/purchaseorder-form',
                purchaseOrderNumber: '',
                purchaseOrderFilename: '',
                purchaseOrderContact: ''
            },
            initObservable: function () 
            {
                this._super().observe('purchaseOrderNumber');
                this._super().observe('purchaseOrderFilename');
                // this._super().observe('purchaseOrderContact');
                return this;
            },
            initChildren: function () 
            {
                this.createFileUploaderComponent();
                return this;
            },
            createFileUploaderComponent: function () 
            {
                var self = this;
                let siteurl = window.location.href;
                siteurl = siteurl.split("//").pop();
                let urlparts = siteurl.split("/");
                urlparts.shift();
                siteurl = urlparts.join("/");
                if(siteurl.indexOf("checkout") != 0)
                {
                    siteurl = "/" + siteurl.split("checkout")[0];
                }
                else
                {
                    siteurl = "";
                }
                var fileUploaderComponent = {
                    parent: this.name,
                    name: this.name + '.uploader',
                    label: 'Select file to upload',
                    allowedExtensions: 'jpg jpeg png pdf',
                    placeholderType: 'image',
                    component: 'Magento_Ui/js/form/element/file-uploader',
                    template: 'SDMagentoModules_SDPurchaseOrder/uploader',
                    previewTmpl: 'SDMagentoModules_SDPurchaseOrder/preview',
                    displayArea: 'uploader',
                    uploaderConfig: {url: siteurl + 'uploader/purchaseorderupload/index'},
                    required: true,
                    addFile: function(file) 
                    {
                        file = this.processFile(file);
                        this.isMultipleFiles ? this.value.push(file) : this.value([file]);
                        self.purchaseOrderFilename(file.name);
                        return this;
                    }
                };
                layout([fileUploaderComponent]);
                return this;
            },
            getData: function () 
            {
                return {
                    method: this.item.method,
                    'po_number': this.purchaseOrderNumber(),
                    'additional_data': {
                        'po_filename': this.purchaseOrderFilename()
                    }
                };
            },
        });
    }
});