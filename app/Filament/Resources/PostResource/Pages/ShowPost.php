<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Models\Post;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;

class ShowPost extends Page
{
    protected static string $resource = PostResource::class;

    protected static string $view = 'filament.resources.post-resource.pages.show-post';

    public function getData(): ?Object{

        $id = request()->segment(4);

        $result = Post::whereSlug($id)->first();

        return $result;

    }

    protected function getHeader(): ?View
    {
        $obj = $this->getData();
        $data['title'] = $obj->title;
        return view('filament.resources.post-resource.pages.header-post', $data);
    }
}
