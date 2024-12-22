<?php
session_start();

// セッションに成功メッセージがない場合はホームページにリダイレクト
if (!isset($_SESSION['success_message'])) {
    header('Location: index.php');
    exit();
}

// 成功メッセージを取得して、セッションから削除
$success_message = $_SESSION['success_message'];
unset($_SESSION['success_message']);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>処理完了</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-16">
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
            <!-- 成功アイコン -->
            <div class="flex justify-center mb-6">
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            <h1 class="text-3xl font-bold text-center text-gray-800 mb-4">処理が完了しました</h1>

            <div class="text-center mb-8">
                <p class="text-gray-600">
                    <?php echo htmlspecialchars($success_message); ?>
                </p>
            </div>

            <div class="text-center">
                <a href="index.php"
                   class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-lg transition duration-300">
                    トップページに戻る
                </a>
            </div>
        </div>
    </div>
</body>
</html>
