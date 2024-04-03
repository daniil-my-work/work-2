<div class="toast-container alert-modal" id="alert-modal">
    <?php foreach ($modalList as $modalItem) : ?>
        <!-- ТОСТ -->
        <?php $category = $modalItem['category'] ? $modalItem['category'] : ''; ?>
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-set-category="<?= $category; ?>">
            <div class="toast-header">
                <?php if (isset($modalItem['title'])) : ?>
                    <?= $modalItem['title']; ?>
                <?php endif; ?>


                <?php if (isset($modalItem['category']) && $modalItem['category'] === 'error' || $modalItem['category'] === 'link') : ?>
                    <button type="button" class="btn-close me-0 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                <?php endif; ?>
            </div>

            <div class="toast-body">
                <div class="mt-0 pt-0">
                    <?php $categoryName = $modalItem['category']; ?>
                    <?php if ($categoryName === 'city') : ?>
                        <?php foreach ($modalItem['button'] as $modalButtonItem) : ?>
                            <button type="button" class="<?= $modalButtonItem['class']; ?> me-2"><?= $modalButtonItem['text']; ?></button>
                        <?php endforeach; ?>
                    <?php elseif ($categoryName === 'error') : ?>
                        <?= $modalItem['error']; ?>
                    <?php elseif ($categoryName === 'link') : ?>
                        <?php $linkInfo = $modalItem['link']; ?>
                        <a href="<?= $linkInfo['address']; ?>">
                            <?= $linkInfo['linkTitle']; ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>