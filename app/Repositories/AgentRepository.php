<?php

namespace App\Repositories;
use App\Models\Agents;

class AgentRepository implements RepositoryInterface
{
    private $model;

    public function __construct()
    {
        $this->model = new Agents();
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
        $barang = $this->model->where('id',$id)->first();
        $barang->update(
            [
            'name' => $paramupdate['name'],
            'address' => $paramupdate['address'],
            'noTelp' =>$paramupdate['noTelp'],
            'gmaps'=>$paramupdate['gmaps'],
            ]
            );
        $barang->save();
    }
    public function insert($paraminsert)
    {
        $this->model->insert([
            'name' => $paraminsert['name'],
            'address' => $paraminsert['address'],
            'images' =>$paraminsert['images'],
            'noTelp' =>$paraminsert['noTelp'],
            'gmaps'=>$paraminsert['gmaps'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return ([
            'id'=>'sendAgent'.$paraminsert['name'],
            'name'=>$paraminsert['name']
        ]);
    }
    public function delete($id)
    {
        return $this->model::destroy($id);
    }
}
    
