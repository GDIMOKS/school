<header>
    <div class="header_up">
        <nav>
            <ul>
                <li>
                    <a href="/">
                        <p class="logo">ОНЛАЙН-ШКОЛА</p>
                    </a>

                </li>

                <li>
                    <a href="/assets/pages/cart.php" class="button cart-button">
                        <span>Корзина</span>
                        <span id="cart-num"><?= $_SESSION['cart.count'] ?? 0 ?></span>
                    </a>

                    <?php
                        if (!empty($_SESSION['auth']) && $_SESSION['auth'] == true) {
                            $href = '/assets/pages/cabinet.php';
                        } else {
                            $href = '/assets/pages/signin.php';
                        }
                    ?>

                    <a class="button cabinet_button" href="<?=$href?>">
                        <div class="cabinet_button_text">Личный кабинет</div>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

    <?php
        $categories = get_objects('categories');
    ?>
    <div class="header_down">

        <nav>
            <ul class="my_ul">
                <?php foreach ($categories as $category): ?>
                    <li class="my_li">
                        <a class="header_text" href="/assets/pages/categories.php?category=<?=$category['id']?>"><?= $category['name'] ?></a>
                    </li>
                <?php endforeach; ?>

            </ul>
        </nav>

    </div>



</header>
