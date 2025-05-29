<?php
//handle unwanted characters or attacks in the post method
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST["unsub_email"]);
    if(empty($email)){

    }
    else {
        if(store_dnc(trim($email))) {
            notify_email($email);
            header('Location: unsubscribe_success.html');
        }
        else {
            echo " Could not unsubscribe ";
        }
    }
}

function notify_email($email) {
    $to = "awhite@immensetec.com";
    $subject = "Prospect unsubscribed";
    
    $message = "
    <html>
    <head>
    <title>Prospect unsubscribed</title>
    </head>
    <body>
    <h3>The below prospect has chosen to unsubscribe</h3>
    <h1>" . $email . "</h1>
    </body>
    </html>
    ";
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
    // More headers
    $headers .= 'From: noreply@immensetec.com' . "\r\n";
    
    mail($to,$subject,$message,$headers);
}

function store_dnc($email) {
    $servername = "localhost";
    $username = "immehubw_root";
    $password = "YfkO)rlpna+*";
    $dbname = "immehubw_profiling";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if(!$conn) {
        die("connection failed: " . mysqli_connect_error());

        exit();
    }

    $sql = "INSERT INTO `unsub_list`(`unsub_email`) VALUES ('" . $email . "');";

    if (mysqli_query($conn, $sql)) {
        $empemail_sql = "UPDATE `empemail` SET `EmpEmailStatus`='DNC' WHERE `EmpEmail` = '" . $email . "';";
        mysqli_query($conn, $empemail_sql);
        return true;
      } else {
        return false;
      }
}

?>
