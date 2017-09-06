<?php
namespace Deployer;

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/vendor/deployer/deployer/recipe/laravel.php";

host('139.162.161.167')
    ->port(22)
    ->user('forge')
    ->set('deploy_path', '~/www.michtech.se')
    ->forwardAgent(true)
    ->multiplexing(true)
    ->set('branch', 'master')
    ->stage('production')
    ->identityFile('~/.ssh/id_rsa');

set('repository', 'https://github.com/ekandreas/michtech.git');
set('keep_releases', 2);
set('writable_dirs', []);

task('deploy:restart', function () {
    run("cd {{deploy_path}}/current && php artisan storage:link");
    run("cd {{deploy_path}}/current && php artisan migrate --force");
    run("cd {{deploy_path}}/current && php artisan config:clear");
    run("cd {{deploy_path}}/current && php artisan queue:restart");
    run("sudo /etc/init.d/php7.1-fpm restart");
    //run("sudo /etc/init.d/nginx reload");
    //run("sudo /etc/init.d/php7.1-fpm reload");
    //run("sudo /etc/init.d/supervisor restart");
})
    ->desc('Restarting and other stuff after deploy');
after('cleanup', 'deploy:restart');

task('pull', function () {

    $dotenv = new \Dotenv\Dotenv(__DIR__);
    $dotenv->load();

    $password = getenv('PROD_DB_PASSWORD');

    $actions = [
        "ssh forge@139.162.161.167 'export MYSQL_PWD={$password}; mysqldump michtech -u michtech --skip-lock-tables --hex-blob --single-transaction' > db.sql",
        "mysql -u root michtech < db.sql",
        "rm -f db.sql",
        "rsync -rve ssh forge@139.162.161.167:{{deploy_path}}/shared/storage storage",
    ];

    foreach ($actions as $action) {
        writeln("{$action}");
        runLocally($action, ['timeout'=>999]);
    }
});