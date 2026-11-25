<?php

require_once __DIR__ . '/../core/Model.php';

/**
 * Carga del CSV hacia la tabla maestra m_afiliados.
 * No consulta ni modifica las tablas operativas de afiliados.
 */
class MaestroAfiliado extends Model
{
    public function contar(): int
    {
        return (int) $this->db
            ->query('SELECT COUNT(*) FROM m_afiliados')
            ->fetchColumn();
    }

    /**
     * Valida el CSV completo antes de reemplazar m_afiliados.
     * Exige 17 columnas en el encabezado y en cada fila de datos.
     * Si falla una inserción, la transacción revierte el reemplazo.
     */
    public function cargarCsv(string $rutaArchivo): array
    {
        if ($rutaArchivo === '' || !is_readable($rutaArchivo)) {
            throw new RuntimeException('No se puede leer el archivo CSV.');
        }

        @set_time_limit(0);
        $handle = fopen($rutaArchivo, 'rb');

        if ($handle === false) {
            throw new RuntimeException('No fue posible abrir el archivo CSV.');
        }

        try {
            $encabezados = fgetcsv($handle, 0, ',', '"', '\\');

            if ($encabezados === false) {
                throw new RuntimeException('El CSV está vacío.');
            }

            if (isset($encabezados[0])) {
                $encabezados[0] = preg_replace('/^\xEF\xBB\xBF/', '', (string) $encabezados[0]);
            }

            if (count($encabezados) !== 17) {
                throw new RuntimeException(
                    'El CSV debe contener exactamente 17 columnas. Se detectaron ' .
                    count($encabezados) . ' en el encabezado.'
                );
            }

            // Primera pasada: validar todas las filas antes de tocar los datos actuales.
            $numeroFila = 1;
            $totalLeidos = 0;

            while (($fila = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                $numeroFila++;

                if (count($fila) === 1 && trim((string) ($fila[0] ?? '')) === '') {
                    continue;
                }

                if (count($fila) !== 17) {
                    throw new RuntimeException(
                        "La fila {$numeroFila} debe tener exactamente 17 columnas; se encontraron " .
                        count($fila) . '. No se modificaron los datos actuales.'
                    );
                }

                $totalLeidos++;
            }

            if ($totalLeidos === 0) {
                throw new RuntimeException(
                    'El CSV no contiene registros para cargar. No se modificaron los datos actuales.'
                );
            }

            $sql = 'INSERT INTO m_afiliados (
                        IdSiasis, TipoTabla, NroFormato, Correlativo, TipoDocumento,
                        FechaFormato, NumeroDni, NombreAsegurado, Sexo,
                        FechaNacimiento, Estado, FechaBaja, EessAdscrito,
                        Provincia, Distrito, GrupoPoblacional, MotivoBaja
                    ) VALUES (
                        :IdSiasis, :TipoTabla, :NroFormato, :Correlativo, :TipoDocumento,
                        :FechaFormato, :NumeroDni, :NombreAsegurado, :Sexo,
                        :FechaNacimiento, :Estado, :FechaBaja, :EessAdscrito,
                        :Provincia, :Distrito, :GrupoPoblacional, :MotivoBaja
                    )';

            $columnas = [
                'IdSiasis', 'TipoTabla', 'NroFormato', 'Correlativo', 'TipoDocumento',
                'FechaFormato', 'NumeroDni', 'NombreAsegurado', 'Sexo',
                'FechaNacimiento', 'Estado', 'FechaBaja', 'EessAdscrito',
                'Provincia', 'Distrito', 'GrupoPoblacional', 'MotivoBaja'
            ];

            // Regresar al inicio y omitir nuevamente el encabezado para insertar.
            rewind($handle);
            fgetcsv($handle, 0, ',', '"', '\\');

            $this->db->beginTransaction();
            $this->db->exec('DELETE FROM m_afiliados');
            $insertar = $this->db->prepare($sql);

            $totalCargados = 0;

            while (($fila = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
                if (count($fila) === 1 && trim((string) ($fila[0] ?? '')) === '') {
                    continue;
                }

                // La estructura ya fue validada en la primera pasada.
                $datos = [];
                foreach ($columnas as $indice => $columna) {
                    $valor = trim((string) $fila[$indice]);
                    $datos[$columna] = ($valor === '') ? null : $valor;
                }

                $insertar->execute($datos);
                $totalCargados++;
            }

            $this->db->commit();

            return [
                'totalLeidos' => $totalLeidos,
                'totalCargados' => $totalCargados,
                'duplicadosOmitidos' => 0,
                'errores' => 0,
            ];
        } catch (Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            throw $e;
        } finally {
            fclose($handle);
        }
    }
}
