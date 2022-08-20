<?php

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="modal fade" id="footerModalReview">            
    <div class="modal-dialog modal-sm">               
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-close" data-dismiss="modal"><a href="#"><i class="fa fa-times-circle" style="font-size: 25px;"></i></a></div>
                <div id="titleFooter">Отзыв</div>
            </div>
            <div class="modal-body">
                <form id="footerNewModal" action="<?= Url::to('/icms/submit/review') ?> " method="post">      
                    <div id="errors"></div>      
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" placeholder="Ваше имя" required="required">
                        </div>
                        <div class="help-block"></div>
                    </div>  
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone" placeholder="Телефон" required="required">
                        </div>  
                        <div class="help-block"></div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <textarea rows="3" class="form-control" name="comment" placeholder="Текст отзыва" required="required"></textarea>
                        </div>  
                        <div class="help-block"></div>
                    </div>
                    <div class="text-center">
                        <button class="btn-intrid">Отправить</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p>Нажимая кнопку «Отправить», Вы даете свое согласие на обработку персональных данных</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="footerModalTech">            
    <div class="modal-dialog modal-sm">               
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-close" data-dismiss="modal"><a href="#"><i class="fa fa-times-circle" style="font-size: 25px;"></i></a></div>
                <div id="titleFooter">Заявка на техподдержку</div>
            </div>
            <div class="modal-body">
                <form id="footerTechModal" action="<?= Url::to('/icms/submit/tech') ?> " method="post">      
                    <div id="errors"></div>      
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" placeholder="Ваше имя" required="required">
                        </div>
                        <div class="help-block"></div>
                    </div>   
                    <div class="form-group">
                        <div class="input-group">
                            <input type="text" class="form-control" name="phone" placeholder="Телефон" required="required">
                        </div>  
                        <div class="help-block"></div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <textarea rows="3" class="form-control" name="comment" placeholder="Проблема" required="required"></textarea>
                        </div>  
                        <div class="help-block"></div>
                    </div>
                    <div class="text-center">
                        <button class="btn-intrid">Отправить</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <p>Нажимая кнопку «Отправить», Вы даете свое согласие на обработку персональных данных</p>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalInfo">            
    <div class="modal-dialog modal-sm">               
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-close" data-dismiss="modal"><a href="#"><i class="fa fa-times-circle" style="font-size: 25px;"></i></a></div>
                <h4 class="modal-title" id="modalInfo-title"></h4>
            </div>
            <div class="modal-body" id="modalInfo-body">
                
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalError">            
    <div class="modal-dialog modal-lg">               
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-close" data-dismiss="modal"><a href="#"><i class="fa fa-times-circle" style="font-size: 25px;"></i></a></div>
                <h4 class="modal-title" id="modalError-title"></h4>
            </div>
            <div class="modal-body" id="modalError-body">
                
            </div>
            <div class="modal-footer">
                
            </div>
        </div>
    </div>
</div>