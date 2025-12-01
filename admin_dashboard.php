<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
}

// PERBAIKAN: Ambil SEMUA data transaksi, bukan hanya bulan ini
$pdo = require 'koneksi.php';

$query = $pdo->prepare("
    SELECT DATE(tanggal) AS tanggal_lengkap, SUM(total_harga) AS total
    FROM transactions
    GROUP BY DATE(tanggal)
    ORDER BY tanggal_lengkap
");
$query->execute();

$allData = $query->fetchAll(PDO::FETCH_ASSOC);

// Konversi ke format yang mudah dipakai JS
$salesData = [];
$kurs = 16000;

foreach ($allData as $row) {
  $date = $row['tanggal_lengkap']; // Format: YYYY-MM-DD
  $usd = round($row['total'] / $kurs, 2);
  $salesData[$date] = $usd;
}

// Kirim ke JavaScript sebagai objek
echo "<script>
    const allSalesData = " . json_encode($salesData) . ";
</script>";

?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Ringkas - Partner Coding</title>
  <link rel="stylesheet" href="src/outputail.css" />

  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <style>
    body {
      background-repeat: no-repeat;
      background-position: left bottom;
      background-size: 15%;
      background-attachment: fixed;
    }
  </style>
</head>

<body class="bg-[#D2A278] text-gray-800">
  <div class="h-screen grid grid-cols-[250px_1fr]">
    <aside
      class="bg-white p-5 text-[#8B4513] overflow-y-auto shadow-lg rounded-r-3xl min-h-screen">
      <div class="logo mb-6">
        <img src="content/logo.png" alt="Logo Orinuts" class="w-28" />
      </div>
      <nav>
        <ul class="space-y-1 text-sm">
          <li
            class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer text-[#8B4513] bg-[#D2A278] rounded-r-3xl">
            <i class="fas fa-home w-4"></i> Beranda
          </li>
          <a href="admin_product.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-box-open w-4"></i> Produk
            </li>
          </a>
          <a href="admin_message.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-comment w-4"></i> Message
              <span
                class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
            </li>
          </a>
          <a href="admin_order.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-shopping-cart w-4"></i> Order
            </li>
          </a>
          <a href="admin_profile.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-user-circle w-4"></i> Profile
            </li>
          </a>
        </ul>
      </nav>
    </aside>

    <main class="p-6 flex flex-col">
      <header
        class="bg-white rounded-2xl flex justify-between items-center py-2 border-b border-gray-200 flex-shrink-0">
        <h2 class="text-xl font-semibold text-[#8B4513] ml-5">BERANDA</h2>
        <div class="user-profile text-[#8B4513]">
          <i class="fas fa-user-circle text-2xl cursor-pointer mr-5"></i>
        </div>
      </header>

      <div
        class="dashboard-area pt-4 flex flex-col flex-grow overflow-hidden">
        <div
          class="bg-[#F0C39F] p-5 rounded-xl text-[#8B4513] mb-4 shadow-lg flex-shrink-0">
          <h1 class="text-3xl font-bold mb-0.5">Halo, <?php echo htmlspecialchars($_SESSION['admin']['username']); ?></h1>
          <p class="text-base text-gray-700">
            Selamat datang lagi di sistem kendali, Hari ini ada peningkatan
            lho
          </p>
        </div>

        <div class="grid grid-cols-3 gap-4 mb-4 flex-grow">
          <div
            class="col-span-3 bg-white p-4 rounded-xl shadow-lg flex flex-col">
            <div
              class="flex justify-between items-center mb-2 flex-shrink-0">
              <h4
                id="chart-title"
                class="text-base font-semibold text-[#8B4513]">
                Data Penjualan (Bulanan)
              </h4>
              <div class="flex gap-2">
                <button
                  id="prev-month"
                  class="bg-[#8B4513] text-white px-3 py-1 rounded-lg hover:bg-[#A0522D] transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button
                  id="next-month"
                  class="bg-[#8B4513] text-white px-3 py-1 rounded-lg hover:bg-[#A0522D] transition-colors text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
            </div>

            <div class="flex-grow min-h-0">
              <canvas id="penjualanChart" class="h-full w-full"></canvas>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4 flex-shrink-0">
          <div
            class="stat-card bg-[#F0C39F] p-5 rounded-xl text-center text-[#8B4513] shadow-lg">
            <?php
            $sql = "SELECT COUNT(DISTINCT nama_produk) AS total_products FROM products";
            $query = $pdo->prepare($sql);
            $query->execute();
            $totalProduk = $query->fetchColumn();
            ?>
            <div class="text-3xl font-bold mb-0.5">
              <?= $totalProduk ?>
            </div>
            <div class="text-sm">Macam Produk</div>
          </div>

          <div
            class="stat-card bg-[#F0C39F] p-5 rounded-xl text-center text-[#8B4513] shadow-lg">
            <?php
            $sql = "SELECT COUNT(DISTINCT terjual) AS total_sells FROM products";
            $query = $pdo->prepare($sql);
            $query->execute();
            $totalPenjulan = $query->fetchColumn();
            ?>
            <div class="text-3xl font-bold mb-0.5" id="sold-count"><?= $totalPenjulan ?></div>
            <div class="text-sm">Produk Terjual</div>
          </div>
        </div>
      </div>
    </main>
  </div>

  <script>
    // ========================================================
    // VARIABEL GLOBAL DAN STATE
    // ========================================================
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    let currentYear = today.getFullYear();
    let currentMonth = today.getMonth();
    const monthNames = [
      "JANUARI", "FEBRUARI", "MARET", "APRIL", "MEI", "JUNI",
      "JULI", "AGUSTUS", "SEPTEMBER", "OKTOBER", "NOVEMBER", "DESEMBER",
    ];

    const limitYear = 2025;
    const limitMonth = 10;

    const baseColor = "#A0522D";
    const highlightColor = "#D2A278";
    const disabledColor = "#E0BBA1";

    const chartTitleEl = document.getElementById("chart-title");
    const prevMonthBtn = document.getElementById("prev-month");
    const nextMonthBtn = document.getElementById("next-month");
    const ctx = document.getElementById("penjualanChart").getContext("2d");

    // ========================================================
    // FUNGSI HELPER: Format tanggal ke YYYY-MM-DD
    // ========================================================
    function formatDate(year, month, day) {
      const m = String(month + 1).padStart(2, '0');
      const d = String(day).padStart(2, '0');
      return `${year}-${m}-${d}`;
    }

    // ========================================================
    // INISIALISASI CHART.JS
    // ========================================================
    const penjualanChart = new Chart(ctx, {
      type: "bar",
      data: {
        labels: [],
        datasets: [{
          data: [],
          backgroundColor: [],
          borderWidth: 1,
          borderRadius: 5,
          barThickness: 10,
        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: "rgba(0, 0, 0, 0.1)"
            },
            ticks: {
              callback: function(value) {
                return "$" + value;
              },
              font: {
                size: 10
              },
            },
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                size: 10
              }
            },
          },
        },
      },
    });

    // ========================================================
    // FUNGSI UTAMA: UPDATE CHART BERDASARKAN BULAN
    // ========================================================
    function updateChartForMonth(year, month) {
      const daysInMonth = new Date(year, month + 1, 0).getDate();

      const newLabels = [];
      const newData = [];
      const newColors = [];

      // Loop setiap hari dalam bulan yang dipilih
      for (let day = 1; day <= daysInMonth; day++) {
        newLabels.push(day);

        // Buat tanggal untuk hari ini
        const dateStr = formatDate(year, month, day);
        const currentDate = new Date(year, month, day);
        currentDate.setHours(0, 0, 0, 0);

        // Ambil data penjualan dari allSalesData (dari PHP)
        let sales = allSalesData[dateStr] || 0;

        // Tentukan warna bar
        let barColor = baseColor;
        if (currentDate < today) {
          barColor = baseColor;
        } else if (currentDate.getTime() === today.getTime()) {
          barColor = highlightColor;
        } else if (currentDate > today) {
          barColor = disabledColor;
        }

        newData.push(sales);
        newColors.push(barColor);
      }

      // Update judul chart
      chartTitleEl.textContent = `Data Penjualan (${monthNames[month]} ${year})`;

      // Update data chart
      penjualanChart.data.labels = newLabels;
      penjualanChart.data.datasets[0].data = newData;
      penjualanChart.data.datasets[0].backgroundColor = newColors;
      penjualanChart.data.datasets[0].barThickness = daysInMonth > 28 ? 10 : 12;

      penjualanChart.update();

      // Kontrol tombol navigasi
      if (year === limitYear && month === limitMonth) {
        prevMonthBtn.disabled = true;
        prevMonthBtn.classList.add("opacity-50", "cursor-not-allowed");
      } else {
        prevMonthBtn.disabled = false;
        prevMonthBtn.classList.remove("opacity-50", "cursor-not-allowed");
      }

      nextMonthBtn.disabled = false;
      nextMonthBtn.classList.remove("opacity-50", "cursor-not-allowed");
    }

    // ========================================================
    // EVENT LISTENERS
    // ========================================================
    prevMonthBtn.addEventListener("click", () => {
      if (currentYear === limitYear && currentMonth === limitMonth) {
        return;
      }

      currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      updateChartForMonth(currentYear, currentMonth);
    });

    nextMonthBtn.addEventListener("click", () => {
      currentMonth++;
      if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
      }
      updateChartForMonth(currentYear, currentMonth);
    });

    // ========================================================
    // INISIALISASI AWAL
    // ========================================================
    updateChartForMonth(currentYear, currentMonth);

    window.addEventListener("resize", () => penjualanChart.resize());
  </script>
</body>

</html>