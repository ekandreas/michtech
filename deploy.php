<?php
namespace Deployer;

require __DIR__ . "/vendor/autoload.php";

require __DIR__ . "/vendor/deployer/deployer/recipe/common.php";

set('ssh_type', 'native');
set('ssh_multiplexing', true);

set('domain', 'michtech.app');

server('dev','michtech.app');

server('production', 'elseif.se', 22)
    ->set('deploy_path', '~/michtech.elseif.se')
    ->user('forge')
    ->stage('production')
    ->identityFile();

task('pull', function () {

    $dotenv = new \Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    $password = getenv('PROD_DB_PASSWORD');

    $actions = [
        "ssh forge@elseif.se 'mysqldump michtech -u michtech -p{$password} --skip-lock-tables --hex-blob --single-transaction | gzip' > db.sql.gz",
        "gzip -df db.sql.gz",
        "mysql -u root michtech < db.sql",
        "rm -f db.sql",
        "rsync -rve ssh forge@elseif.se:{{deploy_path}}/storage storage",
    ];

    foreach ($actions as $action) {
        writeln("{$action}");
        writeln(runLocally($action, 999));
    }
});