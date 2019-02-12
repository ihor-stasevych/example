<?php

namespace App\AddHash\MinerPanel\Infrastructure\Services\Miner\MinerPool;

use Doctrine\ORM\PersistentCollection;
use App\AddHash\MinerPanel\Domain\Miner\Miner;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\MinerPool;
use App\AddHash\MinerPanel\Infrastructure\Miner\Parsers\Parser;
use App\AddHash\MinerPanel\Domain\Miner\MinerRepositoryInterface;
use App\AddHash\MinerPanel\Infrastructure\Miner\Extender\MinerSocket;
use App\AddHash\MinerPanel\Domain\Miner\MinerCredential\MinerCredential;
use App\AddHash\MinerPanel\Infrastructure\Miner\ApiCommand\MinerApiCommand;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\MinerPoolRepositoryInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidMinerException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidSCPSendException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidSSHAuthException;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Command\MinerPoolCreateCommandInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidSCPFetchException;
use App\AddHash\MinerPanel\Domain\Miner\MinerPool\Services\MinerPoolCreateServiceInterface;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidCredentialSSHException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateInvalidSSHConnectionException;
use App\AddHash\MinerPanel\Domain\Miner\Exceptions\MinerPoolCreateNoCreatedDirConfigPoolsException;

final class MinerPoolCreateService implements MinerPoolCreateServiceInterface
{
    private const INDEX_POOLS = 'pools';

    private const REMOTE_DIR_CONFIG = '/config/';


    private $minerRepository;

    private $minerPoolRepository;

    private $dirConfigPools;

    public function __construct(
        MinerRepositoryInterface $minerRepository,
        MinerPoolRepositoryInterface $minerPoolRepository,
        string $dirConfigPools
    )
    {
        $this->minerRepository = $minerRepository;
        $this->minerPoolRepository = $minerPoolRepository;
        $this->dirConfigPools = $dirConfigPools;
    }

    /**
     * @param MinerPoolCreateCommandInterface $command
     * @throws MinerPoolCreateInvalidCredentialSSHException
     * @throws MinerPoolCreateInvalidMinerException
     * @throws MinerPoolCreateInvalidSCPFetchException
     * @throws MinerPoolCreateInvalidSCPSendException
     * @throws MinerPoolCreateInvalidSSHAuthException
     * @throws MinerPoolCreateInvalidSSHConnectionException
     * @throws MinerPoolCreateNoCreatedDirConfigPoolsException
     */
    public function execute(MinerPoolCreateCommandInterface $command): void
    {
        $minerId = $command->getMinerId();

        var_dump('Start pool create');

        $miner = $this->minerRepository->getMinerAndPools($minerId);

        if (null === $miner) {
            throw new MinerPoolCreateInvalidMinerException('Invalid miner');
        }

        $oldPools = $miner->getPools();
        $newPools = $command->getPools();

        //if (false === $this->isIdentityPools($oldPools, $newPools)) {
            $miner->setStatusPoolOff();
            $this->minerRepository->save($miner);

            $loginSsh = $miner->getCredential()->getLoginSsh();

            if (null === $loginSsh) {
                throw new MinerPoolCreateInvalidCredentialSSHException('Invalid credential SSH');
            }

            $connection = @ssh2_connect(
                $miner->getCredential()->getIp(),
                $miner->getCredential()->getPortSsh()
            );

            if (false === $connection) {
                throw new MinerPoolCreateInvalidSSHConnectionException('Invalid SSH connection');
            }

            $auth = @ssh2_auth_password(
                $connection,
                $loginSsh,
                $miner->getCredential()->getPasswordSsh()
            );

            if (false === $auth) {
                throw new MinerPoolCreateInvalidSSHAuthException('Invalid SSH auth');
            }

            $configName = $miner->getConfig()->getName();

            $remotePathFile = static::REMOTE_DIR_CONFIG . $configName;
            $localDir = $this->dirConfigPools . $minerId . '/';

            $this->checkExistsDirTempConfig($localDir);

            $localPathFile = $localDir . $configName;

            $isScpFetch = @ssh2_scp_recv($connection, $remotePathFile, $localPathFile);

            if (false === $isScpFetch) {
                throw new MinerPoolCreateInvalidSCPFetchException('Invalid SCP fetch');
            }

            $this->changeLocalTempConfig($localPathFile, $newPools);

            $isScpSend = @ssh2_scp_send($connection, $localPathFile, $remotePathFile);

            if (false === $isScpSend) {
                throw new MinerPoolCreateInvalidSCPSendException('Invalid SCP send');
            }

            //$this->saveNewPools($miner, $newPools);

            @unlink($localPathFile);
            @rmdir($localDir);

            $this->minerRestart($miner->getCredential());

            var_dump('End pool create');
        //}
    }

    private function isIdentityPools(PersistentCollection $oldPools, array $newPools): bool
    {
        $countOldPools = $oldPools->count();

        if ($countOldPools !== count($newPools)) {
            return false;
        }

        $newPools = array_values($newPools);

        for ($i = 0; $i < $countOldPools; $i++) {
            $oldPool = $oldPools[$i];
            $newPool = $newPools[$i];

            if (
                $oldPool->getWorker() != $newPool['worker'] ||
                $oldPool->getUrl() != $newPool['url'] ||
                $oldPool->getPassword() != $newPool['password']
            )
            {
                return false;
            }
        }

        return true;
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
                'user' => $pool['worker'],
                'pass' => $pool['password'] ?? '',
            ];
        }

        return $newFormatPools;
    }

    private function saveNewPools(Miner $miner, array $pools): void
    {
        $this->minerPoolRepository->deleteByMiner($miner);

        $data = [];

        foreach ($pools as $pool) {
            $data[] = new MinerPool(
                $pool['worker'],
                $pool['url'],
                $pool['password'],
                $miner
            );
        }

        $this->minerPoolRepository->saveAll($data);
    }

    private function minerRestart(MinerCredential $minerCredential): void
    {
        $minerApiCommand = new MinerApiCommand(
            new MinerSocket($minerCredential),
            new Parser()
        );

        $minerApiCommand->restart();
    }

    private function checkExistsDirTempConfig(string $dir): void
    {
        if (false === is_dir($dir)) {

            if (false === @mkdir($dir, 0777, true)) {
                throw new MinerPoolCreateNoCreatedDirConfigPoolsException('No created dir config pools');
            }
        }
    }
}