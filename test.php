<?php
// Get product data
$productId = 123;
$ratingBreakdown = getRatingBreakdown($productId);

// Get average rating
$avgQuery = "
    SELECT 
        ROUND(AVG(rating), 1) as avg_rating,
        COUNT(*) as total_reviews
    FROM reviews 
    WHERE product_id = ?
";
$stmt = $pdo->prepare($avgQuery);
$stmt->execute([$productId]);
$avgData = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="rating-summary">
    <div class="rating-overview">
        <div class="avg-rating">
            <h1><?= $avgData['avg_rating'] ?></h1>
            <div class="stars">
                <?php 
                $fullStars = floor($avgData['avg_rating']);
                $halfStar = ($avgData['avg_rating'] - $fullStars) >= 0.5;
                
                for ($i = 0; $i < $fullStars; $i++) echo '★';
                if ($halfStar) echo '⯨';
                for ($i = 0; $i < (5 - ceil($avgData['avg_rating'])); $i++) echo '☆';
                ?>
            </div>
            <p>dari <?= $avgData['total_reviews'] ?> ulasan</p>
        </div>
    </div>

    <div class="rating-breakdown">
        <?php foreach ($ratingBreakdown as $data): ?>
        <div class="rating-row">
            <span class="rating-label"><?= $data['rating'] ?> ★</span>
            <div class="progress-bar">
                <div class="progress-fill" 
                     style="width: <?= $data['percentage'] ?>%"
                     data-percentage="<?= $data['percentage'] ?>">
                </div>
            </div>
            <span class="rating-percentage"><?= $data['percentage'] ?>%</span>
            <span class="rating-count">(<?= $data['count'] ?>)</span>
        </div>
        <?php endforeach; ?>
    </div>
</div>