<?php

require_once __DIR__ . '/../core/Model.php';

class OcurrenciaCA extends Model
{
    public function listar(
        string $buscar = '',
        ?string $dniResponsableAfiliacion = null,
        ?string $fechaInicio = null,
        ?string $fechaFin = null,
        int $limite = 10,
        int $offset = 0
    ): array {

        $sql = "SELECT
                    o.IdOcurrencia,
                    o.FechaRegistro,
                    o.IdAfiliados,
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

                    a.NombreAsegurado,

                    i.IPRESS_ATENC,

                    u.Nombres AS NombresResponsableAfiliacion,
                    u.Apellidos AS ApellidosResponsableAfiliacion,
                    u.DNI AS DNIUsuarioResponsable

                FROM OcurrenciasCA o

                INNER JOIN maestro_afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

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
                OR a.NombreAsegurado LIKE ?
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
        ?string $fechaFin = null
    ): int {

        $sql = "SELECT COUNT(*)

                FROM OcurrenciasCA o

                INNER JOIN maestro_afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

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
                OR a.NombreAsegurado LIKE ?
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

                    a.NumeroDni,
                    a.NombreAsegurado,
                    a.Sexo,
                    a.FechaNacimiento,
                    a.EessAdscrito,
                    a.Provincia,
                    a.Distrito,

                    i.IPRESS_ATENC

                FROM OcurrenciasCA o

                INNER JOIN maestro_afiliados a
                    ON a.IdAfiliados = o.IdAfiliados

                LEFT JOIN ipress i
                    ON i.IdEESS = o.IdEESS

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
        int $idAfiliados,
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
        ?string $distrito = null
    ): int {

        try {

            $this->db->beginTransaction();

            $sql = "INSERT INTO OcurrenciasCA (
                        IdAfiliados,
                        DNI_PACIENTE,
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
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                $idAfiliados,
                $dniPaciente,
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
        int $idAfiliados,
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
        ?string $distrito = null
    ): bool {

        $sql = "UPDATE OcurrenciasCA
                SET
                    IdAfiliados = ?,
                    DNI_PACIENTE = ?,
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
            $dniPaciente,
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
}