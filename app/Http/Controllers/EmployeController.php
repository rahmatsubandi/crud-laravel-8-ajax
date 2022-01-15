<?php

namespace App\Http\Controllers;

use App\Models\Employe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeController extends Controller
{
    // set index page view
    public function index()
    {
        return view('index');
    }

    // handle fetch all data employe ajax request
    public function fetchAll()
    {
        $emps = Employe::all();
        $output = '';
        if ($emps->count() > 0) {
            $output .= '<table class="table table-striped table-sm text-center align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Post</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
            foreach ($emps as $emp) {
                $output .= '<tr>
                    <td>' . $emp->id . '</td>
                    <td><img src="storage/images/' . $emp->avatar . '" width="50"class="img-thumbnail rounded-circle"></td>
                    <td>' . $emp->first_name . ' ' . $emp->last_name . '</td>
                    <td>' . $emp->email . '</td>
                    <td>' . $emp->post . '</td>
                    <td>' . $emp->phone . '</td>
                    <td>
                        <a href="javascript:void(0)" id="' . $emp->id . '" class="btn btn-info btn-sm mx-1 text-light editIcon" data-bs-toggle="modal" data-bs-target="#editEmployeModal"><i class="bi-pencil-square"></i></a>
                        <a href="javascript:void(0)" id="' . $emp->id . '" class="btn btn-danger btn-sm mx-1 deleteIcon"><i class="bi-trash"></i></a>
                    </td>
                </tr>';
            }
            $output .= '</tbody></table>';
            echo $output;
        } else {
            echo '<h1 class="text-center text-secondary my-5">No record present in the database!</h1>';
        }
    }

    // handle insert a new employe ajax request
    public function store(Request $request)
    {
        $file = $request->file('avatar');
        $fileName = time() . '.' . md5(rand(1, 9999)) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/images', $fileName);

        $empData = [
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'post' => $request->post,
            'avatar' => $fileName
        ];
        Employe::create($empData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle edit an employe ajax request
    public function edit(Request $request)
    {
        $id = $request->id;
        $emp = Employe::find($id);
        return response()->json($emp);
    }

    // handle update an employe ajax request
    public function update(Request $request)
    {
        $fileName = '';
        $emp = Employe::find($request->emp_id);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $fileName = time() . '.' . md5(rand(1, 9999)) . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images', $fileName);
            if ($emp->avatar) {
                Storage::delete('public/images/' . $emp->avatar);
            }
        } else {
            $fileName = $request->emp_avatar;
        }

        $empData = [
            'first_name' => $request->fname,
            'last_name' => $request->lname,
            'email' => $request->email,
            'phone' => $request->phone,
            'post' => $request->post,
            'avatar' => $fileName
        ];

        $emp->update($empData);
        return response()->json([
            'status' => 200,
        ]);
    }

    // handle delete an employe ajax request
    public function delete(Request $request)
    {
        $id = $request->id;
        $emp = Employe::find($id);
        if (Storage::delete('public/images/' . $emp->avatar)) {
            Employe::destroy($id);
        }
    }
}
