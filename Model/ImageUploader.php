<?php
namespace SDMagentoModules\SDPurchaseOrder\Model;

class ImageUploader extends \Magento\Catalog\Model\ImageUploader
{
    private $uploaderFactory;
    private $allowedMimeTypes = [
        'image/jpg',
        'image/jpeg',
        'image/png',
        'application/pdf'
    ];
    public function __construct(\Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDatabase,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Psr\Log\LoggerInterface $logger,
        string $baseTmpPath,
        string $basePath,
        array $allowedExtensions)
    {
        parent::__construct(
            $coreFileStorageDatabase,
            $filesystem,
            $uploaderFactory,
            $storeManager,
            $logger,
            $baseTmpPath,
            $basePath,
            $allowedExtensions
        );
        $this->uploaderFactory = $uploaderFactory;
    }

    public function saveFileToTmpDir($fileId)
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $uploader->setAllowRenameFiles(true);
        if (!$uploader->checkMimeType($this->allowedMimeTypes)) 
        {
            $this->logger->critical("no results");
            return null;
        }
        $result = $uploader->save($this->mediaDirectory->getAbsolutePath($baseTmpPath));
        unset($result['path']);
        if (!$result) 
        {
            $this->logger->critical("no results");
            return null;
        }
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['url'] = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];
        if (isset($result['file'])) 
        {
            try 
            {
                $relativePath = rtrim($baseTmpPath, '/') . '/' . ltrim($result['file'], '/');
                $this->coreFileStorageDatabase->saveFile($relativePath);
            } 
            catch (\Exception $e) 
            {
                $this->logger->critical($e);
                return;
            }
        }
        return $result;
    }
}
