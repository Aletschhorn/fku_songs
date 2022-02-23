<?php
namespace FKU\FkuSongs\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\Stream;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use FKU\FkuSongs\Domain\Repository\ReportingRepository;

class SongReportingStatusUpdate implements MiddlewareInterface {
	 /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Server\RequestHandlerInterface $handler
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function process (ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {		
		if (!isset($request->getQueryParams()['songReportingStatusUpdate'])) {
            return $handler->handle($request);
        }
		
		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$reportingRepository = $objectManager->get(ReportingRepository::class);

		$success = false;
		$reportingUid = intval($request->getQueryParams()['songReportingStatusUpdate']);
		
		if ($reporting = $reportingRepository->findByUid($reportingUid)) {
			$status = intval($_POST['status']);
			$reporting->setStatus($status);
			$reportingRepository->update($reporting);

			$objectManager->get(\TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager::class)->persistAll();
			$objectManager->get(\TYPO3\CMS\Extbase\Service\CacheService::class)->clearPageCache(['459']);

			$success = true;
		}
		
		$body = new Stream('php://temp', 'rw');
        $body->write($success);
		return (new Response())
                ->withHeader('content-type', 'text/plain; charset=utf-8')
                ->withBody($body)
                ->withStatus(200);
	}
}

?>