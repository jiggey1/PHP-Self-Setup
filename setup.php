<?php
    include_once("./api/setup/setup.php");
    include_once("./api/fileManager/SetupFileManager.php");

    $setup = new SetupFileManager();

    // Stage 0 means, Database Setup
    $stage = 0;

    if($setup->getDatabaseStatus()) {
        $stage = 1;
    }

    if($stage == 1) {
        if($setup->isDatabaseReady()) {
            $stage = 2;
        }
    }

    if($stage == 2) {
        if($setup->doesAdminExist()) {
            $stage = 3;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Setup Page</title>

    <!-- CSS Stylesheet -->
    <link rel="stylesheet" href="./assets/css/main.css">

    <style>
        html {
            text-decoration: none;
        }

        li {
            list-style: none;
        }
    </style>

</head>
<body>

    <section id="setup">
        <div class="content" style="text-align: center">
            <?php
            switch ($stage) {
                case 0:
            ?>
            <h1>Configure WebServer</h1>
            <p><strong>You will only have to do this once, make sure you complete all required steps!</strong></p>

            <br /><br />

            <h2>Database Setup</h2>
            <form action="./api/setup/setup.php?request=database_setup" target="_self" method="post">
                <label for="db_host">Database Host (Example: localhost / 127.0.0.1)</label><br />
                <input type="text" name="db_host" id="db_host" maxlength="40" required><br /><br />

                <label for="db_port">Database Port (Default Port: 3306)</label><br />
                <input type="number" name="db_port" max="99999" id="db_port" maxlength="5" required><br /><br />

                <label for="db_root_name">Database Root Username (Example: root)</label><br />
                <input type="text" name="db_root_name" id="db_root_name" maxlength="128" required><br /><br />

                <label for="db_root_pass">Database Root Password (The 'root' users password.)</label><br />
                <input type="text" name="db_root_pass" id="db_root_pass" maxlength="128" required><br /><br />

                <button type="submit">Setup Database</button>
            </form>

            <?php break;
            case 1:?>

            <h1>Configuring Database + Tables</h1>
            <p>The next part of this process will create all the required databases and tables needed, using the database you provided.</p><br />
            <p>The next stage is: Setting up admin accounts + basic configuration.</p>

            <h3>The following databases will be created:</h3>
            <ul>
                <li>- system</li>
            </ul><br />
            <h3>The following tables will be created:</h3>
            <ul>
                <li>- users</li>
                <li>- tickets</li>
                <li>- ticket_history</li>
            </ul><br /><br />
                <form action="./api/setup/setup.php?request=database_configure" method="post">
                    <button type="submit">Setup Environment</button>
                </form>
            <?php break;
            case 2:?>

                <h2>Admin Account Setup</h2>
                <p>Create an admin account. This will function as your main account, but will be setup as an administrator account.</p>
                <form action="./api/setup/setup.php?request=account_setup" target="_self" method="post">
                    <label for="admin_name">Account Username:</label><br />
                    <input type="text" name="admin_name" id="admin_name" maxlength="128" required><br /><br />

                    <label for="admin_email">Account E-Mail:</label><br />
                    <input type="text" name="admin_email" id="admin_email" maxlength="128" required><br /><br />

                    <label for="admin_pass">Account Password:</label><br />
                    <input type="text" name="admin_pass" id="admin_pass" maxlength="128" required><br /><br />

                    <label for="admin_pass_conf">Confirm Password:</label><br />
                    <input type="text" name="admin_pass_conf" id="admin_pass_conf" maxlength="128" required><br /><br />

                    <button type="submit">Create Account</button>
                </form>

            <?php break;
            case 3:
                http_response_code(404);
                echo "<script>window.location='./index.php'</script>";
                exit();
            break; } ?>

        </div>

    </section>

</body>
</html>