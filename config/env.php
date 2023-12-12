<?php

defined('YII_DEBUG') or define('YII_DEBUG', filter_var($_ENV['DEBUG_MODE'], FILTER_VALIDATE_BOOL));
defined('YII_ENV') or define('YII_ENV', $_ENV['ENV_MODE']);

define('DB_ADDRESS', $_ENV['DB_ADDRESS']);
define('DB_NAME', $_ENV['DB_NAME']);
define('DB_USERNAME', $_ENV['DB_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_PASSWORD']);

define('COM_CHAT_CHANNEL_ID', $_ENV['COM_CHAT_CHANNEL_ID']);
define('COM_CHAT_SECRET_KEY', $_ENV['COM_CHAT_SECRET_KEY']);
define('RU_CHAT_CHANNEL_ID', $_ENV['RU_CHAT_CHANNEL_ID']);
define('RU_CHAT_SECRET_KEY', $_ENV['RU_CHAT_SECRET_KEY']);

define('PBX_CORE_SERVICE_ADDRESS', $_ENV['PBX_CORE_SERVICE_ADDRESS']);

define('AUTH_VAULT_SERVICE_ADDRESS', $_ENV['AUTH_VAULT_SERVICE_ADDRESS']);
define('AUTH_VAULT_SERVICE_ACCESS_TOKEN', $_ENV['AUTH_VAULT_SERVICE_ACCESS_TOKEN']);

define('API_AUTH_KEY', $_ENV['API_AUTH_KEY']);

define('AMO_INTEGRATION_SECRET_KEY', $_ENV['AMO_INTEGRATION_SECRET_KEY']);
define('AMO_INTEGRATION_ID', $_ENV['AMO_INTEGRATION_ID']);
define('AMO_INTEGRATION_REDIRECT_URL', $_ENV['AMO_INTEGRATION_REDIRECT_URL']);

define('LOG_SERVICE_ADDRESS', $_ENV['LOG_SERVICE_ADDRESS']);
define('LOG_SERVICE_TIMEOUT', $_ENV['LOG_SERVICE_TIMEOUT']);
define('LOG_SERVICE_ACCESS_TOKEN', $_ENV['LOG_SERVICE_ACCESS_TOKEN']);