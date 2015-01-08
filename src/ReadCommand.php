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

        if (file_exists(sprintf(
            '%s%s%s%sproblem.php',
            $this->getApplication()->config['problems_directory'],
            DIRECTORY_SEPARATOR,
            $problem,
            DIRECTORY_SEPARATOR
        )))
        {
            $formatter = $this->getHelper('formatter');
            $block     = $formatter->formatBlock(file_get_contents(sprintf(
                '%s%s%s%sproblem.php',
                $this->getApplication()->config['problems_directory'],
                DIRECTORY_SEPARATOR,
                $problem,
                DIRECTORY_SEPARATOR
            )), 'question');

            $output->writeln($block);
        }
    }
}
