<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT");
header("Access-Control-Allow-Headers: Content-Type");

require_once "bd.php";

try {
    $database = new Database();
    $conn = $database->connect();

    // GET - Obtener todos los libros
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $sql = "SELECT * FROM libros ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        $libros = [];
        
        while ($row = $result->fetch_assoc()) {
            $libros[] = $row;
        }

        echo json_encode([
            "status" => "success",
            "data" => $libros
        ]);
    }

    // POST - Agregar nuevo libro
    elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data["titulo"]) || !isset($data["autor"])) {
            throw new Exception("Faltan datos requeridos");
        }

        $sql = "INSERT INTO libros (titulo, autor, genero, precio, stock) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssdd", 
            $data["titulo"],
            $data["autor"],
            $data["genero"],
            $data["precio"],
            $data["stock"]
        );

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Libro agregado correctamente",
                "id" => $conn->insert_id
            ]);
        } else {
            throw new Exception("Error al agregar el libro");
        }
    }

    // PUT - Actualizar libro existente
    elseif ($_SERVER["REQUEST_METHOD"] == "PUT") {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data["id"]) || !isset($data["titulo"]) || !isset($data["autor"])) {
            throw new Exception("Faltan datos requeridos");
        }

        $sql = "UPDATE libros SET titulo = ?, autor = ?, genero = ?, precio = ?, stock = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssddi", 
            $data["titulo"],
            $data["autor"],
            $data["genero"],
            $data["precio"],
            $data["stock"],
            $data["id"]
        );

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Libro actualizado correctamente"
            ]);
        } else {
            throw new Exception("Error al actualizar el libro");
        }
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
} finally {
    if (isset($database)) {
        $database->close();
    }
}
?>