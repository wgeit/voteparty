<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - The Vote</title>
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
    </style>
</head>
<body class="animated-gradient min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/90 backdrop-blur-md shadow-2xl border-b-4 border-purple-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">🎉 The Vote</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <span id="userName" class="text-gray-700"></span>
                    <button 
                        id="logoutButton"
                        class="bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white px-6 py-2 rounded-xl font-bold transition duration-300 transform hover:scale-105 shadow-lg"
                    >
                        🚪 ออกจากระบบ
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 rounded-2xl shadow-2xl p-8 mb-6 text-white">
            <h2 class="text-4xl font-bold mb-3">🎊 ยินดีต้อนรับ! 🎉</h2>
            <p class="text-xl font-medium">คุณได้เข้าสู่ระบบสำเร็จแล้ว</p>
            <p class="text-white/80 mt-2">Happy New Year 2026! 🎆</p>
        </div>

        <!-- User Info -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 mb-6 border-2 border-purple-200">
            <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">👤 ข้อมูลผู้ใช้</h3>
            <div id="userInfo" class="space-y-2">
                <div class="flex">
                    <span class="font-medium text-gray-600 w-32">ชื่อ:</span>
                    <span id="displayName" class="text-gray-800"></span>
                </div>
                <div class="flex">
                    <span class="font-medium text-gray-600 w-32">อีเมล:</span>
                    <span id="displayEmail" class="text-gray-800"></span>
                </div>
                <div class="flex">
                    <span class="font-medium text-gray-600 w-32">สมาชิกเมื่อ:</span>
                    <span id="displayCreatedAt" class="text-gray-800"></span>
                </div>
            </div>
        </div>

        <!-- Users List -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 border-2 border-purple-200">
            <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">📋 รายชื่อผู้ใช้ทั้งหมด</h3>
            <div id="usersList" class="space-y-2">
                <div class="text-center py-8">
                    <svg class="inline w-8 h-8 animate-spin text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-gray-600 mt-2">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Check if user is logged in
        const token = localStorage.getItem('token');
        const user = JSON.parse(localStorage.getItem('user') || '{}');

        if (!token) {
            window.location.href = '/login';
        }

        // Display user info
        if (user.name) {
            document.getElementById('userName').textContent = user.name;
            document.getElementById('displayName').textContent = user.name;
            document.getElementById('displayEmail').textContent = user.email;
        }

        // Fetch current user info
        async function fetchCurrentUser() {
            try {
                const response = await fetch('/api/me', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.status === 'success') {
                        document.getElementById('userName').textContent = data.user.name;
                        document.getElementById('displayName').textContent = data.user.name;
                        document.getElementById('displayEmail').textContent = data.user.email;
                        
                        // Format date
                        const createdAt = new Date(data.user.created_at);
                        document.getElementById('displayCreatedAt').textContent = createdAt.toLocaleDateString('th-TH', {
                            year: 'numeric',
                            month: 'long',
                            day: 'numeric'
                        });
                    }
                } else if (response.status === 401) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Error fetching user:', error);
            }
        }

        // Fetch all users
        async function fetchUsers() {
            try {
                const response = await fetch('/api/users', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.status === 'success') {
                        displayUsers(data.data);
                    }
                } else if (response.status === 401) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = '/login';
                }
            } catch (error) {
                console.error('Error fetching users:', error);
                document.getElementById('usersList').innerHTML = `
                    <div class="text-center text-red-600 py-4">
                        เกิดข้อผิดพลาดในการโหลดข้อมูล
                    </div>
                `;
            }
        }

        // Display users in table
        function displayUsers(users) {
            if (users.length === 0) {
                document.getElementById('usersList').innerHTML = `
                    <div class="text-center text-gray-600 py-4">
                        ไม่พบข้อมูลผู้ใช้
                    </div>
                `;
                return;
            }

            const tableHTML = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ชื่อ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">อีเมล</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">วันที่สมัคร</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${users.map(u => `
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${u.id}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${u.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${u.email}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${new Date(u.created_at).toLocaleDateString('th-TH', {
                                            year: 'numeric',
                                            month: 'short',
                                            day: 'numeric'
                                        })}
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;

            document.getElementById('usersList').innerHTML = tableHTML;
        }

        // Logout function
        document.getElementById('logoutButton').addEventListener('click', async function() {
            try {
                await fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Accept': 'application/json'
                    }
                });
            } catch (error) {
                console.error('Error logging out:', error);
            } finally {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }
        });

        // Load data on page load
        fetchCurrentUser();
        fetchUsers();
    </script>
</body>
</html>
