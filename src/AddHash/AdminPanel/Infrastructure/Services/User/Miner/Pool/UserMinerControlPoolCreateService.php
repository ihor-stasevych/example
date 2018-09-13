<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Psr\Log\LoggerInterface;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2SCP;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2AuthPubKey;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2Connection;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2SCPFailException;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketDefaultParser;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2ConnectionFailException;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\UserMinerControlCommandInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthKeyIsNotExistsException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Miner\Pool\UserMinerNoValidUrlPoolException;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerControlPoolCreateServiceInterface;

class UserMinerControlPoolCreateService implements UserMinerControlPoolCreateServiceInterface
{
    const DEFAULT_CONFIG_NAME = 'bmminer.conf';

    const PATH_CONFIG_REMOTE_SERVER = '/config/' . self::DEFAULT_CONFIG_NAME;

    const INDEX_POOLS = 'pools';


    private $logger;

    private $allowedUrlRepository;

    public function __construct(LoggerInterface $logger, MinerAllowedUrlRepositoryInterface $allowedUrlRepository)
    {
        $this->logger = $logger;
        $this->allowedUrlRepository = $allowedUrlRepository;
    }

    /**
     * @param UserMinerControlCommandInterface $command
     * @param MinerStock $minerStock
     * @return array
     * @throws SSH2AuthFailException
     * @throws SSH2AuthKeyIsNotExistsException
     * @throws SSH2ConnectionFailException
     * @throws SSH2SCPFailException
     * @throws UserMinerNoValidUrlPoolException
     */
    public function execute(UserMinerControlCommandInterface $command, MinerStock $minerStock)
    {
        $data = [];

        $minerStockId = $minerStock->getId();

        if ($minerStockId != $command->getMinerId()) {
            return $data;
        }

        $newPools = $command->getPools();

        $this->checkAllowedUrl($newPools);

        $pathLocalConfigDir = getenv('PATH_CONFIG_POOLS') . $minerStockId . '/';
        $pathLocalConfigFile = $pathLocalConfigDir . static::DEFAULT_CONFIG_NAME;

        $oldPools = $this->getOldPools($pathLocalConfigFile);

        $isIdentity = $this->checkIdentity($oldPools, $newPools);

        if (true === $isIdentity) {
            return $data;
        }

        $pathKeys = getenv('PATH_SSH_KEYS') . $minerStockId . '/';
        $pathPublicKey = $pathKeys . getenv('DEFAULT_NAME_RSA_PUBLIC_KEY');
        $pathPrivateKey = $pathKeys . getenv('DEFAULT_NAME_RSA_PRIVATE_KEY');

        if (!file_exists($pathPublicKey) || !file_exists($pathPrivateKey)) {
            throw new SSH2AuthKeyIsNotExistsException('Auth key is not exists');
        }

        $connection = (new SSH2Connection($minerStock->getIp()))->getConnection();
        new SSH2AuthPubKey($connection, $minerStock->getUser(), $pathPublicKey, $pathPrivateKey);

        @mkdir($pathLocalConfigDir, 0777, true);
        $scp = new SSH2SCP($connection);
        $scp->fetch($pathLocalConfigFile, static::PATH_CONFIG_REMOTE_SERVER);

        $this->changeLocalConfig($pathLocalConfigFile, $newPools);

        $scp->send($pathLocalConfigFile, static::PATH_CONFIG_REMOTE_SERVER);

        $this->minerRestart($minerStock);

        return $data;
    }

    private function minerRestart(MinerStock $minerStock)
    {
        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketDefaultParser()
        );

        $minerCommand->restart();
    }

    private function changeLocalConfig(string $path, array $pools)
    {
        $tempConfigFile = $this->getLocalConfig($path);

        $tempConfigFile[static::INDEX_POOLS] = $this->toFormatPools($pools);

        file_put_contents(
            $path,
            json_encode($tempConfigFile, JSON_UNESCAPED_SLASHES)
        );
    }

    private function getOldPools(string $path): array
    {
        $oldPools = [];

        if (file_exists($path)) {
            $oldConfigFile = $this->getLocalConfig($path);
            $oldPools = $oldConfigFile[static::INDEX_POOLS];
        }

        return $oldPools;
    }

    private function getLocalConfig(string $path): array
    {
        return json_decode(
            file_get_contents($path),
            true
        );
    }

    /**
     * @param $pools
     * @throws UserMinerNoValidUrlPoolException
     */
    private function checkAllowedUrl(array $pools)
    {
        $uniqueUrls = $this->getUniqueUrls($pools);

        $getCountAllowedUrl = $this->allowedUrlRepository->getCountByValuesEnabledUrl($uniqueUrls);

        if ($getCountAllowedUrl != count($uniqueUrls)) {
            throw new UserMinerNoValidUrlPoolException('No valid url');
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

    private function toFormatPools(array $pools): array
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

    private function getUniqueUrls(array $pools)
    {
        $urls = [];

        foreach ($pools as $pool) {
            if (false === array_search($pool['url'], $urls)) {
                $urls[] = $pool['url'];
            }
        }

        return $urls;
    }
}