<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Class RunCommand
 * @package Euler
 */
class CurrentCommand extends Command
{
    use DirectoryCreator;

    /**
     *
     */
    public function configure()
    {
        $this->setName('current')
             ->setDescription('Output the current problem');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if ($current = $this->getLatestDirectory($this->getApplication()->config['problems_directory'])) {
            $this->getApplication()->find('read')
                 ->run(new ArrayInput([
                     'problem' => $current,
                     'command' => 'read',
                 ]), $output);
        } else {
            $output->writeln('<error>No problems found</error>');
        }
    }
}
