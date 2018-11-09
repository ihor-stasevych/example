<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\User\Miner\Pool;

use Doctrine\ORM\PersistentCollection;
use App\AddHash\AdminPanel\Domain\Miners\MinerStock;
use App\AddHash\AdminPanel\Domain\Miners\MinerStockPool;
use App\AddHash\AdminPanel\Domain\Miners\MinerAlgorithm;
use App\AddHash\AdminPanel\Domain\Miners\MinerAllowedUrl;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2SCP;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2Connection;
use App\AddHash\AdminPanel\Infrastructure\Miners\SSH2\SSH2AuthPubKey;
use App\AddHash\AdminPanel\Infrastructure\Miners\Extender\MinerSocket;
use App\AddHash\AdminPanel\Infrastructure\Miners\Commands\MinerCommand;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2SCPFailException;
use App\AddHash\AdminPanel\Domain\User\Order\UserOrderMinerRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthFailException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\MinerControlNoMainerException;
use App\AddHash\AdminPanel\Infrastructure\Miners\Parsers\MinerSocketDefaultParser;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2ConnectionFailException;
use App\AddHash\AdminPanel\Domain\Miners\Exceptions\NoCreatedDirConfigPoolsException;
use App\AddHash\AdminPanel\Domain\User\Services\UserGetAuthenticationServiceInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerStockPoolRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\Repository\MinerAllowedUrlRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Miners\SSH2\Exceptions\SSH2AuthKeyIsNotExistsException;
use App\AddHash\AdminPanel\Domain\User\Exceptions\Miner\Pool\UserMinerNoValidUrlPoolException;
use App\AddHash\AdminPanel\Domain\User\Services\Miner\Pool\UserMinerPoolCreateServiceInterface;
use App\AddHash\AdminPanel\Domain\User\Command\Miner\Pool\UserMinerControlPoolCreateCommandInterface;

final class UserMinerPoolCreateService implements UserMinerPoolCreateServiceInterface
{
    private const INDEX_POOLS = 'pools';


    private $authenticationService;

    private $userOrderMinerRepository;

    private $allowedUrlRepository;

    private $minerStockPoolRepository;

    private $pathRsaPublicKey;

    private $pathRsaPrivateKey;

    private $dirConfigPools;

    /**
     * UserMinerPoolCreateService constructor.
     * @param UserGetAuthenticationServiceInterface $authenticationService
     * @param UserOrderMinerRepositoryInterface $userOrderMinerRepository
     * @param MinerAllowedUrlRepositoryInterface $allowedUrlRepository
     * @param MinerStockPoolRepositoryInterface $minerStockPoolRepository
     * @param $pathRsaPublicKey
     * @param $pathRsaPrivateKey
     * @param $dirConfigPools
     * @throws SSH2AuthKeyIsNotExistsException
     */
    public function __construct(
        UserGetAuthenticationServiceInterface $authenticationService,
        UserOrderMinerRepositoryInterface $userOrderMinerRepository,
        MinerAllowedUrlRepositoryInterface $allowedUrlRepository,
        MinerStockPoolRepositoryInterface $minerStockPoolRepository,
        $pathRsaPublicKey,
        $pathRsaPrivateKey,
        $dirConfigPools
    )
    {
        $this->authenticationService = $authenticationService;
        $this->userOrderMinerRepository = $userOrderMinerRepository;
        $this->allowedUrlRepository = $allowedUrlRepository;
        $this->minerStockPoolRepository = $minerStockPoolRepository;
        $this->pathRsaPublicKey = $pathRsaPublicKey;
        $this->pathRsaPrivateKey = $pathRsaPrivateKey;
        $this->dirConfigPools = $dirConfigPools;

        $this->checkExistsKeysRsa();
    }

    /**
     * @param UserMinerControlPoolCreateCommandInterface $command
     * @throws MinerControlNoMainerException
     * @throws NoCreatedDirConfigPoolsException
     * @throws SSH2AuthFailException
     * @throws SSH2ConnectionFailException
     * @throws SSH2SCPFailException
     * @throws UserMinerNoValidUrlPoolException
     */
    public function execute(UserMinerControlPoolCreateCommandInterface $command): void
    {
        $user = $this->authenticationService->execute();

        $userOrderMiner = $this->userOrderMinerRepository->getByUserAndMinerStockId($user, $command->getMinerId());

        if (null === $userOrderMiner) {
            throw new MinerControlNoMainerException('No mainer');
        }

        /** @var MinerStock $minerStock */
        $minerStock = $userOrderMiner->getMiners()->first();

        $algorithm = $minerStock->infoMiner()->getAlgorithm();

        $newPools = $command->getPools();

        $this->checkAllowedUrl($algorithm, $newPools);

        $oldPools = $minerStock->getPool();

        $isIdentity = $this->isIdentityPools($oldPools, $newPools);

        if (true === $isIdentity) {
            return;
        }

        $pathConfigLocalDir = $this->dirConfigPools . $minerStock->getId() . '/';
        $pathConfigLocalFile = $pathConfigLocalDir . $minerStock->getConfig()->getFileName();

        $this->checkExistsDirTempConfig($pathConfigLocalDir);

        $connection = (new SSH2Connection($minerStock->getIp()))->getConnection();
        new SSH2AuthPubKey($connection, $this->pathRsaPublicKey, $this->pathRsaPrivateKey);

        $pathConfigRemoteServer = $minerStock->getConfig()->getPath();

        $scp = new SSH2SCP($connection);
        $scp->fetch($pathConfigLocalFile, $pathConfigRemoteServer);

        $this->changeLocalTempConfig($pathConfigLocalFile, $newPools);

        $scp->send($pathConfigLocalFile, $pathConfigRemoteServer);

        $this->saveNewPools($minerStock, $newPools);

        @unlink($pathConfigLocalFile);
        @rmdir($pathConfigLocalDir);

        $this->minerRestart($minerStock);
    }

    /**
     * @throws SSH2AuthKeyIsNotExistsException
     */
    private function checkExistsKeysRsa(): void
    {
        if (false === file_exists($this->pathRsaPublicKey) || false === file_exists($this->pathRsaPrivateKey)) {
            throw new SSH2AuthKeyIsNotExistsException('Auth key is not exists');
        }
    }

    /**
     * @param MinerAlgorithm $algorithm
     * @param array $pools
     * @throws UserMinerNoValidUrlPoolException
     */
    private function checkAllowedUrl(MinerAlgorithm $algorithm, array $pools): void
    {
        $uniqueUrls = $this->getUniqueUrls($pools);

        $allowedUrls = $this->allowedUrlRepository->getByValuesAndAlgorithm($algorithm, $uniqueUrls);

        $allowedUrlsValue = [];

        if (count($allowedUrls) !== count($uniqueUrls)) {
            /** @var MinerAllowedUrl $allowedUrl */
            foreach ($allowedUrls as $allowedUrl) {
                $allowedUrlsValue[] = $allowedUrl->getValue();
            }

            $errors = [];

            foreach ($pools as $position => $pool) {
                if (false === in_array($pool['url'], $allowedUrlsValue)) {
                    $errors['pools[' . $position . '][url]'] = ['No valid url'];
                }
            }

            throw new UserMinerNoValidUrlPoolException($errors);
        }
    }

    private function isIdentityPools(PersistentCollection $oldPools, array $newPools): bool
    {
        $countOldPools = count($oldPools);

        if ($countOldPools == count($newPools)) {
            $newPools = array_values($newPools);

            for ($i = 0; $i < $countOldPools; $i++) {
                /** @var MinerStockPool $oldPool */
                $oldPool = $oldPools[$i];
                $newPool = $newPools[$i];

                if (
                    $oldPool->getUserName() != $newPool['user'] ||
                    $oldPool->getUrl() != $newPool['url'] ||
                    $oldPool->getPassword() != $newPool['password']
                ) {
                    return false;
                }
            }

            return true;
        }

        return false;
    }

    /**
     * @param string $dir
     * @throws NoCreatedDirConfigPoolsException
     */
    private function checkExistsDirTempConfig(string $dir): void
    {
        if (false === is_dir($dir)) {

            if (false === @mkdir($dir, 0777, true)) {
                throw new NoCreatedDirConfigPoolsException('No created dir config pools');
            }
        }
    }

    private function getUniqueUrls(array $pools): array
    {
        $urls = [];

        foreach ($pools as $pool) {
            if (false === array_search($pool['url'], $urls)) {
                $urls[] = $pool['url'];
            }
        }

        return $urls;
    }

    private function changeLocalTempConfig(string $path, array $pools)
    {
        $tempConfigFile = json_decode(
            file_get_contents($path),
            true
        );

        $tempConfigFile[static::INDEX_POOLS] = $this->toFormatPools($pools);

        file_put_contents(
            $path,
            json_encode($tempConfigFile, JSON_UNESCAPED_SLASHES)
        );
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

    private function saveNewPools(MinerStock $minerStock, array $pools)
    {
        $this->minerStockPoolRepository->deleteByMinerStock($minerStock);

        $minerStockPools = [];

        foreach ($pools as $pool) {
            $minerStockPools[] = new MinerStockPool(
                $minerStock,
                $pool['user'],
                $pool['url'],
                $pool['password']
            );
        }

        $this->minerStockPoolRepository->saveAll($minerStockPools);
    }

    private function minerRestart(MinerStock $minerStock): void
    {
        $minerCommand = new MinerCommand(
            new MinerSocket($minerStock),
            new MinerSocketDefaultParser()
        );

        $minerCommand->restart();
    }
}