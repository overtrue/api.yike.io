<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'yikeio api');

// Project repository
set('repository', 'git@gitee.com:yikeio/api.yike.io.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);
set('ssh_type', 'native');
set('ssh_multiplexing', true);
set('default_timeout', 1600);
set('deploy_path', '/www/api.yike.io');
set('writable_use_sudo', true);
set('clear_use_sudo', true);
set('http_user', 'deployer');
set('http_group', 'www-data');
set('writable_mode', 'chown');
set('default_stage', 'production');
set('keep_releases', 2);

host('api.yike.io')
    ->stage('production')
    ->user('deployer')
    ->multiplexing(true);

desc('Restarting php-fpm.');
task('php-fpm:restart', function () {
    run('sudo service php7.2-fpm restart');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
after('cleanup', 'php-fpm:restart');
after('cleanup', 'artisan:queue:restart');
