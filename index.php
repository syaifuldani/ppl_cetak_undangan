<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promosi Undangan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: Arial, sans-serif;
    }

    .promotion-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 50px;
    }

    .promotion-text {
        max-width: 40%;
    }

    .promotion-text h2 {
        font-size: 2rem;
        margin-bottom: 20px;
    }

    .promotion-text a {
        color: #00A2FF;
        text-decoration: none;
        font-size: 1.2rem;
    }

    .promotion-text a:hover {
        text-decoration: underline;
    }

    .promotion-images {
        display: flex;
        gap: 10px;
        max-width: 50%;
    }

    .promotion-images img {
        width: 100%;
        border-radius: 10px;
    }
    </style>
</head>

<body>

    <div class="container">
        <div class="promotion-section">
            <div class="promotion-images">
                <div class="col">
                    <img src="/mnt/data/PROMOTION.png" alt="Undangan 1">
                </div>
                <div class="col">
                    <img src="/mnt/data/PROMOTION.png" alt="Undangan 2">
                </div>
                <div class="col">
                    <img src="/mnt/data/PROMOTION.png" alt="Undangan 3">
                </div>
            </div>
            <div class="promotion-text">
                <h2>Buat Momen Spesial Lebih Berkesan dengan Undangan yang Elegan</h2>
                <a href="#">Lanjut ➜</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>