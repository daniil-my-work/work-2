<?php foreach ($pagination as $paginationItem) : ?>
                            <?php $isActive = $paginationItem == $currentPage ? 'menu__nav-item--current' : ''; ?>
                            <?php if ($isActive == 'menu__nav-item--current') : ?>
                                <p class="menu__nav-item <?= $isActive; ?>">
                                    <?= $paginationItem; ?>
                                </p>
                            <?php else : ?>
                                <a href="./account.php?page=<?= $paginationItem; ?>" class="menu__nav-item">
                                    <?= $paginationItem; ?>
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>