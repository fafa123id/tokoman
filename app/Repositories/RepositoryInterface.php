<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function update($id, $paramupdate);
    public function insert($paraminsert);
    public function delete($id);
    
}
