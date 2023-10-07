<?php
declare(strict_types=1);

namespace Agn\FakeStoreApiIntegration\Console\Command;

use Agn\FakeStoreApiIntegration\Model\GetCategoryByName;
use Agn\FakeStoreApiIntegration\Model\SaveCategory;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Agn\FakeStoreApiIntegration\Service\GetCategoriesFromApiService;

class SyncCategoryWithAPICommand extends Command
{
    private const NAME = 'sync:category';

    public function __construct(
        private State $state,
        private SaveCategory $saveCategory,
        private GetCategoriesFromApiService $apiService,
        private GetCategoryByName $getCategoryByName,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName(self::NAME);
        $this->setDescription('Category synchronization console command');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->state->setAreaCode(Area::AREA_GLOBAL);

        $apiCategories = $this->apiService->getCategories();

        if (!$apiCategories) {
            $output->writeln('<error>There was an error related to the API connection</error>');
            return Cli::RETURN_FAILURE;
        }

        try {
            foreach ($apiCategories as $categoryName) {
                if (!$this->getCategoryByName->execute($categoryName)) {
                    $this->saveCategory->execute($categoryName);
                }
            }

            $output->writeln('<info>Categories have been synced</info>');
            return Cli::RETURN_SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('<error>There was an error during the saving process.</error>');
            return Cli::RETURN_FAILURE;
        }
    }
}
