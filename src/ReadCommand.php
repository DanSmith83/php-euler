<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package Euler
 */
class ReadCommand extends Command
{
    /**
     *
     */
    public function configure()
    {
        $this->setName('read')
             ->setDescription('Output the specified problem')
             ->addArgument('problem', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $problem   = $input->getArgument('problem');
        $directory = $this->getApplication()->config['problems_directory'];

        if (file_exists($directory.DIRECTORY_SEPARATOR.$problem.'.php')) {
            $formatter = $this->getHelper('formatter');
            $block     = $formatter->formatBlock(file_get_contents($directory.DIRECTORY_SEPARATOR.$problem.'.php'), 'question');

            $output->writeln($block);
        }
    }
}
