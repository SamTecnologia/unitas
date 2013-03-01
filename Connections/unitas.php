<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_unitas = "localhost";
$database_unitas = "samtec_unitas";
$username_unitas = "samtec_unitas";
$password_unitas = "unitas";
$unitas = mysql_pconnect($hostname_unitas, $username_unitas, $password_unitas) or trigger_error(mysql_error(),E_USER_ERROR); 
?>