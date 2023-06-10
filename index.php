<?php
    include_once($_SERVER['DOCUMENT_ROOT'] . "/api/general/init.php");
    global $user;
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Homepage</title>
</head>
<body>

    <?php if(isset($user->user_uname)) { ?>
        <h1>Hello, <?php echo $_SESSION['username']; ?>!</h1>
    <?php } else { ?>
        <h1>Hello, unknown user!</h1>
    <?php } ?>

    <p>The environment has now been configured and setup for you. This page is still under construction, but at some point, will be more appealing to look at :)</p>

</body>
</html>
