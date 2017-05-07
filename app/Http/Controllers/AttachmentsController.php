<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

use App\Attachment;
use App\Http\Requests\AttachmentRequest;

class AttachmentsController extends Controller
{

  public function store(AttachmentRequest $request)
  {
    $type = $request->get('attachable_type');
    $id = $request->get('attachable_id');
    $file  =$request->file('image');

    if(class_exists($type) && method_exists($type, 'attachments')) {
      $subject = call_user_func($type . '::find', $id);
      if($subject) {
        $attachment = new Attachment($request->only('attachable_type', 'attachable_id'));

        $attachment->uploadFile($file);
        $attachment->save();

        return $attachment;
      } else {
        return new JsonResponse([ 'attachable_id' => 'This content cannot receive attachments' ], 422);
      }
    } else {
      return new JsonResponse([ 'attachable_type' => 'This content cannot receive attachments' ], 422);
    }
  }

}
