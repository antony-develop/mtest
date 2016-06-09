<?php include ROOT.'/views/layouts/header.php'; ?>

<div class="col-md-5 login-form" role="form">
    <h2>Войти</h2>
    <div class="col-md-12">
            
        <?php if (isset($errors) && is_array($errors)): ?>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
            
    </div>
    <form action="/login" method="POST">
        <div class="form-group">
            <label for="name">Имя:</label>
            <input class="username form-control" name="name" placeholder="Имя" autofocus />
        </div>
        <div class="form-group">
            <label for="password">Пароль:</label>
            <input class='form-control' type="password" name="password" placeholder="Пароль"/>
        </div>
        <button type="submit" name="submit" class="btn btn-default">Войти</button>
    </form>
</div>

<?php include ROOT.'/views/layouts/footer.php'; ?>