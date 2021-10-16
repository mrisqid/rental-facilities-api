<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;
use Illuminate\Support\Facades\Validator;

class RentalController extends Controller
{
  private $status_code = 200;

  // Create Facilities
  public function create(Request $request) {
    $validator = Validator::make($request->all(), [
      "name" => "required",
      "identity_card" => "required",
      "identity_card.*" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
      "phone_number" => "required",
      "organization_image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048",
      "facilities" => "required|array",
      "date" => "required",
      "file.*" => "file|max:2048",
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
      'date' => date('Y-m-d H:i:s',strtotime($request->date)),
      'message' => $request->message,
      'file' => $file_filename
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

  // List Facilities
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
}
