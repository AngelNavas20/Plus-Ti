<?php
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $domain = substr(strrchr($email, "@"), 1);
    
    if (checkdnsrr($domain, "MX")) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
}
?>
