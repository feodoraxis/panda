<?php
if ( !defined('ABSPATH') ) die();

use Carbon_Fields\Field;
use Carbon_Fields\Block;

Block::make( 'Recall form' )
    ->add_fields( Array(
        Field::make( 'text', 'recall-title', 'Заголовок' )

    ) )
    ->set_render_callback( function ( $fields, $attributes, $inner_blocks ) {
        ?>
        <div class="b-page-static-form">
            <form action="/send.php" method='post' class='validate-form-2' enctype="multipart/form-data">

                <input type="hidden" name="name" value="">
                <input type="hidden" name="phone" value="">
                <input type="hidden" name="email" value="">

                <h2><?php echo $fields['recall-title']; ?></h2>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="main-input-text">
                            <input type="text" name='hyt68d7siqwfde7'>
                            <p>Имя <span>*</span></p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="main-input-text">
                            <input type="tel" name='dbe6ud6tew7y3re'>
                            <p>Телефон <span>*</span></p>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="main-input-textarea">
                            <textarea name='hgd67tqdgeuwf6t' id=""></textarea>
                            <p>Комментарий к заказу</p>
                        </div>
                    </div>
                </div>

                <div class="file">
                    <input type="file" class='hidden' name="lk9o809oye3fcg6" id='lk9o809oye3fcg6'>
                    <div class="file-fake" for="#lk9o809oye3fcg6">Загрузить файл</div>
                </div>

                <button class="btn btn-rosa">Оформить заказ</button>

                <div class="style-checkbox">                        
                    <input id="iagree" name="cjw7h8" type="checkbox" checked="checked">
                    <label for="iagree" class="checked">
                        <p>Нажимая на кнопку "Оформить заказ" я соглашаюсь <br>с <a href="/rules/" target="_blank">Условиями обработки персональных данных</a>.</p>
                    </label>    
                </div>

                <div class="b-page-static-form form-result"></div>

            </form>
        </div>
        <?php

    } );