<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use App\DepartmentUser;
use App\DepartmentFile;
use App\User;
use Storage;

class DepartmentController extends Controller
{
    public function department($id = null)
    {
        if(is_null($id)) {
            if(auth()->user()->hasPermission('global_administrator')) {
                $departments = Department::all();
                return json_encode([
                    'success' => true,
                    'message' => 'All departments have been returned.',
                    'data' => $departments
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'You do not have permission to access this area.'
                ]);
            }
        } else {
            $department = Department::find($id);
            if($department) {
                if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $department->id)) {
                    return json_encode([
                        'success' => true,
                        'message' => 'Department has been returned.',
                        'data' => $department
                    ]);
                } else {
                    return json_encode([
                        'success' => false,
                        'message' => 'You do not have permission to access this area.'
                    ]);
                }
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'Department not found!'
                ]);
            }
        }
    }

    public function update_user_permission(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $user = DepartmentUser::where('user_id', $request->userid)->where('department_id', $request->departmentid)->first();
            if($user) {
                $user->permission_id = $request->permissionid;
                $user->save();
            }
        }
    }

    public function delete_user_permission(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $user = DepartmentUser::where('user_id', $request->userid)->where('department_id', $request->departmentid)->first();
            if($user) {
                $user->delete();

                return json_encode([
                    'success' => true,
                    'message' => 'Permission deleted.'
                ]);
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'Failed to delete user permission. Please try again.'
                ]);
            }
        }
    }

    public function add_new_user(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator')) {
            $user_exists = User::where('username', $request->username)->first();
            if($user_exists) {
                $user_is_global_administrator = DepartmentUser::where('user_id', $user_exists->id)->where('permission_id', 1)->first();
                if($user_is_global_administrator) {
                    return json_encode([
                        'success' => false,
                        'message' => 'This user is already a Global Administrator.'
                    ]);
                } else {
                    $already_has_permission = DepartmentUser::where('user_id', $user_exists->id)->where('department_id', $request->departmentid)->first();
                    if($already_has_permission) {
                        return json_encode([
                            'success' => false,
                            'message' => 'This user already has an assigned permission for this Department.'
                        ]);
                    } else {
                        $new_permission = new DepartmentUser;
                        $new_permission->user_id = $user_exists->id;
                        $new_permission->department_id = $request->departmentid;
                        $new_permission->permission_id = $request->permissionid;
                        $new_permission->save();

                        return json_encode([
                            'success' => true,
                            'message' => 'New permission applied successfully.'
                        ]);
                    }
                }
            } else {
                return json_encode([
                    'success' => false,
                    'message' => 'No user could be found with the username ['.$request->username.']. Make sure to have the user authenticate first so their internal user ID can be generated.'
                ]);
            }
        }
    }

    public function check_current_user(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $request->departmentid)) {
            return json_encode([
                'success' => true
            ]);
        } else {
            return json_encode([
                'success' => false
            ]);
        }
    }

    public function delete_file(Request $request)
    {
        if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $request->departmentid)) {
            $file = DepartmentFile::find($request->fileid);
            if($file) {
                Storage::delete($file->path);

                $file->delete();

                return json_encode([
                    'success' => true
                ]);
            }
        }
    }

    public function upload_new_files($departmentid, Request $request)
    {
        if($request->hasFile('attachments')) {
            $department = Department::find($departmentid);

            $files = $request->attachments;
            if(count($files)) {
                foreach($files as $file) {
                    $new_file = $file->store($department->uri);

                    $new_file_upload = new DepartmentFile;
                    $new_file_upload->department_id = $department->id;
                    $new_file_upload->uploaded_by = auth()->user()->id;
                    $new_file_upload->name = $file->getClientOriginalName();
                    $new_file_upload->path = $new_file;
                    $new_file_upload->mime = Storage::mimeType($new_file);
                    $new_file_upload->size = $file->getClientSize();
                    $new_file_upload->save();
                }

                return json_encode([
                    'success' => true
                ]);
            } else {
                $new_file = $files->store($department->uri);

                $new_file_upload = new DepartmentFile;
                $new_file_upload->department_id = $department->id;
                $new_file_upload->uploaded_by = auth()->user()->id;
                $new_file_upload->name = $files->getClientOriginalName();
                $new_file_upload->path = $new_file;
                $new_file_upload->mime = Storage::mimeType($new_file);
                $new_file_upload->size = $files->getClientSize();
                $new_file_upload->save();

                return json_encode([
                    'success' => true
                ]);
            }
        } else {
            return json_encode([
                'success' => false,
                'errors' => 'no files'
            ]);
        }
    }

    public function get_file_url($department_id, Request $request)
    {
        $department = Department::find($department_id);
        if($department) {
            if(auth()->user()->hasPermission('global_administrator') || auth()->user()->hasPermission('department_administrator', $department->id) || auth()->user()->hasPermission('department_user', $department->id)) {
                $file = DepartmentFile::where('department_id', $department->id)->where('id', $request->file_id)->first();
                if($file) {
                    return Storage::url($file->path);
                }
            }
        }
    }
}
