<div class="meal-item__add-to-cart">
    <div class="meal-item-quantity">
<!--        <div class="meal-item-quantity-minus disabled"><i class="fas fa-minus"></i></div>-->
        <input type="number" name="quantity" class="input-quantity" pattern="[0-9]{3}" value="1" />
<!--        <div class="meal-item-quantity-plus"><i class="fas fa-plus"></i></div>-->
    </div>
    <div class="error-message">Please enter number in here.</div>
    <button type="button" class="button" value="<?php esc_attr_e($id, 'food-to-prep'); ?>">
        <h4><?php esc_html_e('Add', 'food-to-prep'); ?></h4>
    </button>
</div>
