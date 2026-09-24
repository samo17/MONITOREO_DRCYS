<?php

class Digitador extends Model
{
    protected string $tabla = 'digitadores';

    protected string $clavePrimaria = 'IdDIGITADOR';

    /**
     * --------------------------------------------------------------------------
     * Listar digitadores
     * --------------------------------------------------------------------------
     */
    public function listar(
        string $buscar = '',
        string $condicion = '',
        string $tipo = '',
        string $grupo = '',
        int $limite = 10,
        int $offset = 0
    ): array {
        $sql = "SELECT
                    IdDIGITADOR,
                    NOMBRES,
                    DNI,
                    FECHA_NACIMIENTO,
                    CONDICION,
                    FECHA_INGRESO,
                    TELEFONO,
                    CORREO,
                    TIPO,
                    IMG,
                    GRUPO,
                    META_DIARIA
                FROM digitadores";

        $condiciones = [];
        $parametros = [];

        /**
         * ----------------------------------------------------------------------
         * Búsqueda por nombres o DNI
         * ----------------------------------------------------------------------
         */
        if ($buscar !== '') {
            $condiciones[] = "(
                NOMBRES LIKE ?
                OR DNI LIKE ?
            )";

            $valor = '%' . $buscar . '%';

            $parametros[] = $valor;
            $parametros[] = $valor;
        }

        /**
         * ----------------------------------------------------------------------
         * Filtro por condición
         * ----------------------------------------------------------------------
         */
        if ($condicion !== '') {
            $condiciones[] = "CONDICION = ?";
            $parametros[] = $condicion;
        }

        /**
         * ----------------------------------------------------------------------
         * Filtro por tipo
         * ----------------------------------------------------------------------
         */
        if ($tipo !== '') {
            $condiciones[] = "TIPO = ?";
            $parametros[] = $tipo;
        }

        /**
         * ----------------------------------------------------------------------
         * Filtro por grupo
         * ----------------------------------------------------------------------
         */
        if ($grupo !== '') {
            $condiciones[] = "GRUPO = ?";
            $parametros[] = $grupo;
        }

        // Por defecto no mostrar registros con TIPO = NO DIGITA.
        // Si el usuario selecciona NO DIGITA en el filtro, sí se muestran.
        if ($tipo === '') {
            $condiciones[] = "UPPER(TRIM(TIPO)) <> 'NO DIGITA'";
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        // Ordenar por ID descendente: el registro más reciente primero.
        $sql .= "
            ORDER BY IdDIGITADOR DESC
            LIMIT ? OFFSET ?";

        $parametros[] = $limite;
        $parametros[] = $offset;

        $stmt = $this->db->prepare($sql);

        $indice = 1;

        foreach ($parametros as $parametro) {
            if (is_int($parametro)) {
                $stmt->bindValue(
                    $indice,
                    $parametro,
                    PDO::PARAM_INT
                );
            } else {
                $stmt->bindValue(
                    $indice,
                    $parametro,
                    PDO::PARAM_STR
                );
            }

            $indice++;
        }

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * --------------------------------------------------------------------------
     * Contar digitadores
     * --------------------------------------------------------------------------
     */
    public function contar(
        string $buscar = '',
        string $condicion = '',
        string $tipo = '',
        string $grupo = ''
    ): int {
        $sql = "SELECT COUNT(*)
                FROM digitadores";

        $condiciones = [];
        $parametros = [];

        /**
         * ----------------------------------------------------------------------
         * Búsqueda
         * ----------------------------------------------------------------------
         */
        if ($buscar !== '') {
            $condiciones[] = "(
                NOMBRES LIKE ?
                OR DNI LIKE ?
            )";

            $valor = '%' . $buscar . '%';

            $parametros[] = $valor;
            $parametros[] = $valor;
        }

        /**
         * ----------------------------------------------------------------------
         * Condición
         * ----------------------------------------------------------------------
         */
        if ($condicion !== '') {
            $condiciones[] = "CONDICION = ?";
            $parametros[] = $condicion;
        }

        /**
         * ----------------------------------------------------------------------
         * Tipo
         * ----------------------------------------------------------------------
         */
        if ($tipo !== '') {
            $condiciones[] = "TIPO = ?";
            $parametros[] = $tipo;
        }

        /**
         * ----------------------------------------------------------------------
         * Grupo
         * ----------------------------------------------------------------------
         */
        if ($grupo !== '') {
            $condiciones[] = "GRUPO = ?";
            $parametros[] = $grupo;
        }

        // Por defecto no contabilizar registros con TIPO = NO DIGITA.
        // Si el usuario selecciona NO DIGITA, el conteo incluye esos registros.
        if ($tipo === '') {
            $condiciones[] = "UPPER(TRIM(TIPO)) <> 'NO DIGITA'";
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        return (int) $stmt->fetchColumn();
    }

    /**
     * --------------------------------------------------------------------------
     * Obtener digitador por ID
     * --------------------------------------------------------------------------
     */
    public function buscarPorId(int $id): ?array
    {
        $sql = "SELECT
                    IdDIGITADOR,
                    NOMBRES,
                    DNI,
                    FECHA_NACIMIENTO,
                    CONDICION,
                    FECHA_INGRESO,
                    TELEFONO,
                    CORREO,
                    TIPO,
                    IMG,
                    GRUPO,
                    META_DIARIA
                FROM digitadores
                WHERE IdDIGITADOR = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado ?: null;
    }

    /**
     * --------------------------------------------------------------------------
     * Listar tipos
     * --------------------------------------------------------------------------
     */
    public function listarTipos(): array
    {
        $sql = "SELECT DISTINCT TIPO
                FROM digitadores
                WHERE TIPO IS NOT NULL
                  AND TIPO <> ''
                ORDER BY TIPO ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * --------------------------------------------------------------------------
     * Listar grupos
     * --------------------------------------------------------------------------
     */
    public function listarGrupos(): array
    {
        $sql = "SELECT DISTINCT GRUPO
                FROM digitadores
                WHERE GRUPO IS NOT NULL
                  AND GRUPO <> ''
                ORDER BY GRUPO ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * --------------------------------------------------------------------------
     * Listar condiciones
     * --------------------------------------------------------------------------
     */
    public function listarCondiciones(): array
    {
        $sql = "SELECT DISTINCT CONDICION
                FROM digitadores
                WHERE CONDICION IS NOT NULL
                  AND CONDICION <> ''
                ORDER BY CONDICION ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * --------------------------------------------------------------------------
     * Exportar digitadores
     * --------------------------------------------------------------------------
     */
    public function exportar(
        string $buscar = '',
        string $condicion = '',
        string $tipo = '',
        string $grupo = ''
    ): PDOStatement {
        $sql = "SELECT
                    IdDIGITADOR,
                    NOMBRES,
                    DNI,
                    FECHA_NACIMIENTO,
                    CONDICION,
                    FECHA_INGRESO,
                    TELEFONO,
                    CORREO,
                    TIPO,
                    GRUPO,
                    META_DIARIA
                FROM digitadores";

        $condiciones = [];
        $parametros = [];

        /**
         * ----------------------------------------------------------------------
         * Búsqueda
         * ----------------------------------------------------------------------
         */
        if ($buscar !== '') {
            $condiciones[] = "(
                NOMBRES LIKE ?
                OR DNI LIKE ?
            )";

            $valor = '%' . $buscar . '%';

            $parametros[] = $valor;
            $parametros[] = $valor;
        }

        /**
         * ----------------------------------------------------------------------
         * Condición
         * ----------------------------------------------------------------------
         */
        if ($condicion !== '') {
            $condiciones[] = "CONDICION = ?";
            $parametros[] = $condicion;
        }

        /**
         * ----------------------------------------------------------------------
         * Tipo
         * ----------------------------------------------------------------------
         */
        if ($tipo !== '') {
            $condiciones[] = "TIPO = ?";
            $parametros[] = $tipo;
        }

        /**
         * ----------------------------------------------------------------------
         * Grupo
         * ----------------------------------------------------------------------
         */
        if ($grupo !== '') {
            $condiciones[] = "GRUPO = ?";
            $parametros[] = $grupo;
        }

        if (!empty($condiciones)) {
            $sql .= " WHERE " . implode(" AND ", $condiciones);
        }

        $sql .= "
            ORDER BY NOMBRES ASC, IdDIGITADOR ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        return $stmt;
    }

    /**
     * --------------------------------------------------------------------------
     * Crear digitador
     * --------------------------------------------------------------------------
     */
    public function crear(array $datos): bool
    {
        $sql = "INSERT INTO digitadores (
                    NOMBRES,
                    DNI,
                    FECHA_NACIMIENTO,
                    CONDICION,
                    FECHA_INGRESO,
                    TELEFONO,
                    CORREO,
                    TIPO,
                    IMG,
                    GRUPO,
                    META_DIARIA
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $datos['NOMBRES'] ?? null,
            $datos['DNI'] ?? null,
            $datos['FECHA_NACIMIENTO'] ?? null,
            $datos['CONDICION'] ?? null,
            $datos['FECHA_INGRESO'] ?? null,
            $datos['TELEFONO'] ?? null,
            $datos['CORREO'] ?? null,
            $datos['TIPO'] ?? null,
            $datos['IMG'] ?? null,
            $datos['GRUPO'] ?? null,
            $datos['META_DIARIA'] ?? null
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Actualizar digitador
     * --------------------------------------------------------------------------
     */
    public function actualizar(
        int $id,
        array $datos
    ): bool {
        $sql = "UPDATE digitadores
                SET
                    NOMBRES = ?,
                    DNI = ?,
                    FECHA_NACIMIENTO = ?,
                    CONDICION = ?,
                    FECHA_INGRESO = ?,
                    TELEFONO = ?,
                    CORREO = ?,
                    TIPO = ?,
                    IMG = ?,
                    GRUPO = ?,
                    META_DIARIA = ?
                WHERE IdDIGITADOR = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $datos['NOMBRES'] ?? null,
            $datos['DNI'] ?? null,
            $datos['FECHA_NACIMIENTO'] ?? null,
            $datos['CONDICION'] ?? null,
            $datos['FECHA_INGRESO'] ?? null,
            $datos['TELEFONO'] ?? null,
            $datos['CORREO'] ?? null,
            $datos['TIPO'] ?? null,
            $datos['IMG'] ?? null,
            $datos['GRUPO'] ?? null,
            $datos['META_DIARIA'] ?? null,
            $id
        ]);
    }

    /**
     * --------------------------------------------------------------------------
     * Eliminar digitador
     * --------------------------------------------------------------------------
     */
    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM digitadores
                WHERE IdDIGITADOR = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$id]);
    }
}