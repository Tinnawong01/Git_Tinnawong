<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// เรียกข้อมูลผู้ใช้งานมาแสดง profile-password profile-name profile-main navbar
$app->get('/memberid/{id_member}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT id_member, email, SUBSTRING(password, 1, 8) as password, prefix, fname, lname, role 
            FROM tb_member 
            WHERE id_member = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $args['id_member']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "Member not found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});


$app->get('/member/{id_member}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT email, password, prefix, fname, lname, role 
            FROM tb_member 
            WHERE id_member = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $args['id_member']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data) {
        unset($data['id_member']); 
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "Member not found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});

$app->post('/Edit_password', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับข้อมูลจากคำขอ
    $id_member = $request->getParsedBody()['id_member'] ?? null;
    $email = $request->getParsedBody()['email'] ?? null;
    $password = $request->getParsedBody()['password'] ?? null;

    // ตรวจสอบว่าข้อมูลที่จำเป็นทั้งหมดถูกส่งมา
    if (!$id_member || !$email || !$password) {
        $errorResponse = ["message" => "Missing required parameters"];
        error_log("Edit_password error: " . json_encode($errorResponse)); // บันทึกข้อผิดพลาด
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // ตรวจสอบว่ามี email และ id_member ตรงกันในฐานข้อมูลหรือไม่
    $sql = "SELECT id_member FROM tb_member WHERE id_member = ? AND email = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare SQL statement: " . $conn->error); // บันทึกข้อผิดพลาด
        $errorResponse = ["message" => "Failed to prepare statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmt->bind_param('is', $id_member, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $errorResponse = ["message" => "Email and ID do not match"];
        error_log("Email and ID do not match for id_member: " . $id_member); // บันทึกข้อผิดพลาด
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // แฮชรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // อัพเดทรหัสผ่านในฐานข้อมูล
    $sql = "UPDATE tb_member SET password = ? WHERE id_member = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare SQL statement: " . $conn->error); // บันทึกข้อผิดพลาด
        $errorResponse = ["message" => "Failed to prepare statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmt->bind_param('si', $hashed_password, $id_member);

    if (!$stmt->execute()) {
        error_log("Failed to execute SQL statement: " . $stmt->error); // บันทึกข้อผิดพลาด
        $errorResponse = ["message" => "Failed to execute statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = ["affected_rows" => $affected];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No rows affected"];
        error_log("No rows affected when updating password for member: " . $id_member); // บันทึกข้อผิดพลาด
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

$app->post('/Edit_profile', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // Receive data from the request
    $id_member = $request->getParsedBody()['id_member'] ?? null;
    $fname = $request->getParsedBody()['fname'] ?? null;
    $lname = $request->getParsedBody()['lname'] ?? null;

    // Check if all necessary data is sent
    if (!$id_member || !$fname || !$lname) {
        $errorResponse = ["message" => "Missing required parameters"];
        error_log("Edit_profile error: " . json_encode($errorResponse)); // Log error
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // Update first and last name in the database
    $sql = "UPDATE tb_member SET fname = ?, lname = ? WHERE id_member = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare SQL statement: " . $conn->error); // Log error
        $errorResponse = ["message" => "Failed to prepare statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmt->bind_param('ssi', $fname, $lname, $id_member);

    if (!$stmt->execute()) {
        error_log("Failed to execute SQL statement: " . $stmt->error); // Log error
        $errorResponse = ["message" => "Failed to execute statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $affected = $stmt->affected_rows;
    if ($affected > 0) {
        $data = ["affected_rows" => $affected];
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No rows affected"];
        error_log("No rows affected when updating profile for member: " . $id_member); // Log error
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});