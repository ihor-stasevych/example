<?php

namespace App\AddHash\AdminPanel\Infrastructure\Services\Store\Product;

use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductUserVote;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote\UserVoteExistException;
use App\AddHash\AdminPanel\Domain\Store\Product\StoreProductUserVoteRepositoryInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote\ProductIsNotExistException;
use App\AddHash\AdminPanel\Domain\Store\Product\Exceptions\Vote\RatingIsNotValidException;
use App\AddHash\AdminPanel\Domain\Store\Product\Command\StoreProductVoteCreateCommandInterface;
use App\AddHash\AdminPanel\Domain\Store\Product\Services\StoreProductVoteCreateServiceInterface;

class StoreProductVoteCreateService implements StoreProductVoteCreateServiceInterface
{
    const ASSESSMENTS = [1, 2, 3, 4, 5];

    const ROUND_VOTE = 2;

    private $productRepository;

    private $productUserVoteRepository;

    private $tokenStorage;

	public function __construct(
	    StoreProductRepositoryInterface $productRepository,
        StoreProductUserVoteRepositoryInterface $productUserVoteRepository,
        TokenStorageInterface $tokenStorage
    )
	{
        $this->productRepository = $productRepository;
        $this->productUserVoteRepository = $productUserVoteRepository;
        $this->tokenStorage = $tokenStorage;
	}

    /**
     * @param StoreProductVoteCreateCommandInterface $command
     * @throws RatingIsNotValidException
     * @throws ProductIsNotExistException
     * @throws UserVoteExistException
     */
	public function execute(StoreProductVoteCreateCommandInterface $command)
	{
        if (false === array_search($command->getValue(), static::ASSESSMENTS)) {
            throw new RatingIsNotValidException('Rating is not valid');
        }

        $product = $this->productRepository->findById($command->getProductId());

        if (null === $product) {
            throw new ProductIsNotExistException('Product id is not valid');
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $userVote = $this->productUserVoteRepository->getByUserIdAndProductId($user->getId(), $command->getProductId());

        if (null !== $userVote) {
            throw new UserVoteExistException('User vote is already exists');
        }

        $userVote = new StoreProductUserVote($command->getValue());
        $userVote->setUser($user);
        $userVote->setProduct($product);

        $this->productUserVoteRepository->create($userVote);

        //recalculation and save vote
        $vote = $this->productUserVoteRepository->getAvgByProductId($command->getProductId());

        $product->setVote(round($vote, static::ROUND_VOTE));

        $this->productRepository->save($product);
	}
}