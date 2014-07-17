<?php

class TasksController extends \BaseController {

	/**
	 * Display a listing of tasks
	 *
	 * @return Response
	 */
	public function index()
	{
		$tasks = Task::all();

		return Response::json($tasks, 200);
	}

	

	/**
	 * Spawn a newly created task.
	 *
	 * @return Response
	 */
	public function store()
	{

		$r = Task::create(Input::all());
		if ($r != null)
			return Response::json($r, 201);
		else
			return Response::make("", 422);
	}

	/**
	 * Display the specified task.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$task = Task::findOrFail($id);

		if ($task != false)
			return Response::json($task, 200);
		else
			return Response::make("", 404);
	}


	/**
	 * Reprioritize the specified task in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

		$task = Task::findOrFail($id);

		if ($task == false)
			return Response::make(null, 404);

		$r= $task->update(Input::all());

		if ($r != 0)
			return Response::make("", 422);

		return Response::make("", 204);
	}

	/**
	 * Remove the specified task.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$task = Task::findOrFail($id);
		if ($task == false)
			return Response::make(null, 404);
		$ret=$task->destroy();
		
		if ($ret != 0)
			return Response::make("", 403);
		else
			return Response::make("", 204);

	}

}
