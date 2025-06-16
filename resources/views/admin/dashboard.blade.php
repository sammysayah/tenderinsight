@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <!-- Total Sales Section -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalSales }}</h3>
                    <p>Total Sales</p>
                </div>
                <div class="icon">
                    <i class="fa fa-dollar-sign"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $tendersWon }}</h3>
                    <p>Tenders Won</p>
                </div>
                <div class="icon">
                    <i class="fa fa-gavel"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $quotationsWon }}</h3>
                    <p>Quotations Won</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $prequalificationsWon }}</h3>
                    <p>Prequalification Won</p>
                </div>
                <div class="icon">
                    <i class="fa fa-check-square"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphs Section -->
    <div class="row">
        <!-- Bar Chart: Sales Performance -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales Performance (Last 12 Months)</h3>
                </div>
                <div class="card-body">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Pie Chart: Sales Categories -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sales by Category</h3>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable Section -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Sales</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Wrap table inside a responsive div -->
                <table id="salesTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Client Name</th>
                            <th>Business Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentSales as $sale)
                            <tr>
                                <td>{{ $sale->client_name }}</td>
                                <td>{{ $sale->business_type }}</td>
                                <td>${{ $sale->amount }}</td>
                                <td>{{ $sale->bid_status }}</td>
                                <td>{{ $sale->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        Developed By: <a href="{{ config('app.company_url', 'mailto:sayahsamson@gmail.com') }}">
            {{ config('app.company_name', 'Samson Saya') }}
        </a>
    </strong>
@stop

@section('css')
    <!-- Include Chart.js CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
@stop

@section('js')
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <script>
        $(function () {
            // Initialize DataTable
            $('#salesTable').DataTable();

            // Sales Chart - Bar Chart
            var ctx1 = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Sales Amount ($)',
                        data: @json($salesData),
                        backgroundColor: 'rgba(0, 123, 255, 0.6)',
                        borderColor: 'rgba(0, 123, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Sales by Category - Pie Chart
            var ctx2 = document.getElementById('categoryChart').getContext('2d');
            var categoryChart = new Chart(ctx2, {
                type: 'pie',
                data: {
                    labels: @json($categoryLabels),
                    datasets: [{
                        label: 'Sales by Category',
                        data: @json($categoryData),
                        backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
                        borderColor: ['#ffffff', '#ffffff', '#ffffff', '#ffffff'],
                        borderWidth: 2
                    }]
                }
            });
        });
    </script>
@stop
