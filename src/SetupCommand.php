<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
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
        $questionHelper = $this->getHelper('question');

        foreach ($this->getApplication()->config as $key => $val)
        {
            $question = new Question(sprintf('<question>%s</question>', $key), $val);
            $response = $questionHelper->ask($input, $output, $question);
            $output->writeln(sprintf('<info>%s</info>', $response));
        }

        $this->createDirectory($this->getApplication()->config['functions_directory']);
        $this->createDirectory($this->getApplication()->config['resources_directory']);
        $this->createDirectory($this->getApplication()->config['solutions_directory']);
    }
}