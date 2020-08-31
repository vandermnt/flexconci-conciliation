$(window).on("load", function () {
  const url = window.location.href;
  if (url.indexOf("code") != -1) {
    code = url.split("=");
    email = localStorage.getItem("email");

    // $.ajax({
    //   // url: "https://api2.cielo.com.br/consent/v1/oauth/access-token",
    //   url: "https://apihom-cielo.sensedia.com/consent/v1/oauth/access-token",
    //   type: "post",
    //   beforeSend: function (xhr) {
    //     xhr.setRequestHeader(
    //       "Authorization",
    //       "Basic " +
    //         "MGQ4NzM5NDEtNGFlNi0zMzQ0LWJkNzItNjNmOWZmNDA1OGE4OmYzMzBiYWNmLTg2ZTUtM2I1Ny04YzI3LTQzNDk3MzdhMzY0YQ=="
    //     );
    //   },
    //   data: { grant_type: "authorization_code", code: code[2] },
    //   dataType: "json",
    //   success: function (response) {
    acess_token = "d20bf885-0ad1-3d5e-b505-1b7d59c6dbc6";
    refresh_token = "ee802773-e45a-37e4-a587-faa9fa323fa5";
    console.log(acess_token);

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
      // url: "https://apihom-cielo.sensedia.com/edi-api/v2/edi/registers",
      type: "post",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("Authorization", "Bearer " + acess_token);
      },
      data: JSON.stringify({
        type: array,
        merchantEMail: "implantacao@conciflex.com.rb",
      }),
      contentType: "application/json; charset=utf-8",
      dataType: "json",
      success: function (response) {
        console.log(response.status);
        registerID = response["registerID"];
        merchants = response["merchants"];
        mainMerchantId = response["mainMerchantId"];
        codigo = code[2];

        $.ajax({
          url: "{{ url('credenciamento-edi') }}",
          type: "post",
          header: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
          },
          data: {
            _token: "{{csrf_token()}}",
            registerID,
            email,
            acess_token,
            refresh_token,
            codigo,
            mainMerchantId,
            merchants,
          },
          dataType: "json",
          success: function (response) {
            console.log(response);
          },
        });
      },
    });
    //   },
    // });
  }
});
