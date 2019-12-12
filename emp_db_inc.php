<?php  
// session_start();

$host = 'localhost';  
$user = 'root';  
$passwd = '';  
$schema = 'employee_management';  

$pdo = NULL;  
$dsn = 'mysql:host=' . $host . ';dbname=' . $schema;  
try {      
    $pdo = new PDO($dsn, $user,  $passwd);        
    /* Enable exceptions on errors */    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
} 
catch (PDOException $e) {   
    /* If there is an error an exception is thrown */    
    echo 'Database connection failed.---';    
    die(); 
} 
