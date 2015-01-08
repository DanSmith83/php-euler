<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package Euler
 */
class ImportCommand extends Command
{
    /**
     * @param Client $client
     * @param null   $name
     */
    public function __construct(\ZipArchive $zip)
    {
        parent::__construct();

        $this->zip = $zip;
    }
    /**
     *
     */
    public function configure()
    {
        $this->setName('import')
             ->setDescription('Import zip file')
             ->addArgument('url', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $tempFile = 'tmpfile.zip';

        try
        {
            $this->fetch($input->getArgument('url'), $tempFile);
            $this->extract($tempFile);
            $output->writeln('<info>Import complete</info>');

        }
        catch (Exception $e)
        {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
        }
    }

    private function extract($tempFile)
    {
        $res = $this->zip->open($tempFile);

        if ($res !== true) {
            throw new \Exception('Could not open archive.');
        }

        if (! $this->zip->extractTo('./'))
        {
            throw new \Exception('Could not extract archive');
        }

        unlink($tempFile);
    }

    private function fetch($url, $tempFile)
    {
        if (! copy($url, $tempFile)) {

            throw new \Exception('Remote file not found.');
        }
    }
}
