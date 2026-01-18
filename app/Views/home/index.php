<!-- Page d'accueil -->
<?php if ($isConnected): ?>
    <!-- Hero Section - Welcome -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; padding: 60px 30px; border-radius: 16px; margin-bottom: 50px; text-align: center;">
        <h1 style="font-size: 48px; font-weight: 700; margin: 0 0 15px 0; letter-spacing: -1px;">👋 Welcome back, <?= htmlspecialchars($userName) ?>!</h1>
        <p style="font-size: 18px; opacity: 0.95; margin: 0; max-width: 600px; margin: 0 auto;">Discover amazing products, manage your cart, and track your orders seamlessly</p>
    </div>

    <!-- Action Cards Grid -->
    <div class="grid grid-cols-3" style="margin-bottom: 50px;">
        <!-- Products Card -->
        <div class="card" style="text-align: center; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 2px solid #e5e7eb;">
            <div style="font-size: 48px; margin-bottom: 20px;">🛍️</div>
            <h3 style="margin: 0 0 12px 0;">Browse Products</h3>
            <p style="color: #6b7280; margin: 0 0 20px 0; font-size: 14px;">Explore our curated collection of premium items</p>
            <a href="/products" class="btn btn-primary" style="width: 100%;">View Catalog</a>
        </div>

        <!-- Cart Card -->
        <div class="card" style="text-align: center; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 2px solid #e5e7eb;">
            <div style="font-size: 48px; margin-bottom: 20px;">🛒</div>
            <h3 style="margin: 0 0 12px 0;">Shopping Cart</h3>
            <p style="color: #6b7280; margin: 0 0 20px 0; font-size: 14px;">Review and manage your selected items</p>
            <a href="/cart" class="btn btn-success" style="width: 100%;">Open Cart</a>
        </div>

        <!-- Orders Card -->
        <div class="card" style="text-align: center; background: linear-gradient(135deg, #ffffff 0%, #f9fafb 100%); border: 2px solid #e5e7eb;">
            <div style="font-size: 48px; margin-bottom: 20px;">📦</div>
            <h3 style="margin: 0 0 12px 0;">My Orders</h3>
            <p style="color: #6b7280; margin: 0 0 20px 0; font-size: 14px;">Track and manage your purchase history</p>
            <a href="/orders" class="btn btn-warning" style="width: 100%;">View Orders</a>
        </div>
    </div>

    <!-- Features Section -->
    <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 40px 30px; border-radius: 16px; margin-bottom: 50px; border-left: 5px solid #6366f1;">
        <h2 style="margin: 0 0 30px 0; font-size: 28px;">Why Choose EliteShop?</h2>
        <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
            <div style="padding: 20px; background: white; border-radius: 12px; border-left: 4px solid #6366f1;">
                <div style="font-size: 32px; margin-bottom: 12px;">⚡</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 600;">Fast Checkout</h4>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Complete your purchase in seconds</p>
            </div>
            <div style="padding: 20px; background: white; border-radius: 12px; border-left: 4px solid #10b981;">
                <div style="font-size: 32px; margin-bottom: 12px;">🔒</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 600;">Secure Payment</h4>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Your data is always protected</p>
            </div>
            <div style="padding: 20px; background: white; border-radius: 12px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 32px; margin-bottom: 12px;">📞</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 600;">Support 24/7</h4>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">We're here to help anytime</p>
            </div>
            <div style="padding: 20px; background: white; border-radius: 12px; border-left: 4px solid #ef4444;">
                <div style="font-size: 32px; margin-bottom: 12px;">🚀</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 600;">Fast Shipping</h4>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Get orders delivered quickly</p>
            </div>
        </div>
    </div>

<?php else: ?>
    <!-- Welcome Section for non-connected users -->
    <div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: white; padding: 80px 30px; border-radius: 16px; margin-bottom: 50px; text-align: center;">
        <h1 style="font-size: 48px; font-weight: 700; margin: 0 0 15px 0; letter-spacing: -1px;">Welcome to EliteShop</h1>
        <p style="font-size: 20px; opacity: 0.95; margin: 0 0 40px 0; max-width: 600px; margin: 0 auto 40px;">Your gateway to premium shopping experience. Sign in or create an account to get started!</p>
        
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="/login" class="btn btn-primary" style="padding: 15px 40px; font-size: 16px; font-weight: 600;">Sign In</a>
            <a href="/register" class="btn btn-success" style="padding: 15px 40px; font-size: 16px; font-weight: 600;">Create Account</a>
        </div>
    </div>

    <!-- Benefits Grid -->
    <div style="margin-bottom: 50px;">
        <h2 style="text-align: center; margin-bottom: 40px; font-size: 32px;">Why join EliteShop?</h2>
        <div class="grid grid-cols-3">
            <div class="card" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 20px;">🎁</div>
                <h3 style="margin: 0 0 12px 0;">Exclusive Deals</h3>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Access special discounts and offers for members</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 20px;">🌟</div>
                <h3 style="margin: 0 0 12px 0;">Quality Products</h3>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Hand-picked items from trusted sellers</p>
            </div>
            <div class="card" style="text-align: center;">
                <div style="font-size: 48px; margin-bottom: 20px;">💳</div>
                <h3 style="margin: 0 0 12px 0;">Easy Payment</h3>
                <p style="color: #6b7280; margin: 0; font-size: 14px;">Secure and flexible payment options</p>
            </div>
        </div>
    </div>

    <!-- Feature Highlights -->
    <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); padding: 50px 30px; border-radius: 16px; border-left: 5px solid #6366f1;">
        <h2 style="margin: 0 0 40px 0; font-size: 28px; text-align: center;">Features</h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
            <div>
                <h3 style="color: #6366f1; font-size: 20px; margin: 0 0 12px 0;">📦 Smart Catalog</h3>
                <p style="color: #6b7280; margin: 0; line-height: 1.6;">Browse thousands of products organized by categories. Find exactly what you're looking for in seconds.</p>
            </div>
            <div>
                <h3 style="color: #10b981; font-size: 20px; margin: 0 0 12px 0;">🛒 Smart Cart</h3>
                <p style="color: #6b7280; margin: 0; line-height: 1.6;">Manage your items effortlessly. Add, remove, or update quantities with just a click.</p>
            </div>
            <div>
                <h3 style="color: #f59e0b; font-size: 20px; margin: 0 0 12px 0;">📋 Order History</h3>
                <p style="color: #6b7280; margin: 0; line-height: 1.6;">Keep track of all your purchases. View order status and details anytime.</p>
            </div>
        </div>
    </div>

<?php endif; ?>
