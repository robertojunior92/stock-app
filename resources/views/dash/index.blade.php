@extends('layouts.master')

<style>
    h1 {
        font-size: 36px;
    }

    p {
        font-size: 18px;
    }
    .border-all-sides-card {
        border: 1px solid grey; /* Cor da borda e largura desejadas */
        border-radius: 10px; /* Para deixar o card com bordas arredondadas */
    }

</style>

@section('content')

    <div class="box" id="app">
        <div class="box-header with-border">
            <h3 class="box-title"> <i class="fas fa-home" style="color: black;"></i> Home</h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-lg-3 col-md-4">
                    <div class="card border-all-sides-card rounded">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center icon-container">
                                <i class="fas fa-archive fa-2x" style="color: blue; margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total em estoque</h5>
                                    <h3 class="card-text mb-0" id="totalStock"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="card border-all-sides-card rounded">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center icon-container">
                                <i class="fas fa-arrow-left fa-2x" style="color: orange; margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Entradas</h5>
                                    <h3 class="card-text mb-0" id="totalEntryStock"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="card border-all-sides-card rounded">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center icon-container">
                                <i class="fas fa-arrow-right fa-2x" style="color: green; margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Saídas</h5>
                                    <h3 class="card-text mb-0" id="totalOutStock"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4">
                    <div class="card border-all-sides-card rounded">
                        <div class="row no-gutters align-items-center">
                            <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center icon-container">
                                <i class="fas fa-money-bill fa-2x" style="color: green; margin-top: 10px;"></i>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Valor em Saídas</h5>
                                    <h3 class="card-text mb-0" id="totalValueOutStock"></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6" style="max-width: 900px; max-height: 400px;">
                    <canvas id="myPieChart" width="900" height="400"></canvas>
                </div>
                <div class="col-lg-3">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Top 10 - Entrada de Produtos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Produto 1</td>
                        </tr>
                        <tr>
                            <td>Produto 2</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-3">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Top 10 - Saída de Produtos</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Produto 1</td>
                        </tr>
                        <tr>
                            <td>Produto 2</td>
                        </tr>
                        <!-- Adicione as entradas para os produtos restantes -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            resultProdutos: [],
            modoEdicao: false
        },
            methods:{
                getTotalStock() {
                    $.get("{{route('get-total-stock')}}", {

                    }, function (data) {
                        if (data) {
                            $('#totalStock').text(data.totalStock.totalStock);
                        }
                    })
                },
                getTotalEntryStock() {
                    $.get("{{route('get-total-entry-stock')}}", {

                    }, function (data) {
                        if (data) {
                            $('#totalEntryStock').text(data.totalEntryStock.totalEntryStock);
                        }
                    })
                },
                getTotalOutStock() {
                    $.get("{{route('get-total-out-stock')}}", {

                    }, function (data) {
                        if (data) {
                            $('#totalOutStock').text(data.totalOutStock.totalOutStock);
                        }
                    })
                },
                getTotalValueOutStock() {
                    $.get("{{route('get-total-value-out-stock')}}", {}, function (data) {
                        if (data && data.totalValueOutStock) {
                            const totalValue = data.totalValueOutStock.totalValueOutStock;
                            const formattedValue = 'R$ ' + totalValue; // Adicione 'R$ ' ao valor
                            $('#totalValueOutStock').text(formattedValue);
                        }
                    })
                },
            },
            mounted: function () {
                this.getProducts();
                this.getTotalStock();
                this.popularCategory();

                $.get('/get-total-category-stock', function (data) {
                    const ctxPie = document.getElementById('myPieChart').getContext('2d');

                    const myPieChart = new Chart(ctxPie, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: data.cores,
                            }]
                        },
                    });
                });
            },
        });

    </script>

    <script>
        // Dados do gráfico (exemplo)
        const data = {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [
                {
                    label: 'Sample Data',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    data: [65, 59, 80, 81, 56, 55],
                },
            ],
        };

        // Opções do gráfico (pode personalizar)
        const options = {
            scales: {
                y: {
                    beginAtZero: true,
                },
            },
        };

        // Seletor do elemento canvas
        const ctx = document.getElementById('myChart').getContext('2d');

        // Crie o gráfico
        const myChart = new Chart(ctx, {
            type: 'bar', // Tipo de gráfico (bar, line, pie, etc.)
            data: data,
            options: options,
        });
    </script>




@stop
