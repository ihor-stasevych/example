<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2SCP;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2Connection;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2AuthPassword;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2SCPFailException;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerPoolRepositoryInterface;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketDefaultParser;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2ConnectionFailException;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    const PATH_TEMP_CONFIG = '../config_pools/';

    const DEFAULT_CONFIG_NAME = 'bmminer.conf';

    const PATH_CONFIG_REMOTE_SERVER = '/config/' . self::DEFAULT_CONFIG_NAME;

    const INDEX_POOLS = 'pools';


    private $logger;

    private $allowedUrlRepository;

    private $minerPoolRepository;

    public function __construct(LoggerInterface $logger, MinerAllowedUrlRepositoryInterface $allowedUrlRepository, MinerPoolRepositoryInterface $minerPoolRepository)
    {
        $this->logger = $logger;
        $this->allowedUrlRepository = $allowedUrlRepository;
        $this->minerPoolRepository = $minerPoolRepository;
    }

    /**
     * @param UserMinerControlCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     * @throws SSH2AuthFailException
     * @throws SSH2SCPFailException
     * @throws SSH2ConnectionFailException
     */
    public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock)
    {
        $data = [];

        if ($minerStock->getId() != $command->getMinerId()) {
            return $data;
        }

        $pathTempConfig = static::PATH_TEMP_CONFIG . $minerStock->getId() . '/';
        $pathTempConfigFile = $pathTempConfig . static::DEFAULT_CONFIG_NAME;

        $oldPools = [];

        if (file_exists($pathTempConfigFile)) {
            $oldConfigFile = json_decode(
                file_get_contents($pathTempConfigFile),
                true
            );

            $oldPools = $oldConfigFile[static::INDEX_POOLS];
        }

        $newPools = $command->getPools();

        $isIdentity = $this->checkIdentity($oldPools, $newPools);

        if (false === $isIdentity) {
            $ssh2Connection = new SSH2Connection('10.0.10.7');
            $connection = $ssh2Connection->getConnection();

            new SSH2AuthPassword($connection, 'root', 'Dmnrtpf[23-}');

            @mkdir($pathTempConfig, 0777, true);
            $scp = new SSH2SCP($connection);
            $scp->fetch($pathTempConfigFile, static::PATH_CONFIG_REMOTE_SERVER);

            $tempConfigFile = json_decode(
                file_get_contents($pathTempConfigFile),
                true
            );

            $tempConfigFile[static::INDEX_POOLS] = $this->toFormatPools($newPools);

            file_put_contents(
                $pathTempConfigFile,
                json_encode($tempConfigFile, JSON_UNESCAPED_SLASHES)
            );

            $scp->send($pathTempConfigFile, static::PATH_CONFIG_REMOTE_SERVER);

            $minerCommand = new MinerCommand(
                new MinerSocket($minerStock),
                new MinerSocketDefaultParser()
            );

            $minerCommand->restart();
        }
    }

    private function checkIdentity(array $oldPools, array $newPools): bool
    {
        if (count($oldPools) != count($newPools)) {
            return false;
        }

        foreach ($oldPools as $oldPool) {
            foreach ($newPools as &$newPool) {
                if (
                    $newPool['url'] == $oldPool['url'] &&
                    $newPool['user'] == $oldPool['user'] &&
                    $newPool['password'] == $oldPool['pass'] &&
                    empty($newPool['reserved'])
                ) {
                    $newPool['reserved'] = 1;
                }
            }
        }

        foreach ($newPools as $pool) {
            if (empty($pool['reserved'])) {
                return false;
            }
        }

        return true;
    }

    private function toFormatPools($pools): array
    {
        $newFormatPools = [];

        foreach ($pools as $pool) {
            $newFormatPools[] = [
                'url'  => $pool['url'],
                'user' => $pool['user'],
                'pass' => $pool['password']
            ];
        }

        return $newFormatPools;
    }
}