<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Moderator - Mara Lacto</title>
  <!-- Include Foundation CSS prin CDN -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/css/foundation.min.css" crossorigin="anonymous">
  <!-- Link către stilurile custom, dacă există -->
  <link rel="stylesheet" href="assets/css/custom.css">
  <style>
    body {
      padding-top: 20px;
    }
    /* Container pentru grafic, centrat și cu lățime maximă */
    .chart-container {
      width: 100%;
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
    }
  </style>
</head>
<body>
  <!-- Navigație -->
  <div class="top-bar">
    <div class="top-bar-left">
      <ul class="menu">
        <li class="menu-text">Mara Lacto - Moderator</li>
        <?php
        require_once "includes/dbh.inc.php";
        session_start();
        $user_type = $_SESSION['user_type'] ?? 1; // default admin, fallback if not set
        $meniu = [];
        $stmt = $pdo->prepare("SELECT p.nume_meniu, p.pagina FROM pagini p INNER JOIN drepturi d ON p.id = d.IdPage WHERE p.meniu = 1 AND d.IdUser = ? ORDER BY p.id ASC");
        $stmt->execute([$user_type]);
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $meniu[] = $row;
        }
        // Get total users
        $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        foreach ($meniu as $item): ?>
          <li><a href="<?php echo htmlspecialchars($item['pagina']); ?>"><?php echo htmlspecialchars($item['nume_meniu']); ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="top-bar-right">
      <ul class="menu">
        <li><a href="index.html" class="button">Log Out</a></li>
      </ul>
    </div>
  </div>

  <!-- Conținut Dashboard -->
  <div class="grid-container">
    <div class="grid-x grid-padding-x">
      <div class="cell small-12">
        <h1>Dashboard Moderator</h1>
        <p>Bun venit, moderator! Aici poți vizualiza statistici de activitate și informații financiare ale magazinului.</p>
        <!-- Exemplu widget -->
        <div class="callout primary">
          <h5>Total Utilizatori</h5>
          <p><?php echo $totalUsers; ?></p>
        </div>

        <!-- Graficul Financiar -->
        <div class="chart-container">
          <canvas id="financialChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="grid-container">
    <hr>
    <p class="text-center">&copy; 2025 Mara Lacto. Pagina de moderator.</p>
  </footer>

  <!-- Scripturi: jQuery, Foundation JS și Chart.js prin CDN -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/foundation-sites@6.7.5/dist/js/foundation.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    $(document).foundation();

    // Setare grafic: date exemplu
    const ctx = document.getElementById('financialChart').getContext('2d');
    const financialChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: [
          'Ianuarie', 'Februarie', 'Martie', 'Aprilie', 'Mai', 'Iunie', 
          'Iulie', 'August', 'Septembrie', 'Octombrie', 'Noiembrie', 'Decembrie'
        ],
        datasets: [
          {
            label: 'Încasări (RON)',
            data: [12000, 10000, 9000, 10500, 12000, 13000, 15000, 13500, 11000, 13000, 16000, 17000],
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
          },
          {
            label: 'Cheltuieli (RON)',
            data: [8000, 8000, 8000, 8000, 8000, 8000, 8000, 8000, 8000, 8000, 9000, 8000],
            backgroundColor: 'rgba(255, 99, 132, 0.7)',
            borderColor: 'rgba(255, 99, 132, 1)',
            borderWidth: 1
          },
          {
            label: 'Câștig Net (RON)',
            data: [4000, 2000, 1000, 2500, 4000, 5000, 7000, 5500, 3000, 4000, 7000, 9000],
            backgroundColor: 'rgba(75, 192, 192, 0.7)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
          }
        ]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return value + ' RON';
              }
            }
          }
        },
        responsive: true,
        plugins: {
          title: {
            display: true,
            text: 'Situația Financiară Lunară'
          }
        }
      }
    });
  </script>
</body>
</html>
