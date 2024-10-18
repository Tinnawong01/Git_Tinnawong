<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/login', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json, true);
    $email = $jsonData['email'];
    $password = $jsonData['password'];

    $conn = $GLOBALS['conn'];
    $sql = 'SELECT * FROM tb_member WHERE email = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $role = $user['role'];

            if ($role === 'user') {
                $data = ["message" => "เข้าสู่ระบบสำเร็จ", "user" => $user, "redirect" => "main"];
            } elseif ($role === 'admin') {
                $data = ["message" => "เข้าสู่ระบบสำเร็จ", "user" => $user, "redirect" => "dashboard"];
            } else {
                $data = ["message" => "ไม่พบข้อมูล"];
            }

            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } else {
            $data = ["message" => "อีเมลหรือรหัสผ่านไม่ถูกต้อง"];
            $response->getBody()->write(json_encode($data));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    } else {
        $data = ["message" => "ไม่พบข้อมูล"];
        $response->getBody()->write(json_encode($data));
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(401);
    }
});
