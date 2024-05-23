<?php
session_start();

include 'config.php';

// 학번을 세션에서 가져옵니다.
$학번 = $_SESSION['학번'];

// POST로 전달된 과목정보번호를 가져옵니다.
$과목정보번호 = $_POST['과목정보번호'];

// 이미 수강신청을 했는지 확인
$checkQuery = "SELECT * FROM 수강신청 WHERE 학번='$학번' AND 과목정보번호='$과목정보번호'";
$checkResult = $conn->query($checkQuery);

if($checkResult->num_rows > 0) {
    // 이미 수강신청한 과목인 경우
    echo "already_enrolled";
} else {
    $query = "INSERT INTO 수강신청 (학번, 과목정보번호) VALUES ('$학번', '$과목정보번호')";

    if ($conn->query($query) === TRUE) {
        // 수강신청 성공 후 현재수강인원 +1 증가
        $query_increase = "UPDATE 과목정보 SET 현재수강인원 = 현재수강인원 + 1 WHERE 과목정보번호 = '$과목정보번호'";
        
        if($conn->query($query_increase)) {
            // 현재수강인원 업데이트에 성공한 경우
            
            // 성적 테이블에 데이터 삽입 쿼리
            $insert_grade_query = "INSERT INTO 성적 (학번, 과목정보번호, 학점) VALUES ('$학번', '$과목정보번호', NULL)";
            if($conn->query($insert_grade_query) === TRUE) {
                echo "success"; // 성공적으로 수강 신청 및 성적 데이터 삽입
            } else {
                echo "grade_table_error: " . $conn->error; // 성적 테이블 데이터 삽입 에러 메시지 출력
            }
            
        } else {
            // 현재수강인원 업데이트에 실패한 경우
            echo "UPDATE error: " . $conn->error;  // 에러 메시지 출력
        }
    
    } else {
        // 수강신청 실패
        echo "INSERT error: " . $conn->error;  // 에러 메시지 출력
    }
}
    
$conn->close();
?>
