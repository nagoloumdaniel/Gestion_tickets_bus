<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION['ID_administrateur'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non authentifié']);
    exit();
}

if (!isset($_FILES['photo']) || !is_array($_FILES['photo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Fichier manquant']);
    exit();
}

$file = $_FILES['photo'];

if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Erreur de téléversement']);
    exit();
}

$tmpName = $file['tmp_name'] ?? '';
$originalName = $file['name'] ?? '';

if ($tmpName === '' || !is_uploaded_file($tmpName)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Fichier invalide']);
    exit();
}

$extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (!in_array($extension, $allowedExtensions, true)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format non supporté']);
    exit();
}

$imageInfo = @getimagesize($tmpName);
if ($imageInfo === false) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Le fichier n\'est pas une image']);
    exit();
}

$uploadDir = __DIR__ . '/../../../admin/img1';
if (!is_dir($uploadDir)) {
    @mkdir($uploadDir, 0777, true);
}

if (!is_dir($uploadDir) || !is_writable($uploadDir)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Dossier de destination inaccessible']);
    exit();
}

$safeName = bin2hex(random_bytes(16)) . '.' . $extension;
$destinationPath = $uploadDir . DIRECTORY_SEPARATOR . $safeName;

if (!move_uploaded_file($tmpName, $destinationPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Impossible d\'enregistrer le fichier']);
    exit();
}

$publicPath = 'img1/' . $safeName;

require __DIR__ . '/../../../conn.php';

$updated_at = date('Y-m-d H:i:s');
$sql = 'UPDATE Administrateurs SET profil = ?, updated_at = ? WHERE ID_administrateur = ?';
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Erreur SQL']);
    exit();
}

$idAdmin = (int)$_SESSION['ID_administrateur'];
$stmt->bind_param('ssi', $publicPath, $updated_at, $idAdmin);

if (!$stmt->execute()) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Mise à jour impossible']);
    exit();
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'filePath' => $publicPath]);

