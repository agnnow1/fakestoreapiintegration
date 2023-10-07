<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Service;

use Agn\FakeStoreApiIntegration\Model\Config;
use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use Magento\Framework\Webapi\Rest\Request;

abstract class AbstractFakeStoreApiService
{
    protected $apiRequestEndpoint = '';

    public function __construct(
        private Config          $config,
        private ClientFactory   $clientFactory,
        private ResponseFactory $responseFactory,
    ) {}

    public function execute(): ?array
    {
        $response = $this->doRequest($this->apiRequestEndpoint);

        if ($response->getStatusCode() !== 200) {
            return null;
        }

        $responseContent = $response->getBody()->getContents();
        return json_decode($responseContent);
    }

    private function doRequest(
        string $uriEndpoint,
        array $params = [],
        string $requestMethod = Request::HTTP_METHOD_GET
    ): Response {
        /** @var Client $client */
        $client = $this->clientFactory->create(['config' => [
            'base_uri' => $this->config->getApiUrl()
        ]]);

        try {
            $response = $client->request(
                $requestMethod,
                $uriEndpoint,
                $params
            );
        } catch (GuzzleException $exception) {
            /** @var Response $response */
            $response = $this->responseFactory->create([
                'status' => $exception->getCode(),
                'reason' => $exception->getMessage()
            ]);
        }

        return $response;
    }
}
