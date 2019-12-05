<?php
namespace SDMagentoModules\SDPurchaseOrder\Plugin\OfflinePayments\Model;

use SDMagentoModules\SDPurchaseOrder\Model\ImageUploader;

class PurchaseOrderPlugin extends \Magento\Framework\Model\AbstractModel
{
    private $imageUploader;
    public function __construct(ImageUploader $imageUploader)
    {
        $this->imageUploader = $imageUploader;
    }

    public function beforeAssignData(\Magento\OfflinePayments\Model\Purchaseorder $subject, \Magento\Framework\DataObject $data) 
    {
        $po_filename = $data->getAdditionalData('po_filename');
        if ($po_filename) 
        {
            $this->imageUploader->moveFileFromTmp($po_filename);
            $subject->getInfoInstance()->setAdditionalInformation('po_filename', $po_filename);
        }
        return null;
    }
}
