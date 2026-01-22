<!doctype html>
<html lang="th">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>The Vote — เข้าสู่ระบบ</title>
	<meta name="description" content="The Vote — New Year Party 2026 login">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
	<style>
    html,body{height:100%;margin:0;padding:0;}
    body{
      font-family:Inter,ui-sans-serif,system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
      background-size: 400% 400%;
      animation: gradientShift 15s ease infinite;
    }
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .glass-card {
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.3);
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    .gradient-text {
      background: linear-gradient(90deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
    }
    .confetti {
      position: absolute;
      width: 10px;
      height: 10px;
      background: #f093fb;
      opacity: 0.7;
      animation: confettiFall 4s linear infinite;
    }
    @keyframes confettiFall {
      to { transform: translateY(100vh) rotate(360deg); opacity: 0; }
    }
    .input-glow:focus {
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.25);
    }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center px-4 relative overflow-hidden">
  <!-- Animated confetti decorations -->
  <div class="confetti" style="left:10%;animation-delay:0s;"></div>
  <div class="confetti" style="left:20%;animation-delay:1s;background:#4facfe;"></div>
  <div class="confetti" style="left:30%;animation-delay:2s;background:#00f2fe;"></div>
  <div class="confetti" style="left:40%;animation-delay:0.5s;background:#667eea;"></div>
  <div class="confetti" style="left:50%;animation-delay:1.5s;"></div>
  <div class="confetti" style="left:60%;animation-delay:2.5s;background:#764ba2;"></div>
  <div class="confetti" style="left:70%;animation-delay:0.8s;background:#4facfe;"></div>
  <div class="confetti" style="left:80%;animation-delay:1.8s;"></div>
  <div class="confetti" style="left:90%;animation-delay:2.2s;background:#00f2fe;"></div>

  <!-- Centered Login Card -->
  <div class="w-full max-w-md relative z-10">
    <div class="glass-card rounded-2xl p-10 transform transition-all hover:scale-[1.02]">
      <!-- Header with logo -->
      <div class="text-center mb-8">
        <div class="inline-block mb-4">
          <img src="/images/Logo-Well-Graded 2025.png" alt="">
        </div>
        <h1 class="text-4xl font-bold gradient-text mb-2">The Vote</h1>
        <p class="text-gray-600 text-sm">New Year Party 2026</p>
        <p class="text-gray-500 text-xs mt-1">เข้าสู่ระบบเพื่อเข้าร่วมกิจกรรมโหวต</p>
      </div>

      <!-- Login Form -->
      <form id="loginForm" class="space-y-5">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">อีเมล</label>
          <input 
            id="email" 
            name="email" 
            type="email" 
            required 
            placeholder="your@email.com" 
            class="input-glow block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-purple-500 transition-all"
          >
        </div>

        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">รหัสผ่าน</label>
          <input 
            id="password" 
            name="password" 
            type="password" 
            required 
            placeholder="••••••••" 
            class="input-glow block w-full px-4 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:border-purple-500 transition-all"
          >
        </div>

       

        <div class="pt-2">
          <button 
            id="submitBtn" 
            type="submit" 
            class="w-full py-3.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white rounded-xl font-semibold text-base shadow-lg transform transition-all hover:scale-[1.02] active:scale-[0.98]"
          >
            เข้าสู่ระบบ
          </button>
        </div>

        <div id="msg" class="text-sm text-red-600 text-center min-h-[20px] font-medium"></div>

        
      </form>

      <!-- Footer -->
      <div class="mt-8 pt-6 border-t border-gray-200 text-center">
        <p class="text-xs text-gray-500">© 2026 The Vote. All rights reserved.</p>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const form = document.getElementById('loginForm');
      const msg = document.getElementById('msg');
      const submitBtn = document.getElementById('submitBtn');

      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        msg.textContent = '';
        const email = document.getElementById('email').value.trim();
        const password = document.getElementById('password').value;
        
        submitBtn.disabled = true;
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        try {
            // env API_URL
          console.log('Sending login request...');
          const res = await fetch('/api/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ email, password })
          });

          console.log('Login response status:', res.status);
          const data = await res.json();
          console.log('Login response data:', data);
          
          if (!res.ok) {
            msg.textContent = data.message || 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            return;
          }

          // Extract token from various possible locations in response
          const token = data.access_token || data.authorization?.token || data.token;
          console.log('Token extracted:', token ? 'YES' : 'NO');
                     
          if (token) {
            // Send token to backend to store in session
            console.log('Sending token to set-session...');
            const sessionRes = await fetch('/api/set-session', {
              method: 'POST',
              headers: { 
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
              },
              body: JSON.stringify({ token, user: data.user })
            });
            console.log('Set-session response:', sessionRes.status, await sessionRes.json());
            
            localStorage.setItem('token', token);
            if (data.user) localStorage.setItem('user', JSON.stringify(data.user));
            
            msg.textContent = '✓ เข้าสู่ระบบสำเร็จ กำลังเปลี่ยนหน้า...';
            msg.classList.remove('text-red-600');
            msg.classList.add('text-green-600');
            
            window.location.href = '/SpecialEvent/VoteRegister';
            return;
          }

          msg.textContent = 'การเข้าสู่ระบบล้มเหลว — ไม่มีโทเค็นตอบกลับ';
          console.log('Response data:', data); // Debug: show full response
        } catch (err) {
          msg.textContent = 'เกิดข้อผิดพลาดในการเชื่อมต่อ';
          console.error(err);
        } finally {
          if (!msg.classList.contains('text-green-600')) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
          }
        }
      });
    })();
  </script>
</body>
</html>
