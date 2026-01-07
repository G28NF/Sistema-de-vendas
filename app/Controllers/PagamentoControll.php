<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\PagamentoModel;
use App\Models\ProdutoModel;
use App\Models\CompraModel;

class ControllerPagamento extends BaseController
{
    protected PagamentoModel $PagamentoModel;
    protected ProdutoModel $ProdutoModel;
    protected CompraModel $CompraModel;

    public function  __construct()
    {
        $this->PagamentoModel = new PagamentoModel();
        $this->ProdutoModel   = new ProdutoModel();
        $this->CompraModel    = new CompraModel();
    }

    public function pedido()
    {
        $codigoProduto = $this->request->getPost('codigo_produto');
        $produto = $this->ProdutoModel->where('codigo', $codigoProduto)->first();

        if(!$produto)
        {
            return redirect()->back()->with('erro', 'Produto não encontrado.')->withInput();
        }
        
        $quantidadeProduto = $this->request->getPost('quantidade_produto');

        if($quantidadeProduto > $produto['estoque'])
        {
            return redirect()->back()->with('erro', 'Quantidade solicitada excede o estoque disponível.')->withInput();
        }

        $pagamento = $this->request->getPost('forma_pagamento');
        $ValidarPagamento = $this->PagamentoModel->where('forma_pagamento', $pagamento)->first();

        if(!$ValidarPagamento)
        {
            return redirect()->back()->with('erro', 'Forma de pagamento inválida.')->withInput();
        }

        $valorTotal = $produto['preco'] * $quantidadeProduto;

        $email = session('email');
        $nome = session('nome');
        $cpf = session('cpf');
        $id = session('id');
        $rg = session('rg');

        $this->CompraModel->insert([
            'cliente_id' => $id,
            'cpf_cliente' => $cpf,
            'email_cliente' => $email,
            'nome_cliente' => $nome,
            'quantidade' => $quantidadeProduto,
            'preco_total' => $valorTotal,
            'rg_cliente' => $rg,
            'data_compra' => date('Y-m-d H:i:s'),
            'status' => 'pendente'
        ]);

        $this->PagamentoModel->insert([
            'valor' => $produto['preco'] * $quantidadeProduto,
            'forma_pagamento' => $pagamento
        ]);
    }

    public function confirmarPagamento()
    {
        $idPagamento = $this->request->getPost('id_pagamento');
        $pagamento = $this->PagamentoModel->where('id', $idPagamento)->first();

        if(!$pagamento)
        {
            return redirect()->back()->with('erro', 'Pagamento não encontrado.')->withInput();
        }

        $this->CompraModel->update($pagamento['id'], ['status' => 'aprovado']);

        return redirect()->back()->with('sucesso', 'Pagamento confirmado com sucesso.');
    }

    public function atualizarEstoque()
    {
        $codigoProduto = $this->request->getPost('codigo_produto');
        $produto = $this->ProdutoModel->where('codigo', $codigoProduto)->first();

        if(!$produto)
        {
            return redirect()->back()->with('erro', 'Produto não encontrado.')->withInput();
        }

        $quantidadeProduto = $this->request->getPost('quantidade_produto');

        if($quantidadeProduto > $produto['estoque'])
        {
            return redirect()->back()->with('erro', 'Quantidade solicitada excede o estoque disponível.')->withInput();
        }
        
        $novoEstoque = $produto['estoque'] - $quantidadeProduto;

        $this->ProdutoModel->update($produto['id'], ['estoque' => $novoEstoque]);

        return redirect()->back()->with('sucesso', 'Compra realizada com sucesso!');
    }
}