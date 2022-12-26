<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskNote;
use App\Models\TaskUser;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $tasks = Task::where('admin_id' , auth()->user()->id)->latest()->get();
        return view('dashboard/src/tasks',['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        $users = User::where('company_NO', auth()->user()->company_NO)->where('role_id',3)->orderBy('id','DESC')->get();
        return view('dashboard.src.add-task',['users'=>$users]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'user_id' => 'required'
        ],
            [
                'title.required' => 'يجب ادخال عنوان المهمة!',
                'description.required' => 'يجب ادخال وصف المهمة!',
                'user_id.required' => 'يجب اختيار الموظفين!',
                'start_date.required' => 'يجب ادخال تاريخ بداية المهمة',
                'start_date.date' => 'الحقل يجب انا يكون تاريخ',
                'start_date.before' => 'تحديد تاريخ البداية قبل تاريخ النهاية',
                'end_date.required' => 'يجب ادخال تاريخ نهاية المهمة',
                'end_date.date' => 'الحقل يجب انا يكون تاريخ',
                'end_date.after' => 'تحديد تاريخ النهاية بعد تاريخ البداية',

            ]
        );

        DB::beginTransaction();
        try {
            $task = Task::create([
                'admin_id' => auth()->user()->id,
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $task_id = $task->id;

            foreach ($request->user_id as $key => $user_id) {
                $task_user = TaskUser::create([
                    'task_id' => $task_id,
                    'user_id' => $user_id,
                ]);
            }



            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('tasks')->with('success','تم انشاء المهمة بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function show(Task $task)
    {
        return view('dashboard.src.show-task',
            [
                'task'=>$task,
                ]);
    }

    public function closed(Task $task)
    {


        DB::beginTransaction();
        try {
            $task = Task::where('id',$task->id)->update([
                'status' => 'closed'
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','تم تعديل المهمة بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function addNote(Task $task,Request $request)
    {

        $this->validate($request, [
            'note' => 'required',
        ],
            [
                'note.required' => 'يجب ادخال الملاحظة!',
            ]
        );

        DB::beginTransaction();
        try {

            $task_note = TaskNote::create([
                'task_id' => $task->id,
                'admin_id' => auth()->user()->id,
                'note' => $request->note
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','تم اضافة ملاحظة للمهمة بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit(Task $task)
    {
        return view('dashboard.src.edit-task',
            ['task'=>$task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Task $task)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
//            'user_id' => 'required'
        ],
            [
                'title.required' => 'يجب ادخال عنوان المهمة!',
                'description.required' => 'يجب ادخال وصف المهمة!',
//                'user_id.required' => 'يجب اختيار الموظفين!',
                'start_date.required' => 'يجب ادخال تاريخ بداية المهمة',
                'start_date.date' => 'الحقل يجب انا يكون تاريخ',
                'start_date.before' => 'تحديد تاريخ البداية قبل تاريخ النهاية',
                'end_date.required' => 'يجب ادخال تاريخ نهاية المهمة',
                'end_date.date' => 'الحقل يجب انا يكون تاريخ',
                'end_date.after' => 'تحديد تاريخ النهاية بعد تاريخ البداية',
            ]
        );

        DB::beginTransaction();
        try {
            $task = Task::where('id',$task->id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

//            $task_id = $task->id;
//
//            foreach ($request->user_id as $key => $user_id) {
//                $task_user = TaskUser::create([
//                    'task_id' => $task_id,
//                    'user_id' => $user_id,
//                ]);
//            }



            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('tasks')->with('success','تم تعديل المهمة بنجاح');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Task $task)
    {
        DB::beginTransaction();
        try {

            // delete task
            $task_deleted = Task::find($task->id)->delete();



            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->back()->with('success','تم حذف المهمة بنجاح');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
}
