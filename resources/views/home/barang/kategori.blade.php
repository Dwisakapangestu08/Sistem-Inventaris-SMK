@extends('layouts.main')

@section('title', 'Barang | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/list-kategori') }}">
    <meta name="link-api-edit" link="{{ url('/api/v1/admin/edit-kategori') }}">
    <meta name="link-api-status" link="{{ url('/api/v1/admin/status-user') }}">
    <meta name="link-api-tambah" link="{{ url('/api/v1/admin/tambah-kategori') }}">
    <meta name="link-api-update" link="{{ url('/api/v1/admin/update-kategori') }}">
    <meta name="link-api-hapus" link="{{ url('/api/v1/admin/hapus-kategori') }}">
@endsection

@section('content')
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Kategori Barang</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Kategori</li>
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
                            Tambah Kategori
                        </button>
                    </div>
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
                            <h5 class="card-title">Daftar Nama Barang</h5>

                            <table class="table table-borderless" id="table_kategori">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Kategori</th>
                                        <th scope="col">Deskripsi</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="tambah_kategori">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" name="kategori" class="form-control" id="kategori"
                                placeholder="Kategori">
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3"></textarea>
                            <div class="text-danger deskripsi_err"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-simpan">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModal">Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit_kategori">
                    <div class="modal-body">
                        @csrf
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ url('/assets/js/admin/kategori_barang.js') }}"></script>
@endsection
