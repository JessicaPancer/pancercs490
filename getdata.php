<?php
    $mysqli = new mysqli('localhost', 'root', 'natalie123', 'studyabroad');
    if (!$mysqli)
    {
        die('Could not connect: ' . mysqli_error($mysqli));
    }
    //bind to $name
    if ($stmt = $mysqli->prepare("SELECT semester.region FROM semester")) {
        $stmt->bind_result($name);
        $OK = $stmt->execute();
    }
    //put all of the resulting names into a PHP array
    $result_array = Array();
    while($stmt->fetch()) {
        $result_array[] = $name;
    }
    //convert the PHP array into JSON format, so it works with javascript
    $json_array = json_encode($result_array);
    // header('Content-Type: application/json');
    // echo $json_array;

    $thing = 10;
    echo $thing;
?>