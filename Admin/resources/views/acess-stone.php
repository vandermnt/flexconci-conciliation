<?php

$headers = [
    'authorization: Bearer 028f2e66-0134-477a-8d4c-12f122767680',
    'x-authorization-raw-data: Conciflex',
    'x-authorization-encrypted-data: 1351f414d687764000a993974029f1f20ac5b37e4b66e86841e22f85d5671926489dd848a0563c67e63ec61a5cdaff9756e3c142ae253e8d0a69b13338ebad40',
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
// curl_setopt($curl, CURLOPT_MAXREDIRS, 3);
// curl_setopt($curl, CURLOPT_ENCODING, 'gzip');

curl_setopt($curl, CURLOPT_POST,           true);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST,  'PUT');

//$url = "https://conciliation.stone.com.br/v1/merchant/" . $code . "/conciliation-file/" . $data;
$url = "https://conciliation.stone.com.br/v1/merchant/175072086/access-authorization";
curl_setopt($curl, CURLOPT_URL, $url);
//curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
$server_output = curl_exec($curl);

echo "retorno = " . curl_getinfo($curl, CURLINFO_HTTP_CODE);

curl_close($curl);
