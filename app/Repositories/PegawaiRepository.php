<?php

namespace App\Repositories;
use App\Models\User;

class PegawaiRepository implements RepositoryInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new User();
    }

    public function all()
    {
        return $this->model;
    }
    public function find($id)
    {
        return $this->model->where('id',$id)->first();
    }
    public function update($id, $paramupdate)
    {
        $this->model::where('id', $id)->update($paramupdate);
    }
    public function insert($paraminsert)
    {
        $this->model->insert([
            'name' => $paraminsert['name'],
            'email' => $paraminsert['email'],
            'password' => bcrypt($paraminsert['password']),
            'adminVerified' => now(),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => $paraminsert['role_id']
        ]);
    }
    public function delete($id)
    {
        return $this->model::destroy($id);
    }
}
    
