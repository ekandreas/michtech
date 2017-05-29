<?php
namespace Deployer;

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/vendor/deployer/deployer/recipe/laravel.php";

host('elseif.se')
    ->port(22)
    ->set('deploy_path', '~/michtech.elseif.se')
    ->user('forge')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('branch', 'master')
    ->stage('production')
    ->set('database', 'michtech')
    ->identityFile('~/.ssh/id_rsa');

set('repository', 'https://github.com/ekandreas/michtech.git');
set('env', 'prod');
set('keep_releases', 2);

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