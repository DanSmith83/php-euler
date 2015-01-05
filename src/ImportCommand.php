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
        $zip = new \ZipArchive;
        $res = $zip->open($tempFile);

        if ($res !== true) {
            throw new \Exception('Could not open archive.');
        }

        if (! $zip->extractTo('./'))
        {
            throw new \Exception('Could not extract archive');
        }

        unlink($tempFile);
    }

    private function fetch($url, $tempFile)
    {
        $zip = new \ZipArchive;

        if (! copy($url, $tempFile)) {

            throw new \Exception('Remote file not found.');
        }
    }

    private function extractDirectory()
    {
        /*
        if ($zip->locateName($this->getApplication()->config['functions_directory'])) {
        }

        if ($zip->locateName($this->getApplication()->config['resources_directory'])) {
        }

        if ($zip->locateName($this->getApplication()->config['solutions_directory'])) {
        }
        */
    }
}
