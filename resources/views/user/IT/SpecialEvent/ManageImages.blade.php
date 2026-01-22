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
    .image-preview {
        transition: transform 0.3s ease;
    }
    
    .image-preview:hover {
        transform: scale(1.05);
    }

    .modal-image {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
    }

    #imagePreview {
        max-width: 100%;
        max-height: 300px;
        object-fit: contain;
    }

    .upload-area {
        border: 2px dashed #cbd5e1;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .upload-area.dragging {
        border-color: #2563eb;
        background-color: #dbeafe;
    }
</style>
</head>
<body>
 <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200">
        <nav class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-2">
                <a href="/SpecialEvent/Dashboard" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-sm hover:shadow-md">
                    <span>Dashboard</span>
                </a>
                 <a href="/SpecialEvent/VoteRegister" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-sm hover:shadow-md">
                    <span>จัดการผู้ลงทะเบียน</span>
                </a>
                {{-- จัดการรูปภาพ --}}
                <a href="/User/IT/SpecialEvent/MangeImage" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-sm hover:shadow-md">
                    <span>จัดการรูปภาพ</span>
                </a>
            </div>
        </nav>
    </div>
<div class="pt-6">
    <!-- Header Section -->
    <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-images text-white text-lg"></i>
                    </span>
                    จัดการรูปภาพ - งานพิเศษ
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Special Event Image Management - ระบบจัดการรูปภาพประกวด</p>
            </div>
            <div class="flex flex-wrap gap-2">
                {{-- <a href="/SpecialEvent/Dashboard" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-chart-line"></i> Dashboard
                </a> --}}
                <button type="button" onclick="openUploadModal()" class="inline-flex items-center gap-2 px-3 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-upload"></i> อัพโหลดรูปภาพ
                </button>
                {{-- <a href="{{ route('SpecialEvent.exportImagesExcel') }}" class="inline-flex items-center gap-2 px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a> --}}
                <button type="button" onclick="loadImages()" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium border border-gray-300">
                    <i class="fas fa-sync-alt"></i> รีเฟรช
                </button>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-search text-purple-600 mr-1"></i> ค้นหา
                </label>
                <input type="text" id="searchImage" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors" placeholder="ค้นหาด้วยหมวด...">
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-layer-group text-purple-600 mr-1"></i> หมวด
                </label>
                <select id="filterCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors bg-white">
                    <option value="">ทุกหมวด</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-images text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">รูปทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalImages">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">จำนวนหมวด</p>
                    <p class="text-2xl font-bold text-blue-600" id="totalCategories">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">อัพเดทล่าสุด</p>
                    <p class="text-sm font-bold text-green-600" id="lastUpdate">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Images Table Section -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-4 py-3 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-table text-purple-600"></i>
                    <h2 class="text-base font-semibold text-gray-800">คลังรูปภาพ</h2>
                </div>
                <span class="text-sm text-gray-600">แสดง <span id="displayCount">0</span> รายการ</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-32">รูปภาพ</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">หมวดหมู่</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ชื่อผู้ Post</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">คะแนนโหวต</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">วันที่อัปโหลด</th>
                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-48">จัดการ</th>
                    </tr>
                </thead>
                <tbody id="imagesContainer" class="divide-y divide-gray-200">
                    <!-- Loading state -->
                    <tr>
                        <td colspan="6" class="text-center py-12">
                            <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-3"></i>
                            <p class="text-gray-600">กำลังโหลดรูปภาพ...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Upload Modal -->
    <div id="uploadModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-upload text-purple-600 mr-2"></i>อัพโหลดรูปภาพใหม่
                </h3>
                <button type="button" onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="uploadForm" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-layer-group text-purple-600 mr-1"></i>หมวดหมู่ <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="block">
                            <input type="radio" name="uploadCategory" value="เดี่ยวชาย" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 transition-all peer-checked:bg-purple-600 peer-checked:border-purple-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-male text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">เดี่ยวชาย</p>
                            </div>
                        </label>
                        <label class="block">
                            <input type="radio" name="uploadCategory" value="เดี่ยวหญิง" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 transition-all peer-checked:bg-purple-600 peer-checked:border-purple-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-female text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">เดี่ยวหญิง</p>
                            </div>
                        </label>
                        <label class="block">
                            <input type="radio" name="uploadCategory" value="แบบกลุ่ม" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-purple-400 transition-all peer-checked:bg-purple-600 peer-checked:border-purple-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">แบบกลุ่ม</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-purple-600 mr-1"></i>ชื่อผู้ Post <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="postName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent" placeholder="กรอกชื่อผู้ post" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image text-purple-600 mr-1"></i>รูปภาพ <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="file" id="imageFile" accept="image/*" class="hidden" required>
                        <button type="button" id="browseImageBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium border border-gray-300">
                            <i class="fas fa-folder-open mr-2"></i>เลือกไฟล์
                        </button>
                        <span id="selectedFileName" class="text-sm text-gray-600">ยังไม่ได้เลือกไฟล์</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">รองรับ: JPG, PNG, GIF (ไม่เกิน 10MB)</p>
                    <div id="imagePreviewContainer" class="hidden mt-4 text-center">
                        <img id="imagePreview" class="mx-auto rounded-lg shadow-md" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeUploadModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors font-medium">
                        <i class="fas fa-upload mr-1"></i>อัพโหลด
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-edit text-blue-600 mr-2"></i>แก้ไขรูปภาพ
                </h3>
                <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="editForm" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" id="editImageId">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">รูปปัจจุบัน</label>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <img id="currentImage" class="modal-image mx-auto rounded-lg shadow-md">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-layer-group text-blue-600 mr-1"></i>หมวดหมู่ <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="block">
                            <input type="radio" name="editCategory" value="เดี่ยวชาย" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-male text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">เดี่ยวชาย</p>
                            </div>
                        </label>
                        <label class="block">
                            <input type="radio" name="editCategory" value="เดี่ยวหญิง" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-female text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">เดี่ยวหญิง</p>
                            </div>
                        </label>
                        <label class="block">
                            <input type="radio" name="editCategory" value="แบบกลุ่ม" required class="peer sr-only">
                            <div class="flex flex-col items-center justify-center p-4 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-all peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white peer-checked:shadow-lg">
                                <i class="fas fa-users text-3xl mb-2"></i>
                                <p class="text-sm font-semibold">แบบกลุ่ม</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user text-blue-600 mr-1"></i>ชื่อผู้ Post <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="editPostName" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="กรอกชื่อผู้ post" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-image text-blue-600 mr-1"></i>เปลี่ยนรูปภาพ (ถ้าต้องการ)
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="file" id="editImageFile" accept="image/*" class="hidden">
                        <button type="button" id="browseEditImageBtn" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium border border-gray-300">
                            <i class="fas fa-folder-open mr-2"></i>เลือกไฟล์ใหม่
                        </button>
                        <span id="selectedEditFileName" class="text-sm text-gray-600">ไม่ได้เลือกไฟล์ใหม่</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">หากไม่เลือกจะใช้รูปเดิม</p>
                    <div id="editImagePreviewContainer" class="hidden mt-4 text-center">
                        <img id="editImagePreview" class="mx-auto rounded-lg shadow-md" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-1"></i>บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let imagesData = [];
    let filteredData = [];
    let uploadAreaInitialized = false;
    let editUploadAreaInitialized = false;

    $(document).ready(function() {
        loadImages();
        initUploadAreas();
        
        // Event listeners
        $('#searchImage').on('input', function() {
            filterImages();
        });
        
        $('#filterCategory').on('change', function() {
            filterImages();
        });
    });

    function loadImages() {
        $('#imagesContainer').html(`
            <tr>
                <td colspan="6" class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-3"></i>
                    <p class="text-gray-600">กำลังโหลดรูปภาพ...</p>
                </td>
            </tr>
        `);
        
        $.ajax({
            url: '/User/IT/SpecialEvent/getImages',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status === 200) {
                    imagesData = response.images;
                    filteredData = imagesData;
                    updateCategoryFilter();
                    displayImages(filteredData);
                    updateStatistics();
                } else {
                    showError('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
            }
        });
    }

    function updateCategoryFilter() {
        const categories = [...new Set(imagesData.map(img => img.category))].filter(Boolean);
        const $select = $('#filterCategory');
        $select.find('option:not(:first)').remove();
        
        categories.forEach(cat => {
            $select.append(`<option value="${cat}">${cat}</option>`);
        });
    }

    function filterImages() {
        const searchText = $('#searchImage').val().toLowerCase().trim();
        const selectedCategory = $('#filterCategory').val();
        
        filteredData = imagesData.filter(img => {
            const matchSearch = !searchText || 
                img.category.toLowerCase().includes(searchText);
            
            const matchCategory = !selectedCategory || img.category === selectedCategory;
            
            return matchSearch && matchCategory;
        });
        
        displayImages(filteredData);
    }

    function displayImages(data) {
        const $container = $('#imagesContainer');
        $container.empty();
        
        if (data.length === 0) {
            $container.html(`
                <tr>
                    <td colspan="6" class="text-center py-12 text-gray-500">
                        <i class="fas fa-images text-6xl mb-3 text-gray-300"></i>
                        <p class="text-lg">ไม่พบรูปภาพ</p>
                    </td>
                </tr>
            `);
            $('#displayCount').text('0');
            return;
        }
        
        data.forEach(img => {
            const row = `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3">
                        <img src="/${img.image_path}" alt="${img.category}" class="w-20 h-20 object-cover rounded-lg shadow-sm cursor-pointer hover:shadow-md transition-shadow" onclick="viewImage('${img.image_path}', '${img.category}')">
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-layer-group text-purple-600"></i>
                            <span class="font-medium text-gray-800">${img.category}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-user text-blue-600"></i>
                            <span class="text-gray-700">${img.post_name || '-'}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <i class="fas fa-vote-yea mr-1"></i> ${img.vote_count || 0}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-600 text-sm">
                        ${formatDateTime(img.created_at)}
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <button onclick="openEditModalWithData(${img.id})" class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-medium">
                                <i class="fas fa-edit"></i> แก้ไข
                            </button>
                            <button onclick="deleteImage(${img.id}, '${img.category}')" class="px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-medium">
                                <i class="fas fa-trash"></i> ลบ
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            $container.append(row);
        });
        
        $('#displayCount').text(data.length);
    }

    function updateStatistics() {
        const total = imagesData.length;
        const categories = [...new Set(imagesData.map(img => img.category))].length;
        const lastUpdate = imagesData.length > 0 ? formatDateTime(imagesData[0].updated_at) : '-';
        
        $('#totalImages').text(total);
        $('#totalCategories').text(categories);
        $('#lastUpdate').text(lastUpdate);
    }

    function initUploadAreas() {
        if (uploadAreaInitialized && editUploadAreaInitialized) return;
        
        if (!uploadAreaInitialized) {
            setupUploadArea();
            uploadAreaInitialized = true;
        }
        
        if (!editUploadAreaInitialized) {
            setupEditUploadArea();
            editUploadAreaInitialized = true;
        }
    }

    function setupUploadArea() {
        const $browseBtn = $('#browseImageBtn');
        const $fileInput = $('#imageFile');
        
        $browseBtn.off('click').on('click', function() {
            $fileInput.click();
        });
        
        $fileInput.off('change').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                $('#selectedFileName').text(file.name);
                handleFileSelect(file, 'imagePreview', 'imagePreviewContainer');
            }
        });
    }

    function setupEditUploadArea() {
        const $browseBtn = $('#browseEditImageBtn');
        const $fileInput = $('#editImageFile');
        
        $browseBtn.off('click').on('click', function() {
            $fileInput.click();
        });
        
        $fileInput.off('change').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                $('#selectedEditFileName').text(file.name);
                handleFileSelect(file, 'editImagePreview', 'editImagePreviewContainer');
            }
        });
    }

    function handleFileSelect(file, previewId, containerId) {
        if (!file) return;
        
        if (!file.type.startsWith('image/')) {
            showError('กรุณาเลือกไฟล์รูปภาพเท่านั้น');
            return;
        }
        
        if (file.size > 10 * 1024 * 1024) {
            showError('ไฟล์ใหญ่เกินไป (ไม่เกิน 10MB)');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            $(`#${previewId}`).attr('src', e.target.result);
            $(`#${containerId}`).removeClass('hidden');
        };
        reader.readAsDataURL(file);
    }

    function openUploadModal() {
        $('#uploadModal').removeClass('hidden');
        $('#uploadForm')[0].reset();
        $('#postName').val('');
        $('#selectedFileName').text('ยังไม่ได้เลือกไฟล์');
        $('#imagePreviewContainer').addClass('hidden');
    }

    function closeUploadModal() {
        $('#uploadModal').addClass('hidden');
        $('#uploadForm')[0].reset();
        $('#selectedFileName').text('ยังไม่ได้เลือกไฟล์');
        $('#imagePreviewContainer').addClass('hidden');
    }

    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        const category = $('input[name="uploadCategory"]:checked').val();
        const postName = $('#postName').val().trim();
        const fileInput = $('#imageFile')[0];
        
        if (!category || !postName || !fileInput.files[0]) {
            showError('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }
        
        const formData = new FormData();
        formData.append('category', category);
        formData.append('post_name', postName);
        formData.append('image', fileInput.files[0]);
        
        $.ajax({
            url: '/User/IT/SpecialEvent/uploadImage',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    closeUploadModal();
                    loadImages();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'อัพโหลดสำเร็จ!',
                        text: 'เพิ่มรูปภาพเรียบร้อยแล้ว',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาด');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถอัพโหลดได้');
            }
        });
    });

    function openEditModalWithData(imageId) {
        const image = imagesData.find(img => img.id === imageId);
        if (!image) return;
        
        $('#editImageId').val(image.id);
        $(`input[name="editCategory"][value="${image.category}"]`).prop('checked', true);
        $('#editPostName').val(image.post_name || '');
        $('#currentImage').attr('src', '/'+image.image_path);
        $('#selectedEditFileName').text('ไม่ได้เลือกไฟล์ใหม่');
        $('#editImagePreviewContainer').addClass('hidden');
        $('#editModal').removeClass('hidden');
    }

    function closeEditModal() {
        $('#editModal').addClass('hidden');
        $('#editForm')[0].reset();
        $('#selectedEditFileName').text('ไม่ได้เลือกไฟล์ใหม่');
        $('#editImagePreviewContainer').addClass('hidden');
    }

    $('#editForm').on('submit', function(e) {
        e.preventDefault();
        
        const imageId = $('#editImageId').val();
        const category = $('input[name="editCategory"]:checked').val();
        const postName = $('#editPostName').val().trim();
        const fileInput = $('#editImageFile')[0];
        
        if (!category || !postName) {
            showError('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }
        
        const formData = new FormData();
        formData.append('id', imageId);
        formData.append('category', category);
        formData.append('post_name', postName);
        if (fileInput.files[0]) {
            formData.append('image', fileInput.files[0]);
        }
        
        $.ajax({
            url: '/User/IT/SpecialEvent/updateImage',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    closeEditModal();
                    loadImages();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'แก้ไขสำเร็จ!',
                        text: 'อัพเดทข้อมูลเรียบร้อยแล้ว',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาด');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถแก้ไขได้');
            }
        });
    });

    function deleteImage(imageId, category) {
        Swal.fire({
            title: 'ยืนยันการลบ',
            html: `<p>ต้องการลบรูปภาพในหมวด <strong>${category}</strong> หรือไม่?</p><p class="text-red-500 text-sm mt-2">⚠️ ข้อมูลที่ลบแล้วไม่สามารถกู้คืนได้</p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-trash"></i> ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/User/IT/SpecialEvent/deleteImage',
                    type: 'POST',
                    data: { id: imageId },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            loadImages();
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบสำเร็จ!',
                                text: 'ลบรูปภาพเรียบร้อยแล้ว',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            showError(response.message || 'เกิดข้อผิดพลาด');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        showError('ไม่สามารถลบได้');
                    }
                });
            }
        });
    }

    function viewImage(imagePath, category) {
        Swal.fire({
            title: category,
            imageUrl: imagePath,
            imageAlt: category,
            showCloseButton: true,
            showConfirmButton: false,
            width: '80%',
            customClass: {
                image: 'modal-image'
            }
        });
    }

    function formatDateTime(dateTimeString) {
        if (!dateTimeString) return '-';
        const date = new Date(dateTimeString);
        return date.toLocaleDateString('th-TH', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: message,
            confirmButtonColor: '#ef4444'
        });
    }
</script>
</body>
</html>