<?php

require_once __DIR__ . '/../core/Model.php';

class Ipress extends Model
{
    /**
     * Lista IPRESS activas con paginación y búsqueda.
     */
public function listar(
    int $pagina = 1,
    int $porPagina = 10,
    string $busqueda = '',
    string $estado = '1'
): array {

    $pagina = max(1, $pagina);

    $opciones = [5, 10, 20, 50, 100];

    if (!in_array($porPagina, $opciones, true)) {
        $porPagina = 10;
    }

    $offset = ($pagina - 1) * $porPagina;

    $sql = "SELECT
                IdEESS,
                RENAES,
                IPRESS_ATENC,
                TIPO,
                NIVEL,
                CLAS_NOCLAS,
                COD_PPDD,
                COD_UE,
                UE,
                RED,
                MICRORED,
                PROVINCIA,
                DISTRITO,
                ESTADO,
                GRUPO
            FROM ipress
            WHERE 1=1";

    $parametros = [];

    /*
     * Filtro por estado
     */
    if ($estado === '1') {

        $sql .= " AND ESTADO = 1";

    } elseif ($estado === '0') {

        $sql .= " AND ESTADO = 0";
    }

    /*
     * Filtro por búsqueda
     */
    if ($busqueda !== '') {

        $sql .= " AND (
                    IPRESS_ATENC LIKE ?
                    OR RENAES LIKE ?
                )";

        $texto = '%' . $busqueda . '%';

        $parametros[] = $texto;
        $parametros[] = $texto;
    }

    $sql .= " ORDER BY CAST(RENAES AS UNSIGNED) ASC
              LIMIT " . (int) $porPagina . "
              OFFSET " . (int) $offset;

    $stmt = $this->db->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll();
}


public function total(
    string $busqueda = '',
    string $estado = '1'
): int {

    $sql = "SELECT COUNT(*)
            FROM ipress
            WHERE 1=1";

    $parametros = [];

    /*
     * Filtro por estado
     */
    if ($estado === '1') {

        $sql .= " AND ESTADO = 1";

    } elseif ($estado === '0') {

        $sql .= " AND ESTADO = 0";
    }

    /*
     * Filtro por búsqueda
     */
    if ($busqueda !== '') {

        $sql .= " AND (
                    IPRESS_ATENC LIKE ?
                    OR RENAES LIKE ?
                )";

        $texto = '%' . $busqueda . '%';

        $parametros[] = $texto;
        $parametros[] = $texto;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($parametros);

    return (int) $stmt->fetchColumn();
}


    /**
     * Busca una IPRESS activa por IdEESS.
     */
    public function buscarPorId(int $idEESS): ?array
    {
        $sql = "SELECT
                    IdEESS,
                    RENAES,
                    IPRESS_ATENC,
                    TIPO,
                    NIVEL,
                    CLAS_NOCLAS,
                    COD_PPDD,
                    COD_UE,
                    UE,
                    RED,
                    MICRORED,
                    PROVINCIA,
                    DISTRITO,
                    LATITUD_GEOGRAFICA,
                    LONGITUD_GEOGRAFICA,
                    COORDENADA_ESTE,
                    COORDENADA_NORTE,
                    ESTADO,
                    GRUPO
                FROM ipress
                WHERE IdEESS = ?
                AND ESTADO = 1
                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$idEESS]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }
public function listarParaExportar(string $busqueda = ''): array
{
    $sql = "SELECT
                RENAES,
                IPRESS_ATENC,
                TIPO,
                NIVEL,
                UE,
                RED,
                MICRORED,
                PROVINCIA,
                DISTRITO,
                ESTADO
            FROM ipress
            WHERE ESTADO = 1";

    $parametros = [];

    if ($busqueda !== '') {
        $sql .= " AND (
                    IPRESS_ATENC LIKE ?
                    OR RENAES LIKE ?
                )";

        $texto = '%' . $busqueda . '%';

        $parametros[] = $texto;
        $parametros[] = $texto;
    }

    $sql .= " ORDER BY CAST(RENAES AS UNSIGNED) ASC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute($parametros);

    return $stmt->fetchAll();
}
public function listarUnidadesEjecutoras(): array
{
    $sql = "SELECT DISTINCT
                COD_UE,
                UE
            FROM ipress
            WHERE ESTADO = 1
              AND COD_UE IS NOT NULL
              AND TRIM(COD_UE) <> ''
              AND UE IS NOT NULL
              AND TRIM(UE) <> ''
            ORDER BY UE";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

public function listarRedesPorUE(string $codUE): array
{
    $sql = "SELECT DISTINCT
                RED
            FROM ipress
            WHERE ESTADO = 1
              AND COD_UE = ?
              AND RED IS NOT NULL
              AND TRIM(RED) <> ''
            ORDER BY RED";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$codUE]);

    return $stmt->fetchAll();
}

public function listarMicroredesPorRed(
    string $codUE,
    string $red
): array {
    $sql = "SELECT DISTINCT
                MICRORED
            FROM ipress
            WHERE ESTADO = 1
              AND COD_UE = ?
              AND RED = ?
              AND MICRORED IS NOT NULL
              AND TRIM(MICRORED) <> ''
            ORDER BY MICRORED";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([
        $codUE,
        $red
    ]);

    return $stmt->fetchAll();
}

public function listarProvincias(): array
{
    $sql = "SELECT DISTINCT
                PROVINCIA
            FROM ipress
            WHERE ESTADO = 1
              AND PROVINCIA IS NOT NULL
              AND TRIM(PROVINCIA) <> ''
            ORDER BY PROVINCIA";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}

public function listarDistritosPorProvincia(
    string $provincia
): array {
    $sql = "SELECT DISTINCT
                DISTRITO
            FROM ipress
            WHERE ESTADO = 1
            AND PROVINCIA = ?
            AND DISTRITO IS NOT NULL
            AND TRIM(DISTRITO) <> ''
            ORDER BY DISTRITO";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$provincia]);

    return $stmt->fetchAll();
}

public function existeRenaes(
    string $renaes,
    ?int $idEESS = null
): bool {
    $sql = "SELECT COUNT(*)
            FROM ipress
            WHERE RENAES = ?";

    $parametros = [$renaes];

    if ($idEESS !== null) {
        $sql .= " AND IdEESS <> ?";
        $parametros[] = $idEESS;
    }

    $stmt = $this->db->prepare($sql);
    $stmt->execute($parametros);

    return (int) $stmt->fetchColumn() > 0;
}

public function crear(array $datos): int
{
    $sql = "INSERT INTO ipress (
                RENAES,
                IPRESS_ATENC,
                TIPO,
                NIVEL,
                CLAS_NOCLAS,
                COD_PPDD,
                COD_UE,
                UE,
                RED,
                MICRORED,
                PROVINCIA,
                DISTRITO,
                LATITUD_GEOGRAFICA,
                LONGITUD_GEOGRAFICA,
                COORDENADA_ESTE,
                COORDENADA_NORTE,
                ESTADO,
                GRUPO
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, 1, ?
            )";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $datos['RENAES'],
        $datos['IPRESS_ATENC'],
        $datos['TIPO'],
        $datos['NIVEL'],
        $datos['CLAS_NOCLAS'],
        $datos['COD_PPDD'],
        $datos['COD_UE'],
        $datos['UE'],
        $datos['RED'],
        $datos['MICRORED'],
        $datos['PROVINCIA'],
        $datos['DISTRITO'],
        $datos['LATITUD_GEOGRAFICA'],
        $datos['LONGITUD_GEOGRAFICA'],
        $datos['COORDENADA_ESTE'],
        $datos['COORDENADA_NORTE'],
        $datos['GRUPO']
    ]);

    return (int) $this->db->lastInsertId();
}

public function buscarPorIdDetalle(int $idEESS): ?array
{
    $sql = "SELECT
                IdEESS,
                RENAES,
                IPRESS_ATENC,
                TIPO,
                NIVEL,
                CLAS_NOCLAS,
                COD_PPDD,
                COD_UE,
                UE,
                RED,
                MICRORED,
                PROVINCIA,
                DISTRITO,
                LATITUD_GEOGRAFICA,
                LONGITUD_GEOGRAFICA,
                COORDENADA_ESTE,
                COORDENADA_NORTE,
                ESTADO,
                GRUPO
            FROM ipress
            WHERE IdEESS = ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$idEESS]);

    $resultado = $stmt->fetch();

    return $resultado ?: null;
}

public function buscarPorIdParaEditar(int $idEESS): ?array
{
    $sql = "SELECT
                IdEESS,
                RENAES,
                IPRESS_ATENC,
                TIPO,
                NIVEL,
                CLAS_NOCLAS,
                COD_PPDD,
                COD_UE,
                UE,
                RED,
                MICRORED,
                PROVINCIA,
                DISTRITO,
                LATITUD_GEOGRAFICA,
                LONGITUD_GEOGRAFICA,
                COORDENADA_ESTE,
                COORDENADA_NORTE,
                ESTADO,
                GRUPO
            FROM ipress
            WHERE IdEESS = ?
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->execute([$idEESS]);

    $resultado = $stmt->fetch();

    return $resultado ?: null;
}

public function actualizar(int $idEESS, array $datos): bool
{
    $sql = "UPDATE ipress
            SET
                RENAES = ?,
                IPRESS_ATENC = ?,
                TIPO = ?,
                NIVEL = ?,
                CLAS_NOCLAS = ?,
                COD_PPDD = ?,
                COD_UE = ?,
                UE = ?,
                RED = ?,
                MICRORED = ?,
                PROVINCIA = ?,
                DISTRITO = ?,
                LATITUD_GEOGRAFICA = ?,
                LONGITUD_GEOGRAFICA = ?,
                COORDENADA_ESTE = ?,
                COORDENADA_NORTE = ?,
                ESTADO = ?,
                GRUPO = ?
            WHERE IdEESS = ?";

    $stmt = $this->db->prepare($sql);

    return $stmt->execute([
        $datos['RENAES'],
        $datos['IPRESS_ATENC'],
        $datos['TIPO'],
        $datos['NIVEL'],
        $datos['CLAS_NOCLAS'],
        $datos['COD_PPDD'],
        $datos['COD_UE'],
        $datos['UE'],
        $datos['RED'],
        $datos['MICRORED'],
        $datos['PROVINCIA'],
        $datos['DISTRITO'],
        $datos['LATITUD_GEOGRAFICA'],
        $datos['LONGITUD_GEOGRAFICA'],
        $datos['COORDENADA_ESTE'],
        $datos['COORDENADA_NORTE'],
        $datos['ESTADO'],
        $datos['GRUPO'],
        $idEESS
    ]);
}

}