<?php
#phpinfo();
echo date('l, F jS, Y');
$dbconn = pg_connect("host=localhost dbname=postgres user=postgres password=1234")
or die('Could not connect: ' . pg_last_error());


print "<!DOCTYPE html>
<html>

<head>

</head>

<body>
<h1 style=&quot;color:yellow&quot;> hahahahahahaha</h1>
</body>


</html>";
pg_close($dbconn)
?>