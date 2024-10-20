$(document).ready(function () {
    const validasi = (message) => {
        $.each(message, function (key, value) {
            $("." + key + "_err").text(value);
        });
    };

    const formatCurrency = (number) => {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0, // Tidak menampilkan angka desimal
            maximumFractionDigits: 0, // Tidak menampilkan angka desimal
        }).format(number);
    };

    const load_table = () => {
        let url = $("meta[name='link-api']").attr("link");
        $("table#table_barang").DataTable().destroy();
        $("table#table_barang").DataTable({
            ajax: {
                url: url,
                headers: {
                    Authorization: "Bearer " + get_cookie("token"),
                },
                type: "POST",
            },
            serverSide: true,
            processing: true,
            aaSorting: [[0, "desc"]],
            columns: [
                {
                    data: null,
                    render: function (data, type, row, meta) {
                        return (
                            meta.row + meta.settings._iDisplayStart + 1 + "."
                        );
                    },
                },
                { data: "name_barang", name: "name_barang" },
                { data: "name_kategori", name: "name_kategori" },
                { data: "jumlah", name: "jumlah" },
                { data: "kondisi", name: "kondisi" },
                {
                    data: "harga",
                    render: function (data) {
                        return formatCurrency(data);
                    },
                },
                {
                    data: null,
                    render: function (data) {
                        let jumlah = data.jumlah.split(" ");
                        return formatCurrency(data.harga * jumlah[0]);
                    },
                },
                { data: "lokasi", name: "lokasi" },
                {
                    data: null,
                    render: (res) => {
                        // let link = $("meta[name='link-api-edit']").attr("link");
                        // let link_edit = link + "/" + res.id;
                        return `
                        <a href="#" class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                            data-bs-target="#editModal" data-id="${res.id}" style="color: #FFF;" title="edit"><i class="bi bi-pen"></i></a> |
                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="${res.id}" data-nama="${res.name_barang}" style="color: #FFF;" title="Hapus"><i class="bi bi-trash"></i></a>
                    `;
                    },
                },
            ],
        });
    };
    load_table();

    $("#tambah_barang").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-tambah']").attr("link");
        $(".btn-simpan").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            processData: false,
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            success: function (res) {
                console.log(res);
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-simpan").prop("disabled", false);
                    $("#tambah_barang").trigger("reset");
                    load_table();
                });
            },
            error: function (xhr) {
                $(".btn-simpan").prop("disabled", false);
                console.log(xhr);
                if (xhr.responseJSON.validation) {
                    validasi(xhr.responseJSON.message);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: xhr.responseJSON.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            },
        });
    });

    $(document).on("click", ".btn-edit", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let url = $("meta[name='link-api-edit']").attr("link");
        $.ajax({
            type: "GET",
            url: url + "/" + id,
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            success: function (res) {
                // console.log(res);
                $("#editModal .modal-body").html(
                    `
                      
                        <div class="mb-3">
                            <label for="name" class="form-label">Name Barang</label>
                            <input type="hidden" name="id" id="id" value="${
                                res.data.id
                            }">
                            <input type="text" name="name" class="form-control" id="name"
                                placeholder="Contoh: Keyboard" value="${
                                    res.data.name_barang
                                }">
                            <div class="text-danger name_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select name="kategori" id="kategori" class="form-select">
                                <option value="">Pilih Kategori</option>
                                ${res.kategori
                                    .map(
                                        (element) => `
                                    <option value="${element.id}" ${
                                            element.id == res.data.kategori_id
                                                ? "selected"
                                                : ""
                                        }>${element.name_kategori}</option>
                                `
                                    )
                                    .join("")}
                            </select>
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah" class="form-label">Jumlah Barang</label>
                            <input type="text" name="jumlah" class="form-control" id="jumlah"
                                placeholder="Contoh: 10" value="${
                                    res.data.jumlah_barang
                                }">
                            <div class="text-danger jumlah_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="kondisi" class="form-label">Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" id="kondisi"
                                placeholder="Contoh: Bekas" value="${
                                    res.data.kondisi_barang
                                }">
                            <div class="text-danger kondisi_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="text" name="harga" class="form-control" id="harga"
                                placeholder="Contoh: 10000" value="${
                                    res.data.harga_barang
                                }">
                            <div class="text-danger harga_err"></div>
                        </div>
                        <div class="mb-4">
                            <label for="lokasi" class="form-label">Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" id="lokasi"
                                placeholder="Contoh: Lab 3" value="${
                                    res.data.lokasi_barang
                                }">
                            <div class="text-danger lokasi_err"></div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-update mx-2">Save changes</button>
                        </div>
               
                    `
                );
            },
        });
    });

    $("#update_barang").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-update']").attr("link");
        let id = $("#id").val();
        $(".btn-update").prop("disabled", true);
        $.ajax({
            type: "POST",
            url: url + "/" + id,
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            processData: false,
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            success: function (res) {
                console.log(res);
            },
        });
    });

    $(document).on("click", ".btn-delete", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let name = $(this).data("nama");
        let url = $("meta[name='link-api-hapus']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan menghapus data " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "GET",
                    url: url + "/" + id,
                    headers: {
                        Authorization: "Bearer " + get_cookie("token"),
                    },
                    success: function (res) {
                        Swal.fire({
                            icon: "success",
                            title: "Berhasil",
                            text: res.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            load_table();
                        });
                    },
                    error: function (xhr) {
                        console.log(xhr);
                        Swal.fire({
                            icon: "error",
                            title: "Gagal",
                            text: xhr.responseJSON.message,
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    },
                });
            }
        });
    });
});
