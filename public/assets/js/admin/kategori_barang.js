$(document).ready(function () {
    const validasi = (message) => {
        $.each(message, function (key, value) {
            $("." + key + "_err").text(value);
        });
    };
    const load_table = () => {
        let url = $("meta[name='link-api']").attr("link");
        $("table#table_kategori").DataTable().destroy();
        $("table#table_kategori").DataTable({
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
                { data: "name_kategori", name: "name_kategori" },
                { data: "deskripsi", name: "deskripsi" },
                {
                    data: null,
                    render: (res) => {
                        // let link = $("meta[name='link-api-edit']").attr("link");
                        // let link_edit = link + "/" + res.id;
                        return `
                        <a href="#" data-bs-toggle="modal"
                            data-bs-target="#editModal" class="btn btn-warning btn-sm btn-edit" data-id="${res.id}" style="color: #FFF;" title="edit"><i class="bi bi-pen"></i></a> |
                        <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="${res.id}" data-name="${res.name}" style="color: #FFF;" title="Hapus"><i class="bi bi-trash"></i></a>
                    `;
                    },
                },
            ],
        });
    };

    load_table();

    $("#tambah_kategori").on("submit", function (e) {
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
                // console.log(res);
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $("#tambah_kategori")[0].reset();
                    $("#exampleModal").modal("hide");
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
                $(".btn-simpan").prop("disabled", false);
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
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="hidden" id="id" value="${res.data.id}">
                            <input type="text" name="kategori" class="form-control" id="kategori"
                                placeholder="Kategori" value="${res.data.name_kategori}">
                            <div class="text-danger kategori_err"></div>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3">${res.data.deskripsi}</textarea>
                            <div class="text-danger deskripsi_err"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-update">Ubah</button>
                    </div>
                   `
                );
            },
        });
    });

    $("#edit_kategori").on("submit", function (e) {
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
                    $("#editModal").modal("hide");
                    load_table();
                });
            },
            error: function (xhr) {
                $(".btn-update").prop("disabled", false);
                const jsonParse = JSON.parse(xhr.responseText);
                console.log(jsonParse);
                if (jsonParse.validation) {
                    validasi(jsonParse.message);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Login Failed",
                        text: jsonParse.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            },
        });
    });

    $(document).on("click", ".btn-delete", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let name = $(this).data("name");
        let url = $("meta[name='link-api-hapus']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan menghapus kategori " + name,
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
                        }).then(() => {
                            load_table();
                        });
                    },
                });
            }
        });
    });
});
