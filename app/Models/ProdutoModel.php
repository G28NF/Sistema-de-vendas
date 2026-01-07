<?php

namespace App\Models;
use CodeIgniter\Model;

class ProdutoModel extends Model
{
    protected $table         = 'Produto';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['nome', 'descricao', 'preco', 'estoque'];
    
    protected $validationRules = 
    [
        'nome' => 'required|string|max_length[100]',
        'descricao' => 'required|string|max_length[255]',
        'preco' => 'required|numeric|greater_than_equal_to[0]',
        'estoque' => 'required|int|greater_than_equal_to[0]'
    ];
}