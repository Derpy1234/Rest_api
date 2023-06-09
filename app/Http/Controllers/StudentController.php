<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Student::all();

        if ($data) {
            return ApiFormatter::createApi(200, 'succsess', $data);
        }else{
            return ApiFormatter::createApi(400, 'failed');
        }

    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'nama' => 'required',
                'email' => 'required',
                'tgl_lahir' => 'required',
                'no_tlpn' => 'required',
                'linkedin' => 'required',
                'instagram' => 'required',
                'facebook' => 'required',
                'twitter' => 'required',
            ]);

            $images = null;
            if($request->file){
                $extension = $request->file('file')->getClientOriginalExtension();
                $images = $request->nama.'-'.now()->timestamp.'.'.$extension;
                $request->file('file')->move(public_path('storage/'), $images);
            }
            $request['image'] = $images;

            $student = Student::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'tgl_lahir' => $request->tgl_lahir,
                'no_tlpn' => $request->no_tlpn,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'image' =>  $images,
            ]);

            $test = Student::where('id', '=', $student->id)->get();
            if ($test) {
                return ApiFormatter::createApi(200, 'succsess', $test);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
         }
        catch(Exception $error){
            return ApiFormatter::createApi(400, 'failed', $error);
        }
    }

    public function createToken()
    {
        return csrf_token();
    }


    /**
     * Display the specified resource.
     */
    public function show(Student $student,$id)
    {
        try{
            $studentDetail = Student::where('id', $id)->first();
            if ($studentDetail) {
                return ApiFormatter::createApi(200, 'succsess', $studentDetail);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }

        }
        catch(Exception $error){
            return ApiFormatter::createApi(400, 'failed', $error);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try{
            $request->validate([
                'nama' => 'required',
                'email' => 'required',
                'tgl_lahir' => 'required',
                'no_tlpn' => 'required',
                'linkedin' => 'required',
                'instagram' => 'required',
                'facebook' => 'required',
                'twitter' => 'required',
                'image' => 'required',
            ]);

            if($request->file('image'))
            {
                $extension = $request->file('image')->getClientOriginalExtension();
                $newName = $request->title.'-'.now()->timestamp.'.'.$extension;
                $request->file('image')->storeAs('cover', $newName);  
                $request['cover'] = $newName;
            }

            $siswa = Student::findOrFail($id);

            $siswa->update([
                'nama' => $request->nama,
                'email' => $request->email,
                'tgl_lahir' => $request->tgl_lahir,
                'no_tlpn' => $request->no_tlpn,
                'linkedin' => $request->linkedin,
                'instagram' => $request->instagram,
                'facebook' => $request->facebook,
                'twitter' => $request->twitter,
                'image' => $request->image,
            ]);

            $updateDataSiswa = Student::where('id', $siswa->id)->first();
            if ($updateDataSiswa) {
                return ApiFormatter::createApi(200, 'succsess', $updateDataSiswa);
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }

        }
        catch(Exception $error){
            return ApiFormatter::createApi(400, 'failed', $error);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student, $id)
    {
        try{
            $getdata = Student::findOrFail($id);
            $delete = $getdata->delete();
            if ($delete) {
                return ApiFormatter::createApi(200, 'succsess');
            }else{
                return ApiFormatter::createApi(400, 'failed');
            }
        }catch(Exception $error){
            return ApiFormatter::createApi(400, 'failed', $error);
        }
    }
}
