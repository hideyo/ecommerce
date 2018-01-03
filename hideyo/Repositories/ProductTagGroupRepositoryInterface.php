<?php
namespace Hideyo\Repositories;

interface ProductTagGroupRepositoryInterface
{
    public function create(array $attributes);

    public function updateById(array $attributes, $id);
    
    public function destroy($id);

    public function selectAll();
    
    public function find($id);
}
