<!-- Modern Login Form -->
<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; min-height: 70vh;">
    <!-- Left Side - Features -->
    <div style="display: flex; flex-direction: column; gap: 30px;">
        <div>
            <h1 style="font-size: 42px; font-weight: 700; margin: 0 0 15px 0; color: #1f2937;">Welcome Back</h1>
            <p style="font-size: 18px; color: #6b7280; margin: 0;">Sign in to your EliteShop account and continue shopping</p>
        </div>

        <div style="background: #f0f4ff; padding: 20px; border-radius: 12px; border-left: 4px solid #6366f1;">
            <div style="font-size: 32px; margin-bottom: 12px;">🎁</div>
            <h3 style="margin: 0 0 8px 0; color: #1f2937;">Exclusive Deals</h3>
            <p style="color: #6b7280; margin: 0; font-size: 14px;">Access members-only discounts and offers</p>
        </div>

        <div style="background: #f0fdf4; padding: 20px; border-radius: 12px; border-left: 4px solid #10b981;">
            <div style="font-size: 32px; margin-bottom: 12px;">🛒</div>
            <h3 style="margin: 0 0 8px 0; color: #1f2937;">Smart Shopping</h3>
            <p style="color: #6b7280; margin: 0; font-size: 14px;">Save your cart and order history</p>
        </div>

        <div style="background: #fefce8; padding: 20px; border-radius: 12px; border-left: 4px solid #f59e0b;">
            <div style="font-size: 32px; margin-bottom: 12px;">⚡</div>
            <h3 style="margin: 0 0 8px 0; color: #1f2937;">Fast Checkout</h3>
            <p style="color: #6b7280; margin: 0; font-size: 14px;">Complete purchases in seconds</p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="card">
        <h2 style="margin: 0 0 30px 0; font-size: 28px; color: #1f2937; text-align: center;">Sign In</h2>

        <!-- Error Messages -->
        <?php 
            $errors = [
                'missing_fields' => 'Please fill in all fields',
                'invalid_credentials' => 'Invalid email or password',
            ];
            if (isset($_GET['error']) && isset($errors[$_GET['error']])): 
        ?>
            <div style="padding: 14px; margin-bottom: 20px; background-color: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; border-radius: 8px; display: flex; gap: 10px; align-items: center;">
                <span style="font-size: 20px;">⚠️</span>
                <span style="font-weight: 500;"><?= htmlspecialchars($errors[$_GET['error']]) ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login" style="display: flex; flex-direction: column; gap: 20px;">
            <!-- Email Field -->
            <div>
                <label for="email" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937;">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="you@example.com"
                    required 
                    style="width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit;"
                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.1)';"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                >
            </div>

            <!-- Password Field -->
            <div>
                <label for="password" style="display: block; margin-bottom: 8px; font-weight: 600; color: #1f2937;">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••"
                    required 
                    style="width: 100%; padding: 12px 14px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 15px; transition: border-color 0.3s; font-family: inherit;"
                    onfocus="this.style.borderColor='#6366f1'; this.style.boxShadow='0 0 0 3px rgba(99, 102, 241, 0.1)';"
                    onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none';"
                >
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="btn btn-primary"
                style="padding: 14px; margin-top: 10px; font-size: 16px; font-weight: 600; width: 100%;"
            >
                Sign In to Your Account
            </button>
        </form>

        <!-- Divider -->
        <div style="display: flex; align-items: center; gap: 15px; margin: 25px 0; opacity: 0.5;">
            <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
            <span style="color: #6b7280; font-size: 14px;">or</span>
            <div style="flex: 1; height: 1px; background: #e5e7eb;"></div>
        </div>

        <!-- Register Link -->
        <div style="text-align: center; padding-top: 20px; border-top: 1px solid #e5e7eb;">
            <p style="color: #6b7280; margin: 0 0 12px 0; font-size: 15px;">Don't have an account?</p>
            <a href="/register" style="color: #6366f1; text-decoration: none; font-weight: 600; display: inline-block; padding: 10px 20px; border-radius: 6px; transition: background 0.3s;"
               onmouseover="this.style.background='#f0f4ff'"
               onmouseout="this.style.background='transparent'">
                Create a new account
            </a>
        </div>
    </div>
</div>
