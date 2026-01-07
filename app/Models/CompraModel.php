<?php

namespace App\Models;
use CodeIgniter\Model;

class CompraModel extends Model
{
    protected $table         = 'Compra';
    protected $primaryKey    = 'id';

    protected $allowedFields = ['nome_cliente', 'cliente_id','cpf_cliente','email_cliente','rg_cliente','quantidade','preco_total','data_compra', 'status'];

    protected $validationRules =
    [
        'nome_cliente' => 'required|min_length[10]|max_length[150]',
        'cliente_id'   => 'required|integer',
        'cpf_cliente'  => 'required|exact_length[11]',
        'email_cliente'=> 'required|valid_email|max_length[150]',
        'rg_cliente'   => 'required|exact_length[9]',
        'quantidade'   => 'required|integer|greater_than[0]',
        'preco_total'  => 'required|numeric|greater_than_equal_to[0]',
        'data_compra' => 'required|valid_date[Y-m-d]',
        'status' => 'required|in_list[aprovado,pendente,cancelado]'
    ];
}
