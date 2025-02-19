<?php

namespace HumanElement\CryptKeyCLI\Console\Command;

use Magento\Framework\Console\QuestionPerformer\YesNo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Change extends Command {
    const NAME = 'humanelement:cryptkey:change';

    private \HumanElement\CryptKeyCLI\Model\ResourceModel\Key\Change $change;

    private YesNo $questionPerformer;

    public function __construct(\HumanElement\CryptKeyCLI\Model\ResourceModel\Key\Change $change, YesNo $questionPerformer) {
        parent::__construct();
        $this->change = $change;
        $this->questionPerformer = $questionPerformer;
    }

    protected function configure() {
        $this->setName(self::NAME)
            ->setDescription('Adds a new encryption key to env.php and uses it to re-encrypt credit card numbers and encrypted configuration values.')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Skip confirmation prompt.');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $force = $input->getOption('force');
        if (!$force) {
            if (!$input->isInteractive()) {
                $output->writeln('This command must be run interactively; specify --force to skip the prompt');
                return 1;
            }

            $prompt = 'This will change the encryption key and re-encrypt every known encrypted value in the database with a freshly-encrypted value. Are you sure you want to continue (y/n)?';
            if (!$this->questionPerformer->execute([$prompt], $input, $output)) {
                return 1;
            }
        }

        try {
            $this->change->changeEncryptionKey();
        } catch (\Exception $e) {
            $output->writeln('An error occurred: ');
            $output->writeln($e->getMessage());
            return 1;
        }

        $output->writeln('Encryption key changed.');
        return 0;
    }
}
