<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('build/images/wge-favicon-small.png')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>ลงคะแนนโหวต - งานปีใหม่</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Swiper -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }
        
        .category-tab {
            background: #f3f4f6;
            color: #6b7280;
        }
        .category-tab.active {
            background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
        }
        
        .image-card {
            position: relative;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            transition: all 0.3s;
        }
        
        .image-card.selected {
            box-shadow: 0 0 0 4px #10b981;
            transform: scale(0.98);
        }
        
        .image-card img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
        
        .image-loading {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1.5rem;
        }
        
        .image-loading i {
            font-size: 3rem;
            color: #9ca3af;
            animation: pulse 2s infinite;
        }
        
        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        .image-card img.loaded {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .vote-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem;
        }
        
        .selected-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: #10b981;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-weight: bold;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5);
            display: none;
        }
        
        .image-card.selected .selected-badge {
            display: block;
            animation: bounceIn 0.5s;
        }
        
        @keyframes bounceIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        
        .swiper {
            width: 100%;
            padding-bottom: 50px !important;
        }
        
        .swiper-pagination-bullet {
            width: 12px;
            height: 12px;
            background: white;
            opacity: 0.5;
        }
        
        .swiper-pagination-bullet-active {
            background: white;
            opacity: 1;
        }
        
        /* Swipe Instruction Overlay */
        .swipe-instruction {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 100;
            text-align: center;
            pointer-events: none;
            transition: opacity 0.5s ease;
        }
        
        .swipe-instruction.hidden {
            opacity: 0;
            pointer-events: none;
        }
        
        .swipe-arrows {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        @keyframes swipeLeft {
            0%, 100% { transform: translateX(0); opacity: 1; }
            50% { transform: translateX(-30px); opacity: 0.3; }
        }
        
        @keyframes swipeRight {
            0%, 100% { transform: translateX(0); opacity: 1; }
            50% { transform: translateX(30px); opacity: 0.3; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        @keyframes fadeInOut {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }
        
        .arrow-left {
            animation: swipeLeft 1.5s ease-in-out infinite;
        }
        
        .arrow-right {
            animation: swipeRight 1.5s ease-in-out infinite;
        }
        
        .swipe-text {
            animation: fadeInOut 2s ease-in-out infinite;
        }
        
        .hand-icon {
            animation: pulse 1.5s ease-in-out infinite;
        }
    </style>
</head>
<body>
<div class="min-h-screen bg-gradient-to-br from-blue-400 via-blue-200 to-white">
    <!-- PIN Entry Screen -->
    <div id="pinScreen" class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md p-8">
            <div class="text-center mb-8">
                <div class="mb-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-400 blur-3xl opacity-20 animate-pulse"></div>
                    <img src="/images/Logo-Well-Graded 2025.png" alt="Well Graded Logo" class="relative mx-auto h-20 object-contain drop-shadow-xl">
                </div>
                <div class="space-y-3">
                    <h1 class="text-4xl font-extrabold bg-gradient-to-r from-blue-600 via-blue-500 to-blue-400 bg-clip-text text-transparent">
                        New Year Night Party 2026
                    </h1>
                    
                    <!-- Event Closed Warning -->
                    <div id="eventClosedWarning" class="hidden">
                        <div class="inline-block px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-full shadow-lg animate-pulse">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-lock text-xl"></i>
                                <span class="font-bold text-lg">ยังไม่เปิดให้ร่วมการโหวต</span>
                                <i class="fas fa-lock text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-red-600 mt-2 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>
                            กรุณารอการแจ้งเตือนจากเจ้าหน้าที่
                        </p>
                    </div>
                    
                    <div class="flex items-center justify-center gap-2 text-gray-600" id="normalInstructions">
                        <div class="h-px w-12 bg-gradient-to-r from-transparent to-blue-300"></div>
                        <i class="fas fa-ticket-alt text-blue-500"></i>
                        <span class="font-medium">กรุณากรอกเลขท้ายบัตร 3 หลัก <br> เพื่อยืนยันตัวตน</span>
                        <i class="fas fa-ticket-alt text-blue-400"></i>
                        <div class="h-px w-12 bg-gradient-to-l from-transparent to-blue-300"></div>
                    </div>
                </div>
            </div>

            <!-- Error Message -->
            <div id="errorMessage" class="hidden mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg">
                <i class="fas fa-exclamation-circle mr-2"></i><span id="errorText"></span>
            </div>

            <div class="mb-8">
                <div class="flex justify-center gap-4 mb-6">
                    <input type="text" id="pin1" maxlength="1" class="w-16 h-20 text-center text-3xl font-bold border-2 border-gray-300 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" id="pin2" maxlength="1" class="w-16 h-20 text-center text-3xl font-bold border-2 border-gray-300 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all" inputmode="numeric" pattern="[0-9]*">
                    <input type="text" id="pin3" maxlength="1" class="w-16 h-20 text-center text-3xl font-bold border-2 border-gray-300 rounded-2xl focus:border-blue-500 focus:ring-4 focus:ring-blue-200 transition-all" inputmode="numeric" pattern="[0-9]*">
                </div>

                <button id="verifyBtn" class="w-full py-4 bg-gradient-to-r from-blue-500 to-blue-400 text-white font-bold text-lg rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                    <i class="fas fa-check-circle mr-2"></i>ยืนยัน
                </button>
            </div>
        </div>
    </div>

    <!-- Voting Screen -->
    <div id="votingScreen" class="hidden min-h-screen p-4 pb-32">
        <div class="max-w-md mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-3xl shadow-lg p-3 mb-3">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">โหวตภาพที่ชอบ ทั้ง 3 หมวด</h2>
                        <p class="text-xs text-gray-600">เลขบัตร: <span id="cardNumber" class="font-bold text-blue-600"></span></p>
                    </div>
                    <div class="text-right">
                        <div class="text-xl font-bold text-blue-600" id="voteCount">0/3</div>
                        <div class="text-xs text-gray-500">โหวตแล้ว</div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div id="progressBar" class="h-full bg-gradient-to-r from-blue-500 to-blue-400 transition-all duration-300" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="bg-white rounded-3xl shadow-lg p-3 mb-3">
                <div class="flex gap-2">
                    <button class="category-tab flex-1 py-2 px-3 rounded-xl font-semibold text-sm transition-all" data-category="เดี่ยวชาย">
                        <i class="fas fa-male mr-1"></i>ชายเดี่ยว
                    </button>
                    <button class="category-tab flex-1 py-2 px-3 rounded-xl font-semibold text-sm transition-all" data-category="เดี่ยวหญิง">
                        <i class="fas fa-female mr-1"></i>หญิงเดี่ยว
                    </button>
                    <button class="category-tab flex-1 py-2 px-3 rounded-xl font-semibold text-sm transition-all" data-category="แบบกลุ่ม">
                        <i class="fas fa-users mr-1"></i>แบบกลุ่ม
                    </button>
                </div>
            </div>

            <!-- Images Container -->
            <div id="imagesContainer" class="bg-white rounded-3xl shadow-lg p-6 mb-4 relative">
                <!-- Swipe Instruction Overlay -->
                <div id="swipeInstruction" class="swipe-instruction">
                    <div class="bg-blue-600 text-white px-8 py-6 rounded-3xl shadow-2xl backdrop-blur-lg bg-opacity-95">
                        <div class="swipe-arrows">
                            <i class="fas fa-chevron-left text-4xl arrow-left text-white"></i>
                            <div class="hand-icon">
                                <i class="fas fa-hand-pointer text-5xl text-yellow-300"></i>
                            </div>
                            <i class="fas fa-chevron-right text-4xl arrow-right text-white"></i>
                        </div>
                        <div class="swipe-text">
                            <p class="text-2xl font-bold mb-2">ปัดเพื่อดูรูปภาพ</p>
                            <p class="text-sm opacity-90">แตะรูปที่ชอบเพื่อโหวต</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-3"></i>
                    <p class="text-gray-600">กำลังโหลดรูปภาพ...</p>
                </div>
            </div>
        </div>

        <!-- Submit Button (Fixed at bottom) -->
        <div class="fixed bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-white via-white/95 to-transparent shadow-2xl" style="z-index: 999;">
            <div class="max-w-md mx-auto">
                <button id="submitVoteBtn" disabled class="w-full py-5 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold text-xl rounded-2xl shadow-2xl disabled:opacity-50 disabled:cursor-not-allowed hover:shadow-3xl transform hover:scale-105 transition-all">
                    <i class="fas fa-paper-plane mr-2"></i>ยืนยันการโหวต
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    let cardNumber = '';
    let currentCategory = 'เดี่ยวชาย';
    let imagesData = [];
    let votes = {
        'เดี่ยวชาย': null,
        'เดี่ยวหญิง': null,
        'แบบกลุ่ม': null
    };
    let swiper = null;
    const eventStatus = {{ $eventStatus ?? 0 }}; // 0 = ปิดโหวต, 1 = เปิดโหวต

    // ฟังก์ชันจัดการเมื่อรูปโหลดเสร็จ
    window.imageLoaded = function(img) {
        const $img = $(img);
        const $loading = $img.siblings('.image-loading');
        
        $loading.fadeOut(200, function() {
            $img.addClass('loaded').fadeIn(300);
            $(this).remove();
        });
    };

    // ฟังก์ชันจัดการเมื่อรูปโหลดไม่สำเร็จ
    window.imageError = function(img) {
        const $img = $(img);
        const $loading = $img.siblings('.image-loading');
        
        $loading.html('<div class="text-center"><i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-2"></i><p class="text-gray-500 text-sm">โหลดรูปไม่สำเร็จ</p></div>');
    };

    $(document).ready(function() {
        console.log(eventStatus)
        // ตรวจสอบสถานะการเปิดโหวตก่อน
        if (eventStatus === 0) {
            showEventClosedMessage();
            return;
        } else {
            // แสดงคำแนะนำปกติ
            $('#normalInstructions').removeClass('hidden');
        }
        
        setupPinInput();
        setupCategoryTabs();
        
        $('#verifyBtn').on('click', verifyCardNumber);
        $('#submitVoteBtn').on('click', submitVotes);
    });

    function showEventClosedMessage() {
        // แสดง warning message และซ่อนคำแนะนำปกติ
        $('#eventClosedWarning').removeClass('hidden');
        $('#normalInstructions').addClass('hidden');
        
        // ปิดการใช้งาน input fields
        $('#pin1, #pin2, #pin3').prop('disabled', true).addClass('bg-gray-100 cursor-not-allowed');
        $('#verifyBtn').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
    }

    function setupPinInput() {
        const pins = ['pin1', 'pin2', 'pin3'];
        
        pins.forEach((pinId, index) => {
            // Clear value when clicking/focusing on a filled input
            $(`#${pinId}`).on('focus click', function() {
                if (this.value) {
                    this.value = '';
                }
            });
            
            $(`#${pinId}`).on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
                if (this.value.length === 1 && index < 2) {
                    $(`#${pins[index + 1]}`).focus();
                }
            });
            
            $(`#${pinId}`).on('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    $(`#${pins[index - 1]}`).focus();
                }
            });
        });
        
        $('#pin1').focus();
    }

    function verifyCardNumber() {
        const pin1 = $('#pin1').val();
        const pin2 = $('#pin2').val();
        const pin3 = $('#pin3').val();
        
        if (!pin1 || !pin2 || !pin3) {
            showError('กรุณากรอกเลขท้ายบัตรให้ครบ 3 หลัก');
            return;
        }
        
        cardNumber = pin1 + pin2 + pin3;
        
        // แสดง loading state
        const $verifyBtn = $('#verifyBtn');
        const originalText = $verifyBtn.html();
        $verifyBtn.prop('disabled', true)
                  .html('<i class="fas fa-spinner fa-spin mr-2"></i>กำลังตรวจสอบ...');
        //loading
        Swal.fire({
            title: 'กำลังตรวจสอบข้อมูล...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            url: '/User/IT/SpecialEvent/verifyCard',
            type: 'POST',
            data: { card_number: cardNumber },
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(response) {
                if (response.status === 200) {
                    Swal.close();
                    // แสดง Modal ยืนยันข้อมูลก่อน
                    Swal.fire({
                        title: '<span style="font-size:1.1rem;">ยืนยันข้อมูลผู้โหวต<br>ห้ามโหวตรหัสผู้อื่น!</span>',
                        html: `
                            <div class="text-left space-y-3 p-4">
                                <div class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg">
                                    <i class="fas fa-user text-purple-600 text-2xl"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">ชื่อ-นามสกุล</p>
                                        <p class="text-lg font-bold text-gray-800">${response.employee.name}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-briefcase text-blue-600 text-2xl"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">ตำแหน่ง/บริษัทฯ</p>
                                        <p class="text-lg font-bold text-gray-800">${response.employee.position || '-'}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 bg-pink-50 rounded-lg">
                                    <i class="fas fa-ticket-alt text-pink-600 text-2xl"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">เลขท้ายบัตร</p>
                                        <p class="text-lg font-bold text-gray-800">${cardNumber}</p>
                                    </div>
                                </div>
                                
                               
                            </div>
                            <p class="text-center text-sm text-gray-600 mt-4">กรุณาตรวจสอบความถูกต้อง</p>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#a855f7',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: '<i class="fas fa-check-circle mr-1"></i>ข้อมูลถูกต้อง เริ่มโหวต',
                        cancelButtonText: '<i class="fas fa-times mr-1"></i>ยกเลิก',
                        width: '500px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#cardNumber').text(cardNumber);
                            $('#pinScreen').addClass('hidden');
                            $('#votingScreen').removeClass('hidden');
                            loadImages();
                        } else {
                            // ยกเลิก - คืนค่าปุ่มกลับเป็นปกติ
                            $verifyBtn.prop('disabled', false).html(originalText);
                        }
                    });
                } else {
                    swal.close()
                    showError(response.message);
                    // คืนค่าปุ่มเมื่อเกิด error
                    
                    $verifyBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                showError('เกิดข้อผิดพลาดในการตรวจสอบ');
                // คืนค่าปุ่มเมื่อเกิด error
                $verifyBtn.prop('disabled', false).html(originalText);
            }
        });
    }

    function showError(message) {
        $('#errorText').text(message);
        $('#errorMessage').removeClass('hidden');
        setTimeout(() => {
            $('#errorMessage').addClass('hidden');
        }, 3000);
    }

    function loadImages() {
        //loading
        swal.fire({
            title: 'กำลังโหลดรูปภาพ...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        $.ajax({
            url: '/User/IT/SpecialEvent/getVoteImages',
            type: 'GET',
            success: function(response) {
                Swal.close();
                if (response.status === 200) {
                    imagesData = response.images;
                    displayImages(currentCategory);
                    $('.category-tab[data-category="เดี่ยวชาย"]').addClass('active');
                    preloadCategoryImages('เดี่ยวชาย');
                }
            },
            error: function() {
                swal.close();
                showError('โหลดข้อมูลรูปภาพไม่สำเร็จ');
            }
        });
    }

    // Preload รูปภาพในหมวดนั้น เพื่อลด bandwidth เมื่อสลับหมวด
    function preloadCategoryImages(category) {
        const categoryImages = imagesData.filter(img => img.category === category);
        
        // Preload แค่ 3 รูปแรก
        categoryImages.slice(0, 3).forEach(img => {
            const preloadLink = document.createElement('link');
            preloadLink.rel = 'preload';
            preloadLink.as = 'image';
            preloadLink.href = img.image_path;
            document.head.appendChild(preloadLink);
        });
    }

    function setupCategoryTabs() {
        $('.category-tab').on('click', function() {
            const category = $(this).data('category');
            currentCategory = category;
            
            $('.category-tab').removeClass('active');
            $(this).addClass('active');
            
            displayImages(category);
            
            // Preload รูปภาพของหมวดนี้
            preloadCategoryImages(category);
        });
    }

    function displayImages(category) {
        const categoryImages = imagesData.filter(img => img.category === category);
        
        if (categoryImages.length === 0) {
            $('#imagesContainer').html(`
                <div class="text-center py-12">
                    <i class="fas fa-image text-gray-300 text-5xl mb-3"></i>
                    <p class="text-gray-500">ไม่มีรูปภาพในหมวดนี้</p>
                </div>
            `);
            return;
        }
        
        let slides = '';
        categoryImages.forEach(img => {
            const isSelected = votes[category] === img.id ? 'selected' : '';
            slides += `
                <div class="swiper-slide">
                    <div class="image-card ${isSelected}" data-id="${img.id}" data-category="${category}">
                        <div class="image-loading">
                            <i class="fas fa-image"></i>
                        </div>
                        <img src="${img.image_path}" alt="${img.post_name}" style="display: none;" onload="imageLoaded(this)" onerror="imageError(this)">
                        <div class="vote-overlay">
                            <div class="selected-badge">
                                <i class="fas fa-check-circle mr-1"></i>เลือกแล้ว
                            </div>
                            <div>
                                <div class="inline-block bg-black bg-opacity-70 px-4 py-2 rounded-xl">
                                    <h3 class="text-2xl font-bold mb-1 text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">${img.post_name || 'ไม่ระบุชื่อ'}</h3>
                                    <p class="text-sm text-white opacity-90" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.8);"><i class="fas fa-layer-group mr-1"></i>${img.category}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#imagesContainer').html(`
            <!-- Swipe Instruction Overlay -->
            <div id="swipeInstruction" class="swipe-instruction">
                <div class="bg-blue-600 text-white px-8 py-6 rounded-3xl shadow-2xl backdrop-blur-lg bg-opacity-95">
                    <div class="swipe-arrows">
                        <i class="fas fa-chevron-left text-4xl arrow-left text-white"></i>
                        <div class="hand-icon">
                            <i class="fas fa-hand-pointer text-5xl text-yellow-300"></i>
                        </div>
                        <i class="fas fa-chevron-right text-4xl arrow-right text-white"></i>
                    </div>
                    <div class="swipe-text">
                        <p class="text-2xl font-bold mb-2">ปัดเพื่อดูรูปภาพ</p>
                        <p class="text-sm opacity-90">แตะรูปที่ชอบเพื่อโหวต</p>
                    </div>
                </div>
            </div>
            
            <div class="swiper">
                <div class="swiper-wrapper">${slides}</div>
                <div class="swiper-pagination"></div>
            </div>
            
            <!-- Permanent Instruction Below Images -->
            <div class="mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-200">
                <div class="flex items-center justify-center gap-3 text-center">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-hand-pointer text-blue-600 text-xl"></i>
                        <span class="text-blue-700 font-semibold text-sm">ปัดซ้าย-ขวาเพื่อเลื่อนดูรูป</span>
                    </div>
                    <div class="w-px h-6 bg-blue-300"></div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-mouse-pointer text-indigo-600 text-xl"></i>
                        <span class="text-indigo-700 font-semibold text-sm">แตะเพื่อโหวต</span>
                    </div>
                </div>
            </div>
        `);
        
        initSwiper();
    }

    function initSwiper() {
        if (swiper) {
            swiper.destroy(true, true);
        }
        
        // Show swipe instruction initially
        $('#swipeInstruction').removeClass('hidden');
        
        swiper = new Swiper('.swiper', {
            effect: 'cards',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 1,
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            on: {
                init: function() {
                    bindImageClick();
                },
                slideChange: function() {
                    // Hide instruction after first swipe
                    $('#swipeInstruction').addClass('hidden');
                    bindImageClick();
                },
                touchStart: function() {
                    // Hide instruction on first touch
                    $('#swipeInstruction').addClass('hidden');
                }
            }
        });
        
        // Auto hide instruction after 5 seconds
        setTimeout(function() {
            $('#swipeInstruction').addClass('hidden');
        }, 5000);
    }

    function bindImageClick() {
        $('.image-card').off('click').on('click', function(e) {
            // ถ้า popup ยังแสดงอยู่ ให้ปิด popup ก่อน ไม่เลือกรูป
            const $instruction = $('#swipeInstruction');
            if (!$instruction.hasClass('hidden')) {
                e.stopPropagation();
                $instruction.addClass('hidden');
                return;
            }
            
            const $this = $(this);
            const imageId = $this.data('id');
            const category = $this.data('category');
            
            // ถ้ารูปนี้ถูกเลือกอยู่แล้ว ให้ยกเลิกการเลือก
            if ($this.hasClass('selected')) {
                $this.removeClass('selected');
                votes[category] = null;
            } else {
                // ลบการเลือกออกจากรูปอื่นในหมวดเดียวกัน
                $(`.image-card[data-category="${category}"]`).removeClass('selected');
                
                // เลือกรูปนี้
                $this.addClass('selected');
                votes[category] = imageId;
            }
            
            updateVoteCount();
        });
    }

    function updateVoteCount() {
        const count = Object.values(votes).filter(v => v !== null).length;
        $('#voteCount').text(`${count}/3`);
        $('#progressBar').css('width', `${(count / 3) * 100}%`);
        
        // Enable submit button if all 3 categories are voted
        if (count === 3) {
            $('#submitVoteBtn').prop('disabled', false);
        } else {
            $('#submitVoteBtn').prop('disabled', true);
        }
    }

    function submitVotes() {
        Swal.fire({
            title: 'ยืนยันการโหวต?',
            html: `คุณได้เลือกโหวต 3 รูป<br>เลขบัตร: <strong>${cardNumber}</strong>`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#ef4444',
            confirmButtonText: '<i class="fas fa-check mr-1"></i>ยืนยัน',
            cancelButtonText: '<i class="fas fa-times mr-1"></i>ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // แสดง loading state
                const $submitBtn = $('#submitVoteBtn');
                const originalText = $submitBtn.html();
                $submitBtn.prop('disabled', true)
                          .html('<i class="fas fa-spinner fa-spin mr-2"></i>กำลังบันทึก...');
                
                $.ajax({
                    url: '/User/IT/SpecialEvent/submitVotes',
                    type: 'POST',
                    data: {
                        card_number: cardNumber,
                        votes: votes
                    },
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire({
                                title: 'สำเร็จ!',
                                text: 'ขอบคุณที่ร่วมโหวต',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                timer: 3000
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('ผิดพลาด!', response.message, 'error');
                            // คืนค่าปุ่มเมื่อเกิด error
                            $submitBtn.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function() {
                        Swal.fire('ผิดพลาด!', 'เกิดข้อผิดพลาดในการบันทึก', 'error');
                        // คืนค่าปุ่มเมื่อเกิด error
                        $submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            }
        });
    }
</script>
</body>
</html>
