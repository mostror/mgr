<?php

class Task  {
	/*public var $user;
	public var $pid;
	public var $cpu;
	public var $mem;
	public var $vsz;
	public var $rss;
	public var $tty;
	public var $stat;
	public var $start;
	public var $time;
	public var $command;*/

	function __construct($user, $pid, $cpu, $mem, $vsz, $rss, $tty, $stat, $start, $time, $command) {
       $this->user = $user;
       $this->pid = $pid;
       $this->cpu = $cpu;
       $this->mem = $mem;
       $this->vsz = $vsz;
       $this->rss = $rss;
       $this->tty = $tty;
       $this->stat = $stat;
       $this->start = $start;
       $this->time = $time;
       $this->command = $command;

   }

	public static function all(){
		$tasks=[];
		$output = shell_exec('ps faxu --no-headers');
		$output=explode("\n", $output);

		foreach ($output as $key => $task) {
			$task = preg_replace('!\s+!', ' ', $task);
			$task = explode(" ", $task);
			if (count($task) > 1)
				array_push($tasks, new Task($task[0], $task[1], $task[2], $task[3], $task[4], $task[5], $task[6], $task[7], $task[8], $task[9], implode(array_slice($task, 10))));
		}
		
		return $tasks;
	}
	
	public static function findOrFail($pid){
		$tasks = Task::all();

		foreach ($tasks as $key => $task) {
			if ($task->pid == $pid) {
				return $task;
			}
		}

		return false;
	}

	public static function create($data){
		$output = array();
		$stdout = array();
		$stderr= array();

		if (!isset($data['output']))
			$pid = exec($data['command'] . ' > /dev/null 2>&1 & echo $!; ', $output);
		else
			$pid = exec($data['command'] . ' > stdout 2>stderr & echo $!; ', $output);
		$task =  Task::findOrFail($pid);

		if ($task != null && !isset($data['output']))
			return $task;

		if ($task == null && isset($data['output']))
		{
			exec("cat stdout", $stdout);
			$r['output'] = $stdout;
			exec("cat stderr", $stderr);
			$r['$error'] = $stderr;
			exec("rm stderr stdout");
			return $r;
		}
		else
			if (isset($data['output']))
			{
				exec("cat stdout", $output);
				$task->output = $output;
				exec("cat stderr", $output);
				$task->error = $output;
				exec("rm stderr stdout");
			}
		return $task;
		
	}

	public function destroy(){
		$output = array();
		$returnvalue;
		exec("kill -9 ".$this->pid, $output, $returnvalue); // ver esto
		return $returnvalue;

	}

	public function update($data){
		$output = array();
		$returnvalue;
		exec($string = "renice ".$data['priority']." -p ".$this->pid, $output, $returnvalue);
		return $returnvalue;
		
	}

}
