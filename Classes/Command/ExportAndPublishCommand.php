<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Command;

use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\Publisher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class ExportAndPublishCommand extends Command
{
    /**
     * @var Exporter
     */
    protected $exporter;

    /**
     * @var Publisher
     */
    protected $publisher;

    public function injectExporter(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }

    public function injectPublisher(Publisher $publisher)
    {
        $this->publisher = $publisher;
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Run the export and publish mechanism of the static export.')
            ->addOption('keep-local-export-number', null, InputOption::VALUE_REQUIRED, 'Number of exports that are kept in the filesystem.', 14);
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        try {
            $this->publisher->publish($this->exporter->export());
            $this->exporter->cleanup((int)$input->getOption('keep-local-export-number'));
        } catch (\Exception $exception) {
            return 1;
        }

        return 0;
    }
}
