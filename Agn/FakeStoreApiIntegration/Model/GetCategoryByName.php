<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Model;

use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;

class GetCategoryByName
{
    public function __construct(
        private CollectionFactory $categoryCollectionFactory,
    ) {}

    public function execute(string $name): ?CategoryInterface
    {
        /** @var CategoryInterface $category */
        $category = $this->categoryCollectionFactory->create()
            ->addFieldToFilter('name', ['eq' => $name])
            ->getFirstItem();

        return $category->getId() ? $category : null;
    }
}
