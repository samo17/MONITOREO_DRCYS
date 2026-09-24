<?php

require_once __DIR__ . '/../core/Model.php';

class Usuario extends Model
{
    /**
     * Busca un usuario por su nombre de usuario.
     */
    public function buscarPorUsuario(string $usuario): ?array
    {
        $sql = "SELECT
                    IdUsuario,
                    Usuario,
                    Password,
                    Nombres,
                    Apellidos,
                    DNI,
                    Correo,
                    Telefono,
                    Estado,
                    UltimoAcceso,
                    FechaRegistro,
                    FechaActualizacion
                FROM Usuarios
                WHERE Usuario = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

    /**
     * Busca un usuario por su DNI.
     */
    public function buscarPorDNI(string $dni): ?array
    {
        $sql = "SELECT
                    IdUsuario,
                    Usuario,
                    Nombres,
                    Apellidos,
                    DNI,
                    Correo,
                    Telefono,
                    Estado,
                    UltimoAcceso,
                    FechaRegistro,
                    FechaActualizacion
                FROM Usuarios
                WHERE DNI = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dni]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }

public function buscarPorId(int $idUsuario): ?array
{
    $sql = "SELECT
                IdUsuario,
                Usuario,
                Nombres,
                Apellidos,
                DNI,
                Correo,
                Telefono,
                Estado,
                UltimoAcceso,
                FechaRegistro,
                FechaActualizacion
            FROM Usuarios
            WHERE IdUsuario = ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([$idUsuario]);

    $resultado = $stmt->fetch();

    return $resultado ?: null;
}

    /**
     * Actualiza la fecha y hora del último acceso.
     */
    public function actualizarUltimoAcceso(int $idUsuario): bool
    {
        $sql = "UPDATE Usuarios
                SET UltimoAcceso = NOW()
                WHERE IdUsuario = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$idUsuario]);
    }

    /**
     * Lista todos los usuarios.
     */
    public function listar(
    string $buscar = '',
    ?int $estado = null,
    int $limite = 10,
    int $offset = 0
): array {
    $sql = "SELECT
                IdUsuario,
                Usuario,
                Nombres,
                Apellidos,
                DNI,
                Correo,
                Telefono,
                Estado,
                UltimoAcceso,
                FechaRegistro
            FROM Usuarios";

    $condiciones = [];
    $parametros = [];

    if ($buscar !== '') {
        $condiciones[] = "(Usuario LIKE ?
                           OR Nombres LIKE ?
                           OR Apellidos LIKE ?
                           OR DNI LIKE ?)";

        $termino = '%' . $buscar . '%';

        $parametros[] = $termino;
        $parametros[] = $termino;
        $parametros[] = $termino;
        $parametros[] = $termino;
    }

    if ($estado !== null) {
        $condiciones[] = "Estado = ?";
        $parametros[] = $estado;
    }

    if (!empty($condiciones)) {
        $sql .= " WHERE " . implode(" AND ", $condiciones);
    }

    $sql .= " ORDER BY Apellidos, Nombres
              LIMIT ? OFFSET ?";

    $parametros[] = $limite;
    $parametros[] = $offset;

    $stmt = $this->db->prepare($sql);

    $indice = 1;

    foreach ($parametros as $parametro) {
        if ($indice > count($parametros) - 2) {
            $stmt->bindValue($indice, (int) $parametro, PDO::PARAM_INT);
        } else {
            $stmt->bindValue($indice, $parametro);
        }

        $indice++;
    }

    $stmt->execute();

    return $stmt->fetchAll();
}

public function contar(
    string $buscar = '',
    ?int $estado = null
): int {
    $sql = "SELECT COUNT(*)
            FROM Usuarios";

    $condiciones = [];
    $parametros = [];

    if ($buscar !== '') {
        $condiciones[] = "(Usuario LIKE ?
                           OR Nombres LIKE ?
                           OR Apellidos LIKE ?
                           OR DNI LIKE ?)";

        $termino = '%' . $buscar . '%';

        $parametros[] = $termino;
        $parametros[] = $termino;
        $parametros[] = $termino;
        $parametros[] = $termino;
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

    /**
     * Lista los permisos o módulos activos.
     */
    public function listarPermisos(): array
    {
        $sql = "SELECT
                    IdPermiso,
                    Nombre,
                    Descripcion,
                    Modulo,
                    Estado
                FROM Permisos
                WHERE Estado = 1
                ORDER BY IdPermiso";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

public function listarPermisosUsuario(int $idUsuario): array
{
    $sql = "SELECT
                p.IdPermiso,
                p.Nombre,
                p.Descripcion,
                p.Modulo
            FROM Usuario_Permiso up
            INNER JOIN Permisos p
                ON p.IdPermiso = up.IdPermiso
            WHERE up.IdUsuario = ?
            AND p.Estado = 1
            ORDER BY p.IdPermiso";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([$idUsuario]);

    return $stmt->fetchAll();
}

    /**
     * Crea un usuario y asigna sus permisos.
     *
     * La operación se realiza dentro de una transacción.
     */
    public function crearConPermisos(
        string $usuario,
        string $password,
        string $nombres,
        string $apellidos,
        ?string $dni,
        ?string $correo,
        ?string $telefono,
        array $permisos
    ): int {

        try {

            $this->db->beginTransaction();

            /*
             * 1. Crear usuario
             */
            $sqlUsuario = "INSERT INTO Usuarios (
                                Usuario,
                                Password,
                                Nombres,
                                Apellidos,
                                DNI,
                                Correo,
                                Telefono,
                                Estado
                           ) VALUES (?, ?, ?, ?, ?, ?, ?, 1)";

            $stmtUsuario = $this->db->prepare($sqlUsuario);

            $stmtUsuario->execute([
                $usuario,
                password_hash($password, PASSWORD_DEFAULT),
                $nombres,
                $apellidos,
                $dni,
                $correo,
                $telefono
            ]);

            /*
             * 2. Obtener IdUsuario generado
             */
            $idUsuario = (int) $this->db->lastInsertId();

            /*
             * 3. Guardar permisos seleccionados
             */
            if (!empty($permisos)) {

                $sqlPermiso = "INSERT INTO Usuario_Permiso (
                                    IdUsuario,
                                    IdPermiso
                               ) VALUES (?, ?)";

                $stmtPermiso = $this->db->prepare($sqlPermiso);

                foreach ($permisos as $idPermiso) {

                    $stmtPermiso->execute([
                        $idUsuario,
                        (int) $idPermiso
                    ]);
                }
            }

            /*
             * 4. Confirmar la operación completa
             */
            $this->db->commit();

            return $idUsuario;

        } catch (Throwable $e) {

            /*
             * Si ocurre algún error,
             * deshacer toda la operación.
             */
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }

public function actualizarConPermisos(
    int $idUsuario,
    string $usuario,
    string $nombres,
    string $apellidos,
    ?string $dni,
    ?string $correo,
    ?string $telefono,
    int $estado,
    array $permisos,
    ?string $password = null
): bool {
    try {

        $this->db->beginTransaction();

        /*
         * 1. Actualizar datos del usuario
         */
        if ($password !== null && $password !== '') {

            $sqlUsuario = "UPDATE Usuarios
                           SET Usuario = ?,
                               Password = ?,
                               Nombres = ?,
                               Apellidos = ?,
                               DNI = ?,
                               Correo = ?,
                               Telefono = ?,
                               Estado = ?
                           WHERE IdUsuario = ?";

            $stmtUsuario = $this->db->prepare($sqlUsuario);

            $stmtUsuario->execute([
                $usuario,
                password_hash($password, PASSWORD_DEFAULT),
                $nombres,
                $apellidos,
                $dni,
                $correo,
                $telefono,
                $estado,
                $idUsuario
            ]);

        } else {

            $sqlUsuario = "UPDATE Usuarios
                           SET Usuario = ?,
                               Nombres = ?,
                               Apellidos = ?,
                               DNI = ?,
                               Correo = ?,
                               Telefono = ?,
                               Estado = ?
                           WHERE IdUsuario = ?";

            $stmtUsuario = $this->db->prepare($sqlUsuario);

            $stmtUsuario->execute([
                $usuario,
                $nombres,
                $apellidos,
                $dni,
                $correo,
                $telefono,
                $estado,
                $idUsuario
            ]);
        }

        /*
         * 2. Eliminar permisos actuales
         */
        $sqlEliminar = "DELETE FROM Usuario_Permiso
                        WHERE IdUsuario = ?";

        $stmtEliminar = $this->db->prepare($sqlEliminar);
        $stmtEliminar->execute([$idUsuario]);

        /*
         * 3. Registrar nuevamente los permisos seleccionados
         */
        if (!empty($permisos)) {

            $sqlPermiso = "INSERT INTO Usuario_Permiso (
                            IdUsuario,
                            IdPermiso
                        ) VALUES (?, ?)";

            $stmtPermiso = $this->db->prepare($sqlPermiso);

            foreach ($permisos as $idPermiso) {

                $stmtPermiso->execute([
                    $idUsuario,
                    (int) $idPermiso
                ]);
            }
        }

        /*
         * 4. Confirmar toda la operación
         */
        $this->db->commit();

        return true;

    } catch (Throwable $e) {

        /*
         * Si ocurre algún error,
         * deshacer toda la operación.
         */
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }

        throw $e;
    }
}

public function cambiarEstado(int $idUsuario, int $estado): bool
{
    $sql = "UPDATE Usuarios
            SET Estado = ?
            WHERE IdUsuario = ?";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        $estado,
        $idUsuario
    ]);
}


    /**
     * Comprueba si un usuario tiene un permiso por módulo.
     */
    public function tienePermiso(
        int $idUsuario,
        string $modulo
    ): bool {

        $sql = "SELECT 1
                FROM Usuario_Permiso up
                INNER JOIN Permisos p
                    ON p.IdPermiso = up.IdPermiso
                WHERE up.IdUsuario = ?
                  AND p.Nombre = ?
                  AND p.Estado = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $idUsuario,
            $modulo
        ]);

        return $stmt->fetchColumn() !== false;
    }
}