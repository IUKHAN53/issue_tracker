<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use GrahamCampbell\GitLab\Facades\GitLab;
use GrahamCampbell\GitLab\GitLabManager;
use Livewire\Component;

class IssuesBoard extends Component
{
    public $boards = [];
    public $issues;
    public $tasks;

    public function mount(GitLabManager $gitlab)
    {
        $this->issues = $gitlab->issues()->all();
        $this->getBoards();
        $this->getTasks();
    }

    public function render()
    {
        $issues = $this->issues;
        return view('livewire.issues-board',compact('issues'));
    }

    public function updateTaskLabel($labels)
    {
        foreach ($labels as $label){
            foreach ($label['items'] as $issue){
                $this->updateIssue($issue['value'],$label['value']);
            }
        }
        $this->issues = GitLab::issues()->all();
        $this->getBoards();
        $this->getTasks();
    }
    public function updateIssue($id, $board)
    {
        $issue = $this->findIssue($id, $board);
        GitLab::issues()->update($issue['project_id'], $issue['iid'], ['labels'=>$board]);
    }

    function getBoards()
    {
        $data = collect();
        foreach ($this->issues as $issue) {
            $data->push($issue['labels']);
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
                'project_id' => $issue['project_id'],
                'name' => $issue['title'],
                'status' => $issue['state'],
                'boardName' => implode(', ', $issue['labels']),
                'date' => $issue['created_at'],
            ]);
        }
        $this->tasks = $data;
    }

    function findIssue($id,$update = null)
    {
        $issue = array_filter($this->issues,
            function ($arr) use ($id) {
                return $arr['id'] == $id;
            });
        if($update != null){
            $this->issues[array_key_first($issue)]['labels'] = [$update];
        }
        return reset($issue);
    }
}
