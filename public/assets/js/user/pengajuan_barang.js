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
    /**
     * Initializes and configures the DataTable for displaying pengajuan data.
     *
     * This function fetches data from a specified API endpoint and populates
     * the DataTable with the retrieved data. The table supports server-side
     * processing and includes columns for various attributes such as "nama",
     * "barang", "jumlah", "harga", etc. It also includes custom render
     * functions for displaying formatted currency, status badges, and action
     * buttons based on the status of each pengajuan item.
     */
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
                    /**
                     * Render nomor urut
                     * @param {Object} data data row from server
                     * @param {String} type type of data
                     * @param {Object} row data row from server
                     * @param {Object} meta data meta from datatables
                     * @returns {String} nomor urut di awali dengan tanda titik
                     */
                    render: function (data, type, row, meta) {
                        return (
                            meta.row + meta.settings._iDisplayStart + 1 + "."
                        );
                    },
                },
                { data: "barang", name: "barang" },
                { data: "jumlah", name: "jumlah" },
                {
                    data: "harga",
                    render: (res) => {
                        return formatCurrency(res);
                    },
                },
                { data: "kondisi", name: "kondisi" },
                {
                    data: "total",
                    /**
                     * Render total harga
                     * @param {Number} res response from server
                     * @returns {String} formatted currency string
                     */
                    render: (res) => {
                        return formatCurrency(res);
                    },
                },
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
                            span = `<span class="badge bg-warning">Pending</span>`;
                        } else if (res.status == 1) {
                            span = `<span class="badge bg-success">Diterima</span>`;
                        } else if (res.status == 4) {
                            span = `<span class="badge bg-success">Diterima</span>`;
                        } else {
                            span = `<span class="badge bg-danger">Ditolak</span>`;
                        }
                        return span;
                    },
                },
                {
                    data: null,
                    render: (res) => {
                        let anchor = "";
                        if (res.status == 1) {
                            anchor = `<span class="badge bg-success">Disetujui</span>`;
                        } else if (res.status == 0) {
                            anchor = `
                        <a href="#" class="btn btn-warning btn-sm btn-edit" data-bs-toggle="modal"
                            data-bs-target="#editModal" data-id="${res.id}" style="color: #FFF;" title="Edit"><i class="bi bi-pen"></i></a> 
                            |
                            <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="${res.id}" data-nama="${res.barang}" style="color: #FFF;" title="Hapus"><i class="bi bi-trash"></i></a>`;
                        } else if (res.status == 4) {
                            anchor = `<span class="badge bg-success">Selesai</span>`;
                        } else {
                            anchor = `
                            <a href="#" class="btn btn-danger btn-sm btn-penolakan" data-bs-toggle="modal"
                            data-bs-target="#penolakanModal" data-id="${res.id}" style="color: #FFF;" title="Baca Penolakan"><i class="bi bi-envelope-paper"></i></a>`;
                        }
                        return anchor;
                    },
                },
            ],
        });
    };
    load_table();

    $("#pengajuan_barang").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-pengajuan']").attr("link");
        let loading = $('meta[name="loading"]').attr("link");
        let loadingLoad = `<img src="${loading}" height="35" width="35"> Proses Pengajuan`;
        let empty = "";
        $(".btn-simpan").html(empty);
        $(".btn-simpan").html(loadingLoad);
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
            success: (res) => {
                // console.log(res);
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-simpan").html("Kirim");
                    $(".btn-simpan").prop("disabled", false);
                    $("#pengajuan_barang").trigger("reset");
                    $(".modal").modal("hide");
                    load_table();
                });
            },

            error: (xhr, ajaxOptions, thrownError) => {
                $(".btn-simpan").html(empty);
                $(".btn-simpan").html("Kirim");
                $(".btn-simpan").prop("disabled", false);
                console.log(xhr.responseText);
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

    $(document).on("click", ".btn-penolakan", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let url = $("meta[name='link-api-penolakan']").attr("link");
        $.ajax({
            type: "GET",
            url: url + "/" + id,
            dataType: "JSON",
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            success: (res) => {
                // console.log(res);
                $("textarea#alasan_penolakan").html(res.data.alasan_penolakan);
            },
            error: (xhr, ajaxOptions, thrownError) => {
                // console.log(xhr.responseText);
            },
        });
    });

    $(document).on("click", ".btn-edit", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let url = $("meta[name='link-api-edit']").attr("link");
        $.ajax({
            type: "GET",
            dataType: "JSON",
            url: url + "/" + id,
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            success: (res) => {
                // console.log(res.data);
                $("input#id").val(res.data.id);
                $("input#name_barang_pengajuan").val(
                    res.data.name_barang_pengajuan
                );
                $("input#jumlah_barang_pengajuan").val(
                    res.data.jumlah_barang_pengajuan
                );
                $("input#harga_perkiraan").val(res.data.harga_perkiraan);
                $("input#kondisi").val(res.data.kondisi);
                $("input#tujuan_pengajuan").val(res.data.tujuan_pengajuan);
            },
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(xhr.responseText);
            },
        });
    });

    $("#edit_pengajuan").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-update']").attr("link");
        let id = $("#id").val();
        let loading = $('meta[name="loading"]').attr("link");
        let loadingLoad = `<img src="${loading}" height="35" width="35"> Proses Pengajuan`;
        let empty = "";
        $(".btn-update").html(empty);
        $(".btn-update").html(loadingLoad);
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
            success: (res) => {
                console.log(res);
                Swal.fire({
                    icon: "success",
                    title: "Berhasil",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-simpan").html("Kirim");
                    $(".btn-simpan").prop("disabled", false);
                    $("#edit_pengajuan").trigger("reset");
                    $(".modal").modal("hide");
                    window.location.reload();
                });
            },

            error: (xhr, ajaxOptions, thrownError) => {
                $(".btn-simpan").html(empty);
                $(".btn-simpan").html("Kirim");
                $(".btn-simpan").prop("disabled", false);
                console.log(xhr.responseText);
                if (xhr.responseJSON.validation) {
                    validasi(xhr.responseJSON.message);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Gagal",
                        text: xhr.responseJSON.exception,
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
        let name_barang = $(this).data("nama");
        let url = $("meta[name='link-api-delete']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan menghapus data " + name_barang,
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
