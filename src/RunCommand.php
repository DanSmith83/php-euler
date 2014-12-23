<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command {

    public function configure()
    {
        $this->setName('run')
             ->setDescription('The problem number')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');

        if ( ! is_dir('solutions'))
        {
            mkdir('solutions', 0755, true);
        }

        if (file_exists('solutions'.DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            $output->writeln(include 'solutions'.DIRECTORY_SEPARATOR.$problem.'.php');
        }


    }

}