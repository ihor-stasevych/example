<?php

use EasyCorp\Bundle\EasyDeployBundle\Deployer\DefaultDeployer;

return new class extends DefaultDeployer
{
    public function configure()
    {
        return $this->getConfigBuilder()
            // SSH connection string to connect to the remote server (format: user@host-or-IP:port-number)
            ->server('root@dev.addhash.com')
            // the absolute path of the remote server directory where the project is deployed
            ->deployDir('/var/www/html/dev')
            // the URL of the Git repository where the project code is hosted
            ->repositoryUrl('git@bitbucket.org:tapgerine/equipmentcrm.git')
            // the repository branch to deploy
            ->repositoryBranch('testDeploy')
        ;
    }

    // run some local or remote commands before the deployment is started
    public function beforeStartingDeploy()
    {
        // $this->runLocal('./vendor/bin/simple-phpunit');
    }

    // run some local or remote commands after the deployment is finished
    public function beforeFinishingDeploy()
    {
        // $this->runRemote('{{ console_bin }} app:my-task-name');
        // $this->runLocal('say "The deployment has finished."');
    }
};
