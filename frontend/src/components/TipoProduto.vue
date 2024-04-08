<template>
    <div class="tipoProduto">
        <div class="modal fade" id="cadastroTipoProduto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="cadastroTipoProdutoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="cadastroTipoProdutoLabel">Cadastro Tipo Produto</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <label for="inputCodigo">Código</label>
                                <input id="inputCodigo" type="text" class="form-control" v-model="tipoModal.codigo" disabled>
                            </div>
                            <div class="col-12">
                                <label for="inputDescricao">Descrição</label>
                                <input id="inputDescricao" type="text" class="form-control" v-model="tipoModal.descricao">
                            </div>
                            <div class="col-12">
                                <label for="inputImposto">Imposto</label>
                                <MoneyInput 
                                    id="inputImposto" 
                                    class="form-control"
                                    v-model="tipoModal.imposto" 
                                    :value="tipoModal.imposto"
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
                        <button v-if="tipoModal.codigo > 0" @click="alterarTipo" type="button" class="btn btn-success">Confirmar</button>
                        <button v-else type="button" @click="incluirTipo" class="btn btn-success">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
  
        <h3>Tipos</h3>
  
        <table id="gridTipos" class="table table-default border">
            <thead>
                <tr>
                    <th v-for="coluna in colunas" scope="col">{{ coluna }}</th>
                </tr>
            </thead>
            <tbody>
                <tr @click="cliqueTipo($event, tipo)" v-for="tipo in tipos">
                    <td>{{ tipo.codigo }}</td>
                    <td>{{ tipo.descricao }}</td>
                    <td>{{ formatarValor(tipo.imposto) }}</td>
                </tr>
            </tbody>
        </table>
  
            <button @click="abrirIncluirTipo" type="button" class="btn btn-success me-3">
                Incluir
            </button>
        
            <button @click="abrirAlterarTipo" type="button" class="btn btn-primary me-3">
                Alterar
            </button>
        
            <button @click="excluirTipo" type="button" class="btn btn-danger">
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
                "Imposto"
                ],
                tipos: [],
                tipoSelecionado: {},
                tipoModal: {
                codigo: "",
                descricao: "",
                imposto: 0
                },
                myModal: null
            }
        },
        mounted() {
            this.carregaTipos();

            this.myModal = new Modal(document.getElementById('cadastroTipoProduto'), {
                keyboard: false
            });
        },
        methods: {
            cliqueTipo(evt, tipo) {
                var elements = document.getElementsByTagName("tr");
                Array.prototype.forEach.call(elements, function(element) {
                    element.classList.remove("selected");
                });

                evt.target.parentNode.classList.add("selected");
                
                this.tipoSelecionado = tipo;
            },
            async carregaTipos() {
                var elements = document.getElementsByTagName("tr");
                Array.prototype.forEach.call(elements, function(element) {
                    element.classList.remove("selected");
                });
                this.tipoSelecionado = {};

                await axios.get('http://localhost:8080/tipo/produto')
                .then((response) => {
                    console.log(response.data)
                    this.tipos = response.data;
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            abrirAlterarTipo() {
                if (Object.keys(this.tipoSelecionado).length === 0) {
                    alert("Selecione um tipo!");
                    return;
                }

                this.tipoModal = Object.assign({}, this.tipoSelecionado);

                this.myModal.show();
            },
            abrirIncluirTipo() {
                this.tipoModal = {
                    codigo: "",
                    descricao: "",
                    imposto: 0
                };

                this.myModal.show();
            },
            async alterarTipo() {
                let erro = this.validaCamposTipo(
                    this.tipoModal.descricao,
                    this.tipoModal.imposto
                );

                if (erro != "") {
                    alert(erro);
                    return;
                }

                await axios.put('http://localhost:8080/tipo/produto', this.tipoModal)
                .then((response) => {
                    this.myModal.hide();
                    alert("Tipo alterado com sucesso!");
                    this.carregaTipos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            validaCamposTipo(descricao, imposto) {
                let erro = "";

                if (descricao.trim() == "") {
                    erro = "Informe um descrição.";
                } else if (
                    !(imposto >= 0) || 
                    imposto == null || 
                    imposto == ""
                ) {
                    erro = "Informe um imposto.";
                }

                return erro;
            },
            async incluirTipo() {
                let erro = this.validaCamposTipo(
                    this.tipoModal.descricao,
                    this.tipoModal.imposto
                );

                if (erro != "") {
                    alert(erro);
                    return;
                }

                await axios.post('http://localhost:8080/tipo/produto', this.tipoModal)
                .then((response) => {
                    this.myModal.hide();
                    alert("Tipo incluido com sucesso!");
                    this.carregaTipos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            async excluirTipo() {
                if (Object.keys(this.tipoSelecionado).length === 0) {
                    alert("Selecione um tipo!");
                    return;
                }

                await axios.delete('http://localhost:8080/tipo/produto', { params: { codigo: this.tipoSelecionado.codigo } })
                .then((response) => {
                    this.myModal.hide();
                    alert("Tipo excluído com sucesso!");
                    this.carregaTipos();
                })
                .catch((error) => {
                    console.log(error);
                });
            },
            formatarValor(valor) {
                let valorFormatado = Number(valor).toLocaleString('pt-br', { maximumFractionDigits: 2, minimumFractionDigits: 2 });
                return valorFormatado;
            }
        }
    }
</script>
  
<style scoped>

    #gridTipos {
        display: table;
        height: 40vh;
        max-height: 40vh;
    }

</style>