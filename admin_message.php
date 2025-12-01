<?php
session_start();
if (!$_SESSION['admin']) {
  header('Location: index.php');
  exit();
}

$pdo = require 'koneksi.php';
$sql = "SELECT messages.*, users.username, users.email, users.profile FROM messages 
        INNER JOIN users ON messages.id_user = users.id
        ORDER BY time ASC";
$query = $pdo->query($sql);
$messages = $query->fetchAll();


function formatTanggalHeader($date)
{
  $today = date("Y-m-d");
  $yesterday = date("Y-m-d", strtotime("-1 day"));
  $d = date("Y-m-d", strtotime($date));

  if ($d == $today) return "Hari Ini";
  if ($d == $yesterday) return "Kemarin";


  setlocale(LC_TIME, 'id_ID.UTF-8');
  return strftime("%e %B %Y", strtotime($date));
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Message (Single Feed) | Orinuts</title>
  <link rel="stylesheet" href="src/outputail.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
</head>

<body class="flex min-h-screen bg-[#F0F0E8]">
  <aside class="bg-white text-[#8B4513] shadow-lg rounded-r-3xl min-h-screen flex-shrink-0 w-63">
    <div class="fixed p-5 w-63">
      <div class="logo mb-6">
        <img src="content/logo.png" alt="Logo Orinuts" class="w-28" />
      </div>
      <nav>
        <ul class="space-y-1 text-sm">
          <a href="admin_dashboard.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-home w-4"></i> Beranda
            </li>
          </a>
          <a href="admin_product.php">
            <li
              class="nav-item p-2.5 flex items-center gap-3 cursor-pointer hover:bg-[#E0BBA1]/20 rounded-r-3xl transition">
              <i class="fas fa-box-open w-4"></i> Produk
            </li>
          </a>
          <li
            class="nav-item font-bold p-2.5 flex items-center gap-3 cursor-pointer text-[#8B4513] bg-[#D2A278] rounded-r-3xl">
            <i class="fas fa-comment w-4"></i> Message
            <span class="ml-auto bg-red-600 text-white text-xs font-semibold px-2 py-0.5 rounded-full">2</span>
          </li>
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
    </div>
  </aside>

  <main class="flex-grow flex flex-col p-5">
    <header class="flex justify-end items-center mb-5 p-3 rounded-xl text-[#8B4513] font-semibold">
      <h1 class="text-2xl font-normal mr-auto text-[#8B4513]">
        HALAMAN ADMIN!!
      </h1>
      <div class="flex items-center space-x-2 cursor-pointer">
        <i class="fas fa-user-circle text-2xl"></i>
      </div>
    </header>

    <div class="flex-grow bg-white rounded-3xl shadow-lg flex h-full">
      <div class="flex-grow flex flex-col" id="chat-window">

        <!-- AREA SCROLL -->
        <div id="scroll-area" class="flex-grow p-5 overflow-y-scroll space-y-4 rounded-tr-3xl max-h-[600px]">
          <?php
          $lastDate = null;
          foreach ($messages as $msg) {
            $messageDate = date("Y-m-d", strtotime($msg['time']));
            $base64 = base64_encode($msg['profile']);
            if ($messageDate !== $lastDate) { ?>

              <div class="flex justify-center my-3 day-separator">
                <span class="bg-gray-200 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">
                  <?= formatTanggalHeader($msg['time']); ?>
                </span>
              </div>

            <?php $lastDate = $messageDate;
            } ?>

            <div class="flex justify-between message-bubble">
              <div class="min-w-full bg-[#F0F0E8] p-3 rounded-xl rounded-br-none shadow-md text-sm text-[#8B4513]">

                <div class="flex items-center gap-2 mb-2 pb-2 border-b border-gray-300">
                  <div class="w-8 h-8 rounded-full overflow-hidden">
                    <img src="data:image/*;base64, <?= $base64 ?>" class="w-full h-full object-cover">
                  </div>
                  <div class="flex-grow min-w-0">
                    <p class="text-sm font-bold text-[#8B4513] truncate"><?= htmlspecialchars($msg['username']) ?></p>
                    <p class="text-xs text-gray-600 truncate"><?= htmlspecialchars($msg['email']) ?></p>
                  </div>
                </div>

                <div class="flex flex-col min-w-[150px]">
                  <p class="mr-3"><?= htmlspecialchars($msg['message']) ?></p>
                  <span class="text-xs text-gray-500/70 whitespace-nowrap self-end mt-1">
                    <?= date("H:i", strtotime($msg['time'])) ?>
                  </span>
                </div>

              </div>
            </div>

          <?php } ?>
        </div>

      </div>
    </div>

  </main>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const scrollArea = document.getElementById("scroll-area");
      scrollArea.scrollTop = scrollArea.scrollHeight;
    });
  </script>

</body>

</html>