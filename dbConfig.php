<?php

$db = mysqli_connect('localhost', 'root', '', 'journal_entry_db');

if (!$db) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
