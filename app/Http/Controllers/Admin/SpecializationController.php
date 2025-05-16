<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialization;
use App\Models\Faculty;
use App\Http\Requests\Admin\StoreSpecializationRequest;
use App\Http\Requests\Admin\UpdateSpecializationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SpecializationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Specialization::with(['faculty', 'createdByAdmin', 'lastUpdatedByAdmin'])->latest();
        if ($request->filled('faculty_id')) {
            $query->where('faculty_id', $request->faculty_id);
        }
        $specializations = $query->paginate(15);
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']); // For filtering dropdown
        return view('admin.specializations.index', compact('specializations', 'faculties'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.specializations.create', compact('faculties'));
    }

    public function store(StoreSpecializationRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['created_by_admin_id'] = Auth::guard('admin_web')->id();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        Specialization::create($validatedData);
        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تم إنشاء الاختصاص بنجاح.');
    }

    public function show(Specialization $specialization)
    {
        $specialization->load(['faculty', 'courses', 'projects', 'createdByAdmin', 'lastUpdatedByAdmin']);
        return view('admin.specializations.show', compact('specialization'));
    }

    public function edit(Specialization $specialization)
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        return view('admin.specializations.edit', compact('specialization', 'faculties'));
    }

    public function update(UpdateSpecializationRequest $request, Specialization $specialization)
    {
        $validatedData = $request->validated();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        $specialization->update($validatedData);
        return redirect()->route('admin.specializations.index')
                         ->with('success', 'تم تحديث الاختصاص بنجاح.');
    }

    public function destroy(Specialization $specialization)
    {
         if ($specialization->courses()->exists() || $specialization->projects()->exists() || $specialization->students()->exists()) {
             return redirect()->route('admin.specializations.index')
                              ->with('error', 'لا يمكن حذف الاختصاص لوجود مقررات أو مشاريع أو طلاب مرتبطين به.');
        }
        try {
            $specialization->delete();
            return redirect()->route('admin.specializations.index')
                             ->with('success', 'تم حذف الاختصاص بنجاح.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('admin.specializations.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}