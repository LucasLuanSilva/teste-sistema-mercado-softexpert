<template>
    <div id="venda">
        <div class="modal fade" id="listagemProdutos" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="listagemProdutosLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="listagemProdutosLabel">Selecione um produto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-default border">
                                <thead>
                                    <tr>
                                    <th v-for="coluna in colunas" scope="col">{{ coluna }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr @click="cliqueProduto($event, produto)" v-for="produto in produtos">
                                    <td>{{ produto.codigo }}</td>
                                    <td>{{ produto.descricao }}</td>
                                    <td>{{ formatarValor(produto.valor_unitario) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" @click="setProduto" class="btn btn-success">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="modal modal-lg fade" id="listagemVendas" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="listagemVendasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="listagemVendasLabel">Vendas</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-default border">
                                <thead>
                                    <tr>
                                    <th v-for="coluna in colunasVendas" scope="col">{{ coluna }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="venda in vendas">
                                    <td>{{ venda.codigo }}</td>
                                    <td>{{ formatarData(venda.data_inclusao) }}</td>
                                    <td>{{ formatarValor(venda.imposto) }}</td>
                                    <td>{{ formatarValor(venda.valor) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
  
        <div class="row">
            <div class="col-6">
                <h5>Itens venda</h5>
                </div>
                <div class="col-6">
                <h5>Incluir Produto</h5>
            </div>
        </div>
        <div class="row">
            <div id="containerProdutos" class="col-6">
            <table class="table border">
                <thead>
                    <tr>
                        <th v-for="coluna in colunasItens" scope="col">{{ coluna }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(produto, index) in itensVenda">
                        <td>{{ produto.codigo }}</td>
                        <td>{{ produto.descricao }}</td>
                        <td>{{ formatarValor(produto.quantidade) }}</td>
                        <td>{{ formatarValor(produto.valor_unitario) }}</td>
                        <td>{{ formatarValor((produto.quantidade * produto.valor_unitario) * (produto.imposto_unitario / 100)) }}</td>
                        <td>{{ formatarValor((produto.quantidade * produto.valor_unitario) + (produto.quantidade * produto.valor_unitario) * (produto.imposto_unitario / 100)) }}</td>
                        <td @click="excluirItem(index)" id="excluirItem" style="color: red; font-weight: bold;">x</td>
                    </tr>
                </tbody>
            </table>
            </div>
            <div class="col-6">
                <div class="row">
                    <div class="col-2">
                        <label for="inputCodigo">Cód.</label>
                        <div class="input-group mb-3">
                            <button style="width: 30px;margin: 0px;padding: 4px;" class="btn btn-primary" type="button" id="button-addon1" @click="abrirModalProdutos">&#128269;</button>
                            <input id="inputCodigo" type="text" class="form-control" aria-describedby="button-addon1" v-model="produto.codigo" disabled>
                        </div>
                    </div>
                    <div class="col-5">
                        <label for="inputDescricao">Descrição</label>
                        <input id="inputDescricao" type="text" class="form-control" v-model="produto.descricao" disabled>
                    </div>
                        <div class="col-2">
                        <label for="inputValor">Valor</label>
                        <input id="inputValor" type="text" class="form-control" :value="formatarValor(produto.valor_unitario)" disabled>
                    </div>
                    <div class="col-2">
                        <label for="inputQuantidade">Qtd.</label>
                        <MoneyInput 
                            id="inputQuantidade" 
                            class="form-control"
                            v-model="produto.quantidade"
                            :value="produto.quantidade"
                            :options="{
                                'locale': 'pt-BR',
                                'currency': 'USD',
                                'currencyDisplay': 'hidden',
                                'precision': 2,
                                'autoDecimalDigits': true
                            }"
                        />
                    </div>
                    <div class="col-1">
                        <button :style="'margin-top: 23px;'" @click="incluirItem" type="button" class="btn btn-success">
                            ✓
                        </button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6">
                        <label for="inputTotalImposto">Total Impostos</label>
                        <input id="inputTotalImposto" type="text" class="form-control" :value="formatarValor(totalImposto)" disabled>
                    </div>
                    <div class="col-6">
                        <label for="inputTotalVenda">Total Venda</label>
                        <input id="inputTotalVenda" type="text" class="form-control" :value="formatarValor(totalVenda)" disabled>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <button @click="finalizarVenda" type="button" class="btn btn-success btn-large">
                            Finalizar Venda
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button @click="cancelarVenda" type="button" class="btn btn-warning text-white btn-large">
                            Cancelar Venda
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <button @click="abrirModalVendas" type="button" class="btn btn-primary btn-large">
                            Vendas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script type="text/javascript">
    import { Modal } from 'bootstrap';
    import axios from 'axios';
    import MoneyInput from "./MoneyInput.vue";
    export default {
        components: {
            MoneyInput
        },
        data() {
            return {
                colunas: [
                    "Código",
                    "Descrição",
                    "Valor"
                ],
                colunasItens: [
                    "Código",
                    "Descrição",
                    "Qtd",
                    "Valor",
                    "Imposto",
                    "Valor Total",
                    ""
                ],
                produtos: [],
                produtoSelecionado: {},
                produto: {
                    codigo: "",
                    descricao: "",
                    valor_unitario: "",
                    quantidade: 1,
                    imposto_unitario: 0
                },
                itensVenda: [],
                modalProdutos: null,
                totalImposto: 0,
                totalVenda: 0,
                modalVendas: null,
                colunasVendas: [
                    "Código",
                    "Data",
                    "Imposto",
                    "Valor Total"
                ],
                vendas: []
            }
        },
        mounted() {
            this.modalProdutos = new Modal(document.getElementById('listagemProdutos'), {
                keyboard: false
            });
    
            this.modalVendas = new Modal(document.getElementById('listagemVendas'), {
                keyboard: false
            });
        },
        methods: {
            cliqueProduto(evt, produto) {
                var elements = document.getElementsByTagName("tr");
                Array.prototype.forEach.call(elements, function(element) {
                    element.classList.remove("selected");
                });
        
                evt.target.parentNode.classList.add("selected");
                
                this.produtoSelecionado = produto;
            },
            carregaProdutos() {
                var elements = document.getElementsByTagName("tr");
                Array.prototype.forEach.call(elements, function(element) {
                    element.classList.remove("selected");
                });
                this.produtoSelecionado = {};
        
                axios.get('http://localhost:8080/produto')
                .then((response) => {
                    this.produtos = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            abrirModalProdutos() {
                var elements = document.getElementsByTagName("tr");
                Array.prototype.forEach.call(elements, function(element) {
                    element.classList.remove("selected");
                });
                this.produtoSelecionado = {};
        
                this.carregaProdutos();
        
                this.modalProdutos.show();
            },
            abrirModalVendas() {
                this.carregaVendas();
        
                this.modalVendas.show();
            },
            setProduto() {
                this.produto = {
                    codigo: this.produtoSelecionado.codigo,
                    descricao: this.produtoSelecionado.descricao,
                    valor_unitario: this.produtoSelecionado.valor_unitario,
                    quantidade: 1,
                    imposto_unitario: this.produtoSelecionado.imposto_unitario
                };
        
                this.modalProdutos.hide();
            },
            validaCamposItem(codigo, quantidade) {
                let erro = "";
        
                if (!(codigo > 0)) {
                    erro = "Informe um produto.";
                } else if (!(quantidade > 0)) {
                    erro = "Informe uma quantidade válida.";
                }
        
                return erro;
            },
            incluirItem() {
                let erro = this.validaCamposItem(
                    this.produto.codigo,
                    this.produto.quantidade
                );
        
                if (erro != "") {
                    alert(erro);
                    return;
                }
        
                this.itensVenda.push(this.produto);
        
                this.produto = {
                    codigo: "",
                    descricao: "",
                    valor_unitario: "",
                    quantidade: 1,
                    imposto_unitario: 0
                }
        
                this.totalizaVenda();
            },
            excluirItem(index) {
                let itens = [];
        
                for (var i in this.itensVenda) {
                    if (index != i) {
                        itens.push(this.itensVenda[i])
                    }
                }
        
                this.itensVenda = itens;
        
                this.totalizaVenda();
            },
            totalizaVenda() {
                let totalImposto = 0;
                let totalProduto = 0;
            
                this.itensVenda.forEach((item) => {
                    totalProduto += Number(item.quantidade) * Number(item.valor_unitario);
                    totalImposto += (Number(item.quantidade) * Number(item.valor_unitario)) * (Number(item.imposto_unitario) / 100);
                });
        
                this.totalImposto = totalImposto;
                this.totalVenda = totalProduto + totalImposto;
            },
            formatarValor(valor) {
                let valorFormatado = Number(valor).toLocaleString('pt-br', { maximumFractionDigits: 2, minimumFractionDigits: 2 });
                return valorFormatado;
            },
            formatarData(data) {
                const dataObj = new Date(data);
                if (isNaN(dataObj.getTime())) {
                    return 'Data inválida!';
                }
                const dia = ('0' + dataObj.getDate()).slice(-2);
                const mes = ('0' + (dataObj.getMonth() + 1)).slice(-2);
                const ano = dataObj.getFullYear();
                return `${dia}/${mes}/${ano}`;
            },
            finalizarVenda() {
                if (!(this.itensVenda.length > 0)) {
                    alert("Inclua ao menos um item ao pedido.");
                    return;
                }
        
                let body = {
                    itens: this.itensVenda,
                    imposto:  this.totalImposto,
                    valor: this.totalVenda
                };
        
                axios.post('http://localhost:8080/venda', body)
                .then((response) => {
                    console.log(response)
                    alert("Venda finalizada com sucesso!");
                    this.cancelarVenda();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            cancelarVenda() {
                this.itensVenda = [];
                this.totalVenda = 0;
                this.totalImposto = 0;
            },
            carregaVendas() {
                axios.get('http://localhost:8080/venda')
                .then((response) => {
                    this.vendas = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            }
        }
    }
</script>
  
<style scoped>
    #venda {
        margin: 5px!important;
    }

    #containerProdutos {
        background-color: rgba(145, 145, 145, 0.5);
        height: 70vh;
        overflow-x: hidden;
    }
    
    table {
        margin-top: 10px;
        margin-bottom: 10px;
        background-color: white;
    }

    #excluirItem {
        cursor: pointer;
    }

    .btn-large {
        width: 100%;
    }

</style>