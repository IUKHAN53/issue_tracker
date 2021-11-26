<?php

namespace App\Http\Livewire;

use Gitlab\Client;
use Livewire\Component;

class IssuesBoard extends Component
{
    public $boards = [];
    public $issues;
    public $tasks;
    public $invalid_token = false;

    public function mount()
    {
//        $this->issues = [];
        try {
            $this->issues = getClient()->issues()->all();
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                $this->invalid_token = true;
            } else {
                dd($e);
            }
        }
        if (!$this->invalid_token) {
            $this->getBoards();
            $this->tasks = getTasks($this->issues);
        }
    }

    public function render()
    {
        return view('livewire.issues-board');
    }

    public function updateTaskLabel($labels)
    {
        foreach ($labels as $label) {
            foreach ($label['items'] as $issue) {
                $this->updateIssue($issue['value'], $label['value']);
            }
        }
        $this->mount();
    }

    public function updateIssue($id, $board)
    {
        $issue = $this->findIssue($id, $board);
        getClient()->issues()->update($issue['project_id'], $issue['iid'], ['labels' => $board]);
    }

    function getBoards()
    {
        $data = collect();
        $flag = false;
        foreach ($this->issues as $issue) {
            if (count($issue['labels']) > 0) {
                $data->push($issue['labels']);
            } else if (!$flag) {
                $data->push(array('No label'));
                $flag = true;
            }
        }

        $this->boards = $data->flatten()->unique()->toArray();
    }

    function findIssue($id, $update = null)
    {
        $issue = array_filter($this->issues,
            function ($arr) use ($id) {
                return $arr['id'] == $id;
            });
        if ($update != null) {
            $this->issues[array_key_first($issue)]['labels'] = [$update];
        }
        return reset($issue);
    }

}
