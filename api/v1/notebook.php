<?php
class Notebook {
    private $db;

    public function __construct($mysqli) {
        $this->db = $mysqli;
    }

    public function getAll() {
        $result = $this->db->query('SELECT * FROM notes');
        $data = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($data);
    }

    public function getById($id) {
        $stmt = $this->db->prepare('SELECT * FROM notes WHERE id=?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        echo json_encode($data ?: ['error' => 'Record not found']);
    }

    public function create() {
        $input = json_decode(file_get_contents('php://input'), true);
        
        $name = $input['name'];
        $company = $input['company'] ?? null;
        $phone = $input['phone'];
        $email = $input['email'];
        $birthdate = $input['birthdate'] ?? null;
        $photo = $input['photo'] ?? null;
        $stmt = $this->db->prepare('INSERT INTO notes (name, company, phone, email, birthdate, photo) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->bind_param(
            'ssssss',
            $name,
            $company,
            $phone,
            $email,
            $birthdate,
            $photo
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'id' => $this->db->insert_id]);
        } else {
            echo json_encode(['error' => 'Failed to create record']);
        }
    }

    public function update($id) {
        $input = json_decode(file_get_contents('php://input'), true);

        $name = $input['name'];
        $company = $input['company'] ?? null;
        $phone = $input['phone'];
        $email = $input['email'];
        $birthdate = $input['birthdate'] ?? null;
        $photo = $input['photo'] ?? null;
        $stmt = $this->db->prepare('UPDATE notes SET name = ?, company = ?, phone = ?, email = ?, birthdate = ?, photo = ? WHERE id = ?');
        $stmt->bind_param(
            'ssssssi',
            $name,
            $company,
            $phone,
            $email,
            $birthdate,
            $photo,
            $id
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to update record']);
        }
    }

    public function delete($id) {
        $stmt = $this->db->prepare('DELETE FROM notes WHERE id = ?');
        $stmt->bind_param('i', $id);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to delete record']);
        }
    }
}
?>
