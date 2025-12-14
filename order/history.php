<?php
include '../base.php';
include '../head.php';

// Fetch all orders (latest first)
$history = $_db->query("
    SELECT id, date, total_unit, total_price
    FROM `order`
    ORDER BY date DESC
")->fetchAll();
?>

<div class="container">

  <!-- LEFT SIDEBAR -->
  <div class="sidebar">
    <div class="logo-card">
      <div class="logo-icon">☕</div>
      <h1>HardRock Cafe</h1>
    </div>

    <a href="../index.php" class="menu-btn" >☕ Drink Menu</a>
    <a href="../cart.php" class="menu-btn">🛒 My Cart</a>
    <a href="/order/history.php" class="menu-btn active">🧾 My Order</a>

  </div>

  <!-- RIGHT CONTENT -->
  <div class="content">

    <h1 style="color:white;">Order History</h1>
    <hr><br>

    <?php if (!$history): ?>
        <p style="color:black; font-size:18px;">No orders found.</p>
    <?php else: ?>
    
        <?php foreach ($history as $h): ?>
        <a href="detail.php?id=<?= $h->id ?>">
            <div class="detail_box">

                <div class="order_id">
                    Order ID <br>
                    <strong><?= $h->id ?></strong>
                </div>

                <div class="date">
                    Date & Time <br>
                    <strong><?= $h->date ?></strong>
                </div>

                <div class="qty">
                    Total quantity <br>
                    <strong>x <?= $h->total_unit ?></strong>
                </div>

                <div class="pr">
                    Total price <br>
                    <strong>RM<?= number_format($h->total_price, 2) ?></strong>
                </div>

                <div class="dt">Detail</div>

            </div>
        </a>
        <?php endforeach; ?>

    <?php endif; ?>

  </div> <!-- end content -->

</div> <!-- end container -->

<?php include '../foot.php'; ?>
