<?php
class AppBaseException extends Exception {
    public function getHtmlPanel() {
        echo '<div class="panel panel-danger">'
                .'<div class="panel-heading">'
                    .'<h3 class="panel-title">'
                        .'<span class="glyphicon glyphicon-warning-sign"></span> Ошибка!'
                    .'</h3>'
                .'</div>'
                .'<div class="panel-body">'
                    .$this->getMessage().' <a href="index.php">На главную.</a>'
                .'</div>'
             .'</div>';        
    }
}