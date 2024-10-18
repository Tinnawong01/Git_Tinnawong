<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/register', function(Request $request, Response $response){
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT * FROM tb_member';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach($result as $row){
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

$app->post('/register', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $conn = $GLOBALS['conn'];

    // ตรวจสอบอีเมลซ้ำ
    $checkEmailSql = "SELECT COUNT(*) AS email_count FROM tb_member WHERE email = ?";
    $checkEmailStmt = $conn->prepare($checkEmailSql);
    $checkEmailStmt->bind_param('s', $jsonData['email']);
    $checkEmailStmt->execute();
    $checkEmailResult = $checkEmailStmt->get_result();
    $emailCount = $checkEmailResult->fetch_assoc()['email_count'];
    if ($emailCount > 0) {
        $errorResponse = ["message" => "Email address already in use"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400); 
    }

    // ตรวจสอบ std_id ซ้ำ
    $checkIdSql = "SELECT COUNT(*) AS id_count FROM tb_member WHERE std_id = ?";
    $checkIdStmt = $conn->prepare($checkIdSql);
    $checkIdStmt->bind_param('s', $jsonData['std_id']);
    $checkIdStmt->execute();
    $checkIdResult = $checkIdStmt->get_result();
    $idCount = $checkIdResult->fetch_assoc()['id_count'];
    if ($idCount > 0) {
        $errorResponse = ["message" => "Student ID already in use"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    // สุ่ม id_member
    $id_member = rand(10000, 99999); // สามารถปรับช่วงของการสุ่มตัวเลขได้ตามต้องการ

    // Insert ข้อมูลสมาชิกใหม่
    $sql = "INSERT INTO tb_member(id_member, email, password, prefix, fname, lname, sex, faculty_id, department, role, std_id) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
    $hashedPassword = password_hash($jsonData['password'], PASSWORD_DEFAULT);

    $role = isset($jsonData['role']) ? $jsonData['role'] : 'user';  // ตั้งค่า role เป็น 'user' ถ้าไม่ได้ส่งค่า role มา

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('issssssssss', $id_member, $jsonData['email'], $hashedPassword, $jsonData['prefix'], $jsonData['fname'], $jsonData['lname'], $jsonData['sex'], $jsonData['faculty_id'], $jsonData['department'], $role, $jsonData['std_id']);
    $stmt->execute();

    $affected = $stmt->affected_rows;

    if ($affected > 0) {
        $data = ["affected_rows" => $affected, "last_id_member" => $id_member];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200); 
    } else {
        $errorResponse = ["message" => "Failed to create user"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); 
    }
});

$app->get('/faculties', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];

    // Fetch all faculties
    $sqlFacuty = 'SELECT Facuty_id, Facuty_name FROM tb_facuty';
    $stmtFacuty = $conn->prepare($sqlFacuty);
    $stmtFacuty->execute();
    $resultFacuty = $stmtFacuty->get_result();
    $faculties = array();

    while ($rowFacuty = $resultFacuty->fetch_assoc()) {
        // Push each faculty to the faculties array
        $faculties[] = $rowFacuty;
    }

    // Return the faculties as JSON response
    $response->getBody()->write(json_encode($faculties, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});


$app->get('/departments/{facultyId}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    $facultyId = $args['facultyId'];

    // Fetch departments for the given faculty ID
    $sqlDepartment = 'SELECT Major_id, Major_name FROM tb_major WHERE Facuty_id = ?';
    $stmtDepartment = $conn->prepare($sqlDepartment);
    $stmtDepartment->bind_param('i', $facultyId);
    $stmtDepartment->execute();
    $resultDepartment = $stmtDepartment->get_result();
    $departments = array();

    while ($rowDepartment = $resultDepartment->fetch_assoc()) {
        array_push($departments, $rowDepartment);
    }

    // Return the departments as JSON response
    $response->getBody()->write(json_encode($departments, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});