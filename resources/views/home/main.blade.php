@extends('layouts.main')

@section('title', 'Dashboard | Inventaris SMK Baitul Hikmah')

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Dashboard</h1>
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
                                    <h5 class="card-title">Inventaris <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\Barang::count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Barang</span>

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
                                    <h5 class="card-title">Pengajuan <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-cart text-warning"></i>
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
                        </div><!-- End Sales Card -->
                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Akun <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people text-success"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\User::where([
                                                        'role' => '2',
                                                        'status' => '1',
                                                    ])->count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Akun</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->
                        <!-- Sales Card -->
                        <div class="col-xxl-3 col-md-6">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Akun Non Aktif <span>| SMK Bathik</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people text-danger"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>
                                                @php
                                                    $count = \App\Models\User::where([
                                                        'role' => '2',
                                                        'status' => '0',
                                                    ])->count();
                                                @endphp
                                                {{ $count }}
                                            </h6>
                                            <span class="text-muted small pt-2 ps-1">Akun</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End Sales Card -->
                    </div>
                </div><!-- End Reports -->
            </div><!-- End Left side columns -->
        </section>

    </main>
@endsection
