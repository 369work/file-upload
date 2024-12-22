<?php
/**
 * post process
 */
session_start();

 // POSTリクエストの確認
if  (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'uploadFile') {
    // POSTデータの取得と検証
    $data = array_map(function($value) {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }, $_POST);

    // CSRFトークンの検証
    if (!isset($data['csrf_token']) || !validateCsrfToken($data['csrf_token'])) {
        die('不正なリクエストです');
    }

    //アップロードを許可する拡張子
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'webp'];

    try {
        // ここでPOSTデータの処理を行う

        // Upload Dir
        $UpDir = __DIR__ . '/uploads/';
        // ディレクトリが存在しない場合は作成
        if (!file_exists($UpDir)) {
            mkdir($UpDir, 0777, true);
        }


        if (
            !isset($_FILES['up-file']['error']) ||
            is_array($_FILES['up-file']['error'])
        ) {
            throw new RuntimeException('無効なパラメータです。');
        }

        //error check
        switch ($_FILES['up-file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('ファイルは送信されませんでした。');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('ファイルサイズの制限を超えました。');
            default:
                throw new RuntimeException('不明なエラー。');
        }

        // You should also check filesize here. (128MB = 134217728B )
        //PHPの設定ではなくサイトで5MBにしたなら5242880になる
        if ($_FILES['up-file']['size'] > 134217728) {
            throw new RuntimeException('ファイルサイズの制限を超えました。');
        }

        //拡張子チェック
        $finfo = pathinfo($_FILES['up-file']['name']);
        $ext = strtolower($finfo['extension']);
        if (in_array($ext, $allowedExtensions) === false) {
            throw new RuntimeException('ファイル形式が無効です。');
        }

        //ファイル名の変更をするとき
        //日本語ファイル名を日時に変えるならsha1_file($_FILES['up-file']['tmp_name'])をtime()などに
        $newFileName = sprintf( $UpDir . '%s.%s',
            sha1_file($_FILES['up-file']['tmp_name']),
            $ext
        );
        if (!move_uploaded_file( $_FILES['up-file']['tmp_name'], $newFileName)) {
            throw new RuntimeException('アップロードしたファイルの移動に失敗しました。');
        }

        // セッションに成功メッセージを保存
        $_SESSION['success_message'] = 'ファイルのアップロードが完了しました。';


        // 処理成功時のリダイレクト
        header('Location: success.php');
        exit;

    } catch (Exception $e) {
        // エラー処理
        $error = $e->getMessage();
    }
}

function validateCsrfToken($token) {
    return hash_equals($_SESSION['csrf_token'], $token);
}