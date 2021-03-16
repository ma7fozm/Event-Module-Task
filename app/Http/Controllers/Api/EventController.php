<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    use ApiResponseTrait;

    public function createEvent(EventRequest $request): JsonResponse
    {
        if ($request->validator->fails()) {
            return $this->apiResponse(null, $request->validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event = Event::create($request->all());
        return $this->apiResponse(new EventResource($event), 'Event created successfully', Response::HTTP_CREATED);
    }

    public function getEvents(): JsonResponse
    {
        $events = Event::latest()->get();
        return $this->apiResponse(EventResource::collection($events), 'Events list', Response::HTTP_OK);
    }

    public function approveEvent($id)
    {
        optional(Event::find($id)->update(['status' => 'approved']));
        $events = Event::all();
        return $this->apiResponse(EventResource::collection($events), 'Event updated successfully', Response::HTTP_OK);
    }
}
