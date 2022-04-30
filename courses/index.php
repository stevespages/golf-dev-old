<?php
/**
 * Courses
 */
session_start();
/*
* if they are not logged in redirect to home page.
*/
//if(!isset($_SESSION['user'])){
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

require "../php/db-functions.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['delete'])){
        $tableName = "uid".$_SESSION['uid']."courses";
        $sql = "DELETE FROM $tableName WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $_GET['delete']);
        $stmt->execute();
    }
}

require_once '../header.php';
?>
        <title>Golf</title>
    </head>
    <body>
<?php
$courseNames = getCourseNames($db);
?>
        <p><a href="../">Home</a></p>
        <h1>Courses</h1>
        <ul id='courses-ul'>
<?php
for ($i=0; $i < count($courseNames); $i++) {
    echo "<li class='course-name-lis'";
    echo " id='course-name{$courseNames[$i]['id']}'";
    echo " data-courses-id={$courseNames[$i]['id']}>";
    echo "{$courseNames[$i]['name']}";
    echo " {$courseNames[$i]['postcode']}";
    echo "</li>";
}
?>      
        </ul>
<script src='javascript/main.js'></script>
    </body>
</html>

