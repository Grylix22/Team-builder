<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backend/Full-stack recruitment task</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Exo+2:wght@900&family=Josefin+Sans:wght@300;400;700&family=Signika+Negative:wght@400;500;700&display=swap"
        rel="stylesheet">
</head>

<body>

    <main>
        <?php

        require_once './partials/main.php';

        ?>


        <!-- add user to JSON file -->
        <form method="POST" action="index.php" id="addUserForm">
            <span>Add User</span>

            <div class="inputBox">
                <div class="inputRow">
                    <label for="name">Name: </label>
                    <input type="text" name="name" id="" placeholder="Name">
                </div>


                <div class="inputRow">
                    <label for="username">Username: </label>
                    <input type="text" name="username" id="" placeholder="Username">
                </div>

                <div class="inputRow">
                    <label for="email">E-mail: </label>
                    <input type="text" name="email" id="" placeholder="E-mail">
                </div>


                <div class="inputRow">
                    <label for="phone">Phone nr: </label>
                    <input type="text" name="phone" id="" placeholder="Phone number">
                </div>


                <div class="inputRow">
                    <label for="company">Company name: </label>
                    <input type="text" name="company" id="" placeholder="Company name">
                </div>


                <!-- address -->
                <div class="inputRow">
                    <label for="address">Street: </label>
                    <input type="text" name="street" id="" placeholder="Street">
                </div>


                <div class="inputRow">
                    <label for="suite">Suite: </label>
                    <input type="text" name="suite" id="" placeholder="Suite">
                </div>


                <div class="inputRow">
                    <label for="city">City: </label>
                    <input type="text" name="city" id="" placeholder="City">
                </div>

                <div class="inputRow">
                    <label for="zipcode">Zipcode: </label>
                    <input type="text" name="zipcode" id="" placeholder="Zipcode">
                </div>



            </div>

            <!-- form fill errors  -->
            <div class="formError">
                <?php
                session_start();

                // check and print errors
                if (isset($_SESSION['form_error'])) {
                    $errors = $_SESSION['form_error'];
                    unset($_SESSION['form_error']);

                    foreach ($errors as $error) {
                        echo '<p>' . $error . '</p>';
                    }
                }
                ?>
            </div>


            <input type="submit" name="addUser" value="Add User">
        </form>

        <br />
        <!-- table contain users data -->
        <h3>User List</h3>
        
        <?php
            $controller = new UserController();
            $model = new UserModel($controller);
            $usersTable = $model->displayUsersTable();

            // print table with users
            echo $usersTable;

            // check is data sended and update
            if (isset($_POST['addUser'])) {
                $controller->receiveDataUserFromForm();
                unset($_POST['addUser']);

            }
        ?>

    </main>

    <script src="assets/js/main.js" type="module"></script>
</body>

</html>