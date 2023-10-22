<?php

require 'OracleConnection.php';

$oracleConnection = new OracleConnection();
$connection = $oracleConnection->getConnection();

if ($connection) {
    echo "Conexión exitosa a la base de datos Oracle.";

     // Enrutamiento básico para la ruta "/createRecord"
     if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['insert_transaction'])) {
        if ($_SERVER['REQUEST_URI'] === '/createRecord') {
            // Obtén los datos JSON de la solicitud
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true);

            if ($data) {
                // Llama al método createRecord para insertar la transacción
                $result = $oracleConnection->createRecord(
                    $data['source_client_id'],
                    $data['destination_client_id'],
                    $data['transaction_date'],
                    $data['currency_type'],
                    $data['payment_amount']
                );

                if ($result) {
                    echo "Transacción creada con éxito.";
                } else {
                    echo "Error al crear la transacción.";
                }
            } else {
                echo "Datos JSON incorrectos.";
            }
        }
    }

} else {
    echo "No se pudo conectar a la base de datos Oracle.";
}

?>

