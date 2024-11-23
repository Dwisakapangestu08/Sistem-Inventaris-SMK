@extends('layouts.main')

@section('title', 'Pengajuan Barang | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/user/daftar-pengajuan') }}">
    <meta name="link-api-pengajuan" link="{{ url('/api/v1/user/tambah-pengajuan') }}">
    <meta name="link-api-penolakan" link="{{ url('/api/v1/user/alasan-penolakan') }}">
    <meta name="link-api-edit" link={{ url('/api/v1/user/edit-pengajuan') }}>
    <meta name="link-api-update" link={{ url('/api/v1/user/update-pengajuan') }}>
    <meta name="loading" link="{{ url('images/load_login.gif') }}">
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Pengajuan Barang</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/user">Home</a></li>
                    <li class="breadcrumb-item active">Pengajuan Barang</li>
                </ol>
            </nav>
        </div>
        <!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <!-- Recent Sales -->
                <div class="col-12">
                    <div class="mb-3 d-flex justify-content-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            Ajukan Barang
                        </button>
                    </div>
                    <div class="card overflow-auto recent-sales">
                        <div class="card-body">
                            <h5 class="card-title">Daftar Pengajuan Barang</h5>

                            <table class="table table-borderless" id="table_pengajuan">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Barang Pengajuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="pengajuan_barang">
                        @csrf
                        <div class="mb-3">
                            <label for="name_barang_pengajuan" class="form-label">Nama Barang</label>
                            <input type="text" name="name_barang_pengajuan" class="form-control"
                                id="name_barang_pengajuan" placeholder="Contoh: Keyboard" required>
                            <div class="text-danger name_barang_pengajuan_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Barang (Pack/Pcs)</label>
                            <input type="number" name="jumlah_barang_pengajuan" class="form-control"
                                id="jumlah_barang_pengajuan">
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="harga_perkiraan" class="form-label">Harga Perkiraan</label>
                            <input type="number" name="harga_perkiraan" class="form-control" id="harga_perkiraan"
                                placeholder="Contoh: 10000">
                            <div class="text-danger harga_perkiraan_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" id="kondisi"
                                placeholder="Contoh: Baru/Bekas">
                            <div class="text-danger kondisi_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="tujuan_pengajuan" class="form-label">Tujuan Pengajuan</label>
                            <input type="text" name="tujuan_pengajuan" class="form-control" id="tujuan_pengajuan"
                                placeholder="Contoh: Tulis Tujuan Pengajuan">
                            <div class="text-danger tujuan_pengajuan_err"></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary btn-simpan mx-2">Kirim</button>
                        </div>
                        {{-- <div class="loading-section" style="display: block; width: 100%; background-color: #0e6eff"></div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="penolakanModal" tabindex="-1" aria-labelledby="penolakanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="penolakanModalLabel">Alasan Penolakan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="alasan_penolakan">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="alasan_penolakan" id="alasan_penolakan" cols="30" rows="10"
                                disabled></textarea>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Update Pengajuan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="edit_pengajuan">
                        @csrf
                        <div class="mb-3">
                            <input type="hidden" name="id" id="id">
                            <label for="name_barang_pengajuan" class="form-label">Nama Barang</label>
                            <input type="text" name="name_barang_pengajuan" class="form-control"
                                id="name_barang_pengajuan" placeholder="Contoh: Keyboard" required>
                            <div class="text-danger name_barang_pengajuan_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Barang (Pack/Pcs)</label>
                            <input type="number" name="jumlah_barang_pengajuan" class="form-control"
                                id="jumlah_barang_pengajuan">
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="harga_perkiraan" class="form-label">Harga Perkiraan</label>
                            <input type="number" name="harga_perkiraan" class="form-control" id="harga_perkiraan"
                                placeholder="Contoh: 10000">
                            <div class="text-danger harga_perkiraan_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" id="kondisi"
                                placeholder="Contoh: Baru/Bekas">
                            <div class="text-danger kondisi_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="tujuan_pengajuan" class="form-label">Tujuan Pengajuan</label>
                            <input type="text" name="tujuan_pengajuan" class="form-control" id="tujuan_pengajuan"
                                placeholder="Contoh: Tulis Tujuan Pengajuan">
                            <div class="text-danger tujuan_pengajuan_err"></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary btn-update mx-2">Kirim</button>
                        </div>
                        {{-- <div class="loading-section" style="display: block; width: 100%; background-color: #0e6eff"></div> --}}
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ url('/assets/js/user/pengajuan_barang.js') }}"></script>
@endsection
