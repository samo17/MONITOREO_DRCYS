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

        /*
         * La vista solo utiliza datos de afiliados.
         * Por ello NO se hace JOIN con ipress cuando no hay filtros
         * de UE, Red, Microred o IPRESS. Esto evita recorrer y
         * transformar 1.1 millones de filas en cada apertura.
         */
        $usarIpress = (
            $ue !== '' ||
            $red !== '' ||
            $microred !== '' ||
            $idEess !== ''
        );

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
                    a.MotivoBaja
                FROM afiliados a";

        if ($usarIpress) {
            $sql .= "
                LEFT JOIN ipress i
                    ON a.EessCodigo = LPAD(i.RENAES, 10, '0')";
        }

        $condiciones = [];
        $parametros = [];

        /* Búsqueda por DNI o nombre. */
        if ($buscar !== '') {
            if (preg_match('/^\d{8}$/', $buscar)) {
                $condiciones[] = 'a.NumeroDni = ?';
                $parametros[] = $buscar;
            } else {
                $condiciones[] = "(
                    a.NumeroDni LIKE ?
                    OR a.NombreAsegurado LIKE ?
                )";

                $valorBuscar = '%' . $buscar . '%';
                $parametros[] = $valorBuscar;
                $parametros[] = $valorBuscar;
            }
        }

        if ($ue !== '') {
            $condiciones[] = 'i.COD_UE = ?';
            $parametros[] = $ue;
        }

        if ($red !== '') {
            $condiciones[] = 'i.RED = ?';
            $parametros[] = $red;
        }

        if ($microred !== '') {
            $condiciones[] = 'i.MICRORED = ?';
            $parametros[] = $microred;
        }

        if ($idEess !== '') {
            $condiciones[] = 'i.IdEESS = ?';
            $parametros[] = (int) $idEess;
        }

        if (!empty($condiciones)) {
            $sql .= ' WHERE ' . implode(' AND ', $condiciones);
        }

        /*
         * Sin filtros, IdAfiliados es PRIMARY KEY y permite obtener
         * los primeros 20 registros sin ordenar 1.1 millones de filas.
         * Con búsqueda/filtros mantenemos un orden estable.
         */
        if ($buscar === '' && !$usarIpress) {
            $sql .= ' ORDER BY a.IdAfiliados ASC LIMIT ? OFFSET ?';
        } else {
            $sql .= ' ORDER BY a.IdAfiliados ASC LIMIT ? OFFSET ?';
        }

        $stmt = $this->db->prepare($sql);

        $indice = 1;
        foreach ($parametros as $parametro) {
            $stmt->bindValue(
                $indice,
                $parametro,
                is_int($parametro) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
            $indice++;
        }

        $stmt->bindValue($indice, $limite, PDO::PARAM_INT);
        $indice++;
        $stmt->bindValue($indice, $offset, PDO::PARAM_INT);

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

        /*
         * Sin filtros de IPRESS, no existe ninguna razón para hacer
         * JOIN con ipress. El total se obtiene directamente del maestro.
         */
        $usarIpress = (
            $ue !== '' ||
            $red !== '' ||
            $microred !== '' ||
            $idEess !== ''
        );

        $sql = 'SELECT COUNT(*) FROM afiliados a';

        if ($usarIpress) {
            $sql .= "
                LEFT JOIN ipress i
                    ON a.EessCodigo = LPAD(i.RENAES, 10, '0')";
        }

        $condiciones = [];
        $parametros = [];

        if ($buscar !== '') {
            if (preg_match('/^\d{8}$/', $buscar)) {
                $condiciones[] = 'a.NumeroDni = ?';
                $parametros[] = $buscar;
            } else {
                $condiciones[] = "(
                    a.NumeroDni LIKE ?
                    OR a.NombreAsegurado LIKE ?
                )";

                $valorBuscar = '%' . $buscar . '%';
                $parametros[] = $valorBuscar;
                $parametros[] = $valorBuscar;
            }
        }

        if ($ue !== '') {
            $condiciones[] = 'i.COD_UE = ?';
            $parametros[] = $ue;
        }

        if ($red !== '') {
            $condiciones[] = 'i.RED = ?';
            $parametros[] = $red;
        }

        if ($microred !== '') {
            $condiciones[] = 'i.MICRORED = ?';
            $parametros[] = $microred;
        }

        if ($idEess !== '') {
            $condiciones[] = 'i.IdEESS = ?';
            $parametros[] = (int) $idEess;
        }

        if (!empty($condiciones)) {
            $sql .= ' WHERE ' . implode(' AND ', $condiciones);
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
                FROM afiliados
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
            FROM afiliados a

            LEFT JOIN ipress i
                ON a.EessCodigo = LPAD(i.RENAES, 10, '0')";

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


    /**
     * ==========================================================
     * ANALIZAR CSV DE AFILIADOS
     * ==========================================================
     *
     * Analiza el archivo sin modificar la base de datos.
     * El CSV debe contener 17 columnas.
     */
    public function analizarCsv(string $rutaArchivo): array
    {
        if (!is_readable($rutaArchivo)) {
            throw new RuntimeException(
                'No se puede leer el archivo CSV.'
            );
        }

        $handle = fopen($rutaArchivo, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                'No fue posible abrir el archivo CSV.'
            );
        }

        try {

            $encabezados = fgetcsv(
                $handle,
                0,
                ',',
                '"',
                '\\'
            );

            if ($encabezados === false) {
                throw new RuntimeException(
                    'El archivo CSV está vacío.'
                );
            }

            $encabezados = array_map(
                fn($valor) => $this->normalizarCsvValor($valor),
                $encabezados
            );

            if (
                isset($encabezados[0])
                && str_starts_with($encabezados[0], "\xEF\xBB\xBF")
            ) {
                $encabezados[0] = substr(
                    $encabezados[0],
                    3
                );
            }

            if (count($encabezados) !== 17) {
                throw new RuntimeException(
                    'El CSV debe contener exactamente 17 columnas. ' .
                    'Se encontraron ' . count($encabezados) . '.'
                );
            }

            $cantidadRegistros = 0;
            $vistaPrevia = [];

            while (
                ($fila = fgetcsv(
                    $handle,
                    0,
                    ',',
                    '"',
                    '\\'
                )) !== false
            ) {

                if (
                    count($fila) === 1
                    && trim((string) $fila[0]) === ''
                ) {
                    continue;
                }

                if (count($fila) !== 17) {
                    throw new RuntimeException(
                        'Se encontró una fila con ' .
                        count($fila) .
                        ' columnas. Todas las filas deben contener 17 columnas.'
                    );
                }

                $fila = array_map(
                    fn($valor) => $this->normalizarCsvValor($valor),
                    $fila
                );

                $cantidadRegistros++;

                if (count($vistaPrevia) < 10) {
                    $vistaPrevia[] = $fila;
                }
            }

            return [
                'cantidadRegistros' => $cantidadRegistros,
                'cantidadColumnas' => count($encabezados),
                'encabezados' => $encabezados,
                'vistaPrevia' => $vistaPrevia
            ];

        } finally {

            fclose($handle);
        }
    }


    /**
     * ==========================================================
     * CARGAR CSV DE AFILIADOS
     * ==========================================================
     *
     * La clave de coincidencia para actualización es NumeroDni + NroFormato.
     *
     * - Si DNI + NroFormato existen: actualiza solo ese registro cuando
     *   FechaFormato del CSV sea más reciente.
     * - Si DNI + NroFormato no existen: inserta un nuevo registro.
     * - Si el DNI no existe: inserta un nuevo registro.
     * - No elimina registros existentes.
     * - IdAfiliados se conserva en las actualizaciones.
     * - Toda la operación se ejecuta dentro de una transacción.
     */
    public function cargarCsv(string $rutaArchivo): array
    {
        if (!is_readable($rutaArchivo)) {
            throw new RuntimeException('No se puede leer el archivo CSV.');
        }

        @set_time_limit(0);
        $this->db->beginTransaction();

        try {
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_afiliados_import");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_afiliados_import (
                IdCarga BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                IdSiasis VARCHAR(255) NULL, TipoTabla VARCHAR(255) NULL,
                NroFormato VARCHAR(255) NULL, Correlativo VARCHAR(255) NULL,
                TipoDocumento VARCHAR(255) NULL, FechaFormato VARCHAR(30) NULL,
                NumeroDni VARCHAR(50) NULL, NombreAsegurado VARCHAR(255) NULL,
                Sexo VARCHAR(50) NULL, FechaNacimiento VARCHAR(30) NULL,
                Estado VARCHAR(255) NULL, FechaBaja VARCHAR(30) NULL,
                EessAdscrito VARCHAR(255) NULL, Provincia VARCHAR(255) NULL,
                Distrito VARCHAR(255) NULL, GrupoPoblacional VARCHAR(255) NULL,
                MotivoBaja VARCHAR(255) NULL,
                KEY idx_import_dni_formato (NumeroDni, NroFormato),
                KEY idx_import_formato_fecha (FechaFormato)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

            $rutaSql = $this->db->quote($rutaArchivo);
            $sql = "LOAD DATA LOCAL INFILE {$rutaSql}
                INTO TABLE tmp_afiliados_import CHARACTER SET utf8mb4
                FIELDS TERMINATED BY ',' ENCLOSED BY '\"' ESCAPED BY '\\\\'
                LINES TERMINATED BY '\n' IGNORE 1 LINES
                (@c0,@c1,@c2,@c3,@c4,@c5,@c6,@c7,@c8,@c9,@c10,@c11,@c12,@c13,@c14,@c15,@c16)
                SET IdSiasis=NULLIF(TRIM(@c0),''), TipoTabla=NULLIF(TRIM(@c1),''),
                    NroFormato=NULLIF(TRIM(@c2),''), Correlativo=NULLIF(TRIM(@c3),''),
                    TipoDocumento=NULLIF(TRIM(@c4),''), FechaFormato=NULLIF(TRIM(@c5),''),
                    NumeroDni=NULLIF(TRIM(@c6),''), NombreAsegurado=NULLIF(TRIM(@c7),''),
                    Sexo=NULLIF(TRIM(@c8),''), FechaNacimiento=NULLIF(TRIM(@c9),''),
                    Estado=NULLIF(TRIM(@c10),''), FechaBaja=NULLIF(TRIM(@c11),''),
                    EessAdscrito=NULLIF(TRIM(@c12),''), Provincia=NULLIF(TRIM(@c13),''),
                    Distrito=NULLIF(TRIM(@c14),''), GrupoPoblacional=NULLIF(TRIM(@c15),''),
                    MotivoBaja=NULLIF(TRIM(@c16),'')";
            $this->db->exec($sql);

            $total = (int)$this->db->query("SELECT COUNT(*) FROM tmp_afiliados_import")->fetchColumn();
            if ($total < 1) {
                throw new RuntimeException('El CSV no contiene registros para importar.');
            }

            $invalidDate = (int)$this->db->query("SELECT COUNT(*) FROM tmp_afiliados_import
                WHERE FechaFormato IS NOT NULL AND FechaFormato <> ''
                  AND STR_TO_DATE(FechaFormato, '%Y-%m-%d') IS NULL")->fetchColumn();
            if ($invalidDate > 0) {
                throw new RuntimeException("Hay {$invalidDate} registros con FechaFormato distinto de yyyy-mm-dd.");
            }

            // m_afiliados es una tabla de trabajo: se sustituye solo después de cargar y validar el CSV.
            $this->db->exec("DELETE FROM m_afiliados");
            $this->db->exec("INSERT INTO m_afiliados (
                IdSiasis, TipoTabla, NroFormato, Correlativo, TipoDocumento, FechaFormato,
                NumeroDni, NombreAsegurado, Sexo, FechaNacimiento, Estado, FechaBaja,
                EessAdscrito, Provincia, Distrito, GrupoPoblacional, MotivoBaja)
                SELECT IdSiasis, TipoTabla, NroFormato, Correlativo, TipoDocumento, FechaFormato,
                    NumeroDni, NombreAsegurado, Sexo, FechaNacimiento, Estado, FechaBaja,
                    EessAdscrito, Provincia, Distrito, GrupoPoblacional, MotivoBaja
                FROM tmp_afiliados_import ORDER BY IdCarga");

            $this->db->commit();
            return ['insertados' => $total, 'actualizados' => 0, 'errores' => 0,
                'totalProcesados' => $total, 'totalNuevos' => $total, 'totalActualizados' => 0];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Carga inicial directa desde m_afiliados hacia afiliados.
     * No compara DNI, NroFormato ni busca coincidencias en el destino.
     * Inserta por lotes dentro de una única transacción para poder revertir
     * toda la carga si ocurre un error.
     */
    public function sincronizarAfiliadosDesdeCero(): array
    {
        @set_time_limit(0);
        $tamanoLote = 5000;
        $this->db->beginTransaction();

        try {
            $totalFuente = (int) $this->db->query(
                "SELECT COUNT(*) FROM m_afiliados"
            )->fetchColumn();

            if ($totalFuente === 0) {
                throw new RuntimeException('m_afiliados está vacía. No hay registros para insertar.');
            }

            $totalDestino = (int) $this->db->query(
                "SELECT COUNT(*) FROM afiliados"
            )->fetchColumn();

            if ($totalDestino !== 0) {
                throw new RuntimeException(
                    'La sincronización desde cero requiere que afiliados esté vacía. ' .
                    'No se borró ni modificó ningún registro.'
                );
            }

            // Copia directa: conserva IdAfiliados y los valores de m_afiliados.
            // No compara filas ni transforma NroFormato u otros campos.
            // EessCodigo se omite porque es una columna generada en afiliados.
            $ultimoId = 0;
            $insertados = 0;

            while (true) {
                $sqlHasta = "SELECT MAX(IdAfiliados) FROM (
                    SELECT IdAfiliados
                    FROM m_afiliados
                    WHERE IdAfiliados > {$ultimoId}
                    ORDER BY IdAfiliados ASC
                    LIMIT {$tamanoLote}
                ) AS lote_origen";
                $hastaId = $this->db->query($sqlHasta)->fetchColumn();

                if ($hastaId === false || $hastaId === null) {
                    break;
                }
                $hastaId = (int) $hastaId;

                $sqlInsert = "INSERT INTO afiliados (
                    IdAfiliados, IdSiasis, TipoTabla, NroFormato, Correlativo,
                    TipoDocumento, FechaFormato, NumeroDni, NombreAsegurado,
                    Sexo, FechaNacimiento, Estado, FechaBaja, EessAdscrito,
                    Provincia, Distrito, GrupoPoblacional, MotivoBaja
                )
                SELECT
                    IdAfiliados, IdSiasis, TipoTabla, NroFormato, Correlativo,
                    TipoDocumento, FechaFormato, NumeroDni, NombreAsegurado,
                    Sexo, FechaNacimiento, Estado, FechaBaja, EessAdscrito,
                    Provincia, Distrito, GrupoPoblacional, MotivoBaja
                FROM m_afiliados
                WHERE IdAfiliados > {$ultimoId}
                  AND IdAfiliados <= {$hastaId}
                ORDER BY IdAfiliados ASC";

                $insertados += $this->db->exec($sqlInsert);
                $ultimoId = $hastaId;
            }

            if ($insertados !== $totalFuente) {
                throw new RuntimeException(
                    "La cantidad insertada ({$insertados}) no coincide con el origen ({$totalFuente}). Se revertirá la carga."
                );
            }

            $this->db->commit();

            return [
                'totalFuente' => $totalFuente,
                'insertados' => $insertados,
                'lotes' => (int) ceil($totalFuente / $tamanoLote),
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Sincroniza diariamente m_afiliados hacia afiliados.
     * - DNI: NumeroDni + NroFormato.
     * - Sin DNI: NroFormato + Correlativo + NombreAsegurado + FechaNacimiento.
     * - Lote E -> Lote 2: coincidencia única por nombre, nacimiento, sexo e IPRESS.
     * - Los registros ausentes se colocan en una bandeja de revisión; nunca se eliminan.
     */
    public function sincronizarAfiliados(): array
    {
        @set_time_limit(0);
        $etapaSync = 'inicio';
        $this->db->beginTransaction();

        try {
            $etapaSync = 'verificar tabla de pendientes de revisión';
            // Esta tabla se crea previamente mediante el SQL entregado junto con el modelo.
            $this->db->query("SELECT IdAfiliados FROM afiliados_pendientes_revision LIMIT 0");

            $etapaSync = 'contar registros de m_afiliados';
            $totalFuente = (int) $this->db->query(
                "SELECT COUNT(*) FROM m_afiliados"
            )->fetchColumn();

            if ($totalFuente === 0) {
                throw new RuntimeException('m_afiliados está vacío. Primero cargue un CSV.');
            }

            $etapaSync = 'validar FechaFormato';
            $fechasInvalidas = (int) $this->db->query("SELECT COUNT(*)
                FROM m_afiliados
                WHERE FechaFormato IS NOT NULL
                  AND FechaFormato <> ''
                  AND STR_TO_DATE(FechaFormato, '%Y-%m-%d') IS NULL")->fetchColumn();

            if ($fechasInvalidas > 0) {
                throw new RuntimeException("m_afiliados contiene {$fechasInvalidas} fechas inválidas.");
            }

            $etapaSync = 'preparar registros únicos de la fuente';
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_sync_afiliados");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_sync_afiliados ENGINE=InnoDB AS
                SELECT * FROM (
                    SELECT m.*,
                        ROW_NUMBER() OVER (
                            PARTITION BY
                                COALESCE(NULLIF(TRIM(m.NumeroDni), ''), '#SIN_DNI#'),
                                COALESCE(NULLIF(TRIM(m.NroFormato), ''), '#SIN_FORMATO#'),
                                CASE WHEN COALESCE(TRIM(m.NumeroDni), '') = ''
                                     THEN COALESCE(TRIM(m.Correlativo), '#SIN_CORRELATIVO#') ELSE '' END,
                                CASE WHEN COALESCE(TRIM(m.NumeroDni), '') = ''
                                     THEN LOWER(TRIM(COALESCE(m.NombreAsegurado, ''))) ELSE '' END,
                                CASE WHEN COALESCE(TRIM(m.NumeroDni), '') = ''
                                     THEN COALESCE(TRIM(m.FechaNacimiento), '') ELSE '' END
                            ORDER BY m.FechaFormato DESC, m.IdAfiliados DESC
                        ) AS rn
                    FROM m_afiliados m
                ) AS fuente
                WHERE fuente.rn = 1");

            $etapaSync = 'crear índices de la fuente temporal';
            // 1. Actualizar coincidencias con DNI + formato cuando la fecha fuente sea más reciente.
            $etapaSync = 'contar actualizaciones por DNI y formato';
            $actualizadosDni = (int) $this->db->query("SELECT COUNT(*)
                FROM afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NumeroDni = s.NumeroDni
                   AND a.NroFormato = s.NroFormato
                WHERE COALESCE(TRIM(s.NumeroDni), '') <> ''
                  AND COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')")->fetchColumn();

            $etapaSync = 'actualizar afiliados por DNI y formato';
            $this->db->exec("UPDATE afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NumeroDni = s.NumeroDni
                   AND a.NroFormato = s.NroFormato
                SET a.IdSiasis = s.IdSiasis,
                    a.TipoTabla = s.TipoTabla,
                    a.Correlativo = s.Correlativo,
                    a.TipoDocumento = s.TipoDocumento,
                    a.FechaFormato = s.FechaFormato,
                    a.NumeroDni = s.NumeroDni,
                    a.NombreAsegurado = s.NombreAsegurado,
                    a.Sexo = s.Sexo,
                    a.FechaNacimiento = s.FechaNacimiento,
                    a.Estado = s.Estado,
                    a.FechaBaja = s.FechaBaja,
                    a.EessAdscrito = s.EessAdscrito,
                    a.Provincia = s.Provincia,
                    a.Distrito = s.Distrito,
                    a.GrupoPoblacional = s.GrupoPoblacional,
                    a.MotivoBaja = s.MotivoBaja
                WHERE COALESCE(TRIM(s.NumeroDni), '') <> ''
                  AND COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')");

            // 2. Actualizar coincidencias sin DNI usando los cuatro campos acordados.
            $etapaSync = 'contar actualizaciones sin DNI';
            $actualizadosSinDni = (int) $this->db->query("SELECT COUNT(*)
                FROM afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NroFormato = s.NroFormato
                   AND a.Correlativo = s.Correlativo
                   AND LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci =
                       LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND a.FechaNacimiento = s.FechaNacimiento
                WHERE COALESCE(TRIM(a.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND COALESCE(TRIM(s.Correlativo), '') <> ''
                  AND COALESCE(TRIM(s.NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(s.FechaNacimiento), '') <> ''
                  AND COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')")->fetchColumn();

            $etapaSync = 'actualizar afiliados sin DNI';
            $this->db->exec("UPDATE afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NroFormato = s.NroFormato
                   AND a.Correlativo = s.Correlativo
                   AND LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci =
                       LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND a.FechaNacimiento = s.FechaNacimiento
                SET a.IdSiasis = s.IdSiasis,
                    a.TipoTabla = s.TipoTabla,
                    a.TipoDocumento = s.TipoDocumento,
                    a.FechaFormato = s.FechaFormato,
                    a.NumeroDni = s.NumeroDni,
                    a.NombreAsegurado = s.NombreAsegurado,
                    a.Sexo = s.Sexo,
                    a.FechaNacimiento = s.FechaNacimiento,
                    a.Estado = s.Estado,
                    a.FechaBaja = s.FechaBaja,
                    a.EessAdscrito = s.EessAdscrito,
                    a.Provincia = s.Provincia,
                    a.Distrito = s.Distrito,
                    a.GrupoPoblacional = s.GrupoPoblacional,
                    a.MotivoBaja = s.MotivoBaja
                WHERE COALESCE(TRIM(a.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND COALESCE(TRIM(s.Correlativo), '') <> ''
                  AND COALESCE(TRIM(s.NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(s.FechaNacimiento), '') <> ''
                  AND COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')");

            // 3. Detectar migraciones Lote E -> Lote 2 con coincidencia única.
            // MySQL 8 no permite reabrir una misma tabla temporal en subconsultas
            // correlacionadas. Por eso calculamos previamente las cardinalidades
            // de la fuente y del destino en tablas temporales independientes.
            $etapaSync = 'contar candidatos Lote 2 en la fuente';
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_sync_lote2_counts");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_sync_lote2_counts ENGINE=InnoDB AS
                SELECT
                    LOWER(TRIM(NombreAsegurado)) COLLATE utf8mb4_unicode_ci AS NombreClave,
                    FechaNacimiento,
                    UPPER(TRIM(Sexo)) AS SexoClave,
                    LOWER(TRIM(EessAdscrito)) COLLATE utf8mb4_unicode_ci AS EessClave,
                    COUNT(*) AS TotalFuente
                FROM tmp_sync_afiliados
                WHERE (UPPER(TRIM(TipoTabla)) LIKE '%LOTE 2%' OR UPPER(TRIM(TipoTabla)) = '2')
                  AND COALESCE(TRIM(NumeroDni), '') <> ''
                  AND COALESCE(TRIM(NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(FechaNacimiento), '') <> ''
                  AND COALESCE(TRIM(Sexo), '') <> ''
                  AND COALESCE(TRIM(EessAdscrito), '') <> ''
                GROUP BY
                    LOWER(TRIM(NombreAsegurado)) COLLATE utf8mb4_unicode_ci,
                    FechaNacimiento,
                    UPPER(TRIM(Sexo)),
                    LOWER(TRIM(EessAdscrito)) COLLATE utf8mb4_unicode_ci");
            $etapaSync = 'contar candidatos Lote E en afiliados';
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_sync_lotee_counts");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_sync_lotee_counts ENGINE=InnoDB AS
                SELECT
                    LOWER(TRIM(NombreAsegurado)) COLLATE utf8mb4_unicode_ci AS NombreClave,
                    FechaNacimiento,
                    UPPER(TRIM(Sexo)) AS SexoClave,
                    LOWER(TRIM(EessAdscrito)) COLLATE utf8mb4_unicode_ci AS EessClave,
                    COUNT(*) AS TotalDestino
                FROM afiliados
                WHERE (UPPER(TRIM(TipoTabla)) LIKE '%LOTE E%' OR UPPER(TRIM(TipoTabla)) = 'E')
                  AND COALESCE(TRIM(NumeroDni), '') = ''
                  AND COALESCE(TRIM(NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(FechaNacimiento), '') <> ''
                  AND COALESCE(TRIM(Sexo), '') <> ''
                  AND COALESCE(TRIM(EessAdscrito), '') <> ''
                GROUP BY
                    LOWER(TRIM(NombreAsegurado)) COLLATE utf8mb4_unicode_ci,
                    FechaNacimiento,
                    UPPER(TRIM(Sexo)),
                    LOWER(TRIM(EessAdscrito)) COLLATE utf8mb4_unicode_ci");
            $etapaSync = 'crear coincidencias únicas Lote E a Lote 2';
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_sync_lotee_match");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_sync_lotee_match ENGINE=InnoDB AS
                SELECT
                    a.IdAfiliados AS IdDestino,
                    s.IdAfiliados AS IdFuente
                FROM afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci =
                       LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND a.FechaNacimiento = s.FechaNacimiento
                   AND UPPER(TRIM(a.Sexo)) = UPPER(TRIM(s.Sexo))
                   AND LOWER(TRIM(a.EessAdscrito)) COLLATE utf8mb4_unicode_ci =
                       LOWER(TRIM(s.EessAdscrito)) COLLATE utf8mb4_unicode_ci
                INNER JOIN tmp_sync_lote2_counts c2
                    ON c2.NombreClave = LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND c2.FechaNacimiento = s.FechaNacimiento
                   AND c2.SexoClave = UPPER(TRIM(s.Sexo))
                   AND c2.EessClave = LOWER(TRIM(s.EessAdscrito)) COLLATE utf8mb4_unicode_ci
                   AND c2.TotalFuente = 1
                INNER JOIN tmp_sync_lotee_counts ce
                    ON ce.NombreClave = LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND ce.FechaNacimiento = a.FechaNacimiento
                   AND ce.SexoClave = UPPER(TRIM(a.Sexo))
                   AND ce.EessClave = LOWER(TRIM(a.EessAdscrito)) COLLATE utf8mb4_unicode_ci
                   AND ce.TotalDestino = 1
                WHERE (UPPER(TRIM(a.TipoTabla)) LIKE '%LOTE E%' OR UPPER(TRIM(a.TipoTabla)) = 'E')
                  AND (UPPER(TRIM(s.TipoTabla)) LIKE '%LOTE 2%' OR UPPER(TRIM(s.TipoTabla)) = '2')
                  AND COALESCE(TRIM(a.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NumeroDni), '') <> ''
                  AND COALESCE(TRIM(a.NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(a.FechaNacimiento), '') <> ''
                  AND COALESCE(TRIM(a.Sexo), '') <> ''
                  AND COALESCE(TRIM(a.EessAdscrito), '') <> ''");

            $etapaSync = 'contar coincidencias Lote E a Lote 2';
            $loteEEncontrados = (int) $this->db->query(
                "SELECT COUNT(*) FROM tmp_sync_lotee_match"
            )->fetchColumn();

            $etapaSync = 'contar actualizaciones Lote E a Lote 2';
            $loteEActualizados = (int) $this->db->query("SELECT COUNT(*)
                FROM afiliados a
                INNER JOIN tmp_sync_lotee_match mm ON mm.IdDestino = a.IdAfiliados
                INNER JOIN tmp_sync_afiliados s ON s.IdAfiliados = mm.IdFuente
                WHERE COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')")->fetchColumn();

            $etapaSync = 'actualizar coincidencias Lote E a Lote 2';
            $this->db->exec("UPDATE afiliados a
                INNER JOIN tmp_sync_lotee_match mm ON mm.IdDestino = a.IdAfiliados
                INNER JOIN tmp_sync_afiliados s ON s.IdAfiliados = mm.IdFuente
                SET a.IdSiasis = s.IdSiasis,
                    a.TipoTabla = s.TipoTabla,
                    a.NroFormato = s.NroFormato,
                    a.Correlativo = s.Correlativo,
                    a.TipoDocumento = s.TipoDocumento,
                    a.FechaFormato = s.FechaFormato,
                    a.NumeroDni = s.NumeroDni,
                    a.NombreAsegurado = s.NombreAsegurado,
                    a.Sexo = s.Sexo,
                    a.FechaNacimiento = s.FechaNacimiento,
                    a.Estado = s.Estado,
                    a.FechaBaja = s.FechaBaja,
                    a.EessAdscrito = s.EessAdscrito,
                    a.Provincia = s.Provincia,
                    a.Distrito = s.Distrito,
                    a.GrupoPoblacional = s.GrupoPoblacional,
                    a.MotivoBaja = s.MotivoBaja
                WHERE COALESCE(s.FechaFormato, '0000-00-00') >
                      COALESCE(a.FechaFormato, '0000-00-00')");

            // 4. Insertar nuevos. Los registros sin DNI requieren los cuatro campos
            // de identidad completos para evitar duplicados por coincidencias débiles.
            $etapaSync = 'contar afiliados antes de insertar nuevos';
            $antes = (int) $this->db->query("SELECT COUNT(*) FROM afiliados")->fetchColumn();

            $etapaSync = 'insertar afiliados nuevos';
            $this->db->exec("INSERT INTO afiliados (
                    IdSiasis, TipoTabla, NroFormato, Correlativo, TipoDocumento,
                    FechaFormato, NumeroDni, NombreAsegurado, Sexo, FechaNacimiento,
                    Estado, FechaBaja, EessAdscrito, Provincia, Distrito,
                    GrupoPoblacional, MotivoBaja
                )
                SELECT
                    s.IdSiasis, s.TipoTabla, s.NroFormato, s.Correlativo, s.TipoDocumento,
                    s.FechaFormato, s.NumeroDni, s.NombreAsegurado, s.Sexo, s.FechaNacimiento,
                    s.Estado, s.FechaBaja, s.EessAdscrito, s.Provincia, s.Distrito,
                    s.GrupoPoblacional, s.MotivoBaja
                FROM tmp_sync_afiliados s
                WHERE COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND (
                    (
                        COALESCE(TRIM(s.NumeroDni), '') <> ''
                        AND NOT EXISTS (
                            SELECT 1 FROM afiliados a
                            WHERE a.NumeroDni = s.NumeroDni
                              AND a.NroFormato = s.NroFormato
                        )
                    )
                    OR
                    (
                        COALESCE(TRIM(s.NumeroDni), '') = ''
                        AND COALESCE(TRIM(s.Correlativo), '') <> ''
                        AND COALESCE(TRIM(s.NombreAsegurado), '') <> ''
                        AND COALESCE(TRIM(s.FechaNacimiento), '') <> ''
                        AND NOT EXISTS (
                            SELECT 1 FROM afiliados a
                            WHERE COALESCE(TRIM(a.NumeroDni), '') = ''
                              AND a.NroFormato = s.NroFormato
                              AND a.Correlativo = s.Correlativo
                              AND LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci =
                                  LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                              AND a.FechaNacimiento = s.FechaNacimiento
                        )
                    )
                  )");

            $etapaSync = 'contar afiliados después de insertar';
            $despues = (int) $this->db->query("SELECT COUNT(*) FROM afiliados")->fetchColumn();
            $insertados = max(0, $despues - $antes);

            // 5. Construir el conjunto de afiliados presentes en el CSV actual.
            $etapaSync = 'identificar afiliados presentes en la fuente';
            $this->db->exec("DROP TEMPORARY TABLE IF EXISTS tmp_sync_presentes");
            $this->db->exec("CREATE TEMPORARY TABLE tmp_sync_presentes (
                IdAfiliados BIGINT NOT NULL PRIMARY KEY
            ) ENGINE=InnoDB");

            // Coincidencias con DNI.
            $this->db->exec("INSERT IGNORE INTO tmp_sync_presentes (IdAfiliados)
                SELECT a.IdAfiliados
                FROM afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NumeroDni = s.NumeroDni
                   AND a.NroFormato = s.NroFormato
                WHERE COALESCE(TRIM(a.NumeroDni), '') <> ''
                  AND COALESCE(TRIM(s.NumeroDni), '') <> ''");

            // Coincidencias sin DNI mediante la clave compuesta acordada.
            $this->db->exec("INSERT IGNORE INTO tmp_sync_presentes (IdAfiliados)
                SELECT a.IdAfiliados
                FROM afiliados a
                INNER JOIN tmp_sync_afiliados s
                    ON a.NroFormato = s.NroFormato
                   AND a.Correlativo = s.Correlativo
                   AND LOWER(TRIM(a.NombreAsegurado)) COLLATE utf8mb4_unicode_ci =
                       LOWER(TRIM(s.NombreAsegurado)) COLLATE utf8mb4_unicode_ci
                   AND a.FechaNacimiento = s.FechaNacimiento
                WHERE COALESCE(TRIM(a.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NumeroDni), '') = ''
                  AND COALESCE(TRIM(s.NroFormato), '') <> ''
                  AND COALESCE(TRIM(s.Correlativo), '') <> ''
                  AND COALESCE(TRIM(s.NombreAsegurado), '') <> ''
                  AND COALESCE(TRIM(s.FechaNacimiento), '') <> ''");

            // Una pareja Lote E -> Lote 2 única también cuenta como presente,
            // incluso si la fecha fuente no obliga a actualizar los datos.
            $this->db->exec("INSERT IGNORE INTO tmp_sync_presentes (IdAfiliados)
                SELECT IdDestino FROM tmp_sync_lotee_match");

            // 6. Guardar solo el estado pendiente actual. No se crea historial.
            $etapaSync = 'registrar afiliados ausentes para revisión';
            $this->db->exec("INSERT INTO afiliados_pendientes_revision (
                    IdAfiliados, TipoTabla, NroFormato, Correlativo, TipoDocumento,
                    NumeroDni, NombreAsegurado, Sexo, FechaNacimiento, Estado,
                    EessAdscrito, Provincia, Distrito, GrupoPoblacional, MotivoBaja
                )
                SELECT
                    a.IdAfiliados, a.TipoTabla, a.NroFormato, a.Correlativo, a.TipoDocumento,
                    a.NumeroDni, a.NombreAsegurado, a.Sexo, a.FechaNacimiento, a.Estado,
                    a.EessAdscrito, a.Provincia, a.Distrito, a.GrupoPoblacional, a.MotivoBaja
                FROM afiliados a
                LEFT JOIN tmp_sync_presentes p ON p.IdAfiliados = a.IdAfiliados
                WHERE p.IdAfiliados IS NULL
                ON DUPLICATE KEY UPDATE
                    TipoTabla = VALUES(TipoTabla),
                    NroFormato = VALUES(NroFormato),
                    Correlativo = VALUES(Correlativo),
                    TipoDocumento = VALUES(TipoDocumento),
                    NumeroDni = VALUES(NumeroDni),
                    NombreAsegurado = VALUES(NombreAsegurado),
                    Sexo = VALUES(Sexo),
                    FechaNacimiento = VALUES(FechaNacimiento),
                    Estado = VALUES(Estado),
                    EessAdscrito = VALUES(EessAdscrito),
                    Provincia = VALUES(Provincia),
                    Distrito = VALUES(Distrito),
                    GrupoPoblacional = VALUES(GrupoPoblacional),
                    MotivoBaja = VALUES(MotivoBaja)");

            $etapaSync = 'retirar de pendientes los afiliados que reaparecieron';
            $this->db->exec("DELETE p
                FROM afiliados_pendientes_revision p
                INNER JOIN tmp_sync_presentes pr ON pr.IdAfiliados = p.IdAfiliados");

            $etapaSync = 'contar resultados de sincronización';
            $sinDni = (int) $this->db->query("SELECT COUNT(*)
                FROM tmp_sync_afiliados
                WHERE COALESCE(TRIM(NumeroDni), '') = ''")->fetchColumn();
            $sinDniSinClaveCompleta = (int) $this->db->query("SELECT COUNT(*)
                FROM tmp_sync_afiliados
                WHERE COALESCE(TRIM(NumeroDni), '') = ''
                  AND (
                    COALESCE(TRIM(NroFormato), '') = ''
                    OR COALESCE(TRIM(Correlativo), '') = ''
                    OR COALESCE(TRIM(NombreAsegurado), '') = ''
                    OR COALESCE(TRIM(FechaNacimiento), '') = ''
                  )")->fetchColumn();
            $pendientes = (int) $this->db->query(
                "SELECT COUNT(*) FROM afiliados_pendientes_revision"
            )->fetchColumn();

            $this->db->commit();

            return [
                'totalFuente' => $totalFuente,
                'actualizadosDniFormato' => $actualizadosDni,
                'actualizadosSinDni' => $actualizadosSinDni,
                'actualizadosLoteE' => $loteEActualizados,
                'coincidenciasLoteE' => $loteEEncontrados,
                'insertados' => $insertados,
                'sinDniEnFuente' => $sinDni,
                'sinDniSinClaveCompleta' => $sinDniSinClaveCompleta,
                'pendientesRevision' => $pendientes
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }

            throw new RuntimeException(
                'Error durante la etapa «' . $etapaSync . '»: ' . $e->getMessage(),
                (int) $e->getCode(),
                $e
            );
        }
    }


    private function normalizarCsvValor(
        mixed $valor
    ): string {

        $valor = (string) $valor;

        if (
            str_starts_with(
                $valor,
                "\xEF\xBB\xBF"
            )
        ) {
            $valor = substr(
                $valor,
                3
            );
        }

        if (
            function_exists('mb_check_encoding')
            && !mb_check_encoding(
                $valor,
                'UTF-8'
            )
        ) {
            $valor = mb_convert_encoding(
                $valor,
                'UTF-8',
                'Windows-1252'
            );
        }

        return $valor;
    }


}
