<?php
$host = "gateway01.ap-southeast-1.prod.aws.tidbcloud.com";
$port = 4000;
$user = "2RUtomSKNHYamAH.root";
$password = "uuIpNSj1cplnuSxT";
$dbname = "test";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    
    $conn = new PDO($dsn, $user, $password, $options);
    // echo "Connected successfully!";
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
