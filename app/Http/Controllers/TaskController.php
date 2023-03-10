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
use Illuminate\Support\Facades\App;
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

        App::setLocale($request->input('locale'));

        // Determine the current language
        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'user_id' => 'required'
            ]
            );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
                'user_id' => 'required'
            ],
                [
                    'title.required' => '?????? ?????????? ?????????? ????????????!',
                    'description.required' => '?????? ?????????? ?????? ????????????!',
                    'user_id.required' => '?????? ???????????? ????????????????!',
                    'start_date.required' => '?????? ?????????? ?????????? ?????????? ????????????',
                    'start_date.date' => '?????????? ?????? ?????? ???????? ??????????',
                    'start_date.before' => '?????????? ?????????? ?????????????? ?????? ?????????? ??????????????',
                    'end_date.required' => '?????? ?????????? ?????????? ?????????? ????????????',
                    'end_date.date' => '?????????? ?????? ?????? ???????? ??????????',
                    'end_date.after' => '?????????? ?????????? ?????????????? ?????? ?????????? ??????????????',

                ]
            );
        }



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

            if ($language == 'en') {
                return redirect()->route('tasks')->with('success','Task Created Successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('tasks')->with('success','???? ?????????? ???????????? ??????????');
            }

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
            return redirect()->back()->with('success','???? ?????????? ???????????? ??????????');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function addNote(Task $task,Request $request)
    {

        App::setLocale($request->input('locale'));

        // Determine the current language
        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'note' => 'required',
            ]
            );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'note' => 'required',
            ],
                [
                    'note.required' => '?????? ?????????? ????????????????!',
                ]
            );
        }


        DB::beginTransaction();
        try {

            $task_note = TaskNote::create([
                'task_id' => $task->id,
                'admin_id' => auth()->user()->id,
                'note' => $request->note
            ]);

            // Commit And Redirected To Listing
            DB::commit();

            if ($language == 'en') {
                return redirect()->back()->with('success','Note Added To The Task Successfully');
            } elseif ($language == 'ar') {
                return redirect()->back()->with('success','???? ?????????? ???????????? ???????????? ??????????');
            }
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

        App::setLocale($request->input('locale'));

        // Determine the current language
        $language = App::getLocale();

        if ($language == 'en') {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
            ]
            );
        } elseif ($language == 'ar') {
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required',
                'start_date' => 'required|date|before:end_date',
                'end_date' => 'required|date|after:start_date',
            ],
                [
                    'title.required' => '?????? ?????????? ?????????? ????????????!',
                    'description.required' => '?????? ?????????? ?????? ????????????!',
                    'start_date.required' => '?????? ?????????? ?????????? ?????????? ????????????',
                    'start_date.date' => '?????????? ?????? ?????? ???????? ??????????',
                    'start_date.before' => '?????????? ?????????? ?????????????? ?????? ?????????? ??????????????',
                    'end_date.required' => '?????? ?????????? ?????????? ?????????? ????????????',
                    'end_date.date' => '?????????? ?????? ?????? ???????? ??????????',
                    'end_date.after' => '?????????? ?????????? ?????????????? ?????? ?????????? ??????????????',
                ]
            );        }



        DB::beginTransaction();
        try {
            $task = Task::where('id',$task->id)->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            if ($language == 'en') {
                return redirect()->route('tasks')->with('success','Task Updated Successfully');
            } elseif ($language == 'ar') {
                return redirect()->route('tasks')->with('success','???? ?????????? ???????????? ??????????');
            }
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
    public function destroy(Request $request,Task $task)
    {
        DB::beginTransaction();
        try {

            App::setLocale($request->input('locale'));

            // Determine the current language
            $language = App::getLocale();

            // delete task
            $task_deleted = Task::find($task->id)->delete();



            // Commit And Redirected To Listing
            DB::commit();
            if ($language == 'en') {
                return redirect()->back()->with('success','Task Deleted Successfully');
            } elseif ($language == 'ar') {
                return redirect()->back()->with('success','???? ?????? ???????????? ??????????');
            }

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }
}
