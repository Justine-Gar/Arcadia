<?php
// Configuration de la base de données et constantes
$pdo = new PDO('mysql:host=localhost;dbname=arcadia', 'JustAdmin', 'Juhn.DEV2023$');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

define('BASE_UPLOAD_DIR', __DIR__ . '/../../../public/uploads/');
define('MAX_FILE_SIZE', 5000000); // 5 MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'avif']);

function sanitizeString($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function handleUpload() {
    global $pdo;

    if (!isset($_POST['entity_type'], $_POST['entity_id'], $_POST['fileName'], $_FILES['file'])) {
        throw new Exception("Tous les champs sont requis.");
    }

    $entityType = sanitizeString($_POST['entity_type']);
    $entityId = filter_input(INPUT_POST, 'entity_id', FILTER_VALIDATE_INT);
    $fileName = sanitizeString($_POST['fileName']);
    $uploadedFile = $_FILES['file'];

    if (!in_array($entityType, ['animal', 'service', 'habitat']) || $entityId === false || $entityId === null) {
        throw new Exception("Type d'entité invalide ou ID d'entité manquant.");
    }

    if ($uploadedFile['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Erreur lors de l'upload du fichier.");
    }

    if ($uploadedFile['size'] > MAX_FILE_SIZE) {
        throw new Exception("Le fichier est trop volumineux. Taille maximale : " . (MAX_FILE_SIZE / 1000000) . " MB");
    }

    $fileExtension = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
    if (!in_array($fileExtension, ALLOWED_EXTENSIONS)) {
        throw new Exception("Type de fichier non autorisé. Extensions autorisées : " . implode(', ', ALLOWED_EXTENSIONS));
    }

    $entityDir = BASE_UPLOAD_DIR . $entityType . 's/';
    if (!is_dir($entityDir) && !mkdir($entityDir, 0777, true)) {
        throw new Exception("Impossible de créer le répertoire de destination.");
    }

    $newFileName = uniqid() . '.' . $fileExtension;
    $filePath = $entityDir . $newFileName;

    if (!move_uploaded_file($uploadedFile['tmp_name'], $filePath)) {
        throw new Exception("Échec du déplacement du fichier uploadé.");
    }

    $relativeFilePath = str_replace('public/', '', $filePath);

    $tableName = 'file' . ucfirst($entityType[0]);
    $idColumnName = 'id_' . $entityType;

    $stmt = $pdo->prepare("INSERT INTO $tableName (fileName, filePath, $idColumnName) VALUES (?, ?, ?)");
    $stmt->execute([$fileName, $relativeFilePath, $entityId]);

    return "L'image a été uploadée et enregistrée avec succès.";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $message = handleUpload();
        echo json_encode(['success' => true, 'message' => $message]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload de fichier</title>
</head>
<body>
    <h1>Upload de fichier</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        <div>
            <label for="entity_type">Type d'entité :</label>
            <select name="entity_type" id="entity_type" required>
                <option value="animal">Animal</option>
                <option value="service">Service</option>
                <option value="habitat">Habitat</option>
            </select>
        </div>
        <div>
            <label for="entity_id">ID de l'entité :</label>
            <input type="number" name="entity_id" id="entity_id" required>
        </div>
        <div>
            <label for="fileName">Nom :</label>
            <input type="text" name="fileName" id='fileName' required>
        </div>
        <div>
            <label for="file">Sélectionner une image :</label>
            <input type="file" name="file" id="file" required accept="image/*">
        </div>
        <button type="submit">Uploader</button>
    </form>
</body>
</html>