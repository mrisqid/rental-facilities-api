<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facilities;
use Illuminate\Support\Facades\Validator;

class FacilitiesController extends Controller
{
  private $status_code = 200;

  // Create Facilities
  public function create(Request $request) {
    $validator = Validator::make($request->all(), [
      "name" => "required",
      "type" => "required",
      "image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $image = $request->image;
    $filename = time().rand(0, 3). ".".$image->getClientOriginalExtension();
    $image->move("uploads/", $filename);

    $data_array = array(
      "name" => $request->name,
      "type" => $request->type,
      "image" => $filename
    );

    $facility = Facilities::create($data_array);

    if (!is_null($facility)) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "message" => "Facility created successfully",
        "data" => $facility
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Facility failed to create"
      ]);
    }
  }

  // List Facilities
  public function list() {
    $facilites = Facilities::all();

    if (count($facilites) > 0) {
      return response()->json([
        "status" => $this->status_code,
        "success" => true,
        "count" => count($facilites),
        "data" => $facilites
      ]);
    } else {
      return response()->json([
        "status" => "failed",
        "success" => false,
        "message" => "Whoops! No data found"
      ]);
    }
  }

  // Edit Facility
  public function edit(Request $request, $id) {
    $validator = Validator::make($request->all(), [
      "image.*" => "image|mimes:jpeg,png,jpg,gif,svg|max:2048"
    ]);

    if ($validator->fails()) {
      return response()->json([
        "status" => "failed",
        "message" => "validation_error",
        "errors" => $validator->errors()
      ]);
    }

    $facility = Facilities::find($id);
    $isImgSame = $facility->image == $request->image;

    if (!empty($request->image) && !($isImgSame)) {
      $image = $request->image;
      $filename = time().rand(0, 3). ".".$image->getClientOriginalExtension();
      $image->move("uploads/", $filename);
  
      $data_array = array(
        "name" => $request->name,
        "type" => $request->type,
        "image" => $filename
      );
  
      if (!is_null($facility)) {
        $updated_status = Facilities::where("id", $id)->update($data_array);
  
        if ($updated_status = 1) {
          return response()->json([
            "status" => $this->status_code,
            "success" => true,
            "message" => "Facility updated successfully"
          ]);
        } else {
          return response()->json([
            "status" => "failed",
            "message" => "Whoops! Facility failed to update"
          ]);
        }
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Whoops! No data found with this id"
        ]);
      }
    } else {
      $data_array = array(
        "name" => $request->name,
        "type" => $request->type
      );
  
      if (!is_null($facility)) {
        $updated_status = Facilities::where("id", $id)->update($data_array);
  
        if ($updated_status = 1) {
          return response()->json([
            "status" => $this->status_code,
            "success" => true,
            "message" => "Facility updated successfully"
          ]);
        } else {
          return response()->json([
            "status" => "failed",
            "message" => "Whoops! Facility failed to update"
          ]);
        }
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Whoops! No data found with this id"
        ]);
      }
    }
  }

  // Delete Facility
  public function delete($id) {
    $facility = Facilities::find($id);

    if (!is_null($facility)) {
      $delete_status = Facilities::where("id", $id)->delete();

      if ($delete_status == 1) {
        return response()->json([
          "status" => $this->status_code,
          "success" => true,
          "message" => "Facility has been deleted successfully"
        ]);
      } else {
        return response()->json([
          "status" => "failed",
          "message" => "Facility failed to delete"
        ]);
      }
    } else {
      return response()->json([
        "status" => "failed",
        "message" => "Whoops! No data found with this id"
      ]);
    }
  }
}
