<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use MarcinOrlowski\ResponseBuilder\Exceptions\NotIntegerException;
use MarcinOrlowski\ResponseBuilder\Exceptions\InvalidTypeException;
use MarcinOrlowski\ResponseBuilder\Exceptions\IncompatibleTypeException;
use MarcinOrlowski\ResponseBuilder\Exceptions\ArrayWithMixedKeysException;
use MarcinOrlowski\ResponseBuilder\Exceptions\ConfigurationNotFoundException;
use MarcinOrlowski\ResponseBuilder\Exceptions\MissingConfigurationKeyException;

class PostController extends Controller
{
    public function __construct(protected readonly PostService $postService)
    {
    }


    /**
     * @throws \Exception
     */
    public function store(CreatePostRequest $request): Response
    {
        $result = $this->postService->store($request->only(
            'title', 'description', 'images', 'status',
            'city', 'district', 'ward', 'detail_address', 'lat', 'lon',
            'room_type', 'acreage', 'rent_fee', 'electricity_fee', 'water_fee', 'internet_fee', 'extra_fee',
            'furniture', 'furniture_detail', 'room_number',
            'contact_name', 'contact_email', 'contact_phone'
        ));

        return $this->respond($result);
    }

    /**
     * @throws InvalidTypeException
     * @throws NotIntegerException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     */
    public function update($id, CreatePostRequest $request)
    {
        $result = $this->postService->update(intval($id), $request->only(
            'title', 'description', 'images', 'status',
            'city', 'district', 'ward', 'detail_address', 'lat', 'lon',
            'room_type', 'acreage', 'rent_fee', 'electricity_fee', 'water_fee', 'internet_fee', 'extra_fee',
            'furniture', 'furniture_detail', 'room_number',
            'contact_name', 'contact_email', 'contact_phone'
        ));

        return $this->respond($result);
    }

    /**
     * @throws InvalidTypeException
     * @throws NotFoundExceptionInterface
     * @throws NotIntegerException
     * @throws ContainerExceptionInterface
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     */
    public function index()
    {
        $posts = Post::get();
        return $this->respond($posts);
    }

    public function destroy($id)
    {
        $result = $this->postService->destroy(intval($id));
        return $this->respond($result);
    }

    public function show($id)
    {
        $result = DB::table('posts')
                ->join('location_cities', 'posts.city', '=', 'location_cities.id')
                ->join('location_districts', 'posts.district', '=', 'location_districts.id')
                ->join('location_wards', 'posts.ward', '=', 'location_wards.id')
                ->select('posts.*', 'location_cities.name as city_name', 
                'location_districts.lat as district_lat', 
                'location_districts.lon as district_lon', 
                'location_wards.name as ward_name')
                ->where('posts.id', $id);
        return $this->respond($result);
    }

    public function getFavoritePost()
    {
        Log::info('ChienTT getFavoritePost');
        $result = $this->postService->getFavoritePost(auth()->id());
        return $this->respond($result);
    }

    public function saveFavorite($postId)
    {
        $this->postService->saveFavoritePost($postId, auth()->id());
        return $this->respond(true);
    }

    public function deleteFavorite($postId)
    {
        $this->postService->deleteFavorite($postId);
        return $this->respond(true);
    }
}
