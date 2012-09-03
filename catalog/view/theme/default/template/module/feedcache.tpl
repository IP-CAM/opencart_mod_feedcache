<div class="box">
    <div class="box-heading"><?php echo $heading_title; ?></div>
    <div class="box-content">
        <div class="feedcache">
            <?php foreach($feeditems as $item) : ?>
                <a href="<?= $item["link"] ?>" target="_blank"><?= $item["title"] ?></a>
                <span class="date"><?= $item["date_published"] ?></span>
                <p><?= $item["content_snippet"] ?></p>
            <?php endforeach;?>
        </div>
    </div>
</div>