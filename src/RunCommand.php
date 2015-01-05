<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package Euler
 */
class RunCommand extends Command {

    /**
     *
     */
    public function configure()
    {
        $this->setName('run')
             ->setDescription('Run the code for the given problem')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem = $input->getArgument('problem');

        if (file_exists('solutions'.DIRECTORY_SEPARATOR.$problem.'.php'))
        {
            try
            {
                $output->writeln(
                    sprintf(
                        '<info>%s</info>',
                        include $this->getApplication()->config['solutions_directory'].DIRECTORY_SEPARATOR.$problem.'.php'
                    )
                );
            }
            catch (\Exception $e)
            {
                $output->writeln(
                    sprintf(
                        '<error>%s</error>',
                        $e->getMessage()
                    )
                );
            }
        }
    }
}