<?php
define("db_SERVER", "localhost");
define("db_USER", "root");
define("db_PASSWORD", "");
define("db_to_drop", "apedb");

try {
    // Connection to the database
    $con = mysqli_connect(db_SERVER, db_USER, db_PASSWORD);
} catch (Exception $ex) {
    $error = "Error in Connection " . $ex->getMessage();
}

if (isset($_POST['create_database'])) {
    $sql = "CREATE DATABASE " . db_to_drop;

    try {
        $QueryResult = mysqli_query($con, $sql);
        if ($QueryResult) {
            $success = "Database Created";
        } else {
            // Get the error message
            $error = mysqli_error($con);

            // Check if the error indicates the database already exists
            if (strpos($error, "database exists") !== false) {
                $error = "Error: Database already exists";
            }
        }
    } catch (Exception $ex) {
        $error = "Error in creating the database: " . $ex->getMessage();
    }

    mysqli_close($con);
} elseif (isset($_POST['drop_database'])) {
    // SQL to drop the database
    $sql = "DROP DATABASE " . db_to_drop;

    try {
        $QueryResult = mysqli_query($con, $sql);
        if ($QueryResult) {
            $success = "Database " . db_to_drop . " dropped successfully.";
        } else {
            // Handle errors
            $error = mysqli_error($con);
            $error = "Error dropping database: " . $error;
        }
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
    }

    // Close the connection
    mysqli_close($con);
} elseif (isset($_POST['create_tables'])) {
    @mysqli_select_db($con, db_to_drop)
        or die("<p>The database is not available.</p>");

    $exec_success = true;

    // Create Table Members
    $sql1 = "CREATE table members (id int primary key auto_increment, name varchar(50), phonenumber int(20) , dob date ,package varchar(50), payment double, dor DATE DEFAULT (CURRENT_DATE) );";

    try {
        $QueryResult1 = mysqli_query($con, $sql1)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    // Create Table payments
    $sql2 = "CREATE TABLE payments(
    id INT AUTO_INCREMENT PRIMARY KEY,mid int ,name VARCHAR(50), package varchar(50), price double,
    payment double, remainingpayment double, dor DATE DEFAULT (CURRENT_DATE));";

    try {
        $QueryResult2 = mysqli_query($con, $sql2)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    // Create Table packages
    $sql3 = "CREATE TABLE packages(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50), price double);";

    try {
        $QueryResult3 = mysqli_query($con, $sql3)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    $sql4 = "INSERT INTO packages (name, price) VALUES    ('One Month', 150),('Six Months', 850),('Premium 1 Year',  1700),('Day Pass', 20), ('Solarium 80mins', 50),('Towel',3),('Physiotherapy',50), ('Cold Plunge Therapy',30),('Pool Access', 10);";

    try {
        $QueryResult4 = mysqli_query($con, $sql4)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    $sql5 = "CREATE TABLE ptpackages(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50), price double);";

    try {
        $QueryResult5 = mysqli_query($con, $sql5)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;

    }

    $sql6 = "INSERT INTO ptpackages (name, price) VALUES    ('Gold 10s', 400),('Gold 30s', 1100),('Platinum 10s', 600), ('Platinum 30s',1700);";

    try {
        $QueryResult6 = mysqli_query($con, $sql6)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }


    $sql7 = "CREATE TABLE trainers(id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(50));";

    try {
        $QueryResult7 = mysqli_query($con, $sql7)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;

    }

    $sql8 = "CREATE TABLE ptpayments(
        id INT AUTO_INCREMENT PRIMARY KEY, mid INT ,name VARCHAR(50), trainer VARCHAR(50), package varchar(50), price double,
        payment double, remainingpayment double, dor DATE DEFAULT (CURRENT_DATE));";

    try {
        $QueryResult8 = mysqli_query($con, $sql8)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    $sql9 = "CREATE TABLE potentials(
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50), phonenumber int(20), dor DATE DEFAULT (CURRENT_DATE));";

    try {
        $QueryResult9 = mysqli_query($con, $sql9)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }
    $sql10 = "CREATE TABLE classes(
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(50), instructor VARCHAR(50), day VARCHAR(10) NOT NULL CHECK (day IN 
        ('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')), time TIME NOT NULL, dor DATE DEFAULT (CURRENT_DATE));";

    try {
        $QueryResult10 = mysqli_query($con, $sql10)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    $sql11 = "CREATE TABLE financialreports(
        id INT AUTO_INCREMENT PRIMARY KEY,pid int, mid int, name VARCHAR(50), package varchar(50), price double, payment double, remainingpayment double, payment_type ENUM('ptpayment', 'payment'), dor DATE DEFAULT (CURRENT_DATE));";

    try {
        $QueryResult11 = mysqli_query($con, $sql11)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }
    $sql12 = "CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY,username VARCHAR(50) NOT NULL UNIQUE,password_hash VARCHAR(255) NOT NULL,role ENUM('user', 'admin') DEFAULT 'user',created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,is_active BOOLEAN DEFAULT TRUE);";

    try {
        $QueryResult12 = mysqli_query($con, $sql12)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }

    $sql13 = "INSERT INTO trainers (name) VALUES ('Master Trainer Mohammad');";

    try {
        $QueryResult13 = mysqli_query($con, $sql13)
            or die("Unable to execute the query" . mysqli_error($con));
    } catch (Exception $ex) {
        $error = "Exception caught: " . $ex->getMessage();
        $exec_success = false;
    }



    if ($exec_success)
        $success = "Tables was created successfully";

}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Database Setting</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-green-700">
    <div class="h-screen flex flex-col justify-center gap-5 items-center">
        <?php if (isset($success)) { ?>
            <div class="w-full text-center text-3xl">
                <?php echo $success; ?>
            </div>
        <?php } ?>
        <div class="flex justify-center flex-wrap gap-5 items-center">
            <?php if (empty($error)) { ?>
                <div>
                    <form action="database-setting.php" method="post">
                        <input type="hidden" name="create_database">
                        <button
                            class="bg-green-700 border border-green-700 text-black hover:bg-black hover:text-green-700 px-5 py-2 rounded">Create
                            Database</button>
                    </form>
                </div>
                <div>
                    <form action="database-setting.php" method="post">
                        <input type="hidden" name="drop_database">
                        <button
                            class="bg-green-700 border border-green-700 text-black hover:bg-black hover:text-green-700 px-5 py-2 rounded">Drop
                            Database</button>
                    </form>
                </div>
                <div>
                    <form action="database-setting.php" method="post">
                        <input type="hidden" name="create_tables">
                        <button
                            class="bg-green-700 border border-green-700 text-black hover:bg-black hover:text-green-700 px-5 py-2 rounded">Create
                            Tables</button>
                    </form>
                </div>
            <?php } else { ?>
                <div class="px-10 text-center">
                    <h1 class="text-7xl text-left"><?php echo $error; ?></h1>
                    <a href="database-setting.php" class="text-3xl mt-5 underline hover:text-green-800">Back</a>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>