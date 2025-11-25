<?php
include('connect.php');


$query = "SELECT Therapist_Id, password FROM therapist"; 
$result = mysqli_query($db, $query);

if (mysqli_num_rows($result) > 0) {
    while ($user = mysqli_fetch_assoc($result)) {
        $id = $user['Therapist_Id'];
        $plainPassword = $user['password'];

        $hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

        
        mysqli_query($db, "UPDATE therapist SET password='$hashedPassword' WHERE Therapist_Id=$id");
    }
    echo "All passwords have been hashed successfully!";
} else {
    echo "No users found in the database.";
}
?>