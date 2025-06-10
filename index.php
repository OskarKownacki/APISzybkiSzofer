<!DOCTYPE html>
<html lang="en">

<head>


    <!---
1. ZDITM Szczecin udostępnia API dla programitów: https://www.zditm.szczecin.pl/pl/zditm/dla-programistow/api-linie
	Zapoznaj się z nim
2. Chcę, abyś przygotował stronę z 5 statystykami: tabele+wykresy do analizy dowolnych danych i zestawień np:
	na wykresie są różne modele autobusów
	a w tabelce ich zestawienie: nr_linii, nr_boczny, gdzie jest itp
	
3.	Chcę, abyś też umieścił mapę. Na stronie ładujesz z API numery linii do pola select[option]. Człowiek wybiera linię
	i mapa pokazuje wszystkie pojazdy na danej linii.
4. Coś na 6. Zrób filtry dla wykresów, abym mógł np suwakami jeżdżąc wyświetlał dane z danej godziny. Musisz sobie gdzieś zrzucić potrzebne dane z kilku dni, bo te API tego nie posiada.



*/-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Szybki Szofer</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS with Bootstrap 5 styling -->
    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.1/b-3.2.3/b-html5-3.2.3/cc-1.0.2/sr-1.4.1/datatables.min.css"
        rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.3.2/b-3.2.3/b-html5-3.2.3/datatables.min.css"
        rel="stylesheet" integrity="sha384-cUwynvVEspVhxrXYAuUW86OEATeSsRoRFF7KHu+VmqxUVYBhubNKR1WxV+z6pnFI"
        crossorigin="anonymous">

    <link href="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.3.2/b-3.2.3/b-html5-3.2.3/cc-1.0.4/datatables.min.css"
        rel="stylesheet" integrity="sha384-SHbyZKF7PSmTi23Pzu2xCTNP0LCxcsuHsyDpZoujlJ763bm4l2QiDU9280Rgipms"
        crossorigin="anonymous">

    <script src="https://cdn.datatables.net/v/dt/jszip-3.10.1/dt-2.3.2/b-3.2.3/b-html5-3.2.3/cc-1.0.4/datatables.min.js"
        integrity="sha384-lEV7R4K8I+PYG2kqJZHua2ao4z/uWJ6m8V04oJxiYTEy2ojGkDe4u/7h+RfzvbZL" crossorigin="anonymous">
    </script>


    <script src="https://code.jquery.com/jquery-3.7.1.js"
        integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <style>
    /* Custom styling */
    .dataTables_wrapper {
        padding: 20px 0;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .search-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    #firstTable th {
        background-color: #0d6efd;
        color: white;
    }

    .dt-button {
        border-radius: 5px !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }
    </style>
</head>

<body>


    <div class="container py-4">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="velocity-tab" data-bs-toggle="tab"
                    data-bs-target="#velocity-tab-pane" type="button" role="tab">Velocity</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="late-tab" data-bs-toggle="tab" data-bs-target="#late-tab-pane"
                    type="button" role="tab">Late</button>
            </li>
        </ul>
        <div class="tab-content p-3 border border-top-0 rounded-bottom" id="myTabContent">
            <div class="tab-pane fade show active" id="velocity-tab-pane" role="tabpanel">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0"><i class="fas fa-bus me-2"></i>Vehicle Tracker</h3>
                    </div>

                    <div class="card-body">
                        <!-- Search Section -->
                        <div class="search-container mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-8">
                                    <label for="vehicleInput" class="form-label">Search by Line Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" class="form-control form-control-lg" id="vehicleInput"
                                            placeholder="Enter line number...">
                                        <button class="btn btn-primary" onclick="reloadTable()">
                                            <i class="fas fa-sync-alt me-1"></i> Search
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-grid w-100">
                                        <button class="btn btn-success mx-1" onclick="table.buttons(0).trigger()">
                                            <i class="fas fa-file-excel me-1"></i> Export Excel
                                        </button>
                                    </div>
                                    <div class="d-grid w-100">
                                        <button class="btn btn-danger" onclick="table.buttons(1).trigger()">
                                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table id="firstTable" class="table table-striped table-hover table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Vehicle ID</th>
                                        <th class="text-center">Velocity (km/h)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <small>Data last updated: <span id="updateTime"></span></small>
                    </div>
                </div>
                <canvas id="myChart" style="width:100%"></canvas>
            </div>

            <div class="tab-pane fade show" id="late-tab-pane" role="tabpanel">
                siiigma
                date_subsda

                dsadsa

            </div>
        </div>


    </div>
    </div>
    </div>


    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script
        src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.1/b-3.2.3/b-html5-3.2.3/cc-1.0.2/sr-1.4.1/datatables.min.js">
    </script>

    <script>
    // Initialize DataTable
    var table = $('#firstTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'pB>>",
        buttons: [{
                extend: 'excelHtml5',
                className: 'd-none',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                title: 'Vehicle_Data'
            },
            {
                extend: 'pdfHtml5',
                className: 'd-none',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                title: 'Vehicle_Data'
            }
        ],
        ajax: {
            url: "./fetchVehicle.php",
            type: "GET",
            dataType: "json",
            dataSrc: "",
            data: function(d) {
                var input = document.getElementById('vehicleInput');
                if (input.value) {
                    d.q = input.value;
                }
            }
        },
        columns: [{
                data: "vehicle_id",
                className: "text-center"
            },
            {
                data: "velocity",
                className: "text-center",
                render: function(data) {
                    return data + ' km/h';
                }
            }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search in table...",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries found",
            infoFiltered: "(filtered from _MAX_ total entries)",
            loadingRecords: "Loading...",
            zeroRecords: "No matching records found",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        responsive: true,
        initComplete: function() {
            // Update timestamp on load
            document.getElementById('updateTime').textContent = new Date().toLocaleString();
        }
    });

    // Add event listener for input changes with debounce
    let timeout;
    document.getElementById('vehicleInput').addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            table.ajax.reload(function() {
                // Update timestamp after reload
                document.getElementById('updateTime').textContent = new Date().toLocaleString();
            });
        }, 500);
    });

    function reloadTable() {
        table.ajax.reload(function() {
            document.getElementById('updateTime').textContent = new Date().toLocaleString();
        });
        updateChart(velChart, table);
    }

    function sendDataToDb() {
        let dataArray = table.rows().data().toArray();
        return $.ajax({
            url: "saveStatistics.php",
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                dataArray: dataArray
            }),
            success: function(response) {
                console.log(response);
            }
        });
    }

    function updateChart(chart, table) {
        const data = table.rows().data().toArray();
        const velocities = data.map(vehicle => vehicle.velocity);
        const labels = data.map(vehicle => vehicle.vehicle_id);
        chart.data = {
            labels: labels,
            datasets: [{
                label: 'vehicle speed',
                data: velocities,
                borderWidth: 1
            }]
        };
        chart.update();
    }
    var velChart;

    function createChart() {
        const ctx = document.getElementById('myChart');
        const data = table.rows().data().toArray();
        const velocities = data.map(vehicle => vehicle.velocity);
        const labels = data.map(vehicle => vehicle.vehicle_id);
        velChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'vehicle speed',
                    data: velocities,
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
    $(window).on("load", createChart());
    setInterval(reloadTable, 5000);
    setInterval(sendDataToDb, 10000);
    </script>
</body>

</html>