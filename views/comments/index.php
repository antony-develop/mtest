<?php include ROOT.'/views/layouts/header.php'; ?>
    
    
    <div class="col-md-12 comments" role="main">
        <div class="row">
            <h1 class="col-md-11">Комментарии</h1>
            <div class="col-md-11 order_by">
                <strong>Отсортировать по:</strong>
                <a href="/comments/?sort=name">Имени</a>
                <span>|</span>
                <a href="/comments/?sort=email">Email</a>
                <span>|</span>
                <a href="/comments/?sort=date">Дате</a>
            </div>
        </div>
        <br>
        <hr>
        <br>
        <?php foreach ($commentsList as $commentsItem):?>
            <?php if (($commentsItem['accepted'] && !Admin::isAdmin()) || (Admin::isAdmin())): ?>
                <div class="comment bs-callout bs-callout-info">
                    <h5>
                        <span class="username"> <?php echo $commentsItem['name'];?> </span>
                        <span>|</span>
                        <span class="email"> <?php echo $commentsItem['email'];?> </span>
                        <span>|</span>
                        <span class="created_at"> <?php echo $commentsItem['date'];?> </span>  
                        
                        <?php if ($commentsItem['changed_by_admin']): ?>
                            <span>|</span>
                            <code class="changed_by_admin">Изменен администратором</code>
                        <?php endif; ?>
                        
                        <?php if (Admin::isAdmin()): ?>
                            <?php if (!$commentsItem['accepted']): ?>
                                <span>|</span>
                                <code>Не принят</code>
                            <?php else: ?>
                                <span>|</span>
                                <code class="text-success">Принят</code>
                            <?php endif; ?>
                                
                            <span>|</span>
                            <span class="edit"><a href="/comments/<?php echo $commentsItem['id'];?>">Редактировать</a></span>
                               
                            <span>|</span>
                            <span><a href="/comments/delete/<?php echo $commentsItem['id'];?>" class="text-danger"><i class="glyphicon glyphicon-trash"></i> Удалить</a></span>
                        <?php endif; ?>
                    </h5>

                    <div class="body bs-example">
                        <?php echo $commentsItem['text'];?>
                    </div>

                    <div class="image bs-example-images">
                        <img class="img-thumbnail"  src='<?php echo $commentsItem["image"]; ?>' />
                    </div>
                </div>
            
                <br>
                <hr>
                <br>
                
            <?php endif; ?>
        <?php endforeach;?> 
        
        <div class="comment preview bs-callout bs-callout-info bg-info" style="display:none">
            <h2>Предварительный просмотр</h2>
            <h5>
                <span class="name"></span>
                <span>|</span>
                <span class="email"></span>
                <span>|</span>
                <span class="date"></span>
            </h5>
            <div class="text">
            </div>
            <div class="preview-image">
                <img id="thumb-preview-image" />                    
            </div>
        </div>
    </div>
    
<?php if (/*!Admin::isAdmin()*/true): ?>
    <div class="col-md-12 add-comment-form" role="form">
        <h2>Добавить комментарий</h2>
        <form action="/comments/" method="POST" enctype="multipart/form-data">
            <div class="col-md-12">
                <?php if (isset($result)): ?>
                    <p class="text-success">Комментарий добавлен успешно</p>
                <?php else: ?>
                    <?php if (isset($errors) && is_array($errors)): ?>
                        <ul>
                            <?php foreach ($errors as $error): ?>
                                <li class="text-danger"><?php echo $error; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                    <hr>
                <?php endif; ?>

            </div>
            <div id="inputs">
                <div class="form-group">
                    <label for="name">Имя:</label>
                    <input class="name form-control" name="name" placeholder="Ваше имя" />
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="email form-control" name="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                    <label for="text">Текст:</label>
                    <textarea class="text form-control" name="text" placeholder="Комментарий"></textarea>
                </div>
                <div class="form-group">
                    <input type="file" name="image" id="load-image">
                </div>
            </div>

            <a class="js-preview btn">Предварительный просмотр</a>
            <a class="edit-comment btn" style="display:none">Редактировать</a>
            <button type="submit" name="submit" class="btn btn-default">Отправить</button>
        </form>
    </div>

<?php endif; ?>

<script type="text/javascript">
$("document").ready(function(){  
    //If preview button was click
    $('a.js-preview').on('click', function() {
        //hide form
        $('div#inputs').hide();
        $('a.js-preview').hide();
        $('div.add-comment-form > h2').hide();
        
        //show edit button
        $('a.edit-comment').show();
        //show preview 
        $('div.comment.preview').show();
        
        //Add data from form to preview element
        $("div.comment.preview span.name").text($("div.add-comment-form input.name").val());
        $("div.comment.preview span.email").text($("div.add-comment-form input.email").val());
        $("div.comment.preview div.text").text($("div.add-comment-form textarea.text").val()); 
        
        //get current date and insert in preview element
        var d = new Date();
        var time = d.getFullYear() + "-"
                + (d.getMonth()+1)  + "-" 
                + d.getDate() + " "  
                + d.getHours() + ":"  
                + d.getMinutes() + ":" 
                + d.getSeconds();
        $("div.comment.preview span.date").text(time);
        
    });
    
    //get image and insert it in preview element
    $('#load-image').on('change', function(event) {
        var output = document.getElementById('thumb-preview-image');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.style.width = "320px";
        output.style.height = "240px";
    });
    
    //If edit button was click,
    $('a.edit-comment').on('click', function() {
        //show edit form
        $('div#inputs').show();
        $('a.js-preview').show();
        $('div.add-comment-form > h2').show();
        
        //hide edit button
        $('a.edit-comment').hide();
        //hide preview element
        $('div.comment.preview').hide();
        
    });
});
</script>  

<?php include ROOT.'/views/layouts/footer.php'; ?>