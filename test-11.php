<!-- Навигация для таблицы заказов -->
<?php if (count($pagination) > 1) : ?>
                        <nav class="menu__nav account__orders-nav">
                            <?php $prevPageNumber = $currentPage - 1; ?>
                            <?php $nextPageNumber = $currentPage + 1; ?>

                            <?php if ($currentPage > 1) : ?>
                                <a href="./account.php?page=<?= $prevPageNumber; ?>" class="menu__nav-button menu__nav-button--prev">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>

                            <ul class="menu__nav-list">
                                <?php if ($currentPage == count($pagination) && $prevPageNumber != 1) : ?>
                                    <a href="./account.php?page=<?= $prevPageNumber - 1; ?>" class="menu__nav-item">
                                        <?= $prevPageNumber - 1; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumber != 0) : ?>
                                    <a href="./account.php?page=<?= $prevPageNumber; ?>" class="menu__nav-item">
                                        <?= $prevPageNumber; ?>
                                    </a>
                                <?php endif; ?>

                                <p class="menu__nav-item menu__nav-item--current">
                                    <?= $currentPage; ?>
                                </p>

                                <?php if ($currentPage != count($pagination)) : ?>
                                    <a href="./account.php?page=<?= $nextPageNumber; ?>" class="menu__nav-item">
                                        <?= $nextPageNumber; ?>
                                    </a>
                                <?php endif; ?>

                                <?php if ($prevPageNumber == 0 && $nextPageNumber != count($pagination)) : ?>
                                    <a href="./account.php?page=<?= $nextPageNumber + 1; ?>" class="menu__nav-item">
                                        <?= $nextPageNumber + 1; ?>
                                    </a>
                                <?php endif; ?>
                            </ul>

                            <?php if ($currentPage != count($pagination)) : ?>
                                <a href="./account.php?page=<?= $nextPageNumber; ?>" class="menu__nav-button menu__nav-button--next">
                                    <svg class="menu__nav-button-img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64">
                                        <path stroke="currentColor" stroke-width="2px" stroke-linecap="square" d="m26.1 46-2-2 11.8-11.7-11.8-11.7 2-2 13.8 13.7L26.1 46" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>