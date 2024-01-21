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

class Backend10Controller extends AbstractBackendController
{
    public function listAction(bool $export = false): ResponseInterface
    {
        // @todo handle view for v10
        $this->view->assignMultiple([
            'exports' => $this->listProcess($export),
        ]);

        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $moduleTemplate->setContent($this->view->render());

        return $this->htmlResponse($moduleTemplate->renderContent());
    }

    public function downloadAction(string $fileName)
    {
        $this->downloadProcess($fileName);
        exit;
    }

    public function publishAction(string $fileName)
    {
        $this->publishProcess($fileName);
        $this->redirect('list');
    }

    public function deleteAction(string $fileName)
    {
        $this->deleteProcess($fileName);
        $this->redirect('list');
    }
}
