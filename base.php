<?php
session_start();
// Is GET request?
function is_get() {
    return $_SERVER['REQUEST_METHOD'] == 'GET';
}
// Is POST request?
function is_post() {
    return $_SERVER['REQUEST_METHOD'] == 'POST';
}
// Obtain GET parameter
function get($key, $value = null) {
    $value = $_GET[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}
// Obtain POST parameter
function post($key, $value = null) {
    $value = $_POST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}
// Obtain REQUEST (GET and POST) parameter
function req($key, $value = null) {
    $value = $_REQUEST[$key] ?? $value;
    return is_array($value) ? array_map('trim', $value) : trim($value);
}
// Redirect to URL
function redirect($url = null) {
    $url ??= $_SERVER['REQUEST_URI'];
    header("Location: $url");
    exit();
}
// Set or get temporary session variable
function temp($key, $value = null) {
    if ($value !== null) {
        $_SESSION["temp_$key"] = $value;
    }
    else {
        $value = $_SESSION["temp_$key"] ?? null;
        unset($_SESSION["temp_$key"]);
        return $value;
    }
}
// Validation is email?
function is_email($value) {
    return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
}
function encode($value) {
    return htmlentities($value);
}
function html_name($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' placeholder='Enter name' id='$key' name='$key' value='$value' $attr>";
}
function html_email($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='text' placeholder='Email address' id='$key' name='$key' value='$value' $attr>";
}
function html_pass($key, $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='password' placeholder='Password' id='$key' name='$key' value='$value' $attr>";
}
// Generate <input type='number'>
function html_number($key, $min = '', $max = '', $step = '', $attr = '') {
    $value = encode($GLOBALS[$key] ?? '');
    echo "<input type='number' id='$key' name='$key' value='$value' min='$min' max='$max' step='$step' $attr>";
}
function is_money($value) {
    return preg_match('/^\-?\d+(\.\d{1,2})?$/', $value);
}
// Generate <input type='hidden'>
function html_hidden($key, $value = '', $attr = '') {
    $value = encode($value);
    echo "<input type='hidden' id='$key' name='$key' value='$value' $attr>";
}
function html_radios($key, $items, $br = false) {
    $value = encode($GLOBALS[$key] ?? '');
    echo '<div>';
    foreach ($items as $id => $text) {
        $state = $id == $value ? 'checked' : '';
        echo "<label><input type='radio' id='{$key}_$id' name='$key' value='$$id' $state>$text</label>";
        if ($br) {
            echo '<br>';
        }
    }
    echo '</div>';
}
// Generate <input type='file'>
function html_file($key, $accept = '', $attr = '') {
    echo "<input type='file' id='$key' name='$key' accept='$accept' $attr>";
}
function save_photo($f, $folder, $width = 200, $height = 200) {
    $photo = uniqid() . '.jpg';
    require_once 'lib/SimpleImage.php';
    $img = new SimpleImage();
    $img->fromFile($f->tmp_name)
        ->thumbnail($width, $height)
        ->toFile("$folder/$photo", 'image/jpeg');
    return $photo;
}
// Global error array
$_err = [];
// Generate <span class='err'>
function err($key) {
    global $_err;
    if ($_err[$key] ?? false) {
        echo "<span class='err'>$_err[$key]</span>";
    }
    else {
        echo '<span></span>';
    }
}
function get_file($key) {
    $f = $_FILES[$key] ?? null;
   
    if ($f && $f['error'] == 0) {
        return (object)$f;
    }
    return null;
}
// ============================================================================
// Security
// ============================================================================
// Global user object
$_user = $_SESSION['user'] ?? null;
//login user
function login($user, $url = '/') {
    $_SESSION['user'] = $user;
    redirect($url);
}
// Logout user
function logout($url = '/') {
    unset($_SESSION['user']);
    redirect($url);
}
// Authorization
function auth(...$roles) {
    global $_user;
    if ($_user) {
        if ($roles) {
            if (in_array($_user->role, $roles)) {
                return; // OK
            }
        }
        else {
            return; // OK
        }
    }
   
    redirect('/login.php');
}
// ============================================================================
// Shopping Cart
// ============================================================================
// Get shopping cart
function get_cart() {
    return $_SESSION['cart'] ?? [];
}
// Set shopping cart
function set_cart($cart = []) {
    $_SESSION['cart'] = $cart;
}
// Update shopping cart
function update_cart($id, $unit) {
    $cart = get_cart();
    if ($unit >= 1 && $unit <= 10 && is_exists($id, 'product', 'id')) {
        $cart[$id] = $unit;
        ksort($cart);
    }
    else {
        unset($cart[$id]);
    }
    set_cart($cart);
}
// ============================================================================
// Database Setups and Functions
// ============================================================================
// Global PDO object -  Amazon RDS Aurora / RDS MySQL
$_db = new PDO(
    'mysql:host=myasmdb.cgqrtafng5p5.us-east-1.rds.amazonaws.com;port=3306;dbname=chaagee;charset=utf8mb4',
    'cloudasm_user',
    'Admin12345!',
    [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ]
);

// Is unique?
function is_unique($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() == 0;
}
// Is exists?
function is_exists($value, $table, $field) {
    global $_db;
    $stm = $_db->prepare("SELECT COUNT(*) FROM $table WHERE $field = ?");
    $stm->execute([$value]);
    return $stm->fetchColumn() > 0;
}
