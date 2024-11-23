@extends('layouts.main')

@section('title', 'Pengajuan Barang | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/daftar-pengajuan') }}">
    <meta name="link-api-status" link="{{ url('/api/v1/admin/status-pengajuan') }}">
    <meta name="link-api-reject" link="{{ url('/api/v1/admin/reject') }}">
    <meta name="link-api-penolakan" link="{{ url('/api/v1/admin/penolakan-pengajuan') }}">
    {{-- <meta name="link-api-selesai" link="{{ url('/api/v1/admin/selesai-pengajuan') }}"> --}}
@endsection

@section('content')
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

    <div class="modal fade" id="alasanModal" tabindex="-1" aria-labelledby="alasanModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="alasanModal">Tuliskan Alasan Penolakan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="form_penolakan">
                    <div class="modal-body">
                        @csrf
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ url('/assets/js/admin/pengajuan_barang.js') }}"></script>
@endsection
