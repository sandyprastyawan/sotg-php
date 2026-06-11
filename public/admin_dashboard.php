<?php
session_start();
include 'service/database.php';

if (!isset($_SESSION['is_login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$machine_id = $_SESSION['machine_id'];
$message    = '';

if (isset($_POST['tambah_stok'])) {
    $product_id = (int)$_POST['product_id'];
    $jumlah     = (int)$_POST['jumlah'];
    if ($jumlah > 0) {
        mysqli_query($db,
            "UPDATE `products` SET `stock` = `stock` + $jumlah
             WHERE `id` = '$product_id' AND `machine_id` = '$machine_id'"
        );
    }
    header("Location: admin_dashboard.php?success=1");
    exit();
}

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Stok berhasil ditambahkan!";
}

$mesin = mysqli_fetch_assoc(mysqli_query($db,
    "SELECT * FROM `machines` WHERE `id` = '$machine_id'"
));
$products = mysqli_query($db,
    "SELECT * FROM `products` WHERE `machine_id` = '$machine_id' ORDER BY `id` ASC"
);
$transaksi = mysqli_query($db,
    "SELECT * FROM `transaksi` WHERE `machine_id` = '$machine_id' ORDER BY `waktu_bayar` DESC"
);
$total_result     = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(`total`) as total FROM `transaksi` WHERE `machine_id` = '$machine_id' AND `status` = 'success'"));
$total_pendapatan = $total_result['total'] ?? 0;
$total_transaksi  = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM `transaksi` WHERE `machine_id` = '$machine_id'"));
$jumlah_transaksi = $total_transaksi['total'] ?? 0;
$total_stok       = mysqli_fetch_assoc(mysqli_query($db, "SELECT SUM(`stock`) as total FROM `products` WHERE `machine_id` = '$machine_id'"));
$jumlah_stok      = $total_stok['total'] ?? 0;
$hampir_habis     = mysqli_fetch_assoc(mysqli_query($db, "SELECT COUNT(*) as total FROM `products` WHERE `machine_id` = '$machine_id' AND `stock` <= 3 AND `stock` > 0"));
$jumlah_hampir_habis = $hampir_habis['total'] ?? 0;

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SanitaryOnTheGo</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
/* ══ RESET ══ */
*,*::before,*::after{margin:0;padding:0;box-sizing:border-box}
body{font-family:'Poppins',sans-serif;background:linear-gradient(145deg,#7aaec8 0%,#9bbdd4 35%,#b8d0e4 65%,#8fb8cf 100%);min-height:100vh;color:#1a1a2e}

/* ══ LAYOUT ══ */
.admin-layout{display:flex;min-height:100vh}

/* ══ SIDEBAR ══ */
.sidebar{width:250px;flex-shrink:0;background:rgba(255,255,255,0.18);backdrop-filter:blur(18px);border-right:1px solid rgba(255,255,255,0.3);display:flex;flex-direction:column;padding:26px 18px;position:sticky;top:0;height:100vh;overflow-y:auto}
.sidebar-brand{display:flex;align-items:center;gap:12px;margin-bottom:32px;padding-bottom:22px;border-bottom:1px solid rgba(255,255,255,0.25)}
.brand-icon{width:42px;height:42px;background:#00578E;border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;flex-shrink:0;box-shadow:0 4px 14px rgba(0,87,142,0.35);font-size:1.2rem;position:relative}
.brand-icon::after{content:'';position:absolute;top:-3px;right:-3px;width:10px;height:10px;background:#4ade80;border-radius:50%;border:2px solid #fff;animation:pulse 2s ease infinite}
@keyframes pulse{0%,100%{transform:scale(1);opacity:1}50%{transform:scale(1.3);opacity:.6}}
.brand-name{font-size:.85rem;font-weight:800;color:#fff;line-height:1.2;text-shadow:0 1px 4px rgba(0,0,0,.2)}
.brand-sub{font-size:.68rem;color:rgba(255,255,255,.7);font-weight:500}
.nav-label{font-size:.62rem;font-weight:700;letter-spacing:1.2px;text-transform:uppercase;color:rgba(255,255,255,.6);padding:0 8px;margin-bottom:8px;margin-top:4px}
.sidebar-nav{display:flex;flex-direction:column;gap:3px}
.nav-item{display:flex;align-items:center;gap:9px;padding:10px 13px;border-radius:10px;font-size:.83rem;font-weight:500;color:rgba(255,255,255,.8);text-decoration:none;transition:background .2s,color .2s}
.nav-item:hover{background:rgba(255,255,255,.2);color:#fff}
.nav-item-active{background:rgba(255,255,255,.25);color:#fff;font-weight:600}
.nav-icon{font-size:1rem;width:18px;text-align:center;flex-shrink:0}

/* ══ MAIN ══ */
.admin-main{flex:1;padding:26px 30px 48px;display:flex;flex-direction:column;gap:22px;overflow-y:auto}

/* ══ HEADER ══ */
.admin-header{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px}
.admin-header-title{font-size:1.55rem;font-weight:800;color:#fff;text-shadow:0 2px 10px rgba(0,0,0,.15)}
.gradient-text{background:linear-gradient(135deg,#fff 0%,#c7e8ff 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
.admin-header-meta{display:flex;align-items:center;gap:7px;margin-top:3px}
.admin-header-meta p{font-size:.78rem;color:rgba(255,255,255,.75);font-weight:500}
.ping-dot{width:8px;height:8px;background:#4ade80;border-radius:50%;flex-shrink:0;animation:pulse 2s ease infinite}
.admin-header-actions{display:flex;align-items:center;gap:9px}
.btn-icon{width:37px;height:37px;border-radius:10px;border:1px solid rgba(255,255,255,.4);background:rgba(255,255,255,.2);color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:background .2s;text-decoration:none}
.btn-icon:hover{background:rgba(255,255,255,.35)}
.btn-danger{display:flex;align-items:center;gap:7px;padding:9px 17px;background:rgba(220,53,69,.85);color:white;border:1px solid rgba(255,255,255,.2);border-radius:10px;font-family:'Poppins',sans-serif;font-size:.82rem;font-weight:600;cursor:pointer;transition:background .2s,transform .15s}
.btn-danger:hover{background:rgba(220,53,69,1);transform:translateY(-1px)}

/* ══ NOTIF ══ */
.notif-success-bar{display:flex;align-items:center;gap:10px;background:rgba(74,222,128,.18);border:1px solid rgba(74,222,128,.45);color:#fff;padding:12px 20px;border-radius:12px;font-size:.84rem;font-weight:600;transition:opacity .5s,transform .5s}

/* ══ STATS ══ */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(195px,1fr));gap:14px}
.stat-card{background:rgba(255,255,255,.92);border-radius:16px;padding:18px 20px;box-shadow:0 6px 22px rgba(0,0,0,.1);display:flex;flex-direction:column;gap:5px;transition:transform .25s,box-shadow .25s;animation:slideUp .6s cubic-bezier(.16,1,.3,1) both}
.stat-card:nth-child(1){animation-delay:.05s}
.stat-card:nth-child(2){animation-delay:.12s}
.stat-card:nth-child(3){animation-delay:.19s}
.stat-card:hover{transform:translateY(-4px);box-shadow:0 12px 34px rgba(0,0,0,.13)}
.stat-card-top{display:flex;align-items:center;justify-content:space-between}
.stat-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.05rem}
.bg-blue{background:rgba(0,87,142,.12)}
.bg-cyan{background:rgba(6,182,212,.12)}
.bg-yellow{background:rgba(245,158,11,.12)}
.stat-label{font-size:.65rem;font-weight:700;letter-spacing:.8px;text-transform:uppercase;color:#999}
.stat-title{font-size:.78rem;font-weight:600;color:#555}
.stat-value{font-size:1.35rem;font-weight:800;color:#00578E;line-height:1.2}

/* ══ TABLE CARD ══ */
.table-card{background:rgba(255,255,255,.93);border-radius:16px;box-shadow:0 6px 22px rgba(0,0,0,.1);overflow:hidden;animation:slideUp .7s cubic-bezier(.16,1,.3,1) .2s both}
.table-card-header{display:flex;align-items:center;justify-content:space-between;padding:16px 22px;border-bottom:1px solid #f0f0f0}
.table-card-header h3{display:flex;align-items:center;gap:8px;font-size:.92rem;font-weight:700;color:#1a1a2e}
.table-scroll{overflow-x:auto}
table{width:100%;border-collapse:collapse}
thead tr{background:#f7f9fc}
th{padding:11px 15px;font-size:.7rem;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#888;text-align:left;white-space:nowrap}
.table-row{border-top:1px solid #f4f4f4;transition:background .15s}
.table-row:hover{background:#f5faff}
td{padding:12px 15px;font-size:.82rem;color:#333;vertical-align:middle}
.td-bold{font-weight:600;color:#111}
.td-muted{color:#888}
.td-xs{font-size:.73rem}
.stock-num{font-size:.95rem;font-weight:800;display:inline-block;min-width:30px;text-align:center}
.stock-ok{color:#16a34a}
.stock-low{color:#dc2626}

/* ══ BADGE ══ */
.badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.68rem;font-weight:700;white-space:nowrap}
.badge-teal{background:rgba(0,87,142,.1);color:#00578E}
.badge-cyan{background:rgba(6,182,212,.1);color:#0891b2}
.badge-yellow{background:rgba(245,158,11,.12);color:#b45309}
.badge-red{background:rgba(220,53,69,.1);color:#dc2626}
.badge-slate{background:rgba(100,116,139,.1);color:#475569}

/* ══ ORDER ID ══ */
.order-id{font-family:'Courier New',monospace;font-size:.73rem;color:#555;background:#f4f4f4;padding:2px 8px;border-radius:6px}

/* ══ STOCK FORM ══ */
.stock-form{display:flex;align-items:center;gap:7px}
.stock-input{width:62px;padding:6px 9px;border:1.5px solid #dde3ea;border-radius:8px;font-family:'Poppins',sans-serif;font-size:.8rem;font-weight:600;color:#111;text-align:center;transition:border-color .2s}
.stock-input:focus{outline:none;border-color:#00578E}
.btn-primary-sm{padding:7px 13px;background:#00578E;color:white;border:none;border-radius:8px;font-family:'Poppins',sans-serif;font-size:.76rem;font-weight:600;cursor:pointer;transition:background .2s,transform .15s;white-space:nowrap}
.btn-primary-sm:hover{background:#003f6b;transform:translateY(-1px)}
.btn-primary-sm:active{transform:scale(.97)}

/* ══ ANIMATION ══ */
@keyframes slideUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}

/* ══ RESPONSIVE ══ */
@media(max-width:768px){
  .sidebar{width:200px;padding:18px 13px}
  .admin-main{padding:18px 14px 40px}
}
@media(max-width:580px){
  .admin-layout{flex-direction:column}
  .sidebar{width:100%;height:auto;position:static;padding:14px 16px}
  .sidebar-nav{flex-direction:row;flex-wrap:wrap}
}
    </style>
</head>
<body>
<div class="admin-layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">🛍️</div>
            <div>
                <p class="brand-name">SanitaryOnTheGo</p>
                <p class="brand-sub">Admin Dashboard</p>
            </div>
        </div>
        <nav class="sidebar-nav">
            <p class="nav-label">Management</p>
            <a href="#dashboard" class="nav-item nav-item-active">
                <span class="nav-icon">⊞</span> Dashboard
            </a>
            <a href="#stok" class="nav-item">
                <span class="nav-icon">📦</span> Monitoring Stok
            </a>
            <a href="#transaksi" class="nav-item">
                <span class="nav-icon">🧾</span> Riwayat Transaksi
            </a>
        </nav>
    </aside>

    <!-- Main -->
    <main class="admin-main" id="dashboard">

        <!-- Header -->
        <header class="admin-header">
            <div>
                <h2 class="admin-header-title">
                    Admin <span class="gradient-text">Dashboard</span>
                </h2>
                <div class="admin-header-meta">
                    <span class="ping-dot"></span>
                    <p>Mesin: <?php echo htmlspecialchars($mesin['name'] ?? '-'); ?> &nbsp;|&nbsp; Lokasi: <?php echo htmlspecialchars($mesin['location'] ?? '-'); ?></p>
                </div>
            </div>
            <div class="admin-header-actions">
                <button onclick="window.location.reload()" class="btn-icon" title="Refresh">↻</button>
                <form action="admin_dashboard.php" method="post" style="margin:0">
                    <button type="submit" name="logout" class="btn-danger">⎋ Logout</button>
                </form>
            </div>
        </header>

        <?php if ($message): ?>
        <div id="notif" class="notif-success-bar">
            ✔ <?php echo htmlspecialchars($message); ?>
        </div>
        <script>
        setTimeout(function(){
            var n=document.getElementById('notif');
            if(n){n.style.opacity='0';n.style.transform='translateY(-10px)';setTimeout(()=>n.remove(),500);}
        },3000);
        </script>
        <?php endif; ?>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon bg-blue">💰</div>
                    <span class="stat-label">Total</span>
                </div>
                <p class="stat-title">Pendapatan</p>
                <p class="stat-value">Rp<?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon bg-cyan">📦</div>
                    <span class="stat-label">Total</span>
                </div>
                <p class="stat-title">Stok Tersedia</p>
                <p class="stat-value"><?php echo $jumlah_stok; ?> pcs</p>
            </div>
            <div class="stat-card">
                <div class="stat-card-top">
                    <div class="stat-icon bg-yellow">⚠️</div>
                    <span class="stat-label">Alert</span>
                </div>
                <p class="stat-title">Stok Kritis</p>
                <p class="stat-value"><?php echo $jumlah_hampir_habis; ?> produk</p>
            </div>
        </div>

        <!-- Monitoring Stok -->
        <div class="table-card" id="stok">
            <div class="table-card-header">
                <h3>📦 Monitoring Stok</h3>
                <span class="badge badge-teal"><?php echo htmlspecialchars($mesin['name'] ?? '-'); ?></span>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Produk</th><th>Ukuran</th><th>Harga</th>
                            <th>Stok</th><th>Status</th><th>Tambah Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php mysqli_data_seek($products, 0); while ($product = mysqli_fetch_assoc($products)): ?>
                        <tr class="table-row">
                            <td class="td-bold"><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="td-muted"><?php echo htmlspecialchars($product['size']); ?></td>
                            <td class="td-bold">Rp<?php echo number_format($product['price'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="stock-num <?php echo $product['stock'] <= 3 ? 'stock-low' : 'stock-ok'; ?>">
                                    <?php echo $product['stock']; ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($product['stock'] == 0): ?>
                                    <span class="badge badge-red">Habis</span>
                                <?php elseif ($product['stock'] <= 3): ?>
                                    <span class="badge badge-yellow">Hampir Habis</span>
                                <?php else: ?>
                                    <span class="badge badge-teal">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <form action="admin_dashboard.php" method="POST" class="stock-form">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="jumlah" min="1" max="100" value="1" class="stock-input">
                                    <button type="submit" name="tambah_stok" class="btn-primary-sm">+ Tambah</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Riwayat Transaksi -->
        <div class="table-card" id="transaksi">
            <div class="table-card-header">
                <h3>🧾 Riwayat Transaksi</h3>
                <span class="badge badge-cyan">Auto-Sync</span>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th><th>Total</th><th>Status</th>
                            <th>Metode</th><th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($transaksi)): ?>
                        <tr class="table-row">
                            <td><span class="order-id"><?php echo htmlspecialchars($row['order_id']); ?></span></td>
                            <td class="td-bold">Rp<?php echo number_format($row['total'], 0, ',', '.'); ?></td>
                            <td><span class="badge badge-teal"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            <td><span class="badge badge-slate"><?php echo strtoupper(htmlspecialchars($row['metode_bayar'])); ?></span></td>
                            <td class="td-muted td-xs"><?php echo htmlspecialchars($row['waktu_bayar']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
</div>
</body>
</html>