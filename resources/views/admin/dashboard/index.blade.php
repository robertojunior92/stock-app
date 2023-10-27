@extends(request()->header('layout') ??  request()->header('layout') ?? 'adminetic::admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Dashboard</h3>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-6">
                    <h3>Dashboard</h3>
                </div>
            </div>
        </div>
        <canvas id="myChart"></canvas>
    </div>

    <!-- Container-fluid Ends-->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

@endsection

@section('custom_js')
    @include('admin.layouts.modules.dashboard.scripts')
@endsection
