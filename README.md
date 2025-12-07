# aeternox cpanel

control panel and homepage of aeternox private server.

### usage

untrack `config/constants.php` from git so changes will be ignored:

```sh
git update-index --assume-unchanged config/constants.php
```

then update the values in `config/constants.php`,
or leave it as-is if you want to run locally.

fill config/constants.php with the following:

```php
<?php
$AETERNOX_DB_IP = "<db-ip>"
$AETERNOX_DB_DB = "<db-name>"
$AETERNOX_DB_LOGIN = "<login-db-name>"
$AETERNOX_DB_CHAR = "<char-db-name>"
$AETERNOX_DB_MAP = "<map-db-name>"
$AETERNOX_DB_WEB = "<logs-db-name>"
$AETERNOX_DB_LOGS = "<logs-db-name>"
$AETERNOX_DB_ID = "<db-user>"
$AETERNOX_PASSWORD = "<db-pass>"
?>
```

