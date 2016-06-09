<?php

return array(
    'comments/([0-9]+)' => 'comments/editComment/$1',
    'comments/delete/([0-9]+)' => 'comments/deleteComment/$1',
    'comments' => 'comments/index',    
    'login' => 'admin/login',
    'logout' => 'admin/logout',
    '' => 'comments/index',
);