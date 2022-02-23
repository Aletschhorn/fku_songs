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
use FKU\FkuSongs\Domain\Repository\EventRepository;

class LastUsageCommand extends Command {
	
    /**
     * Configure the command
     */
    protected function configure() {
        $this->setHelp('Searches all events in the last x months and evaluates the chosen songs. The date of last usage per used song is updated in the database.');
		$this->addArgument('months',InputArgument::OPTIONAL,'Optional. Number of months back from today the events are analyzed. Default = 1', '1');
    }

    /**
     * Executes the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output) {

		$objectManager = GeneralUtility::makeInstance(ObjectManager::class);
		$this->eventRepository = $objectManager->get(EventRepository::class);
		$this->songRepository = $objectManager->get(SongRepository::class);

		$songIds = [];
		$months = max([1,intval($input->getArgument('months'))]);
        $events = $this->eventRepository->findLastEventsWithSongs('',$months,false);
		foreach ($events as $event) {
			foreach ($event->getSongreporting() as $reporting) {
				$song = $this->songRepository->findByUid($reporting->getSong()->getUid());
				$song->setLastUsage($event->getEventStart());
				$this->songRepository->update($song);
				if (! in_array($song->getUid(),$songIds)) {
					$songIds[] = $song->getUid();
				}
			}
		}
		$persistenceManager = GeneralUtility::makeInstance("TYPO3\\CMS\\Extbase\\Persistence\\Generic\\PersistenceManager");
		$persistenceManager->persistAll();
		
        $io = new SymfonyStyle($input, $output);
		$io->writeln('Analyzed '.$events->count().' events and updated '.count($songIds).' different songs.');

		return 0;
    }
}
?>