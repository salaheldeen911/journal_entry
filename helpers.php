<?php

$errors = array();

function getAllMasterAccounts()
{
    global $db;
    $query = "SELECT * FROM master_accounts";

    $stm = mysqli_query($db, $query);
    $result = $stm->fetch_all(MYSQLI_ASSOC);
    return $result;
}

