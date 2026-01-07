<?php

namespace App\Models;
use CodeIgniter\Model;

class PagamentoModel extends Model
{
    protected $table         = 'Pagamento';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['valor', 'forma_pagamento'];
    
    protected $validationRules = 
    [
        'valor' => 'required|numeric',
        'forma_pagamento' => 'required|in_list[pix,boleto,credito,debito,dinheiro]'
    ];
}