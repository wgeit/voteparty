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
    .time-display {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-check-in {
        transition: all 0.3s ease;
    }

    .btn-check-in:hover {
        transform: scale(1.05);
    }

    .btn-check-in.confirmed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
</style>
</head>
<body class="bg-gray-100 min-h-screen p-6">

<div class="pt-6">
    <!-- Header Section -->
    <!-- Top Navigation Bar -->
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
    <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-10 h-10 bg-blue-900 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-white text-lg"></i>
                    </span>
                    จัดการรายชื่อพนักงาน - งานพิเศษ
                </h1>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Special Event Employee Management - ระบบจัดการเข้างานพิเศษ</p>
            </div>
            <div class="flex flex-wrap gap-2 items-center">
                <a href="{{url('/User/IT/SpecialEvent/Vote')}}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-vote-yea"></i> ไปที่หน้าการโหวต
                </a>
                <button type="button" onclick="openAddEmployeeModal()" class="inline-flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-user-plus"></i> เพิ่มพนักงาน
                </button>
                <button type="button" onclick="openAddContractorModal()" class="inline-flex items-center gap-2 px-3 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors text-sm font-medium shadow-sm">
                    <i class="fas fa-user-tie"></i> เพิ่มผู้รับเหมา
                </button>
                
                <!-- QR Code -->
                <button type="button" onclick="openQRModal()" class="relative group">
                    <div class="flex items-center gap-2 px-3 py-2 bg-purple-100 border-2 border-purple-300 rounded-lg hover:bg-purple-200 transition-all cursor-pointer group-hover:scale-105 transform duration-200">
                        <img src="{{ asset('images/QRnewYear.png') }}" alt="QR Code" class="w-8 h-8 rounded">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-purple-700">QR Code โหวต</span>
                            <span class="text-[10px] text-purple-600">คลิกเพื่อดู</span>
                        </div>
                        <i class="fas fa-qrcode text-purple-600 text-sm ml-1"></i>
                    </div>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">
                        <div class="text-center">
                            <div class="font-semibold">QR Code สำหรับโหวต</div>
                            <div class="text-gray-300">คลิกเพื่อดูและดาวน์โหลด</div>
                        </div>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                </button>
                
                <!-- Event Status Toggle -->
                {{-- <button type="button" onclick="toggleEventStatus()" id="eventStatusBtn" class="relative group inline-flex items-center gap-2 px-3 py-2 rounded-lg transition-all text-sm font-medium shadow-sm">
                    <i id="eventStatusIcon" class="fas fa-lock"></i>
                    <span id="eventStatusText">กำลังโหลด...</span>
                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none whitespace-nowrap z-50">
                        <div class="text-center">
                            <div class="font-semibold">สถานะการโหวต</div>
                            <div class="text-gray-300">คลิกเพื่อเปิด/ปิด</div>
                        </div>
                        <div class="absolute top-full left-1/2 transform -translate-x-1/2 border-4 border-transparent border-t-gray-900"></div>
                    </div>
                </button> --}}
                
                <button type="button" onclick="exportExcel()" class="inline-flex items-center gap-2 px-3 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-medium border border-green-300">
                    <i class="fas fa-file-excel"></i> ส่งออก Excel
                </button>
                <button type="button" onclick="loadEmployeeList()" class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium border border-gray-300">
                    <i class="fas fa-sync-alt"></i> รีเฟรช
                </button>
            </div>
        </div>
    </div>

    <!-- Event Selection Section -->
   
   
    <div id="EventSelection" style="display:none" class="mb-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl shadow-md border-2 border-blue-200 p-4">
        <div class="flex items-center gap-2 mb-3">
            <i class="fas fa-calendar-check text-blue-600 text-lg"></i>
            <h3 class="text-lg font-semibold text-gray-800">เลือก Event</h3>
            <span class="text-xs text-gray-500 ml-2">(*กรุณาเลือกปีและกลุ่มหน่วยงานเพื่อโหลดรายชื่อพนักงาน)</span>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 items-end">
            <div class="w-full sm:flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> ปีกิจกรรม <span class="text-red-500">*</span>
                </label>
                <select name="filterYear" id="filterYear" class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white font-medium">
                    <option value="">-- เลือกปีกิจกรรม --</option>
                    <?php $gettype = '';?>
                    @foreach ($typeEvent->unique('yearEvent')->sortByDesc('yearEvent') as $item)
                        <option value="{{ $item->yearEvent }}" {{ $item->status == 1 ? 'selected' : '' }}>{{ $item->yearEvent }}</option>
                        <?php $gettype = $item->SiteEvent; ?>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-building text-blue-600 mr-1"></i> กลุ่มหน่วยงาน <span class="text-red-500">*</span>
                </label>
                <select name="filterType" id="filterType" class="w-full px-3 py-2 border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white font-medium" >
                    <option value="">-- เลือกกลุ่มหน่วยงาน --</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <button type="button" onclick="loadEmployeesByEvent()" id="btnLoadEvent" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all text-sm font-semibold shadow-lg hover:shadow-xl transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none" disabled>
                    <i class="fas fa-sync-alt"></i> โหลดข้อมูล
                </button>
            </div>
        </div>
    </div>
    <!-- Filter Section -->
    <div class="mb-6 bg-white rounded-xl shadow-md border border-gray-200 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-search text-blue-600 mr-1"></i> ค้นหาพนักงาน
                </label>
                <input type="text" id="searchEmployee" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="ค้นหาด้วยPin, ชื่อ, แผนก, บริษัท...">
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-building text-blue-600 mr-1"></i> แผนก
                </label>
                <select id="filterDepartment" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="">ทุกแผนก</option>
                </select>
            </div>
            <div class="w-full sm:w-48">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fas fa-filter text-blue-600 mr-1"></i> สถานะ
                </label>
                <select id="filterStatus" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white">
                    <option value="">ทั้งหมด</option>
                    <option value="checked">ลงทะเบียนแล้ว</option>
                    <option value="not-checked">ยังไม่ลงทะเบียน</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">พนักงานทั้งหมด</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalEmployees">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">ลงทะเบียนแล้ว</p>
                    <p class="text-2xl font-bold text-green-600" id="checkedInEmployees">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">รอลงทะเบียน</p>
                    <p class="text-2xl font-bold text-orange-600" id="pendingEmployees">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-percentage text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500">เปอร์เซ็นต์</p>
                    <p class="text-2xl font-bold text-purple-600" id="percentageChecked">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table Section - พนักงาน -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-4 py-3 border-b border-blue-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-users text-blue-600"></i>
                    <h2 class="text-base font-semibold text-gray-800">รายชื่อพนักงาน</h2>
                </div>
                <span class="text-sm text-gray-600">แสดง <span id="displayCount">0</span> รายการ</span>
            </div>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                <table class="min-w-full divide-y divide-gray-200" id="employeeTable">
                    <thead class="bg-gradient-to-r from-blue-900 to-blue-800 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 60px;">CodePin</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 100px;">รหัสพนักงาน</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 200px;">ชื่อ-นามสกุล</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">แผนก</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">ตำแหน่ง</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 100px;">ชื่อเล่น</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">เวลาเข้างาน</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="employeeTableBody">
                        <tr>
                            <td colspan="8" class="text-center py-8 text-gray-500">
                                <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
                                <p class="mt-2">กำลังโหลดข้อมูล...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Data Table Section - ผู้รับเหมา -->
    <div class="bg-white rounded-xl shadow-md border border-teal-200 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-teal-50 to-teal-100 px-4 py-3 border-b border-teal-200">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <i class="fas fa-user-tie text-teal-600"></i>
                    <h2 class="text-base font-semibold text-gray-800">รายชื่อผู้รับเหมา</h2>
                </div>
                <span class="text-sm text-gray-600">แสดง <span id="contractorDisplayCount">0</span> รายการ</span>
            </div>
            <!-- ช่องค้นหาสำหรับผู้รับเหมา -->
            <div class="flex gap-3">
                <div class="flex-1">
                    <input type="text" id="searchContractor" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors text-sm" placeholder="ค้นหาผู้รับเหมาด้วย CodePin, ชื่อ, บริษัท...">
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                <table class="min-w-full divide-y divide-gray-200" id="contractorTable">
                    <thead class="bg-gradient-to-r from-teal-700 to-teal-600 sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 60px;">CodePin</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 100px;">รหัสพนักงาน</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 200px;">ชื่อ-นามสกุล</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 200px;">บริษัท</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">เวลาเข้างาน</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap" style="width: 150px;">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="contractorTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-8 text-gray-500">
                                <i class="fas fa-spinner fa-spin text-3xl text-teal-600"></i>
                                <p class="mt-2">กำลังโหลดข้อมูล...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>เพิ่มพนักงานใหม่
                </h3>
                <button type="button" onclick="closeAddEmployeeModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="addEmployeeForm" class="space-y-4">
                <div>
                    <label for="newEmpCode" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-id-card text-blue-600 mr-1"></i>รหัสพนักงาน  (ไม่บังคับ)
                    </label>
                    <input type="text" id="newEmpCode"  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="เช่น K0710069">
                </div>
                
                <div>
                    <label for="newEmpName" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user text-blue-600 mr-1"></i>ชื่อ-นามสกุล <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="newEmpName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="เช่น นายวุฒิพงษ์ ชาสติ">
                </div>
                
                <div>
                    <label for="newEmpDiv" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-building text-blue-600 mr-1"></i>แผนก (ถ้าไม่ใช่พนักงาน ระบุคำว่า ผู้รับเหมา เท่านั้น)
                    </label>
                    <input type="text" id="newEmpDiv" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="เช่น แผนก IT">
                </div>
                
                <div>
                    <label for="newEmpPosition" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-briefcase text-blue-600 mr-1"></i>ตำแหน่ง (ถ้าไม่ใช่พนักงาน ระบุชื่อบริษัทฯ) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="newEmpPosition" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="เช่น โปรแกรมเมอร์">
                </div>
                
                <div>
                    <label for="newEmpNickname" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-smile text-blue-600 mr-1"></i>ชื่อเล่น
                    </label>
                    <input type="text" id="newEmpNickname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" placeholder="เช่น พงษ์">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddEmployeeModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i class="fas fa-save mr-1"></i>บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Contractor Modal -->
    <div id="addContractorModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-md shadow-lg rounded-xl bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-user-tie text-teal-600 mr-2"></i>เพิ่มผู้รับเหมาใหม่
                </h3>
                <button type="button" onclick="closeAddContractorModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="addContractorForm" class="space-y-4">
                <div>
                    <label for="newContractorName" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user text-teal-600 mr-1"></i>ชื่อ-นามสกุล <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="newContractorName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="เช่น นายสมชาย ใจดี">
                </div>
                
                <div>
                    <label for="newContractorCompany" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-building text-teal-600 mr-1"></i>ชื่อบริษัท <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="newContractorCompany" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-colors" placeholder="เช่น บริษัท ABC จำกัด">
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeAddContractorModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                        <i class="fas fa-times mr-1"></i>ยกเลิก
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors font-medium">
                        <i class="fas fa-save mr-1"></i>บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div id="qrCodeModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative mx-auto p-6 border w-11/12 max-w-md shadow-2xl rounded-2xl bg-white">
            <div class="flex items-center justify-between mb-4 pb-3 border-b">
                <h3 class="text-lg font-semibold text-gray-900">
                    <i class="fas fa-qrcode text-purple-600 mr-2"></i>QR Code โหวตงานปีใหม่
                </h3>
                <button type="button" onclick="closeQRModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center">
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-6 rounded-xl mb-4">
                    <img src="{{ asset('images/QRnewYear.png') }}" alt="QR Code" class="w-full max-w-sm mx-auto rounded-lg shadow-lg border-4 border-white">
                </div>
                
                <p class="text-sm text-gray-600 mb-4">
                    <i class="fas fa-mobile-alt text-purple-600 mr-1"></i>
                    สแกน QR Code เพื่อเข้าสู่หน้าโหวต
                </p>
                
                <a href="{{ asset('images/QRnewYear.png') }}" download="QRcode-NewYearVote.png" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                    <i class="fas fa-download"></i>
                    ดาวน์โหลด QR Code
                </a>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const userIT = user.IT || 0;
    let employeeData = [];
    let filteredData = [];
    let currentEventStatus = 0; // 0 = ปิด, 1 = เปิด
    let typeEventData = @json($typeEvent);
    let selectedYear = '';
    let selectedType = '';

    $(document).ready(function() {
        if(userIT ==1 ){
            $('#EventSelection').show();
        }
        // ตั้งค่าเริ่มต้นจาก status = 1
        const activeEvent = typeEventData.find(e => e.status == 1);
        if (activeEvent) {
            selectedYear = activeEvent.yearEvent;
            $('#filterYear').val(selectedYear);
            updateTypeFilter();
            selectedType = activeEvent.SiteEvent;
            $('#filterType').val(selectedType);
            $('#btnLoadEvent').prop('disabled', false);
            loadEmployeesByEvent();
        }
        
        loadEventStatus();
        
        // Event listeners
        $('#filterYear').on('change', function() {
            selectedYear = $(this).val();
            updateTypeFilter();
            if (selectedYear) {
                $('#filterType').prop('disabled', false);
            } else {
                $('#filterType').prop('disabled', true).val('');
                $('#searchEmployee').prop('disabled', true).val('');
                $('#btnLoadEvent').prop('disabled', true);
                clearEmployeeList();
            }
            checkLoadButton();
        });
        
        $('#filterType').on('change', function() {
            selectedType = $(this).val();
            checkLoadButton();
        });
        
        $('#searchEmployee').on('input', function() {
            filterEmployees();
        });
        
        $('#searchContractor').on('input', function() {
            filterContractors();
        });
        
        $('#filterDepartment, #filterStatus').on('change', function() {
            filterEmployees();
        });
    });

    function checkLoadButton() {
        if (selectedYear && selectedType) {
            $('#btnLoadEvent').prop('disabled', false);
        } else {
            $('#btnLoadEvent').prop('disabled', true);
        }
    }

    function updateTypeFilter() {
        const $select = $('#filterType');
        $select.find('option:not(:first)').remove();
        $select.val('');
        selectedType = ''; // รีเซ็ตค่า selectedType
        
        if (selectedYear) {
            const types = typeEventData.filter(event => event.yearEvent == selectedYear);
            // ใช้ Set เพื่อกรอง SiteEvent ที่ซ้ำกัน
            const uniqueTypes = [...new Set(types.map(t => t.SiteEvent))].filter(t => t); // กรองค่าว่างด้วย
            uniqueTypes.forEach(type => {
                $select.append(`<option value="${type}">${type}</option>`);
            });
        }
        
        checkLoadButton(); // เช็คสถานะปุ่มโหลดหลังจากอัพเดท
    }

    function clearEmployeeList() {
        employeeData = [];
        filteredData = [];
        $('#employeeTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">
                    <i class="fas fa-info-circle text-4xl mb-2 text-blue-400"></i>
                    <p class="font-medium">กรุณาเลือกปีและกลุ่มหน่วยงาน แล้วกดปุ่ม "โหลดข้อมูล"</p>
                </td>
            </tr>
        `);
        updateStatistics();
        updateDepartmentFilter();
        $('#searchEmployee').prop('disabled', true).val('');
    }

    function loadEmployeesByEvent() {
        if (!selectedYear || !selectedType) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือก Event',
                text: 'กรุณาเลือกปีกิจกรรมและกลุ่มหน่วยงานก่อนโหลดข้อมูล',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }

        loadEmployeeList();
    }

    function loadEmployeeList(skipSearch = false) {
        if (!selectedYear || !selectedType) {
            clearEmployeeList();
            return;
        }

        $('#employeeTableBody').html(`
            <tr>
                <td colspan="8" class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i>
                    <p class="text-gray-600 mt-2">กำลังโหลดข้อมูล...</p>
                </td>
            </tr>
        `);

        const searchText = skipSearch ? '' : $('#searchEmployee').val().trim();

        $.ajax({
            url: '/User/IT/SpecialEvent/getEmployeeList',
            type: 'GET',
            data: {
                year: selectedYear,
                type: selectedType,
                search: searchText
            },
            dataType: 'json',
            success: function(response) {
                console.log(response)
                if (response.status === 200) {
                    employeeData = response.employees;
                    filteredData = employeeData;
                    $('#searchEmployee').prop('disabled', false);
                    updateDepartmentFilter();
                    displayEmployees(filteredData);
                    updateStatistics();
                    
                    // แจ้งเตือนเมื่อโหลดสำเร็จ
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'โหลดข้อมูลสำเร็จ',
                    //     text: `พบพนักงาน ${employeeData.length} คน ใน Event ${selectedYear} - ${selectedType}`,
                    //     timer: 2000,
                    //     showConfirmButton: false,
                    //     toast: true,
                    //     position: 'top-end'
                    // });
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

    function updateDepartmentFilter() {
        const departments = [...new Set(employeeData.map(emp => emp.empdiv))].filter(Boolean);
        const $select = $('#filterDepartment');
        $select.find('option:not(:first)').remove();
        
        departments.forEach(dept => {
            $select.append(`<option value="${dept}">${dept}</option>`);
        });
    }

    function filterEmployees() {
        const searchText = $('#searchEmployee').val().toLowerCase().trim();
        const selectedDept = $('#filterDepartment').val();
        const selectedStatus = $('#filterStatus').val();
        
        filteredData = employeeData.filter(emp => {
            const matchSearch = !searchText || 
                String(emp.CodePin || '').toLowerCase().includes(searchText) ||
                String(emp.empname || '').toLowerCase().includes(searchText) ||
                String(emp.empdiv || '').toLowerCase().includes(searchText) ||
                String(emp.empposition || '').toLowerCase().includes(searchText) ||
                String(emp.empnickname || '').toLowerCase().includes(searchText);
            
            const matchDept = !selectedDept || emp.empdiv === selectedDept;
            
            const matchStatus = !selectedStatus || 
                (selectedStatus === 'checked' && emp.TimeIn) ||
                (selectedStatus === 'not-checked' && !emp.TimeIn);
            
            return matchSearch && matchDept && matchStatus;
        });
        
        displayEmployees(filteredData);
        updateStatistics();
    }

    function displayEmployees(data) {
        // แยกข้อมูลพนักงานและผู้รับเหมา
        const employees = data.filter(emp => emp.empdiv !== 'ผู้รับเหมา');
        const contractors = data.filter(emp => emp.empdiv === 'ผู้รับเหมา');
        
        // แสดงตารางพนักงาน
        displayEmployeeTable(employees);
        
        // แสดงตารางผู้รับเหมา
        displayContractorTable(contractors);
    }
    
    function displayEmployeeTable(employees) {
        const $tbody = $('#employeeTableBody');
        $tbody.empty();
        
        if (employees.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="8" class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>ไม่พบข้อมูลพนักงาน</p>
                    </td>
                </tr>
            `);
            $('#displayCount').text('0');
            return;
        }
        
        employees.forEach((emp, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            const isCheckedIn = emp.TimeIn ? true : false;
            
            let actionButton;
            if (!isCheckedIn) {
                actionButton = `
                    <button onclick="checkInEmployee('${emp.empcode}', ${index}, false,'${emp.empname}')" 
                            class="btn-check-in inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-colors"
                            id="btn-${emp.empcode}">
                        <i class="fas fa-clock"></i> ลงทะเบียน
                    </button>
                `;
            } else {
                actionButton = `
                    <button onclick="updateCheckIn('${emp.empcode}', ${index}, false,'${emp.empname}')" 
                            class="btn-check-in confirmed inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg hover:brightness-110 transition-colors"
                            id="btn-${emp.empcode}">
                        <i class="fas fa-edit"></i> แก้ไขเวลา
                    </button>
                `;
            }
            
            const row = `
                <tr class="${rowClass} hover:bg-blue-50 transition-colors">
                    <td class="px-4 py-3 text-center text-sm text-gray-900">${emp.CodePin}</td>
                    <td class="px-4 py-3 text-sm font-medium text-blue-600">${emp.empcode || '-'}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${emp.empname}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${emp.empdiv}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${emp.empposition}</td>
                    <td class="px-4 py-3 text-center text-sm text-gray-900">${emp.empnickname || '-'}</td>
                    <td class="px-4 py-3 text-center text-sm font-semibold" id="time-${emp.empcode}">
                        ${isCheckedIn ? `<span class="text-green-600">${emp.TimeIn}</span>` : '<span class="text-gray-400">-</span>'}
                    </td>
                    <td class="px-4 py-3 text-center">${actionButton}</td>
                </tr>
            `;
            
            $tbody.append(row);
        });
        
        $('#displayCount').text(employees.length);
    }
    
    function displayContractorTable(contractors) {
        const $tbody = $('#contractorTableBody');
        $tbody.empty();
        
        if (contractors.length === 0) {
            $tbody.html(`
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2"></i>
                        <p>ไม่พบข้อมูลผู้รับเหมา</p>
                    </td>
                </tr>
            `);
            $('#contractorDisplayCount').text('0');
            return;
        }
        
        contractors.forEach((emp, index) => {
            const rowClass = index % 2 === 0 ? 'bg-white' : 'bg-gray-50';
            const isCheckedIn = emp.TimeIn ? true : false;
            
            let actionButton;
            if (!isCheckedIn) {
                actionButton = `
                    <button onclick="checkInEmployee('${emp.empcode}', ${index}, true,'${emp.empname}')" 
                            class="btn-check-in inline-flex items-center gap-1 px-3 py-1.5 bg-teal-600 text-white text-xs font-medium rounded-lg hover:bg-teal-700 transition-colors"
                            id="btn-contractor-${emp.empcode}">
                        <i class="fas fa-clock"></i> ลงทะเบียน
                    </button>
                `;
            } else {
                actionButton = `
                    <button onclick="updateCheckIn('${emp.empcode}', ${index}, true,${emp.empname})" 
                            class="btn-check-in confirmed inline-flex items-center gap-1 px-3 py-1.5 bg-green-600 text-white text-xs font-medium rounded-lg hover:brightness-110 transition-colors"
                            id="btn-contractor-${emp.empcode}">
                        <i class="fas fa-edit"></i> แก้ไขเวลา
                    </button>
                `;
            }
            
            const row = `
                <tr class="${rowClass} hover:bg-teal-50 transition-colors">
                    <td class="px-4 py-3 text-center text-sm text-gray-900">${emp.CodePin}</td>
                    <td class="px-4 py-3 text-sm font-medium text-teal-600">${emp.empcode}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${emp.empname}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">${emp.empposition || '-'}</td>
                    <td class="px-4 py-3 text-center text-sm font-semibold" id="time-contractor-${emp.empcode}">
                        ${isCheckedIn ? `<span class="text-green-600">${emp.TimeIn}</span>` : '<span class="text-gray-400">-</span>'}
                    </td>
                    <td class="px-4 py-3 text-center">${actionButton}</td>
                </tr>
            `;
            
            $tbody.append(row);
        });
        
        $('#contractorDisplayCount').text(contractors.length);
    }

    function checkInEmployee(empCode, index, isContractor = false,empName) {
        const currentTime = getCurrentTime();
        const currentDateTime = getCurrentDateTime();
        const personType = isContractor ? 'ผู้รับเหมา' : 'พนักงาน';
        
        // หาชื่อนามสกุลจาก employeeData
        const employee = employeeData.find(e => e.empcode === empCode);
       
        
        Swal.fire({
            title: 'ยืนยันการลงทะเบียน',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>ประเภท:</strong> ${personType}</p>
                    <p class="mb-2"><strong>รหัสพนักงาน:</strong> ${empCode}</p>
                    <p class="mb-2"><strong>ชื่อ-นามสกุล:</strong> ${empName}</p>
                    <p class="mb-2"><strong>เวลาเข้างาน:</strong> <span class="text-blue-600 font-bold">${currentTime}</span></p>
                    <p class="text-sm text-gray-500 mt-3">กดยืนยันเพื่อบันทึกเวลาเข้างาน</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: isContractor ? '#14b8a6' : '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check"></i> ยืนยัน',
            cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                saveCheckIn(empCode, empName, currentDateTime, index, isContractor);
            }
        });
    }

    function updateCheckIn(empCode, index, isContractor = false) {
        const currentTime = getCurrentTime();
        const currentDateTime = getCurrentDateTime();
        const personType = isContractor ? 'ผู้รับเหมา' : 'พนักงาน';
        
        // หาชื่อนามสกุลจาก employeeData
        const employee = employeeData.find(e => e.empcode === empCode);
        const empName = employee ? employee.empname : '-';
        
        Swal.fire({
            title: 'แก้ไขเวลาเข้างาน',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>ประเภท:</strong> ${personType}</p>
                    <p class="mb-2"><strong>รหัสพนักงาน:</strong> ${empCode}</p>
                    <p class="mb-2"><strong>ชื่อ-นามสกุล:</strong> ${empName}</p>
                    <p class="mb-2"><strong>เวลาเข้างานใหม่:</strong> <span class="text-orange-600 font-bold">${currentTime}</span></p>
                    <p class="text-sm text-red-500 mt-3">⚠️ การแก้ไขเวลาจะเขียนทับข้อมูลเดิม</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check"></i> ยืนยันการแก้ไข',
            cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                saveCheckIn(empCode, empName, currentDateTime, index, isContractor);
            }
        });
    }

    function saveCheckIn(empCode, empName, checkInTime, index, isContractor = false) {
        $.ajax({
            url: '/User/IT/SpecialEvent/saveCheckIn',
            type: 'POST',
            data: {
                emp_code: empCode,
                emp_name: empName,
                check_in_time: checkInTime
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    const empIndex = employeeData.findIndex(e => e.empcode === empCode);
                    if (empIndex !== -1) {
                        employeeData[empIndex].TimeIn = checkInTime;
                    }
                    
                    // อัปเดต filteredData ด้วย
                    const filteredIndex = filteredData.findIndex(e => e.empcode === empCode);
                    if (filteredIndex !== -1) {
                        filteredData[filteredIndex].TimeIn = checkInTime;
                    }
                    
                    // กำหนด ID prefix ตามประเภท
                    const timeId = isContractor ? `time-contractor-${empCode}` : `time-${empCode}`;
                    const btnId = isContractor ? `btn-contractor-${empCode}` : `btn-${empCode}`;
                    const btnColorClass = isContractor ? 'bg-green-600' : 'confirmed';
                    
                    $(`#${timeId}`).html(`<span class="text-green-600 time-display">${formatTime(checkInTime)}</span>`);
                    $(`#${btnId}`).replaceWith(`
                        <button onclick="updateCheckIn('${empCode}', ${index}, ${isContractor})" 
                                class="btn-check-in ${btnColorClass} inline-flex items-center gap-1 px-3 py-1.5 text-white text-xs font-medium rounded-lg hover:brightness-110 transition-colors"
                                id="${btnId}">
                            <i class="fas fa-edit"></i> แก้ไขเวลา
                        </button>
                    `);
                    
                    updateStatistics();
                    
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: isUpdate ? 'แก้ไขเวลาสำเร็จ!' : 'ลงทะเบียนสำเร็จ!',
                    //     text: `บันทึกเวลาเข้างาน ${formatTime(checkInTime)} เรียบร้อยแล้ว`,
                    //     timer: 2000,
                    //     showConfirmButton: false
                    // });
                } else {
                    showError('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถบันทึกข้อมูลได้');
            }
        });
    }

    function updateStatistics() {
        const total = employeeData.length;
        const checkedIn = employeeData.filter(emp => emp.TimeIn).length;
        const pending = total - checkedIn;
        const percentage = total > 0 ? Math.round((checkedIn / total) * 100) : 0;
        
        $('#totalEmployees').text(total);
        $('#checkedInEmployees').text(checkedIn);
        $('#pendingEmployees').text(pending);
        $('#percentageChecked').text(percentage + '%');
    }

    function getCurrentTime() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    function getCurrentDateTime() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    }

    function formatTime(dateTimeString) {
        if (!dateTimeString) return '-';
        const date = new Date(dateTimeString);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: message,
            confirmButtonColor: '#ef4444'
        });
    }

    function exportExcel() {
        Swal.fire({
            title: 'ส่งออกข้อมูล',
            text: 'ต้องการส่งออกข้อมูลเป็น Excel หรือไม่?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-file-excel"></i> ส่งออก',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/User/IT/SpecialEvent/exportExcel';
            }
        });
    }

    function openAddEmployeeModal() {
        if (!selectedYear || !selectedType) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือก Event',
                text: 'กรุณาเลือกปีกิจกรรมและกลุ่มหน่วยงานก่อนเพิ่มพนักงาน',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        
        $('#addEmployeeModal').removeClass('hidden');
        $('#addEmployeeForm')[0].reset();
        $('#newEmpCode').focus();
    }

    function openAddContractorModal() {
        if (!selectedYear || !selectedType) {
            Swal.fire({
                icon: 'warning',
                title: 'กรุณาเลือก Event',
                text: 'กรุณาเลือกปีกิจกรรมและกลุ่มหน่วยงานก่อนเพิ่มผู้รับเหมา',
                confirmButtonColor: '#f59e0b'
            });
            return;
        }
        
        $('#addContractorModal').removeClass('hidden');
        $('#addContractorForm')[0].reset();
        $('#newContractorName').focus();
    }

    function closeAddContractorModal() {
        $('#addContractorModal').addClass('hidden');
        $('#addContractorForm')[0].reset();
    }

    function closeAddEmployeeModal() {
        $('#addEmployeeModal').addClass('hidden');
        $('#addEmployeeForm')[0].reset();
    }

    function openQRModal() {
        $('#qrCodeModal').removeClass('hidden');
    }

    function closeQRModal() {
        $('#qrCodeModal').addClass('hidden');
    }

    function loadEventStatus() {
        $.ajax({
            url: '/User/IT/SpecialEvent/getEventStatus',
            type: 'GET',
            success: function(response) {
                if (response.status == 200) {
                    currentEventStatus = response.eventStatus;
                    // updateEventStatusUI();
                } else {
                    console.error('Failed to load event status');
                    // updateEventStatusUI(); // แสดง UI แบบ default
                }
            },
            error: function(xhr, status, error) {
                console.error('Error loading event status:', error);
                // updateEventStatusUI(); // แสดง UI แบบ default
            }
        });
    }

    // function updateEventStatusUI() {
    //     const $btn = $('#eventStatusBtn');
    //     const $icon = $('#eventStatusIcon');
    //     const $text = $('#eventStatusText');
        
    //     if (currentEventStatus === 1) {
    //         // เปิดโหวต
    //         $btn.removeClass('bg-red-100 border-red-300 text-red-700')
    //             .addClass('bg-green-100 border-2 border-green-300 text-green-700 hover:bg-green-200');
    //         $icon.removeClass('fa-lock').addClass('fa-unlock');
    //         $text.text('เปิดโหวต');
    //     } else {
    //         // ปิดโหวต
    //         $btn.removeClass('bg-green-100 border-green-300 text-green-700')
    //             .addClass('bg-red-100 border-2 border-red-300 text-red-700 hover:bg-red-200');
    //         $icon.removeClass('fa-unlock').addClass('fa-lock');
    //         $text.text('ปิดโหวต');
    //     }
    // }

    function toggleEventStatus() {
        const newStatus = currentEventStatus === 1 ? 0 : 1;
        const statusText = newStatus === 1 ? 'เปิดโหวต' : 'ปิดโหวต';
        const statusColor = newStatus === 1 ? 'green' : 'red';
        
        Swal.fire({
            title: 'ยืนยันการเปลี่ยนสถานะ',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>สถานะปัจจุบัน:</strong> <span class="text-${currentEventStatus === 1 ? 'green' : 'red'}-600 font-bold">${currentEventStatus === 1 ? 'เปิดโหวต' : 'ปิดโหวต'}</span></p>
                    <p class="mb-2"><strong>เปลี่ยนเป็น:</strong> <span class="text-${statusColor}-600 font-bold">${statusText}</span></p>
                    <p class="text-sm text-gray-500 mt-3">${newStatus === 1 ? '⚠️ พนักงานจะสามารถเข้าโหวตได้' : '⚠️ พนักงานจะไม่สามารถเข้าโหวตได้'}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: newStatus === 1 ? '#10b981' : '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check"></i> ยืนยัน',
            cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                saveEventStatus(newStatus);
            }
        });
    }

    function saveEventStatus(newStatus) {
        // console.log('Saving new event status:', newStatus);
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
                if (response.status === 200) {
                    currentEventStatus = newStatus;
                    // updateEventStatusUI();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'เปลี่ยนสถานะสำเร็จ!',
                        text: `ระบบโหวตถูก${newStatus === 1 ? 'เปิด' : 'ปิด'}แล้ว`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถบันทึกข้อมูลได้');
            }
        });
    }

    // Handle contractor form submit
    $('#addContractorForm').on('submit', function(e) {
        e.preventDefault();
        
        const contractorName = $('#newContractorName').val().trim();
        const contractorCompany = $('#newContractorCompany').val().trim();
        
        if (!contractorName || !contractorCompany) {
            showError('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }
        
        // สร้างรหัสอัตโนมัติ (S + timestamp)
        const contractorCode = 'S' + Date.now();
        
        Swal.fire({
            title: 'ยืนยันการเพิ่มผู้รับเหมา',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>ชื่อ:</strong> ${contractorName}</p>
                    <p class="mb-2"><strong>บริษัท:</strong> ${contractorCompany}</p>
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#14b8a6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check"></i> ยืนยัน',
            cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                saveNewContractor(contractorCode, contractorName, contractorCompany);
            }
        });
    });

    function saveNewContractor(contractorCode, contractorName, contractorCompany) {
        if (!selectedYear || !selectedType) {
            showError('กรุณาเลือกปีและกลุ่มหน่วยงานก่อนเพิ่มผู้รับเหมา');
            return;
        }

        $.ajax({
            url: '/User/IT/SpecialEvent/addEmployee',
            type: 'POST',
            data: {
                emp_code: contractorCode,
                emp_name: contractorName,
                emp_div: 'ผู้รับเหมา',
                emp_position: contractorCompany,
                emp_nickname: '',
                year: selectedYear,
                type: selectedType
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    closeAddContractorModal();
                    $('#searchEmployee').val(''); // ล้างช่องค้นหา
                    loadEmployeeList(true); // โหลดข้อมูลทั้งหมดโดยไม่ใช้ search filter
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มผู้รับเหมาสำเร็จ!',
                        text: `เพิ่ม ${contractorCode} - ${contractorName} ใน Event ${selectedYear} - ${selectedType}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถบันทึกข้อมูลได้');
            }
        });
    }

    // Handle form submit
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        
        const empCode = $('#newEmpCode').val().trim();
        const empName = $('#newEmpName').val().trim();
        const empDiv = $('#newEmpDiv').val().trim();
        const empPosition = $('#newEmpPosition').val().trim();
        const empNickname = $('#newEmpNickname').val().trim();
        
        if ( !empName || !empDiv || !empPosition) {
            showError('กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน');
            return;
        }
        
        // ตรวจสอบรหัสซ้ำ
        const exists = employeeData.some(emp => emp.id === empCode);
        if (exists) {
            showError('รหัสพนักงานนี้มีอยู่ในระบบแล้ว');
            return;
        }
        
        Swal.fire({
            title: 'ยืนยันการเพิ่มพนักงาน',
            html: `
                <div class="text-left">
                    <p class="mb-2"><strong>รหัส:</strong> ${empCode}</p>
                    <p class="mb-2"><strong>ชื่อ:</strong> ${empName}</p>
                    <p class="mb-2"><strong>แผนก:</strong> ${empDiv}</p>
                    <p class="mb-2"><strong>ตำแหน่ง:</strong> ${empPosition}</p>
                    ${empNickname ? `<p class="mb-2"><strong>ชื่อเล่น:</strong> ${empNickname}</p>` : ''}
                </div>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#6b7280',
            confirmButtonText: '<i class="fas fa-check"></i> ยืนยัน',
            cancelButtonText: '<i class="fas fa-times"></i> ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                saveNewEmployee(empCode, empName, empDiv, empPosition, empNickname);
            }
        });
    });

    function saveNewEmployee(empCode, empName, empDiv, empPosition, empNickname) {
        if (!selectedYear || !selectedType) {
            showError('กรุณาเลือกปีและกลุ่มหน่วยงานก่อนเพิ่มพนักงาน');
            return;
        }

        $.ajax({
            url: '/User/IT/SpecialEvent/addEmployee',
            type: 'POST',
            data: {
                emp_code: empCode,
                emp_name: empName,
                emp_div: empDiv,
                emp_position: empPosition,
                emp_nickname: empNickname,
                year: selectedYear,
                type: selectedType
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 200) {
                    closeAddEmployeeModal();
                    $('#searchEmployee').val(''); // ล้างช่องค้นหา
                    loadEmployeeList(true); // โหลดข้อมูลทั้งหมดโดยไม่ใช้ search filter
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'เพิ่มพนักงานสำเร็จ!',
                        text: `เพิ่ม ${empCode} - ${empName} ใน Event ${selectedYear} - ${selectedType}`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    showError(response.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                showError('ไม่สามารถบันทึกข้อมูลได้');
            }
        });
    }
</script>
</body>
</html>