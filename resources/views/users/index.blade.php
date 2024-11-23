@extends('layouts.main')

@section('title', 'Dashboard | Inventaris SMK Baitul Hikmah')

@section('link-api')

@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard {{ Auth::user()->role == 1 ? 'Admin' : 'Guru' }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row">
                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pengajuan <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clipboard2-data text-primary"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\Pengajuan::all()->count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Items</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Sales Card -->
                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pengajuan Diterima <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clipboard2-check text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\Pengajuan::where('status', '1')->count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Items</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Sales Card -->
                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Pengajuan Ditolak <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-clipboard2-x text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\Pengajuan::where('status', '3')->count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Items</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Sales Card -->
                    </div>
                </div><!-- End Reports -->
            </div><!-- End Left side columns -->
        </section>

    </main>
@endsection

@section('js')

@endsection
