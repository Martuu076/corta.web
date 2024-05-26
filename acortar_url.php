<?php
$msg = '';
$flag = false;

try {
    if (isset($_POST['url'])) {
        $url = $_POST['url'];
        $apiKey = '30a5b3d49c9330d788a8681c8e85e74eb3e01e11';
        
        // Datos para enviar a Bitly
        $data = array(
            'long_url' => $url,
            'domain' => 'bit.ly' // Opcional: puedes especificar el dominio de acortamiento si tienes un dominio personalizado
        );

        // Configurar la solicitud a Bitly
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api-ssl.bitly.com/v4/shorten');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ));

        // Ejecutar la solicitud y obtener la respuesta
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Verificar el código de estado HTTP y procesar la respuesta
        if ($httpcode >= 200 && $httpcode < 300) {
            $short_url = json_decode($output);
            $msg = $short_url->link;
            $flag = true;
        } else {
            throw new Exception('Error al acortar la URL. Código de estado HTTP: ' . $httpcode);
        }
    }
} catch (Exception $e) {
    $msg = $e->getMessage();
    $flag = true;
}

// Devolver el resultado como texto
echo $msg;
?>
