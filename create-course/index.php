<?php
/**
 * Create Course
 */
session_start();
/*
* if they are not logged in redirect to home page.
*/
if (empty($_SESSION['user'])) {
    header('Location: ../');
    exit();
}

/*
 * Error reporting
 * Comment this out in production
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="stylesheet" href="../css/main.css">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Golf</title>
        <style>
        input[type=number] {
            width: 30px;
        }
        </style>
    </head>
    <body>
<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include "../php/db-functions.php";
    /**
     * Inserts a course as a row in uid123courses table
     * 
     * @param object $db   PDO Object
     * @param array  $post The $_POST array from form submission
     * 
     * @return bool
     */
    function insertCourse($db, $post)
    {
        $tableName = "uid".$_SESSION["uid"]."courses";

        // This should be altered to use named placeholders eg :h2par
        $sql = "INSERT INTO $tableName (name, postcode, ";
        $sql .= "h1par, h1si, h2par, h2si, h3par, h3si, h4par, h4si, ";
        $sql .= "h5par, h5si, h6par, h6si, h7par, h7si, h8par, h8si, ";
        $sql .= "h9par, h9si, h10par, h10si, h11par, h11si, h12par, h12si, ";
        $sql .= "h13par, h13si, h14par, h14si, h15par, h15si, h16par, h16si, ";
        $sql .= "h17par, h17si, h18par, h18si) ";
        $sql .= "VALUES ('{$post['name']}', '{$post['postcode']}', ";
        $sql .= "'{$post['h1par']}', '{$post['h1si']}', ";
        $sql .= "'{$post['h2par']}', '{$post['h2si']}', ";
        $sql .= "'{$post['h3par']}', '{$post['h3si']}', ";
        $sql .= "'{$post['h4par']}', '{$post['h4si']}', ";
        $sql .= "'{$post['h5par']}', '{$post['h5si']}', ";
        $sql .= "'{$post['h6par']}', '{$post['h6si']}', ";
        $sql .= "'{$post['h7par']}', '{$post['h7si']}', ";
        $sql .= "'{$post['h8par']}', '{$post['h8si']}', ";
        $sql .= "'{$post['h9par']}', '{$post['h9si']}', ";
        $sql .= "'{$post['h10par']}', '{$post['h10si']}', ";
        $sql .= "'{$post['h11par']}', '{$post['h11si']}', ";
        $sql .= "'{$post['h12par']}', '{$post['h12si']}', ";
        $sql .= "'{$post['h13par']}', '{$post['h13si']}', ";
        $sql .= "'{$post['h14par']}', '{$post['h14si']}', ";
        $sql .= "'{$post['h15par']}', '{$post['h15si']}', ";
        $sql .= "'{$post['h16par']}', '{$post['h16si']}', ";
        $sql .= "'{$post['h17par']}', '{$post['h17si']}', ";
        $sql .= "'{$post['h18par']}', '{$post['h18si']}')"; 

        $stmt = $db->prepare($sql);
        $stmt->execute();
    }
    insertCourse($db, $_POST);
    header("Location: ../");
    exit;
}
?>
        <p><a href="../">Home</a></p>
        <h1>Create Course</h1>
        <form method="post">
            <label for="name">Name</label>
            <input type="text" id="name" name="name">
            <label for="postcode">postcode</label>
            <input type="text" id="postcode" name="postcode">

            <fieldset>
                <label for="h1par">h1par</label>
                <input type="number" id="h1par" step="1" name="h1par"> 
                <label for="h1si">h1si</label>
                <input type="number" id="h1si" step="1" name="h1si">
            </fieldset>

            <fieldset>
                <label for="h2par">h2par</label>
                <input type="number" id="h2par" step="1" name="h2par"> 
                <label for="h2si">h2si</label>
                <input type="number" id="h2si" step="1" name="h2si">
            </fieldset>

            <fieldset>
                <label for="h3par">h3par</label>
                <input type="number" id="h3par" step="1" name="h3par"> 
                <label for="h3si">h3si</label>
                <input type="number" id="h3si" step="1" name="h3si">
            </fieldset>

            <fieldset>
                <label for="h4par">h4par</label>
                <input type="number" id="h4par" step="1" name="h4par"> 
                <label for="h4si">h4si</label>
                <input type="number" id="h4si" step="1" name="h4si">
            </fieldset>

            <fieldset>
                <label for="h5par">h5par</label>
                <input type="number" id="h5par" step="1" name="h5par"> 
                <label for="h5si">h5si</label>
                <input type="number" id="h5si" step="1" name="h5si">
            </fieldset>

            <fieldset>
                <label for="h6par">h6par</label>
                <input type="number" id="h6par" step="1" name="h6par"> 
                <label for="h6si">h6si</label>
                <input type="number" id="h6si" step="1" name="h6si">
            </fieldset>

            <fieldset>
                <label for="h7par">h7par</label>
                <input type="number" id="h7par" step="1" name="h7par"> 
                <label for="h7si">h7si</label>
                <input type="number" id="h7si" step="1" name="h7si">
            </fieldset>

            <fieldset>
                <label for="h8par">h8par</label>
                <input type="number" id="h8par" step="1" name="h8par"> 
                <label for="h8si">h8si</label>
                <input type="number" id="h8si" step="1" name="h8si">
            </fieldset>

            <fieldset>
                <label for="h9par">h9par</label>
                <input type="number" id="h9par" step="1" name="h9par"> 
                <label for="h9si">h9si</label>
                <input type="number" id="h9si" step="1" name="h9si">
            </fieldset>

            <fieldset>
                <label for="h10par">h10par</label>
                <input type="number" id="h10par" step="1" name="h10par"> 
                <label for="h10si">h10si</label>
                <input type="number" id="h10si" step="1" name="h10si">
            </fieldset>

            <fieldset>
                <label for="h11par">h11par</label>
                <input type="number" id="h11par" step="1" name="h11par"> 
                <label for="h11si">h11si</label>
                <input type="number" id="h11si" step="1" name="h11si">
            </fieldset>

            <fieldset>
                <label for="h12par">h12par</label>
                <input type="number" id="h12par" step="1" name="h12par"> 
                <label for="h12si">h12si</label>
                <input type="number" id="h12si" step="1" name="h12si">
            </fieldset>

            <fieldset>
                <label for="h13par">h13par</label>
                <input type="number" id="h13par" step="1" name="h13par"> 
                <label for="h13si">h13si</label>
                <input type="number" id="h13si" step="1" name="h13si">
            </fieldset>

            <fieldset>
                <label for="h14par">h14par</label>
                <input type="number" id="h14par" step="1" name="h14par"> 
                <label for="h14si">h14si</label>
                <input type="number" id="h14si" step="1" name="h14si">
            </fieldset>

            <fieldset>
                <label for="h15par">h15par</label>
                <input type="number" id="h15par" step="1" name="h15par"> 
                <label for="h15si">h15si</label>
                <input type="number" id="h15si" step="1" name="h15si">
            </fieldset>

            <fieldset>
                <label for="h16par">h16par</label>
                <input type="number" id="h16par" step="1" name="h16par"> 
                <label for="h16si">h16si</label>
                <input type="number" id="h16si" step="1" name="h16si">
            </fieldset>

            <fieldset>
                <label for="h17par">h17par</label>
                <input type="number" id="h17par" step="1" name="h17par"> 
                <label for="h17si">h17si</label>
                <input type="number" id="h17si" step="1" name="h17si">
            </fieldset>

            <fieldset>
                <label for="h18par">h18par</label>
                <input type="number" id="h18par" step="1" name="h18par"> 
                <label for="h18si">h18si</label>
                <input type="number" id="h18si" step="1" name="h18si">
            </fieldset>

            <input type="submit" name="submit">
        </form>
  </body>
</html>
