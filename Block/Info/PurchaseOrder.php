<?php
namespace SDMagentoModules\SDPurchaseOrder\Block\Info;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class PurchaseOrder extends \Magento\Payment\Block\Info
{
    protected $_template = 'SDMagentoModules_SDPurchaseOrder::info/purchaseorder.phtml';
    private $imageUploader;
    protected $mediaDirectory;

    public function __construct(
        \SDMagentoModules\SDPurchaseOrder\Model\ImageUploader $imageUploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->imageUploader = $imageUploader;
        $this->mediaDirectory = $filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        parent::__construct($context, $data);
    }

    public function getPoNumber()
	{
		$poNumber = null;
        try 
        {
			$poNumber = $this->getInfo()->getPoNumber();
        } 
        catch ( LocalizedException $e ) 
        {
            $this->_logger->error($e);
            return null;
		}
		return $poNumber;
	}

    public function getPurchaseorderFileUrl()
    {
        $mediaPath = $this ->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $uploadsPath = $this->imageUploader->getBasePath();
        $filename = $this->getInfo()->getAdditionalInformation('po_filename');
        $filePath = $mediaPath.$uploadsPath.'/'.$filename;
        return $this->getUrl($filePath);
    }

    public function getPurchaseorderFileLink()
    {
    	$link = null;
        try 
        {
            if ( $poFilename = $this->getInfo()->getAdditionalInformation( 'po_filename' ) ) 
            {
		    	$link = sprintf('<a class="file" target="_blank" href="%s">%s</a>', $this->escapeHtml($this->getPurchaseorderFileUrl()), $this->escapeHtml($poFilename));
		    }
        } 
        catch ( NoSuchEntityException $e ) 
        {
            $this->_logger->error($e);
            return null;
        } 
        catch ( LocalizedException $e ) 
        {
            $this->_logger->error($e);
            return null;
	    }
	    return $link;
    }

    public function toPdf()
    {
        $this->setTemplate('Magento_OfflinePayments::info/pdf/purchaseorder.phtml');
        return $this->toHtml();
    }
}
