<?php

namespace HumanElement\CryptKeyCLI\Console\Command;

use Magento\Framework\Config\ConfigOptionsListConstants;
use Magento\Framework\Math\Random;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command {
    const NAME = 'humanelement:cryptkey:generate';

    /**
     * Random
     *
     * @var Random
     */
    protected $random;

    public function __construct(
        Random $random,
    ) {
        $this->random = $random;
        parent::__construct();
    }

    protected function configure() {
        $this->setName(self::NAME)
            ->setDescription('Generates a valid encryption key for use in env.php');
        parent::configure();
    }

    /**
     * This logic copied from magento/module-encryption-key/Model/ResourceModel/Key/Change.php
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $key = md5($this->random->getRandomString(ConfigOptionsListConstants::STORE_KEY_RANDOM_STRING_SIZE));
        $output->writeln($key);
    }
}
