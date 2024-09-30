<?php
    // Código PHP para atualizar e registrar os dados do ESP32 na tabela
    require 'database.php';
  
    // Condição para checar se o POST não está vazio
    if (!empty($_POST)) {
        // Substituir os valores
        $id = $_POST['id'];
        $celula = $_POST['celula'];
        $bssid = $_POST['bssid'];
        $rssi = $_POST['rssi'];

        //Obter o tempo e a data
        #date_default_timezone_set("America/Sao-Paulo");
        #$tm = date("H:i:s");
        #$dt = date("Y-m-d");
        
        // Atualiza os dados na tabela
        $pdo = Database::connect();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_update'.
        // This table is used to store DHT11 sensor data updated by ESP32. 
        // This table is also used to store the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
        // This table is operated with the "UPDATE" command, so this table will only contain one row.
        $sql = "UPDATE redes SET id = ?, celula = ?, bssid = ?, time = ?, date = ? WHERE id = ?";
        $q = $pdo->prepare($sql);
        $q->execute(array($celula,$bssid,$rssi,$id));
        Database::disconnect();
        //........................................ 
        
        //........................................ Entering data into a table.
        $id_key;
        $board = $_POST['id'];
        $found_empty = false;
        
        $pdo = Database::connect();
        
        //:::::::: Process to check if "id" is already in use.
        while ($found_empty == false) {
        $id_key = generate_string_id(10);
        // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_record'.
        // This table is used to store and record DHT11 sensor data updated by ESP32. 
        // This table is also used to store and record the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
        // This table is operated with the "INSERT" command, so this table will contain many rows.
        // Before saving and recording data in this table, the "id" will be checked first, to ensure that the "id" that has been created has not been used in the table.
        $sql = 'SELECT * FROM esp32 WHERE id="' . $id_key . '"';
        $q = $pdo->prepare($sql);
        $q->execute();
        
        if (!$data = $q->fetch()) {
            $found_empty = true;
        }
        }
        //::::::::
        
        //:::::::: The process of entering data into a table.
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // replace_with_your_table_name, on this project I use the table name 'esp32_table_dht11_leds_record'.
        // This table is used to store and record DHT11 sensor data updated by ESP32. 
        // This table is also used to store and record the state of the LEDs, the state of the LEDs is controlled from the "home.php" page. 
        // This table is operated with the "INSERT" command, so this table will contain many rows.
            $sql = "INSERT INTO esp32 (id,board,celula,bssid,rssi) values(?, ?, ?, ?, ?)";
            $q = $pdo->prepare($sql);
            $q->execute(array($id_key,$board,$celula,$bssid,$rssi));
        //::::::::
        
        Database::disconnect();
        //........................................ 
    }
    //---------------------------------------- 
  
    //---------------------------------------- Function to create "id" based on numbers and characters.
    function generate_string_id($strength = 16) {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $input_length = strlen($permitted_chars);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
        $random_string .= $random_character;
        }
        return $random_string;
    }
  //---------------------------------------- 
?>