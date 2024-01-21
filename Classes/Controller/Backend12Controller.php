<?php

declare(strict_types=1);

namespace FRUIT\StaticExport\Controller;

use FRUIT\StaticExport\Service\Exporter;
use FRUIT\StaticExport\Service\PathService;
use FRUIT\StaticExport\Service\Publisher;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Backend\Attribute\Controller;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Http\ForwardResponse;

#[Controller]
class Backend12Controller extends AbstractBackendController
{
    protected $moduleTemplateFactory;

    public function __construct(
        Exporter    $exporter,
        Publisher   $publisher,
        PathService $pathService
    )
    {

        parent::__construct($exporter, $publisher, $pathService);
        // Do not include this via DI, because of TYPO3 v10 support
        $this->moduleTemplateFactory = GeneralUtility::makeInstance(ModuleTemplateFactory::class);
    }

    public function listAction(bool $export = false): ResponseInterface
    {
        $this->view->assignMultiple([
            'exports' => $this->listProcess($export),
        ]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    public function downloadAction(string $fileName): ResponseInterface
    {
        $this->downloadProcess($fileName);
        exit;
    }

    public function publishAction(string $fileName): ResponseInterface
    {
        $this->publishProcess($fileName);

        return new ForwardResponse('list');
    }

    public function deleteAction(string $fileName): ResponseInterface
    {
        $this->deleteProcess($fileName);
        return new ForwardResponse('list');
    }
}
