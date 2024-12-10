<?php
namespace App\Repositories;

class RepositoryManager
{
    protected $repositories = [];

    public function __construct()
    {
    
        $this->repositories = [
            'barang' => new BarangRepository(),
            'agent'=> new AgentRepository(),
            'mitra'=> new MitraRepository(),
            'pegawai'=> new PegawaiRepository()
        ];
    }

    public function getRepository($name)
    {
        if (!isset($this->repositories[$name])) {
            throw new \Exception("Repository {$name} not found.");
        }
        return $this->repositories[$name];
    }
}