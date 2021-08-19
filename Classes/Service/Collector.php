<?php

namespace FRUIT\StaticExport\Service;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;

class Collector
{

    public function collect(RequestInterface $request, ResponseInterface $response)
    {
        /** @var \TYPO3\CMS\Core\Http\Uri $uri */
        $uri = clone $request->getUri();
        $uri = $uri->withHost('')->withScheme('');
        if ((string) pathinfo($uri->getPath(), PATHINFO_EXTENSION) === '') {
            $uri = $uri->withPath(rtrim($uri->getPath(), '/') . '/index.html');
        }

        $targetFile = Environment::getProjectPath() . Exporter::BASE_EXPORT_DIR . Exporter::COLLECT_FOLDER . '/' . ltrim((string) $uri, '/');

        if (is_file($targetFile)) {
            unlink($targetFile);
        }

        GeneralUtility::mkdir_deep(pathinfo($targetFile, PATHINFO_DIRNAME));
        GeneralUtility::writeFile($targetFile, (string) $response->getBody());
    }

}
