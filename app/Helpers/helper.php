<?php

use Gitlab\Client;


if(!function_exists('getClient')){
    function getClient(): Client
    {
//        glpat-z4gVarUesDCqxum1JsWC
        $client = new Client();
        if (auth()->check()) {
            $client->authenticate((auth()->user()->personal_access_token == '') ? env('GITLAB_PERSONAL_ACCESS_TOKEN') : auth()->user()->personal_access_token, Client::AUTH_HTTP_TOKEN);
        } else {
            $client->authenticate(env('GITLAB_PERSONAL_ACCESS_TOKEN'), Client::AUTH_HTTP_TOKEN);
        }
//        $client->authenticate('yLsnGUR6ixAmGym2xFNs', Client::AUTH_HTTP_TOKEN);
//        $client->setUrl('https://gitlab.webvisum.de');
        return $client;
    }
}

if(!function_exists('getTasks')){
    function getTasks($issues): \Illuminate\Support\Collection
    {
        $data = collect();
        foreach ($issues as $issue) {
            $data->push([
                'id' => $issue['id'],
                'iid' => $issue['iid'],
                'project' => getClient()->projects()->show($issue['project_id'])['name'],
                'name' => $issue['title'],
                'description' => $issue['description'],
                'status' => $issue['state'],
                'boardName' => count($issue['labels']) > 0 ? implode(', ', $issue['labels']) : 'No label',
                'date' => $issue['created_at'],
                'updated' => $issue['updated_at'],
            ]);
        }
        return $data;
    }
}
