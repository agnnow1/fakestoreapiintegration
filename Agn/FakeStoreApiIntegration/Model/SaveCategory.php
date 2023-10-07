<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Model;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Catalog\Model\CategoryFactory;

class SaveCategory
{
    public function __construct(
        private categoryFactory             $categoryFactory,
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    /**
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(string $categoryName): CategoryInterface
    {
        $category = $this->categoryFactory->create();

        $category->setName($categoryName)
            ->setIsActive(1);

        return $this->categoryRepository->save($category);
    }
}
