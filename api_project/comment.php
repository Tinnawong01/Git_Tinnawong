<?php
// $app->post('/Edit_stadium', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $imgPath = './img/';

//     function handleUpload($fileKey, $imgPath) {
//         if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
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

//     $imgstadium = handleUpload('path_img', $imgPath);

//     if ($imgstadium === null && isset($_FILES['path_img'])) {
//         $errorResponse = ["message" => "Failed to upload image"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }

//     $sql = "UPDATE tb_stadium SET 
//     stadium_name = ?, 
//     location = ?, 
//     info_stadium = ?, 
//     path_img = IFNULL(?, path_img) 
//     WHERE id_stadium = ?";

//     $stmt = $conn->prepare($sql);
//     if (!$stmt) {
//         $errorResponse = ["message" => "Failed to prepare statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }

//     $stmt->bind_param(
//         'ssssi', 
//         $_POST['stadium_name'],
//         $_POST['location'], 
//         $_POST['info_stadium'], 
//         $imgstadium, 
//         $_POST['id_stadium']
//     );

//     if (!$stmt->execute()) {
//         $errorResponse = ["message" => "Failed to execute statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }

//     $affected = $stmt->affected_rows;
//     if ($affected > 0) {
//         $data = ["affected_rows" => $affected];
//         $response->getBody()->write(json_encode($data));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "No rows affected"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });


// //ดึงข้อมูลสนามกีฬาตาม ID
// $app->get('/stadium_number/{id_stadium}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name 
//             FROM tb_number 
//             JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
//             WHERE tb_number.id_stadium = ?';
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $args['id_stadium']);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Stadium not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });
// ดึงข้อมูลสนามกีฬาตาม id_number
// $app->get('/stadium_number/{id_number}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name 
//             FROM tb_number 
//             JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
//             WHERE tb_number.id_number = ?'; // Changed to select based on id_number
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $args['id_number']); // Binding id_number
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Stadium not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });
// $app->get('/stadium_number/{id_number}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name 
//             FROM tb_number 
//             JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
//             WHERE tb_number.id_number = ?'; // Changed to select based on id_number
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $args['id_number']); // Binding id_number
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Stadium not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });

// $app->post('/Edit_number', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     $sql = "UPDATE tb_number SET 
//     number_name = ?, 
//     id_stadium = ? 
//     WHERE id_number = ?";

//     $stmt = $conn->prepare($sql);
//     if (!$stmt) {
//         error_log("Failed to prepare SQL statement");
//         $errorResponse = ["message" => "Failed to prepare statement"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
//     }

//     $stmt->bind_param('ssi', $_POST['number_name'], $_POST['id_stadium'], $_POST['id_number']);

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

// $app->post('/Insert_stadium', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $imgPath = './img/';

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

//     $imgstadium = handleUpload('path_img', $imgPath);

//     $sql = "INSERT INTO tb_stadium (stadium_name, location, info_stadium, path_img) VALUES (?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);

//     $stmt->bind_param(
//         'ssss', 
//         $_POST['stadium_name'], 
//         $_POST['location'], 
//         $_POST['info_stadium'], 
//         $imgstadium, 

//     );

//     $stmt->execute();

//     $affected = $stmt->affected_rows;

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

// // เพิ่มข้อมูลสนามกีฬาใหม่
// $app->post('/Insertsss-stadium', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $isAdmin = true; 

//     if (!$isAdmin) {
//         $errorResponse = ["message" => "Unauthorized access"];
//         $response->getBody()->write(json_encode($errorResponse));   
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(401); 
//     }

//     $data = $request->getParsedBody();
//     $imgPath = './img/';
//     $imgstdium = handleUpload('path_img',$imgPath);
//     function handleUpload($fileKey, $imgPath) {
//         if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
//             $imgName = uniqid() . '.png'; // Assume PNG file for simplicity
//             $targetFilePath = $imgPath . $imgName;
//             if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetFilePath)) {
//                 return $imgName;
//             } else {
//                 return null; // If file move fails
//             }
//         } else {
//             return null; // If no image uploaded
//         }
//     }
//     // เพิ่มข้อมูลสนามกีฬาใหม่ลงในฐานข้อมูล
//     $sql = "INSERT INTO tb_stadium (stadium_name, location, info_stadium, path_img) VALUES (?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('ssss', $data['stadium_name'], $data['location'], $data['info_stadium'] ,$imgstdium);
//     $stmt->execute();

//     $affectedRows = $stmt->affected_rows;

//     if ($affectedRows > 0) {
//         $responseBody = ["message" => "Stadium added successfully"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201); 
//     } else {
//         $errorResponse = ["message" => "Failed to add stadium"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500); 
//     }
// });
// $app->post('/Insert_number', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     // สมมติว่าคุณมีวิธีการตรวจสอบว่าผู้ใช้เป็นผู้ดูแลระบบ
//     $isAdmin = true;

//     if (!$isAdmin) {
//         $errorResponse = ["message" => "การเข้าถึงไม่ได้รับอนุญาต"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(401);
//     }

//     $data = $request->getParsedBody();

//     // ตรวจสอบความถูกต้องของข้อมูลที่รับมา
//     if (!isset($data['number_name']) || !isset($data['id_stadium'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // เพิ่มข้อมูลสนามกีฬาลงในฐานข้อมูล
//     $sql = "INSERT INTO tb_number (number_name, id_stadium) VALUES (?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('si', $data['number_name'], $data['id_stadium']);
//     $stmt->execute();

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

// $app->delete('/delete_stadium/{id_stadium}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     // รับ ID จากพารามิเตอร์ของเส้นทาง
//     $StadiumId = $args['id_stadium'];
//     // เริ่มต้นการทำธุรกรรม (transaction)
//     $conn->begin_transaction();
//     try {

//         $sqlstadium = "DELETE FROM tb_number WHERE id_stadium = ?";
//         $stmtstadium = $conn->prepare($sqlstadium);
//         $stmtstadium->bind_param('i', $StadiumId );
//         $stmtstadium->execute();
        
//         $sqlstadium = "DELETE FROM tb_stadium WHERE id_stadium = ?";
//         $stmtstadium = $conn->prepare($sqlstadium);
//         $stmtstadium->bind_param('i', $StadiumId );
//         $stmtstadium->execute();

//         $affectedstadium = $stmtstadium->affected_rows;

//         if ($affectedstadium > 0) {
  
//             $conn->commit();

//             $data = [
//                 "message" => "stadium deleted successfully",
//                 "affected_stadium_rows" =>  $affectedstadium
//             ];
//             $response->getBody()->write(json_encode($data));
//             return $response
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withStatus(200); // ส่งกลับสถานะ 200 OK
//         } else {
   
//             $conn->rollback();

//             $errorResponse = ["message" => "Failed to delete zone"];
//             $response->getBody()->write(json_encode($errorResponse));
//             return $response
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
//         }
//     } catch (Exception $e) {
//         // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
//         $conn->rollback();

//         $errorResponse = ["message" => "An error occurred while deleting zone", "error" => $e->getMessage()];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
//     }
// });

// $app->delete('/delete_number/{id_number}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับ ID จากพารามิเตอร์ของเส้นทาง
//     $numberId = $args['id_number'];

//     // เริ่มต้นการทำธุรกรรม (transaction)
//     $conn->begin_transaction();

//     try {
//         // ลบข้อมูลจากตาราง tb_number ที่มี id_number ตรงกัน
//         $sqlNumber = "DELETE FROM tb_number WHERE id_number = ?";
//         $stmtNumber = $conn->prepare($sqlNumber);
//         $stmtNumber->bind_param('i', $numberId);
//         $stmtNumber->execute();
        
//         // ตรวจสอบจำนวนแถวที่ได้รับผลกระทบ
//         $affectedNumberRows = $stmtNumber->affected_rows;

//         if ($affectedNumberRows > 0) {
//             // ยืนยันการทำธุรกรรม (commit transaction)
//             $conn->commit();

//             $data = [
//                 "message" => "number deleted successfully",
//                 "affected_number_rows" => $affectedNumberRows
//             ];
//             $response->getBody()->write(json_encode($data));
//             return $response
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withStatus(200); // ส่งกลับสถานะ 200 OK
//         } else {
//             // ยกเลิกการทำธุรกรรม (rollback transaction) หากล้มเหลว
//             $conn->rollback();

//             $errorResponse = ["message" => "Failed to delete number: Number not found"];
//             $response->getBody()->write(json_encode($errorResponse));
//             return $response
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
//         }
//     } catch (Exception $e) {
//         // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
//         $conn->rollback();

//         $errorResponse = ["message" => "An error occurred while deleting number", "error" => $e->getMessage()];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
//     }
// });

// $app->post('/Edit_stadium', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $imgPath = 'D:/angular/angular-app/src/assets/img/';

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

// // ดึงข้อมูลสนามกีฬาทั้งหมด
// $app->get('/stadiums/{id_stadium}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $id_stadium = $args['id_stadium'];
//     $sql = 'SELECT id_stadium, stadium_name, location, info_stadium FROM tb_stadium WHERE id_stadium = ?';
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("i", $id_stadium); // Bind parameter
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach ($result as $row) {
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/stadium', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT id_stadium, stadium_name, location, info_stadium FROM tb_stadium';
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/stadium_number/{id_number}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name, tb_number.id_stadium 
//             FROM tb_number 
//             JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
//             WHERE tb_number.id_number = ?'; // Changed to select based on id_number
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $args['id_number']); // Binding id_number
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Stadium not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });


// $app->get('/stadium/{id_stadium}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT tb_number.id_number, tb_number.number_name, tb_stadium.stadium_name 
//             FROM tb_number 
//             JOIN tb_stadium ON tb_number.id_stadium = tb_stadium.id_stadium 
//             WHERE tb_number.id_stadium = ?';
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $args['id_stadium']);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Stadium not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });

// $app->get('/graph_booking/{id_stadium}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับค่าพารามิเตอร์ id_stadium จาก URL
//     $idStadium = isset($args['id_stadium']) ? (int)$args['id_stadium'] : 0;

//     // เตรียมคำสั่ง SQL เพื่อกรองข้อมูลตาม id_stadium
//     $sql = "
//         SELECT 
//             DATE_FORMAT(booking_date, '%Y-%m') AS month,
//             COUNT(id_booking) AS booking_count
//         FROM tb_booking
//         WHERE id_stadium = ?
//         GROUP BY month
//         ORDER BY month
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $idStadium); // ผูกพารามิเตอร์ id_stadium กับคำสั่ง SQL
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // ดึงข้อมูลทั้งหมด
//     $data = $result->fetch_all(MYSQLI_ASSOC);

//     // แปลงชื่อเดือนเป็นภาษาไทย
//     $monthNamesThai = [
//         '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม',
//         '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน',
//         '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน',
//         '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม'
//     ];

//     // สร้าง array สำหรับเก็บข้อมูลทั้งหมด 12 เดือน
//     $resultData = [];
//     foreach ($monthNamesThai as $key => $monthName) {
//         $resultData[$key] = [
//             'month' => $monthName,
//             'booking_count' => 0
//         ];
//     }

//     // แปลงข้อมูลที่ได้รับจากฐานข้อมูล
//     foreach ($data as $row) {
//         $month = date('m', strtotime($row['month'] . '-01')); // ดึงเดือนจากวันที่
//         $resultData[$month]['booking_count'] = $row['booking_count']; // แปลงเดือนเป็นภาษาไทย
//     }

//     // ส่งข้อมูลในรูปแบบ JSON
//     $response->getBody()->write(json_encode(array_values($resultData), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// จองได้เเค่รอบละ 1 ครั้ง โดยต้องผ่านวันที่จองไปก่อนถึงจะจองอีกได้ booking-confirm
// $app->post('/user_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
//     $bookingTime = intval($data['id_time']); // Assuming id_time is an integer representing the time slot

//     // ตั้งค่า timezone ให้เป็นเวลาของประเทศไทย
//     date_default_timezone_set('Asia/Bangkok');
//     $currentDate = date('Y-m-d');
//     $presentDate = date('Y-m-d'); // ใช้วันที่ปัจจุบัน
//     $presentTime = date('H:i:s'); // ใช้เวลาปัจจุบัน

//     // Check if the member has an existing booking on or after the current date
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date >= ? AND id_member = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('si', $currentDate, $data['id_member']);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองปัจจุบันที่ยังไม่ผ่านไป"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Check if there is already a booking for the selected date, stadium, and time
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Insert new booking with current date and time
//     $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member, present_date, present_time) VALUES (?, 1, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('siiiiss', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member'], $presentDate, $presentTime);
//     $stmt->execute();

//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });

//จองกี่ครั้งก็ได้ เเต่ได้วันละ 1 ครั้ง
// $app->post('/user_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
//     $bookingTime = intval($data['id_time']); // Assuming id_time is an integer representing the time slot
//     date_default_timezone_set('Asia/Bangkok');
//     $currentDate = date('Y-m-d');
//     $presentDate = date('Y-m-d'); // ใช้วันที่ปัจจุบัน
//     $presentTime = date('H:i:s'); // ใช้เวลาปัจจุบัน
//     // Check if the member has already booked on the selected date and stadium
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_member = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('si', $bookingDate, $data['id_member']);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในวันที่เดียวกัน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Check if there is already a booking for the selected date, stadium, and time
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Insert new booking
//     $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member, present_date, present_time) VALUES (?, 1, ?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('siiiiss', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member'], $presentDate, $presentTime);
//     $stmt->execute();

//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });

// $app->get('/report_booking_month', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT COUNT(b.id_booking) as count, DATE_FORMAT(b.booking_date, "%Y-%m") as month_year, s.stadium_name, n.number_name
//             FROM tb_booking b
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             GROUP BY month_year, b.id_stadium, b.id_number
//             ORDER BY month_year ASC, count DESC';
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     while($row = $result->fetch_assoc()) {
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/report_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT b.id_booking, b.booking_date, s.stadium_name, n.number_name, t.time, m.fname, m.lname, b.booking_status, t.id_time, b.id_stadium, b.id_number
//     FROM tb_booking b
//     INNER JOIN tb_member m ON b.id_member = m.id_member
//     INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//     INNER JOIN tb_number n ON b.id_number = n.id_number
//     INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//     ORDER BY b.id_booking DESC';

//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });
// $app->get('/report_booking/{id_booking}', function (Request $request, Response $response, array $args) {
//     $conn = $GLOBALS['conn'];

//     // รับ id_booking จาก URL
//     $id_booking = $args['id_booking'];

//     // สร้าง SQL query พร้อมเงื่อนไขกรอง id_booking
//     $sql = 'SELECT 
//                 b.id_booking, 
//                 b.booking_date, 
//                 s.stadium_name, 
//                 n.number_name, 
//                 t.time, 
//                 m.fname, 
//                 m.lname, 
//                 CASE 
//                     WHEN b.booking_status = 1 THEN "จอง"
//                     WHEN b.booking_status = 2 THEN "เข้าใช้งาน"
//                     ELSE "สถานะไม่ทราบ"
//                 END AS booking_status_label, 
//                 t.id_time, 
//                 b.id_stadium, 
//                 b.id_number
//             FROM tb_booking b
//             INNER JOIN tb_member m ON b.id_member = m.id_member
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.id_booking = ?
//             ORDER BY b.id_booking DESC';

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id_booking); // bind parameter เพื่อป้องกัน SQL injection
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     while($row = $result->fetch_assoc()){
//         array_push($data, $row);
//     }

//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/report_booking_all', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT COUNT(b.id_booking) as count, b.booking_date, s.stadium_name, n.number_name, t.time
//             FROM tb_booking b
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//                         INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             GROUP BY b.booking_date, b.id_stadium, b.id_number
//             ORDER BY b.booking_date ASC, count DESC';
    
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->post('/user_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Set timezone to Asia/Bangkok
//     $timezone = new DateTimeZone('Asia/Bangkok');

//     $bookingDate = new DateTime($data['booking_date'], $timezone);
//     $bookingTimeSlot = intval($data['id_time']);

//     // Define time slot mapping
//     $timeSlotMapping = [
//         1 => '08:00:00',
//         2 => '09:00:00',
//         3 => '10:00:00',
//         4 => '11:00:00',
//         5 => '12:00:00',
//         6 => '13:00:00',
//         7 => '14:00:00',
//         8 => '15:00:00',
//     ];

//     // Combine booking date and time slot to create a DateTime object for booking
//     $bookingDateTime = new DateTime($data['booking_date'] . ' ' . $timeSlotMapping[$bookingTimeSlot], $timezone);

//     // Check if there is already a booking on the same date, stadium, and time slot
//     $existingBookingQuery = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//     $stmtExisting = $conn->prepare($existingBookingQuery);
//     $stmtExisting->bind_param('siii', $bookingDate->format('Y-m-d'), $data['id_stadium'], $data['id_number'], $bookingTimeSlot);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         // Fetch the existing booking details
//         $existingBooking = $resultExisting->fetch_assoc();
//         $existingBookingDateTime = new DateTime($existingBooking['booking_date'] . ' ' . $timeSlotMapping[$existingBooking['id_time']], $timezone);
        
//         // Check if the existing booking time has passed
//         $currentDateTime = new DateTime('now', $timezone);
//         if ($existingBookingDateTime > $currentDateTime) {
//             $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//             $response->getBody()->write(json_encode($errorResponse));
//             return $response
//                 ->withHeader('Content-Type', 'application/json')
//                 ->withStatus(400);
//         }
//     }

//     // Insert new booking
//     $sql = "INSERT INTO tb_booking (booking_date, booking_time, booking_status, id_stadium, id_number, id_time, id_member) VALUES (?, ?, 1, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('ssiiii', $bookingDate->format('Y-m-d'), $timeSlotMapping[$bookingTimeSlot], $data['id_stadium'], $data['id_number'], $bookingTimeSlot, $data['id_member']);
//     $stmt->execute();

//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });

// ล่าสุด //
// $app->post('/user_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
//     $bookingTime = intval($data['id_time']); // Assuming id_time is an integer representing the time slot

//     // ตั้งค่า timezone ให้เป็นเวลาของประเทศไทย
//     date_default_timezone_set('Asia/Bangkok');
//     $currentDate = date('Y-m-d');

//     // Check if the member has an existing booking on or after the current date
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date >= ? AND id_member = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('si', $currentDate, $data['id_member']);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองปัจจุบันที่ยังไม่ผ่านไป"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Check if there is already a booking for the selected date, stadium, and time
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Insert new booking
//     $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member) VALUES (?, 1, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
//     $stmt->execute();

//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });


// $app->post('/user_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
//     $bookingTime = intval($data['id_time']); // Assuming id_time is an integer representing the time slot

//     // Check if the member has already booked on the selected date and stadium
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_member = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('si', $bookingDate, $data['id_member']);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในวันที่เดียวกัน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Check if there is already a booking for the selected date, stadium, and time
//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     // Insert new booking
//     $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member) VALUES (?, 1, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
//     $stmt->execute();

//     if ($stmt->affected_rows > 0) {
//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } else {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });

// 
// $app->get('/report_bookinguser/{id_member}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
    
//     // รับค่า id_member จากพารามิเตอร์ของ URL
//     $id_member = $args['id_member'];

//     // ปรับปรุง SQL query เพื่อให้กรองข้อมูลตาม id_member และ booking_status = 1
//     $sql = 'SELECT b.id_booking, b.booking_date, b.present_date, b.present_time, s.stadium_name, n.number_name, t.time, m.fname, m.lname
//             FROM tb_booking b
//             INNER JOIN tb_member m ON b.id_member = m.id_member
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.id_member = ? AND b.booking_status = 1
//             ORDER BY b.present_date DESC, b.present_time DESC
//             LIMIT 1';
    
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/report_bookinguser/{id_member}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
    
//     // รับค่า id_member จากพารามิเตอร์ของ URL
//     $id_member = $args['id_member'];

//     // ปรับปรุง SQL query เพื่อให้กรองข้อมูลตาม id_member
//     $sql = 'SELECT b.id_booking, b.booking_date, b.present_date, b.present_time, s.stadium_name, n.number_name, t.time, m.fname, m.lname
//             FROM tb_booking b
//             INNER JOIN tb_member m ON b.id_member = m.id_member
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.id_member = ?
//             ORDER BY b.present_date DESC, b.present_time DESC
//             LIMIT 1';
    
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/report_bookinguser/{id_member}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
    
//     // รับค่า id_member จากพารามิเตอร์ของ URL
//     $id_member = $args['id_member'];

//     // ปรับปรุง SQL query เพื่อให้กรองข้อมูลตาม id_member และเรียงลำดับตาม present_date และ present_time
//     $sql = 'SELECT b.id_booking, b.booking_date, b.present_date, b.present_time, s.stadium_name, n.number_name, t.time, m.fname, m.lname
//             FROM tb_booking b
//             INNER JOIN tb_member m ON b.id_member = m.id_member
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.id_member = ?
//             ORDER BY b.present_date DESC, b.present_time DESC
//             LIMIT 1';
    
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });


// $app->get('/report_bookinguser/{id_member}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
    
//     // รับค่า id_member จากพารามิเตอร์ของ URL
//     $id_member = $args['id_member'];

//     // ปรับปรุง SQL query เพื่อให้กรองข้อมูลตาม id_member
//     $sql = 'SELECT b.id_booking, b.booking_date, s.stadium_name, n.number_name, t.time, m.fname, m.lname
//             FROM tb_booking b
//             INNER JOIN tb_member m ON b.id_member = m.id_member
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.id_member = ?
//             ORDER BY b.id_booking DESC
//             LIMIT 1';
    
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = array();
//     foreach($result as $row){
//         array_push($data, $row);
//     }
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->post('/admin_booking', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     $data = $request->getParsedBody();

//     if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
//         $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

//     $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
//     $bookingTimes = explode(',', $data['id_time']); // Convert string to array of integers representing the time slots

//     // Begin transaction
//     $conn->begin_transaction();

//     try {
//         // Insert new bookings
//         $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member) VALUES (?, 1, ?, ?, ?, ?)";
//         $stmt = $conn->prepare($sql);

//         foreach ($bookingTimes as $bookingTime) {
//             // Check if there is already a booking for the selected date, stadium, and time
//             $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
//             $stmtExisting = $conn->prepare($existingBooking);
//             $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
//             $stmtExisting->execute();
//             $resultExisting = $stmtExisting->get_result();

//             if ($resultExisting->num_rows > 0) {
//                 // Rollback the transaction
//                 $conn->rollback();

//                 $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//                 $response->getBody()->write(json_encode($errorResponse));
//                 return $response
//                     ->withHeader('Content-Type', 'application/json')
//                     ->withStatus(400);
//             }

//             // Insert the booking
//             $stmt->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
//             $stmt->execute();
//         }

//         // Commit the transaction
//         $conn->commit();

//         // Get the last inserted id_booking
//         $lastIdBooking = $conn->insert_id;

//         $responseBody = ["message" => "จองสนามกีฬาสำเร็จ", "id_booking" => $lastIdBooking];
//         $response->getBody()->write(json_encode($responseBody));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(201);
//     } catch (Exception $e) {
//         // Rollback the transaction
//         $conn->rollback();

//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(500);
//     }
// });
