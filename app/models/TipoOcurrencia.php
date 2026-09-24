<?php

require_once __DIR__ . '/../core/Model.php';

class TipoOcurrencia extends Model
{
    public function listar(
        string $buscar = '',
        ?int $estado = null,
        int $limite = 10,
        int $offset = 0
    ): array {
        $sql = "SELECT
                    IdTipoOcurrencia,
                    Descripcion,
                    Estado,
                    FechaRegistro
                FROM TiposOcurrencia";

        $condiciones = [];
        $parametros = [];

        if ($buscar !== '') {
            $condiciones[] = "Descripcion LIKE ?";
            $parametros[] = '%' . $buscar . '%';
        }

        if ($estado !== null) {
            $condiciones[] = "Estado = ?";
            $parametros[] = $estado;
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $sql .= " ORDER BY IdTipoOcurrencia ASC
                  LIMIT ? OFFSET ?";

        $parametros[] = $limite;
        $parametros[] = $offset;

        $stmt = $this->db->prepare($sql);

        $cantidadParametros = count($parametros);

        foreach ($parametros as $indice => $parametro) {
            $numeroParametro = $indice + 1;

            if ($numeroParametro > $cantidadParametros - 2) {
                $stmt->bindValue(
                    $numeroParametro,
                    (int) $parametro,
                    PDO::PARAM_INT
                );
            } else {
                $stmt->bindValue(
                    $numeroParametro,
                    $parametro,
                    PDO::PARAM_STR
                );
            }
        }

        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function contar(
        string $buscar = '',
        ?int $estado = null
    ): int {
        $sql = "SELECT COUNT(*)
                FROM TiposOcurrencia";

        $condiciones = [];
        $parametros = [];

        if ($buscar !== '') {
            $condiciones[] = "Descripcion LIKE ?";
            $parametros[] = '%' . $buscar . '%';
        }

        if ($estado !== null) {
            $condiciones[] = "Estado = ?";
            $parametros[] = $estado;
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        return (int) $stmt->fetchColumn();
    }

    public function buscarPorId(int $idTipoOcurrencia): ?array
    {
        $sql = "SELECT
                    IdTipoOcurrencia,
                    Descripcion,
                    Estado,
                    FechaRegistro
                FROM TiposOcurrencia
                WHERE IdTipoOcurrencia = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idTipoOcurrencia]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    public function crear(string $descripcion): int
    {
        $sql = "INSERT INTO TiposOcurrencia (
                    Descripcion,
                    Estado
                ) VALUES (?, 1)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$descripcion]);

        return (int) $this->db->lastInsertId();
    }

    public function actualizar(
        int $idTipoOcurrencia,
        string $descripcion
    ): bool {
        $sql = "UPDATE TiposOcurrencia
                SET Descripcion = ?
                WHERE IdTipoOcurrencia = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $descripcion,
            $idTipoOcurrencia
        ]);
    }

    public function cambiarEstado(
        int $idTipoOcurrencia,
        int $estado
    ): bool {
        $sql = "UPDATE TiposOcurrencia
                SET Estado = ?
                WHERE IdTipoOcurrencia = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $estado,
            $idTipoOcurrencia
        ]);
    }

    public function listarActivos(): array
    {
        $sql = "SELECT
                    IdTipoOcurrencia,
                    Descripcion
                FROM TiposOcurrencia
                WHERE Estado = 1
                ORDER BY IdTipoOcurrencia ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}