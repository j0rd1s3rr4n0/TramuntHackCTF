<?php
    session_start();
    session_destroy();
    header("location:index.html"); //Replace with Logged Out page. Remove if you want to use HTML in same file.
?>

// Below is not needed, unless header above is missing. In that case, put logged out text here.
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
    </head>
    <body>
        <!-- Put logged out message here -->
    </body>
</html>