<?php
require_once('dbconfig.php');

if (
    !isset($_POST['credits']) ||
    !isset($_POST['debits']) ||
    !isset($_POST['date']) ||
    !isset($_POST['note']) ||
    !isset($_POST['number']) ||
    !isset($_POST['total_c']) ||
    !isset($_POST['total_d']) ||
    $_POST['total_c'] !== $_POST['total_d']
) {
    echo json_encode(['success' => false, 'message' => "Bad request"]);
    exit;
}

$number = $_POST['number'];
$date = $_POST['date'];
$note = $_POST['note'];
$total_d = $_POST['total_d'];
$total_c = $_POST['total_c'];

try {
    $query = "INSERT INTO je_headers (JE_Number, JE_Date, JE_Note, JE_Totals_D, JE_Totals_C) 
                VALUES(?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);

    $stmt->bind_param("isiii", $number, $date, $note, $total_d, $total_c);
    $stmt->execute();

    $last_header_id = mysqli_insert_id($db);
} catch (\Exception $ex) {
    echo json_encode(['error' => $ex->getMessage()]);
    exit;
}

try {
    foreach ($_POST['credits'] as $account) {

        $query = "INSERT INTO je_lines (Account_ID, JE_ID, C_Amount) 
                    VALUES(?, ?, ?)";
        $stmt = $db->prepare($query);

        $Account_ID = $account['id'];
        $amount = $account['amount'];

        $stmt->bind_param("iii", $Account_ID, $last_header_id, $amount);
        $stmt->execute();
    }
} catch (\Exception $ex) {
    echo json_encode(['error' => $ex->getMessage()]);
    exit;
}

try {
    foreach ($_POST['debits'] as $account) {
        $query = "INSERT INTO je_lines (Account_ID, JE_ID, D_Amount) 
                    VALUES(?, ?, ?)";
        $stmt = $db->prepare($query);

        $Account_ID = $account['id'];
        $amount = $account['amount'];

        $stmt->bind_param("iii", $Account_ID, $last_header_id, $amount);
        $stmt->execute();
    }
    echo json_encode(['success' => true, 'message' => "Journal Entry number $number has been created successfully."]);
    exit;
} catch (\Exception $ex) {
    echo json_encode(['error' => $ex->getMessage()]);
    exit;
}
