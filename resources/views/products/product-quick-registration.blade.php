@extends('layouts.master')

@section('content')

    <div class="box" id="app">
        <div class="box-header with-border">
            <h3 class="box-title"> <i class="fas fa-rocket" style="color: black;"></i> Cadastro Rápido de Produtos</h3>
        </div>
        <div class="box-body">
            <div class="form-group col-md-12">
                <label for="ProductName">Nome do Produto</label>
                <input type="text" class="form-control" id="ProductName" name="ProductName" required>
            </div>
            <div class="form-group col-md-12">
                <label for="CategoryID">Categoria</label>
                <select class="form-control" id="CategoryID" required>
                    <option value="">Selecione</option>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="UnitPrice">Preço por Unidade</label>
                <input type="text" class="form-control" id="UnitPrice" name="UnitPrice" required>
            </div>
            <div class="form-group col-md-12">
                <label for="qtd">Quantidade</label>
                <input type="text" class="form-control" id="qtd" name="qtd" required>
            </div>
            <div class="form-group col-md-12">
                <button v-on:click="gravar()" type="button" class="btn btn-primary">Salvar</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <script>
        Vue.component('vue-multiselect', window.VueMultiselect.default);

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
                modoEdicao: false
            },
            methods:{
                popularCategory() {
                    $.get("{{route("get-category")}}", {

                    }, function (data) {
                        $.each(data, function (index, value) {
                            $('#CategoryID').append('<option value="' + value.id + '">' + value.category_name + '</option>');
                        });
                    });
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

                    let url = '{{ route("insert-products") }}';
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
            },
            mounted: function () {
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
                allowMinus: false,
                autoGroup: true,
                rightAlign: false,
                numericInput: true
            });
        });
    </script>

@stop
