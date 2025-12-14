<?php

require '../base.php';
include '../head.php';

$_err = []; // store errors

if (is_post() && isset($_POST['checkout'])) {

    $name = req('name');
    $email = req('email');

    // NAME VALIDATION
    if (!$name) {
        $_err['name'] = 'Name is required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters allowed';
    }

    // EMAIL VALIDATION
    if (!$email) {
        $_err['email'] = 'Email is required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters allowed';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email format';
    }


    // If no errors → process checkout
    if (!$_err) {

        

     // ------------------------------------------
    // DB transaction (insert order and items)
    // ------------------------------------------

        $_db->beginTransaction();

        // (B) Insert order, keep order id
        $stm = $_db->prepare("
            INSERT INTO `order` (date, customer_name, customer_email, total_unit, total_price)
            VALUES (NOW(), ?, ?, 0, 0)
        ");
        $stm->execute([$name, $email]);

        $id = $_db->lastInsertId();



        $stm = $_db->prepare("
            INSERT INTO order_detail(order_id, product_id, unit, price, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ");

        foreach ($_SESSION['cart'] as $product_id => $qty) {
            $p = $_db->query("SELECT * FROM product WHERE id='$product_id'")->fetch();
            $price = $p->price;
            $subtotal = $price * $qty;

            $stm->execute([$id, $product_id, $qty, $price, $subtotal]);
        }


        // (D) Update order (total_unit and total_price)
        $stm = $_db->prepare('
            UPDATE `order`
            SET total_unit = (SELECT SUM(unit) FROM order_detail WHERE order_id = ?),
                total_price = (SELECT SUM(subtotal) FROM order_detail WHERE order_id = ?)
            WHERE id = ?
        ');
        $stm->execute([$id, $id, $id]);

        // (E) Commit transcation
        $_db->commit(); // if all success, 

        // ------------------------------------------

        // (3) Clear shopping cart
        set_cart();

        // (4) Redirect to detail.php?id=XXX
        temp('info', 'Payment successful✅');
        redirect("detail.php?id=$id");

    }

}

$total = 0;
foreach ($_SESSION['cart'] as $id => $qty) {
    $p = $_db->query("SELECT * FROM product WHERE id='$id'")->fetch();
    $total += $p->price * $qty;
}

?>

<div class="container">

    <?= err(temp('info', 'Invalid Input!')) ?>

  <!-- LEFT SIDEBAR -->
  <div class="sidebar">
    <div class="logo-card">
      <div class="logo-icon">☕</div>
      <h1>HardRock Cafe</h1>
    </div>

    <a href="../index.php" class="menu-btn" >☕ Drink Menu</a>
    <a href="../cart.php" class="menu-btn active">🛒 My Cart</a>
    <a href="/order/history.php" class="menu-btn">🧾 My Order</a>
  </div>

  <!-- RIGHT CONTENT -->
  <div class="content">

    <h1 style="color: white;">Checkout</h1>
    <hr>

    <h2 style="color: white;">Order Summary</h2>
    <?php foreach ($_SESSION['cart'] as $id => $qty):
        $p = $_db->query("SELECT * FROM product WHERE id='$id'")->fetch();
        $subtotal = $p->price * $qty;
    ?>
    <div class="menu-item">
        <img src="../product_photo/<?= $p->photo ?>">
        <div class="item-info">
            <h2><?= $p->name ?> 
                <span class="price-tag">RM<?= number_format($p->price,2) ?></span>
            </h2>
            <p>Quantity: <?= $qty ?></p>
            <p>Subtotal: RM<?= number_format($subtotal,2) ?></p>
        </div>
    </div>
    <?php endforeach; ?>

    <h2 style="margin-top:20px; color:white;">Total: RM<?= number_format($total,2) ?></h2>

    <h2 style="border-bottom: 2px solid black;"></h2>

    <h2 style="color: white;">Guest Details</h2>
    <form method="post">

        <label for="name">Name</label>
        <?= html_name('name', 'maxlength="100"') ?>
        <?= err('name') ?>
        <br>

        <label for="email">Email</label>
        <?= html_email('email', 'maxlength="100"') ?>
        <?= err('email') ?>
        <br>

        <button type="submit" name="checkout" class="s-a-btn" style="padding:12px 25px; font-size:18px;">
            ✅ Complete Purchase
        </button>

    </form>


  </div>
</div>

<?php
include '../foot.php';
