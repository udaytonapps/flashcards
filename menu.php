
<?php
$menu = array(
    'AddCardSet.php' => '<span class="fa-stack small">
                            <span class="fa fa-square fa-stack-2x" style="top:-6px;"></span>
                            <span class="fa fa-square-o fa-stack-2x" style="top:2px;left:-8px;"></span>
                            <span class="fa fa-inverse fa-plus fa-stack-1x" style="top:-6px;"></span>
                         </span>
                         <span class="menu-with-icon">Create Card Set</span>',
    'LinkToSet.php' => '<span class="fa fa-external-link"></span> Link To Card Set'
);
?>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="index.php">Flashcards</a>
        </div>
        <ul class="nav navbar-nav">
            <?php foreach( $menu as $menupage => $menulabel ) : ?>
                <li<?php if($menupage == basename($_SERVER['PHP_SELF'])){echo ' class="active"';} ?>>
                    <a href="<?php echo $menupage ; ?>">
                        <?php echo $menulabel ; ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
</nav>