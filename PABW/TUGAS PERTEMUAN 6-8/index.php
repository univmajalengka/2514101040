
<?php

include "database.php";

// Fungsi untuk menghitung diskon
function hitungDiskon($totalBelanja) {
    $diskon = 0;

    if ($totalBelanja >= 100000) {
        $diskon = 0.10 * $totalBelanja;
    } elseif ($totalBelanja >= 50000) {
        $diskon = 0.05 * $totalBelanja;
    }

    return $diskon;
}

// Variabel awal
$totalBelanja = "";
$diskon = 0;
$totalBayar = 0;
$persentase = 0;
$hasil = false;
$error = "";

// Memproses input dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $input = $_POST["totalBelanja"] ?? "";

    // Menghapus titik pemisah ribuan
    $input = str_replace('.', '', $input);

    if ($input === "" || !is_numeric($input) || $input < 0) {

        $error = "Masukkan nominal belanja yang valid!";

    } else {

        $totalBelanja = (float) $input;

        // Memanggil fungsi hitungDiskon()
        $diskon = hitungDiskon($totalBelanja);

        // Menentukan persentase diskon
        if ($totalBelanja >= 100000) {
            $persentase = 10;
        } elseif ($totalBelanja >= 50000) {
            $persentase = 5;
        }

        // Menghitung total pembayaran
        $totalBayar = $totalBelanja - $diskon;

        // Menyimpan hasil perhitungan ke database
        $stmt = $conn->prepare(
            "INSERT INTO riwayat_diskon
            (total_belanja, diskon, persentase, total_bayar)
            VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {

            $stmt->bind_param(
                "ddid",
                $totalBelanja,
                $diskon,
                $persentase,
                $totalBayar
            );

            if ($stmt->execute()) {
                $hasil = true;
            } else {
                $error = "Gagal menyimpan data: " . $stmt->error;
            }

            $stmt->close();

        } else {
            $error = "Gagal menyiapkan query: " . $conn->error;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kalkulator Diskon</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<div class="container">

    <header class="topbar">
        <span>PROGRAM WEB</span>
        <span>PROGRAM PERHITUNGAN BELANJA</span>
    </header>

    <main class="main-content">

        <section class="information">

            <p class="eyebrow">SMART SHOPPING TOOL</p>

            <h1>Belanja lebih hemat.</h1>

            <p class="description">
                Hitung potongan harga dan ketahui jumlah pembayaran
                setelah mendapatkan diskon.
            </p>

            <div class="discount-info">

                <p class="section-label">KETENTUAN DISKON</p>

                <div class="discount-row">
                    <span>Belanja ≥ Rp100.000</span>
                    <strong>10%</strong>
                </div>

                <div class="discount-row">
                    <span>Belanja ≥ Rp50.000</span>
                    <strong>5%</strong>
                </div>

                <div class="discount-row">
                    <span>Belanja di bawah Rp50.000</span>
                    <strong>0%</strong>
                </div>

            </div>

            <p class="note">
                Masukkan nominal belanja untuk mulai menghitung.
            </p>

        </section>

        <section class="calculator">

            <div class="form-heading">
                <span>01 — INPUT DATA</span>
                <h2>Hitung diskon</h2>
            </div>

            <form method="POST" action="">

                <label for="totalBelanja">Total belanja</label>

                <div class="input-group">

                    <span>Rp</span>

                    <input
                        type="text"
                        id="totalBelanja"
                        name="totalBelanja"
                        placeholder="0"
                        inputmode="numeric"
                        value="<?= $totalBelanja !== "" ? number_format((float)$totalBelanja, 0, ',', '.') : '' ?>"
                        oninput="formatAngka(this)"
                        required
                    >

                </div>

                <button type="submit">
                    Hitung sekarang <span>→</span>
                </button>

            </form>

            <?php if ($error != ""): ?>

                <div class="error">
                    <?= htmlspecialchars($error) ?>
                </div>

            <?php endif; ?>

            <?php if ($hasil): ?>

                <div class="result">

                    <div class="result-heading">
                        <span>02 — HASIL PERHITUNGAN</span>
                        <span class="status">SELESAI</span>
                    </div>

                    <div class="receipt-row">
                        <span>Total belanja</span>
                        <strong>
                            Rp <?= number_format($totalBelanja, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <div class="receipt-row">
                        <span>Diskon (<?= $persentase ?>%)</span>
                        <strong class="discount">
                            - Rp <?= number_format($diskon, 0, ',', '.') ?>
                        </strong>
                    </div>

                    <div class="payment">
                        <span>Total pembayaran</span>
                        <strong>
                            Rp <?= number_format($totalBayar, 0, ',', '.') ?>
                        </strong>
                    </div>

                </div>

            <?php endif; ?>

        </section>

    </main>

    <footer>
        <span>KALKULATOR DISKON BELANJA</span>
        <span>© 2026 | Intania Cantika A - 2514101040</span>
    </footer>

</div>

<script>
    // Fungsi untuk format angka
    function formatAngka(input) {

        let angka = input.value.replace(/[^0-9]/g, '');

        if (angka !== '') {
            input.value = Number(angka).toLocaleString('id-ID');
        } else {
            input.value = '';
        }

    }
</script>

</body>
</html>
