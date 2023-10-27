@extends('layouts.master')



@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>

    <div class="box" id="app">
        <div class="box-header with-border">
            <h3 class="box-title">Cadastro de Produtos</h3>
            <div style="float: right">
                <button v-on:click="abrirInclusao()" type="button" title="Inserir"
                        class="btn btn-success"><i class="fas fa-rocket" style="color: white;"></i>&nbsp;  Cadastro Rápido</button>
            </div>
        </div>
        <div class="box-body">
            <div style="margin-top: 20px" class="row">
                <div style="margin-top: 10px;" class="row">
                    <div class="col-md-offset-3 col-md-6">
                        <div class="input-group">
                            <div id="loadersearch" class="input-group-addon">
                                <i class="fal fa-search"></i>
                            </div>
                            <input v-on:keyup="searchProdutos" v-model="inputProdutos" style="width: 100%; padding: 10px"
                                   name="searchNew" class="searchlicense"
                                   placeholder="Insira um produto para a busca">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Categoria</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(products, index) in resultProdutos">
                                <td>@{{ (products.product_name) }}</td>
                                <td>@{{ (products.category_name) }}</td>
                                <td>@{{ (products.status_product_name) }}</td>
                                <td>
                                    <button v-on:click="abrirEdicao(products)" type="button" title="Editar"
                                            class="btn btn-xs btn-warning"><i
                                            class="fal fa-edit"></i></button>
                                    <button v-on:click="deleteProduto(products, index)" type="button" title="Excluir"
                                            class="btn btn-xs btn-danger"><i class="fal fa-trash-alt"></i></button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="modal fade bd-example-modal-lg" id="modalCadastro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-rocket" style="color: black;"></i> <span id="spnOperacao"> </span> Rápida de Produtos </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="productsForm">
                                @csrf
                                <div class="form-group col-md-12">
                                    <input type="hidden" class="form-control" id="id" name="id">
                                    <label for="ProductName">Nome do Produto</label>
                                    <input type="text" class="form-control" id="ProductName" name="ProductName">
                                </div>
                                <div class="form-group col-md-12">
                                    <div class="form-group">
                                        <label for="CategoryID">Categoria</label>
                                        <select class="form-control" id="CategoryID">
                                            <option value="">Selecione</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="UnitPrice">Preço por unidade</label>
                                    <input type="text" class="form-control" id="UnitPrice" name="UnitPrice">
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="qtd">Quantidade</label>
                                    <input type="text" class="form-control" id="qtd" name="qtd">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" v-on:click="gravar()">@{{ modoEdicao ? 'Atualizar' : 'Enviar' }}</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>


    <script>
        Vue.component('vue-multiselect', window.VueMultiselect.default);

        function getProducts(limit, offset, cb) {
            $.get("{{route('get-products-admin')}}", {
                limit: limit,
                offset: offset
            }, function (data) {
                if (data) {
                    cb(data);
                }
            })
        }
        var app = new Vue({
            el: "#app",
            data: {
                priority: "2",
                association: [],
                tipo: "1",
                title: "",
                activateDate: false,
                interest: "N",
                active: "1",
                revision: "N",
                inputProdutos: "",
                resultProdutos: [],
                modoEdicao: false
            },
            methods:{
                async searchProdutos() {
                    if(this.inputProdutos.length > 4){

                    }
                    const response = await fetch("{{route("search-products-admin")}}"+"?input="+this.inputProdutos);
                    let data = await response.json();

                    this.resultProdutos = data;
                },
                getProducts: function () {
                    var that = this;
                    getProducts(30, 0, function (data) {
                        that.resultProdutos = data;
                    })
                },
                abrirInclusao() {
                    $('#productsForm').each(function() {
                        this.reset();
                    });
                    this.modoEdicao = false; // Define o modo de edição como falso
                    $('#operacao').val("I");
                    $('#spnOperacao').html("Inclusão de");
                    $('#modalCadastro').modal('toggle');
                },
                popularCategory() {
                    $.get("{{route("get-category")}}", {

                    }, function (data) {
                        $.each(data, function (index, value) {
                            $('#CategoryID').append('<option value="' + value.id + '">' + value.category_name + '</option>');
                        });
                    });
                },
                abrirEdicao: function (products){
                    let that = this;
                    $.get("{{route("data-product")}}", {
                        id: products.id
                    }, function (data) {
                        that.modoEdicao = true;
                        $('#operacao').val("A");
                        $('#spnOperacao').html("Alteração de");
                        $('#id').val(data.id);
                        $('#ProductName').val(data.ProductName);
                        $('#modalCadastro').modal('toggle');
                    })
                },
                gravar: function (){

                    let id          = $('#id').val();
                    let ProductName = $('#ProductName').val();
                    let CategoryID  = $('#CategoryID').val();
                    let qtd         = $('#qtd').val();
                    let UnitPrice   = $('#UnitPrice').val().replace(/[^\d.,]/g, '').replace(',', '.');
                    let operacao    = this.modoEdicao ? 'editar' : 'inserir'; // Verifica se é modo de edição ou inserção

                    let data = {
                        id: id,
                        ProductName: ProductName,
                        CategoryID: CategoryID,
                        qtd: qtd,
                        UnitPrice: UnitPrice,
                    };

                    if (!this.modoEdicao) {
                        delete data.id;
                    }

                    let url = this.modoEdicao ? '{{ route("update-products") }}' : '{{ route("insert-products") }}'; // Substitua pelas rotas corretas

                    // Configuração dos cabeçalhos (adicionar o token CSRF)
                    let headers = {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };

                    axios.post(url, data, { headers: headers })
                    .then(response => {
                        if (response.data.success == '1') {
                            swal({
                                title:'Ok!',
                                icon: 'success'
                            }).then((result)=>{
                                if (result == true) {
                                    swal(
                                        'Refreshing...',
                                        'Aguarde sua página está sendo carregada.',
                                        'info'
                                    )
                                    // Atualize a página após um curto intervalo de tempo
                                    setTimeout(function() {
                                        $('#modalCadastro').modal('hide');
                                        location.reload();
                                    }, 1500); // Tempo em milissegundos (aqui é 1,5 segundos)
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
                },
                deleteProduto: function (products, index) {
                    var that = this;
                    if (window.confirm("Gostaria realmente de deletar?")) {
                        let headers = {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        };

                        let url = "{{route('delete-product')}}";
                        let data= {
                            productID: products.id
                        }

                        axios.post(url, data, { headers: headers })
                            .then(response => {
                                if (response.data.success == '1') {
                                    swal({
                                        title:'Ok!',
                                        text: 'Produto Deletado!',
                                        icon: 'info'
                                    }).then((result)=>{
                                        if (result == true) {
                                            swal(
                                                'Refreshing...',
                                                'Aguarde sua página está sendo carregada.',
                                                'info'
                                            )
                                            // Atualize a página após um curto intervalo de tempo
                                            setTimeout(function() {
                                                $('#modalCadastro').modal('hide');
                                                location.reload();
                                            }, 1500); // Tempo em milissegundos (aqui é 1,5 segundos)
                                        }
                                    });
                                }

                            })
                    }
                },
            },
            mounted: function () {
                this.getProducts();
                this.popularCategory();
            }
        });

    </script>
    <script>
        $(document).ready(function () {
            $('#UnitPrice').inputmask("currency", {
                prefix: 'R$ ',
                radixPoint: ",",
                groupSeparator: ".",
                allowMinus: false, // Remova esta linha se quiser permitir valores negativos
                autoGroup: true,
                rightAlign: false,
                numericInput: true
            });
        });
    </script>


@stop
