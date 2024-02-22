<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Command;

use FRUIT\StaticExport\Service\Collector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanupCommand extends Command
{
    /**
     * @var Collector
     */
    protected $collector;

    public function injectCollector(Collector $collector)
    {
        $this->collector = $collector;
    }

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Reset the collect folder to start a fresh collection.');
    }

    protected function execute(InputInterface $input, OutputInterface $output):int
    {
        try {
            $this->collector->cleanup();
        } catch (\Exception $exception) {
            return 1;
        }

        return 0;
    }
}
