<?php
namespace SDMagentoModules\SDPurchaseOrder\Controller\PurchaseOrderUpload;

use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    const ADMIN_RESOURCE = 'Magento_Customer::manage';
    protected $baseTmpPath;
    protected $checkoutSession;
    protected $logger;
    protected $resultPageFactory;
    protected $resultFactory;
    protected $jsonSerializer;
    protected $imageUploader;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Serialize\Serializer\Json $jsonSerializer,
        \SDMagentoModules\SDPurchaseOrder\Model\ImageUploader $imageUploader,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->resultFactory = $context->getResultFactory();
        $this->jsonSerializer = $jsonSerializer;
        $this->imageUploader = $imageUploader;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
        parent::__construct($context);
    }

    public function execute()
    {
        try 
        {
            $result = $this->imageUploader->saveFileToTmpDir('files');
            $quote = $this->checkoutSession->getQuote();
            $quoteAdditionalData = $quote->getAdditionalData() ?: [];
            $this->baseTmpPath = $this->imageUploader->getBaseTmpPath();
            $file_path = $this->baseTmpPath . '/' . $result['file'];
            array_push($quoteAdditionalData, json_encode(['po_filename' => $file_path]));
        } 
        catch (\Exception $e) 
        {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            $this->logger->critical($e->getMessage());
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}

