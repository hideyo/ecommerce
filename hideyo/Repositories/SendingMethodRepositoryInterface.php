<?php
namespace Hideyo\Repositories;

interface SendingMethodRepositoryInterface
{
    public function create(array $attributes);

    public function updateById(array $attributes, $id);

    public function destroy($id);
    
    public function selectAll();
    
    public function selectAllActiveByShopId($shopId);

    public function selectOneByShopIdAndId($shopId, $id);
    
    public function find($id);
}
