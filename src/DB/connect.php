<?php
// PHP Data Objects(PDO) Sample Code:
try {
    $conn = new PDO("sqlsrv:server = tcp:desasqlserverteroz.database.windows.net,1433; Database = desaTeroz", "teroz", "vff267dqwXp2G^");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {
    print("Error connecting to SQL Server.");
    die(print_r($e));
}

// SQL Server Extension Sample Code:
$connectionInfo = array("UID" => "teroz", "pwd" => "vff267dqwXp2G^", "Database" => "desaTeroz", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:desasqlserverteroz.database.windows.net,1433";
$conn = sqlsrv_connect($serverName, $connectionInfo);
?>