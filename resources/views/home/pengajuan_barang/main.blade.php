@extends('layouts.main')

@section('title', 'Pengajuan Barang | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/daftar-pengajuan') }}">
    <meta name="link-api-tambah" link="{{ url('/api/v1/admin/tambah-penanggung-jawab') }}">
    <meta name="link-api-edit" link="{{ url('/api/v1/admin/edit-penanggung-jawab') }}">
    <meta name="link-api-update" link="{{ url('/api/v1/admin/update-penanggung-jawab') }}">
    <meta name="link-api-hapus" link="{{ url('/api/v1/admin/hapus-penanggung-jawab') }}">
@endsection

@section('content')
    @php
        // $gurus = \App\Models\User::where('role', '2')->where('status', '1')->get();
        // // dd($gurus);
        // $barangs = \App\Models\Barang::all();
    @endphp
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Pengajuan Barang</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Pengajuan Barang</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- Recent Sales -->
                <div class="col-12">

                    <div class="card overflow-auto recent-sales">
                        <div class="card-body">
                            <h5 class="card-title">Nama Guru</h5>

                            <table class="table table-borderless" id="table_pengajuan">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Guru</th>
                                        <th scope="col">Nama Barang</th>
                                        <th scope="col">Jumlah(Pack/Pcs)</th>
                                        <th scope="col">Harga Perkiraan</th>
                                        <th scope="col">Kondisi</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col">Tujuan Pengajuan</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Aksi</th>
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
    <script src="{{ url('/assets/js/admin/pengajuan_barang.js') }}"></script>
@endsection
