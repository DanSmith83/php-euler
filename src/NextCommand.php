<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Euler\SetupCommand;

class NextCommand extends Command {

    public function __construct(SetupCommand $command, $name = null)
    {
        parent::__construct($name);

        $this->command = $command;
    }

    public function configure()
    {
        $this->setName('next')
            ->setDescription('The problem number');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Woop');

        $files = scandir('solutions', SCANDIR_SORT_DESCENDING);
        $file  = array_shift($files);
        $bits  = explode('.', $file);

        $i = new ArrayInput(['problem' => $bits[0] + 1]);
        $o = new BufferedOutput;

        $code = $this->command->run($i, $o);

        if ($code == 0)
        {
            $outputText = $o->fetch();
            $output->writeln($outputText);
        }
    }

}