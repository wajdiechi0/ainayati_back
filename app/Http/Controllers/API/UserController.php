<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UserRole;
use App\AffectRequest;
use App\AppointmentRequest;
use App\AffectDoctorPatient;
use App\AffectRequestDoctorNurse;
use App\AffectDoctorNurse;


use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function loginUser()
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success = $user;
            $success['type'] = User::join('user_roles', 'user_roles.id_user', '=', 'users.id')
                ->join('roles', 'roles.id', '=', 'user_roles.id_role')
                ->where('users.id', '=', $user->id)
                ->select('type')
                ->get()[0]['type'];
            $success['token'] =  $user->createToken('ainayati')->accessToken;
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $success,
            ], 200);
        } else {
            return response()->json(['code' => '01', 'status' => '401', 'data' => []], 200);
        }
    }

    public function registerDoctor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'work_address' => '',
            'specialty' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2], 'message'=> 'Email already exists'], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 3]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success, 'message'=> 'You have successfully added a new doctor'], 200);
    }

    public function registerPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'description' => '',
            'weight' => '',
            'height' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2], 'message'=> 'Email already exists'], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 5]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success, 'message'=> 'You have successfully added a new patient'], 200);
    }

    public function registerNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'birthdate' => 'required',
            'home_address' => 'required',
            'work_address' => '',
            'gender' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        try {
            $user = User::create($input);
        } catch (\Exception $e) {
            return response()->json(['code' => '03', 'status' => '500', 'data' => $e->errorInfo[2], 'message'=> 'Email already exists'], 200);
        }
        $userRole = UserRole::create(['id_user' => $user->id, 'id_role' => 4]);
        $success = $user;
        $success['token'] =  $user->createToken('ainayati')->accessToken;
        return response()->json(['code' => '0', 'status' => '200', 'data' => $success, 'message'=> 'You have successfully added a new nurse'], 200);
    }

    public function getProfileInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => "We can't find a user with that id"
            ], 200);
        }
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $user,
        ], 200);
    }
    public function getProfileInfoUsingEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $User = User::where('email', $request->email)->first();
        if (!$User) {
            User::where('id', '=', request('id'))
                        ->delete();
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "User not found"
                    ], 200);
        }
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $User,
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => [],
                'message' => "User not found !"
            ], 200);
        }

        foreach ($request->all() as $key => $value) {
            if ($value=="") {
                $value = " ";
            }
            $user[$key] = $value;
        }
        $user->save();
        return response()->json([
            'code' => '0',
            'status' => '200',
            'data' => $user,
            'message'=> 'You have successfully updated this profile'
        ], 200);
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=>"Please check your entries !"], 200);
        }
        $user = User::where('id', $request->id)->first();
        if (!$user) {
            return response()->json([
                'code' => '04',
                'status' => '404',
                'data' => [],
                'message' => "We can't find you !"
            ], 200);
        }
        if (!Hash::check(request('current_password'), $user->password)) {
            return response()->json(['code' => '03', 'status' => '401', 'data' =>  [], 'message'=>"Your current password is incorrect !"], 200);
        }
        if (request('new_password') == request('current_password')) {
            return response()->json(['code' => '03', 'status' => '401', 'data' =>  [], 'message'=>"Your new password must be different from your current password"], 200);
        }
        $user->password = bcrypt($request->new_password);
        $user->save();

        return response()->json(['code' => '0', 'status' => '200', 'data' =>  $user, 'message' => "Your password has been successfully changed"], 200);
    }

    public function getPatientList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->join('affect_doctor_patients', 'users.id', '=', 'affect_doctor_patients.id_patient')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender','affect_doctor_patients.id_doctor'])
                ->where('user_roles.id_role', '=', 5)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No patients found'], 200);
        }
    }

    public function nursePatientList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_nurse' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $patients = User::join('affect_doctor_patients', 'users.id', '=', 'affect_doctor_patients.id_patient')
                ->join('affect_doctor_nurses', 'affect_doctor_patients.id_doctor', '=', 'affect_doctor_nurses.id_doctor')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender','affect_doctor_nurses.id_doctor'])
                ->where('affect_doctor_nurses.id_nurse', '=', request('id_nurse'))
                ->get();
        if ($patients) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $patients
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No patients found'], 200);
        }
    }
    public function getDoctorPatientList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $users = User::join('affect_doctor_patients', 'users.id', '=', 'affect_doctor_patients.id_patient')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                ->where('affect_doctor_patients.id_doctor', '=', request('id_doctor'))
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No patients found'], 200);
        }
    }


    public function getPatientDoctorList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_patient' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $users = User::join('affect_doctor_patients', 'users.id', '=', 'affect_doctor_patients.id_doctor')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.specialty','users.gender'])
                ->where('affect_doctor_patients.id_patient', '=', request('id_patient'))
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }

    public function getDoctorNurseList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $users = User::join('affect_doctor_nurses', 'users.id', '=', 'affect_doctor_nurses.id_nurse')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.work_address','users.gender'])
                ->where('affect_doctor_nurses.id_doctor', '=', request('id_doctor'))
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No nurses found'], 200);
        }
    }


    public function getNurseDoctorList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_nurse' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $users = User::join('affect_doctor_nurses', 'users.id', '=', 'affect_doctor_nurses.id_doctor')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.specialty','users.gender'])
                ->where('affect_doctor_nurses.id_nurse', '=', request('id_nurse'))
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }

    public function getDoctorList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.specialty','users.gender'])
                ->where('user_roles.id_role', '=', 3)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }

    public function doctorListBySpecialty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'specialty' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.specialty','users.gender'])
                ->where('user_roles.id_role', '=', 3)
                ->where("users.specialty", '=', request('specialty'))
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No doctors found'], 200);
        }
    }

    public function getNurseList(Request $request)
    {
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.work_address','users.gender'])
                ->where('user_roles.id_role', '=', 4)
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No nurses found'], 200);
        }
    }

    public function removeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $userRole = UserRole::where('id_user', '=', request('id'))
                ->delete();
        if (!$userRole) {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'User not found'], 200);
        }
        $user = User::where('id', '=', request('id'))
                ->get();

        if ($user) {
            AffectDoctorPatient::where('id_patient', '=', request('id'))
                ->delete();
            AffectDoctorPatient::where('id_doctor', '=', request('id'))
                ->delete();
            AffectDoctorNurse::where('id_nurse', '=', request('id'))
                ->delete();
            AffectDoctorNurse::where('id_doctor', '=', request('id'))
                ->delete();
            AffectRequest::where('id_patient', '=', request('id'))
                ->delete();
            AffectRequest::where('id_doctor', '=', request('id'))
                ->delete();
            AffectRequestDoctorNurse::where('id_nurse', '=', request('id'))
                ->delete();
            AffectRequestDoctorNurse::where('id_doctor', '=', request('id'))
                ->delete();
            User::where('id', '=', request('id'))
                ->delete();
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $user
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'User not found'], 200);
        }
    }

    public function removeAffectDoctorPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $doctor = User::where('id', '=', request('id_doctor'))
                ->first();
        $patient = User::where('id', '=', request('id_patient'))
                ->first();

        if ($doctor && $patient) {
            $affect = AffectDoctorPatient::where('id_doctor', '=', request('id_doctor'))
                ->where('id_patient', '=', request('id_patient'))->first();
            if ($affect) {
                $affectResult = AffectDoctorPatient::where('id_doctor', '=', request('id_doctor'))
                ->where('id_patient', '=', request('id_patient'))
                ->delete();
                return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $affectResult
            ], 200);
            } else {
                return response()->json(['code' => '04', 'status' => '404', 'data' => 'Affect not found between doctor and patient'], 200);
            }
        } else {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'Doctor or patient not found'], 200);
        }
    }

    public function affectDoctorPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();

        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $patient = User::where('id', '=', request('id_patient'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();

        $rolePatient = UserRole::where('id_user', '=', request('id_patient'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            User::where('id', '=', request('id'))
                        ->delete();
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        if (!$patient || !$rolePatient || $rolePatient->id_role !=5) {
            User::where('id', '=', request('id'))
                        ->delete();
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Patient not found"
                    ], 200);
        }
        $affect = AffectDoctorPatient::where('id_doctor', '=', request('id_doctor'))
        ->where('id_patient', '=', request('id_patient'))
        ->first();
        if ($affect) {
            return response()->json([
                'code' => '01',
                'status' => '401',
                'data' => [],
                'message' => "Patient is already affected to the doctor"
            ], 200);
        } else {
            $affect = AffectDoctorPatient::create($input);
            return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'The patient has been successfully affected to the doctor'], 200);
        }
    }

    public function sendAffectRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $patient = User::where('id', '=', request('id_patient'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        $rolePatient = UserRole::where('id_user', '=', request('id_patient'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        if (!$patient || !$rolePatient || $rolePatient->id_role !=5) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Patient not found"
                    ], 200);
        }
        $affectRequest = AffectRequest::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if ($affectRequest) {
            return response()->json([
                        'code' => '01',
                        'status' => '401',
                        'data' => $affectRequest,
                        'message' => "Request already exists"
                    ], 200);
        }
        $affectDoctorPatient = AffectDoctorPatient::where('id_patient', '=', request('id_patient'))
                                                    ->where('id_doctor', '=', request('id_doctor'))
                                                    ->first();
        if ($affectDoctorPatient) {
            return response()->json([
                        'code' => '01',
                        'status' => '401',
                        'data' => $affectDoctorPatient,
                        'message' => "You are already affected !"
                    ], 200);
        }
        $affect = AffectRequest::create($input);
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'Affect request has been successfully sent'], 200);
    }


    public function sendAppointmentRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $patient = User::where('id', '=', request('id_patient'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        $rolePatient = UserRole::where('id_user', '=', request('id_patient'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        if (!$patient || !$rolePatient || $rolePatient->id_role !=5) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Patient not found"
                    ], 200);
        }
        $appointmentRequest = AppointmentRequest::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if ($appointmentRequest) {
            return response()->json([
                        'code' => '01',
                        'status' => '401',
                        'data' => $appointmentRequest,
                        'message' => "Request already exists"
                    ], 200);
        }
        $affect = AppointmentRequest::create($input);
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'Appointment request has been successfully sent'], 200);
    }

    public function removeAffectDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors()], 200);
        }
        $doctor = User::where('id', '=', request('id_doctor'))
                ->first();
        $nurse = User::where('id', '=', request('id_nurse'))
                ->first();

        if ($doctor && $nurse) {
            $affect = AffectDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                ->where('id_nurse', '=', request('id_nurse'))->first();
            if ($affect) {
                $affectResult = AffectDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                ->where('id_nurse', '=', request('id_nurse'))
                ->delete();
                return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $affectResult
            ], 200);
            } else {
                return response()->json(['code' => '04', 'status' => '404', 'data' => 'Affect not found between doctor and nurse'], 200);
            }
        } else {
            return response()->json(['code' => '04', 'status' => '404', 'data' => 'Doctor or nurse not found'], 200);
        }
    }

    public function affectDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();

        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $nurse = User::where('id', '=', request('id_nurse'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();

        $roleNurse = UserRole::where('id_user', '=', request('id_nurse'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            User::where('id', '=', request('id'))
                        ->delete();
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        if (!$nurse || !$roleNurse || $roleNurse->id_role !=4) {
            User::where('id', '=', request('id'))
                        ->delete();
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Nurse not found"
                    ], 200);
        }
        $affect = AffectDoctorNurse::where('id_doctor', '=', request('id_doctor'))
        ->where('id_nurse', '=', request('id_nurse'))
        ->first();
        if ($affect) {
            return response()->json([
                'code' => '01',
                'status' => '401',
                'data' => [],
                'message' => "Patient is already affected to the doctor"
            ], 200);
        } else {
            $affect = AffectDoctorNurse::create($input);
            return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'The nurse has been successfully affected to the doctor'], 200);
        }
    }

    public function sendAffectRequestDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $nurse = User::where('id', '=', request('id_nurse'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        $roleNurse = UserRole::where('id_user', '=', request('id_nurse'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        if (!$nurse || !$roleNurse || $roleNurse->id_role !=4) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Nurse not found"
                    ], 200);
        }
        $affectRequest = AffectRequestDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_nurse', '=', request('id_nurse'))
                                        ->first();
        if ($affectRequest) {
            return response()->json([
                        'code' => '01',
                        'status' => '401',
                        'data' => $affectRequest,
                        'message' => "Request already exists"
                    ], 200);
        }
        $affectDoctorNurse = AffectDoctorNurse::where('id_nurse', '=', request('id_nurse'))
                                                    ->where('id_doctor', '=', request('id_doctor'))
                                                    ->first();
        if ($affectDoctorNurse) {
            return response()->json([
                        'code' => '01',
                        'status' => '401',
                        'data' => $affectDoctorNurse,
                        'message' => "You are already affected !"
                    ], 200);
        }
        $affect = AffectRequestDoctorNurse::create($input);
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'Affect request has been successfully sent'], 200);
    }


    public function fetchAffectRequestsDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        $input = $request->all();
        $nurses = User::join('affect_request_doctor_nurses', 'affect_request_doctor_nurses.id_nurse', '=', 'users.id')
                        ->where('affect_request_doctor_nurses.id_doctor', '=', request('id_doctor'))
                        ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                        ->get();
        if ($nurses->isEmpty()) {
            return response()->json([
                        'code' => '0',
                        'status' => '200',
                        'data' => [],
                        'message' => "No affect requests found"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $nurses,
            ], 200);
        }
    }

    public function fetchAffectRequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        $input = $request->all();
        $patients = User::join('affect_requests', 'affect_requests.id_patient', '=', 'users.id')
                        ->where('affect_requests.id_doctor', '=', request('id_doctor'))
                        ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                        ->get();
        if ($patients->isEmpty()) {
            return response()->json([
                        'code' => '0',
                        'status' => '200',
                        'data' => [],
                        'message' => "No affect requests found"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $patients,
            ], 200);
        }
    }

    public function fetchNurseAppointmentRequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $nurse = User::where('id', '=', request('id_nurse'))
                ->get();
        if (!$nurse) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Nurse not found"
                    ], 200);
        }
        $input = $request->all();
        $patients = User::join('appointment_requests', 'appointment_requests.id_patient', '=', 'users.id')
                        ->join('affect_doctor_nurses', 'appointment_requests.id_doctor', '=', 'affect_doctor_nurses.id_doctor')
                        ->where('affect_doctor_nurses.id_nurse', '=', request('id_nurse'))
                        ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.gender','appointment_requests.id_doctor'])
                        ->get();
        if ($patients->isEmpty()) {
            return response()->json([
                        'code' => '0',
                        'status' => '200',
                        'data' => [],
                        'message' => "No appointment requests found"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $patients,
            ], 200);
        }
    }
    public function fetchAppointmentRequests(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $doctor = User::where('id', '=', request('id_doctor'))
                ->get();
        $roleDoctor = UserRole::where('id_user', '=', request('id_doctor'))->first();
        if (!$doctor || !$roleDoctor || $roleDoctor->id_role !=3) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Doctor not found"
                    ], 200);
        }
        $input = $request->all();
        $patients = User::join('appointment_requests', 'appointment_requests.id_patient', '=', 'users.id')
                        ->where('appointment_requests.id_doctor', '=', request('id_doctor'))
                        ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.gender'])
                        ->get();
        if ($patients->isEmpty()) {
            return response()->json([
                        'code' => '0',
                        'status' => '200',
                        'data' => [],
                        'message' => "No appointment requests found"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $patients,
            ], 200);
        }
    }
    public function checkAffectDoctorPatient(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affect = AffectDoctorPatient::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if (!$affect) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => false,
                        'message' => "Patient not affected to the doctor"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => true,
                'message' => "Patient is affected to the doctor"
            ], 200);
        }
    }


    public function checkAffectDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affect = AffectDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_nurse', '=', request('id_nurse'))
                                        ->first();
        if (!$affect) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => false,
                        'message' => "Nurse not affected to the doctor"
                    ], 200);
        } else {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => true,
                'message' => "Nurse is affected to the doctor"
            ], 200);
        }
    }

    public function acceptAffectRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affectRequest = AffectRequest::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if (!$affectRequest) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Affect Request not found"
                    ], 200);
        }
        $affectRequest->delete();
        $affect = AffectDoctorPatient::create($input);
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'The patient has been successfully affected to you '], 200);
    }

    public function denyAffectRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affectRequest = AffectRequest::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if (!$affectRequest) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Affect request not found"
                    ], 200);
        }
        $affectRequest->delete();
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affectRequest, 'message'=> 'You have successfully declined the affect request'], 200);
    }

    public function denyAppointmentRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_patient' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $appointmentRequest = AppointmentRequest::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_patient', '=', request('id_patient'))
                                        ->first();
        if (!$appointmentRequest) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Appointment request not found"
                    ], 200);
        }
        $appointmentRequest->delete();
        return response()->json(['code' => '0', 'status' => '200', 'data' => $appointmentRequest, 'message'=> 'You have successfully declined the appointment request'], 200);
    }


    public function acceptAffectRequestDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affectRequest = AffectRequestDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_nurse', '=', request('id_nurse'))
                                        ->first();
        if (!$affectRequest) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Affect Request not found"
                    ], 200);
        }
        $affectRequest->delete();
        $affect = AffectDoctorNurse::create($input);
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affect, 'message'=> 'The nurse has been successfully affected to you '], 200);
    }

    public function denyAffectRequestDoctorNurse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_doctor' => 'required',
            'id_nurse' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $input = $request->all();
        $affectRequest = AffectRequestDoctorNurse::where('id_doctor', '=', request('id_doctor'))
                                        ->where('id_nurse', '=', request('id_nurse'))
                                        ->first();
        if (!$affectRequest) {
            return response()->json([
                        'code' => '04',
                        'status' => '404',
                        'data' => [],
                        'message' => "Affect Request not found"
                    ], 200);
        }
        $affectRequest->delete();
        return response()->json(['code' => '0', 'status' => '200', 'data' => $affectRequest, 'message'=> 'You have successfully declined the affect request'], 200);
    }

    public function getLast24HoursUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                ->where('user_roles.id_role', '=', request('type'))
                ->where('users.created_at', '>', Carbon::now()->subDays(1)->toDateTimeString())
                ->get();
        if ($users) {
            return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $users
            ], 200);
        } else {
            return response()->json(['code' => '04', 'status' => '200', 'data' => 'No users found'], 200);
        }
    }

    public function registrationsPerMonth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['code' => '02', 'status' => '401', 'data' => $validator->errors(), 'message'=> 'Please check your entries !'], 200);
        }
        $users = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                ->where('user_roles.id_role', '=', request('type'))
                ->get();
        $regisrations =[];
        for ($i = 1 ; $i < 13 ; $i++) {
            $usersCount = User::join('user_roles', 'users.id', '=', 'user_roles.id_user')
                ->select(['users.id','users.name','users.email','users.birthdate','users.home_address','users.description','users.height','users.weight','users.gender'])
                ->where('user_roles.id_role', '=', request('type'))
                ->whereMonth(
                    'users.created_at',
                    $i
                )->whereYear(
                    'users.created_at',
                    Carbon::now()->format('Y')
                )->count();
            $regisrations[$i-1] = $usersCount;
        }
        return response()->json([
                'code' => '0',
                'status' => '200',
                'data' => $regisrations
            ], 200);
    }
}
