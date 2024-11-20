$(document).ready(function () {
    const load_table = () => {
        let url = $("meta[name='link-api']").attr("link");
        $("table#table_pengajuan").DataTable().destroy();
        $("table#table_pengajuan").DataTable({
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
                { data: "nama", name: "nama" },
                { data: "barang", name: "barang" },
                { data: "jumlah", name: "jumlah" },
                { data: "harga", name: "harga" },
                { data: "kondisi", name: "kondisi" },
                { data: "total", name: "total" },
                { data: "tujuan", name: "tujuan" },
                {
                    data: null,
                    /**
                     * Render status
                     * @param {Object} res response from server
                     * @returns {String} status in string
                     */
                    render: (res) => {
                        let span = "";
                        if (res.status == 0) {
                            span = `<span class="badge bg-danger">Belum aktif</span>`;
                        } else if (res.status == 1) {
                            span = `<span class="badge bg-success">Aktif</span>`;
                        } else if (res.status == 3) {
                            span = `<span class="badge bg-danger">Akun diblokir</span>`;
                        } else {
                            span = `<span class="badge bg-warning">Ditolak</span>`;
                        }
                        return span;
                    },
                },
                {
                    data: null,
                    /**
                     * Render a button that can be used to manage the status of the Pengajuan Barang.
                     * If the status is 0, the button will be "Aktifkan" and "Tolak".
                     * If the status is 1, the button will be "Banned".
                     * If the status is 3, the button will be "UnBanned".
                     * If the status is 4, the button will be "Aktifkan".
                     * @param {Object} res - The data of the Pengajuan Barang.
                     * @returns {String} A string containing the HTML of the button.
                     */
                    render: (res) => {
                        let link = "";
                        if (res.status == 0) {
                            link = `<a href="#" class="btn btn-success btn-sm btn-aktif" data-id="${res.id}" data-name="${res.name}" data-type="approved" style="color: #FFF;" title="Aktifkan"><i class="bi bi-check-circle"></i></a> 
                            |
                                <a href="#" class="btn btn-danger btn-sm btn-reject" data-id="${res.id}" data-name="${res.name}" data-type="rejected" style="color: #FFF;" title="tolak"><i class="bi bi-x-circle"></i></a>`;
                        } else if (res.status == 1) {
                            link = `<a href="#" class="btn btn-danger btn-sm btn-banned" data-id="${res.id}" data-name="${res.name}" data-type="banned" style="color: #FFF;" title="Banned"><i class="bi bi-ban"></i></a>`;
                        } else if (res.status == 3) {
                            link = `<a href="#" class="btn btn-success btn-sm btn-unbanned" data-id="${res.id}" data-name="${res.name}" data-type="unbanned" style="color: #FFF;" title="UnBanned"><i class="bi bi-check"></i></a>`;
                        } else if (res.status == 4) {
                            link = `<a href="#" class="btn btn-danger btn-sm btn-aktif" data-id="${res.id}" data-name="${res.name}" data-type="deleted" style="color: #FFF;" title="Aktifkan"><i class="bi bi-check-circle"></i></a>`;
                        }
                        return link;
                    },
                },
            ],
        });
    };
    load_table();

    $("#tambah_penanggung_jawab").on("submit", function (e) {
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
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-simpan").prop("disabled", false);
                    $("#tambah_penanggung_jawab").trigger("reset");
                    load_table();
                });
            },
            error: function (xhr) {
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
                $("#editModal .modal-body").html(
                    `
                        <div class="mb-3">
                            <label for="guru" class="form-label">Guru</label>
                            <input type="hidden" id="id" value="${res.data.id}">
                            <select name="guru" id="guru" class="form-select">
                                <option value="">Pilih Guru</option>
                                ${res.guru
                                    .map(
                                        (element) => `
                                    <option value="${element.id}" ${
                                            element.id == res.data.user_id
                                                ? "selected"
                                                : ""
                                        }>${element.name}</option>
                                `
                                    )
                                    .join("")}
                            </select>
                            <div class="text-danger guru_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="barang" class="form-label">Barang</label>
                            <select name="barang" id="barang" class="form-select">
                                <option value="">Pilih Barang</option>
                                ${res.barang
                                    .map(
                                        (element) => `
                                    <option value="${element.id}" ${
                                            element.id == res.data.barang_id
                                                ? "selected"
                                                : ""
                                        }>${element.name_barang}</option>
                                `
                                    )
                                    .join("")}
                            </select>
                            <div class="text-danger barang_err"></div>
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

    $("#update_penanggung_jawab").on("submit", function (e) {
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
                // console.log(res);
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-update").prop("disabled", false);
                    $("#editModal").modal("hide");
                    load_table();
                });
            },
            error: function (xhr) {
                console.log(xhr);
                $(".btn-update").prop("disabled", false);
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
                load_table();
            },
        });
    });

    $(document).on("click", ".btn-delete", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-hapus']").attr("link");
        let id = $(this).data("id");
        let nama = $(this).data("nama");
        let barang = $(this).data("barang");
        Swal.fire({
            title: "Hapus Penanggung Jawab ?",
            text: nama + " - " + barang,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Hapus!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url + "/" + id,
                    type: "GET",
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
