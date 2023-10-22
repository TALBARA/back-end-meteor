<?php
require 'vendor/autoload.php'; 

class OracleConnection
{
    private $conn;

    public function __construct()
    {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
        $dotenv->load();

        $db_host = $_ENV['DB_HOST'];
        $db_port = $_ENV['DB_PORT'];
        $db_sid = $_ENV['DB_SID'];
        $db_user = $_ENV['DB_USER'];
        $db_pass = $_ENV['DB_PASS'];

        // Establecer la conexión a Oracle
        $this->conn = oci_connect($db_user, $db_pass, "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=$db_host)(PORT=$db_port))(CONNECT_DATA=(SID=$db_sid)))");
        if (!$this->conn) {
            die("Error al conectar a la base de datos: " . oci_error());
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function createRecord($sourceClientId, $destinationClientId, $transactionDate, $currencyType, $paymentAmount)
    {
    $sql = "INSERT INTO transactions (source_client_id, destination_client_id, transaction_date, currency_type, payment_amount) VALUES (:sourceClientId, :destinationClientId, TO_DATE(:transactionDate, 'YYYY-MM-DD'), :currencyType, :paymentAmount)";

    $stmt = oci_parse($this->conn, $sql);
    oci_bind_by_name($stmt, ":sourceClientId", $sourceClientId);
    oci_bind_by_name($stmt, ":destinationClientId", $destinationClientId);
    oci_bind_by_name($stmt, ":transactionDate", $transactionDate);
    oci_bind_by_name($stmt, ":currencyType", $currencyType);
    oci_bind_by_name($stmt, ":paymentAmount", $paymentAmount);

    $result = oci_execute($stmt);

    return $result;
    }


    public function getRecords()
    {
        // Implementa la lógica para obtener una lista de registros
    }
}
