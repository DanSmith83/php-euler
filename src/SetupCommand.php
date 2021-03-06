<?php namespace Euler;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class SetupCommand
 * @package Euler
 */
class SetupCommand extends Command
{
    use DirectoryCreator;

    /**
     *
     */
    public function configure()
    {
        $this->setName('setup')
             ->setDescription('Create configuration');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $config         = $this->getApplication()->config;
        $questionHelper = $this->getHelper('question');

        foreach ($this->getApplication()->config as $key => $val) {
            if (! strpos($key, '_directory')) {
                continue;
            }

            $question = new Question(
                sprintf('<question>%s:</question>', ucfirst(str_replace('_', ' ', $key))), $val
            );

            $response = $questionHelper->ask($input, $output, $question);
            $output->writeln(sprintf('<info>%s</info>', $response));
            $config[$key] = $response;
        }

        file_put_contents(
            'config/config.php',
            sprintf(
                '<?php'.PHP_EOL.PHP_EOL.'return %s;', var_export($config, true)
            )
        );

        $this->createDirectory($config['problems_directory']);
        $this->createDirectory($config['shared_directory']);
    }
}
