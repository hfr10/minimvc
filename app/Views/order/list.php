<!-- Orders List - Modern Design -->
<div>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h2 style="margin: 0; font-size: 36px;">📦 My Orders</h2>
            <p style="color: #6b7280; margin: 10px 0 0 0; font-size: 16px;">Track and manage your purchases</p>
        </div>
        <a href="/products" class="btn btn-primary" style="padding: 12px 24px;">← Continue Shopping</a>
    </div>
    
    <?php if (empty($orders)): ?>
        <!-- Empty State -->
        <div class="card" style="text-align: center; padding: 80px 30px;">
            <div style="font-size: 80px; margin-bottom: 20px;">📋</div>
            <h3 style="margin: 0 0 12px 0; font-size: 24px;">No Orders Yet</h3>
            <p style="color: #6b7280; margin: 0 0 30px 0; font-size: 16px;">You haven't placed any orders yet. Start shopping now!</p>
            <a href="/products" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <!-- Orders Grid -->
        <div style="display: grid; gap: 20px;">
            <?php foreach ($orders as $order): ?>
                <div class="card">
                    <div style="display: flex; justify-content: space-between; align-items: start; flex-wrap: wrap; gap: 20px;">
                        <!-- Order Info -->
                        <div style="flex: 1;">
                            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px; flex-wrap: wrap;">
                                <h3 style="margin: 0; color: #1f2937; font-size: 20px; font-weight: 600;">
                                    Order #<?= htmlspecialchars($order['id']) ?>
                                </h3>
                                
                                <!-- Status Badge -->
                                <?php 
                                    $statusConfig = [
                                        'validee' => ['label' => '✅ Validated', 'bg' => '#dcfce7', 'text' => '#166534'],
                                        'en_attente' => ['label' => '⏳ Pending', 'bg' => '#fef3c7', 'text' => '#92400e'],
                                        'annulee' => ['label' => '❌ Cancelled', 'bg' => '#fee2e2', 'text' => '#b91c1c'],
                                    ];
                                    $status = $statusConfig[$order['statut']] ?? ['label' => $order['statut'], 'bg' => '#e5e7eb', 'text' => '#4b5563'];
                                ?>
                                <span class="badge" style="background-color: <?= $status['bg'] ?>; color: <?= $status['text'] ?>; padding: 6px 14px; border-radius: 20px;">
                                    <?= $status['label'] ?>
                                </span>
                            </div>
                            
                            <!-- Details -->
                            <div style="display: grid; grid-template-columns: auto auto auto; gap: 30px; font-size: 14px;">
                                <div>
                                    <span style="color: #6b7280; display: block; margin-bottom: 4px;">Order Date</span>
                                    <span style="font-weight: 600; color: #1f2937;"><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
                                </div>
                                <div>
                                    <span style="color: #6b7280; display: block; margin-bottom: 4px;">Time</span>
                                    <span style="font-weight: 600; color: #1f2937;"><?= date('H:i', strtotime($order['created_at'])) ?></span>
                                </div>
                                <div>
                                    <span style="color: #6b7280; display: block; margin-bottom: 4px;">Total Amount</span>
                                    <span style="font-size: 18px; font-weight: 700; color: #6366f1;">
                                        <?= number_format((float)$order['total'], 2, '.', ' ') ?> €
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div style="display: flex; flex-direction: column; gap: 10px; min-width: 140px;">
                            <a href="/orders/show?id=<?= htmlspecialchars($order['id']) ?>" 
                               class="btn btn-primary"
                               style="padding: 10px 16px; font-size: 14px; text-align: center;">
                                👁️ View Details
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
