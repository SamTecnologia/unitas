<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conecta = "localhost";
$database_conecta = "unitas";
$username_conecta = "root";
$password_conecta = "mysql";
$conecta = mysql_pconnect($hostname_conecta, $username_conecta, $password_conecta) or trigger_error(mysql_error(),E_USER_ERROR); 
?>