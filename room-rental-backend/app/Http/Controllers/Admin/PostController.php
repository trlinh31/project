<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Common;
use App\Models\Post;
use Illuminate\Http\Request;
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
    public function __construct(protected readonly PostService $postService) {}


    /**
     * @throws \Exception
     */
    public function store(Request $request)
    {

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'city' => $request->city,
            'district' => $request->district,
            'ward' => $request->ward,
            'detail_address' => $request->detail_address,
            'room_type' => $request->room_type,
            'acreage' => $request->acreage,
            'rent_fee' => $request->rent_fee,
            'electricity_fee' => $request->electricity_fee,
            'water_fee' => $request->water_fee,
            'internet_fee' => $request->internet_fee,
            'extra_fee' => $request->extra_fee,
            'furniture' => $request->furniture,
            'furniture_detail' => $request->furniture_detail,
            'room_number' => $request->room_number,
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);



        foreach ($request->images as $image) {
            $post->images()->create([
                'image' => $image
            ]);
        }
        return response()->json([
            'message' => 'Post added successfully!',
            'post' => $post
        ]);
    }


    public function search(Request $request)
    {

        $query = Post::query();


        if ($request->has('city') && $request->city) {
            $query->where('city', $request->city);
        }


        if ($request->has('room_type') && $request->room_type) {
            $query->where('room_type', $request->room_type);
        }


        if ($request->has('ren_free') && $request->ren_free) {
            $query->where('rent_fee', $request->ren_free);
        }


        if ($request->has('acreage') && $request->acreage) {
            $query->where('acreage', $request->acreage);
        }

      
        $posts = $query->get();

        return response()->json([
            'posts' => $posts
        ]);
    }



    public function changeStatus($id) {

        $post = Post::findOrFail($id);

        $post->update([
            'status' => 'PUBLISH'
        ]);

        return response()->json([
            'message' => 'Post status changed successfully!',
            'post' => $post
        ]);
    }

    /**
     * @throws InvalidTypeException
     * @throws NotIntegerException
     * @throws ArrayWithMixedKeysException
     * @throws MissingConfigurationKeyException
     * @throws IncompatibleTypeException
     * @throws ConfigurationNotFoundException
     */
    public function update(Request $request, $id)
    {

        $post = Post::findOrFail($id);


        $post->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
            'city' => $request->city,
            'district' => $request->district,
            'ward' => $request->ward,
            'detail_address' => $request->detail_address,
            'room_type' => $request->room_type,
            'acreage' => $request->acreage,
            'rent_fee' => $request->rent_fee,
            'electricity_fee' => $request->electricity_fee,
            'water_fee' => $request->water_fee,
            'internet_fee' => $request->internet_fee,
            'extra_fee' => $request->extra_fee,
            'furniture' => $request->furniture,
            'furniture_detail' => $request->furniture_detail,
            'room_number' => $request->room_number,
            'contact_name' => $request->contact_name,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);


        if ($request->has('images')) {
            $post->images()->delete();

            // Thêm lại hình ảnh mới
            foreach ($request->images as $image) {
                $post->images()->create([
                    'image' => $image
                ]);
            }
        }

        return response()->json([
            'message' => 'Post updated successfully!',
            'post' => $post
        ]);
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
        $posts = Post::with('images')->get();
        return $this->respond($posts);

    }
    public function destroy($id)
    {
        $result = $this->postService->destroy(intval($id));
        return $this->respond($result);
    }

    public function show($id)
    {
        // Lấy thông tin bài viết và thông tin vị trí liên quan
        $result = DB::table('posts')
            ->join('location_cities', 'posts.city', '=', 'location_cities.id')
            ->join('location_districts', 'posts.district', '=', 'location_districts.id')
            ->join('location_wards', 'posts.ward', '=', 'location_wards.id')
            ->select(
                'posts.*',
                'location_cities.name as city_name',
                'location_districts.name as district_name',
                'location_districts.lat as district_lat',
                'location_districts.lon as district_lon',
                'location_wards.name as ward_name'
            )
            ->where('posts.id', $id)
            ->first();

        if (!$result) {
            return $this->respondNotFound('Post not found.');
        }


        $images = DB::table('images')
            ->where('post_id', $id)
            ->pluck('image');


        $resultArray = json_decode(json_encode($result), true);


        $resultArray['images'] = $images;

        return $this->respond($resultArray);
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
