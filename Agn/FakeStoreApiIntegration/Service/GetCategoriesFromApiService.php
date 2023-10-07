<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Service;

use Agn\FakeStoreApiIntegration\Model\Config;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\ResponseFactory;

class GetCategoriesFromApiService extends AbstractFakeStoreApiService
{
    protected $apiRequestEndpoint = 'products/categories/';

    public function __construct(
        Config $config,
        ClientFactory $clientFactory,
        ResponseFactory $responseFactory,
    ) {
        parent::__construct($config, $clientFactory, $responseFactory);
    }

    public function getCategories(): ?array
    {
        return $this->execute();
    }
}
