<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// เพิ่มข้อมูลประเภทสนามกีฬาใหม่
// $app->post('/Insert_stadium', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn']; 
//     $imgPath = 'D:/angular/angular-app/src/assets/img/'; 
//     // $imgPath = '../assets/img/';
//     // ฟังก์ชัน handleUpload ใช้สำหรับจัดการการอัปโหลดไฟล์ภาพ
//     function handleUpload($fileKey, $imgPath) {
//         if (isset($_FILES[$fileKey])) {
//             $imgName = uniqid() . '.png'; 
//             $targetFilePath = $imgPath . $imgName;
//             if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetFilePath)) {
//                 return $imgName;
//             } else {
//                 return null; 
//             }
//         } else {
//             return null; 
//         }
//     }

//     // เรียกใช้ฟังก์ชัน handleUpload เพื่อรับชื่อของไฟล์ภาพ
//     $imgstadium = handleUpload('path_img', $imgPath);

//     // สร้างคำสั่ง SQL เพื่อเพิ่มข้อมูลลงในตาราง tb_stadium
//     $sql = "INSERT INTO tb_stadium (stadium_name, location, info_stadium, path_img) VALUES (?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);

//     // ผูกข้อมูล POST กับพารามิเตอร์ของคำสั่ง SQL
//     $stmt->bind_param(
//         'ssss', 
//         $_POST['stadium_name'], 
//         $_POST['location'], 
//         $_POST['info_stadium'], 
//         $imgstadium, 
//     );

//     $stmt->execute();
//     $affected = $stmt->affected_rows;

//     // ตรวจสอบว่าการเพิ่มข้อมูลสำเร็จหรือไม่
//     if ($affected > 0) {
//         $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
//         $response->getBody()->write(json_encode($data));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(200); 
//     } else {
//         $errorResponse = ["message" => "Failed to create boot entry"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500); 
//     }
// });
$app->post('/Insert_stadium', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    // $imgPath = 'D:/angular/angular-app/src/assets/img/';
    $imgPath = '../assets/img/';
    function handleUpload($fileKey, $imgPath) {
        if (isset($_FILES[$fileKey])) {
            $imgName = uniqid() . '.png';
            $targetFilePath = $imgPath . $imgName;
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetFilePath)) {
                return $imgName;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    // รับข้อมูล POST
    $stadium_name = $_POST['stadium_name'];
    $location = $_POST['location'];
    $info_stadium = $_POST['info_stadium'];

    // ตรวจสอบว่ามีชื่อสนามกีฬาอยู่ในระบบแล้วหรือไม่
    $checkSql = "SELECT COUNT(*) AS count FROM tb_stadium WHERE stadium_name = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('s', $stadium_name);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $row = $checkResult->fetch_assoc();

    if ($row['count'] > 0) {
        // ส่งข้อความแสดงข้อผิดพลาดหากพบชื่อซ้ำ
        $errorResponse = ["message" => "ชื่อสนามกีฬามีอยู่แล้ว"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    // ถ้าไม่ซ้ำ ดำเนินการอัปโหลดรูปภาพและบันทึกข้อมูล
    $imgstadium = handleUpload('path_img', $imgPath);

    $sql = "INSERT INTO tb_stadium (stadium_name, location, info_stadium, path_img) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $stadium_name, $location, $info_stadium, $imgstadium);

    $stmt->execute();
    $affected = $stmt->affected_rows;

    if ($affected > 0) {
        $data = ["affected_rows" => $affected, "last_idx" => $conn->insert_id];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    } else {
        $errorResponse = ["message" => "ไม่สามารถเพิ่มข้อมูลสนามกีฬาได้"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

// เพิ่มข้อมูลหมายเลขสนามกีฬาใหม่
// $app->post('/Insert_number', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn']; // เชื่อมต่อฐานข้อมูล

//     // รับข้อมูลที่ส่งมาจากคำขอ
//     $data = $request->getParsedBody();

//     // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
//     if (!isset($data['number_name']) || !isset($data['id_stadium'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // เพิ่มข้อมูลหมายเลขสนามกีฬาลงในฐานข้อมูล
//     $sql = "INSERT INTO tb_number (number_name, id_stadium) VALUES (?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('si', $data['number_name'], $data['id_stadium']);
//     $stmt->execute();

//     // ตรวจสอบว่ามีการเพิ่มข้อมูลสำเร็จหรือไม่
//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "เพิ่มข้อมูลสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถเพิ่มข้อมูลสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });
$app->post('/Insert_number', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn']; // เชื่อมต่อฐานข้อมูล

    // รับข้อมูลที่ส่งมาจากคำขอ
    $data = $request->getParsedBody();

    // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
    if (!isset($data['number_name']) || !isset($data['id_stadium'])) {
        $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    // ตรวจสอบว่าหมายเลขซ้ำหรือไม่
    $checkSql = "SELECT COUNT(*) AS count FROM tb_number WHERE number_name = ? AND id_stadium = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param('si', $data['number_name'], $data['id_stadium']);
    $checkStmt->execute();
    $result = $checkStmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        // ส่งข้อความแจ้งเตือนว่าหมายเลขซ้ำ
        $errorResponse = ["message" => "หมายเลขนี้มีอยู่ในสนามแล้ว"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(409); // HTTP Status 409: Conflict
    }

    // ถ้าไม่มีหมายเลขซ้ำ ให้เพิ่มข้อมูลลงในฐานข้อมูล
    $sql = "INSERT INTO tb_number (number_name, id_stadium) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $data['number_name'], $data['id_stadium']);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $responseBody = ["message" => "เพิ่มข้อมูลสนามกีฬาสำเร็จ"];
        $response->getBody()->write(json_encode($responseBody));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    } else {
        $errorResponse = ["message" => "ไม่สามารถเพิ่มข้อมูลสนามกีฬาได้"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

// ลบข้อมูลประเภทสนามกีฬา admin admin-manage
$app->delete('/delete_stadium/{id_stadium}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    // รับ ID จากพารามิเตอร์ของเส้นทาง
    $StadiumId = $args['id_stadium'];
    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->begin_transaction();
    try {
        // ลบข้อมูลที่ผูกไว้ในตาราง tb_booking
        $sqlbooking = "DELETE FROM tb_booking WHERE id_stadium = ?";
        $stmtbooking = $conn->prepare($sqlbooking);
        if (!$stmtbooking) {
            throw new Exception("Prepare statement for tb_booking failed: " . $conn->error);
        }
        $stmtbooking->bind_param('i', $StadiumId);
        if (!$stmtbooking->execute()) {
            throw new Exception("Execute statement for tb_booking failed: " . $stmtbooking->error);
        }

        // ลบข้อมูลที่ผูกไว้ในตาราง tb_number
        $sqlnumber = "DELETE FROM tb_number WHERE id_stadium = ?";
        $stmtnumber = $conn->prepare($sqlnumber);
        if (!$stmtnumber) {
            throw new Exception("Prepare statement for tb_number failed: " . $conn->error);
        }
        $stmtnumber->bind_param('i', $StadiumId);
        if (!$stmtnumber->execute()) {
            throw new Exception("Execute statement for tb_number failed: " . $stmtnumber->error);
        }

        // ลบข้อมูลในตาราง tb_stadium
        $sqlstadium = "DELETE FROM tb_stadium WHERE id_stadium = ?";
        $stmtstadium = $conn->prepare($sqlstadium);
        if (!$stmtstadium) {
            throw new Exception("Prepare statement for tb_stadium failed: " . $conn->error);
        }
        $stmtstadium->bind_param('i', $StadiumId);
        if (!$stmtstadium->execute()) {
            throw new Exception("Execute statement for tb_stadium failed: " . $stmtstadium->error);
        }

        $affectedstadium = $stmtstadium->affected_rows;

        if ($affectedstadium > 0) {
            // ยืนยันการทำธุรกรรม (commit transaction)
            $conn->commit();

            $data = [
                "message" => "Stadium deleted successfully",
                "affected_stadium_rows" => $affectedstadium
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200); // ส่งกลับสถานะ 200 OK
        } else {
            // ยกเลิกการทำธุรกรรม (rollback transaction)
            $conn->rollback();

            $errorResponse = ["message" => "Failed to delete stadium"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
        $conn->rollback();

        // เพิ่มการ log ข้อความข้อผิดพลาด
        error_log("An error occurred: " . $e->getMessage());

        $errorResponse = ["message" => "An error occurred while deleting stadium", "error" => $e->getMessage()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
    }
});


// ลบข้อมูลหมายเลขสนามกีฬา
$app->delete('/delete_number/{id_number}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับ ID จากพารามิเตอร์ของเส้นทาง
    $numberId = $args['id_number'];

    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->begin_transaction();

    try {
        // ลบข้อมูลจากตาราง tb_booking ที่มี id_number ตรงกัน
        $sqlBooking = "DELETE FROM tb_booking WHERE id_number = ?";
        $stmtBooking = $conn->prepare($sqlBooking);
        if (!$stmtBooking) {
            throw new Exception("Prepare statement for tb_booking failed: " . $conn->error);
        }
        $stmtBooking->bind_param('i', $numberId);
        if (!$stmtBooking->execute()) {
            throw new Exception("Execute statement for tb_booking failed: " . $stmtBooking->error);
        }

        // ลบข้อมูลจากตาราง tb_number ที่มี id_number ตรงกัน
        $sqlNumber = "DELETE FROM tb_number WHERE id_number = ?";
        $stmtNumber = $conn->prepare($sqlNumber);
        if (!$stmtNumber) {
            throw new Exception("Prepare statement for tb_number failed: " . $conn->error);
        }
        $stmtNumber->bind_param('i', $numberId);
        if (!$stmtNumber->execute()) {
            throw new Exception("Execute statement for tb_number failed: " . $stmtNumber->error);
        }

        // ตรวจสอบจำนวนแถวที่ได้รับผลกระทบ
        $affectedNumberRows = $stmtNumber->affected_rows;

        if ($affectedNumberRows > 0) {
            // ยืนยันการทำธุรกรรม (commit transaction)
            $conn->commit();

            $data = [
                "message" => "Number deleted successfully",
                "affected_number_rows" => $affectedNumberRows
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200); // ส่งกลับสถานะ 200 OK
        } else {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากล้มเหลว
            $conn->rollback();

            $errorResponse = ["message" => "Failed to delete number: Number not found"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
        $conn->rollback();

        $errorResponse = ["message" => "An error occurred while deleting number", "error" => $e->getMessage()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
    }
});

$app->post('/Edit_stadium', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    // $imgPath = 'D:/angular/angular-app/src/assets/img/';
    $imgPath = '../assets/img/';
    function handleUpload($fileKey, $imgPath) {
        if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
            $imgName = uniqid() . '.png';
            $targetFilePath = $imgPath . $imgName;
            if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetFilePath)) {
                return $imgName;
            } else {
                error_log("Failed to move uploaded file to $targetFilePath");
                return null;
            }
        } else {
            if (isset($_FILES[$fileKey])) {
                error_log("Upload error: " . $_FILES[$fileKey]['error']);
            }
            return null;
        }
    }

    $imgstadium = handleUpload('path_img', $imgPath);
    
    // ตรวจสอบว่าชื่อประเภทสนามมีอยู่ในฐานข้อมูลหรือไม่
    $stadium_name = $_POST['stadium_name'];
    $sqlCheck = "SELECT COUNT(*) FROM tb_stadium WHERE stadium_name = ? AND id_stadium != ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    $stmtCheck->bind_param('si', $stadium_name, $_POST['id_stadium']);
    $stmtCheck->execute();
    $stmtCheck->bind_result($count);
    $stmtCheck->fetch();
    $stmtCheck->close();

    if ($count > 0) {
        $errorResponse = ["message" => "ชื่อประเภทสนามซ้ำ กรุณากรอกชื่อใหม่"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400); // ใช้ 400 Bad Request
    }

    if ($imgstadium === null && isset($_FILES['path_img'])) {
        $errorResponse = ["message" => "Failed to upload image"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $sql = "UPDATE tb_stadium SET 
    stadium_name = ?, 
    location = ?, 
    info_stadium = ?, 
    path_img = IFNULL(?, path_img) 
    WHERE id_stadium = ?";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare SQL statement");
        $errorResponse = ["message" => "Failed to prepare statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmt->bind_param('ssssi', $_POST['stadium_name'], $_POST['location'], $_POST['info_stadium'], $imgstadium, $_POST['id_stadium']);

    if (!$stmt->execute()) {
        error_log("Failed to execute SQL statement: " . $stmt->error);
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
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

// แก้ไขข้อมูลประเภทสนามกีฬา
// $app->post('/Edit_stadium', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $imgPath = 'D:/angular/angular-app/src/assets/img/';
//     // $imgPath = '../assets/img/';
//     function handleUpload($fileKey, $imgPath) {
//         if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
//             $imgName = uniqid() . '.png';
//             $targetFilePath = $imgPath . $imgName;
//             if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetFilePath)) {
//                 return $imgName;
//             } else {
//                 error_log("Failed to move uploaded file to $targetFilePath");
//                 return null;
//             }
//         } else {
//             if (isset($_FILES[$fileKey])) {
//                 error_log("Upload error: " . $_FILES[$fileKey]['error']);
//             }
//             return null;
//         }
//     }

//     $imgstadium = handleUpload('path_img', $imgPath);

//     if ($imgstadium === null && isset($_FILES['path_img'])) {
//         $errorResponse = ["message" => "Failed to upload image"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $sql = "UPDATE tb_stadium SET 
//     stadium_name = ?, 
//     location = ?, 
//     info_stadium = ?, 
//     path_img = IFNULL(?, path_img) 
//     WHERE id_stadium = ?";

//     $stmt = $conn->prepare($sql);
//     if (!$stmt) {
//         error_log("Failed to prepare SQL statement");
//         $errorResponse = ["message" => "Failed to prepare statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $stmt->bind_param('ssssi', $_POST['stadium_name'], $_POST['location'], $_POST['info_stadium'], $imgstadium, $_POST['id_stadium']);

//     if (!$stmt->execute()) {
//         error_log("Failed to execute SQL statement: " . $stmt->error);
//         $errorResponse = ["message" => "Failed to execute statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $affected = $stmt->affected_rows;
//     if ($affected > 0) {
//         $data = ["affected_rows" => $affected];
//         $response->getBody()->write(json_encode($data));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "No rows affected"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }
// });

$app->post('/Edit_number', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับข้อมูลจากคำขอ
    $number_name = $request->getParsedBody()['number_name'] ?? null;
    $id_stadium = $request->getParsedBody()['id_stadium'] ?? null;
    $id_number = $request->getParsedBody()['id_number'] ?? null;

    // ตรวจสอบว่าข้อมูลที่จำเป็นทั้งหมดถูกส่งมา
    if (!$number_name || !$id_stadium || !$id_number) {
        $errorResponse = ["message" => "Missing required parameters"];
        error_log("Edit_number error: " . json_encode($errorResponse));
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // ตรวจสอบชื่อหมายเลขว่ามีอยู่แล้วหรือไม่ (ยกเว้น ID ที่กำลังแก้ไข)
    $sqlCheck = "SELECT id_number FROM tb_number WHERE number_name = ? AND id_number != ?";
    $stmtCheck = $conn->prepare($sqlCheck);
    if (!$stmtCheck) {
        error_log("Failed to prepare SQL check statement: " . $conn->error);
        $errorResponse = ["message" => "Failed to prepare check statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmtCheck->bind_param('si', $number_name, $id_number);
    $stmtCheck->execute();
    $result = $stmtCheck->get_result();

    if ($result->num_rows > 0) {
        $errorResponse = ["message" => "Duplicate number name detected"];
        error_log("Duplicate number name: " . $number_name);
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    // ทำการอัปเดตข้อมูล
    $sql = "UPDATE tb_number SET 
            number_name = ?, 
            id_stadium = ? 
            WHERE id_number = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        error_log("Failed to prepare SQL statement: " . $conn->error);
        $errorResponse = ["message" => "Failed to prepare statement"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }

    $stmt->bind_param('ssi', $number_name, $id_stadium, $id_number);

    if (!$stmt->execute()) {
        error_log("Failed to execute SQL statement: " . $stmt->error);
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
        error_log("No rows affected when updating number: " . $id_number);
        $response->getBody()->write(json_encode($errorResponse));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }
});

// แก้ไขข้อมูลหมายเลขสนามกีฬา
// $app->post('/Edit_number', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับข้อมูลจากคำขอ
//     $number_name = $request->getParsedBody()['number_name'] ?? null;
//     $id_stadium = $request->getParsedBody()['id_stadium'] ?? null;
//     $id_number = $request->getParsedBody()['id_number'] ?? null;

//     // ตรวจสอบว่าข้อมูลที่จำเป็นทั้งหมดถูกส่งมา
//     if (!$number_name || !$id_stadium || !$id_number) {
//         $errorResponse = ["message" => "Missing required parameters"];
//         error_log("Edit_number error: " . json_encode($errorResponse)); // บันทึกข้อผิดพลาด
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
//     }

//     $sql = "UPDATE tb_number SET 
//             number_name = ?, 
//             id_stadium = ? 
//             WHERE id_number = ?";

//     $stmt = $conn->prepare($sql);
//     if (!$stmt) {
//         error_log("Failed to prepare SQL statement: " . $conn->error); // บันทึกข้อผิดพลาด
//         $errorResponse = ["message" => "Failed to prepare statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $stmt->bind_param('ssi', $number_name, $id_stadium, $id_number);

//     if (!$stmt->execute()) {
//         error_log("Failed to execute SQL statement: " . $stmt->error); // บันทึกข้อผิดพลาด
//         $errorResponse = ["message" => "Failed to execute statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $affected = $stmt->affected_rows;
//     if ($affected > 0) {
//         $data = ["affected_rows" => $affected];
//         $response->getBody()->write(json_encode($data));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "No rows affected"];
//         error_log("No rows affected when updating number: " . $id_number); // บันทึกข้อผิดพลาด
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
//     }
// });

// ดึงข้อมูลประเภทสนามกีฬามาแสดงตาม id admin-edittype
$app->get('/stadiums/{id_stadium}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $id_stadium = $args['id_stadium'];

    $sql = 'SELECT id_stadium, stadium_name, location, info_stadium FROM tb_stadium WHERE id_stadium = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_stadium);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});


// เรียกข้อมูลประเภทสนามมาแสดงโดยไม่มีภาพ booking-time admin-manage
$app->get('/stadium', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT id_stadium, stadium_name, location, info_stadium FROM tb_stadium';
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

// เรียกข้อมูลสนามโดยมี ประเภทสนาม หมายเลขสนาม มาแสดง อิงตา id number admin-edittime admin-editnumber
$app->get('/stadium_number/{id_number}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name, tb_number.id_stadium 
            FROM tb_number 
            JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
            WHERE tb_number.id_number = ?'; // Changed to select based on id_number
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $args['id_number']); // Binding id_number
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

    if ($data) {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "Stadium not found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});

//เรียกข้อมูลประเภทสนามกีฬามาแสดงอิงตาม id ของสนามนั้นๆ booking-time
$app->get('/stadium/{id_stadium}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name, tb_stadium.id_stadium
            FROM tb_number 
            JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
            WHERE tb_number.id_stadium = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $args['id_stadium']);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

    if ($data) {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    }  
    // else {
    //     $errorResponse = ["message" => "Stadium not found"];
    //     $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
    //     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    // }
});


//ดึงข้อมูลสนามมาแสดงในฝัง user stadium-detail home booking-time นำภาพมาแสดงด้วย
$app->get('/stadium_user', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT id_stadium, stadium_name, location, info_stadium, path_img FROM tb_stadium';
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

$app->get('/stadium_user/{id_stadium}', function (Request $request, Response $response, array $args) {
    $conn = $GLOBALS['conn'];
    $id_stadium = $args['id_stadium']; 
    
    $sql = 'SELECT id_stadium, stadium_name, location, info_stadium, path_img FROM tb_stadium WHERE id_stadium = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_stadium); 
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }
    
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});