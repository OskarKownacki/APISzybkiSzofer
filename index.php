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
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
<link href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" rel="stylesheet"/>
    <nav class="container my-4">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="input-group" style="width:50%">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control form-control-lg" id="vehicleInput"
                        placeholder="Enter line number...">
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">

        

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-bus me-2"></i>Vehicle Tracker</h3>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="velocity-tab" data-bs-toggle="tab"
                            data-bs-target="#velocity-tab-pane" type="button" role="tab">Velocity</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="late-tab" data-bs-toggle="tab" data-bs-target="#late-tab-pane"
                            type="button" role="tab">Late</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="map-tab" data-bs-toggle="tab" data-bs-target="#map-tab-pane"
                            type="button" role="tab">Map</button>
                    </li>
                </ul>
                <div class="card-body">
                <div class="tab-content p-3 border border-top-0 rounded-bottom" id="myTabContent">
                    <div class="tab-pane fade show active" id="velocity-tab-pane" role="tabpanel">
                        <!-- Search Section -->
                        <div class="search-container mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-8">
                                    <label for="dateFrom">From:</label>
                                    <input type="date" id="dateFrom" name="dateFrom"
                                        class="form-control form-control-sm d-inline-block w-auto">
                                    <label for="dateTo">To:</label>
                                    <input type="date" id="dateTo" name="dateTo"
                                        class="form-control form-control-sm d-inline-block w-auto">
                                    <button class="btn btn-primary btn-sm ms-2" onclick="filterByDate('statistic', velocityHistoryTable)">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-grid w-100">
                                        <button class="btn btn-success mx-1"
                                            onclick="velocityTable.buttons(0).trigger()">
                                            <i class="fas fa-file-excel me-1"></i> Export Excel
                                        </button>
                                    </div>
                                    <div class="d-grid w-100">
                                        <button class="btn btn-danger" onclick="velocityTable.buttons(1).trigger()">
                                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table id="velocityTable" class="table table-striped table-hover table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Vehicle ID</th>
                                        <th class="text-center">Velocity (km/h)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>



                        <canvas id="myChart" style="width:100%"></canvas>
                        <div class="table-responsive">
                            <table id="velocityHistoryTable"
                                class="table table-striped table-hover table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Vehicle ID</th>
                                        <th class="text-center">Velocity (km/h)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="late-tab-pane" role="tabpanel">
                    <div class="search-container mb-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-8">
                                    <label for="dateFrom">From:</label>
                                    <input type="date" id="dateFrom" name="dateFrom"
                                        class="form-control form-control-sm d-inline-block w-auto">
                                    <label for="dateTo">To:</label>
                                    <input type="date" id="dateTo" name="dateTo"
                                        class="form-control form-control-sm d-inline-block w-auto">
                                    <button class="btn btn-primary btn-sm ms-2" onclick="filterByDate('statistic_punctuality', lateHistoryTable)">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <div class="d-grid w-100">
                                        <button class="btn btn-success mx-1"
                                            onclick="lateTable.buttons(0).trigger()">
                                            <i class="fas fa-file-excel me-1"></i> Export Excel
                                        </button>
                                    </div>
                                    <div class="d-grid w-100">
                                        <button class="btn btn-danger" onclick="lateTable.buttons(1).trigger()">
                                            <i class="fas fa-file-pdf me-1"></i> Export PDF
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Table Section -->
                        <div class="table-responsive">
                            <table id="lateTable" class="table table-striped table-hover table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Vehicle ID</th>
                                        <th class="text-center">Punctuality (min)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>



                        <canvas id="lateChart" style="width:100%"></canvas>
                        <div class="table-responsive">
                            <table id="lateHistoryTable"
                                class="table table-striped table-hover table-bordered w-100">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="text-center">Vehicle ID</th>
                                        <th class="text-center">Punctuality (min)</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="map-tab-pane" role="tabpanel">
                    <div id="osm-map"></div>

                </div>
                </div>

            </div>
            <div class="card-footer text-muted">
                <small>Data last updated: <span id="updateTime"></span></small>
            </div>

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
    var element = document.getElementById('osm-map');

	element.style = 'height:300px;';

	var map = L.map(element);

	// Add OSM tile layer to the Leaflet map.
	L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	// Target's GPS coordinates.
	var target1 = L.latLng('53.429', '14.5610');
	// Target's GPS coordinates.


	// Set map's center to target with zoom 14.
	map.setView(target1, 14);



	// Place a marker on the same location.
    let lateChart, velChart;
    var velocityHistoryTable = $('#velocityHistoryTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'pB>>",
        buttons: [{
            extend: 'excelHtml5',
            className: 'd-none',
            text: '<i class="fas fa-file-excel me-1"></i> Excel',
            title: 'Historical_Vehicle_Data'
        }],
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
            },
            {
                data: "vehicle_line",
                className: "text-center",
                title: "Line Number"
            },
            {
                data: "time_added",
                className: "text-center",
                render: function(data) {
                    return new Date(data * 1000).toLocaleString();
                },
                title: "Recorded At"
            }
        ],
        language: {
            // Same as your other table
        },
        responsive: true
    });

    var lateHistoryTable = $('#lateHistoryTable').DataTable({
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'pB>>",
        buttons: [{
            extend: 'excelHtml5',
            className: 'd-none',
            text: '<i class="fas fa-file-excel me-1"></i> Excel',
            title: 'Historical_Vehicle_Data'
        }],
        columns: [{
                data: "vehicle_id",
                className: "text-center"
            },
            {
                data: "punctuality",
                className: "text-center",
                render: function(data) {
                    return data + 'min';
                }
            },
            {

                data: "vehicle_line",
                className: "text-center",
                title: "Line Number"
            },
            {
                data: "time_added",
                className: "text-center",
                render: function(data) {
                    return new Date(data * 1000).toLocaleString();
                },
                title: "Recorded At"
            }
        ],
        language: {
            // Same as your other table
        },
        responsive: true
    });

    function filterByDate(dataBase, table) {
        const fromDate = document.getElementById('dateFrom').value;
        const toDate = document.getElementById('dateTo').value;
        const vehicleInput = document.getElementById('vehicleInput').value;

        // Convert dates to timestamps
        const fromTimestamp = fromDate ? Math.floor(new Date(fromDate).getTime() / 1000) : null;
        const toTimestamp = toDate ? Math.floor(new Date(toDate + 'T23:59:59').getTime() / 1000) : null;

        $.ajax({
            url: "fetchHistoricalData.php",
            type: "GET",
            dataType: "json",
            data: {
                dataBase: dataBase,
                from: fromTimestamp,
                to: toTimestamp,
                line: vehicleInput || null
            },
            success: function(data) {
                table.clear().rows.add(data).draw();
            },
            error: function(xhr, status, error) {
                console.error("Error fetching historical data:", error);
            }
        });
    }

    var velocityTable = $('#velocityTable').DataTable({
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

    var lateTable = $('#lateTable').DataTable({
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
                data: "punctuality",
                className: "text-center",
                render: function(data) {
                    return data + 'min';
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
            velocityTable.ajax.reload(function() {
                // Update timestamp after reload
                document.getElementById('updateTime').textContent = new Date().toLocaleString();
            });
        }, 500);
    });
    document.getElementById('vehicleInput').addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(function() {
            lateTable.ajax.reload(function() {
                // Update timestamp after reload
                document.getElementById('updateTime').textContent = new Date().toLocaleString();
            });
        }, 500);
    });

    function reloadTable(table, chart, param, label) {
        table.ajax.reload(function() {
            document.getElementById('updateTime').textContent = new Date().toLocaleString();
        });
        updateChart(chart, table, param, label);
    }


    function sendVelocityDataToDb() {
        let dataArray = velocityTable.rows().data().toArray();
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

    function sendPunctualityDataToDb() {
        let dataArray = lateTable.rows().data().toArray();
        return $.ajax({
            url: "savePunctualityStatistics.php",
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

    function updateChart(chart, table, param, label) {
    const data = table.rows().data().toArray();
    const values = data.map(vehicle => vehicle[param]);
    const labels = data.map(vehicle => vehicle.vehicle_id);
    
    if (chart) {
        chart.data.labels = labels;
        chart.data.datasets[0].data = values;
        chart.data.datasets[0].label = label;
        chart.update();
    }
}

    function createChart(id, param, table, label) {
    const ctx = document.getElementById(id);
    
    // Destroy existing chart if it exists
    if (id === 'myChart' && velChart) {
        velChart.destroy();
    } else if (id === 'lateChart' && lateChart) {
        lateChart.destroy();
    }
    
    const data = table.rows().data().toArray();
    const values = data.map(vehicle => vehicle[param]);
    const labels = data.map(vehicle => vehicle.vehicle_id);
    
    const newChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: values,
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
    
    // Store the reference to the new chart
    if (id === 'myChart') {
        velChart = newChart;
    } else if (id === 'lateChart') {
        lateChart = newChart;
    }
}
let markerGroup = L.layerGroup().addTo(map);
function updateMap(){
    markerGroup.clearLayers(); // This removes all markers
    
    arrayMap = velocityTable.rows().data().toArray();
    arrayMap.forEach(element => {
        let targetTemp = L.latLng(element.latitude, element.longitude);
        L.marker(targetTemp).addTo(markerGroup);
    });
}

   
$(window).on("load", function() {
    createChart('myChart', 'velocity', velocityTable, 'vehicle velocity');
    createChart('lateChart', 'punctuality', lateTable, 'vehicle punctuality');
});

setInterval(() => {
    velocityTable.ajax.reload(function() {
        document.getElementById('updateTime').textContent = new Date().toLocaleString();
        updateChart(velChart, velocityTable, 'velocity', 'vehicle velocity');
    });
}, 5000);

setInterval(() => {
    lateTable.ajax.reload(function() {
        document.getElementById('updateTime').textContent = new Date().toLocaleString();
        updateChart(lateChart, lateTable, 'punctuality', 'vehicle punctuality');
    });
}, 5000);

setInterval(updateMap, 5000);
    </script>
</body>

</html>