<?php
ob_start(); // 출력 버퍼링 시작
session_start(); // 세션 시작

include 'config.php';

$error_popup = false;

// 로그인 버튼을 클릭했을 때 동작
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $학번 = $_POST['학번'];
    $비밀번호 = $_POST['비밀번호'];

    $query = "SELECT * FROM 학생 WHERE 학번='$학번' AND 비밀번호='$비밀번호'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['학번'] = $row['학번'];
        $_SESSION['이름'] = $row['이름']; // '이름'이라는 컬럼이 있다고 가정합니다.
        
        header('Location: index.php');
        exit(); // 리다이렉트 후 추가 코드 실행 방지
    } else {
        $error_popup = true;
    }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
    <style>
    @font-face {
        font-family: '나눔';
        src: url('fonts/NanumSquareR.ttf') format('truetype');
    }
    body{
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        height: 100vh;
        margin: 0;
    }
    .banner , .form{
        display: block;
        width: 100%
    }
    .banner {
        position: absolute;
        top: 0;
        background-color: #fc9;  // 배경색
        color: #fff;  // 텍스트 색상
        padding: 40px 0;  // 상하 패딩
        text-align: center;  // 중앙 정렬
        font-size: 24px;  // 글자 크기
        width: 100%;
        height: 100px;
        box-sizing: border-box;
        display: flex;
        align-items: center;
        justify-content: center;
    }
       body {
            font-family: '나눔', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
    font-family: '나눔', sans-serif;
    background-color: #ffffff;
    padding: 40px;
    border-radius: 8px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
    width: 400px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

input[type="text"], input[type="password"] {
    width: 80%;               
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 18px;
}

input[type="submit"] {
    font-family: '나눔', sans-serif;
    width: 80%;
    padding: 15px;
    font-size: 18px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    background-color: #333;
    color: #fff;
}
    </style>
    <script>
        function validateForm() {
            var 학번 = document.forms["loginForm"]["학번"].value;
            var 비밀번호 = document.forms["loginForm"]["비밀번호"].value;
            if (학번 == "" || 비밀번호 == "") {
                alert("학번과 비밀번호를 모두 입력해주세요.");
                return false;
            }
        }
    </script>
</head>
<body>
    <div class="banner">
        <h1>대학교 수강신청 시스템</h1>
    </div>
        <form name="loginForm" action="login.php" method="post" onsubmit="return validateForm()">
            <input type="text" id="학번" name="학번" placeholder="학번">
            <br><br>
            <input type="password" id="비밀번호" name="비밀번호" placeholder="패스워드">
            <br><br>
            <input type="submit" value="로그인">
            <a href="register.php" style="display: block; text-align: center; margin-top: 10px; padding: 10px; background-color: #4CAF50; color: white; border-radius: 4px; text-decoration: none;">회원가입</a>

        </form>


    <?php
        if ($error_popup) {
            echo "<script>alert('잘못된 학번 또는 비밀번호입니다.');</script>";
        }
        ob_end_flush(); // 출력 버퍼링 종료
    ?>
</body>
</html>
