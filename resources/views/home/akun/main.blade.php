@extends('layouts.main')

@section('title', 'Akun | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/daftar-user') }}">
    {{-- <meta name="link-api-edit" link="{{ url('/api/v1/admin/edit-user') }}"> --}}
    <meta name="link-api-status" link="{{ url('/api/v1/admin/status-user') }}">
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Akun</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">List Akun</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- Recent Sales -->
                <div class="col-12">
                    {{-- <div class="card recent-sales overflow-auto">

                        <h5 class="card-title">Recent Sales <span>| Today</span></h5>

                        <table class="table table-borderless datatable" id="table_user">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Nama</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Jabatan</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                    </div> --}}

                    <div class="card overflow-auto recent-sales">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Akun</h5>

                            <table class="table table-borderless" id="table_user">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Jabatan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Activate</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- End Recent Sales -->
            </div>
        </section>

    </main>
@endsection

@section('js')
    <script src="{{ url('/assets/js/admin/daftar_user.js') }}"></script>
@endsection
