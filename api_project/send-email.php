<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';
// $app->post('/send-email', 'sendEmail');
// function generateRandomCode($length = 6) {
//     $characters = '0123456789';
//     $charactersLength = strlen($characters);
//     $randomCode = '';
//     for ($i = 0; $i < $length; $i++) {
//         $randomCode .= $characters[rand(0, $charactersLength - 1)];
//     }
//     return $randomCode;
// }

// function sendEmail(Request $request, Response $response, $args) {
//     $data = $request->getParsedBody();

//     if (!isset($data['email']) || empty($data['email'])) {
//         $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Invalid input: email is required']));
//         return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
//     }

//     $email = $data['email'];
//     $code = generateRandomCode(6);

//     $mail = new PHPMailer(true);

//     try {
//         $mail->SMTPDebug = 0;
//         $mail->isSMTP();
//         $mail->Host       = 'smtp.gmail.com';
//         $mail->SMTPAuth   = true;
//         $mail->Username   = '0611671459boss@gmail.com';
//         $mail->Password   = 'your_password'; // เปลี่ยนเป็นรหัสผ่านที่ถูกต้อง
//         $mail->SMTPSecure = 'tls';
//         $mail->Port       = 587;

//         $mail->setFrom('0611671459boss@gmail.com', 'Tinnawong');
//         $mail->addAddress($email);

//         $mail->isHTML(true);
//         $mail->Subject = 'Your Verification Code';
//         $mail->Body    = "Your verification code is: $code <br><br>";

//         $mail->send();
//         $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Email has been sent']));
//     } catch (Exception $e) {
//         $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]));
//     }

//     return $response->withHeader('Content-Type', 'application/json');
// }

$app->post('/request-password-reset', function (Request $request, Response $response, $args) {
    $data = json_decode($request->getBody(), true);

    $email = $data['email'] ?? null;

    if (empty($email)) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'จำเป็นต้องระบุอีเมลล์.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $conn = $GLOBALS['connect'];

    // Check if email exists in student or teacher tables
    $stmt = $conn->prepare("SELECT email FROM student WHERE email = ? UNION SELECT email FROM teacher WHERE email = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'ไม่พบอีเมลล์.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(404);
    }

    // Generate a reset token
    $token = bin2hex(random_bytes(32));
    $created_at = date('Y-m-d H:i:s'); // Current time

    // Save token to database
    $stmt = $conn->prepare("INSERT INTO password_resets (email, token, created_at) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $email, $token, $created_at);
    $stmt->execute();

    // Prepare the email content
    $resetLink = "http://tinnawong.bowlab.net/reset-password?token={$token}"; // Change to your actual domain
    $emailContent = "
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    color: #333;
                    margin: 0;
                    padding: 0;
                    background-color: #f9f9f9;
                }
                .container {
                    width: 100%;
                    max-width: 600px;
                    margin: 20px auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #007bff;
                }
                p {
                    font-size: 16px;
                    line-height: 1.5;
                }
                a {
                    color: #007bff;
                    text-decoration: none;
                }
                .footer {
                    margin-top: 20px;
                    padding-top: 10px;
                    border-top: 1px solid #e1e1e1;
                    text-align: center;
                    font-size: 14px;
                    color: #777;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                <h1>คำขอรีเซ็ตรหัสผ่าน</h1>
                <p>เรียนผู้ใช้,</p>
                <p>เราได้รับคำขอให้รีเซ็ตรหัสผ่านของคุณ เพื่อดำเนินการต่อ กรุณาคลิกที่ลิงก์ด้านล่างนี้:</p>
                <p><a href='{$resetLink}' target='_blank'>รีเซ็ตรหัสผ่านของคุณ</a></p>
                <p>หากคุณไม่ได้ทำการขอรีเซ็ตรหัสผ่านนี้ กรุณามองข้ามอีเมลล์นี้.</p>
                <p>ขอบคุณ,</p>
                <p>ทีมงาน</p>
                <div class='footer'>
                    <p>หากคุณมีคำถามใด ๆ กรุณาติดต่อเราที่ <a href='mailto:jimsimson.dev@gmail.com'>jimsimson.dev@gmail.com</a></p>
                </div>
            </div>
        </body>
        </html>
    ";

    // Send the email
    $mail = new PHPMailer(true);
    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Gmail SMTP host
        $mail->SMTPAuth   = true;
        $mail->Username   = '0611671459boss@gmail.com'; // Gmail username
        $mail->Password   = 'sngl eluu qllh qfug'; // Gmail app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('0611671459boss@gmail.com', 'FaceDetection Teams');
        $mail->addAddress($email);

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = $emailContent;

        $mail->send();
        $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'ลิงก์สำหรับรีเซ็ตรหัสผ่านได้ถูกส่งไปยังอีเมลล์ของคุณแล้ว.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
    } catch (Exception $e) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => "ไม่สามารถส่งข้อความได้ ข้อผิดพลาดของ Mailer: {$mail->ErrorInfo}"]));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(500);
    }
});

$app->post('/reset-password', function (Request $request, Response $response, $args) {
    $data = $request->getParsedBody();
    $token = $data['token'] ?? null;
    $newPassword = $data['new_password'] ?? null;

    if (empty($token) || empty($newPassword)) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Token and new password are required.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $conn = $GLOBALS['connect'];

    // Verify token
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND created_at > NOW() - INTERVAL 1 HOUR");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Invalid or expired token.']));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
    }

    $email = $result->fetch_assoc()['email'];

    // Update password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE student SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE teacher SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashedPassword, $email);
    $stmt->execute();

    // Delete the used token
    $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();

    $response->getBody()->write(json_encode(['status' => 'success', 'message' => 'Password has been reset successfully.']));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});