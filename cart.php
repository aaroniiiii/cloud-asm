<?php
require 'base.php';
include 'head.php';



if (is_post() && isset($_POST['update_cart'])) {
    $id = $_POST['product_id'];
    $qty = (int)$_POST['qty'];
    if ($qty < 1) $qty = 1;
    $_SESSION['cart'][$id] = $qty;
    header("Location: " . $_SERVER['REQUEST_URI']);

    temp('info', 'Record Updated');
    exit;
}

// Remove item from cart if clicked
if (is_post() && isset($_POST['remove_id'])) {
    $rid = $_POST['remove_id'];
    unset($_SESSION['cart'][$rid]);
    header("Location: " . $_SERVER['REQUEST_URI']);
    temp('info', 'Record Removed');
    exit;
}
?>

<div class="container">

  <!-- LEFT MENU (same as index.php) -->
  <div class="sidebar">
    <div class="logo-card">
      <div class="logo-icon">☕</div>
      <h1>HardRock Cafe</h1>
    </div>

    <a href="index.php" class="menu-btn" >☕ Drink Menu</a>
    <a href="cart.php" class="menu-btn active">🛒 My Cart</a>
    <a href="/order/history.php" class="menu-btn">🧾 My Order</a>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="content">

    <h1 style="color: white;">🛒 My Cart</h1>
    <hr>

    <?php if (empty($_SESSION['cart'])): ?>
        <p style="color: white;">Your cart is empty.</p>

    <?php else: ?>
        <?php
        $total = 0;
        foreach ($_SESSION['cart'] as $id => $qty):
            $p = $_db->query("SELECT * FROM product WHERE id = '$id'")->fetch();
            $subtotal = $p->price * $qty;
            $total += $subtotal;
        ?>
            <div class="menu-item">
                <img src="product_photo/<?= $p->photo ?>">
                <div class="item-info">
                    <h2><?= $p->name ?> 
                    <span class="price-tag">RM<?= number_format($p->price,2) ?></span>
                    </h2>
                    <!-- Editable quantity -->
                    <form method="post" class="qty-form">
                        <input type="hidden" name="product_id" value="<?= $id ?>">
                        <div style="display:flex; align-items:center; gap:10px; margin-top:5px;">
                            <button type="button" class="qty-minus qty-btn">-</button>
                            <input type="number" name="qty" value="<?= $qty ?>" min="1" max="100" style="width:50px;">
                            <button type="button" class="qty-plus qty-btn">+</button>
                            <button type="submit" name="update_cart" class="a-btn">Update</button>
                        </div>
                    </form>

                    <p style="margin-top:8px;">Subtotal: RM<?= number_format($subtotal,2) ?></p>

                </div>

                <!-- Optional remove button -->
                <form method="post" style="margin-top:10px;">
                    <input type="hidden" name="remove_id" value="<?= $id ?>">
                    <button class="a-btn" type="submit">Remove</button>
                </form>
            </div>

            
        <?php endforeach; ?>

    <?php endif; ?>

    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="cart-footer">
            <span class="cart-total">TOTAL: RM<?= number_format($total,2) ?></span>
            <form action="../order/checkout.php" method="post" style="margin:0;">
                <button type="submit" class="a-btn">Checkout</button>
            </form>
        </div>
    <?php endif; ?>

  </div>
</div>

<script>
    document.querySelectorAll('.menu-item').forEach(item => {
    let qtyInput = item.querySelector('input[name="qty"]');
    let btnPlus = item.querySelector('.qty-plus');
    let btnMinus = item.querySelector('.qty-minus');

    btnPlus.addEventListener('click', () => {
        let current = parseInt(qtyInput.value, 10) || 1;
        if (current < 100) qtyInput.value = current + 1;
    });

    btnMinus.addEventListener('click', () => {
        let current = parseInt(qtyInput.value, 10) || 1;
        if (current > 1) qtyInput.value = current - 1;
    });
});
</script>

<?php

include 'foot.php';
?>
