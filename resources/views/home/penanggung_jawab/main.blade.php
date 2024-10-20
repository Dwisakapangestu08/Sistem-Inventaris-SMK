@extends('layouts.main')

@section('title', 'Penanggung Jawab | Inventaris SMK Baitul Hikmah')

@section('link-api')
    <meta name="link-api" link="{{ url('/api/v1/admin/daftar-penanggung-jawab') }}">
    <meta name="link-api-tambah" link="{{ url('/api/v1/admin/tambah-penanggung-jawab') }}">
    <meta name="link-api-hapus" link="{{ url('/api/v1/admin/hapus-penanggung-jawab') }}">
@endsection

@section('content')
    @php
        $gurus = \App\Models\User::where('role', 0)->get();
        $barangs = \App\Models\Barang::all();
    @endphp
    <main id="main" class="main">
        <div class="pagetitle">
            <h1>Penanggung Jawab Inventaris</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item active">Penanggung Jawab</li>
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
                            Tambah Guru
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
                            <h5 class="card-title">Nama Guru</h5>

                            <table class="table table-borderless" id="table_penanggung_jawab">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nama Guru</th>
                                        <th scope="col">Nama Barang</th>
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
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Guru</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="tambah_penanggung_jawab">
                        @csrf
                        <div class="mb-3">
                            <label for="guru" class="form-label">Guru</label>
                            <select name="guru" id="guru" class="form-select">
                                <option value="">Pilih Guru</option>
                                @foreach ($gurus as $guru)
                                    <option value="{{ $guru->id }}">{{ $guru->name }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger guru_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="barang" class="form-label">Barang</label>
                            <select name="barang" id="barang" class="form-select">
                                <option value="">Pilih Barang</option>
                                @foreach ($barangs as $barang)
                                    <option value="{{ $barang->id }}">{{ $barang->name_barang }}</option>
                                @endforeach
                            </select>
                            <div class="text-danger barang_err"></div>
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
@endsection

@section('js')
    <script src="{{ url('/assets/js/admin/penanggung_jawab.js') }}"></script>
@endsection
