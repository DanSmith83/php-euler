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
        $this->getApplication()->find('create')
                               ->run(new ArrayInput([
                                   'problem' => $next,
                                   'command' => 'create',
                               ]), $output);
    }

    /**
     * @return int
     */
    private function getNext()
    {
        $file      = 1;
        $directory = $this->getApplication()->config['problems_directory'];

        if ($latestFilename = $this->getLatestFile($directory)) {
            $bits = explode('.', $latestFilename);
            $file = $bits[0];
            $file ++;
        }

        return $file;
    }
}
