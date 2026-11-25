<?php
require_once __DIR__ . '/../core/Model.php';

class IAFAS extends Model
{
    public function listar(string $buscar = '', ?int $estado = null, int $limite = 10, int $offset = 0): array
    {
        $sql = "SELECT IdIAFAS, Nombre, Estado, FechaRegistro FROM iafas";
        $where = []; $params = [];
        if ($buscar !== '') { $where[] = "Nombre LIKE ?"; $params[] = '%' . $buscar . '%'; }
        if ($estado !== null) { $where[] = "Estado = ?"; $params[] = $estado; }
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $sql .= ' ORDER BY Nombre ASC LIMIT ? OFFSET ?';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $i => $v) $stmt->bindValue($i + 1, $v, is_int($v) ? PDO::PARAM_INT : PDO::PARAM_STR);
        $stmt->bindValue(count($params) + 1, $limite, PDO::PARAM_INT);
        $stmt->bindValue(count($params) + 2, $offset, PDO::PARAM_INT);
        $stmt->execute(); return $stmt->fetchAll();
    }

    public function contar(string $buscar = '', ?int $estado = null): int
    {
        $sql = 'SELECT COUNT(*) FROM iafas'; $where = []; $params = [];
        if ($buscar !== '') { $where[] = 'Nombre LIKE ?'; $params[] = '%' . $buscar . '%'; }
        if ($estado !== null) { $where[] = 'Estado = ?'; $params[] = $estado; }
        if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
        $stmt = $this->db->prepare($sql); $stmt->execute($params); return (int)$stmt->fetchColumn();
    }

    public function buscarPorId(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT IdIAFAS, Nombre, Estado, FechaRegistro FROM iafas WHERE IdIAFAS = ? LIMIT 1');
        $stmt->execute([$id]); $row = $stmt->fetch(); return $row ?: null;
    }

    public function crear(string $nombre): int
    {
        $stmt = $this->db->prepare('INSERT INTO iafas (Nombre, Estado) VALUES (?, 1)');
        $stmt->execute([$nombre]); return (int)$this->db->lastInsertId();
    }

    public function actualizar(int $id, string $nombre): bool
    {
        $stmt = $this->db->prepare('UPDATE iafas SET Nombre = ? WHERE IdIAFAS = ?');
        return $stmt->execute([$nombre, $id]);
    }

    public function cambiarEstado(int $id, int $estado): bool
    {
        $stmt = $this->db->prepare('UPDATE iafas SET Estado = ? WHERE IdIAFAS = ?');
        return $stmt->execute([$estado, $id]);
    }
}
