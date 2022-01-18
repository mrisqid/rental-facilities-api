<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
  private $status_code = 200;

  // Create Rental
  public function create(Request $request) {
    $validator = Validator::make($request->all(), [
      "name" => "required",
      "identity_card" => "required",
      "identity_card.*" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
      "phone_number" => "required",
      "organization_image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
      "facilities" => "required",
      "date_start" => "required",
      "date_end" => "required",
      "file.*" => "file|max:2048",
      "user_id" => "required",
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $identity_card = $request->identity_card;
    $identity_card_filename = time().rand(0, 3). ".".$identity_card->getClientOriginalExtension();
    $identity_card->move("uploads/identity-card/", $identity_card_filename);

    $organization_image = $request->organization_image;
    $organization_image_filename = time().rand(0, 3). ".".$organization_image->getClientOriginalExtension();
    $organization_image->move("uploads/organization-image/", $organization_image_filename);
    
    $file = $request->file;
    $file_filename = time().rand(0, 3). ".".$file->getClientOriginalExtension();
    $file->move("uploads/rental-file/", $file_filename);

    $data_array = array(
      'name' => $request->name,
      'identity_card' => $identity_card_filename,
      'phone_number' => $request->phone_number,
      'organization_name' => $request->organization_name,
      'organization_address' => $request->organization_address,
      'organization_image' => $organization_image_filename,
      'facilities' => $request->facilities,
      'date_start' => date('Y-m-d',strtotime($request->date_start)),
      'date_end' => date('Y-m-d',strtotime($request->date_end)),
      'message' => $request->message,
      'file' => $file_filename,
      'user_id' => $request->user_id,
    );

    $rental = Rental::create($data_array);

    if (!is_null($rental)) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "message" => "Rental created successfully",
        "data" => $rental
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Rental failed to create"
      ]);
    }
  }

  // List Rental
  public function list() {
    $rental = Rental::all();

    if (count($rental) > 0) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "count" => count($rental),
        "data" => $rental
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Whoops! No data found"
      ]);
    }
  }

  // Rental list by userId
  public function listById($id) {
    $rental = array();

    if (!is_null($id)) {
      $rental = Rental::where("user_id", $id)->get();

      return $rental;
    }
  }

  // Rental Detail
  public function detail($id) {
    $rental = array();

    if (!is_null($id)) {
      $rental = Rental::where("id", $id)->first();

      return $rental;
    }
  }

  // Update status rental
  public function updateStatus(Request $request, $id) {
    $rental_array = array(
      "status" => $request->status
    );

    $rental_exist = Rental::find($id);

    if (!is_null($rental_exist)) {
      $updated_status = Rental::where("id", $id)->update($rental_array);

      if ($updated_status = 1) {
        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "Rental updated successfully"
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Whoops! rental status failed to update"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No rental found with this id"
      ]);
    }
  }

  // Upload file bukti persetujuan
  public function uploadFileApprove(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      "file_approve.*" => "file|max:2048|required",
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $file = $request->file_approve;
    $file_filename = time().rand(0, 3). ".".$file->getClientOriginalExtension();
    $file->move("uploads/file-approve/", $file_filename);

    $data_array = array(
      'file_approve' => $file_filename,
    );

    $rental_exist = Rental::find($id);

    if (!is_null($rental_exist)) {
      $updated_status = Rental::where("id", $id)->update($data_array);

      if ($updated_status = 1) {
        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "File Approve Uploaded successfully"
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Whoops! File Approve failed to update"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No rental found with this id"
      ]);
    }
  }
}
