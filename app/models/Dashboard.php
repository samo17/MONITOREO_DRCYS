<?php

require_once __DIR__ . '/../core/Model.php';

class Dashboard extends Model
{
    public function totalIpress(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM ipress")->fetchColumn();
    }

    public function ipressPorUE(): array
    {
        $sql = "SELECT
                    COALESCE(NULLIF(TRIM(UE), ''), 'SIN UNIDAD EJECUTORA') AS etiqueta,
                    COUNT(*) AS total
                FROM ipress
                GROUP BY COALESCE(NULLIF(TRIM(UE), ''), 'SIN UNIDAD EJECUTORA')
                ORDER BY total DESC, etiqueta ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }


    public function totalAfiliados(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM afiliados")->fetchColumn();
    }

    public function afiliadosPorUE(): array
    {
        // EessCodigo es la columna generada de afiliados y se relaciona
        // con el RENAES normalizado a 10 caracteres, igual que Afiliado.php.
        $sql = "SELECT
                    COALESCE(NULLIF(TRIM(i.UE), ''), 'SIN UNIDAD EJECUTORA') AS etiqueta,
                    COUNT(DISTINCT a.IdAfiliados) AS total
                FROM afiliados a
                LEFT JOIN ipress i
                    ON a.EessCodigo = LPAD(i.RENAES, 10, '0')
                GROUP BY COALESCE(NULLIF(TRIM(i.UE), ''), 'SIN UNIDAD EJECUTORA')
                ORDER BY total DESC, etiqueta ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function ocurrenciasMensuales(int $anio): array
    {
        $sql = "SELECT
                    MONTH(FechaRegistro) AS mes_numero,
                    COUNT(*) AS total
                FROM OcurrenciasCA
                WHERE FechaRegistro >= ?
                  AND FechaRegistro < ?
                GROUP BY MONTH(FechaRegistro)
                ORDER BY mes_numero";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            sprintf('%04d-01-01 00:00:00', $anio),
            sprintf('%04d-01-01 00:00:00', $anio + 1)
        ]);
        $resultados = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo',
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        $salida = [];
        foreach ($meses as $numero => $nombre) {
            $salida[] = [
                'mes' => $nombre,
                'total' => (int) ($resultados[$numero] ?? 0)
            ];
        }
        return $salida;
    }

    public function totalOcurrencias(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM OcurrenciasCA")->fetchColumn();
    }

    public function totalDigitadores(): int
    {
        // Sigue el criterio del listado habitual: excluir NO DIGITA por defecto.
        return (int) $this->db->query("
            SELECT COUNT(*)
            FROM digitadores
            WHERE UPPER(TRIM(COALESCE(TIPO, ''))) <> 'NO DIGITA'
        ")->fetchColumn();
    }
}
