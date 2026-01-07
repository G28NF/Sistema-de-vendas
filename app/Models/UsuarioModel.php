<?php

namespace App\Models;
use CodeIgniter\Model;

class ClienteModel extends Model 
{
    protected $table         = 'Cliente';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nome', 'nascimento', 'cpf', 'rg', 'endereco', 'email', 'senha', 'telefone', 'teleforne2'];
    
    protected $validationRules = 
    [
        'nome' => 'required|min_length[15]|max_legth[150]',
        'cpf' => 'required|exact_length[11]|is_unique[Cliente.cpf,cpf,{id}]',
        'rg' => 'required|exact_length[11]|is_unique[Cliente.rg,rg,{id}]', 
        'nascimento' => 'required|valid_date[Y-m-d]',
        'endereco' => 'required|min_length[20],max_legth[200]',
        'email' => 'required|min_length[10],max_legth[150],is_unique[Cliente.email,email,{id}]',
        'senha' => 'required|min_legth[8],max_legth[255],is_unique{Cliente.senha,senha,{id}',
        'telefone' => 'required|exact_legth[13]|is_unique[Cliente.telefone,telefone,{id}]',
        'telefone2' => 'exact_legth[13]|is_unique[Cliente.telefone,telefone,{id}]'
    ];
} 