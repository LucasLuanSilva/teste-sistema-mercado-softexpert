<template>
    <div class="produto">
        <div class="modal fade" id="cadastroProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastroProdutoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="cadastroProdutoLabel">Cadastro Produto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                        <div class="col-12">
                            <label for="inputCodigo">Código</label>
                            <input id="inputCodigo" type="text" class="form-control" v-model="produtoModal.codigo" disabled>
                        </div>
                        <div class="col-12">
                            <label for="inputDescricao">Descrição</label>
                            <input id="inputDescricao" type="text" class="form-control" v-model="produtoModal.descricao">
                        </div>
                        <div class="col-12">
                            <label for="selectTipo">Tipo</label>
                            <select id="selectTipo" class="form-select" v-model="produtoModal.codigo_tipo">
                            <option value="0" selected></option>
                            <option v-for="tipo in tipos" :value="tipo.codigo">{{ tipo.descricao }}</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label for="inputValor">Valor</label>
                            <MoneyInput 
                            id="inputValor" 
                            class="form-control"
                            v-model="produtoModal.valor"
                            :value="produtoModal.valor"
                            :options="{
                                'locale': 'pt-BR',
                                'currency': 'USD',
                                'currencyDisplay': 'hidden',
                                'precision': 2,
                                'autoDecimalDigits': true
                            }"
                            />
                        </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                        <button v-if="produtoModal.codigo > 0" @click="alterarProduto" type="button" class="btn btn-success">Confirmar</button>
                        <button v-else type="button" @click="incluirProduto" class="btn btn-success">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    
        <h3>Produtos</h3>
    
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
                <td>{{ formatarValor(produto.valor) }}</td>
                </tr>
            </tbody>
        </table>

        <button @click="abrirIncluirProduto" type="button" class="btn btn-success me-3">
          Incluir
        </button>
    
        <button @click="abrirAlterarProduto" type="button" class="btn btn-primary me-3">
          Alterar
        </button>
    
        <button @click="excluirProduto" type="button" class="btn btn-danger">
          Excluir
        </button>
    </div>
</template>
    
<script type="text/javascript">
    import { Modal } from 'bootstrap'
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
                produtos: [],
                produtoSelecionado: {},
                produtoModal: {
                    codigo: "",
                    descricao: "",
                    valor: 0,
                    codigo_tipo: 0
                },
                tipos: [],
                myModal: null
            }
        },
        mounted() {
            this.carregaProdutos();

            this.myModal = new Modal(document.getElementById('cadastroProduto'), {
                keyboard: false
            });

            this.carregaTipos();
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
            carregaTipos() {
                axios.get('http://localhost:8080/tipo/produto')
                .then((response) => {
                    this.tipos = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            abrirAlterarProduto() {
                if (Object.keys(this.produtoSelecionado).length === 0) {
                    alert("Selecione um produto!");
                    return;
                }

                this.produtoModal = Object.assign({}, this.produtoSelecionado);

                this.myModal.show();
            },
            abrirIncluirProduto() {
                this.produtoModal = {
                    codigo: "",
                    descricao: "",
                    valor: 0,
                    codigo_tipo: 0
                };

                this.myModal.show();
            },
            alterarProduto() {
                let erro = this.validaCamposProduto(
                    this.produtoModal.descricao,
                    this.produtoModal.codigo_tipo,
                    this.produtoModal.valor
                );

                if (erro != "") {
                    alert(erro);
                    return;
                }

                axios.put('http://localhost:8080/produto', this.produtoModal)
                .then((response) => {
                    this.myModal.hide();
                    alert("Produto alterado com sucesso!");
                    this.carregaProdutos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            validaCamposProduto(descricao, codigo_tipo, valor) {
                let erro = "";
                console.log(valor);
                if (descricao.trim() == "") {
                    erro = "Informe um descrição.";
                } else if (!(codigo_tipo > 0)) {
                    erro = "Informe um tipo.";
                } else if (
                    !(valor > 0) || 
                    valor == null || 
                    valor == ""
                ) {
                    erro = "Informe um valor.";
                }

                return erro;
            },
            incluirProduto() {
                let erro = this.validaCamposProduto(
                    this.produtoModal.descricao,
                    this.produtoModal.codigo_tipo,
                    this.produtoModal.valor
                );

                if (erro != "") {
                    alert(erro);
                    return;
                }

                axios.post('http://localhost:8080/produto', this.produtoModal)
                .then((response) => {
                    this.myModal.hide();
                    alert("Produto incluido com sucesso!");
                    this.carregaProdutos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            excluirProduto() {
                if (Object.keys(this.produtoSelecionado).length === 0) {
                    alert("Selecione um produto!");
                    return;
                }

                axios.delete('http://localhost:8080/produto', { params: { codigo: this.produtoSelecionado.codigo } })
                .then((response) => {
                    this.myModal.hide();
                    alert("Produto excluído com sucesso!");
                    this.carregaProdutos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            formatarValor(valor) {
                let valorFormatado = Number(valor).toLocaleString('pt-br', { maximumFractionDigits: 2, minimumFractionDigits: 2 });
                return valorFormatado;
            },
        }
    }
</script>