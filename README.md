Magento Encryption Key CLI Module
=================================

This module adds a number of CLI commands to the `bin/magento` command for managing encryption keys.

- `bin/magento humanelement:cryptkey:generate` - Generates an encryption key suitable for use in `env.php`.
- `bin/magento humanelement:cryptkey:change` - Changes the encryption key in `env.php` and re-encrypts known encrypted values in the database with the new key. This functionality is identical to the **Change Encryption Key** button in the Magento admin UI.
- `bin/magento humanelement:cryptkey:reencrypt` - Re-encrypts known values, but does not change the encryption key in `env.php`. This should be run after manually adding a key to `env.php`.

