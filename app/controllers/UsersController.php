<?php

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /users
	 *
	 * @return Response
	 */
	public function index()
	{
		$users=User::all();
		return Response::json($users, 200);

	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($username)
	{
		$user = User::findOrFail($username);

		if ($user != false)
			return Response::json($user, 200);
		else
			return Response::make("", 404);
	}

	public function tasks($username)
	{
		$tasks=Task::all();
		if (!($user = User::findOrFail($username)))
			return Response::make("", 404);

		$usertasks=array();

		foreach ($tasks as $key => $task) {
			if ($task->user == $user->username)
				array_push($usertasks, $task);
		}

		return Response::json($usertasks, 200);
	}

}
