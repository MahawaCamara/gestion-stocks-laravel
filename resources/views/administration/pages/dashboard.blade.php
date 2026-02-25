@extends('administration.base')

@section('content')

<div class="page-inner">
    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
      <div>
        <h3 class="fw-bold mb-3">Tableau de bord</h3>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-primary bubble-shadow-small"
                >
                  <i class="fas fa-box"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total Produits</p>
                  <h4 class="card-title">{{$produits_count}}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-info bubble-shadow-small"
                >
                  <i class="fas fa-shopping-cart"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Total. Ventes</p>
                  <h4 class="card-title">{{ $ventes_count }} GNF</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-success bubble-shadow-small"
                >
                  <i class="fas fa-box-open fa-2x"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Stock faible</p>
                  <h4 class="card-title">{{ $produits_faibles->count() }} prod(s)</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-3">
        <div class="card card-stats card-round">
          <div class="card-body">
            <div class="row align-items-center">
              <div class="col-icon">
                <div
                  class="icon-big text-center icon-secondary bubble-shadow-small"
                >
                  <i class="fas fa-user-tie"></i>
                </div>
              </div>
              <div class="col col-stats ms-3 ms-sm-0">
                <div class="numbers">
                  <p class="card-category">Employés</p>
                  <h4 class="card-title">{{ $employes_count }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- charts -->
<select id="periodeVente" class="form-select mb-2">
    <option value="jour">Semaine</option>
    <option value="mois">Mois</option>
    <option value="annee">Année</option>
</select>
<canvas id="chartVentes" height="120"></canvas>

<select id="periodeAppro" class="form-select mt-4 mb-2">
    <option value="jour">Semaine</option>
    <option value="mois">Mois</option>
    <option value="annee">Année</option>
</select>
<canvas id="chartAppros" height="120"></canvas>
@endsection
<script>
    // chart
const labelsJour = {!! json_encode($labelsSemaine) !!};
const labelsMois = {!! json_encode($labelsMois) !!};
const labelsAnnee = {!! json_encode($labelsAnnee) !!};

const ventesJour = {!! json_encode($ventesSemaine) !!};
const ventesMois = {!! json_encode($ventesMois) !!};
const ventesAnnee = {!! json_encode($ventesAnnee) !!};

const approsJour = {!! json_encode($approsSemaine) !!};
const approsMois = {!! json_encode($approsMois) !!};
const approsAnnee = {!! json_encode($approsAnnee) !!};

let chartVente, chartAppro;

function createBarChart(ctx, labels, data, label) {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function createDoughnutChart(ctx, labels, data, label) {
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: [
                    '#f39c12', '#e74c3c', '#9b59b6',
                    '#3498db', '#1abc9c', '#2ecc71',
                    '#34495e', '#95a5a6'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true
        }
    });
}

function updateChart(chart, labels, data) {
    chart.data.labels = labels;
    chart.data.datasets[0].data = data;
    chart.update();
}

document.addEventListener('DOMContentLoaded', function () {
    const ctxVente = document.getElementById('chartVentes').getContext('2d');
    chartVente = createBarChart(ctxVente, labelsJour, ventesJour, "Ventes");

    const ctxAppro = document.getElementById('chartAppros').getContext('2d');
    chartAppro = createDoughnutChart(ctxAppro, labelsJour, approsJour, "Approvisionnements");

    document.getElementById('periodeVente').addEventListener('change', function () {
        switch (this.value) {
            case 'jour': updateChart(chartVente, labelsJour, ventesJour); break;
            case 'mois': updateChart(chartVente, labelsMois, ventesMois); break;
            case 'annee': updateChart(chartVente, labelsAnnee, ventesAnnee); break;
        }
    });

    document.getElementById('periodeAppro').addEventListener('change', function () {
        switch (this.value) {
            case 'jour': updateChart(chartAppro, labelsJour, approsJour); break;
            case 'mois': updateChart(chartAppro, labelsMois, approsMois); break;
            case 'annee': updateChart(chartAppro, labelsAnnee, approsAnnee); break;
        }
    });
});
</script>