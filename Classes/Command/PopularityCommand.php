<?php
namespace FKU\FkuSongs\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use FKU\FkuSongs\Domain\Repository\SongRepository;
use FKU\FkuSongs\Domain\Repository\ReportingRepository;

class PopularityCommand extends Command {
	
    /**
     * Configure the command
     */
    protected function configure() {
        $this->setHelp('Searches all events in the last x months and evaluates the chosen songs. The date of last usage per used song is updated in the database.');
		$this->addArgument('eventCategories',InputArgument::OPTIONAL,'Optional. Event category UIDs that are analyzed. Default = all event categories', NULL);
		$this->addArgument('months',InputArgument::OPTIONAL,'Optional. Number of months back from today the events are analyzed. Default = 24', '24');
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$this->songRepository = $objectManager->get(SongRepository::class);
		$this->reportingRepository = $objectManager->get(ReportingRepository::class);

		$log = '';
		$today = new \DateTime('now');
		$eventTypes = [];
		if ($input->getArgument('eventCategories')) {
			$eventTypes = explode(',',$input->getArgument('eventCategories'));
		}
		$months = intval($input->getArgument('months'));
		if ($months <= 0) {
			$months = 24;
		}

		$songs = $this->songRepository->findAll();
		$log .= $today->format('d.m.Y').chr(13);
		$log .= count($songs).' songs'.chr(13).chr(13);

		foreach ($songs as $song) {
			$log .= $song->getTitle().':'.chr(13);
			$sum = 0;
			$usages = 0;
			$history = $this->reportingRepository->findLast($song,$months);
			foreach ($history as $report) {
				$calculate = true;
				$eventDate = $report->getEvent()->getEventStart();
				if (count($eventTypes) > 0) {
					$eventType = $report->getEvent()->getCategory()->getUid();
					if (! in_array($eventType, $eventTypes)) {
						$log .= $eventDate->format('d.m.y').' - wrong category ('.$eventType.')'.chr(13);
						$calculate = false;
					}
				}
				if ($calculate) {
					$usages++;
					$interval = max(50,$eventDate->diff($today)->days);
					$value = 1/sqrt($interval); // formula to calculate the popularity index
					$sum += $value;
					$log .= $eventDate->format('d.m.y').' - add '.$value.' (interval = '.$interval.')'.chr(13);
				}
			}
			$song->setUsages($usages);
			$song->setPopularity(min(1,$sum));
			$song->setStatisticUpdate($today);
			$this->songRepository->update($song);
			$log .= 'Sum: '.$sum.chr(13).chr(13);
		}

		$persistenceManager = GeneralUtility::makeInstance("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
		$persistenceManager->persistAll();
		
        $io = new SymfonyStyle($input, $output);
		$logLines = explode(chr(13),$log);
		foreach ($logLines as $text) {
			$io->writeln($text);
		}

		return 0;
	}
}
?>