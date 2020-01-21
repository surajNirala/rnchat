<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\Profile;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * @var
     */
    protected $user;

    /**
     * TaskController constructor.
     */
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
	 * @return mixed
	 */
	public function index()
	{
	    $profile = $this->user->profile()->get(/*['title', 'description']*/)->toArray();

	    return $profile;
	}
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function show($id)
	{
	    $profile = $this->user->profile()->find($id);

	    if (!$profile) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, profile with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    return $profile;
	}
	/**
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function store(Request $request)
	{
	    $this->validate($request, [
	        'profile_image' => 'required',
	        'description' => 'required',
	    ]);

	    $profile = new Profile();
	    $profile->profile_image = $request->profile_image;
	    $profile->description = $request->description;

	    if ($this->user->profile()->save($profile))
	        return response()->json([
	            'success' => true,
	            'profile' => $profile
	        ]);
	    else
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, profile could not be added.'
	        ], 500);
	}

	/**
	 * @param Request $request
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function update(Request $request, $id)
	{
	    $profile = $this->user->profile()->find($id);

	    if (!$profile) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, profile with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    $updated = $profile->fill($request->all())->save();

	    if ($updated) {
	        return response()->json([
	            'success' => true,
	            'message' => 'Profile Update successfully.'
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, profile could not be updated.'
	        ], 500);
	    }
	}
	/**
	 * @param $id
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function destroy($id)
	{
	    $profile = $this->user->profile()->find($id);

	    if (!$profile) {
	        return response()->json([
	            'success' => false,
	            'message' => 'Sorry, profile with id ' . $id . ' cannot be found.'
	        ], 400);
	    }

	    if ($profile->delete()) {
	        return response()->json([
	            'success' => true,
	            'message' => 'Profile delete successfully.'
	        ]);
	    } else {
	        return response()->json([
	            'success' => false,
	            'message' => 'Profile could not be deleted.'
	        ], 500);
	    }
	}
}
