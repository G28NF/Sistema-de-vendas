<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PagamentoModel;
use App\Models\ProdutoModel;
use App\Models\PedidoModel;

class ControllerPagamento extends BaseController
{
    protected PagamentoModel $PagamentoModel;
    protected ProdutoModel $ProdutoModel;
    protected PedidoModel $PedidoModel;

    public function  __construct()
    {
        $this->PagamentoModel = new PagamentoModel();
        $this->ProdutoModel   = new ProdutoModel();
        $this->PedidoModel    = new PedidoModel();
    }

    public function compra()
    {
        $codigoProduto = $this->request->getPost('codigo_produto');
        $produto = $this->ProdutoModel->where('codigo_produto', $codigoProduto)->first();

        if(!$produto)
        {
            return redirect()->back()->with('erro', 'Produto não encontrado.')->withInput();
        }

        $quantidadeProduto = $this->request->getPost('quantidade_produto');
        $valorTotal = $produto['preco'] * $quantidadeProduto;

        $pagamento = $this->request->getPost('forma_pagamento');
        $ValidarPagamento = $this->PagamentoModel->where('forma_pagamento', $pagamento)->first();

        if(!$ValidarPagamento)
        {
            return redirect()->back()->with('erro', 'Forma de pagamento inválida.')->withInput();
        }

        $email = session('email');
        $nome = session('nome');
        $cpf = session('cpf');

        $this->PedidoModel->insert([
            'codigo_produto' => $codigoProduto,
            'valor_total' => $valorTotal,
            'quantidade_produto' => $quantidadeProduto,
            'forma_pagamento' => $pagamento,
            'email_cliente' => $email,
            'nome_cliente' => $nome,
            'cpf_cliente' => $cpf,
            'data_pedido' => date('Y-m-d H:i:s')
        ]);
    }
}