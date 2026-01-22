<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('build/images/wge-favicon-small.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard ผลการโหวต - New Year Party 2026</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
<style>
    body {
        font-family: 'Sarabun', sans-serif;
        background: linear-gradient(135deg, #1e3a8a 0%, #7c3aed 50%, #dc2626 100%);
        min-height: 100vh;
    }
    
    .leader-card {
        transition: all 0.3s ease;
    }
    
    .leader-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
    }
    
    .vote-count {
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .crown {
        animation: rotate 3s ease-in-out infinite;
        filter: drop-shadow(0 0 10px rgba(234, 179, 8, 0.6));
    }
    
    @keyframes rotate {
        0%, 100% { transform: rotate(-10deg); }
        50% { transform: rotate(10deg); }
    }
    
    .new-vote {
        animation: highlight 1s ease-out;
    }
    
    @keyframes highlight {
        0% { background-color: #fef3c7; }
        100% { background-color: transparent; }
    }
    
    .sparkle {
        animation: sparkle 1.5s ease-in-out infinite;
    }
    
    @keyframes sparkle {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(1.2); }
    }
    
    .firework {
        animation: firework 2s ease-out infinite;
    }
    
    @keyframes firework {
        0% { transform: translateY(0) scale(1); opacity: 1; }
        100% { transform: translateY(-20px) scale(1.3); opacity: 0; }
    }
    
    .vote-pulse {
        animation: votePulse 0.6s ease-in-out;
    }
    
    @keyframes votePulse {
        0%, 100% { transform: scale(1); }
        25% { transform: scale(1.3); }
        50% { transform: scale(0.9); }
        75% { transform: scale(1.15); }
    }
    
    /* Custom Scrollbar */
    .scrollable-table::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .scrollable-table::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .scrollable-table::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #6b7280 0%, #4b5563 100%);
        border-radius: 10px;
    }
    
    .scrollable-table::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #4b5563 0%, #374151 100%);
    }
    
    /* Closing Animation Overlay */
    .closing-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(0,0,0,0.8) 0%, rgba(220,38,38,0.9) 100%);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
    }
    
    .closing-message {
        text-align: center;
        animation: bounceIn 0.8s ease-out;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0) rotate(-180deg);
            opacity: 0;
        }
        50% {
            transform: scale(1.3) rotate(10deg);
        }
        100% {
            transform: scale(1) rotate(0deg);
            opacity: 1;
        }
    }
    
    .closing-icon {
        font-size: 8rem;
        color: #ef4444;
        text-shadow: 0 0 30px rgba(239, 68, 68, 0.8);
        animation: iconPulse 1s ease-in-out infinite;
    }
    
    @keyframes iconPulse {
        0%, 100% {
            transform: scale(1);
            filter: brightness(1);
        }
        50% {
            transform: scale(1.2);
            filter: brightness(1.5);
        }
    }
    
    .closing-text {
        font-size: 4rem;
        font-weight: 900;
        color: white;
        text-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
        margin: 2rem 0;
        animation: textGlow 1.5s ease-in-out infinite;
    }
    
    @keyframes textGlow {
        0%, 100% {
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
        }
        50% {
            text-shadow: 0 0 40px rgba(255, 255, 255, 1), 0 0 60px rgba(239, 68, 68, 0.8);
        }
    }
    
    .closing-subtext {
        font-size: 1.5rem;
        color: #fbbf24;
        font-weight: 600;
    }
    
    /* Confetti particles */
    .confetti {
        position: absolute;
        width: 10px;
        height: 10px;
        background: #fbbf24;
        animation: confettiFall 3s linear;
    }
    
    @keyframes confettiFall {
        0% {
            transform: translateY(-100vh) rotate(0deg);
            opacity: 1;
        }
        100% {
            transform: translateY(100vh) rotate(720deg);
            opacity: 0;
        }
    }
</style>
</head>
<body>
<!-- Closing Animation Overlay -->
<div id="closingOverlay" class="closing-overlay">
    <div class="closing-message">
        <div class="closing-icon">
            <i class="fas fa-lock"></i>
        </div>
        <div class="closing-text">
            ปิดการโหวตแล้ว!
        </div>
        <div class="closing-subtext">
            ขอบคุณที่เข้าร่วมโหวต
        </div>
    </div>
</div>

<div class="min-h-screen p-4 sm:p-6">
    <!-- Header Section -->
    <div class="mb-6 bg-gradient-to-r from-amber-500 via-red-600 to-rose-600 rounded-2xl shadow-2xl relative overflow-hidden transition-all duration-300" id="headerSection">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 text-yellow-300 text-6xl opacity-20">
            <i class="fas fa-star sparkle"></i>
        </div>
        <div class="absolute bottom-0 left-0 text-yellow-300 text-6xl opacity-20">
            <i class="fas fa-star sparkle" style="animation-delay: 0.5s;"></i>
        </div>
        
        <div class="relative z-10 p-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white flex items-center gap-3 drop-shadow-lg">
                            <i class="fas fa-trophy text-4xl text-yellow-300"></i>
                            Dashboard ผลการโหวต กิจกรรมประกวดการแต่งกาย New Year Night Party 2026 
                           
                        </h1>
                        <p class="text-sm text-yellow-100 mt-2 font-semibold">
                            <i class="fas fa-calendar-alt mr-1"></i>
                            New Year Night Party 2026 - ผลคะแนนแบบเรียลไทม์
                             <a href="/SpecialEvent/VoteRegister" class="inline-block ml-4 px-3 py-1 bg-white/30 text-white rounded-full text-sm font-medium hover:bg-white/50 transition-all">
                                ระบบ
                            </a>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        {{-- <a href="/User/IT/SpecialEvent/ManageImages" class="inline-flex items-center gap-2 px-4 py-3 bg-white text-red-600 rounded-xl hover:bg-yellow-50 transition-all font-bold shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-images"></i> จัดการรูปภาพ
                        </a> --}}
                        <!-- Event Status Toggle Switch -->
                        <div class="inline-flex items-center gap-3 px-4 py-3 bg-white/20 backdrop-blur-md rounded-xl border-2 border-white/30 shadow-lg">
                            <span class="text-white font-bold">การโหวต:</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" id="voteToggle" class="sr-only peer" onchange="toggleVoting()">
                                <div class="w-14 h-7 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-green-500"></div>
                            </label>
                            <span id="toggleStatusText" class="text-white font-bold">ปิด</span>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-6">
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-semibold">จำนวนผู้โหวตทั้งหมด</p>
                                <p class="text-4xl font-bold text-white drop-shadow-lg" id="totalVoters">0</p>
                            </div>
                            <i class="fas fa-users text-5xl text-white/40"></i>
                        </div>
                    </div>
                    <div class="bg-white/20 backdrop-blur-md rounded-xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-yellow-100 text-sm font-semibold">รูปภาพทั้งหมด</p>
                                <p class="text-4xl font-bold text-white drop-shadow-lg" id="totalImages">0</p>
                            </div>
                            <i class="fas fa-images text-5xl text-white/40"></i>
                        </div>
                    </div>
                    <div class="bg-white backdrop-blur-md rounded-xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div class="w-full">
                                <p class="text-red-800 text-sm font-semibold">เวลานับถอยหลัง</p>
                                <p class="text-4xl font-bold drop-shadow-lg" id="countdown" style="color: #22c55e;">15:00</p>
                            </div>
                            <i class="fas fa-hourglass-half text-5xl text-white/40"></i>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Category Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-[1800px] mx-auto">
        <!-- ชายเดี่ยว -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-amber-200">
            <div class="bg-gradient-to-r from-amber-500 to-yellow-500 p-3 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <i class="fas fa-star text-4xl text-white absolute top-0 right-4 sparkle"></i>
                </div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2 relative z-10 drop-shadow-lg">
                    <i class="fas fa-male text-lg"></i> ชายเดี่ยว
                    <i class="fas fa-trophy text-yellow-200 ml-auto text-sm"></i>
                </h2>
            </div>
            
            <!-- Leader -->
            <div id="leader-เดี่ยวชาย" class="p-3 bg-gradient-to-br from-amber-50 to-yellow-50">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
            
            <!-- Other Entries Table -->
            <div class="p-3">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">คะแนนอื่นๆ</h3>
                <div id="table-เดี่ยวชาย" class="scrollable-table overflow-y-auto max-h-64 border border-gray-200 rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">อันดับ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">รูปภาพ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อผู้โพสต์</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">คะแนน</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-เดี่ยวชาย" class="divide-y divide-gray-200">
                            <!-- Data will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- หญิงเดี่ยว -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-red-200">
            <div class="bg-gradient-to-r from-red-600 to-rose-600 p-3 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <i class="fas fa-heart text-4xl text-white absolute top-0 right-4 sparkle" style="animation-delay: 0.3s;"></i>
                </div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2 relative z-10 drop-shadow-lg">
                    <i class="fas fa-female text-lg"></i> หญิงเดี่ยว
                    <i class="fas fa-trophy text-yellow-200 ml-auto text-sm"></i>
                </h2>
            </div>
            
            <!-- Leader -->
            <div id="leader-เดี่ยวหญิง" class="p-3 bg-gradient-to-br from-red-50 to-rose-50">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
            
            <!-- Other Entries Table -->
            <div class="p-3">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">คะแนนอื่นๆ</h3>
                <div id="table-เดี่ยวหญิง" class="scrollable-table overflow-y-auto max-h-64 border border-gray-200 rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">อันดับ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">รูปภาพ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อผู้โพสต์</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">คะแนน</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-เดี่ยวหญิง" class="divide-y divide-gray-200">
                            <!-- Data will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- แบบกลุ่ม -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden border-2 border-purple-200">
            <div class="bg-gradient-to-r from-purple-700 to-indigo-700 p-3 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20">
                    <i class="fas fa-users text-4xl text-white absolute top-0 right-4 sparkle" style="animation-delay: 0.6s;"></i>
                </div>
                <h2 class="text-lg font-bold text-white flex items-center gap-2 relative z-10 drop-shadow-lg">
                    <i class="fas fa-users text-lg"></i> แบบกลุ่ม
                    <i class="fas fa-trophy text-yellow-200 ml-auto text-sm"></i>
                </h2>
            </div>
            
            <!-- Leader -->
            <div id="leader-แบบกลุ่ม" class="p-3 bg-gradient-to-br from-purple-50 to-indigo-50">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-gray-400"></i>
                    <p class="text-gray-500 mt-2">กำลังโหลดข้อมูล...</p>
                </div>
            </div>
            
            <!-- Other Entries Table -->
            <div class="p-3">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">คะแนนอื่นๆ</h3>
                <div id="table-แบบกลุ่ม" class="scrollable-table overflow-y-auto max-h-64 border border-gray-200 rounded-lg">
                    <table class="w-full">
                        <thead class="bg-gray-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">อันดับ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">รูปภาพ</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อผู้โพสต์</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">คะแนน</th>
                            </tr>
                        </thead>
                        <tbody id="tbody-แบบกลุ่ม" class="divide-y divide-gray-200">
                            <!-- Data will be inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let refreshInterval;
    let previousVotes = {}; // Store previous vote counts
    let previousData = {}; // Store previous full data for comparison
    let currentEventStatus = 0; // 0 = ปิด, 1 = เปิด
    let scrollIntervals = {}; // Store scroll intervals for each category
    let countdownInterval = null;
    let remainingSeconds = 0;
    let votingEndTime = null;
    
    $(document).ready(function() {
        loadDashboard();
        
        // Auto refresh every 1 second
        refreshInterval = setInterval(loadDashboard, 1000);
        
        // Start auto scroll for all tables
        startAutoScroll('เดี่ยวชาย');
        startAutoScroll('เดี่ยวหญิง');
        startAutoScroll('แบบกลุ่ม');
    });
    
    function startAutoScroll(category) {
        const $table = $(`#table-${category}`);
        let scrollDirection = 1; // 1 = down, -1 = up
        let scrollSpeed = 1; // pixels per interval
        
        // Clear existing interval if any
        if (scrollIntervals[category]) {
            clearInterval(scrollIntervals[category]);
        }
        
        scrollIntervals[category] = setInterval(function() {
            const scrollTop = $table.scrollTop();
            const scrollHeight = $table[0].scrollHeight;
            const clientHeight = $table[0].clientHeight;
            
            // Change direction at top or bottom
            if (scrollTop >= scrollHeight - clientHeight - 1) {
                scrollDirection = -1; // scroll up
            } else if (scrollTop <= 0) {
                scrollDirection = 1; // scroll down
            }
            
            // Scroll smoothly
            $table.scrollTop(scrollTop + (scrollSpeed * scrollDirection));
        }, 30); // Run every 30ms for smooth scrolling
    }
    
    function loadDashboard() {
        $.ajax({
            url: '/User/IT/SpecialEvent/getDashboardData',
            type: 'GET',
            success: function(response) {
                if (response.status === 200) {
                    console.log(response)
                    // อัพเดทสถานะ event
                    if (response.eventStatus !== undefined) {
                        currentEventStatus = response.eventStatus;
                        updateEventStatusUI();
                    }
                    
                    updateStats(response.stats);
                    updateCategoryData('เดี่ยวชาย', response.categories['เดี่ยวชาย'] || []);
                    updateCategoryData('เดี่ยวหญิง', response.categories['เดี่ยวหญิง'] || []);
                    updateCategoryData('แบบกลุ่ม', response.categories['แบบกลุ่ม'] || []);
                    // updateLastUpdate();
                }
            },
            error: function() {
                console.error('Failed to load dashboard data');
                updateEventStatusUI(true); // แสดงสถานะ error
            }
        });
    }
    
    function updateEventStatusUI(isError = false) {
        const $toggle = $('#voteToggle');
        const $text = $('#toggleStatusText');
        
        if (isError) {
            $toggle.prop('checked', false).prop('disabled', true);
            $text.text('ข้อผิดพลาด');
            return;
        }
        
        $toggle.prop('disabled', false);
        
        if (currentEventStatus === 1) {
            $toggle.prop('checked', true);
            $text.text('เปิด');
            // Start countdown if not already running
            if (!countdownInterval) {
                startCountdown();
            }
        } else {
            $toggle.prop('checked', false);
            $text.text('ปิด');
            // Stop countdown
            stopCountdown();
            resetCountdown();
        }
    }
    
    function toggleVoting() {
        const $toggle = $('#voteToggle');
        const newStatus = $toggle.is(':checked') ? 1 : 0;
        
        // หยุด auto-refresh ชั่วคราวเพื่อป้องกัน race condition
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
        
        $.ajax({
            url: '/User/IT/SpecialEvent/updateEventStatus',
            type: 'POST',
            data: {
                event_status: newStatus
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                // console.log(response,newStatus)
                if (response.status === 200) {
                    currentEventStatus = newStatus;
                    $('#toggleStatusText').text(newStatus == 1 ? 'เปิด' : 'ปิด');
                    console.log(newStatus)
                    if (newStatus == 1) {
                        votingEndTime = Date.now() + (15 * 60 * 1000); // 15 minutes from now
                        localStorage.setItem('votingEndTime', votingEndTime);
                        startCountdown();
                    } else {
                        stopCountdown();
                        resetCountdown();
                        localStorage.removeItem('votingEndTime');
                    }
                }
                
                // เริ่ม auto-refresh ใหม่หลังจากอัปเดตเสร็จ
                refreshInterval = setInterval(loadDashboard, 1000);
            },
            error: function() {
                // Revert toggle on error
                $toggle.prop('checked', !$toggle.is(':checked'));
                alert('เกิดข้อผิดพลาดในการเปลี่ยนสถานะ');
                
                // เริ่ม auto-refresh ใหม่แม้จะเกิดข้อผิดพลาด
                refreshInterval = setInterval(loadDashboard, 1000);
            }
        });
    }
    
    function startCountdown() {
        // Load saved end time if exists
        const savedEndTime = localStorage.getItem('votingEndTime');
        if (savedEndTime) {
            votingEndTime = parseInt(savedEndTime);
        } else {
            votingEndTime = Date.now() + (15 * 60 * 1000);
            localStorage.setItem('votingEndTime', votingEndTime);
        }
        
        updateCountdownDisplay();
        
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        
        countdownInterval = setInterval(function() {
            updateCountdownDisplay();
        }, 1000);
    }
    
    function stopCountdown() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
            countdownInterval = null;
        }
    }
    
    function resetCountdown() {
        remainingSeconds = 15 * 60;
        $('#countdown').text('15:00').css('color', '#22c55e');
        $('#countdownMin').text('15:00').css('color', '#22c55e');
    }
    
    function updateCountdownDisplay() {
        remainingSeconds = Math.floor((votingEndTime - Date.now()) / 1000);
        
        if (remainingSeconds <= 0) {
            remainingSeconds = 0;
            stopCountdown();
            // Auto-close voting
            autoCloseVoting();
        }
        
        const minutes = Math.floor(remainingSeconds / 60);
        const seconds = remainingSeconds % 60;
        const timeString = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        
        // Change color based on remaining time
        let color = '#22c55e'; // green
        if (remainingSeconds <= 60) {
            color = '#ef4444'; // red
        } else if (remainingSeconds <= 300) {
            color = '#f59e0b'; // yellow/orange
        }
        
        $('#countdown').text(timeString).css('color', color);
        $('#countdownMin').text(timeString).css('color', color);
    }
    
    function autoCloseVoting() {
        if (currentEventStatus === 1) {
            // Show closing animation first
            showClosingAnimation();
            
            // Then close voting after animation
            setTimeout(function() {
                $.ajax({
                    url: '/User/IT/SpecialEvent/updateEventStatus',
                    type: 'POST',
                    data: {
                        event_status: 0
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            currentEventStatus = 0;
                            $('#voteToggle').prop('checked', false);
                            $('#toggleStatusText').text('ปิด');
                            resetCountdown();
                            localStorage.removeItem('votingEndTime');
                        }
                    }
                });
            }, 1000); // Wait 1 second before closing
        }
    }
    
    function showClosingAnimation() {
        const $overlay = $('#closingOverlay');
        $overlay.css('display', 'flex');
        
        // Create confetti particles
        for (let i = 0; i < 50; i++) {
            setTimeout(function() {
                const confetti = $('<div class="confetti"></div>');
                const colors = ['#fbbf24', '#ef4444', '#3b82f6', '#10b981', '#8b5cf6'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                const randomX = Math.random() * 100;
                const randomDelay = Math.random() * 0.5;
                
                confetti.css({
                    'left': randomX + '%',
                    'background': randomColor,
                    'animation-delay': randomDelay + 's'
                });
                
                $overlay.append(confetti);
            }, i * 20);
        }
        
        // Hide overlay after 4 seconds
        setTimeout(function() {
            $overlay.fadeOut(1000, function() {
                $overlay.css('display', 'none');
                $overlay.find('.confetti').remove();
            });
        }, 4000);
    }
    
    function updateStats(stats) {
        $('#totalVoters').text(stats.total_voters || 0);
        $('#totalImages').text(stats.total_images || 0);
    }
    
    function updateCategoryData(category, data) {
        if (data.length === 0) {
            $(`#leader-${category}`).html(`
                <div class="text-center py-8">
                    <i class="fas fa-image text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">ยังไม่มีข้อมูลการโหวต</p>
                </div>
            `);
            $(`#tbody-${category}`).html('');
            return;
        }
        
        // เช็คว่าข้อมูลเปลี่ยนแปลงหรือไม่
        const categoryKey = category;
        const currentDataString = JSON.stringify(data);
        
        // ถ้าข้อมูลไม่เปลี่ยน ไม่ต้อง render ใหม่
        if (previousData[categoryKey] === currentDataString) {
            return;
        }
        
        previousData[categoryKey] = currentDataString;
        
        // Check if votes changed for animation
        const currentVoteData = {};
        data.forEach(item => {
            currentVoteData[item.id] = item.vote_count;
        });
        
        const hasVoteChange = previousVotes[categoryKey] && 
            JSON.stringify(previousVotes[categoryKey]) !== JSON.stringify(currentVoteData);
        
        previousVotes[categoryKey] = currentVoteData;
        
        // Display leader (top 1)
        const leader = data[0];
        const leaderColor = category === 'เดี่ยวชาย' ? 'amber' : (category === 'เดี่ยวหญิง' ? 'red' : 'purple');
        const leaderGradient = category === 'เดี่ยวชาย' ? 'from-yellow-400 to-amber-500' : (category === 'เดี่ยวหญิง' ? 'from-red-500 to-rose-600' : 'from-purple-600 to-indigo-600');
        // <i class="fas fa-crown text-4xl text-yellow-500 crown"></i>
        console.log(leader.image_path)
        $(`#leader-${category}`).html(`
            <div class="leader-card max-w-2xl mx-auto">
                <div class="text-center mb-3">
                    <h3 class="text-xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r ${leaderGradient} mt-2">🏆 อันดับ 1 🏆</h3>
                </div>
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border-4 border-${leaderColor}-200">
                    <img src="/${leader.image_path}" alt="${leader.post_name}" class="w-full h-64 object-cover">
                    <div class="p-4 bg-gradient-to-br from-white to-${leaderColor}-50">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div>
                                <h4 class="text-2xl font-bold text-gray-800">${leader.post_name || 'ไม่ระบุชื่อ'}</h4>
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-layer-group mr-1"></i>${category}
                                </p>
                            </div>
                            <div class="text-right">
                                <div class="vote-count bg-gradient-to-r ${leaderGradient} text-white px-8 py-4 rounded-2xl shadow-2xl border-2 border-white ${hasVoteChange ? 'vote-pulse' : ''}">
                                    <i class="fas fa-heart mr-2 text-2xl"></i>
                                    <span class="text-4xl font-extrabold">${leader.vote_count}</span>
                                    <span class="text-base ml-2">โหวต</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `);
        
        // Display other entries in table
        let tableRows = '';
        const badgeColor = category === 'เดี่ยวชาย' ? 'amber' : (category === 'เดี่ยวหญิง' ? 'red' : 'purple');
        
        for (let i = 1; i < data.length; i++) {
            const item = data[i];
            const rank = i + 1;
            const medalIcon = rank === 2 ? '<i class="fas fa-medal text-gray-400 text-xl"></i>' : (rank === 3 ? '<i class="fas fa-medal text-amber-600 text-xl"></i>' : rank);
            
            tableRows += `
                <tr class="hover:bg-${badgeColor}-50 transition-colors">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-${badgeColor}-100 to-${badgeColor}-200 font-bold text-${badgeColor}-700 shadow-md">
                            ${medalIcon}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <img src="/${item.image_path}" alt="${item.post_name}" class="w-20 h-20 object-cover rounded-xl shadow-md border-2 border-${badgeColor}-200">
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-bold text-gray-800 text-lg">${item.post_name || 'ไม่ระบุชื่อ'}</p>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <span class="inline-flex items-center gap-2 bg-gradient-to-r from-${badgeColor}-100 to-${badgeColor}-200 text-${badgeColor}-800 px-5 py-3 rounded-xl font-bold shadow-md text-lg">
                            <i class="fas fa-heart text-${badgeColor}-600"></i>
                            ${item.vote_count}
                        </span>
                    </td>
                </tr>
            `;
        }
        
        $(`#tbody-${category}`).html(tableRows || `
            <tr>
                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                    <p>ไม่มีข้อมูลเพิ่มเติม</p>
                </td>
            </tr>
        `);
    }
    
    // Clear interval on page unload
    $(window).on('beforeunload', function() {
        clearInterval(refreshInterval);
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
        // Clear all scroll intervals
        Object.values(scrollIntervals).forEach(interval => clearInterval(interval));
    });
</script>
</body>
</html>