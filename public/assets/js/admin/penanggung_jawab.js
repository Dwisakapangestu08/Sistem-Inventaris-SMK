$(document).ready(function () {
    const validasi = (message) => {
        $.each(message, function (key, value) {
            $("." + key + "_err").text(value);
        });
    };
    const load_table = () => {
        let url = $("meta[name='link-api']").attr("link");
        $("table#table_penanggung_jawab").DataTable().destroy();
        $("table#table_penanggung_jawab").DataTable({
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
                {
                    data: null,
                    render: (res) => {
                        // let link = $("meta[name='link-api-edit']").attr("link");
                        // let link_edit = link + "/" + res.id;
                        return `
                        <a href="#" class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                            data-bs-target="#editModal" data-id="${res.id}" style="color: #FFF;" title="edit"><i class="bi bi-pen"></i></a> |
                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="${res.id}" data-nama="${res.nama}" data-barang="${res.barang}" style="color: #FFF;" title="Hapus"><i class="bi bi-trash"></i></a>
                    `;
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
