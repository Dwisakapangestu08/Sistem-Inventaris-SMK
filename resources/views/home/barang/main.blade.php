@extends('layouts.main')

@section('title', 'Barang | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/list-barang') }}">
    <meta name="link-api-tambah" link="{{ url('/api/v1/admin/tambah-barang') }}">
    <meta name="link-api-edit" link="{{ url('/api/v1/admin/edit-barang') }}">
    <meta name="link-api-update" link="{{ url('/api/v1/admin/update-barang') }}">
    <meta name="link-api-hapus" link="{{ url('/api/v1/admin/hapus-barang') }}">
@endsection

@section('content')
    @php
        $kategori = \App\Models\Kategori::all();
    @endphp
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>List Barang</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Barang</li>
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
                            Tambah Barang
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

                            <table class="table table-borderless" id="table_barang">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Barang</th>
                                        {{-- <th scope="col">Kategori</th> --}}
                                        <th scope="col">Merk Barang</th>
                                        <th scope="col">Ukuran Barang</th>
                                        <th scope="col">Bahan Barang</th>
                                        <th scope="col">Tahun Perolehan</th>
                                        <th scope="col">Jumlah(Pack/Pcs)</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Kondisi</th>
                                        <th scope="col">Keadaan Barang</th>
                                        <th scope="col">Total Harga</th>
                                        <th scope="col">Lokasi</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambah_barang">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name Barang</label>
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Contoh: Keyboard" required>
                            <div class="text-danger name_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategori as $item)
                                    <option value="{{ $item->id }}">{{ $item->name_kategori }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="merk_barang" class="form-label">Merk Barang</label>
                            <input type="text" name="merk_barang" class="form-control" id="merk_barang"
                                placeholder="Contoh: Logitech">
                            <div class="text-danger merk_barang_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="ukuran_barang" class="form-label">Ukuran Barang</label>
                            <input type="text" name="ukuran_barang" class="form-control" id="ukuran_barang"
                                placeholder="Contoh: 10cm">
                            <div class="text-danger ukuran_barang_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="bahan_barang" class="form-label">Bahan Barang</label>
                            <input type="text" name="bahan_barang" class="form-control" id="bahan_barang"
                                placeholder="Contoh: Kayu">
                            <div class="text-danger bahan_barang_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="tahun_perolehan" class="form-label">Tahun Perolehan</label>
                            <input type="number" name="tahun_perolehan" class="form-control" id="tahun_perolehan"
                                placeholder="Contoh: 2020">
                            <div class="text-danger tahun_perolehan_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Barang</label>
                            <input type="text" name="jumlah" class="form-control" id="jumlah"
                                placeholder="Contoh: 10">
                            <div class="text-danger jumlah_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <select name="kondisi" class="form-select" id="kondisi">
                                <option value="" disabled selected>===Pilih Kondisi Barang===</option>
                                <option value="Baru">Baru</option>
                                <option value="Bekas">Bekas</option>
                            </select>
                            <div class="text-danger kondisi_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="keadaan_barang" class="form-label">Keadaan Barang</label>
                            <select name="keadaan_barang" class="form-select" id="keadaan_barang">
                                <option value="" disabled selected>===Pilih Keadaan Barang===</option>
                                <option value="Baik">Baik</option>
                                <option value="Kurang Baik">Kurang Baik</option>
                                <option value="Rusak Berat">Rusak Berat</option>
                            </select>
                            <div class="text-danger keadaan_barang_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" name="harga" class="form-control" id="harga"
                                placeholder="Contoh: 10000">
                            <div class="text-danger harga_err"></div>
                        </div>
                        <div class="mb-4">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" id="lokasi"
                                placeholder="Contoh: Lab 3">
                            <div class="text-danger lokasi_err"></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-simpan mx-2">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModal">Edit Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="update_barang">
                    <div class="modal-body">
                        @csrf
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection

@section('js')
    <script src="{{ url('/assets/js/admin/barang.js') }}"></script>
@endsection
