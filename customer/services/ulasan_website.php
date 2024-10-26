<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan</title>
    <link rel="icon" href="../../resources/img/icons/pleart.png" type="image/png">
    <link rel="stylesheet" href="../../resources/css/ulasan.css">
</head>

<body>
    <div class="review-container">
        <h2>Berikan Ulasan Anda!</h2>
        <form action="submit_review.php" method="POST">
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="1">⭐ 1</option>
                <option value="2">⭐ 2</option>
                <option value="3">⭐ 3</option>
                <option value="4">⭐ 4</option>
                <option value="5">⭐ 5</option>
            </select>

            <label for="comment">Komentar Anda:</label>
            <textarea name="comment" id="comment" rows="4" placeholder="Tulis komentar Anda..." required></textarea>

            <button type="submit">Kirim Ulasan</button>
        </form>
    </div>
    <div class="back-dashboard">
        <a href="../dashboard.php">Kembali ke Dashboard</a>
    </div>
</body>

</html>