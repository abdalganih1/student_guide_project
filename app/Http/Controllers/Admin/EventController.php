<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Faculty;
use App\Http\Requests\Admin\StoreEventRequest; // ستحتاج لإنشاء هذا
use App\Http\Requests\Admin\UpdateEventRequest; // ستحتاج لإنشاء هذا
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin_web');
    }

    public function index(Request $request)
    {
        $query = Event::with(['organizingFaculty', 'createdByAdmin'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('faculty_id')) {
            $query->where('organizing_faculty_id', $request->faculty_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ar', 'like', "%{$search}%")
                  ->orWhere('title_en', 'like', "%{$search}%")
                  ->orWhere('description_ar', 'like', "%{$search}%");
            });
        }

        $events = $query->paginate(15);
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']); // للفلترة
        $statuses = ['scheduled' => 'مجدولة', 'ongoing' => 'جارية', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة', 'draft' => 'مسودة'];

        return view('admin.events.index', compact('events', 'faculties', 'statuses'));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        $statuses = ['scheduled' => 'مجدولة', 'ongoing' => 'جارية', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة', 'draft' => 'مسودة'];
        return view('admin.events.create', compact('faculties', 'statuses'));
    }

    public function store(StoreEventRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['created_by_admin_id'] = Auth::guard('admin_web')->id();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        if ($request->hasFile('main_image')) { // افترض أن اسم الحقل في النموذج هو main_image
            $path = $request->file('main_image')->store('events', 'public');
            $validatedData['main_image_url'] = $path;
        }

        Event::create($validatedData);

        return redirect()->route('admin.events.index')
                         ->with('success', 'تم إنشاء الفعالية بنجاح.');
    }

    public function show(Event $event)
    {
        $event->load(['organizingFaculty', 'createdByAdmin', 'lastUpdatedByAdmin', 'registeredStudents.student']);
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $faculties = Faculty::orderBy('name_ar')->get(['id', 'name_ar']);
        $statuses = ['scheduled' => 'مجدولة', 'ongoing' => 'جارية', 'completed' => 'مكتملة', 'cancelled' => 'ملغاة', 'draft' => 'مسودة'];
        return view('admin.events.edit', compact('event', 'faculties', 'statuses'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $validatedData = $request->validated();
        $validatedData['last_updated_by_admin_id'] = Auth::guard('admin_web')->id();

        if ($request->hasFile('main_image')) {
            if ($event->main_image_url) {
                Storage::disk('public')->delete($event->main_image_url);
            }
            $path = $request->file('main_image')->store('events', 'public');
            $validatedData['main_image_url'] = $path;
        } elseif ($request->boolean('remove_main_image')) { // لإزالة الصورة الحالية
            if ($event->main_image_url) {
                Storage::disk('public')->delete($event->main_image_url);
            }
            $validatedData['main_image_url'] = null;
        }


        $event->update($validatedData);

        return redirect()->route('admin.events.index')
                         ->with('success', 'تم تحديث الفعالية بنجاح.');
    }

    public function destroy(Event $event)
    {
        if ($event->registeredStudents()->exists()) {
            return redirect()->route('admin.events.index')
                             ->with('error', 'لا يمكن حذف الفعالية لوجود طلاب مسجلين فيها.');
        }
        try {
            if ($event->main_image_url) {
                Storage::disk('public')->delete($event->main_image_url);
            }
            $event->delete();
            return redirect()->route('admin.events.index')
                             ->with('success', 'تم حذف الفعالية بنجاح.');
        } catch (\Exception $e) {
            return redirect()->route('admin.events.index')
                             ->with('error', 'حدث خطأ أثناء الحذف.');
        }
    }
}