<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promosi Undangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="resources/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="resources/css/promotion.css">
</head>

<body>

    <div class="container">
        <div id="promotion-layout-1" class="promotion-section">
            <div class="promotion-images">
                <div class="row">
                    <div class="col">
                        <img src="resources/img/introduction/image1.png" alt="Undangan 1">
                    </div>
                    <div class="col">
                        <img src="resources/img/introduction/image3.png" alt="Undangan 2">
                    </div>
                </div>
                <div class="col">
                    <img src="resources/img/introduction/image2.png" alt="Undangan 3">
                </div>
            </div>
            <div class="promotion-text">
                <h2>Buat Momen Spesial Lebih Berkesan dengan Undangan yang Elegan</h2>
                <div class="nav-buttons">
                    <a href="javascript:void(0);" onclick="showNextLayout()">Lanjut &#10230;</a>
                </div>
            </div>
        </div>

        <div id="promotion-layout-2" class="promotion-section" style="display: none;">
            <div class="promotion-images">
                <div class="row">
                    <div class="col">
                        <img src="resources/img/introduction/image4.png" alt="Undangan 1">
                    </div>
                    <div class="col">
                        <img src="resources/img/introduction/image6.png" alt="Undangan 2">
                    </div>
                </div>
                <div class="col">
                    <img src="resources/img/introduction/image5.png" alt="Undangan 3">
                </div>
            </div>
            <div class="promotion-text">
                <h2>Cepat, Mudah, dan Personal Ciptakan Undangan yang Menginspirasi</h2>
                <div class="nav-buttons">
                    <a href="javascript:void(0);" onclick="showPreviousLayout()">&#10229; Kembali</a>
                    <a href="customer/dashboard.php" onclick="showNextLayout()">Lanjut &#10230;</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showNextLayout() {
            document.getElementById('promotion-layout-1').style.display = 'none';
            document.getElementById('promotion-layout-2').style.display = 'flex';
        }

        function showPreviousLayout() {
            document.getElementById('promotion-layout-1').style.display = 'flex';
            document.getElementById('promotion-layout-2').style.display = 'none';
        }
    </script>
</body>

</html>