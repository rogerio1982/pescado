<?php 
$db_server = 'localhost'; 
$db_name = 'plat4478_appdoencas'; 
$db_user = 'plat4478_admin'; 
$db_password = 'admin2020'; 
$no_of_records_per_page = 10; 
$appname = 'AppDoenças'; 

$link = mysqli_connect($db_server, $db_user, $db_password, $db_name); 
$query = "SHOW VARIABLES LIKE 'character_set_database'";
if ($result = mysqli_query($link, $query)) {
    while ($row = mysqli_fetch_row($result)) {
        if (!$link->set_charset($row[1])) {
            printf("Error loading character set $row[1]: %s\n", $link->error);
            exit();
        } else {
            // printf("Current character set: %s", $link->character_set_name());
        }
    }
}

?>