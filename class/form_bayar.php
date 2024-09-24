<?php
class Pembayaran {
    private $totalBayar;
    private $diskon;

    public function __construct($totalBayar, $diskon) {
        $this->totalBayar = $totalBayar;
        $this->diskon = $diskon;
    }

    public function hitungTotalBersih() {
        $diskonAmount = $this->totalBayar * ($this->diskon / 100);
        return max(0, $this->totalBayar - $diskonAmount);
    }

    public function isDiskonValid() {
        return $this->diskon <= 100;
    }
}

$totalBersih = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $totalBayar = floatval($_POST['totalBayar']);
    $diskon = floatval($_POST['diskon']);
    
    $pembayaran = new Pembayaran($totalBayar, $diskon);
    
    if (!$pembayaran->isDiskonValid()) {
        $error = "Peringatan: Diskon tidak boleh lebih besar dari 100%!";
    } else {
        $totalBersih = $pembayaran->hitungTotalBersih();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hitung Total Pembayaran</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 350px;
            transition: all 0.3s ease;
        }
        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        .input-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
        }
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
            box-sizing: border-box;
        }
        input[type="number"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }
        button {
            background-color: #2ecc71;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.1s;
        }
        button:hover {
            background-color: #27ae60;
        }
        button:active {
            transform: scale(0.98);
        }
        .result, .error {
            margin-top: 25px;
            font-size: 20px;
            text-align: center;
            font-weight: 600;
            padding: 15px;
            border-radius: 6px;
        }
        .result {
            background-color: #e8f4fd;
            color: #2980b9;
        }
        .error {
            background-color: #fde8e8;
            color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Hitung Total Pembayaran</h1>
        <form method="post">
            <div class="input-group">
                <label for="totalBayar">Total Bayar (Rp):</label>
                <input type="number" id="totalBayar" name="totalBayar" required min="0" step="0.01">
            </div>
            
            <div class="input-group">
                <label for="diskon">Diskon (%):</label>
                <input type="number" id="diskon" name="diskon" required min="0" max="100" step="0.01">
            </div>
            
            <button type="submit">Hitung Total Bersih</button>
        </form>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php elseif ($totalBersih !== null): ?>
            <div class="result">
                Total Bersih Pembayaran:<br>Rp <?php echo number_format($totalBersih, 2, ',', '.'); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>