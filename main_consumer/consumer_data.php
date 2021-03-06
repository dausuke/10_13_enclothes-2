<?php
session_start();
ini_set('display_errors', 1);

include("../functions.php");

$uid = $_SESSION['uid'];
$pdo = connect_to_db();

// データ取得SQL作成
// $sql = 'SELECT * FROM consumer WHERE id=:uid';
$sql = 'SELECT * FROM consumer LEFT OUTER JOIN (SELECT consumer_id,COUNT(id) AS cnt FROM question GROUP BY consumer_id ) AS consumer_state ON consumer.id = consumer_state.consumer_id';

// SQL準備&実行
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':uid', $uid, PDO::PARAM_STR);
$status = $stmt->execute();

// データ登録処理後
if ($status == false) {
    // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
    $error = $stmt->errorInfo();
    echo json_encode(["error_msg" => "{$error[2]}"]);
    exit();
} else {
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($result, JSON_UNESCAPED_UNICODE);
}
