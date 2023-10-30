@extends('layouts.master')

@section('content')

    <div class="box" id="app">
        <div class="box-header with-border">
            <h3 class="box-title"> <i class="fas fa-th-large" style="color: black;"></i> Cadastro de Categoria de Produtos</h3>
        </div>
        <div class="box-body">
            <div class="form-group col-md-12">
                <label for="CategoryName">Nome da Categoria</label>
                <input type="text" class="form-control" id="CategoryName" name="CategoryName" required>
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
                gravar: function (){

                    let CategoryName = $('#CategoryName').val();

                    let data = {
                        CategoryName: CategoryName,
                    };

                    let url = '{{ route("insert-category") }}';
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
            }
        });

    </script>
@stop
