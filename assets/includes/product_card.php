<div class="product" <?php if ($is_course == false) { echo ('style="height: 50%"');} ?>>
    <?php if ($is_course == true) : ?>
        <div style="width: 100%; height: 50%">
            <a href="#"><img src="<?=$config['uploads'].$product['image'] ?>" alt="<?= $product['name'] ?>" class="product_image"></a>
        </div>
    <?php endif; ?>

    <div class="product_info">
        <a class="product_name" href="#"><?= $product['name'] ?> </a>

        <div class="product_price">
            <?= $product['price'] . ' ' . $price_name?>
        </div>
        <div class="product_buttons">
            <?php
//                $type = false;
                ($is_course == false) ? $type = 'consultations' : $type = 'courses';
            ?>

            <div class="product_button card-btn del-from-cart" data-type="<?=$type?>" data-id="<?= $product['id'] ?>">−</div>
            <div class="product_count" id="count-<?=$product['id']?>" style="font-weight: normal;"><?= $_SESSION['cart'][$product['id']]['count'] ?? 0 ?></div>
            <div class="product_button card-btn add-to-cart" data-type="<?=$type?>" data-id="<?= $product['id'] ?>">+</div>
            <a class="product_button" href="/assets/pages/cart.php">В корзину</a>
        </div>

    </div>
</div>
