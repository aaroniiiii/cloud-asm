<?php
include '../base.php';
include '../head.php';


// Get order ID from URL
$id = $_GET['id'] ?? 0;
if (!$id) {
    echo "<script>alert('Order not found'); window.location='../index.php';</script>";
    exit;
}

// Fetch order
$order = $_db->query("SELECT * FROM `order` WHERE id='$id'")->fetch();
if (!$order) {
    echo "<script>alert('Order not found'); window.location='../index.php';</script>";
    exit;
}

// Fetch order items and product details
$items = $_db->query("
    SELECT od.*, p.name, p.photo
    FROM order_detail od
    JOIN product p ON od.product_id = p.id
    WHERE od.order_id = '$id'
")->fetchAll();


?>


<div class="container-p">

  <!-- LEFT SIDEBAR -->
  <div class="sidebar">
    <div class="logo-card">
      <div class="logo-icon">☕</div>
      <h1>Chaagee Cafee</h1>
    </div>

    <a href="../index.php" class="menu-btn" >☕ Drink Menu</a>
    <a href="../cart.php" class="menu-btn">🛒 My Cart</a>
    <a href="/order/history.php" class="menu-btn active">🧾 My Order</a>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="content">
    <h1>Order Receipt</h1>
    <hr>

    <div class="order-card">
      <p><strong>Order ID:</strong> <?= $order->id ?></p>
      <p><strong>Date:</strong> <?= $order->date ?></p>
      <p><strong>Name:</strong> <?= htmlspecialchars($order->customer_name) ?></p>
      <p><strong>Email:</strong> <?= htmlspecialchars($order->customer_email) ?></p>
    </div>

    <h2>Items</h2>

    <?php foreach ($items as $item): ?>
    <div class="menu-item">
      <img src="../product_photo/<?= $item->photo ?>" alt="<?= $item->name ?>">
      <div class="item-info">
        <h2><?= $item->name ?> </h2>
        <p>Quantity: <?= $item->unit ?></p>
        <p>Unit Price: RM<?= number_format($item->price,2) ?></p>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="checkout-summary">
      <h2>Total: RM<?= number_format($order->total_price,2) ?></h2>
      <a href="../index.php" class="a-btn">Back to Home</a>
    </div>

  </div>
</div>
