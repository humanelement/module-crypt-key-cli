<?php

namespace HumanElement\CryptKeyCLI\Model\ResourceModel\Key;

use Magento\Framework\App\Area;
use Magento\Framework\Config\ScopeInterface;

/**
 * Encryption key changer resource model. Subclassed from core to expose a reencrypt-only method without changing the key.
 */
class Change extends \Magento\EncryptionKey\Model\ResourceModel\Key\Change {
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\App\DeploymentConfig\Writer $writer,
        \Magento\Framework\Math\Random $random,
        \Magento\Framework\Config\ScopeInterface $scope,
        \Magento\Config\Model\Config\StructureFactory $configStructureFactory,
        $connectionName = null
    ) {
        /*
         * Admin scope must be emulated before config structure is constructed,
         * otherwise system.xml contents will not be loaded within.
         */
        $areaScope = $scope->getCurrentScope();
        $scope->setCurrentScope(Area::AREA_ADMINHTML);
        /** @var \Magento\Config\Model\Config\Structure $structure */
        $structure = $configStructureFactory->create();
        $scope->setCurrentScope($areaScope);

        parent::__construct(
            $context,
            $filesystem,
            $structure,
            $encryptor,
            $writer,
            $random,
            $connectionName
        );
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
