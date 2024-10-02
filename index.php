<?php require_once 'register.php' ?>

<form action="register.php" method="post">
    <label for="name">Nome:</label>
    <input type="text" id="name" name="name"><br><br>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email"><br><br>
    <label for="sector_name">Setor:</label>
    <select id="sector" name="sector_id"> <!-- required> -->
        <option value="">Selecione um setor</option>
        <?php
        // Populate the combobox with the sectors from the database
        foreach ($sectors as $sector) {
            echo "<option value='" . $sector['id'] . "'>" . $sector['name'] . "</option>";
        }
        ?>
    </select><br><br>
    <!-- <input type="text" id="sector_name" name="sector_name"><br><br> -->

    <label for="sector_cad">Novo setor:</label>
    <input type="text" id="sector_name" name="sector_name">
    <input type="submit" name="cad" value="Cadastrar Setor"><br><br>

    <input type="submit" name="reg" value="Registrar">
</form>
