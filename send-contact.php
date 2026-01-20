<?php
// ===============================
// CONFIGURACIÓN
// ===============================
$destinatario = "contacto@csintegral.cl";
$asunto = "Nueva solicitud de asesoría - CSI Integral";

// ===============================
// SEGURIDAD: SOLO POST
// ===============================
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(403);
    exit("Acceso no permitido");
}

// ===============================
// OBTENER DATOS
// ===============================
$nombre   = trim($_POST["nombre"] ?? "");
$email    = trim($_POST["email"] ?? "");
$servicio = trim($_POST["servicio"] ?? "");

// ===============================
// VALIDACIONES
// ===============================
if ($nombre === "" || $email === "" || $servicio === "") {
    http_response_code(400);
    exit("Todos los campos son obligatorios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    exit("Correo no válido.");
}

// ===============================
// MENSAJE
// ===============================
$mensaje = "
Nueva solicitud de asesoría desde CSIntegral.cl

Nombre: $nombre
Correo: $email
Servicio de interés: $servicio

Fecha: " . date("d-m-Y H:i") . "
IP: " . $_SERVER["REMOTE_ADDR"] . "
";

// ===============================
// HEADERS (ANTI-SPAM)
// ===============================
$headers  = "From: CSIntegral Web <no-reply@csintegral.cl>\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// ===============================
// ENVIAR
// ===============================
if (mail($destinatario, $asunto, $mensaje, $headers)) {
    header("Location: /gracias.html");
    exit;
} else {
    http_response_code(500);
    echo "Error al enviar el mensaje. Intente nuevamente.";
}