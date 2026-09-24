<?php

require_once __DIR__ . '/../core/Model.php';

class Afiliado extends Model
{
    /**
     * Buscar afiliados por DNI o nombre.
     * Muestra registros activos e inactivos.
     */
    public function buscar(
        string $buscar = '',
        string $ue = '',
        string $red = '',
        string $microred = '',
        string $idEess = '',
        int $limite = 20,
        int $offset = 0
    ): array {

        $sql = "SELECT
                    a.IdAfiliados,
                    a.IdSiasis,
                    a.TipoTabla,
                    a.NroFormato,
                    a.Correlativo,
                    a.TipoDocumento,
                    a.FechaFormato,
                    a.NumeroDni,
                    a.NombreAsegurado,
                    a.Sexo,
                    a.FechaNacimiento,
                    a.Estado,
                    a.FechaBaja,
                    a.EessAdscrito,
                    a.Provincia,
                    a.Distrito,
                    a.GrupoPoblacional,
                    a.MotivoBaja,
                    i.IdEESS,
                    i.RENAES,
                    i.IPRESS_ATENC,
                    i.COD_UE,
                    i.UE,
                    i.RED,
                    i.MICRORED
                FROM maestro_afiliados a

                LEFT JOIN ipress i
                    ON CAST(i.RENAES AS UNSIGNED) =
                       CAST(LEFT(a.EessAdscrito, 10) AS UNSIGNED)";

        $condiciones = [];
        $parametros = [];

        /**
         * Búsqueda por DNI o nombre
         */
        if ($buscar !== '') {

            $condiciones[] = "(
                a.NumeroDni LIKE ?
                OR a.NombreAsegurado LIKE ?
            )";

            $valorBuscar = '%' . $buscar . '%';

            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
        }

        /**
         * Filtro Unidad Ejecutora
         */
        if ($ue !== '') {

            $condiciones[] = "i.COD_UE = ?";

            $parametros[] = $ue;
        }

        /**
         * Filtro Red
         */
        if ($red !== '') {

            $condiciones[] = "i.RED = ?";

            $parametros[] = $red;
        }

        /**
         * Filtro Microred
         */
        if ($microred !== '') {

            $condiciones[] = "i.MICRORED = ?";

            $parametros[] = $microred;
        }

        /**
         * Filtro IPRESS
         */
        if ($idEess !== '') {

            $condiciones[] = "i.IdEESS = ?";

            $parametros[] = (int) $idEess;
        }

        /**
         * WHERE
         */
        if (!empty($condiciones)) {

            $sql .= " WHERE " . implode(
                " AND ",
                $condiciones
            );
        }

        /**
         * Orden y paginación
         */
        $sql .= "
            ORDER BY
                a.NombreAsegurado ASC,
                a.IdAfiliados ASC
            LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        /**
         * Parámetros de filtros
         */
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

        /**
         * LIMIT
         */
        $stmt->bindValue(
            $indice,
            $limite,
            PDO::PARAM_INT
        );

        $indice++;

        /**
         * OFFSET
         */
        $stmt->bindValue(
            $indice,
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Contar resultados de búsqueda.
     */
    public function contar(
        string $buscar = '',
        string $ue = '',
        string $red = '',
        string $microred = '',
        string $idEess = ''
    ): int {

        $sql = "SELECT COUNT(*)
                FROM maestro_afiliados a

                LEFT JOIN ipress i
                    ON CAST(i.RENAES AS UNSIGNED) =
                       CAST(LEFT(a.EessAdscrito, 10) AS UNSIGNED)";

        $condiciones = [];
        $parametros = [];

        /**
         * Búsqueda por DNI o nombre
         */
        if ($buscar !== '') {

            $condiciones[] = "(
                a.NumeroDni LIKE ?
                OR a.NombreAsegurado LIKE ?
            )";

            $valorBuscar = '%' . $buscar . '%';

            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
        }

        /**
         * Unidad Ejecutora
         */
        if ($ue !== '') {

            $condiciones[] = "i.COD_UE = ?";

            $parametros[] = $ue;
        }

        /**
         * Red
         */
        if ($red !== '') {

            $condiciones[] = "i.RED = ?";

            $parametros[] = $red;
        }

        /**
         * Microred
         */
        if ($microred !== '') {

            $condiciones[] = "i.MICRORED = ?";

            $parametros[] = $microred;
        }

        /**
         * IPRESS
         */
        if ($idEess !== '') {

            $condiciones[] = "i.IdEESS = ?";

            $parametros[] = (int) $idEess;
        }

        if (!empty($condiciones)) {

            $sql .= " WHERE " . implode(
                " AND ",
                $condiciones
            );
        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute($parametros);

        return (int) $stmt->fetchColumn();
    }


    /**
     * Buscar un afiliado por IdAfiliados.
     */
    public function buscarPorId(
        int $idAfiliados
    ): ?array {

        $sql = "SELECT
                    IdAfiliados,
                    IdSiasis,
                    TipoTabla,
                    NroFormato,
                    Correlativo,
                    TipoDocumento,
                    FechaFormato,
                    NumeroDni,
                    NombreAsegurado,
                    Sexo,
                    FechaNacimiento,
                    Estado,
                    FechaBaja,
                    EessAdscrito,
                    Provincia,
                    Distrito,
                    GrupoPoblacional,
                    MotivoBaja
                FROM maestro_afiliados
                WHERE IdAfiliados = ?
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $idAfiliados
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }


    /**
     * Listar Unidades Ejecutoras.
     */
    public function listarUnidadesEjecutoras(): array
    {
        $sql = "SELECT DISTINCT
                    COD_UE,
                    UE
                FROM ipress
                WHERE COD_UE IS NOT NULL
                  AND TRIM(COD_UE) <> ''
                  AND UE IS NOT NULL
                  AND TRIM(UE) <> ''
                ORDER BY UE ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll();
    }


    /**
     * Listar Redes según Unidad Ejecutora.
     */
    public function listarRedes(
        string $ue = ''
    ): array {

        $sql = "SELECT DISTINCT
                    RED
                FROM ipress
                WHERE RED IS NOT NULL
                  AND TRIM(RED) <> ''";

        $parametros = [];

        if ($ue !== '') {

            $sql .= "
                AND COD_UE = ?";

            $parametros[] = $ue;
        }

        $sql .= "
            ORDER BY RED ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute(
            $parametros
        );

        return $stmt->fetchAll();
    }


    /**
     * Listar Microredes según UE y Red.
     */
    public function listarMicroredes(
        string $ue = '',
        string $red = ''
    ): array {

        $sql = "SELECT DISTINCT
                    MICRORED
                FROM ipress
                WHERE MICRORED IS NOT NULL
                  AND TRIM(MICRORED) <> ''";

        $parametros = [];

        if ($ue !== '') {

            $sql .= "
                AND COD_UE = ?";

            $parametros[] = $ue;
        }

        if ($red !== '') {

            $sql .= "
                AND RED = ?";

            $parametros[] = $red;
        }

        $sql .= "
            ORDER BY MICRORED ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute(
            $parametros
        );

        return $stmt->fetchAll();
    }


    /**
     * Listar IPRESS según UE, Red y Microred.
     */
    public function listarIpress(
        string $ue = '',
        string $red = '',
        string $microred = ''
    ): array {

        $sql = "SELECT
                    IdEESS,
                    RENAES,
                    IPRESS_ATENC
                FROM ipress
                WHERE IPRESS_ATENC IS NOT NULL
                  AND TRIM(IPRESS_ATENC) <> ''";

        $parametros = [];

        if ($ue !== '') {

            $sql .= "
                AND COD_UE = ?";

            $parametros[] = $ue;
        }

        if ($red !== '') {

            $sql .= "
                AND RED = ?";

            $parametros[] = $red;
        }

        if ($microred !== '') {

            $sql .= "
                AND MICRORED = ?";

            $parametros[] = $microred;
        }

        $sql .= "
            ORDER BY IPRESS_ATENC ASC";

        $stmt = $this->db->prepare($sql);

        $stmt->execute(
            $parametros
        );

        return $stmt->fetchAll();
    }


    /**
     * Exportar afiliados aplicando los mismos filtros
     * del listado, pero sin paginación.
     *
     * IMPORTANTE:
     * No utiliza fetchAll().
     * Devuelve el PDOStatement para procesar
     * los registros uno por uno y evitar
     * consumir toda la memoria de PHP.
     */
    /**
 * Exportar afiliados aplicando los mismos filtros
 * del listado, pero sin paginación.
 *
 * Utiliza consulta NO BUFFERED para evitar
 * cargar todos los registros en memoria.
 */
public function exportar(
    string $buscar = '',
    string $ue = '',
    string $red = '',
    string $microred = '',
    string $idEess = ''
): PDOStatement {

    $sql = "SELECT
                a.IdAfiliados,
                a.IdSiasis,
                a.TipoTabla,
                a.NroFormato,
                a.Correlativo,
                a.TipoDocumento,
                a.FechaFormato,
                a.NumeroDni,
                a.NombreAsegurado,
                a.Sexo,
                a.FechaNacimiento,
                a.Estado,
                a.FechaBaja,
                a.EessAdscrito,
                a.Provincia,
                a.Distrito,
                a.GrupoPoblacional,
                a.MotivoBaja,
                i.IdEESS,
                i.RENAES,
                i.IPRESS_ATENC,
                i.COD_UE,
                i.UE,
                i.RED,
                i.MICRORED
            FROM maestro_afiliados a

            LEFT JOIN ipress i
                ON CAST(i.RENAES AS UNSIGNED) =
                   CAST(LEFT(a.EessAdscrito, 10) AS UNSIGNED)";

    $condiciones = [];
    $parametros = [];

    /**
     * Búsqueda por DNI o nombre
     */
    if ($buscar !== '') {

        $condiciones[] = "(
            a.NumeroDni LIKE ?
            OR a.NombreAsegurado LIKE ?
        )";

        $valorBuscar = '%' . $buscar . '%';

        $parametros[] = $valorBuscar;
        $parametros[] = $valorBuscar;
    }

    /**
     * Filtro Unidad Ejecutora
     */
    if ($ue !== '') {

        $condiciones[] = "i.COD_UE = ?";

        $parametros[] = $ue;
    }

    /**
     * Filtro Red
     */
    if ($red !== '') {

        $condiciones[] = "i.RED = ?";

        $parametros[] = $red;
    }

    /**
     * Filtro Microred
     */
    if ($microred !== '') {

        $condiciones[] = "i.MICRORED = ?";

        $parametros[] = $microred;
    }

    /**
     * Filtro IPRESS
     */
    if ($idEess !== '') {

        $condiciones[] = "i.IdEESS = ?";

        $parametros[] = (int) $idEess;
    }

    /**
     * WHERE
     */
    if (!empty($condiciones)) {

        $sql .= " WHERE " . implode(
            " AND ",
            $condiciones
        );
    }

    /**
     * Ordenamiento.
     * NO lleva LIMIT ni OFFSET.
     */
    $sql .= "
        ORDER BY
            a.NombreAsegurado ASC,
            a.IdAfiliados ASC";

    /**
     * Preparar consulta.
     */
    $stmt = $this->db->prepare($sql);

    /**
     * Parámetros.
     */
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

    /**
     * Ejecutar consulta SIN BUFFER.
     *
     * Esto evita que PDO/MySQL almacene
     * todos los resultados en memoria.
     */
    $this->db->setAttribute(
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,
        false
    );

    $stmt->execute();

    return $stmt;
}

/**
 * Buscar IPRESS por RENAES o nombre.
 *
 * Permite buscar dentro de los filtros
 * de Unidad Ejecutora, Red y Microred.
 */
public function buscarIpress(
    string $buscar = '',
    string $ue = '',
    string $red = '',
    string $microred = ''
): array {

    $sql = "SELECT
                IdEESS,
                RENAES,
                IPRESS_ATENC,
                COD_UE,
                UE,
                RED,
                MICRORED
            FROM ipress
            WHERE 1 = 1";

    $parametros = [];

    /**
     * Buscar por RENAES o nombre de IPRESS.
     */
    if ($buscar !== '') {

        $sql .= " AND (
                    RENAES LIKE ?
                    OR IPRESS_ATENC LIKE ?
                )";

        $valorBuscar = '%' . $buscar . '%';

        $parametros[] = $valorBuscar;
        $parametros[] = $valorBuscar;
    }

    /**
     * Filtro Unidad Ejecutora.
     */
    if ($ue !== '') {

        $sql .= " AND COD_UE = ?";

        $parametros[] = $ue;
    }

    /**
     * Filtro Red.
     */
    if ($red !== '') {

        $sql .= " AND RED = ?";

        $parametros[] = $red;
    }

    /**
     * Filtro Microred.
     */
    if ($microred !== '') {

        $sql .= " AND MICRORED = ?";

        $parametros[] = $microred;
    }

    /**
     * Solo IPRESS con nombre registrado.
     */
    $sql .= "
        AND IPRESS_ATENC IS NOT NULL
        AND TRIM(IPRESS_ATENC) <> ''
        ORDER BY IPRESS_ATENC ASC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute(
        $parametros
    );

    return $stmt->fetchAll();
}

}