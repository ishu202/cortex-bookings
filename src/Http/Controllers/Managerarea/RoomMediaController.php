<?php

declare(strict_types=1);

namespace Cortex\Bookings\Http\Controllers\Managerarea;

use Cortex\Bookings\Models\Room;
use Spatie\MediaLibrary\Models\Media;
use Cortex\Foundation\DataTables\MediaDataTable;
use Cortex\Foundation\Http\Requests\ImageFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RoomMediaController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = Room::class;

    /**
     * {@inheritdoc}
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null): void
    {
        $middleware = [];
        $parameter = $parameter ?: snake_case(class_basename($model));

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:update,{$modelName}"][] = $method;
            $middleware["can:{$ability},media"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

    /**
     * List room media.
     *
     * @param \Cortex\Bookings\Models\Room                 $room
     * @param \Cortex\Foundation\DataTables\MediaDataTable $mediaDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function index(Room $room, MediaDataTable $mediaDataTable)
    {
        return $mediaDataTable->with([
            'resource' => $room,
            'tabs' => 'managerarea.rooms.tabs',
            'phrase' => trans('cortex/bookings::common.rooms'),
            'id' => "managerarea-rooms-{$room->getRouteKey()}-media-table",
            'url' => route('managerarea.rooms.media.store', ['room' => $room]),
        ])->render('cortex/foundation::managerarea.pages.datatable-media');
    }

    /**
     * Store new room media.
     *
     * @param \Cortex\Foundation\Http\Requests\ImageFormRequest $request
     * @param \Cortex\Bookings\Models\Room                      $room
     *
     * @return void
     */
    public function store(ImageFormRequest $request, Room $room): void
    {
        $room->addMediaFromRequest('file')
             ->sanitizingFileName(function ($fileName) {
                 return md5($fileName).'.'.pathinfo($fileName, PATHINFO_EXTENSION);
             })
             ->toMediaCollection('default', config('cortex.bookings.media.disk'));
    }

    /**
     * Destroy given room media.
     *
     * @param \Cortex\Bookings\Models\Room      $room
     * @param \Spatie\MediaLibrary\Models\Media $media
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Room $room, Media $media)
    {
        $room->media()->where($media->getKeyName(), $media->getKey())->first()->delete();

        return intend([
            'url' => route('managerarea.rooms.media.index', ['room' => $room]),
            'with' => ['warning' => trans('cortex/foundation::messages.resource_deleted', ['resource' => 'media', 'identifier' => $media->getRouteKey()])],
        ]);
    }
}