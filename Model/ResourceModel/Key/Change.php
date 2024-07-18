<?php

namespace HumanElement\CryptKey\Model\ResourceModel\Key;

class Change extends \Magento\EncryptionKey\Model\ResourceModel\Key\Change {
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Config\Model\Config\Structure $structure,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\App\DeploymentConfig\Writer $writer,
        \Magento\Framework\Math\Random $random,
        $connectionName = null
    ) {
        parent::__construct($context, $filesystem, $structure, $encryptor, $writer, $random, $connectionName);
    }

    public function reEncryptKnownValues() {
        $this->beginTransaction();
        try {
            $this->_reEncryptSystemConfigurationValues();
            $this->_reEncryptCreditCardNumbers();
            $this->commit();
        } catch (\Exception $e) {
            $this->rollBack();
            throw $e;
        }
    }
}
