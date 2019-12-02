<?php
namespace SDMagentoModules\SDPurchaseOrder\Model\Ui;

use Magento\Checkout\Model\ConfigProviderInterface;
use SDMagentoModules\SDPurchaseOrder\Gateway\Http\Client\ClientMock;

final class ConfigProvider implements ConfigProviderInterface
{
    const CODE = 'sample_gateway';
    /**
     * Retrieve assoc array of checkout configuration
     *
     * @return array
     */
    public function getConfig()
    {
        return [
            'payment' => [
                self::CODE => [
                    'transactionResults' => [
                        ClientMock::SUCCESS => __('Success'),
                        ClientMock::FAILURE => __('Fraud')
                    ]
                ]
            ]
        ];
    }
}
