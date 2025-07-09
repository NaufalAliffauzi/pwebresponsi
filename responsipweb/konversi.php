<?php
// File: konversi.php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $suhu = isset($_POST['Suhu']) ? floatval($_POST['Suhu']) : 0;
    $pilihan = isset($_POST['pilihan']) ? $_POST['pilihan'] : 'celcius';
    $hasil = isset($_POST['suhu']) ? $_POST['suhu'] : 'celcius';
    
    // Fungsi konversi suhu
    function konversiSuhu($nilai, $dari, $ke) {
        if ($dari === $ke) return $nilai;
        
        // Konversi dari Celcius
        if ($dari === 'celcius') {
            if ($ke === 'fahrenheit') return ($nilai * 9/5) + 32;
            if ($ke === 'kelvin') return $nilai + 273.15;
            if ($ke === 'reamur') return $nilai * 4/5;
        }
        
        // Konversi dari Fahrenheit
        if ($dari === 'fahrenheit') {
            if ($ke === 'celcius') return ($nilai - 32) * 5/9;
            if ($ke === 'kelvin') return ($nilai - 32) * 5/9 + 273.15;
            if ($ke === 'reamur') return ($nilai - 32) * 4/9;
        }
        
        // Konversi dari Kelvin
        if ($dari === 'kelvin') {
            if ($ke === 'celcius') return $nilai - 273.15;
            if ($ke === 'fahrenheit') return ($nilai - 273.15) * 9/5 + 32;
            if ($ke === 'reamur') return ($nilai - 273.15) * 4/5;
        }
        
        // Konversi dari Reamur
        if ($dari === 'reamur') {
            if ($ke === 'celcius') return $nilai * 5/4;
            if ($ke === 'fahrenheit') return ($nilai * 9/4) + 32;
            if ($ke === 'kelvin') return ($nilai * 5/4) + 273.15;
        }
        
        return $nilai;
    }
    
    // Lakukan konversi
    $hasilKonversi = konversiSuhu($suhu, $pilihan, $hasil);
    
    // Format output
    $hasilFormatted = number_format($hasilKonversi, 2);
    $satuan = ucfirst($hasil);
    
    // Kembalikan hasil sebagai JSON
    header('Content-Type: application/json');
    echo json_encode([
        'hasil' => $hasilFormatted,
        'satuan' => $satuan,
        'pesan' => "Hasil konversi: $hasilFormatted $satuan"
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konversi Suhu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Konversi Suhu</h2>
        <form id="tempForm" method="post" action="konversi.php">
            <label for="celsius">Suhu</label>
            <input type="number" id="Suhu" name="Suhu" placeholder="Masukkan nilai Suhu" required>

            <label for="Pilihan">Pilihan</label>
            <select name="pilihan" id="pilihan">
                <option value="celcius">Celcius</option>
                <option value="fahrenheit">Fahrenheit</option>
                <option value="kelvin">Kelvin</option>
                <option value="reamur">Reamur</option>
            </select>

            <label for="ke">Ke :</label>
            <input type="radio" id="celcius" name="suhu" value="celcius" checked>
            <label for="celcius">Celcius (°C)</label><br>
            
            <input type="radio" id="fahrenheit" name="suhu" value="fahrenheit">
            <label for="fahrenheit">Fahrenheit (°F)</label><br>
            
            <input type="radio" id="kelvin" name="suhu" value="kelvin">
            <label for="kelvin">Kelvin (K)</label><br>
            
            <input type="radio" id="reamur" name="suhu" value="reamur">
            <label for="reamur">Reamur (°Ré)</label>

            <button type="submit">Konversi</button>
        </form>

        <p id="result"></p>
    </div>

    <script>
    // Fungsi untuk menangani submit form dengan AJAX
    document.getElementById('tempForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('result').textContent = data.pesan;
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('result').textContent = 'Terjadi kesalahan saat konversi';
        });
    });
    </script>
</body>
</html>