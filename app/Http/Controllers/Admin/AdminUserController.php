<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Http\Requests\Admin\StoreAdminUserRequest; // ستحتاج لإنشاء هذا
use App\Http\Requests\Admin\UpdateAdminUserRequest; // ستحتاج لإنشاء هذا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AdminUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
        // تقييد الوصول إلى هذه الوظائف فقط للمدير العام (superadmin)
        $this->middleware(function ($request, $next) {
            if (Auth::guard('admin_web')->user()->role !== 'superadmin') {
                // يمكنك توجيههم إلى صفحة 403 أو صفحة أخرى
                return redirect()->route('admin.dashboard')->with('error', 'ليس لديك الصلاحية للوصول لهذه الصفحة.');
            }
            return $next($request);
        })->except(['index']); // يمكن لجميع المديرين عرض القائمة ربما
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $adminUsers = AdminUser::latest()->paginate(15);
        return view('admin.admin_users.index', compact('adminUsers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // يمكنك تمرير قائمة بالأدوار (roles) إذا كانت ديناميكية
        $roles = ['admin', 'content_manager', 'superadmin']; // مثال
        return view('admin.admin_users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAdminUserRequest $request)
    {
        $validatedData = $request->validated();
        // لا حاجة لتشفير password_hash هنا، النموذج AdminUser يعتني بذلك عبر mutator أو cast
        // $validatedData['password_hash'] = Hash::make($validatedData['password']);

        AdminUser::create($validatedData);

        return redirect()->route('admin.admin-users.index')
                         ->with('success', 'تم إنشاء مستخدم المدير بنجاح.');
    }

    /**
     * Display the specified resource.
     * عادةً لا نحتاج لدالة show لمستخدمي الإدارة، لكن يمكن تركها فارغة أو توجيهها.
     */
    public function show(AdminUser $adminUser) // استخدام Route Model Binding
    {
         return view('admin.admin_users.show', compact('adminUser'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AdminUser $adminUser) // استخدام Route Model Binding
    {
        if (Auth::guard('admin_web')->id() === $adminUser->id && $adminUser->role === 'superadmin' && AdminUser::where('role', 'superadmin')->count() <= 1) {
             return redirect()->route('admin.admin-users.index')->with('error', 'لا يمكن تعديل آخر مدير عام.');
        }
        $roles = ['admin', 'content_manager', 'superadmin'];
        return view('admin.admin_users.edit', compact('adminUser', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAdminUserRequest $request, AdminUser $adminUser)
    {
        $validatedData = $request->validated();

        // إذا تم إدخال كلمة مرور جديدة، قم بتحديثها
        if (!empty($validatedData['password'])) {
            // النموذج سيعتني بالتشفير
            $adminUser->password_hash = $validatedData['password'];
        } else {
            // إذا لم يتم إدخال كلمة مرور جديدة، لا تقم بتغيير الحالية
            unset($validatedData['password']);
        }

        // منع تغيير دور آخر مدير عام
        if ($adminUser->role === 'superadmin' && $validatedData['role'] !== 'superadmin' && AdminUser::where('role', 'superadmin')->count() <= 1 && Auth::guard('admin_web')->id() === $adminUser->id) {
            return redirect()->back()->withInput()->with('error', 'لا يمكن تغيير دور آخر مدير عام.');
        }


        $adminUser->update($validatedData);

        return redirect()->route('admin.admin-users.index')
                         ->with('success', 'تم تحديث بيانات مستخدم المدير بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AdminUser $adminUser)
    {
        // منع حذف المدير الحالي أو آخر مدير عام
        if (Auth::guard('admin_web')->id() === $adminUser->id) {
            return redirect()->route('admin.admin-users.index')
                             ->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }
        if ($adminUser->role === 'superadmin' && AdminUser::where('role', 'superadmin')->count() <= 1) {
             return redirect()->route('admin.admin-users.index')
                             ->with('error', 'لا يمكن حذف آخر مدير عام في النظام.');
        }

        try {
            $adminUser->delete();
            return redirect()->route('admin.admin-users.index')
                             ->with('success', 'تم حذف مستخدم المدير بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.admin-users.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}