<?php foreach ($news as $news_item): ?>

<h2><?php echo $news_item['title'] ?></h2>
<div class="main">
    <?php echo $news_item['text'] ?>
</div>

<p><?php echo anchor("/news/" . $news_item['slug'], "View Art"); ?> </p>

<?php endforeach ?>