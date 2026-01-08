<?php

namespace App\Models;
use CodeIgniter\Model;

class ClienteModel extends Model 
{
    protected $table         = 'Cliente';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nome', 'nascimento', 'cpf', 'rg', 'endereco', 'email', 'senha', 'telefone'];
    
    protected $validationRules = 
    [
        'nome' => 'required|min_length[15]|max_length[150]',
        'cpf' => 'required|exact_length[11]|is_unique[Cliente.cpf,id,{id}]',
        'rg' => 'required|exact_length[11]|is_unique[Cliente.rg,id,{id}]', 
        'nascimento' => 'required|valid_date[Y-m-d]',
        'endereco' => 'required|min_length[20]|max_length[200]',
        'email' => 'required|min_length[10]|max_length[150]|is_unique[Cliente.email,id,{id}]',
        'senha' => 'required|min_length[8]|max_length[255]',
        'telefone' => 'required|exact_length[13]|is_unique[Cliente.telefone,id,{id}]'
    ];
} 