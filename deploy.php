<?php
namespace Deployer;

require 'recipe/symfony.php';

set('repository', 'git@bitbucket.org:tapgerine/equipmentcrm.git');

add('shared_dirs', [
	'var',
]);
add('shared_files', [
	'.env'
]);

add('writable_dirs', [
	'var/cache',
	'var/data',
	'var/log',
]);

// Project name
set('application', 'AddHash');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);


// Hosts

host('dev.addhash.com')
	->stage('beta')
	->user('root')
	->set('deploy_path', '/var/www/html/dev/equipmentcrm');

desc('Restart PHP-FPM service');
task('php-fpm:restart', function () {
	// The user must have rights for restart service
	// /etc/sudoers: username ALL=NOPASSWD:/bin/systemctl restart php-fpm.service
	run('sudo systemctl restart php7.2-fpm.service');
});
after('deploy:symlink', 'php-fpm:restart');

task('upload:env', function () {
	upload('.env.dist', '{{deploy_path}}/shared/.env');
})->desc('Environment setup');



// Tasks
task('deploy', [
	'deploy:prepare',
	'deploy:lock',
	'deploy:release',
	'deploy:update_code',
	'upload:env',
	'deploy:shared',
	'deploy:vendors',
	'deploy:writable',
	'deploy:symlink',
	'php-fpm:restart',
	'deploy:unlock',
	'cleanup',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Migrate database before symlink new release.

before('deploy:symlink', 'database:migrate');

