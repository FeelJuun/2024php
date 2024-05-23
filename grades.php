<?php
session_start();
include 'config.php';

if (!isset($_SESSION['학번'])) {
    header('Location: login.php');
    exit();
}

$학번 = $_SESSION['학번'];

$query = "SELECT 과목.과목명, 성적.학점
          FROM 성적
          INNER JOIN 과목정보 ON 성적.과목정보번호 = 과목정보.과목정보번호
          INNER JOIN 과목 ON 과목정보.과목번호 = 과목.과목번호
          WHERE 성적.학번 = '$학번'";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="ko">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내 성적</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container">
    <div class="py-5 text-center">
        <h2>내 성적</h2>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h4 class="mb-3">성적 목록</h4>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>과목명</th>
                    <th>학점</th>
                </tr>
                </thead>
                <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['과목명'] ?></td>
                        <td><?= $row['학점'] ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<div class="button-container">
    <a href="index.php" class="return-dashboard-btn">메인페이지로 돌아가기</a>
</div>
</body>
</html>
