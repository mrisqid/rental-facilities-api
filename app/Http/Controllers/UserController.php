<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
  private $status_code = 200;

  // Create User
  public function createUser(Request $request) {
    $validator = Validator::make($request->all(), [
      "username" => "required",
      "email" => "required|email",
      "password" => "required",
      "level",
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $userDataArray = array(
      "username" => $request->username,
      "email" => $request->email,
      "password" => md5($request->password),
      "level" => $request->level
    );

    $user_status = User::where("email", $request->email)->first();

    if (!is_null($user_status)) {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Whoops! Email already registered"
      ]);
    }

    $user = User::create($userDataArray);

    if (!is_null($user)) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "message" => "User account created successfully",
        "data" => $user
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "failed to create"
      ]);
    }
  }

  // User List
  public function userList() {
    $users = User::where("level", "admin")->get();
    if (count($users) > 0) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "count" => count($users),
        "data" => $users
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Whoops! no users data found"
      ]);
    }
  }

  // Edit User
  public function userEdit(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      "email" => "email"
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $user_array = array(
      "username" => $request->username,
      "email" => $request->email
    );

    $user_status = User::where("email", $request->email)->first();
    echo($user_status);
    if (!is_null($user_status) && $user_status->id != $id) {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Whoops! Email already registered"
      ]);
    }

    $user = User::find($id);
    
    if (!is_null($user)) {
      $updated_status = User::where("id", $id)->update($user_array);

      if ($updated_status = 1) {
        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "User updated successfully"
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Whoops! user failed to update"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No user found with this id"
      ]);
    }
  }

  // Login
  public function userLogin(Request $request) {
    $validator = Validator::make($request->all(), [
      "email" => "required|email",
      "password" => "required"
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "validation_error" => $validator->errors()
      ]);
    }

    // check if entered emails exists in db
    $email_status = User::where("email", $request->email)->first();

    // if email exists then we will check password for the same email
    if (!is_null($email_status)) {
      $password_status = User::where("email", $request->email)->where("password", md5($request->password))->first();

      // if password correct
      if (!is_null($password_status)) {
        $user = $this->userDetail($request->email);

        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "You have logged in successfully",
          "data" => $user
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "success" => false,
          "message" => "Unable to login. Email / Password wrong"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Unable to login. Email doesn't exist"
      ]);
    }
  }

  // Get User by Email
  public function userDetail($email) {
    $user = array();

    if ($email != "") {
      $user = User::where("email", $email)->first();

      return $user;
    }
  }

  // get User by Id
  public function get($id) {
    $user = User::find($id);

    if (!is_null($user)) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "data" => $user
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No data found with this id"
      ]);
    }
  }

  // Delete User
  public function userDelete($id) {
    $user = User::find($id);

    if (!is_null($user)) {
      $delete_status = User::where("id", $id)->delete();

      if ($delete_status == 1) {
        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "User has been deleted successfully"
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "User failed to delete, please try again"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No user found with this id"
      ]);
    }
  }
}
