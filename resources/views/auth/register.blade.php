<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสมาชิก - The Vote</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@300;400;500;600;700&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Noto Sans Thai', 'Poppins', sans-serif;
        }
        .animated-gradient {
            background: linear-gradient(-45deg, #ee7752, #e73c7e, #23a6d5, #23d5ab);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }
        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .glass {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .shimmer {
            background: linear-gradient(90deg, transparent 0%, rgba(255, 255, 255, 0.3) 50%, transparent 100%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        @keyframes bounce-in {
            0% { transform: scale(0.5); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }
        .bounce-in { animation: bounce-in 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55); }
        .glow { box-shadow: 0 0 20px rgba(255, 215, 0, 0.5), 0 0 40px rgba(255, 215, 0, 0.3); }
    </style>
</head>
<body class="animated-gradient min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <div class="w-full max-w-md">
        <!-- Register Card -->
        <div class="glass rounded-3xl shadow-2xl overflow-hidden backdrop-blur-xl bounce-in">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 via-pink-600 to-yellow-500 p-6 text-center relative overflow-hidden shimmer">
                <h1 class="text-4xl font-bold text-white mb-2 tracking-wider">🎊 The Vote 🎉</h1>
                <p class="text-yellow-200 text-lg font-medium">สมัครสมาชิก</p>
                <p class="text-white/80 text-sm mt-1">New Year Party 2026</p>
            </div>

            <!-- Register Form -->
            <div class="p-8">
                <form id="registerForm" class="space-y-6">
                    <!-- Name Input -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            ชื่อ-นามสกุล
                        </label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            required
                            placeholder="กรอกชื่อ-นามสกุล"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        <span id="nameError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Email Input -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            อีเมล
                        </label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            required
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        <span id="emailError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            รหัสผ่าน (อย่างน้อย 6 ตัวอักษร)
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            required
                            minlength="6"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        <span id="passwordError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Password Confirmation Input -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            ยืนยันรหัสผ่าน
                        </label>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            required
                            minlength="6"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                        >
                        <span id="passwordConfirmationError" class="text-red-500 text-sm hidden"></span>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="hidden bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">
                    </div>

                    <!-- Success Message -->
                    <div id="successMessage" class="hidden bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="registerButton"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 transform hover:scale-[1.02]"
                    >
                        <span id="buttonText">สมัครสมาชิก</span>
                        <span id="buttonLoading" class="hidden">
                            <svg class="inline w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            กำลังสมัครสมาชิก...
                        </span>
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600 text-sm">
                        มีบัญชีอยู่แล้ว? 
                        <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium">เข้าสู่ระบบ</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-600 text-sm">
            <p>&copy; {{ date('Y') }} Vote Party. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Handle form submission
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Clear previous errors
            document.getElementById('nameError').classList.add('hidden');
            document.getElementById('emailError').classList.add('hidden');
            document.getElementById('passwordError').classList.add('hidden');
            document.getElementById('passwordConfirmationError').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('successMessage').classList.add('hidden');
            
            // Show loading state
            document.getElementById('buttonText').classList.add('hidden');
            document.getElementById('buttonLoading').classList.remove('hidden');
            document.getElementById('registerButton').disabled = true;
            
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;
            
            try {
                const response = await fetch('/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password,
                        password_confirmation: password_confirmation
                    })
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Store token in localStorage
                    localStorage.setItem('token', data.authorization.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Show success message
                    document.getElementById('successMessage').textContent = 'สมัครสมาชิกสำเร็จ! กำลังเปลี่ยนหน้า...';
                    document.getElementById('successMessage').classList.remove('hidden');
                    
                    // Redirect to dashboard
                    setTimeout(() => {
                        window.location.href = '/dashboard';
                    }, 1000);
                } else if (data.errors) {
                    // Show validation errors
                    if (data.errors.name) {
                        document.getElementById('nameError').textContent = data.errors.name[0];
                        document.getElementById('nameError').classList.remove('hidden');
                    }
                    if (data.errors.email) {
                        document.getElementById('emailError').textContent = data.errors.email[0];
                        document.getElementById('emailError').classList.remove('hidden');
                    }
                    if (data.errors.password) {
                        document.getElementById('passwordError').textContent = data.errors.password[0];
                        document.getElementById('passwordError').classList.remove('hidden');
                    }
                    
                    // Reset button state
                    document.getElementById('buttonText').classList.remove('hidden');
                    document.getElementById('buttonLoading').classList.add('hidden');
                    document.getElementById('registerButton').disabled = false;
                } else {
                    throw new Error(data.message || 'เกิดข้อผิดพลาดในการสมัครสมาชิก');
                }
            } catch (error) {
                // Show error message
                document.getElementById('errorMessage').textContent = error.message;
                document.getElementById('errorMessage').classList.remove('hidden');
                
                // Reset button state
                document.getElementById('buttonText').classList.remove('hidden');
                document.getElementById('buttonLoading').classList.add('hidden');
                document.getElementById('registerButton').disabled = false;
            }
        });
    </script>
</body>
</html>
