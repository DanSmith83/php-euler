<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Euler\DirectoryCreator;

class SetupCommand extends Command {

    use DirectoryCreator;

    public function configure()
    {
        $this->setName('setup')
             ->setDescription('The problem number');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->createDirectory($this->getApplication()->config['functions_directory']);
        $this->createDirectory($this->getApplication()->config['resources_directory']);
        $this->createDirectory($this->getApplication()->config['solutions_directory']);
    }
}