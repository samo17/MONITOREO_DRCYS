<?php

require_once __DIR__ . '/../core/Model.php';

class OcurrenciaCA extends Model
{
    public function listar(
        string $buscar = '',
        ?string $dniResponsableAfiliacion = null,
        ?string $fechaInicio = null,
        ?string $fechaFin = null,
        ?string $codUE = null,
        int $limite = 10,
        int $offset = 0
    ): array {

        $sql = "SELECT
                    o.IdOcurrencia,
                    o.FechaRegistro,
                    o.IdAfiliados,
                    o.IdIAFAS,
                    o.NombrePaciente,
                    o.SexoPaciente,
                    o.FechaNacimientoPaciente,
                    o.DNI_PACIENTE,
                    o.NroFormato,
                    o.FECHA_PROBABLE_PARTO,
                    o.GESTANTE,
                    o.EMERGENCIAS,
                    o.PRESENCIAL,
                    o.DISTRITO,
                    o.COMUNIDAD,
                    o.DIRECCION,
                    o.REFERENCIA,
                    o.IdEESS,
                    o.OBSERVACIONES,
                    o.PERSONA_RESPONSABLE,
                    o.DNI_RESPONSABLE,
                    o.FONO_RESPONSABLE,
                    o.DNI_RESPONSABLE_AFILIACION,

                    COALESCE(a.NombreAsegurado, o.NombrePaciente) AS NombreAsegurado,
                    ia.Nombre AS IAFAS,

                    (
                        SELECT GROUP_CONCAT(
                            t.Descripcion
                            ORDER BY t.Descripcion
                            SEPARATOR ', '
                        )
                        FROM OcurrenciasCA_Detalle d
                        INNER JOIN TiposOcurrencia t
                            ON t.IdTipoOcurrencia = d.IdTipoOcurrencia
                        WHERE d.IdOcurrencia = o.IdOcurrencia
                    ) AS TiposOcurrencia,

                    i.IPRESS_ATENC,

                    u.Nombres AS NombresResponsableAfiliacion,
                    u.Apellidos AS ApellidosResponsableAfiliacion,
                    u.DNI AS DNIUsuarioResponsable

                FROM OcurrenciasCA o

                LEFT JOIN afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

                LEFT JOIN iafas ia
                    ON ia.IdIAFAS = o.IdIAFAS

                LEFT JOIN ipress i
                    ON i.IdEESS = o.IdEESS

                LEFT JOIN Usuarios u
                    ON u.DNI = o.DNI_RESPONSABLE_AFILIACION";

        $condiciones = [];
        $parametros = [];

        /*
         * Búsqueda general
         */
        if ($buscar !== '') {

            $condiciones[] = "(
                o.DNI_PACIENTE LIKE ?
                OR COALESCE(a.NombreAsegurado, o.NombrePaciente) LIKE ?
                OR o.NroFormato LIKE ?
                OR o.COMUNIDAD LIKE ?
                OR o.PERSONA_RESPONSABLE LIKE ?
                OR u.Nombres LIKE ?
                OR u.Apellidos LIKE ?
                OR u.DNI LIKE ?
            )";

            $valorBuscar = '%' . $buscar . '%';

            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
        }

        /*
         * Filtro por responsable de afiliación
         */
        if (
            $dniResponsableAfiliacion !== null &&
            $dniResponsableAfiliacion !== ''
        ) {

            $condiciones[] =
                "o.DNI_RESPONSABLE_AFILIACION = ?";

            $parametros[] =
                $dniResponsableAfiliacion;
        }

        /*
         * Filtro por fecha inicial
         */
        if (
            $fechaInicio !== null &&
            $fechaInicio !== ''
        ) {

            $condiciones[] =
                "o.FechaRegistro >= ?";

            $parametros[] =
                $fechaInicio . ' 00:00:00';
        }

        /*
         * Filtro por fecha final
         */
        if (
            $fechaFin !== null &&
            $fechaFin !== ''
        ) {

            $condiciones[] =
                "o.FechaRegistro <= ?";

            $parametros[] =
                $fechaFin . ' 23:59:59';
        }

        /*
         * Filtro por Unidad Ejecutora
         */
        if (
            $codUE !== null &&
            $codUE !== ''
        ) {
            $condiciones[] = "i.COD_UE = ?";
            $parametros[] = $codUE;
        }

        /*
         * Aplicar condiciones
         */
        if (!empty($condiciones)) {

            $sql .= " WHERE " .
                implode(
                    " AND ",
                    $condiciones
                );
        }

        /*
         * Orden y paginación
         */
        $sql .= " ORDER BY o.IdOcurrencia DESC
                  LIMIT ? OFFSET ?";

        $stmt = $this->db->prepare($sql);

        /*
         * Parámetros de búsqueda
         */
        foreach (
            $parametros as $indice => $parametro
        ) {

            $stmt->bindValue(
                $indice + 1,
                $parametro,
                PDO::PARAM_STR
            );
        }

        /*
         * LIMIT
         */
        $stmt->bindValue(
            count($parametros) + 1,
            $limite,
            PDO::PARAM_INT
        );

        /*
         * OFFSET
         */
        $stmt->bindValue(
            count($parametros) + 2,
            $offset,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll();
    }


    public function contar(
        string $buscar = '',
        ?string $dniResponsableAfiliacion = null,
        ?string $fechaInicio = null,
        ?string $fechaFin = null,
        ?string $codUE = null
    ): int {

        $sql = "SELECT COUNT(*)

                FROM OcurrenciasCA o

                LEFT JOIN afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

                LEFT JOIN iafas ia
                    ON ia.IdIAFAS = o.IdIAFAS

                LEFT JOIN ipress i
                    ON i.IdEESS = o.IdEESS

                LEFT JOIN Usuarios u
                    ON u.DNI = o.DNI_RESPONSABLE_AFILIACION";

        $condiciones = [];
        $parametros = [];

        /*
         * Búsqueda general
         */
        if ($buscar !== '') {

            $condiciones[] = "(
                o.DNI_PACIENTE LIKE ?
                OR COALESCE(a.NombreAsegurado, o.NombrePaciente) LIKE ?
                OR o.NroFormato LIKE ?
                OR o.COMUNIDAD LIKE ?
                OR o.PERSONA_RESPONSABLE LIKE ?
                OR u.Nombres LIKE ?
                OR u.Apellidos LIKE ?
                OR u.DNI LIKE ?
            )";

            $valorBuscar = '%' . $buscar . '%';

            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
            $parametros[] = $valorBuscar;
        }

        /*
         * Filtro por responsable
         */
        if (
            $dniResponsableAfiliacion !== null &&
            $dniResponsableAfiliacion !== ''
        ) {

            $condiciones[] =
                "o.DNI_RESPONSABLE_AFILIACION = ?";

            $parametros[] =
                $dniResponsableAfiliacion;
        }

        /*
         * Filtro por fecha inicial
         */
        if (
            $fechaInicio !== null &&
            $fechaInicio !== ''
        ) {

            $condiciones[] =
                "o.FechaRegistro >= ?";

            $parametros[] =
                $fechaInicio . ' 00:00:00';
        }

        /*
         * Filtro por fecha final
         */
        if (
            $fechaFin !== null &&
            $fechaFin !== ''
        ) {

            $condiciones[] =
                "o.FechaRegistro <= ?";

            $parametros[] =
                $fechaFin . ' 23:59:59';
        }

        /*
         * Filtro por Unidad Ejecutora
         */
        if (
            $codUE !== null &&
            $codUE !== ''
        ) {
            $condiciones[] = "i.COD_UE = ?";
            $parametros[] = $codUE;
        }

        /*
         * Aplicar condiciones
         */
        if (!empty($condiciones)) {

            $sql .= " WHERE " .
                implode(
                    " AND ",
                    $condiciones
                );
        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute(
            $parametros
        );

        return (int) $stmt->fetchColumn();
    }


    public function buscarPorId(
        int $idOcurrencia
    ): ?array {

        $sql = "SELECT
                    o.IdOcurrencia,
                    o.FechaRegistro,
                    o.IdAfiliados,
                    o.IdIAFAS,
                    o.NombrePaciente,
                    o.SexoPaciente,
                    o.FechaNacimientoPaciente,
                    o.DNI_PACIENTE,
                    o.NroFormato,
                    o.FECHA_PROBABLE_PARTO,
                    o.GESTANTE,
                    o.EMERGENCIAS,
                    o.PRESENCIAL,
                    o.DISTRITO,
                    o.COMUNIDAD,
                    o.DIRECCION,
                    o.REFERENCIA,
                    o.IdEESS,
                    o.OBSERVACIONES,
                    o.PERSONA_RESPONSABLE,
                    o.DNI_RESPONSABLE,
                    o.FONO_RESPONSABLE,
                    o.DNI_RESPONSABLE_AFILIACION,

                    COALESCE(a.NumeroDni, o.DNI_PACIENTE) AS NumeroDni,
                    COALESCE(a.NombreAsegurado, o.NombrePaciente) AS NombreAsegurado,
                    COALESCE(a.Sexo, o.SexoPaciente) AS Sexo,
                    COALESCE(a.FechaNacimiento, o.FechaNacimientoPaciente) AS FechaNacimiento,
                    ia.Nombre AS IAFAS,
                    a.EessAdscrito,
                    a.Provincia,
                    a.Distrito,

                    i.IPRESS_ATENC,
                    u.Nombres AS NombresResponsableAfiliacion,
                    u.Apellidos AS ApellidosResponsableAfiliacion,
                    (
                        SELECT GROUP_CONCAT(
                            t.Descripcion
                            ORDER BY t.Descripcion
                            SEPARATOR ', '
                        )
                        FROM OcurrenciasCA_Detalle d
                        INNER JOIN TiposOcurrencia t
                            ON t.IdTipoOcurrencia = d.IdTipoOcurrencia
                        WHERE d.IdOcurrencia = o.IdOcurrencia
                    ) AS TiposOcurrencia

                FROM OcurrenciasCA o

                LEFT JOIN afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

                LEFT JOIN iafas ia
                    ON ia.IdIAFAS = o.IdIAFAS

                LEFT JOIN ipress i
                    ON i.IdEESS = o.IdEESS

                LEFT JOIN Usuarios u
                    ON u.DNI = o.DNI_RESPONSABLE_AFILIACION

                WHERE o.IdOcurrencia = ?

                LIMIT 1";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $idOcurrencia
        ]);

        $resultado = $stmt->fetch();

        return $resultado ?: null;
    }


    public function crearConTipos(
        ?int $idAfiliados,
        string $dniPaciente,
        ?string $fechaProbableParto,
        ?string $comunidad,
        ?string $direccion,
        ?string $referencia,
        ?int $idEess,
        ?string $observaciones,
        ?string $personaResponsable,
        ?string $dniResponsable,
        ?string $fonoResponsable,
        string $dniResponsableAfiliacion,
        array $tipos,
        ?string $nroFormato = null,
        ?string $gestante = null,
        ?string $emergencias = null,
        ?string $presencial = null,
        ?string $distrito = null,
        ?int $idIAFAS = null,
        ?string $nombrePaciente = null,
        ?string $sexoPaciente = null,
        ?string $fechaNacimientoPaciente = null
    ): int {

        try {

            $this->db->beginTransaction();

            $sql = "INSERT INTO OcurrenciasCA (
                        IdAfiliados,
                        IdIAFAS,
                        DNI_PACIENTE,
                        NombrePaciente,
                        SexoPaciente,
                        FechaNacimientoPaciente,
                        NroFormato,
                        FECHA_PROBABLE_PARTO,
                        GESTANTE,
                        EMERGENCIAS,
                        PRESENCIAL,
                        COMUNIDAD,
                        DIRECCION,
                        REFERENCIA,
                        IdEESS,
                        DISTRITO,
                        OBSERVACIONES,
                        PERSONA_RESPONSABLE,
                        DNI_RESPONSABLE,
                        FONO_RESPONSABLE,
                        DNI_RESPONSABLE_AFILIACION
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $idAfiliados,
                $idIAFAS,
                $dniPaciente,
                $nombrePaciente,
                $sexoPaciente,
                $fechaNacimientoPaciente,
                $nroFormato,
                $fechaProbableParto,
                $gestante,
                $emergencias,
                $presencial,
                $comunidad,
                $direccion,
                $referencia,
                $idEess,
                $distrito,
                $observaciones,
                $personaResponsable,
                $dniResponsable,
                $fonoResponsable,
                $dniResponsableAfiliacion
            ]);

            $idOcurrencia =
                (int) $this->db->lastInsertId();

            if (!empty($tipos)) {

                $sqlTipo = "INSERT INTO OcurrenciasCA_Detalle (
                                IdOcurrencia,
                                IdTipoOcurrencia
                            ) VALUES (?, ?)";

                $stmtTipo =
                    $this->db->prepare($sqlTipo);

                foreach ($tipos as $idTipo) {

                    $stmtTipo->execute([
                        $idOcurrencia,
                        (int) $idTipo
                    ]);
                }
            }

            $this->db->commit();

            return $idOcurrencia;

        } catch (Throwable $e) {

            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw $e;
        }
    }


    public function actualizar(
        int $idOcurrencia,
        ?int $idAfiliados,
        string $dniPaciente,
        ?string $fechaProbableParto,
        ?string $comunidad,
        ?string $direccion,
        ?string $referencia,
        ?int $idEess,
        ?string $observaciones,
        ?string $personaResponsable,
        ?string $dniResponsable,
        ?string $fonoResponsable,
        ?string $nroFormato = null,
        ?string $gestante = null,
        ?string $emergencias = null,
        ?string $presencial = null,
        ?string $distrito = null,
        ?int $idIAFAS = null,
        ?string $nombrePaciente = null,
        ?string $sexoPaciente = null,
        ?string $fechaNacimientoPaciente = null
    ): bool {

        $sql = "UPDATE OcurrenciasCA
                SET
                    IdAfiliados = ?,
                    IdIAFAS = ?,
                    DNI_PACIENTE = ?,
                    NombrePaciente = ?,
                    SexoPaciente = ?,
                    FechaNacimientoPaciente = ?,
                    NroFormato = ?,
                    FECHA_PROBABLE_PARTO = ?,
                    GESTANTE = ?,
                    EMERGENCIAS = ?,
                    PRESENCIAL = ?,
                    COMUNIDAD = ?,
                    DIRECCION = ?,
                    REFERENCIA = ?,
                    IdEESS = ?,
                    DISTRITO = ?,
                    OBSERVACIONES = ?,
                    PERSONA_RESPONSABLE = ?,
                    DNI_RESPONSABLE = ?,
                    FONO_RESPONSABLE = ?
                WHERE IdOcurrencia = ?";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $idAfiliados,
            $idIAFAS,
            $dniPaciente,
            $nombrePaciente,
            $sexoPaciente,
            $fechaNacimientoPaciente,
            $nroFormato,
            $fechaProbableParto,
            $gestante,
            $emergencias,
            $presencial,
            $comunidad,
            $direccion,
            $referencia,
            $idEess,
            $distrito,
            $observaciones,
            $personaResponsable,
            $dniResponsable,
            $fonoResponsable,
            $idOcurrencia
        ]);
    }


    public function listarIafasActivas(): array
    {
        $stmt = $this->db->query("SELECT IdIAFAS, Nombre FROM iafas WHERE Estado = 1 ORDER BY Nombre ASC");
        return $stmt->fetchAll();
    }

    public function buscarIAFAS(int $idIAFAS): ?array
    {
        $stmt = $this->db->prepare("SELECT IdIAFAS, Nombre, Estado FROM iafas WHERE IdIAFAS = ? LIMIT 1");
        $stmt->execute([$idIAFAS]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function existeDuplicadoPorDNI(int $idIAFAS, string $dni, array $tipos, ?string $fecha = null, ?int $idExcluir = null): bool
    {
        if (!$tipos) return false;
        $fecha = $fecha ?: date('Y-m-d');
        $marks = implode(',', array_fill(0, count($tipos), '?'));
        $sql = "SELECT 1 FROM OcurrenciasCA o
                INNER JOIN OcurrenciasCA_Detalle d ON d.IdOcurrencia = o.IdOcurrencia
                WHERE o.IdIAFAS = ? AND o.DNI_PACIENTE = ?
                  AND DATE(o.FechaRegistro) = DATE(?)
                  AND d.IdTipoOcurrencia IN ($marks)";
        $params = [$idIAFAS, $dni, $fecha];
        foreach ($tipos as $tipo) $params[] = (int)$tipo;
        if ($idExcluir !== null) { $sql .= " AND o.IdOcurrencia <> ?"; $params[] = $idExcluir; }
        $sql .= " LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool)$stmt->fetchColumn();
    }

    public function listarTipos(
    int $idOcurrencia
): array {

    $sql = "SELECT
                d.IdTipoOcurrencia
            FROM OcurrenciasCA_Detalle d
            WHERE d.IdOcurrencia = ?
            ORDER BY d.IdTipoOcurrencia ASC";

    $stmt = $this->db->prepare($sql);

    $stmt->execute([
        $idOcurrencia
    ]);

    return $stmt->fetchAll(
        PDO::FETCH_COLUMN
    );
}


    public function listarTiposActivos(): array
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


    public function guardarTipos(
        int $idOcurrencia,
        array $tipos
    ): void {

        $sqlEliminar =
            "DELETE FROM OcurrenciasCA_Detalle
             WHERE IdOcurrencia = ?";

        $stmtEliminar =
            $this->db->prepare($sqlEliminar);

        $stmtEliminar->execute([
            $idOcurrencia
        ]);

        if (empty($tipos)) {
            return;
        }

        $sqlInsertar =
            "INSERT INTO OcurrenciasCA_Detalle (
                IdOcurrencia,
                IdTipoOcurrencia
            ) VALUES (?, ?)";

        $stmtInsertar =
            $this->db->prepare($sqlInsertar);

        foreach ($tipos as $idTipo) {

            $stmtInsertar->execute([
                $idOcurrencia,
                (int) $idTipo
            ]);
        }
    }


    public function listarUnidadesEjecutoras(): array
    {
        $sql = "SELECT DISTINCT
                    TRIM(i.COD_UE) AS COD_UE,
                    TRIM(i.UE) AS UE
                FROM OcurrenciasCA o
                INNER JOIN ipress i
                    ON i.IdEESS = o.IdEESS
                WHERE i.COD_UE IS NOT NULL
                  AND TRIM(i.COD_UE) <> ''
                ORDER BY TRIM(i.UE) ASC, TRIM(i.COD_UE) ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }


 public function listarResponsablesAfiliacion(): array
{
    $sql = "SELECT DISTINCT
                u.DNI,
                u.Nombres,
                u.Apellidos
            FROM usuarios u

            INNER JOIN usuario_permiso up
                ON up.IdUsuario = u.IdUsuario

            INNER JOIN permisos p
                ON p.IdPermiso = up.IdPermiso

            WHERE u.DNI IS NOT NULL
              AND TRIM(u.DNI) <> ''
              AND u.Estado = 1
              AND p.Nombre = 'OCURRENCIAS_CA'
              AND p.Estado = 1

            ORDER BY
                u.Apellidos ASC,
                u.Nombres ASC";

    $stmt = $this->db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll();
}


    /**
     * Comprueba si el paciente ya tiene registrado alguno de los tipos
     * indicados en la misma fecha. En edición, permite excluir el registro
     * que se está modificando.
     *
     * Si no se indica fecha, se utiliza la fecha actual para nuevos registros.
     */
    public function existeDuplicado(
        int $idAfiliados,
        array $tipos,
        ?string $fechaRegistro = null,
        ?int $idOcurrenciaExcluir = null
    ): bool {
        if (empty($tipos)) {
            return false;
        }

        $fecha = ($fechaRegistro !== null && trim($fechaRegistro) !== '')
            ? $fechaRegistro
            : date('Y-m-d');

        $marcadores = implode(',', array_fill(0, count($tipos), '?'));

        $sql = "SELECT 1
                FROM OcurrenciasCA o
                INNER JOIN OcurrenciasCA_Detalle d
                    ON d.IdOcurrencia = o.IdOcurrencia
                WHERE o.IdAfiliados = ?
                  AND DATE(o.FechaRegistro) = DATE(?)
                  AND d.IdTipoOcurrencia IN ($marcadores)";

        $parametros = [$idAfiliados, $fecha];
        foreach ($tipos as $idTipo) {
            $parametros[] = (int) $idTipo;
        }

        if ($idOcurrenciaExcluir !== null) {
            $sql .= " AND o.IdOcurrencia <> ?";
            $parametros[] = $idOcurrenciaExcluir;
        }

        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($parametros);

        return (bool) $stmt->fetchColumn();
    }

}