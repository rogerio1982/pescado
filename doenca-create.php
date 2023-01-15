<?php
// Include config file
require_once "config.php";
require_once "helpers.php";

// Define variables and initialize with empty values
$id_esp = "";
$imagem = "";
$nome = "";
$agente = "";
$sinais = "";
$prevenção = "";
$tratamento = "";

$id_esp_err = "";
$imagem_err = "";
$nome_err = "";
$agente_err = "";
$sinais_err = "";
$prevenção_err = "";
$tratamento_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_esp = trim($_POST["id_esp"]);
		$imagem = trim($_POST["imagem"]);
		$nome = trim($_POST["nome"]);
		$agente = trim($_POST["agente"]);
		$sinais = trim($_POST["sinais"]);
		$prevenção = trim($_POST["prevenção"]);
		$tratamento = trim($_POST["tratamento"]);
		

        $dsn = "mysql:host=$db_server;dbname=$db_name;charset=utf8mb4";
        $options = [
          PDO::ATTR_EMULATE_PREPARES   => false, // turn off emulation mode for "real" prepared statements
          PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, //turn on errors in the form of exceptions
          PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //make the default fetch be an associative array
        ];
        try {
          $pdo = new PDO($dsn, $db_user, $db_password, $options);
        } catch (Exception $e) {
          error_log($e->getMessage());
          exit('Something weird happened'); //something a user can understand
        }

        $vars = parse_columns('doenca', $_POST);
        $stmt = $pdo->prepare("INSERT INTO doenca (id_esp,imagem,nome,agente,sinais,prevenção,tratamento) VALUES (?,?,?,?,?,?,?)");

        if($stmt->execute([ $id_esp,$imagem,$nome,$agente,$sinais,$prevenção,$tratamento  ])) {
                $stmt = null;
                header("location: doenca-index.php");
            } else{
                echo "Tente novamente!";
            }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Criar</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Criar</h2>
                    </div>
                    <p></p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="form-group">
                                <label>id_espe</label>
                                    <select class="form-control" id="id_esp" name="id_esp">
                                    <?php
                                        $sql = "SELECT *,id FROM especie";
                                        $result = mysqli_query($link, $sql);
                                        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                            array_pop($row);
                                            $value = implode(" | ", $row);
                                            if ($row["id"] == $id_esp){
                                            echo '<option value="' . "$row[id]" . '"selected="selected">' . "$value" . '</option>';
                                            } else {
                                                echo '<option value="' . "$row[id]" . '">' . "$value" . '</option>';
                                        }
                                        }
                                    ?>
                                    </select>
                                <span class="form-text"><?php echo $id_esp_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>imagem</label>
                                <textarea name="imagem" class="form-control"><?php echo $imagem ; ?></textarea>
                                <span class="form-text"><?php echo $imagem_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>nome</label>
                                <textarea name="nome" class="form-control"><?php echo $nome ; ?></textarea>
                                <span class="form-text"><?php echo $nome_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>agente</label>
                                <textarea name="agente" class="form-control"><?php echo $agente ; ?></textarea>
                                <span class="form-text"><?php echo $agente_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>sinais</label>
                                <textarea name="sinais" class="form-control"><?php echo $sinais ; ?></textarea>
                                <span class="form-text"><?php echo $sinais_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>prevenção</label>
                                <textarea name="prevenção" class="form-control"><?php echo $prevenção ; ?></textarea>
                                <span class="form-text"><?php echo $prevenção_err; ?></span>
                            </div>
						<div class="form-group">
                                <label>tratamento</label>
                                <textarea name="tratamento" class="form-control"><?php echo $tratamento ; ?></textarea>
                                <span class="form-text"><?php echo $tratamento_err; ?></span>
                            </div>

                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="doenca-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>