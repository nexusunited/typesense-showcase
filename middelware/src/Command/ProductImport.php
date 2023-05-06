<?php declare(strict_types=1);

namespace App\Command;

use App\Import\ProductImport as Import;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:product-import')]
final class ProductImport extends Command
{
    public function __construct(
        private readonly Import $productImport
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->productImport->import();

        return Command::SUCCESS;
    }
}
