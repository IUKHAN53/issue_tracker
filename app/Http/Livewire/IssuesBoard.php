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
            $this->issues = $this->getClient()->issues()->all();
        } catch (\Exception $e) {
            if ($e->getCode() == 401) {
                $this->invalid_token = true;
            } else {
                dd($e);
            }
        }
        if (!$this->invalid_token) {
            $this->getBoards();
            $this->getTasks();
        }
    }

    public function render()
    {
        $issues = $this->issues;
        return view('livewire.issues-board', compact('issues'));
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
        $this->getClient()->issues()->update($issue['project_id'], $issue['iid'], ['labels' => $board]);
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

    function getTasks()
    {
        $data = collect();
        foreach ($this->issues as $issue) {
            $data->push([
                'id' => $issue['id'],
                'iid' => $issue['iid'],
                'project' => $this->getClient()->projects()->show($issue['project_id'])['name'],
                'name' => $issue['title'],
                'status' => $issue['state'],
                'boardName' => count($issue['labels']) > 0 ? implode(', ', $issue['labels']) : 'No label',
                'date' => $issue['created_at'],
            ]);
        }
        $this->tasks = $data;
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

    public function getClient()
    {
//        glpat-z4gVarUesDCqxum1JsWC
        $client = new Client();
        if (auth()->check()) {
            $client->authenticate((auth()->user()->personal_access_token == '') ? env('GITLAB_PERSONAL_ACCESS_TOKEN') : auth()->user()->personal_access_token, Client::AUTH_HTTP_TOKEN);
        } else {
            $client->authenticate(env('GITLAB_PERSONAL_ACCESS_TOKEN'), Client::AUTH_HTTP_TOKEN);
        }
//
//        $client->authenticate('yLsnGUR6ixAmGym2xFNs', Client::AUTH_HTTP_TOKEN);
//        $client->setUrl('http://gitlab.webvisum.de');
        return $client;
    }
}
