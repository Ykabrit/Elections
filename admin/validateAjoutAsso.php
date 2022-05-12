
      <?php

      session_start();
      require_once("bdd_config.php");

      if (isset($_POST["nom-assoc1"])) {

        $nomAsso = '';
        $pic = '';
        $description = '';

        $error_nomAsso = '';
        $error_pic = '';
        $error_discription = '';
        $error_bdd = '';

        if (empty($_POST["nom-assoc1"])) {
            $error_nomAsso = 'Veuillez entrer le nom de l\'association ';
        }else{
          if (strlen($_POST['nom-assoc1']) < 2) {
            $error_nomAsso = 'au moins 2 lettres';
          }else {
            $nomAsso = $_POST["nom-assoc1"];
          }

        }

        if (empty($_POST["pic"])) {
            $error_pic = 'Veuillez choisir un image';
        }else{
            $pic = $_POST["pic"];
        }

        if (empty($_POST["description1"])) {
            $error_discription = 'Veuillez entrer une description';
        }else{
          if (strlen($_POST['description1']) < 20) {
            $error_discription = 'au moins 20 lettres';
          }else {
            $description = $_POST["description1"];
          }
        }

          if(!checkAsso($connection, $nomAsso)) {
            $error_bdd = 'Cette association existe déjà !';
          }

          if ($error_bdd == '' && $error_nomAsso == '' && $error_discription == '' && $error_pic == '') {

              $id = '1';
              addAsso($connection, $id, $nomAsso, $pic, $discription);

              $data = array('success' => true);

          } else {

            $data = array(

              'error_nomAsso' => $error_nomAsso,
              'error_pic' => $error_pic,
              'error_discription' => $error_discription,
              'error_bdd' => $error_bdd
            );
          }
          echo json_encode($data);
        }


        function checkAsso($connection, $username) {
           $query = "SELECT COUNT(*) AS count FROM associations WHERE nom=:username";
           $statement = $connection->prepare($query);
           $statement->bindValue(":username", $username, PDO::PARAM_STR);
           $statement->execute();
           $row = $statement->fetch(PDO::FETCH_ASSOC);
           return $row["count"] == "0";
         }

         function addAsso($connection, $id, $nom, $url, $discription) {
           $query = "INSERT INTO associations (id, nom, url, description) VALUES (:id, :nom, :url, :description)";
           $statement = $connection->prepare($query);
           $statement->bindValue(":id", $id, PDO::PARAM_STR);
           $statement->bindValue(":nom", $nom, PDO::PARAM_STR);
           $statement->bindValue(":url", $url, PDO::PARAM_STR);
           $statement->bindValue(":discription", $discription, PDO::PARAM_STR);
           $Ok = $statement->execute();
         }

       ?>