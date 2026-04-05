<?php
require_once "../../middleware/auth.php";
require_once __DIR__ . "/../../config/database.php";

checkRole('user');

if(!isset($_SESSION['id'])){
    header("Location: ../../login.php");
    exit;
}

$user_id = $_SESSION['id'];

/* ================= UPDATE LOGIC ================= */
if(isset($_GET['aksi']) && isset($_GET['id'])){
    $keranjang_id = intval($_GET['id']);

    if($_GET['aksi'] == "tambah"){
        mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah + 1 WHERE id='$keranjang_id' AND user_id='$user_id'");
    }

    if($_GET['aksi'] == "kurang"){
        $cek = mysqli_query($conn, "SELECT jumlah FROM keranjang WHERE id='$keranjang_id' AND user_id='$user_id'");
        $data = mysqli_fetch_assoc($cek);

        if($data && $data['jumlah'] > 1){
            mysqli_query($conn, "UPDATE keranjang SET jumlah = jumlah - 1 WHERE id='$keranjang_id' AND user_id='$user_id'");
        }
    }

    if($_GET['aksi'] == "hapus"){
        mysqli_query($conn, "DELETE FROM keranjang WHERE id='$keranjang_id' AND user_id='$user_id'");
    }

    header("Location: keranjang.php");
    exit;
}

/* ================= AMBIL DATA ================= */
$query = mysqli_query($conn, "
    SELECT 
        keranjang.id AS keranjang_id,
        keranjang.jumlah,
        keranjang.produk_id,
        produk.nama,
        produk.harga,
        produk.gambar
    FROM keranjang
    INNER JOIN produk ON keranjang.produk_id = produk.id
    WHERE keranjang.user_id = '$user_id'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Keranjang - OXSAL STORE</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins',sans-serif;}
body { background:#f6f0e3; color:#3b2e2c; }

/* Header */
header { background:#7b5e3c; padding:15px 40px; display:flex; align-items:center; }
.logo { display:flex; align-items:center; gap:12px; color:#fff; font-weight:700; font-size:20px; }
.logo img { width:50px; border-radius:50%; }

/* Container */
.container { padding:40px 40px; max-width:1000px; margin:auto; }

/* Back Button */
.back-btn { display:inline-block; margin-bottom:20px; padding:10px 20px; background:#a38260; border-radius:10px; color:white; text-decoration:none; transition:0.3s; }
.back-btn:hover { background:#7b5e3c; }

/* Title */
h1 { margin-bottom:25px; color:#5a4031; }

/* Cart Items */
.cart-box { display:flex; flex-direction:column; gap:15px; }
.cart-item { display:flex; align-items:center; justify-content:space-between; background:#fff8f0; padding:15px; border-radius:15px; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
.cart-item .left { display:flex; align-items:center; gap:15px; }
.cart-item img { width:70px; height:70px; object-fit:cover; border-radius:10px; }
.cart-item .info { display:flex; flex-direction:column; gap:5px; }
.cart-item .info .nama { font-weight:600; }
.cart-item .info .harga { color:#7b5e3c; font-weight:500; }

/* Quantity */
.qty-box { display:flex; gap:10px; align-items:center; }
.qty-btn { background:#d9b58c; color:#3b2e2c; padding:5px 10px; border-radius:6px; text-decoration:none; font-weight:600; transition:0.2s; }
.qty-btn:hover { background:#c49b73; }
.subtotal { font-weight:600; color:#5a4031; }

/* Delete */
.delete-btn { color:red; text-decoration:none; font-weight:500; transition:0.2s; }
.delete-btn:hover { text-decoration:underline; }

/* Checkbox */
.checkbox { transform:scale(1.3); cursor:pointer; margin-right:10px; }

/* Total Box */
.total-wrapper { display:flex; justify-content:flex-end; margin-top:30px; }
.total-box { background:#fff8f0; padding:25px; border-radius:20px; width:320px; box-shadow:0 5px 15px rgba(0,0,0,0.05); }
.total-box h3 { margin-bottom:15px; color:#5a4031; }

/* Checkout Button */
.checkout-btn { display:block; width:100%; background:#7b5e3c; color:white; padding:12px; border-radius:10px; border:none; cursor:pointer; font-weight:600; transition:0.3s; }
.checkout-btn:hover { background:#5a4031; }

</style>
</head>
<body>

<header>
    <div class="logo">
        <img src="../../assets/oxsal.png" class="logo-img">
        OXSAL STORE
    </div>
</header>

<div class="container">

<a href="dashboard.php" class="back-btn">← Kembali</a>

<h1>Keranjang Belanja</h1>

<form action="checkout.php" method="POST" onsubmit="return validasiCheckout()">

<div class="cart-box">

<?php if(mysqli_num_rows($query) > 0): ?>
<?php while($item = mysqli_fetch_assoc($query)): 
$subtotal = $item['harga'] * $item['jumlah'];
?>
<div class="cart-item">
    <div class="left">
        <input type="checkbox" name="pilih[]" value="<?= $item['keranjang_id']; ?>" onclick="hitungTotal()" class="checkbox">
        <img src="../../uploads/<?= $item['gambar']; ?>">
        <div class="info">
            <span class="nama"><?= $item['nama']; ?></span>
            <span class="harga">Rp <?= number_format($item['harga'],0,',','.'); ?></span>
        </div>
    </div>
    <div class="right">
        <div class="qty-box">
            <a class="qty-btn" href="?aksi=kurang&id=<?= $item['keranjang_id']; ?>">-</a>
            <span><?= $item['jumlah']; ?></span>
            <a class="qty-btn" href="?aksi=tambah&id=<?= $item['keranjang_id']; ?>">+</a>
        </div>
        <div class="subtotal">Rp <?= number_format($subtotal,0,',','.'); ?></div>
        <a class="delete-btn" href="?aksi=hapus&id=<?= $item['keranjang_id']; ?>">Hapus</a>
    </div>
</div>
<?php endwhile; ?>
<?php else: ?>
<p style="padding:30px;text-align:center;">Keranjang kosong</p>
<?php endif; ?>

</div>

<div class="total-wrapper">
<div class="total-box">
<h3>Total: Rp <span id="total">0</span></h3>
<button type="submit" class="checkout-btn">Checkout</button>
</div>
</div>

</form>
</div>

<script>
function hitungTotal(){
    let total = 0;
    let checkboxes = document.querySelectorAll("input[name='pilih[]']:checked");
    checkboxes.forEach(cb => {
        let row = cb.closest(".cart-item");
        let subtotal = row.querySelector(".subtotal").innerText.replace(/[Rp\s\.]/g,'');
        total += parseInt(subtotal);
    });
    document.getElementById("total").innerText = total.toLocaleString('id-ID');
}

function validasiCheckout(){
    let checked = document.querySelectorAll("input[name='pilih[]']:checked");
    if(checked.length === 0){
        alert("⚠️ Pilih minimal 1 produk dulu!");
        return false;
    }
    return true;
}
</script>

</body>
</html>