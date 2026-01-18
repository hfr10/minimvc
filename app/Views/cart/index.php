<!-- Shopping Cart - Modern Design -->
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="margin: 0; font-size: 36px;">🛒 Shopping Cart</h2>
            <p style="color: #6b7280; margin: 10px 0 0 0; font-size: 16px;">Review and manage your items before checkout</p>
        </div>
        <a href="/products" class="btn btn-primary" style="padding: 12px 24px;">← Continue Shopping</a>
    </div>
    
    <!-- Messages -->
    <?php if (isset($message) && $message): ?>
        <div style="padding: 14px; margin-bottom: 20px; border-radius: 8px; display: flex; gap: 10px; align-items: center;
                    background-color: <?= $messageType === 'success' ? '#dcfce7' : '#fee2e2' ?>; 
                    color: <?= $messageType === 'success' ? '#166534' : '#b91c1c' ?>; 
                    border: 1px solid <?= $messageType === 'success' ? '#bbf7d0' : '#fecaca' ?>;">
            <span style="font-size: 18px;"><?= $messageType === 'success' ? '✅' : '⚠️' ?></span>
            <span><?= htmlspecialchars($message) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if (empty($cartItems)): ?>
        <!-- Empty Cart State -->
        <div class="card" style="text-align: center; padding: 80px 30px;">
            <div style="font-size: 80px; margin-bottom: 20px;">🛒</div>
            <h3 style="margin: 0 0 12px 0; font-size: 24px;">Your Cart is Empty</h3>
            <p style="color: #6b7280; margin: 0 0 30px 0; font-size: 16px;">Start adding products to your cart</p>
            <a href="/products" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px;">
            <!-- Cart Items -->
            <div>
                <div style="background: white; border-radius: 12px; padding: 24px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);">
                    <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1f2937;">Items in Cart</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 16px;">
                        <?php foreach ($cartItems as $item): ?>
                            <div style="padding: 16px; border: 1px solid #e5e7eb; border-radius: 10px; display: flex; gap: 16px; align-items: center;">
                                <!-- Image -->
                                <div style="width: 100px; height: 100px; flex-shrink: 0;">
                                    <?php if (!empty($item['image_url'])): ?>
                                        <img 
                                            src="<?= htmlspecialchars($item['image_url']) ?>" 
                                            alt="<?= htmlspecialchars($item['nom']) ?>" 
                                            style="width: 100%; height: 100%; object-fit: contain; border-radius: 8px;"
                                            onerror="this.style.display='none'"
                                        >
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: #f3f4f6; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: #d1d5db; font-size: 32px;">📦</span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Info -->
                                <div style="flex: 1;">
                                    <h4 style="margin: 0 0 6px 0; font-size: 15px; font-weight: 600; color: #1f2937;">
                                        <?= htmlspecialchars($item['nom']) ?>
                                    </h4>
                                    <div style="color: #6366f1; font-weight: 600; font-size: 16px; margin-bottom: 8px;">
                                        <?= number_format((float)$item['prix'], 2, '.', ' ') ?> €
                                    </div>
                                    
                                    <!-- Quantity Controls -->
                                    <form method="POST" action="/cart/update" style="display: flex; gap: 8px; align-items: center;">
                                        <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>">
                                        <label style="font-size: 13px; color: #6b7280;">Qty:</label>
                                        <input 
                                            type="number" 
                                            name="quantite" 
                                            value="<?= htmlspecialchars($item['quantite']) ?>" 
                                            min="1" 
                                            style="width: 60px; padding: 6px 10px; border: 1px solid #e5e7eb; border-radius: 6px; font-size: 14px;"
                                        >
                                        <button type="submit" style="padding: 6px 12px; background: #6366f1; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">Update</button>
                                        
                                        <!-- Remove Button -->
                                        <form method="POST" action="/cart/remove" style="display: inline; margin: 0;">
                                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($item['id']) ?>">
                                            <button type="submit" style="padding: 6px 12px; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 12px; font-weight: 500;">Remove</button>
                                        </form>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Summary Sidebar -->
            <div style="height: fit-content;">
                <div class="card">
                    <h3 style="margin: 0 0 20px 0; font-size: 18px; font-weight: 600; color: #1f2937;">Order Summary</h3>
                    
                    <!-- Totals -->
                    <div style="padding: 20px 0; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6b7280;">Subtotal</span>
                            <span style="font-weight: 600; color: #1f2937;"><?= number_format((float)($total ?? 0), 2, '.', ' ') ?> €</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                            <span style="color: #6b7280;">Shipping</span>
                            <span style="font-weight: 600; color: #10b981;">FREE</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px solid #e5e7eb;">
                            <span style="font-weight: 600; color: #1f2937;">Total</span>
                            <span style="font-size: 20px; font-weight: 700; color: #6366f1;"><?= number_format((float)($total ?? 0), 2, '.', ' ') ?> €</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 12px;">
                        <form method="POST" action="/orders/create" style="margin: 0;">
                            <button type="submit" class="btn btn-success" style="width: 100%; padding: 12px; font-weight: 600;">
                                Proceed to Checkout
                            </button>
                        </form>
                        <form method="POST" action="/cart/clear" style="margin: 0;">
                            <button type="submit" class="btn btn-secondary" style="width: 100%; padding: 12px; font-weight: 600;">
                                Clear Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
