$(document).ready(function () {
    const load_table = () => {
        let url = $("meta[name='link-api']").attr("link");
        $("table#table_user").DataTable().destroy();
        $("table#table_user").DataTable({
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
                { data: "name", name: "name" },
                { data: "email", name: "email" },
                { data: "jabatan", name: "jabatan" },
                {
                    data: null,
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
                // {
                //     data: null,
                //     render: (res) => {
                //         let link = $("meta[name='link-api-edit']").attr("link");
                //         let link_edit = link + "/" + res.id;
                //         return `
                //         <a href="${link_edit}" class="btn btn-warning btn-sm btn-edit" style="color: #FFF;" title="edit"><i class="bi bi-pen"></i></a> |
                //         <a href="#" class="btn btn-danger btn-sm btn-delete" data-id="${res.id}" data-nama-santri="${res.name}" style="color: #FFF;" title="Hapus"><i class="bi bi-trash"></i></a>
                //     `;
                //     },
                // },
            ],
        });
    };
    load_table();

    $(document).on("click", ".btn-aktif", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan mengaktifkan user " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, aktifkan!",
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
    $(document).on("click", ".btn-reject", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan menolak user " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, tolak user ini!",
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
    $(document).on("click", ".btn-banned", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan banned user " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, banned user ini!",
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
    $(document).on("click", ".btn-unbanned", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");
        let type = $(this).data("type");
        let url = $("meta[name='link-api-status']").attr("link");
        Swal.fire({
            title: "Apakah anda yakin?",
            text: "Anda akan unbanned user " + name,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, unbanned user ini!",
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
});
