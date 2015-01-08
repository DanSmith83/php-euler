<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class NextCommand
 * @package Euler
 */
class NextCommand extends Command
{
    use DirectoryCreator;

    /**
     *
     */
    public function configure()
    {
        $this->setName('next')
             ->setDescription('Generate boilerplate for the next unsolved problem');
    }

    /**
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $next = $this->getNext();

        $output->writeln('<info>Next Problem</info>');

        $this->runCommand('create', ['problem' => $next], $output);
        $this->runCommand('read', ['problem' => $next], $output);
    }

    /**
     * @return int
     */
    private function getCurrent()
    {
        if ($latest = $this->getLatestDirectory($this->getApplication()->config['problems_directory'])) {
            return $latest;
        }

        return false;
    }

    private function getNext()
    {
        if ($current = $this->getCurrent() !== false)
        {
            return $current + 1;
        }

        return 1;
    }
}
