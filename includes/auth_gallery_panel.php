<?php
if(empty($gallery_images)) return;
?>
<div class="auth-gallery-panel">
    <div class="auth-gallery-header">
        <h3 class="auth-gallery-title"><?php echo $site_name ?></h3>
        <p class="auth-gallery-subtitle">Discover our campus</p>
    </div>
    <div class="auth-gallery-stage">
        <?php foreach($gallery_images as $i => $img): ?>
        <div class="auth-gallery-slide<?php echo $i === 0 ? ' active' : '' ?>" data-index="<?php echo $i ?>">
            <img src="assets/img/<?php echo htmlspecialchars($img) ?>" alt="School photo <?php echo $i + 1 ?>">
        </div>
        <?php endforeach; ?>
    </div>
    <?php if(count($gallery_images) > 1): ?>
    <div class="auth-gallery-dots">
        <?php foreach($gallery_images as $i => $img): ?>
        <button type="button" class="auth-gallery-dot<?php echo $i === 0 ? ' active' : '' ?>" data-index="<?php echo $i ?>" aria-label="Photo <?php echo $i + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
