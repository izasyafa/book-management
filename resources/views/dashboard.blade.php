@extends('layouts.app')

{{-- Customize layout sections --}}
@section('subtitle', 'Dashboard')
@section('content_header_title', 'Dashboard')

{{-- Content body: main page content --}}
@section('content_body')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <canvas id="bookByCategory"></canvas>
            </div>
            <div class="col-md-6 text-center">
                <canvas id="bookByPublisher"></canvas>
            </div>
        </div>
        <div class="col-md-12 text-center mt-3">
            <h4>Tabel Rekap Buku Berdasarkan Penulis</h4>
            <table class="table table-striped table-hover p-2 border">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Nama Penulis</th>
                        <th scope="col">Jumlah Buku</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($writers as $writer)
                    <tr>
                        <th scope="row">1</th>
                        <td>{{ $writer->user->name }}</td>
                        <td>{{ $writer->count }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

{{-- Push extra CSS --}}
@push('css')
    <style>
        #bookByCategory {
            width: 100% !important;
            height: auto !important;
            max-width: 280px;
            margin: 0 auto;
        }

        #bookByPublisher {
            width: 280px !important;
            height: 280px !important;
            margin: 0 auto;
        }
    </style>
@endpush

{{-- Push extra scripts --}}
@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/v1/getByCategory')
                .then(response => response.json())
                .then(data => {
                    const nameCategory = data.map(item => item.category_name);
                    const bookCounts = data.map(item => item.count);

                    const chartData = {
                        labels: nameCategory,
                        datasets: [{
                            label: 'Jumlah Buku per Kategori',
                            data: bookCounts,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(153, 102, 255)'
                            ],
                            hoverOffset: 4
                        }]
                    };

                    const config = {
                        type: 'doughnut',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Buku Berdasarkan Category',
                                    position: 'bottom'
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('bookByCategory').getContext('2d');
                    new Chart(ctx, config);
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/v1/getByPublisher')
                .then(response => response.json())
                .then(data => {
                    const namePublisher = data.map(item => item.publisher_name);
                    const bookCounts = data.map(item => item.count);

                    const chartData = {
                        labels: namePublisher,
                        datasets: [{
                            label: 'Jumlah Buku per Kategori',
                            data: bookCounts,
                            backgroundColor: [
                                'rgb(255, 99, 132)',
                                'rgb(54, 162, 235)',
                                'rgb(255, 205, 86)',
                                'rgb(75, 192, 192)',
                                'rgb(153, 102, 255)'
                            ],
                            hoverOffset: 4
                        }]
                    };

                    const config = {
                        type: 'pie',
                        data: chartData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                title: {
                                    display: true,
                                    text: 'Grafik Buku Berdasarkan Publisher',
                                    position: 'bottom'
                                }
                            }
                        }
                    };

                    const ctx = document.getElementById('bookByPublisher').getContext('2d');
                    new Chart(ctx, config);
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
@endpush
