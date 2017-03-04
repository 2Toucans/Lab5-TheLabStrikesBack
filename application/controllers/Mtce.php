<?php

class Mtce extends Application {
    private $items_per_page = 10;
    public function index()
    {
        $this->page(1);
    }
        
    private function show_page($tasks)
    {
        $this->data['pagetitle'] = 'TODO List Maintenance';
        // substitute the status name
        foreach ($tasks as $task)
            if (!empty($task->status))
                $task->status = $this->statuses->get($task->status)->name;

        // build the task presentation output
        $result = '';   // start with an empty array        
        foreach ($tasks as $task)
            $result .= $this->parser->parse('oneitem',(array)$task,true);

        // and then pass them on
        $this->data['display_tasks'] = $result;
        $this->data['pagebody'] = 'itemlist';
        $this->render();
    }
    
    function page($num = 1)
    {
        $records = $this->tasks->all(); // get all the tasks
        $tasks = array(); // start with an empty extract

        // use a foreach loop, because the record indices may not be sequential
        $index = 0; // where are we in the tasks list
        $count = 0; // how many items have we added to the extract
        $start = ($num - 1) * $this->items_per_page;
        foreach($records as $task) {
            if ($index++ >= $start) {
                $tasks[] = $task;
                $count++;
            }
            if ($count >= $this->items_per_page) break;
        }
        $this->show_page($tasks);
    }
}