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
                { data: "nama", name: "nama" },
                { data: "barang", name: "barang" },
                { data: "jumlah", name: "jumlah" },
                {
                    data: "harga",
                    /**
                     * Render harga
                     * @param {Number} res response from server
                     * @returns {String} formatted currency string
                     */
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
                     * @param {number} res.status - Status of the request:
                     *  0: Pending
                     *  1: Processing
                     *  2: Rejected, but no reason given
                     *  3: Rejected, with reason given
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

                    /**
                     * Generates action links based on the status of a request.
                     *
                     * @param {Object} res - Response object containing request details.
                     * @param {number} res.status - Status of the request:
                     *  0: Pending
                     *  1: Processing
                     *  2: Rejected, but no reason given
                     *  3: Rejected, with reason given
                     * @param {string} res.id - Identifier of the request.
                     * @param {string} res.name - Name associated with the request.
                     * @returns {string} HTML string containing action buttons for the request.
                     */
                    render: (res) => {
                        // If the request is pending, show the approve and reject buttons
                        if (res.status == 0) {
                            return `<a href="#" class="btn btn-success btn-sm btn-aktif" data-id="${res.id}" data-barang="${res.barang}" data-type="approved" style="color: #FFF;" title="Terima"><i class="bi bi-check-circle"></i></a> 
                            |
                                <a href="#" class="btn btn-danger btn-sm btn-reject" data-id="${res.id}" data-barang="${res.barang}" data-type="rejected" style="color: #FFF;" title="Tolak"><i class="bi bi-x-circle"></i></a>`;
                        }
                        // If the request is being processed, show a "Proses..." message
                        else if (res.status == 1) {
                            return `
                            <a href="#" id="btn-selesai" class="btn btn-success btn-sm btn-selesai" data-id="${res.id}"  
                            data-barang="${res.barang}" style="color: #FFF;" title="Tandai Selesai" data-type="selesai"><i class="bi bi-check-circle"></i></a>
                            `;
                        }
                        // If the request is rejected but no reason given, show a button to write the reason
                        else if (res.status == 2) {
                            return `
                            <button id="btn-reject-message" class="btn btn-danger btn-sm btn-reject-message" data-bs-toggle="modal" data-id="${res.id}" data-barang="${res.barang}" data-bs-target="#alasanModal" style="color: #FFF;" title="Tulis Alasan Penolakan"><i class="bi bi-chat-right-text-fill"></i></button>
                            `;
                        }
                        // If the request is rejected and a reason was given, show a "Pesan Terkirim" message
                        else if (res.status == 3) {
                            return `<span class="badge bg-success">Pesan Terkirim</span>`;
                        } else if (res.status == 4) {
                            return `<span class="badge bg-success">Selesai</span>`;
                        }
                        // If the request is in an error state, show an error message
                        else {
                            return `<span class="badge bg-danger">Error !!</span>`;
                        }
                    },
                },
            ],
        });
    };
    load_table();

    $(document).on("click", ".btn-aktif", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let barang = $(this).data("barang");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan yakin menerima pengajuan barang " + barang,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, terima!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        id: id,
                        type: type,
                    },
                    headers: {
                        Authorization: "Bearer " + get_cookie("token"),
                    },
                    // Success callback when the ajax request is completed successfully
                    // Fires a success notification using Swal and then reloads the table
                    // by calling the load_table function
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            load_table();
                        });
                    },
                    /**
                     * Error callback when the ajax request is completed with an error
                     * Fires an error notification using Swal
                     * @param {Object} xhr - The XMLHttpRequest object
                     */
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: xhr.responseJSON.message,
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", ".btn-reject", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let barang = $(this).data("barang");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan menolak pengajuan barang " + barang,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, tolak barang ini!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        id: id,
                        type: type,
                    },
                    headers: {
                        Authorization: "Bearer " + get_cookie("token"),
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
                            showConfirmButton: false,
                            timer: 3000,
                        }).then(() => {
                            load_table();
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: xhr.responseJSON.message,
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    },
                });
            }
        });
    });

    $(document).on("click", ".btn-reject-message", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let url = $("meta[name='link-api-reject']").attr("link");
        $.ajax({
            type: "GET",
            url: url + "/" + id,
            headers: {
                Authorization: "Bearer " + get_cookie("token"),
            },
            /**
             * Success callback for AJAX request to get detail of a specific
             * rejection reason.
             *
             * @param {Object} res - Response object from AJAX request
             */
            success: function (res) {
                $("#alasanModal .modal-body").html(
                    `
                    <div class="mb-4">
                    <input type="hidden" name="id" id="id" value="${res.data.pengajuan_id}">
                        <label for="alasan_penolakan" class="form-label">Alasan Penolakan</label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="3"></textarea>
                        <div class="text-danger alasan_penolakan_err"></div>
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

    $(document).on("click", ".btn-selesai", function (e) {
        e.preventDefault();
        let id = $(this).data("id");
        let barang = $(this).data("barang");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text:
                "Anda akan menyelesaikan pengajuan barang " +
                barang +
                " & Barang akan dipindahkan ke list Inventaris Barang",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, selesaikan!",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        id: id,
                        barang: barang,
                        type: type,
                    },
                    headers: {
                        Authorization: "Bearer " + get_cookie("token"),
                    },
                    success: function (response) {
                        console.log(response);
                        Swal.fire({
                            icon: "success",
                            title: "Success",
                            text: response.message,
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
                            title: "Error",
                            text: xhr.responseJSON.message,
                            showConfirmButton: false,
                            timer: 3000,
                        });
                    },
                });
            }
        });
    });

    $("#form_penolakan").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-penolakan']").attr("link");
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
                // console.log(res.data.alasan_penolakan);
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: res.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $("#alasanModal").modal("hide");
                    load_table();
                });
            },
            error: function (xhr, status, error) {
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
});
