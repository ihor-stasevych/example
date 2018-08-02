<?php

namespace App\AddHash\AdminPanel\Domain\Store\Product;


interface StoreProductUserVoteRepositoryInterface
{
    public function create(StoreProductUserVote $vote);

    public function getByUserIdAndProductId(int $userId, int $productId): ?StoreProductUserVote;

    public function getAvgByProductId(int $productId);
}