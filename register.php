<?php
// register.php

// Include the database connection script
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['cad'])){
        $sector_name = $_POST['sector_name'];
        // Insert the sector data into the sectors table
        $sector_query = "INSERT INTO sectors (name) VALUES (:sector_name)";
        $stmt = $conn->prepare($sector_query);
        $stmt->bindParam(':sector_name', $sector_name);
        $stmt->execute();

        echo "<a href='index.php'> Voltar </a>";
        echo "<br>";
        echo "<br>";
    }else{
        // Get the user and sector data from the form
        $name = $_POST['name'];
        $email = $_POST['email'];
        $sector_id = $_POST['sector_id']; // Now, sector ID comes from the combobox

        // Check if the user already exists in the database based on the email
        $user_check_query = "SELECT id FROM users WHERE email = :email";
        $stmt = $conn->prepare($user_check_query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existing_user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing_user) {
            // If the user already exists, use their existing ID
            $user_id = $existing_user['id'];
        } else {
            // Insert the user data into the users table if they don't exist
            $user_query = "INSERT INTO users (name, email) VALUES (:name, :email)";
            $stmt = $conn->prepare($user_query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            // Get the last inserted user ID
            $user_id = $conn->lastInsertId();
        }

        // Check if the user is already associated with the sector
        $pivot_check_query = "SELECT * FROM user_sectors WHERE user_id = :user_id AND sector_id = :sector_id";
        $stmt = $conn->prepare($pivot_check_query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':sector_id', $sector_id);
        $stmt->execute();
        $existing_pivot = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$existing_pivot) {
            // If not already associated, insert into the pivot table
            $pivot_query = "INSERT INTO user_sectors (user_id, sector_id) VALUES (:user_id, :sector_id)";
            $stmt = $conn->prepare($pivot_query);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':sector_id', $sector_id);
            $stmt->execute();

            // Display a success message
            echo "Usuário e setor cadastrados/associados com sucesso!";
        } else {
            echo "Usuário já está associado a este setor.";
        }
        
    }
    
}

    // Retrieve and display the registered users and their associated sectors
    $query = "SELECT u.name, u.email, s.name as sector_name 
            FROM users u 
            INNER JOIN user_sectors us ON u.id = us.user_id 
            INNER JOIN sectors s ON us.sector_id = s.id";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($result) > 0) {
        echo "<h2>Usuários e setores registrados:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Name</th><th>Email</th><th>Sector</th></tr>";
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . $row['email'] . "</td>";
            echo "<td>" . $row['sector_name'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
        echo "<hr>";
        echo "<br>";

        echo "<a href='index.php'> Voltar </a>";
        echo "<br>";
        echo "<br>";

    } else {
        echo "Não há registros de usuários de setores ainda.";
        echo "<br>";
        echo "<br>";
    }

    // Retrieve the list of sectors for the combobox
    $sector_query = "SELECT id, name FROM sectors";
    $stmt = $conn->prepare($sector_query);
    $stmt->execute();
    $sectors = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the registration form

?>
