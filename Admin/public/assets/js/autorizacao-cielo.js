$(window).on("load", function () {
  const url = window.location.href;
  dados_cliente = param;
  if (url.indexOf("code") != -1) {
    code = url.split("=");
    email = localStorage.getItem("email");

    if (dados_cliente.ACCESS_TOKEN != null) {
      console.log("TEM TOKEEEEEEEEEEEEEEEEEEEN");
      array = [
        "SELL",
        "PAYMENT",
        "ANTECIPATION_CIELO",
        "ASSIGNMENT",
        "BALANCE",
        "ANTECIPATION_ALELO",
      ];
      $.ajax({
        url: "https://api2.cielo.com.br/edi-api/v2/edi/registers",
        type: "post",
        beforeSend: function (xhr) {
          xhr.setRequestHeader(
            "Authorization",
            "Bearer " + dados_cliente.ACCESS_TOKEN
          );
        },
        data: { type: array, merchantEMail: "teste@teste.com" },
        data: JSON.stringify({ type: array, merchantEMail: email }),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (response) {
          console.log(response.status);
          registerID = response["registerID"];

          $.ajax({
            url: "{{ url('credenciamento-edi') }}",
            type: "post",
            header: {
              "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            data: { _token: "{{csrf_token()}}", registerID, email },
            dataType: "json",
            success: function (response) {
              if (response) {
                $("#exampleModal").modal({
                  show: true,
                });
              }
            },
          });
        },
      }).fail(function () {
        $("#exampleModalErro").modal({
          show: true,
        });
      });
    } else {
      console.log("NAO TEM TOKEEEEEEEEEEEEEEEEEEEN");

      $.ajax({
        url: "https://api2.cielo.com.br/consent/v1/oauth/access-token",
        type: "post",
        beforeSend: function (xhr) {
          xhr.setRequestHeader(
            "Authorization",
            "Basic " +
              "MGQ4NzM5NDEtNGFlNi0zMzQ0LWJkNzItNjNmOWZmNDA1OGE4OmYzMzBiYWNmLTg2ZTUtM2I1Ny04YzI3LTQzNDk3MzdhMzY0YQ=="
          );
        },
        data: { grant_type: "authorization_code", code: code[2] },
        dataType: "json",
        success: function (response) {
          acess_token = response["access_token"];
          refresh_token = response["refresh_token"];

          $.ajax({
            url: "{{ url('autorizar-acesso') }}",
            type: "post",
            data: { _token: "{{csrf_token()}}", acess_token, refresh_token },
            dataType: "json",
            success: function (response) {
              if (response) {
                array = [
                  "SELL",
                  "PAYMENT",
                  "ANTECIPATION_CIELO",
                  "ASSIGNMENT",
                  "BALANCE",
                  "ANTECIPATION_ALELO",
                ];
                $.ajax({
                  url: "https://api2.cielo.com.br/edi-api/v2/edi/registers",
                  type: "post",
                  beforeSend: function (xhr) {
                    xhr.setRequestHeader(
                      "Authorization",
                      "Bearer " + acess_token
                    );
                  },
                  data: { type: array, merchantEMail: "teste@teste.com" },
                  data: JSON.stringify({ type: array, merchantEMail: email }),
                  contentType: "application/json; charset=utf-8",
                  dataType: "json",
                  success: function (response) {
                    console.log(response.status);
                    registerID = response["registerID"];

                    $.ajax({
                      url: "{{ url('credenciamento-edi') }}",
                      type: "post",
                      header: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                          "content"
                        ),
                      },
                      data: { _token: "{{csrf_token()}}", registerID, email },
                      dataType: "json",
                      success: function (response) {
                        if (response) {
                          $("#exampleModal").modal({
                            show: true,
                          });
                        }
                      },
                    });
                  },
                }).fail(function () {
                  $("#exampleModalErro").modal({
                    show: true,
                  });
                });
              }
            },
          });
        },
      });
    }
  }

  preCarregarGraficoVendas();
  // página totalmente carregada (DOM, imagens etc.)
});
