$(document).ready(function () {
    $("#check_password").on("click", function () {
        if ($(this).is(":checked")) {
            $(".password").attr("type", "text");
        } else {
            $(".password").attr("type", "password");
        }
    });

    const validasi = (message) => {
        $.each(message, function (key, value) {
            $("." + key + "-err").text(value);
        });
    };

    $("#login-form").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-login']").attr("link");
        let loading = $('meta[name="loading"]').attr("link");
        let loadingLoad = `<img src="${loading}" height="35" width="35"> Proses Login`;
        let empty = "";
        $(".btn-login").html(empty);
        $(".btn-login").html(loadingLoad);
        $(".btn-login").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                Swal.fire({
                    icon: "success",
                    title: "Login Success",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-login").html("Login");
                    $(".btn-login").prop("disabled", false);
                    set_cookie("token", response.remember_token);
                    window.location.href = response.redirect;
                });
            },
            error: function (xhr) {
                $(".btn-register").html("Register");
                $(".btn-register").prop("disabled", false);
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

    $("#register-form").on("submit", function (e) {
        e.preventDefault();
        let url = $("meta[name='link-api-register']").attr("link");
        let loading = $('meta[name="loading"]').attr("link");
        let loadingLoad = `<img src="${loading}" height="35" width="35"> Proses Register`;
        let empty = "";
        $(".btn-register").html(empty);
        $(".btn-register").html(loadingLoad);
        $(".btn-register").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: url,
            data: new FormData(this),
            dataType: "json",
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                Swal.fire({
                    icon: "success",
                    title: "Register Success",
                    text: response.message,
                    showConfirmButton: false,
                    timer: 3000,
                }).then(() => {
                    $(".btn-register").html("Register");
                    $(".btn-register").prop("disabled", false);
                    window.location.href = response.redirect;
                });
            },
            error: function (xhr) {
                $(".btn-register").html("Register");
                $(".btn-register").prop("disabled", false);
                const jsonParse = JSON.parse(xhr.responseText);
                console.log(jsonParse);
                if (jsonParse.validation) {
                    validasi(jsonParse.message);
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Register Failed",
                        text: jsonParse.message,
                        showConfirmButton: false,
                        timer: 3000,
                    });
                }
            },
        });
    });
});
