<div class="meal-item__add-to-cart">
    <div class="meal-item-quantity">
        <select name="quantity" class="input-quantity" value="1">
            <?php for ($x = 1; $x <= 20; $x++) {
                echo '<option value="'. $x .'">'. $x .'</option>';
            }  ?>
        </select>
    </div>
    <button type="button" class="button" value="<?php echo esc_attr($id); ?>">
        <h4><?php esc_html_e('Add', 'food-to-prep'); ?></h4>
    </button>
</div>
