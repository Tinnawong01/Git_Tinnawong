<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// เรียกข้อมูลช่วงเวลามาแสดงในหน้า booking-time
$app->get('/time_slot', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT tb_time_slot.id_time, tb_time_slot.time 
            FROM tb_time_slot';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

    if ($data) {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "Time slots not found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});
//นำข้อมูลการจองมาแสดงทั้งหมดใน tb_booking booking-time
$app->get('/tb_booking_show', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT * FROM tb_booking';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

    if ($data) {
        $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "Time slots not found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});

// จำนวนการจองสนามทั้งหมด admin-main
$app->get('/booking_count', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // SQL Query เพื่อคำนวณจำนวนการจองทั้งหมดที่ booking_admin != 1
    $sql = 'SELECT COUNT(id_booking) AS total_bookings FROM tb_booking WHERE booking_admin != 1';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // ดึงข้อมูลที่นับได้

    if ($data) {
        $responseData = [
            'total_bookings' => (int) $data['total_bookings'] // จำนวนการจองทั้งหมดที่ไม่ได้มาจาก admin
        ];
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No bookings found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});

// จำนวนการเข้าใช้งานสนาม โดยผ่านการยืนยันกับเจ้าหน้าที่ admin-main
$app->get('/bookinguse_count', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // SQL Query เพื่อคำนวณจำนวนการจองทั้งหมดที่มี booking_status = 2
    $sql = 'SELECT COUNT(id_booking) AS total_bookinguse FROM tb_booking WHERE booking_status = 2';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // ดึงข้อมูลที่นับได้

    if ($data) {
        $responseData = [
            'total_bookinguse' => (int) $data['total_bookinguse'] // จำนวนการจองทั้งหมดที่มี booking_status = 2
        ];
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No bookings found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});

// จำนวนสมาชิกที่สมัครเข้ามาใช้งานในระบบ admin-main
$app->get('/member_count', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // SQL Query เพื่อคำนวณจำนวนสมาชิกทั้งหมด
    $sql = 'SELECT COUNT(id_member) AS total_members FROM tb_member';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc(); // ดึงข้อมูลที่นับได้

    if ($data) {
        $responseData = [
            'total_members' => (int) $data['total_members'] // จำนวนสมาชิกทั้งหมด
        ];
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No members found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});
//จำนวนประเภทสนามและหมายเลขสนามทั้งหมด admin-main
$app->get('/stadium_and_number_count', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // SQL Query เพื่อคำนวณจำนวนสนามกีฬาทั้งหมดจาก tb_stadium
    $sqlStadium = 'SELECT COUNT(id_stadium) AS total_stadiums FROM tb_stadium';
    $stmtStadium = $conn->prepare($sqlStadium);
    $stmtStadium->execute();
    $resultStadium = $stmtStadium->get_result();
    $dataStadium = $resultStadium->fetch_assoc(); // ดึงข้อมูลที่นับได้

    // SQL Query เพื่อคำนวณจำนวนหมายเลขสนามจาก tb_number
    $sqlNumber = 'SELECT COUNT(id_number) AS total_numbers FROM tb_number';
    $stmtNumber = $conn->prepare($sqlNumber);
    $stmtNumber->execute();
    $resultNumber = $stmtNumber->get_result();
    $dataNumber = $resultNumber->fetch_assoc(); // ดึงข้อมูลที่นับได้

    if ($dataStadium && $dataNumber) {
        $responseData = [
            'total_stadiums' => (int) $dataStadium['total_stadiums'], // จำนวนสนามกีฬาทั้งหมด
            'total_numbers' => (int) $dataNumber['total_numbers'] // จำนวนหมายเลขสนามทั้งหมด
        ];
        $response->getBody()->write(json_encode($responseData, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
    } else {
        $errorResponse = ["message" => "No data found"];
        $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
        return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
    }
});
// $app->get('/graph_booking/{id_stadium}/{year}', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับค่าพารามิเตอร์ id_stadium และ year จาก URL
//     $idStadium = isset($args['id_stadium']) ? (int)$args['id_stadium'] : 0;
//     $year = isset($args['year']) ? (int)$args['year'] : date('Y');

//     // เตรียมคำสั่ง SQL เพื่อกรองข้อมูลตาม id_stadium, year และ booking_status
//     $sql = "
//         SELECT 
//             DATE_FORMAT(booking_date, '%m') AS month,
//             COUNT(CASE WHEN booking_status = 2 THEN 1 END) AS booking_count,
//             COUNT(CASE WHEN booking_status = 1 THEN 1 END) AS booking_total
//         FROM tb_booking
//         WHERE id_stadium = ? AND YEAR(booking_date) = ?
//         GROUP BY month
//         ORDER BY month
//     ";

//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param('ii', $idStadium, $year); // ผูกพารามิเตอร์ id_stadium และ year กับคำสั่ง SQL
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
//             'booking_count' => 0,
//             'booking_total' => 0
//         ];
//     }

//     // แปลงข้อมูลที่ได้รับจากฐานข้อมูล
//     foreach ($data as $row) {
//         $month = $row['month']; // ดึงเดือนจากข้อมูล
//         $resultData[$month]['booking_count'] = $row['booking_count']; // จำนวนการจองที่มี booking_status = 2
//         $resultData[$month]['booking_total'] = $row['booking_total']; // จำนวนการจองที่มี booking_status = 1
//     }

//     // ส่งข้อมูลในรูปแบบ JSON
//     $response->getBody()->write(json_encode(array_values($resultData), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
// });

// $app->get('/tb_number_show', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT * FROM tb_number';
//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC); // ดึงข้อมูลทั้งหมด

//     if ($data) {
//         $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
//     } else {
//         $errorResponse = ["message" => "Time slots not found"];
//         $response->getBody()->write(json_encode($errorResponse, JSON_UNESCAPED_UNICODE));
//         return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(404);
//     }
// });
$app->get('/graph_booking_newmonth/{month}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับค่าพารามิเตอร์เดือนจาก URL
    $selectedMonth = isset($args['month']) ? $args['month'] : date('Y-m');

    // SQL Query: ดึงข้อมูลการจองตามเดือนและแยกตามสนามกีฬา
    $sql = "
        SELECT 
            ts.id_stadium,  -- รหัสสนามกีฬา
            ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
            COUNT(CASE WHEN tb.booking_status = 2 THEN 1 END) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง (status = 2)
            COUNT(CASE WHEN tb.booking_status IN (1, 2) THEN 1 END) AS booking_total  -- การจองทั้งหมดที่มี status 1 หรือ 2
        FROM tb_stadium ts
        LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium 
            AND DATE_FORMAT(tb.booking_date, '%Y-%m') = ?  -- ใช้ LEFT JOIN เพื่อให้แสดงทุกสนาม
        GROUP BY ts.id_stadium, ts.stadium_name
        ORDER BY ts.stadium_name
    ";

    // เตรียม Statement และผูกพารามิเตอร์
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return $response->withStatus(500)->write('Database error: ' . $conn->error);
    }

    $stmt->bind_param('s', $selectedMonth);  // ผูกเดือนที่เลือกกับ SQL

    // ดำเนินการ SQL และรับผลลัพธ์
    if (!$stmt->execute()) {
        return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
    }

    // ดึงข้อมูลทั้งหมดเป็น Array
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // ปิด Statement
    $stmt->close();

    // ส่งข้อมูลในรูปแบบ JSON
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});


// $app->get('/graph_booking_sum[/{filterValue}]', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับพารามิเตอร์ filterValue จาก URL
//     $filterValue = isset($args['filterValue']) ? $args['filterValue'] : null;

//     // กำหนดเงื่อนไข WHERE และพารามิเตอร์
//     $whereClause = '';
//     $params = [];

//     if ($filterValue) {
//         if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterValue)) {
//             // กรองตามวันที่ (YYYY-MM-DD)
//             $whereClause = "AND tb.booking_date = ?";
//             $params[] = $filterValue;
//         } elseif (preg_match('/^\d{4}-\d{2}$/', $filterValue)) {
//             // กรองตามเดือน (YYYY-MM)
//             $whereClause = "AND DATE_FORMAT(tb.booking_date, '%Y-%m') = ?";
//             $params[] = $filterValue;
//         }
//     }

//     // SQL Query: ดึงข้อมูลการจอง
//     $sql = "
//         SELECT 
//             ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
//             IFNULL(COUNT(CASE WHEN tb.booking_status = 2 THEN 1 END), 0) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง
//             IFNULL(COUNT(CASE WHEN tb.booking_status IN (1, 2) THEN 1 END), 0) AS booking_total  -- จำนวนการจองทั้งหมด
//         FROM tb_stadium ts
//         LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium 
//         $whereClause  -- เงื่อนไขการกรอง
//         GROUP BY ts.stadium_name
//         ORDER BY ts.stadium_name
//     ";

//     // เตรียม Statement
//     $stmt = $conn->prepare($sql);
//     if ($stmt === false) {
//         return $response->withStatus(500)->write('Database error: ' . $conn->error);
//     }

//     // ผูกพารามิเตอร์ถ้ามี
//     if (!empty($params)) {
//         $stmt->bind_param(str_repeat('s', count($params)), ...$params);
//     }

//     // ดำเนินการ SQL และรับผลลัพธ์
//     if (!$stmt->execute()) {
//         return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
//     }

//     // ดึงข้อมูลทั้งหมดเป็น Array
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC);

//     // ปิด Statement
//     $stmt->close();

//     // ส่งข้อมูลในรูปแบบ JSON
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });
// $app->get('/graph_booking_sum[/{filterValue}]', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // รับพารามิเตอร์ filterValue จาก URL
//     $filterValue = isset($args['filterValue']) ? $args['filterValue'] : null;

//     // กำหนดเงื่อนไข WHERE และพารามิเตอร์
//     $whereClause = '';
//     $params = [];

//     if ($filterValue) {
//         if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterValue)) {
//             // กรองตามวันที่ (YYYY-MM-DD)
//             $whereClause = "AND tb.booking_date COLLATE utf8mb4_unicode_ci = ?";
//             $params[] = $filterValue;
//         } elseif (preg_match('/^\d{4}-\d{2}$/', $filterValue)) {
//             // กรองตามเดือน (YYYY-MM)
//             $whereClause = "AND DATE_FORMAT(tb.booking_date, '%Y-%m') COLLATE utf8mb4_unicode_ci = ?";
//             $params[] = $filterValue;
//         }
//     }

//     // SQL Query: ดึงข้อมูลการจอง
//     $sql = "
//         SELECT 
//             ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
//             IFNULL(COUNT(CASE WHEN tb.booking_status = 2 THEN 1 END), 0) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง
//             IFNULL(COUNT(CASE WHEN tb.booking_status IN (1, 2) THEN 1 END), 0) AS booking_total  -- จำนวนการจองทั้งหมด
//         FROM tb_stadium ts
//         LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium 
//         $whereClause  -- เงื่อนไขการกรอง
//         GROUP BY ts.stadium_name
//         ORDER BY ts.stadium_name
//     ";

//     // เตรียม Statement
//     $stmt = $conn->prepare($sql);
//     if ($stmt === false) {
//         return $response->withStatus(500)->write('Database error: ' . $conn->error);
//     }

//     // ผูกพารามิเตอร์ถ้ามี
//     if (!empty($params)) {
//         $stmt->bind_param(str_repeat('s', count($params)), ...$params);
//     }

//     // ดำเนินการ SQL และรับผลลัพธ์
//     if (!$stmt->execute()) {
//         return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
//     }

//     // ดึงข้อมูลทั้งหมดเป็น Array
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC);

//     // ปิด Statement
//     $stmt->close();

//     // ส่งข้อมูลในรูปแบบ JSON
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });

$app->get('/graph_booking_sum[/{filterValue}]', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับพารามิเตอร์ filterValue จาก URL
    $filterValue = isset($args['filterValue']) ? $args['filterValue'] : null;

    // กำหนดเงื่อนไข WHERE และพารามิเตอร์
    $whereClause = '';
    $params = [];

    if ($filterValue) {
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $filterValue)) {
            // กรองตามวันที่ (YYYY-MM-DD)
            $whereClause = "WHERE tb.booking_date COLLATE utf8mb4_unicode_ci = ?";
            $params[] = $filterValue;
        } elseif (preg_match('/^\d{4}-\d{2}$/', $filterValue)) {
            // กรองตามเดือน (YYYY-MM)
            $whereClause = "WHERE DATE_FORMAT(tb.booking_date, '%Y-%m') COLLATE utf8mb4_unicode_ci = ?";
            $params[] = $filterValue;
        }
    }
    

    // SQL Query: ดึงข้อมูลการจอง
    $sql = "
    SELECT 
        ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
        IFNULL(SUM(CASE WHEN tb.booking_status = 2 THEN 1 ELSE 0 END), 0) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง
        IFNULL(SUM(CASE WHEN tb.booking_status IN (1, 2) THEN 1 ELSE 0 END), 0) AS booking_total  -- จำนวนการจองทั้งหมด
    FROM tb_stadium ts
    LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium 
    $whereClause  -- เงื่อนไขการกรอง
    GROUP BY ts.stadium_name
    ORDER BY ts.stadium_name
";



    // เตรียม Statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return $response->withStatus(500)->write('Database error: ' . $conn->error);
    }

    // ผูกพารามิเตอร์ถ้ามี
    if (!empty($params)) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    }

    // ดำเนินการ SQL และรับผลลัพธ์
    if (!$stmt->execute()) {
        return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
    }

    // ดึงข้อมูลทั้งหมดเป็น Array
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // ปิด Statement
    $stmt->close();

    // ส่งข้อมูลในรูปแบบ JSON
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});

// $app->get('/graph_booking_sum[/{filterType}[/{filterValue}]]', function (Request $request, Response $response, $args) {
//     $conn = $GLOBALS['conn'];

//     // ตรวจสอบพารามิเตอร์ที่ได้รับจาก URL
//     $filterType = isset($args['filterType']) ? $args['filterType'] : 'all';  // ประเภทการกรอง ('date', 'month', 'all')
//     $filterValue = isset($args['filterValue']) ? $args['filterValue'] : null; // ค่ากรอง เช่น '2024-10-01' หรือ '2024-10'

//     // กำหนดเงื่อนไขการกรองตาม filterType
//     $whereClause = '';
//     $params = [];  // เก็บพารามิเตอร์ที่จะใช้ใน bind_param

//     if ($filterType === 'date' && $filterValue) {
//         $whereClause = "AND tb.booking_date = ?";
//         $params[] = $filterValue;  // ตัวอย่าง: '2024-10-01'
//     } elseif ($filterType === 'month' && $filterValue) {
//         $whereClause = "AND DATE_FORMAT(tb.booking_date, '%Y-%m') = ?";
//         $params[] = $filterValue;  // ตัวอย่าง: '2024-10'
//     }

//     // SQL Query: ดึงข้อมูลการจองตามเงื่อนไขที่กำหนด
//     $sql = "
//         SELECT 
//             ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
//             IFNULL(COUNT(CASE WHEN tb.booking_status = 2 THEN 1 END), 0) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง (status = 2)
//             IFNULL(COUNT(CASE WHEN tb.booking_status IN (1, 2) THEN 1 END), 0) AS booking_total  -- การจองทั้งหมด (status = 1 หรือ 2)
//         FROM tb_stadium ts
//         LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium 
//         $whereClause  -- เพิ่มเงื่อนไขการกรอง
//         GROUP BY ts.stadium_name
//         ORDER BY ts.stadium_name
//     ";

//     // เตรียม Statement
//     $stmt = $conn->prepare($sql);
//     if ($stmt === false) {
//         return $response->withStatus(500)->write('Database error: ' . $conn->error);
//     }

//     // ผูกพารามิเตอร์ (ถ้ามี)
//     if (!empty($params)) {
//         $stmt->bind_param(str_repeat('s', count($params)), ...$params);
//     }

//     // ดำเนินการ SQL และรับผลลัพธ์
//     if (!$stmt->execute()) {
//         return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
//     }

//     // ดึงข้อมูลทั้งหมดเป็น Array
//     $result = $stmt->get_result();
//     $data = $result->fetch_all(MYSQLI_ASSOC);

//     // ปิด Statement
//     $stmt->close();

//     // ส่งข้อมูลในรูปแบบ JSON
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });



$app->get('/graph_booking_new/{date}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับค่าพารามิเตอร์วันที่จาก URL
    $selectedDate = isset($args['date']) ? $args['date'] : date('Y-m-d');

    // SQL Query: ดึงข้อมูลการจองตามวันที่และแยกตามสนามกีฬา
    $sql = "
        SELECT 
            ts.stadium_name AS stadium_name,  -- ชื่อสนามกีฬา
            COUNT(CASE WHEN tb.booking_status = 2 THEN 1 END) AS booking_count,  -- จำนวนการจองที่ใช้งานจริง (status = 2)
            COUNT(CASE WHEN tb.booking_status IN (1, 2) THEN 1 END) AS booking_total  -- การจองทั้งหมดที่มี status 1 หรือ 2
        FROM tb_stadium ts
        LEFT JOIN tb_booking tb ON tb.id_stadium = ts.id_stadium AND tb.booking_date = ?  -- ใช้ LEFT JOIN เพื่อให้แสดงทุกสนาม
        GROUP BY ts.stadium_name
        ORDER BY ts.stadium_name
    ";

    // เตรียม Statement และผูกพารามิเตอร์
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        return $response->withStatus(500)->write('Database error: ' . $conn->error);
    }

    $stmt->bind_param('s', $selectedDate);  // ผูกวันที่ที่เลือกกับ SQL

    // ดำเนินการ SQL และรับผลลัพธ์
    if (!$stmt->execute()) {
        return $response->withStatus(500)->write('Execution error: ' . $stmt->error);
    }

    // ดึงข้อมูลทั้งหมดเป็น Array
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // ปิด Statement
    $stmt->close();

    // ส่งข้อมูลในรูปแบบ JSON
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});




//ข้อมูลแสดงในกราฟ admin-main
$app->get('/graph_booking/{id_stadium}/{year}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับค่าพารามิเตอร์ id_stadium และ year จาก URL
    $idStadium = isset($args['id_stadium']) ? (int) $args['id_stadium'] : 0;
    $year = isset($args['year']) ? (int) $args['year'] : date('Y');

    // เตรียมคำสั่ง SQL เพื่อกรองข้อมูลตาม id_stadium, year และ booking_status
    $sql = "
        SELECT 
            DATE_FORMAT(booking_date, '%m') AS month,
            COUNT(CASE WHEN booking_status = 2 THEN 1 END) AS booking_count,
            COUNT(CASE WHEN booking_status IN (1, 2) THEN 1 END) AS booking_total
        FROM tb_booking
        WHERE id_stadium = ? AND YEAR(booking_date) = ?
        GROUP BY month
        ORDER BY month
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $idStadium, $year); // ผูกพารามิเตอร์ id_stadium และ year กับคำสั่ง SQL
    $stmt->execute();
    $result = $stmt->get_result();

    // ดึงข้อมูลทั้งหมด
    $data = $result->fetch_all(MYSQLI_ASSOC);

    // แปลงชื่อเดือนเป็นภาษาไทย
    $monthNamesThai = [
        '01' => 'มกราคม',
        '02' => 'กุมภาพันธ์',
        '03' => 'มีนาคม',
        '04' => 'เมษายน',
        '05' => 'พฤษภาคม',
        '06' => 'มิถุนายน',
        '07' => 'กรกฎาคม',
        '08' => 'สิงหาคม',
        '09' => 'กันยายน',
        '10' => 'ตุลาคม',
        '11' => 'พฤศจิกายน',
        '12' => 'ธันวาคม'
    ];

    // สร้าง array สำหรับเก็บข้อมูลทั้งหมด 12 เดือน
    $resultData = [];
    foreach ($monthNamesThai as $key => $monthName) {
        $resultData[$key] = [
            'month' => $monthName,
            'booking_count' => 0,
            'booking_total' => 0
        ];
    }

    // แปลงข้อมูลที่ได้รับจากฐานข้อมูล
    foreach ($data as $row) {
        $month = $row['month']; // ดึงเดือนจากข้อมูล
        $resultData[$month]['booking_count'] = $row['booking_count']; // จำนวนการจองที่มี booking_status = 2
        $resultData[$month]['booking_total'] = $row['booking_total']; // จำนวนการจองทั้งหมดที่มี booking_status = 1 หรือ 2
    }

    // ส่งข้อมูลในรูปแบบ JSON
    $response->getBody()->write(json_encode(array_values($resultData), JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

// เรียกข้อมูลประวัติการจองของสมาชิกมาแสดง admin-history
$app->get('/report_booking', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT 
                b.id_booking, 
                b.booking_date, 
                s.stadium_name, 
                n.number_name, 
                t.time, 
                m.fname, 
                m.lname, 
                CASE 
                    WHEN b.booking_status = 1 THEN "จอง"
                    WHEN b.booking_status = 2 THEN "เข้าใช้งาน"
                    ELSE "สถานะไม่ทราบ"
                END AS booking_status_label, 
                t.id_time, 
                b.id_stadium, 
                b.id_number
            FROM tb_booking b
            INNER JOIN tb_member m ON b.id_member = m.id_member
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            INNER JOIN tb_number n ON b.id_number = n.id_number
            INNER JOIN tb_time_slot t ON b.id_time = t.id_time
            WHERE b.booking_admin != 1
            ORDER BY b.id_booking DESC';

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

$app->get('/report_booking/{id_booking}', function (Request $request, Response $response, array $args) {
    $conn = $GLOBALS['conn'];

    // รับ id_booking จาก URL
    $id_booking = $args['id_booking'];

    // สร้าง SQL query พร้อมเงื่อนไขกรอง id_booking และเรียงตาม present_date, present_time
    $sql = 'SELECT 
                b.id_booking, 
                b.booking_date, 
                b.present_date,
                b.present_time,
                s.stadium_name, 
                n.number_name, 
                t.time, 
                m.fname, 
                m.lname, 
                CASE 
                    WHEN b.booking_status = 1 THEN "จอง"
                    WHEN b.booking_status = 2 THEN "เข้าใช้งาน"
                    ELSE "สถานะไม่ทราบ"
                END AS booking_status_label, 
                t.id_time, 
                b.id_stadium, 
                b.id_number
            FROM tb_booking b
            INNER JOIN tb_member m ON b.id_member = m.id_member
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            INNER JOIN tb_number n ON b.id_number = n.id_number
            INNER JOIN tb_time_slot t ON b.id_time = t.id_time
            WHERE b.id_booking = ?
            ORDER BY b.present_date DESC, b.present_time DESC';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_booking); // bind parameter เพื่อป้องกัน SQL injection
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }

    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

//ยืนยันการเข้าใช้งานของสมาชิก admin-approve
$app->put('/approve_booking/{id_booking}', function (Request $request, Response $response, array $args) {
    $conn = $GLOBALS['conn'];

    // รับ id_booking จาก URL
    $id_booking = $args['id_booking'];

    // สร้าง SQL query สำหรับอัปเดตสถานะการจอง
    $sql = 'UPDATE tb_booking 
            SET booking_status = 2 
            WHERE id_booking = ?';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_booking); // bind parameter เพื่อป้องกัน SQL injection

    if ($stmt->execute()) {
        $response->getBody()->write(json_encode(['status' => 'success'], JSON_UNESCAPED_UNICODE));
    } else {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Failed to approve booking'], JSON_UNESCAPED_UNICODE));
    }

    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

//เรียกข้อมูลจำนวนการจองของผู้ใช้งานทั้งหมด แบบเเยกหมายเลขสนาม admin-report
// $app->get('/report_booking_all', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];
//     $sql = 'SELECT COUNT(b.id_booking) as count, b.booking_date, s.stadium_name, n.number_name
//             FROM tb_booking b
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             -- INNER JOIN tb_time_slot t ON b.id_time = t.id_time
//             WHERE b.booking_status = 2
//             GROUP BY b.booking_date, b.id_stadium, b.id_number
//              ORDER BY b.booking_date DESC, count DESC';

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

//เรียกข้อมูลจำนวนการจองของผู้ใช้งานทั้งหมด แบบรวมกันไม่เเยกหมายเลข admin-report
$app->get('/report_booking_all', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT COUNT(b.id_booking) AS count, b.booking_date, s.stadium_name
            FROM tb_booking b
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            WHERE b.booking_status = 2
            GROUP BY b.booking_date, b.id_stadium
            ORDER BY b.booking_date DESC, count DESC';

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});


//เรียกข้อมูลจำนวนการจองของผู้ใช้งานในเดือนๆต่างๆ แบบรวมกันไม่เเยกหมายเลข admin-report-month
$app->get('/report_booking_month', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];

    // SQL query ที่ปรับปรุง: ตัด tb_number ออก และกรองเฉพาะ booking_status = 2
    $sql = 'SELECT COUNT(b.id_booking) AS count, 
                   DATE_FORMAT(b.booking_date, "%Y-%m") AS month_year, 
                   s.stadium_name
            FROM tb_booking b
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            WHERE b.booking_status = 2  -- กรองเฉพาะสถานะ booking_status = 2
            GROUP BY month_year, b.id_stadium
            ORDER BY month_year ASC, count DESC';

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // จัดเก็บผลลัพธ์ลงใน array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        array_push($data, $row);
    }

    // ส่งข้อมูลเป็น JSON response
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});


$app->get('/report_booking_dayall', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];

    // Query สำหรับนับการจองตามวันที่
    $sqlByDate = 'SELECT COUNT(b.id_booking) AS count, 
                         b.booking_date, 
                         s.stadium_name
                  FROM tb_booking b
                  INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
                  WHERE b.booking_status = 2
                  GROUP BY b.booking_date, b.id_stadium
                  ORDER BY b.booking_date DESC, count DESC';

    // Query สำหรับนับการจองตามเดือน
    $sqlByMonth = 'SELECT COUNT(b.id_booking) AS count, 
                          DATE_FORMAT(b.booking_date, "%Y-%m") AS month_year, 
                          s.stadium_name
                   FROM tb_booking b
                   INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
                   WHERE b.booking_status = 2
                   GROUP BY month_year, b.id_stadium
                   ORDER BY month_year ASC, count DESC';

    // Execute Query แรกสำหรับนับตามวันที่
    $stmtByDate = $conn->prepare($sqlByDate);
    $stmtByDate->execute();
    $resultByDate = $stmtByDate->get_result();
    $dataByDate = [];
    while ($row = $resultByDate->fetch_assoc()) {
        array_push($dataByDate, $row);
    }

    // Execute Query ที่สองสำหรับนับตามเดือน
    $stmtByMonth = $conn->prepare($sqlByMonth);
    $stmtByMonth->execute();
    $resultByMonth = $stmtByMonth->get_result();
    $dataByMonth = [];
    while ($row = $resultByMonth->fetch_assoc()) {
        array_push($dataByMonth, $row);
    }

    // จัดรวมข้อมูลทั้งสองส่วน
    $data = [
        'byDate' => $dataByDate,
        'byMonth' => $dataByMonth
    ];

    // ส่งข้อมูลเป็น JSON response
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
        ->withHeader('Content-Type', 'application/json; charset=utf-8')
        ->withStatus(200);
});


//เรียกข้อมูลจำนวนการจองของผู้ใช้งานในเดือนๆต่างๆ แบบเเยกหมายเลขสนาม admin-report-month
// $app->get('/report_booking_month', function (Request $request, Response $response) {
//     $conn = $GLOBALS['conn'];

//     // SQL query ที่ปรับปรุง: รวม tb_number และแยกตาม id_number
//     $sql = 'SELECT COUNT(b.id_booking) AS count, 
//                    DATE_FORMAT(b.booking_date, "%Y-%m") AS month_year, 
//                    s.stadium_name, 
//                    n.number_name
//             FROM tb_booking b
//             INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
//             INNER JOIN tb_number n ON b.id_number = n.id_number
//             WHERE b.booking_status = 2  -- กรองเฉพาะสถานะ booking_status = 2
//             GROUP BY month_year, b.id_stadium, b.id_number
//             ORDER BY month_year ASC, count DESC';

//     $stmt = $conn->prepare($sql);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     // จัดเก็บผลลัพธ์ลงใน array
//     $data = array();
//     while ($row = $result->fetch_assoc()) {
//         array_push($data, $row);
//     }

//     // ส่งข้อมูลเป็น JSON response
//     $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
//     return $response
//         ->withHeader('Content-Type', 'application/json; charset=utf-8')
//         ->withStatus(200);
// });

//เรียกข้อมูลจำนวนการจองของผู้ใช้งานในช่วงเวลาต่างๆ admin-report-time
$app->get('/report_booking_time', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT COUNT(b.id_booking) as count, b.id_booking, b.booking_date, s.stadium_name, n.number_name, t.time
            FROM tb_booking b
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            INNER JOIN tb_number n ON b.id_number = n.id_number
            INNER JOIN tb_time_slot t ON b.id_time = t.id_time
            WHERE b.booking_status = 2
            GROUP BY b.id_stadium, b.id_number, b.id_time
            ORDER BY count DESC';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

//เรียกข้อมูลจำนวนการเข้าใช้งานสนามทั้งหมด
$app->get('/report_booking_alltime', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];
    $sql = 'SELECT s.stadium_name, COUNT(b.id_booking) AS count
            FROM tb_booking b
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            WHERE b.booking_status = 2
            GROUP BY s.stadium_name
            ORDER BY count DESC';

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});


// จองได้เเค่รอบละ 1 ครั้ง โดยต้องผ่านวันที่จองไปก่อนถึงจะจองอีกได้ booking-confirm
$app->post('/user_booking', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];

    $data = $request->getParsedBody();

    if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
        $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
    $bookingTime = intval($data['id_time']); // Assuming id_time is an integer representing the time slot

    // ตั้งค่า timezone ให้เป็นเวลาของประเทศไทย
    date_default_timezone_set('Asia/Bangkok');
    $currentDate = date('Y-m-d');
    $presentDate = date('Y-m-d'); // ใช้วันที่ปัจจุบัน
    $presentTime = date('H:i:s'); // ใช้เวลาปัจจุบัน

    // Check if the member has an existing booking on or after the current date
    $existingBooking = "SELECT * FROM tb_booking WHERE booking_date >= ? AND id_member = ?";
    $stmtExisting = $conn->prepare($existingBooking);
    $stmtExisting->bind_param('si', $currentDate, $data['id_member']);
    $stmtExisting->execute();
    $resultExisting = $stmtExisting->get_result();

    if ($resultExisting->num_rows > 0) {
        $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองปัจจุบันที่ยังไม่ผ่านไป"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ? AND id_member != ?";
    $stmtExisting = $conn->prepare($existingBooking);
    $stmtExisting->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
    $stmtExisting->execute();
    $resultExisting = $stmtExisting->get_result();

    if ($resultExisting->num_rows > 0) {
        $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
    $stmtExisting = $conn->prepare($existingBooking);
    $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
    $stmtExisting->execute();
    $resultExisting = $stmtExisting->get_result();

    if ($resultExisting->num_rows > 0) {
        $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    // Insert new booking with current date and time
    $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member, present_date, present_time) VALUES (?, 1, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('siiiiss', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member'], $presentDate, $presentTime);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $responseBody = ["message" => "จองสนามกีฬาสำเร็จ"];
        $response->getBody()->write(json_encode($responseBody));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    } else {
        $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});


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

//     $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ? AND id_member != ?";
//     $stmtExisting = $conn->prepare($existingBooking);
//     $stmtExisting->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
//     $stmtExisting->execute();
//     $resultExisting = $stmtExisting->get_result();

//     if ($resultExisting->num_rows > 0) {
//         $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
//         $response->getBody()->write(json_encode($errorResponse));
//         return $response
//             ->withHeader('Content-Type', 'application/json')
//             ->withStatus(400);
//     }

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


//เรียกข้อมูลการจองของ user มาแสดง qr-code booking-my
$app->get('/report_bookinguser/{id_member}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับค่า id_member จากพารามิเตอร์ของ URL
    $id_member = $args['id_member'];

    // ปรับปรุง SQL query เพื่อเรียก id_booking ล่าสุด
    $sql = 'SELECT b.id_booking, b.booking_date, b.present_date, b.present_time, s.stadium_name, n.number_name, t.time, m.fname, m.lname
            FROM tb_booking b
            INNER JOIN tb_member m ON b.id_member = m.id_member
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            INNER JOIN tb_number n ON b.id_number = n.id_number
            INNER JOIN tb_time_slot t ON b.id_time = t.id_time
            WHERE b.id_member = ?
            ORDER BY b.id_booking DESC
            LIMIT 1';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

// ยกเลิกทั้งหมดการปิดใช้งานสนาม admin-alltime
$app->delete('/delete_bookingall/{id_member}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับ ID จากพารามิเตอร์ของเส้นทาง
    $memberId = $args['id_member'];

    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->begin_transaction();

    try {
        // ลบข้อมูลจากตาราง tb_booking ที่มี id_member ตรงกัน
        $sqlBooking = "DELETE FROM tb_booking WHERE id_member = ?";
        $stmtBooking = $conn->prepare($sqlBooking);
        $stmtBooking->bind_param('i', $memberId);
        $stmtBooking->execute();

        // ตรวจสอบจำนวนแถวที่ได้รับผลกระทบ
        $affectedBookingRows = $stmtBooking->affected_rows;

        if ($affectedBookingRows > 0) {
            // ยืนยันการทำธุรกรรม (commit transaction)
            $conn->commit();

            $data = [
                "message" => "All bookings deleted successfully",
                "affected_booking_rows" => $affectedBookingRows
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200); // ส่งกลับสถานะ 200 OK
        } else {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากล้มเหลว
            $conn->rollback();

            $errorResponse = ["message" => "Failed to delete bookings: No bookings found for the member"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
        $conn->rollback();

        $errorResponse = ["message" => "An error occurred while deleting bookings", "error" => $e->getMessage()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
    }
});

// ยกเลิกการปิดใช้งานสนาม admin-alltime
$app->delete('/delete_bookingadmin/{id_booking}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับ ID จากพารามิเตอร์ของเส้นทาง
    $bookingId = $args['id_booking'];

    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->begin_transaction();

    try {
        // ลบข้อมูลจากตาราง tb_booking ที่มี id_booking ตรงกัน
        $sqlBooking = "DELETE FROM tb_booking WHERE id_booking = ?";
        $stmtBooking = $conn->prepare($sqlBooking);
        $stmtBooking->bind_param('i', $bookingId);
        $stmtBooking->execute();

        // ตรวจสอบจำนวนแถวที่ได้รับผลกระทบ
        $affectedBookingRows = $stmtBooking->affected_rows;

        if ($affectedBookingRows > 0) {
            // ยืนยันการทำธุรกรรม (commit transaction)
            $conn->commit();

            $data = [
                "message" => "Booking deleted successfully",
                "affected_booking_rows" => $affectedBookingRows
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200); // ส่งกลับสถานะ 200 OK
        } else {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากล้มเหลว
            $conn->rollback();

            $errorResponse = ["message" => "Failed to delete booking: Booking not found"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
        $conn->rollback();

        $errorResponse = ["message" => "An error occurred while deleting booking", "error" => $e->getMessage()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
    }
});

// ลบข้อมูลการจองสนามของผู้ใช้งาน booking-my
$app->delete('/delete_booking/{id_booking}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับ ID จากพารามิเตอร์ของเส้นทาง
    $bookingId = $args['id_booking'];

    // เริ่มต้นการทำธุรกรรม (transaction)
    $conn->begin_transaction();

    try {
        // ดึงข้อมูล id_time และเวลาจองจากตาราง tb_booking
        $sqlGetBooking = "SELECT id_time, booking_date FROM tb_booking WHERE id_booking = ?";
        $stmtGetBooking = $conn->prepare($sqlGetBooking);
        $stmtGetBooking->bind_param('i', $bookingId);
        $stmtGetBooking->execute();
        $result = $stmtGetBooking->get_result();

        if ($result->num_rows === 0) {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากไม่พบการจอง
            $conn->rollback();
            $errorResponse = ["message" => "Failed to delete booking: Booking not found"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
        }

        $booking = $result->fetch_assoc();
        $idTime = $booking['id_time'];
        $bookingTime = new DateTime($booking['booking_date'], new DateTimeZone('Asia/Bangkok'));

        // กำหนดช่วงเวลาเริ่มต้นและเวลาปัจจุบัน
        $currentTime = new DateTime('now', new DateTimeZone('Asia/Bangkok'));
        $timeOffsets = [
            1 => '08:00:00',
            2 => '09:00:00',
            3 => '10:00:00',
            4 => '11:00:00',
            5 => '12:00:00',
            6 => '13:00:00',
            7 => '14:00:00',
            8 => '15:00:00'
        ];

        if (!isset($timeOffsets[$idTime])) {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หาก id_time ไม่ถูกต้อง
            $conn->rollback();
            $errorResponse = ["message" => "Invalid time slot"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400); // ส่งกลับสถานะ 400 Bad Request
        }

        $timeSlotStart = new DateTime($timeOffsets[$idTime], new DateTimeZone('Asia/Bangkok'));
        $timeSlotStart->setDate($bookingTime->format('Y'), $bookingTime->format('m'), $bookingTime->format('d'));

        // ตรวจสอบว่าเวลาปัจจุบันอยู่ภายในช่วงเวลา 2 ชั่วโมงก่อนเวลาจองหรือไม่
        $cutoffTime = clone $timeSlotStart;
        $cutoffTime->modify('-2 hours');

        if ($currentTime > $cutoffTime) {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากไม่สามารถยกเลิกได้
            $conn->rollback();
            $errorResponse = ["message" => "Cannot cancel booking less than 2 hours before the time slot"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(403); // ส่งกลับสถานะ 403 Forbidden
        }

        // ลบข้อมูลจากตาราง tb_booking ที่มี id_booking ตรงกัน
        $sqlBooking = "DELETE FROM tb_booking WHERE id_booking = ?";
        $stmtBooking = $conn->prepare($sqlBooking);
        $stmtBooking->bind_param('i', $bookingId);
        $stmtBooking->execute();

        // ตรวจสอบจำนวนแถวที่ได้รับผลกระทบ
        $affectedBookingRows = $stmtBooking->affected_rows;

        if ($affectedBookingRows > 0) {
            // ยืนยันการทำธุรกรรม (commit transaction)
            $conn->commit();

            $data = [
                "message" => "Booking deleted successfully",
                "affected_booking_rows" => $affectedBookingRows
            ];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200); // ส่งกลับสถานะ 200 OK
        } else {
            // ยกเลิกการทำธุรกรรม (rollback transaction) หากล้มเหลว
            $conn->rollback();

            $errorResponse = ["message" => "Failed to delete booking: Booking not found"];
            $response->getBody()->write(json_encode($errorResponse));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404); // ส่งกลับสถานะ 404 Not Found
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรม (rollback transaction) หากเกิดข้อผิดพลาด
        $conn->rollback();

        $errorResponse = ["message" => "An error occurred while deleting booking", "error" => $e->getMessage()];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500); // ส่งกลับสถานะ 500 Internal Server Error
    }
});

// การปิดใช้งานสนามในช่วงเวลาต่างๆ admin-edittime
$app->post('/admin_booking', function (Request $request, Response $response) {
    $conn = $GLOBALS['conn'];

    $data = $request->getParsedBody();

    if (!isset($data['booking_date']) || !isset($data['id_stadium']) || !isset($data['id_number']) || !isset($data['id_time']) || !isset($data['id_member'])) {
        $errorResponse = ["message" => "ข้อมูลที่ต้องการไม่ครบถ้วน"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(400);
    }

    $bookingDate = date('Y-m-d', strtotime($data['booking_date']));
    $bookingTimes = explode(',', $data['id_time']); // Convert string to array of integers representing the time slots

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert new bookings
        $sql = "INSERT INTO tb_booking (booking_date, booking_status, id_stadium, id_number, id_time, id_member, booking_admin) VALUES (?, 1, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);

        foreach ($bookingTimes as $bookingTime) {
            // Check if there is already a booking for the selected date, stadium, and time
            $existingBooking = "SELECT * FROM tb_booking WHERE booking_date = ? AND id_stadium = ? AND id_number = ? AND id_time = ?";
            $stmtExisting = $conn->prepare($existingBooking);
            $stmtExisting->bind_param('siii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime);
            $stmtExisting->execute();
            $resultExisting = $stmtExisting->get_result();

            if ($resultExisting->num_rows > 0) {
                // Rollback the transaction
                $conn->rollback();

                $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้ เนื่องจากมีการจองแล้วในช่วงเวลาดังกล่าว"];
                $response->getBody()->write(json_encode($errorResponse));
                return $response
                    ->withHeader('Content-Type', 'application/json')
                    ->withStatus(400);
            }

            // Insert the booking
            $stmt->bind_param('siiii', $bookingDate, $data['id_stadium'], $data['id_number'], $bookingTime, $data['id_member']);
            $stmt->execute();
        }

        // Commit the transaction
        $conn->commit();

        // Get the last inserted id_booking
        $lastIdBooking = $conn->insert_id;

        $responseBody = ["message" => "จองสนามกีฬาสำเร็จ", "id_booking" => $lastIdBooking];
        $response->getBody()->write(json_encode($responseBody));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    } catch (Exception $e) {
        // Rollback the transaction
        $conn->rollback();

        $errorResponse = ["message" => "ไม่สามารถจองสนามกีฬาได้"];
        $response->getBody()->write(json_encode($errorResponse));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(500);
    }
});

// เรียกข้อมูลการปิดใช้งานสนามมาแสดงทั้งหมด admin-alltime
$app->get('/report_bookingadmin/{id_member}', function (Request $request, Response $response, $args) {
    $conn = $GLOBALS['conn'];

    // รับค่า id_member จากพารามิเตอร์ของ URL
    $id_member = $args['id_member'];

    // ปรับปรุง SQL query เพื่อให้กรองข้อมูลตาม id_member และเรียกข้อมูลทั้งหมด
    $sql = 'SELECT b.id_booking, b.booking_date, s.stadium_name, n.number_name, t.time, m.fname, m.lname
            FROM tb_booking b
            INNER JOIN tb_member m ON b.id_member = m.id_member
            INNER JOIN tb_stadium s ON b.id_stadium = s.id_stadium
            INNER JOIN tb_number n ON b.id_number = n.id_number
            INNER JOIN tb_time_slot t ON b.id_time = t.id_time
            WHERE b.id_member = ?
            ORDER BY b.id_booking DESC';

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_member); // ผูกค่า id_member เข้ากับพารามิเตอร์ใน SQL query
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response->withHeader('Content-Type', 'application/json; charset=utf-8')->withStatus(200);
});

