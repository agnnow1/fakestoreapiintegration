<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const API_URL = 'fake_store_api/general/api_url';

    public function __construct(
        private ScopeConfigInterface $scopeConfig
    ) {}

    public function getApiUrl(): string
    {
        return $this->scopeConfig->getValue(self::API_URL, ScopeInterface::SCOPE_STORE);
    }
}
