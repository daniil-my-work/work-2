<div class="toast-container alert-modal" id="alert-modal">
    <?php foreach ($modalList as $modalItem) : ?>
        <!-- ТОСТ -->
        <?php $category = $modalItem['category'] ? $modalItem['category'] : ''; ?>
        <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" data-set-category="<?= $category; ?>">
            <div class="toast-header">
                <?php if (isset($modalItem['title'])) : ?>
                    <?= $modalItem['title']; ?>
                <?php endif; ?>


                <?php if (isset($modalItem['category']) && $modalItem['category'] === 'error') : ?>
                    <button type="button" class="btn-close me-0 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                <?php endif; ?>
            </div>

            <div class="toast-body">
                <div class="mt-0 pt-0">
                    <?php if (isset($modalItem['error'])) : ?>
                        <?= $modalItem['error']; ?>
                    <?php endif; ?>

                    <?php if (isset($modalItem['button'])) : ?>
                        <?php foreach ($modalItem['button'] as $modalButtonItem) : ?>
                            <button type="button" class="<?= $modalButtonItem['class']; ?> me-2"><?= $modalButtonItem['text']; ?></button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>