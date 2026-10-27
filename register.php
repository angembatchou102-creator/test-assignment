
<?php
session_start();
include 'connect.php';

// REGISTRATION
if (isset($_POST['signup'])) {

    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO users (firstName, lastName, email, password)
             VALUES (?, ?, ?, ?)"
        );
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $password);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            echo "Registration failed.";
        }
    }

    $stmt->close();
}

// LOGIN
if (isset($_POST['signIn'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT email, password FROM users WHERE email = ?"
    );
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            $_SESSION['email'] = $row['email'];

            header("Location: homepage.php");
            exit();
        } else {
            echo "Incorrect password.";
        }
    } else {
        echo "Email not found.";
    }

    $stmt->close();
}
?>
