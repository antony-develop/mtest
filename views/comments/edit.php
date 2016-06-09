<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="col-md-12 add-comment-form" role="form">
    <form action="/comments/<?= $commentId ?>" method="POST">
        
        <?php if (isset($result)): ?>
        <p class="text-success">Комментарий изменен успешно</p>
        <?php else: ?>
            <?php if (isset($errors) && is_array($errors)): ?>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="checkbox">
            <label>
                <input type="checkbox" name="accepted" <?= $commentsItem['accepted'] ? 'checked' : '' ?> >Принять<Br>
            </label>
        </div>
        <div class="form-group">
            <label for="username">Имя:</label>
            <input class="username form-control" name="name" placeholder="Имя" value="<?php echo $commentsItem['name'];?>"/>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input class="email form-control" name="email" placeholder="email" value="<?php echo $commentsItem['email'];?>"/>
        </div>
        <div class="form-group">
            <label for="body">Текст:</label>
            <textarea class="body form-control" name="text" placeholder="Комментарий"><?php echo $commentsItem['text'];?></textarea>
        </div>
        <?php if (!empty($comment['image'])): ?>
            <div class="form-group image">
                <img src='/media/upload/<?= $comment['image']; ?>'/>
            </div>
        <?php endif; ?>

        <a href="/comments" class="btn">Отменить</a>
        <button type="submit" name="submit" class="btn btn-default">Отправить</button>
    </form>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>
