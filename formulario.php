<!DOCTYPE html>
<html>
  <head>
    <title>Práctica PHP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="style.css" />
  </head>
<body>

 <!-- FORMULARIO DE ALTA -->
 <div>
    <form class="form create" action="formulario.php" method="POST">
      <h1>Formulario de Alta</h1>
      <div class="form-group">
        <label for="name">Nombre</label>
        <input
          type="text"
          class="form-control"
          id="name"
          name="nombre"
          pattern="[a-zA-Z ]+"
          required
        />
        <div class="invalid-feedback">Por favor ingrese un nombre valido.</div>
      </div>
      <div class="form-group">
        <label for="surname">Apellido</label>
        <input
          type="text"
          class="form-control"
          id="surname"
          name="apellido"
          pattern="[a-zA-Z ]+"
          required
        />
        <div class="invalid-feedback">
          Por favor ingrese un Apellido valido.
        </div>
      </div>
      <div class="form-group">
        <label for="email">E-mail</label>
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          required
        />
        <div class="invalid-feedback">Por favor ingrese un e-mail valido.</div>
      </div>
      <button class="btn btn-primary" type="submit" id="submit-button">
        ENVIAR
      </button>
    </form>
</div>

    <?php
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "cursoSQL";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Imposible establecer conexión con la base de datos:" . $conn->connect_error);
    }

    $name = $_POST["nombre"];
    $surname = $_POST["apellido"];
    $email = $_POST["email"];

    // Sanitize input
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $surname = filter_var($surname, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Validate input
    $errors = [];
    if (empty($name)) {
        $errors[] = "Por favor, ingrese su nombre";
    }
    if (empty($surname)) {
        $errors[] = "Por favor, ingrese su apellido";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Por favor, ingrese un email válido";
    }

    if (!empty($errors)) {
        echo '<div class="registration-error">';
        foreach ($errors as $error) {
            echo "<p>" . $error . "</p>";
        }
        echo '</div>';
    } else {
        // Insert data into DB
        $sql_insert = "INSERT INTO USUARIO (NOMBRE, APELLIDO, EMAIL) 
                       VALUES ('$name', '$surname', '$email')";

        if ($conn->query($sql_insert) === TRUE) {
            echo '<div class="registration-success">';
            echo "Los datos se han registrado con éxito para: \n";
            echo '<ul>';
            echo "<li>Nombre: " . $name . "</li>";
            echo "<li>Apellido: " . $surname . "</li>";
            echo "<li>Email: " . $email . "</li>";
            echo '</ul>';
            echo '</div>';
        } else {
            echo '<div class="registration-error">';
            echo "No se han podido registrar los datos de " . $name . " " . $surname . ". ";
            echo "Será redirigido al formulario en 5 segundos.";
            echo '</div>';
            header("refresh:5;url=index.html" );
        }
    }
    $conn->close();
}
?>
</body>
</html>