<?php
session_start();

// DB接続情報
$dsn = "mysql:host={$host};dbname={$dbName};charset=utf8";

// DB接続
try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    exit('DB_CONNECT_ERROR: ' . $e->getMessage());
}

// セッションからメールアドレスを取得
$email = $_SESSION['email'];
$token = $_GET['token'];

// トークンを使ってユーザーのデータを取得
$sql = "SELECT * FROM gs_an_db WHERE token = :token";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':token', $token, PDO::PARAM_STR);
$stmt->execute();
$userData = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$userData) {
    exit('ユーザーが見つかりません。');
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="../../assets/styles/reset.css">
	<link rel="stylesheet" href="../../assets/styles/styles.css">
	<title>新規登録</title>
</head>
<body>
	
	<div class="form inner">
		<form action="./insert.php" method="post">
			<h2 class="title" data-heading="registran">新規登録</h2>
			<div class="question-item">
				<label>
					<span class="question-title">名前</span>
					<input type="text" name="name" required>
				</label>
				<label>
					<span class="question-title">メールアドレス</span>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($userData['email']); ?>" readonly>
				</label>
				<label>
					<span class="question-title">生年月日</span>
					<input type="text" name="birthday" required>
				</label>
				<label>
					<span class="question-title">電話番号</span>
					<input type="text" name="phone" required>
				</label>
			</div>
			<button type="submit" class="submit-button">登録</button>
		</form>
	</div>
</body>
</html>