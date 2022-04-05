<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Latest compiled and minified Bootstrap CSS -->

    <!-- custom css -->
    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>
    <!-- container -->
    <div class="container">
        <div class="page-header">
            <h1>Update Customer</h1>
        </div>
        <!-- PHP read record by ID will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $user_name = isset($_GET['username']) ? $_GET['username'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'database/connection.php';
        include 'database/function.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT * FROM customer WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $user_name);
            // execute our query
            $stmt->execute();
            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            // values to fill up our form
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $firstname = $row['firstname'];
            $lastname = $row['lastname'];
            $gender = $row['gender'];
            $birthdate = $row['birthdate'];
            //$inputconfirmPassword = $row['inputconfirmPassword'];
            $status = $row['status'];
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
        <!-- HTML form to update record will be here -->
        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?username={$username}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><textarea type='text' name='email' class='form-control'><?php echo htmlspecialchars($email, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><textarea name='password' class='form-control'><?php echo htmlspecialchars($password, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Firstname</td>
                    <td><textarea name='firstname' class='form-control'><?php echo htmlspecialchars($firstname, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>lastname</td>
                    <td><textarea name='lastname' class='form-control'><?php echo htmlspecialchars($lastname, ENT_QUOTES);  ?></textarea></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><input class="form-check-input" type="radio" name="gender" id="gender" <?php if ($gender == "male") echo 'checked' ?> value="male">
                        <label class="form-check-label" for="gender">
                            Male
                        </label>
                        <input class="form-check-input" type="radio" name="gender" id="gender" <?php if ($gender == "female") echo 'checked' ?>value="female">
                        <label class="form-check-label" for="gender">
                            Female
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>Account Status</td>
                    <td><input class="form-check-input" type="radio" name="status" id="status" <?php if ($status == "active") echo 'checked' ?> value="active">
                        <label class="form-check-label" for="status">
                            Active
                        </label>
                        <input class="form-check-input" type="radio" name="status" id="status" <?php if ($status == "disabled") echo 'checked' ?>value="disabled">
                        <label class="form-check-label" for="status">
                            Disabled
                        </label>
                    </td>
                </tr>

                <tr>
                    <td>Date Of Birth</td>
                    <td>
                        <?php


                        echo '<select id="day" name="day">' . "\n";
                        for ($i_day = 1; $i_day <= 31; $i_day++) {
                            $selected = ($birthdate == $i_day ? ' selected' : '');
                            echo '<option value="' . $i_day . '"' . $selected . '>' . $i_day . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                        ?>

                        <!--month-->
                        <?php


                        echo '<select id="month" name="month">' . "\n";
                        for ($i_month = 1; $i_month <= 12; $i_month++) {
                            $selected = ($birthdate == $i_month ? ' selected' : '');
                            echo '<option value="' . $i_month . '"' . $selected . '>' . date('F', mktime(0, 0, 0, $i_month)) . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                        ?>

                        <!--year-->
                        <?php
                        $year_start  = 2022;

                        $birthdate = 2022;

                        echo '<select id="year" name="year">' . "\n";
                        for ($i_year = $year_start; $i_year >= 1990; $i_year--) {
                            $selected = ($birthdate == $i_year ? ' selected' : '');
                            echo '<option value="' . $i_year . '"' . $selected . '>' . $i_year . '</option>' . "\n";
                        }
                        echo '</select>' . "\n";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='customer_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>

        <!-- PHP post to update record will be here -->
        <?php
        $username = $firstname = $lastname = $password = $inputconfirmPassword = $birthdate = $gender = $status = $starsign = $email = "";
        // check if form was submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // posted values 
            $username = htmlspecialchars(strip_tags($_POST['username']));
            $email = htmlspecialchars(strip_tags($_POST['email']));
            $password = htmlspecialchars(strip_tags($_POST['password']));
            //$inputconfirmPassword = htmlspecialchars(strip_tags($_POST['inputconfirmPassword']));
            $firstname = htmlspecialchars(strip_tags($_POST['firstname']));
            $lastname = htmlspecialchars(strip_tags($_POST['lastname']));
            $gender = htmlspecialchars(strip_tags($_POST['gender']));
            //$birthdate = htmlspecialchars(strip_tags($_POST['birthdate']));

            $error['username'] = validateUsername($username); //array call function
            $error['password'] = validatePassword($password, $inputconfirmPassword);
            $error['birthdate'] = validateAge($year, $birthdate);
            $error = array_filter($error);

            if (empty($error)) {
                try {
                    // write update query
                    $query = "UPDATE customer SET username=:username, email=:email, password=:password, firstname=:firstname, lastname=:lastname, gender=:gender, birthdate=:birthdate WHERE username = :username";
                    // prepare query for excecution
                    $stmt = $con->prepare($query);

                    // bind the parameters
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':firstname', $firstname);
                    $stmt->bindParam(':lastname', $lastname);
                    $stmt->bindParam(':gender', $gender);
                    $stmt->bindParam(':birthdate', $birthdate);

                    // Execute the query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to update record. Please try again.</div>";
                    }
                }
                // show errors
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                foreach ($error as $value) {
                    echo "<div class='alert alert-danger'>$value <br/></div>"; //start print error msg
                }
            }
        }
        ?>


    </div>
    <!-- end .container -->
</body>

</html>