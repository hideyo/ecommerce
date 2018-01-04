<?php
namespace Hideyo\Repositories;

interface ProductAmountSeriesRepositoryInterface
{
    public function create(array $attributes, $productId);

    public function updateById(array $attributes, $productId, $id);
    
    public function selectAll();

    public function selectAllActiveByShopId($shopId);
    
    public function find($id);
}
