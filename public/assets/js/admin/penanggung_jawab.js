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
