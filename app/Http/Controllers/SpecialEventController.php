<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\spcEmpLists;
use App\Models\newyearimagepath;
use App\Models\newyearvotes;
use App\Models\newyearopenvote;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\LineWebhookController;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;


class SpecialEventController extends Controller
{
    /**
     * Get current user from JWT token via API
     * Uses cached user from session first to avoid rate limiting
     */
    protected function getCurrentUser(Request $request)
    {
        // Check if user is already cached in session
        $cachedUser = $request->session()->get('user');
        if ($cachedUser) {
            \Log::info('Using cached user from session', ['user_id' => $cachedUser['id'] ?? 'unknown']);
            return $cachedUser;
        }

        // Read token from session first, then fallback to other sources
        $token = $request->session()->get('token')
              ?? $request->bearerToken()
              ?? $request->cookie('token')
              ?? $request->input('jwt_token');
        
        \Log::info('getCurrentUser - Token sources:', [
            'session' => $request->session()->get('token') ? 'exists' : 'empty',
            'bearer' => $request->bearerToken() ? 'exists' : 'empty',
            'cookie' => $request->cookie('token') ? 'exists' : 'empty',
            'final_token' => $token ? substr($token, 0, 20) . '...' : 'null'
        ]);
        
        if (!$token) {
            \Log::warning('No token found in any source');
            return null;
        }

        $apiUrl = env('API_URL', '');
        
        if (!empty($apiUrl)) {
            try {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . $token,
                    ])
                    ->get($apiUrl . '/auth/me');

                \Log::info('API /auth/me response:', [
                    'status' => $response->status(),
                    'successful' => $response->successful()
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $user = $data['user'] ?? $data['data']['user'] ?? $data ?? null;
                    
                    // Cache user in session for subsequent requests
                    if ($user) {
                        $request->session()->put('user', $user);
                        \Log::info('User retrieved and cached:', ['user_id' => $user['id'] ?? 'unknown']);
                    }
                    
                    return $user;
                } else {
                    \Log::error('API /auth/me failed:', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Failed to get current user: ' . $e->getMessage());
            }
        }

        return null;
    }

    //
    public function dashboardIndex(Request $request){
        // Get user from API
        $user = $this->getCurrentUser($request);
        
        \Log::info('Dashboard access attempt', [
            'user' => $user,
            'cookie' => $request->cookie('token'),
            'session' => $request->session()->get('token')
        ]);
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $role = ($user['role'] ?? 0) == 1 || ($user['special_event'] ?? 0) == 1;
        if(!$role){
            return redirect()->route('login')->with('error','คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');
        }
    
        return view('user.IT.SpecialEvent.dashboard', compact('user'));
    }

    public function voteRegisterIndex(Request $request){
        $user = $this->getCurrentUser($request);
        \Log::info('Vote Register access attempt', [
            'user' => $user,
            'cookie' => $request->cookie('token'),
            'session' => $request->session()->get('token')
        ]);
        if (!$user) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }

        $role = ($user['role'] ?? 0) == 1 || ($user['special_event'] ?? 0) == 1;
        if(!$role){
            return redirect()->route('login')->with('error','คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');
        }
        $typeEvent = DB::table('newyearopenvote')->get();
        return view('user.IT.SpecialEvent.ManageUser',compact('typeEvent', 'user'));
    }

    public function getEmployeeList(Request $request){
        // dd($request);
        $search = $request->input('search');
        //  update typeEvent
        DB::table('newyearopenvote')->update(['status' => 0]);
        DB::table('newyearopenvote')
        ->where('yearEvent', $request->input('year'))
        ->where('SiteEvent', $request->input('type'))
        ->update(['status' => 1,'TimeStart' => now()]);

        $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
        
        $employees = spcEmpLists::where('Year',$typeEvent->yearEvent)
        ->where('type',$typeEvent->SiteEvent)
        ->where(function($query) use ($search) {
            $query->where('empname', 'like', '%' . $search . '%')
              ->orWhere('empcode', 'like', '%' . $search . '%');
        })
            ->get();


        return response()->json([
            'status' => 200,
            'employees' => $employees,
            'typeEvent' => $typeEvent
        ]);
    }

    public function saveCheckIn(Request $request){
        $empcode = $request->input('emp_code');
        $currentTime = $request->input('check_in_time');
        $currentTime = \Carbon\Carbon::parse($currentTime);
       
        $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
        // ถ้าไม่มี empcode หรือหาไม่เจอ ให้หาจาก empname ต่อ
        $empname = $request->input('emp_name');
        $checkempcode = spcEmpLists::where('Year',$typeEvent->yearEvent)
        ->where('type',$typeEvent->SiteEvent)->where('empname',$empname)->first();
        $employee = spcEmpLists::where('Year',$typeEvent->yearEvent)
        ->where('type',$typeEvent->SiteEvent)        
        ->where('empcode', $empcode)->first();

        if (!$employee && $empname) {
            $employee = spcEmpLists::where('Year',$typeEvent->yearEvent)
            ->where('type',$typeEvent->SiteEvent)
            ->where('empname', $empname)->first();
        }
        if ($employee) {
            $employee->TimeIn = $currentTime;
            $employee->save();

            // slack ITT
            $message = "✅ พนักงานงานพิเศษลงทะเบียนเข้างาน\n- รหัสพนักงาน: {$employee->empcode}\n- ชื่อ-นามสกุล: {$employee->empname}\n- เวลาเข้างาน: {$currentTime->format('H:i:s')}";
            $teamWebhook = new LineWebhookController();
            $slackwebhook = env('SLACK_ITT');
            $teamWebhook->sendToSlack($slackwebhook, $message);

            return response()->json([
                'status' => 200,
                'message' => 'Check-in successful',
                'TimeIn' => $currentTime->format('H:i:s')
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Employee not found'
            ]);
        }
    }

    public function addEmployee(Request $request){
        DB::beginTransaction();
        try {
            $empcode = $request->input('emp_code');
            $empname = $request->input('emp_name');
            $empdiv = $request->input('emp_div');
            $empposition = $request->input('emp_position');
            $nickname = $request->input('emp_nickname');
            $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
            // dd($request);
            // ตรวจสอบว่ามีพนักงานซ้ำหรือไม่ด้วย lockForUpdate
            // $existingEmployee = spcEmpLists::where('Year',$typeEvent->yearEvent)
            //     ->where('type',$typeEvent->SiteEvent)
            //     ->where('empcode', $empcode)
            //     ->lockForUpdate()
            //     ->first();
                
            // if ($existingEmployee) {
            //     DB::rollBack();
            //     return response()->json([
            //         'status' => 409,
            //         'message' => 'Employee already exists'
            //     ]);
            // }
            
            // หา ID ใหม่ด้วย lockForUpdate เพื่อป้องกัน race condition
            if($empdiv =='ผู้รับเหมา'){
                $maxId = spcEmpLists::where('Year',$typeEvent->yearEvent)
                    ->where('type',$typeEvent->SiteEvent)
                    ->where('empdiv','ผู้รับเหมา')
                    ->lockForUpdate()
                    ->max('CodePin');
                $newid = ($maxId < 499) ? 500 : $maxId + 1;
            } else {
                $maxId = spcEmpLists::where('Year',$typeEvent->yearEvent)
                    ->where('type',$typeEvent->SiteEvent)
                    ->lockForUpdate()
                    ->where('CodePin','<',500)
                    ->max('CodePin');
                $newid = ($maxId ?? 0) + 1;
            }

            $newEmployee = new spcEmpLists();
            // $newEmployee->id = $newid;
            $newEmployee->CodePin = $newid;
            $newEmployee->empcode = $empcode;
            $newEmployee->empname = $empname;
            $newEmployee->empdiv = $empdiv;
            $newEmployee->empposition = $empposition;
            $newEmployee->empnickname = $nickname;
            $newEmployee->TimeIn = null;
            $newEmployee->created_at = now();
            $newEmployee->created_by = auth()->user()->name ?? 'System';
            $newEmployee->Year = $typeEvent->yearEvent;
            $newEmployee->type = $typeEvent->SiteEvent;
            $newEmployee->save();
            
            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Employee added successfully'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Add employee error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function exportExcel(){
        try {
            $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
            $employees = spcEmpLists::where('Year',$typeEvent->yearEvent)
            ->where('type',$typeEvent->SiteEvent)->get();


            // $employees = spcEmpLists::orderBy('id', 'asc')->get();
            
            $filename = 'รายชื่อพนักงานงานพิเศษ_' . date('Ymd_His') . '.xlsx';
            
            return \Excel::download(new \App\Exports\SpecialEventEmployeeExport($employees), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    // Image Management Functions
    public function manageImagesIndex(Request $request){
        $user = $this->getCurrentUser($request);
        if (!$user) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบ');
        }
        $role = ($user['role'] ?? 0) == 1 || ($user['special_event'] ?? 0) == 1;
        if(!$role){
            return redirect()->route('login')->with('error','คุณไม่มีสิทธิ์เข้าใช้งานหน้านี้');
        }
        return view('user.IT.SpecialEvent.ManageImages');
    }

    public function getImages(){
        try {
            $eventStatus = DB::table('newyearopenvote')->where('status','1')->first();

            // ใช้ cache เก็บข้อมูล 5 นาที
            $images =  newyearimagepath::leftJoin('newyearvotes', 'newyearimagepath.id', '=', 'newyearvotes.image_id')
                ->select(
                    'newyearimagepath.*',
                    DB::raw('COUNT(newyearvotes.id) as vote_count')
                )
                ->where('newyearimagepath.yearEvent', $eventStatus->yearEvent)
                ->where('newyearimagepath.siteEvent', $eventStatus->SiteEvent)
                ->groupBy('newyearimagepath.id', 'newyearimagepath.category', 'newyearimagepath.post_name',
                    'newyearimagepath.image_path', 'newyearimagepath.created_by', 'newyearimagepath.updated_by', 'newyearimagepath.created_at', 'newyearimagepath.updated_at')
                ->orderBy('newyearimagepath.created_at', 'desc')
                ->get();
            
            // แปลง path ให้เป็น URL
            $images->transform(function($image) {
                $image->image_path = 'storage/' . $image->image_path;
                return $image;
            });


            return response()->json([
                'status' => 200,
                'images' => $images,
                'eventStatus' => $eventStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function exportImagesExcel(){
        try {
            $images = newyearimagepath::leftJoin('newyearvotes', 'newyearimagepath.id', '=', 'newyearvotes.image_id')
                ->select(
                    'newyearimagepath.*',
                    DB::raw('COUNT(newyearvotes.id) as vote_count')
                )
                ->groupBy('newyearimagepath.id', 'newyearimagepath.category', 'newyearimagepath.post_name', 'newyearimagepath.image_path', 'newyearimagepath.created_by', 'newyearimagepath.updated_by', 'newyearimagepath.created_at', 'newyearimagepath.updated_at')
                ->orderBy('vote_count', 'desc')
                ->orderBy('newyearimagepath.category', 'asc')
                ->get();
            
            $filename = 'รายงานรูปภาพประกวด_คะแนนโหวต_' . date('Ymd_His') . '.xlsx';
            
            return \Excel::download(new \App\Exports\SpecialEventImagesExport($images), $filename);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function updateEventStatus(Request $request){
        try {
            // $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
            $status = $request->input('event_status');
            // dd($request);
            if($status == 1 ){
                DB::table('newyearopenvote')->where('status','1')
                    ->update(['statusOpen' => $status,
                    'TimeStart'=> now()
                ]);
            }else{
                DB::table('newyearopenvote')
                ->update(['statusOpen' => $status]);
            }
           
            

            return response()->json([
                'status' => 200,
                'message' => 'อัพเดทสถานะสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function uploadImage(Request $request){
        try {
            $request->validate([
                'category' => 'required|string|max:255',
                'post_name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240' // 10MB
            ]);
        // dd($request);

            $category = $request->input('category');
            $postName = $request->input('post_name');
            $image = $request->file('image');
            $eventStatus = DB::table('newyearopenvote')->where('status','1')->first();
            // สร้างชื่อไฟล์ที่ไม่ซ้ำ
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            // dd($eventStatus);
            
            // เก็บไฟล์ใน storage/app/public/special_event_images
            $path = $image->storeAs('special_event_images', $filename, 'public');
            // บันทึกข้อมูลลง database
            $newImage = new newyearimagepath();
            $newImage->yearEvent = $eventStatus->yearEvent;
            $newImage->siteEvent = $eventStatus->SiteEvent;
            $newImage->category = $category;
            $newImage->post_name = $postName;
            $newImage->image_path = $path;
            $newImage->created_by = Auth::user()->name ?? 'System';
            $newImage->save();

            // ลบ cache
            Cache::forget('special_event_images');
            Cache::forget('special_event_vote_images');

            return response()->json([
                'status' => 200,
                'message' => 'อัพโหลดสำเร็จ',
                'image' => $newImage
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 422,
                'message' => 'ข้อมูลไม่ถูกต้อง: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function updateImage(Request $request){
        try {
            $request->validate([
                'id' => 'required|exists:newyearimagepath,id',
                'category' => 'required|string|max:255',
                'post_name' => 'required|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240'
            ]);

            $image = newyearimagepath::findOrFail($request->input('id'));
            $image->category = $request->input('category');
            $image->post_name = $request->input('post_name');

            // ถ้ามีการอัพโหลดรูปใหม่
            if ($request->hasFile('image')) {
                // ลบรูปเก่า
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }

                // บันทึกรูปใหม่
                $newImage = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $newImage->getClientOriginalExtension();
                $path = $newImage->storeAs('special_event_images', $filename, 'public');
                $image->image_path = $path;
            }

            $image->updated_by = auth()->user()->name ?? 'System';
            $image->save();

            // ลบ cache
            Cache::forget('special_event_images');
            Cache::forget('special_event_vote_images');

            return response()->json([
                'status' => 200,
                'message' => 'แก้ไขสำเร็จ',
                'image' => $image
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteImage(Request $request){
        try {
            $request->validate([
                'id' => 'required|exists:newyearimagepath,id'
            ]);

            $image = newyearimagepath::findOrFail($request->input('id'));

            // ลบไฟล์รูปภาพ
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }

            // ลบข้อมูลจาก database
            $image->delete();

            // ลบ cache
            Cache::forget('special_event_images');
            Cache::forget('special_event_vote_images');

            return response()->json([
                'status' => 200,
                'message' => 'ลบสำเร็จ'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }
   public function getEventStatus(){
        
        $eventStatus = DB::table('newyearopenvote')   
        ->where('status','1')
        ->value('statusOpen');

        return response()->json([
            'status' => 200,
            'eventStatus' => $eventStatus
        ]);
   }

    // Voting Functions
    public function empVoteIndex(){
        // check สถานะว่าเปิดโหวตหรือไม่
        $eventStatus = DB::table('newyearopenvote')                
        ->where('status','1')
        ->value('statusOpen');

     
        return view('user.IT.SpecialEvent.empvote',compact('eventStatus'));
    }

    public function verifyCard(Request $request){
        try {
            $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();

            // ตรวจสอบสถานะการเปิดโหวต
            $eventStatus = DB::table('newyearopenvote')                
                ->where('status',1)
                ->value('statusOpen');
                
            if($eventStatus == 0){
                return response()->json([
                    'status' => 403,
                    'message' => 'ปิดการโหวตแล้ว'
                ]);
            }

            $cardNumber = $request->input('card_number');
            
            // ใช้ sharedLock เพื่ออ่านข้อมูลโดยไม่ให้ update ระหว่างการอ่าน
            $employee = spcEmpLists::where('Year',$typeEvent->yearEvent)
                ->where('type',$typeEvent->SiteEvent)
                ->whereNotNull('TimeIn')
                ->where('CodePin', $cardNumber)
                ->sharedLock()
                ->first();
            // // dd($employee,$typeEvent,$cardNumber);
            
            if (!$employee) {
                return response()->json([
                    'status' => 404,
                    'message' => 'ไม่พบเลขบัตรนี้ในระบบ หรือยังไม่ได้ลงทะเบียนเข้างาน'
                ]);
            }
            // dd($employee);
            // ตรวจสอบว่าโหวตแล้วหรือยัง
            if ($employee->voted_status == 1) {
                return response()->json([
                    'status' => 403,
                    'message' => 'บัตรนี้ได้ทำการโหวตไปแล้ว'
                ]);
            }
            // dd($employee);
             // เก็บข้อมูลอุปกรณ์
            $userAgent = $request->header('User-Agent');
            $ipAddress = $request->ip();
            $deviceInfo = $this->getDeviceType($userAgent);
            $employee = spcEmpLists::where('Year',$typeEvent->yearEvent)
                ->where('type',$typeEvent->SiteEvent)
                ->where('CodePin', $cardNumber)
                ->lockForUpdate()
                ->first();
            $employee->user_agent = $userAgent;
            $employee->ip_address = $ipAddress;
            $employee->device_info = $deviceInfo;
            $employee->save();
            
            return response()->json([
                'status' => 200,
                'message' => 'ยืนยันสำเร็จ',
                'employee' => [
                    'name' => $employee->empname ?? 'ไม่ระบุชื่อ',
                    'position' => $employee->empposition ?? '-',
                    'card_number' => $employee->CodePin
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Verify card error: ' . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function getVoteImages(){
        try {
            // ใช้ cache เก็บข้อมูล 10 นาที (เพราะใช้บ่อยในหน้าโหวต)
            $eventStatus  = DB::table('newyearopenvote')                
                ->where('status',1)->first();
            $images =  newyearimagepath::select('id', 'category', 'post_name', 'image_path')                    
                    ->where('yearEvent', $eventStatus->yearEvent)
                    ->where('siteEvent', $eventStatus->SiteEvent)
                    ->orderBy('created_at', 'desc')
                    ->get()
                    ->map(function($image) {
                        $image->image_path = asset('storage/' . $image->image_path);
                        return $image;
                    });
            return response()->json([
                'status' => 200,
                'images' => $images
            ])
            ->header('Cache-Control', 'public, max-age=900') // 15 นาที
            ->header('Expires', gmdate('D, d M Y H:i:s', time() + 900) . ' GMT');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()
            ]);
        }
    }

    public function submitVotes(Request $request){
        DB::beginTransaction();
        try {
            $cardNumber = $request->input('card_number');
            $votes = $request->input('votes');

            $event = DB::table('newyearopenvote')
            ->where('status', 1)
            ->where('statusOpen', 1)
            ->first();
                
            if (!$event) {
                return response()->json([
                    'status'  => 403,
                    'message' => 'ปิดการโหวตแล้ว'
                ]);
            }

            $userAgent  = $request->header('User-Agent');
            $ipAddress  = $request->ip();
            $deviceInfo = $this->getDeviceType($userAgent);

            // 3) อัปเดตสถานะโหวตแบบ atomic (ไม่ใช้ lockForUpdate)
            $updated = spcEmpLists::where('Year', $event->yearEvent)
                ->where('type', $event->SiteEvent)
                ->where('CodePin', ltrim($cardNumber, '0'))
                ->where('voted_status', 0)
                ->update([
                    'voted_status' => 1
                ]);
            // dd($userAgent,$ipAddress,$deviceInfo);

            // ถ้าไม่ถูกอัปเดต = ไม่มีสิทธิ์หรือโหวตแล้ว
            if ($updated === 0) {
                DB::rollBack();
                return response()->json([
                    'status'  => 403,
                    'message' => 'ไม่สามารถโหวตได้ หรือโหวตไปแล้ว'
                ]);
            }

            // 4) เตรียมข้อมูลโหวตแบบ bulk insert
            $now  = Carbon::now()->format('Y-m-d H:i:s');
            $rows = [];

            foreach ($votes as $category => $imageId) {
                $rows[] = [
                    'card_number' => $cardNumber,
                    'category'    => $category,
                    'image_id'    => $imageId,
                    'voted_at'    => $now,
                    'created_at'  => $now,
                    'updated_at'  => $now
                ];
            }
            // dd($rows);
            // 5) insert ครั้งเดียว ลดภาระ DB
            DB::table('newyearvotes')->insert($rows);

            DB::commit();

            return response()->json([
                'status'  => 200,
                'message' => 'บันทึกการโหวตสำเร็จ'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Submit votes error', ['error' => $e]);

            return response()->json([
                'status'  => 500,
                'message' => 'เกิดข้อผิดพลาดในการบันทึกการโหวต'
            ]);
        }
    }

    public function getDashboardData()
    {
        try {
            $eventStatus = DB::table('newyearopenvote')                
            ->where('status','1')
            ->value('statusOpen');
            
            // ดึงสถิติทั้งหมด
            $typeEvent = DB::table('newyearopenvote')->where('status','1')->first();
            $totalVoters = spcEmpLists::where('Year',$typeEvent->yearEvent)
            ->where('type',$typeEvent->SiteEvent)
            ->where('voted_status', 1)->count();
            $totalImages = newyearimagepath::where('yearEvent', $typeEvent->yearEvent)
                ->where('siteEvent', $typeEvent->SiteEvent)
                ->count();
            // ดึงข้อมูลคะแนนโหวตแต่ละหมวด
            $categories = ['เดี่ยวชาย', 'เดี่ยวหญิง', 'แบบกลุ่ม'];
            $categoryData = [];
            
            
            foreach ($categories as $category) {
                // ดึงรูปภาพในหมวดนี้พร้อมนับคะแนน
                $images = newyearimagepath::leftJoin('newyearvotes', 'newyearimagepath.id', '=', 'newyearvotes.image_id')
                    ->where('newyearimagepath.category', $category)
                    ->where('yearEvent', $typeEvent->yearEvent)
                    ->where('siteEvent', $typeEvent->SiteEvent)
                    ->select(
                        'newyearimagepath.id',
                        'newyearimagepath.image_path',
                        'newyearimagepath.post_name',
                        'newyearimagepath.category',
                        DB::raw('COUNT(newyearvotes.id) as vote_count')
                    )
                    ->groupBy('newyearimagepath.id', 'newyearimagepath.image_path', 'newyearimagepath.post_name', 'newyearimagepath.category')
                    ->orderBy('vote_count', 'desc')
                    ->get();
               
                // แปลง path ให้เป็น URL เต็ม
                $images->transform(function ($image) {
                    $image->image_path = 'storage/' . $image->image_path;
                    return $image;
                });
                
                $categoryData[$category] = $images;
            }
            
            return response()->json([
                'status' => 200,
                'eventStatus' => $eventStatus,
                'stats' => [
                    'total_voters' => $totalVoters,
                    'total_images' => $totalImages
                ],
                'categories' => $categoryData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage(),
                'stats' => [
                    'total_voters' => 0,
                    'total_images' => 0
                ],
                'categories' => [
                    'เดี่ยวชาย' => [],
                    'เดี่ยวหญิง' => [],
                    'แบบกลุ่ม' => []
                ]
            ]);
        }
    }
    
    /**
     * แยกประเภทอุปกรณ์จาก User Agent
     */
    private function getDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'Unknown';
        }
        
        $userAgent = strtolower($userAgent);
        
        // ตรวจสอบว่าเป็น Mobile
        if (preg_match('/(android|webos|iphone|ipad|ipod|blackberry|iemobile|opera mini)/i', $userAgent)) {
            if (preg_match('/(ipad|tablet|playbook|silk|kindle)/i', $userAgent)) {
                return 'Tablet';
            }
            return 'Mobile';
        }
        
        // ตรวจสอบว่าเป็น Tablet
        if (preg_match('/(tablet|ipad)/i', $userAgent)) {
            return 'Tablet';
        }
        
        return 'Desktop';
    }
}