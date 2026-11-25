<?php
$titulo = 'Dashboard';
require_once __DIR__ . '/../layouts/header.php';
require_once __DIR__ . '/../layouts/navbar.php';

$mesesJson = json_encode(array_column($ocurrenciasMensuales, 'mes'), JSON_UNESCAPED_UNICODE);
$ocurrenciasJson = json_encode(array_map('intval', array_column($ocurrenciasMensuales, 'total')));
$ipressLabels = json_encode(array_column($ipressPorUE, 'etiqueta'), JSON_UNESCAPED_UNICODE);
$ipressValues = json_encode(array_map('intval', array_column($ipressPorUE, 'total')));
$afiliadosLabels = json_encode(array_column($afiliadosPorUE, 'etiqueta'), JSON_UNESCAPED_UNICODE);
$afiliadosValues = json_encode(array_map('intval', array_column($afiliadosPorUE, 'total')));
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 col-lg-2 p-0">
            <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
        </div>

        <main class="col-md-9 col-lg-10">
            <div class="container-fluid py-4" id="dashboardContenido">
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
                    <div class="d-flex align-items-center gap-4 flex-grow-1">
                        <div class="d-flex align-items-center justify-content-center pe-4 me-1 border-end"
                             style="width:370px; min-width:370px; min-height:125px;">
                            <img
                                src="/DRCYS/public/assets/img/LOGO_DIRESA.png"
                                alt="Logo de DIRESA Loreto"
                                style="display:block; width:350px; max-width:100%; height:auto; max-height:115px; object-fit:contain;"
                            >
                        </div>
                        <div class="py-1">
                            <h1 class="h4 mb-1 fw-semibold">Dirección de Referencias, Contrarreferencias y Seguros</h1>
                            <p class="text-muted mb-0">Oficina de Monitoreo - DRCyS</p>
                            <small class="text-muted">DIRESA Loreto</small>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <form method="GET" action="<?= url('dashboard') ?>" class="d-flex gap-2 align-items-center">
                            <label for="anio" class="form-label mb-0">Año:</label>
                            <select name="anio" id="anio" class="form-select form-select-sm" onchange="this.form.submit()">
                                <?php for ($y = (int)date('Y'); $y >= 2020; $y--): ?>
                                    <option value="<?= $y ?>" <?= $anio === $y ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </form>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="window.print()">
                            <i class="bi bi-file-pdf"></i> Descargar PDF
                        </button>
                        <a class="btn btn-success btn-sm" href="<?= url('dashboard/exportar') ?>?anio=<?= (int)$anio ?>">
                            Exportar Excel
                        </a>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 dashboard-kpi">
                            <div class="card-body">
                                <div class="text-muted small">IPRESS ACTIVAS</div>
                                <div class="display-6 fw-semibold"><?= number_format($totalIpress) ?></div>
                                <div class="small text-primary">Total por Unidad Ejecutora</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 dashboard-kpi">
                            <div class="card-body">
                                <div class="text-muted small">Afiliados registrados</div>
                                <div class="display-6 fw-semibold"><?= number_format($totalAfiliados) ?></div>
                                <div class="small text-success">Distribución por Unidad Ejecutora</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 dashboard-kpi">
                            <div class="card-body">
                                <div class="text-muted small">Ocurrencias registradas</div>
                                <div class="display-6 fw-semibold"><?= number_format($totalOcurrencias) ?></div>
                                <div class="small text-warning">Evolución mensual <?= (int)$anio ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-xl-3">
                        <div class="card border-0 shadow-sm h-100 dashboard-kpi">
                            <div class="card-body">
                                <div class="text-muted small">Digitadores</div>
                                <div class="display-6 fw-semibold"><?= number_format($totalDigitadores) ?></div>
                                <div class="small text-info">Excluye tipo «NO DIGITA»</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4 charts-section">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h6 mb-0">Total de IPRESS por Unidad Ejecutora</h2>
                                    <small class="text-muted">Distribución de establecimientos registrados</small>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" onclick="descargarGrafico('graficoIpress','ipress_por_ue')">Descargar PNG</button>
                            </div>
                            <div class="card-body chart-box"><canvas id="graficoIpress"></canvas></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h6 mb-0">Total de Afiliados por Unidad Ejecutora</h2>
                                    <small class="text-muted">Afiliados agrupados según la IPRESS asociada</small>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" onclick="descargarGrafico('graficoAfiliados','afiliados_por_ue')">Descargar PNG</button>
                            </div>
                            <div class="card-body chart-box"><canvas id="graficoAfiliados"></canvas></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="h6 mb-0">Total de Ocurrencias mensuales</h2>
                                    <small class="text-muted">Registros por mes durante <?= (int)$anio ?></small>
                                </div>
                                <button class="btn btn-outline-secondary btn-sm" onclick="descargarGrafico('graficoOcurrencias','ocurrencias_mensuales_<?= (int)$anio ?>')">Descargar PNG</button>
                            </div>
                            <div class="card-body chart-box"><canvas id="graficoOcurrencias"></canvas></div>
                        </div>
                    </div>
                </div>

                <div class="text-muted small mt-4">
                    Generado el <?= date('d/m/Y H:i') ?> · Usuario: <?= e(Auth::nombreCompleto()) ?>
                </div>
            </div>
        </main>
    </div>
</div>

<style>
.chart-box { height: 360px; position: relative; }
.dashboard-kpi { border-left: 4px solid #315b85 !important; }

@media (max-width: 768px) {
    #dashboardContenido > .d-flex:first-child {
        align-items: flex-start !important;
    }
    #dashboardContenido > .d-flex:first-child > div:first-child {
        flex-wrap: wrap;
        gap: 12px !important;
    }
    #dashboardContenido > .d-flex:first-child > div:first-child > div:first-child {
        width: 100% !important;
        min-width: 0 !important;
        border-right: 0 !important;
        border-bottom: 1px solid #dee2e6;
        padding: 0 0 12px 0 !important;
    }
    #dashboardContenido > .d-flex:first-child > div:first-child img {
        width: 320px !important;
    }
}

@media print {
    @page {
        size: A4 portrait;
        margin: 9mm;
    }

    html, body {
        width: 100% !important;
        min-width: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        overflow: visible !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .sidebar, nav, .btn, form, footer {
        display: none !important;
    }

    .container-fluid,
    .row,
    main,
    #dashboardContenido {
        width: 100% !important;
        max-width: 100% !important;
        margin: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        overflow: visible !important;
    }

    main {
        display: block !important;
        flex: 0 0 100% !important;
    }

    #dashboardContenido {
        padding-top: 0 !important;
    }

    .card {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
        box-shadow: none !important;
        border: 1px solid #cfcfcf !important;
        margin-bottom: 7mm !important;
        overflow: visible !important;
    }

    .card-header {
        padding: 7px 10px !important;
    }

    .chart-box {
        position: relative !important;
        width: 100% !important;
        height: 235px !important;
        min-height: 235px !important;
        padding: 8px 10px !important;
        overflow: visible !important;
    }

    .chart-box canvas {
        display: block !important;
        width: 100% !important;
        height: 100% !important;
        max-width: 100% !important;
    }

    #dashboardContenido > .d-flex:first-child > div:first-child > div:first-child {
        width: 310px !important;
        min-width: 310px !important;
        min-height: 100px !important;
    }

    #dashboardContenido > .d-flex:first-child > div:first-child img {
        width: 295px !important;
        max-height: 92px !important;
    }

    .dashboard-kpi .display-6 {
        font-size: 20pt !important;
    }

    /* Los tres gráficos pasan juntos a una segunda página para evitar cortes. */
    .charts-section {
        break-before: page !important;
        page-break-before: always !important;
        display: block !important;
    }

    .charts-section > .col-12 {
        width: 100% !important;
        max-width: 100% !important;
        margin-bottom: 0 !important;
        padding: 0 !important;
    }

    .charts-section .card {
        margin-bottom: 5mm !important;
    }

    .text-muted.small.mt-4 {
        margin-top: 2mm !important;
        font-size: 8pt !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
const meses = <?= $mesesJson ?>;
const datosOcurrencias = <?= $ocurrenciasJson ?>;
const etiquetasIpress = <?= $ipressLabels ?>;
const datosIpress = <?= $ipressValues ?>;
const etiquetasAfiliados = <?= $afiliadosLabels ?>;
const datosAfiliados = <?= $afiliadosValues ?>;

// Registrar el complemento que mantiene visibles las etiquetas de datos.
Chart.register(ChartDataLabels);

const opcionesBase = {
    responsive: true,
    maintainAspectRatio: false,
    layout: {
        padding: {
            right: 42,
            top: 18
        }
    },
    plugins: {
        legend: {
            display: false
        },
        datalabels: {
            display: true,
            color: '#222222',
            anchor: 'end',
            align: 'end',
            offset: 6,
            clamp: false,
            clip: false,
            font: {
                weight: 'bold',
                size: 12
            },
            formatter: function(value) {
                return Number(value).toLocaleString('es-PE');
            }
        },
        tooltip: {
            enabled: true
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                precision: 0
            }
        }
    }
};

const chartIpress = new Chart(document.getElementById('graficoIpress'), {
    type: 'bar',
    data: { labels: etiquetasIpress, datasets: [{ label: 'IPRESS', data: datosIpress, backgroundColor: '#3978b8' }] },
    options: { ...opcionesBase, indexAxis: 'y' }
});
const chartAfiliados = new Chart(document.getElementById('graficoAfiliados'), {
    type: 'bar',
    data: { labels: etiquetasAfiliados, datasets: [{ label: 'Afiliados', data: datosAfiliados, backgroundColor: '#26956d' }] },
    options: { ...opcionesBase, indexAxis: 'y' }
});
const chartOcurrencias = new Chart(document.getElementById('graficoOcurrencias'), {
    type: 'line',
    data: { labels: meses, datasets: [{ label: 'Ocurrencias', data: datosOcurrencias, borderColor: '#d18a28', backgroundColor: 'rgba(209,138,40,.15)', fill: true, tension: .25 }] },
    options: {
        ...opcionesBase,
        layout: {
            padding: {
                top: 30,
                right: 18,
                bottom: 8
            }
        },
        plugins: {
            ...opcionesBase.plugins,
            datalabels: {
                ...opcionesBase.plugins.datalabels,
                display: true,
                color: '#111111',
                anchor: 'end',
                align: 'top',
                offset: 8,
                clamp: true,
                clip: false,
                font: {
                    weight: 'bold',
                    size: 13
                },
                formatter: function(value) {
                    return Number(value).toLocaleString('es-PE');
                }
            }
        },
        scales: {
            ...opcionesBase.scales,
            y: {
                ...opcionesBase.scales.y,
                beginAtZero: true,
                grace: '20%',
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Recalcular el tamaño de los gráficos antes de abrir el diálogo de impresión.
// Esto evita que Chart.js conserve las dimensiones de pantalla y recorte etiquetas o barras.
window.addEventListener('beforeprint', function () {
    [chartIpress, chartAfiliados, chartOcurrencias].forEach(function (chart) {
        chart.resize();
        chart.update('none');
    });
});

window.addEventListener('afterprint', function () {
    [chartIpress, chartAfiliados, chartOcurrencias].forEach(function (chart) {
        chart.resize();
        chart.update('none');
    });
});

function descargarGrafico(id, nombre) {
    const canvas = document.getElementById(id);
    const enlace = document.createElement('a');
    enlace.href = canvas.toDataURL('image/png', 1);
    enlace.download = nombre + '.png';
    enlace.click();
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
