<?php
// 세션 시작
session_start();

// 로그인 되어 있지 않으면 로그인 페이지로 리다이렉트
if(!isset($_SESSION['학번'])) {
    header("Location: login.php");
    exit();
}

// 사용자 정보는 세션에서 가져옴
$user_name = $_SESSION['이름'];

// config.php 포함 (데이터베이스 연결 정보가 포함된 파일이라고 가정합니다.)
include 'config.php';

// 학번을 세션에서 가져옵니다.
$학번 = $_SESSION['학번'];

$query = "SELECT * FROM 학생 WHERE 학번 = '$학번'";
$result = $conn->query($query);

// 학생 데이터를 가져오는 쿼리
$query1 = "SELECT 전공번호 FROM 학생 WHERE 학번 = '$학번'";
$result = $conn->query($query1);
$row = $result->fetch_assoc();
$전공번호 = $row['전공번호'];

// 전공번호를 통해 학과번호를 가져옵니다.
$query2 = "SELECT 학과번호 FROM 전공 WHERE 전공번호 = '$전공번호'";
$result2 = $conn->query($query2);
$row2 = $result2->fetch_assoc();
$학과번호 = $row2['학과번호'];

// 학과번호를 통해 학과명을 가져옵니다.
$query3 = "SELECT 학과이름 FROM 학과 WHERE 학과번호 = '$학과번호'";
$result3 = $conn->query($query3);
$row3 = $result3->fetch_assoc();
$학과명 = $row3['학과이름'];

$result = $conn->query($query);

// 결과를 배열로 가져옵니다.
$student_data = $result->fetch_assoc();

$학번 = $_SESSION['학번'];

$query_enrolled_courses = "SELECT 과목정보.과목정보번호, 과목.과목명, 과목.학점, 교수.이름 AS 교수이름, 일정.시간표
                           FROM 수강신청 
                           INNER JOIN 과목정보 ON 수강신청.과목정보번호 = 과목정보.과목정보번호 
                           INNER JOIN 과목 ON 과목정보.과목번호 = 과목.과목번호 
                           INNER JOIN 교수 ON 과목정보.교수번호 = 교수.교수번호 
                           INNER JOIN 일정 ON 과목정보.일정번호 = 일정.일정번호
                           WHERE 수강신청.학번 = '$학번'";

$result_enrolled = $conn->query($query_enrolled_courses);

$query_grades = "SELECT 과목.과목명, 성적.학점 
                 FROM 성적 
                 INNER JOIN 과목정보 ON 성적.과목정보번호 = 과목정보.과목정보번호
                 INNER JOIN 과목 ON 과목정보.과목번호 = 과목.과목번호
                 WHERE 성적.학번 = '$학번'";
$result_grades = $conn->query($query_grades);

if (!$result_grades) {
    die('Query Error: ' . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="kr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="dashboard-container">

    <div class="header-banner">
        <h1>대학교 수강신청 시스템</h1>
        <p>환영합니다, <?php echo $user_name; ?>님!</p>
    </div>
    
    <nav class="menu">
        <ul>
        <li><a href="course_registration.php">수강신청</a></li>
            <li><a href="grades.php">성적</a></li>
            <li><a href="settings.php">환경설정</a></li>
        </ul>
    </nav>

    <section id="section1">
    <h2>학사정보</h2>
    <table>
        <tr>
            <td>학번</td>
            <td><?php echo $student_data['학번']; ?></td>
        </tr>
        <tr>
            <td>이름</td>
            <td><?php echo $student_data['이름']; ?></td>
        </tr>
        <tr>
            <td>이메일</td>
            <td><?php echo $student_data['이메일']; ?></td>
        </tr>
        <tr>
            <td>학과</td>
            <td><?php echo $학과명; ?></td>
        </tr>
        <!-- 필요한 항목을 추가하여 확장 -->
    </table>
</section>
<section id="section2">
    <h2>수강신청 과목</h2>
    <div class="dashmenu">
    <table>
        <thead>
            <tr>
                <th>과목 코드</th>
                <th>과목 이름</th>
                <th>교수</th>
                <th>학점</th>
                <th>시간</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result_enrolled->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['과목정보번호']; ?></td>
                <td><?php echo $row['과목명']; ?></td>
                <td><?php echo $row['교수이름']; ?></td>
                <td><?php echo $row['학점']; ?></td>
                <td><?php echo $row['시간표']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
        </div>
</section>

        <section id="section3">
            <h2>성적</h2>
            <div class="dashmenu">
        <table>
            <thead>
                <tr>
                    <th>과목명</th>
                    <th>학점</th>
        </tr>
            </thead>
            <tbody>
            <?php while($row = $result_grades->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['과목명']; ?></td>
                <td><?php echo is_null($row['학점']) ? '미정' : $row['학점']; ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
        </div>

        </section>

        <section id="section4">
            <h2>Settings</h2>
            <!-- User settings -->
        </section>
        
        <a href="logout.php" class="logout-btn">로그아웃</a>
    </div>

    <div class="low-banner">
        <h1>대학교 수강신청 시스템</h1>
        <p>환영합니다, <?php echo $user_name; ?>님!</p>
    </div>
    

</body>
</html>
