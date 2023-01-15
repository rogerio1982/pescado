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
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    $id_esp = trim($_POST["id_esp"]);
		$imagem = trim($_POST["imagem"]);
		$nome = trim($_POST["nome"]);
		$agente = trim($_POST["agente"]);
		$sinais = trim($_POST["sinais"]);
		$prevenção = trim($_POST["prevenção"]);
		$tratamento = trim($_POST["tratamento"]);
		

    // Prepare an update statement
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
        exit('Something weird happened');
    }

    $vars = parse_columns('doenca', $_POST);
    $stmt = $pdo->prepare("UPDATE doenca SET id_esp=?,imagem=?,nome=?,agente=?,sinais=?,prevenção=?,tratamento=? WHERE id=?");

    if(!$stmt->execute([ $id_esp,$imagem,$nome,$agente,$sinais,$prevenção,$tratamento,$id  ])) {
        echo "Something went wrong. Please try again later.";
        header("location: error.php");
    } else {
        $stmt = null;
        header("location: doenca-read.php?id=$id");
    }
} else {
    // Check existence of id parameter before processing further
	$_GET["id"] = trim($_GET["id"]);
    if(isset($_GET["id"]) && !empty($_GET["id"])){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM doenca WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Set parameters
            $param_id = $id;

            // Bind variables to the prepared statement as parameters
			if (is_int($param_id)) $__vartype = "i";
			elseif (is_string($param_id)) $__vartype = "s";
			elseif (is_numeric($param_id)) $__vartype = "d";
			else $__vartype = "b"; // blob
			mysqli_stmt_bind_param($stmt, $__vartype, $param_id);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value

                    $id_esp = $row["id_esp"];
					$imagem = $row["imagem"];
					$nome = $row["nome"];
					$agente = $row["agente"];
					$sinais = $row["sinais"];
					$prevenção = $row["prevenção"];
					$tratamento = $row["tratamento"];
					

                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }

            } else{
                echo "Oops! Something went wrong. Please try again later.<br>".$stmt->error;
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);

    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<?php require_once('navbar.php'); ?>
<body>
    <section class="pt-5">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 mx-auto">
                    <div class="page-header">
                        <h2>Atualizar</h2>
                    </div>
                    <p></p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                        <div class="form-group">
                                <label>id_esp</label>
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

                        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Enviar">
                        <a href="doenca-index.php" class="btn btn-secondary">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
