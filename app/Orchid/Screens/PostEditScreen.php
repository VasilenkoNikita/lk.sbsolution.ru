<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\View\Components\Tags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class PostEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Создать новую новость';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Новости компании';

    /**
     * @var bool
     */
    public $exists = false;

    /**
     * Tag list.
     *
     * @var array
     */
    public $taglist = [];

    /**
     * Query data.
     *
     * @param Post $post
     *
     * @return array
     */
    public function query(Post $post): array
    {
        $this->exists = $post->exists;

        if($this->exists){
            $this->name = 'Редактировать новость';
        }

		$post->load('attachment');



//dd($tags = Post::with('tags'));

        $tagsPipe = [];
        if($this->exists) {
            $tags = Post::with('tags')->where('id', $post->id)->get();
            foreach ($tags[0]['tags'] as $tag) {
                $tagsPipe[] = $tag->name;
            }
        }else{
            $tags = Tag::where('type', 'news')->get();
        }


        return [
            'post' => $post,
            'tags' => $tags,
            'tagsList' => implode(", ", $tagsPipe),

        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Создать новость')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('post.title')
                    ->title('Заголовок')
                    ->placeholder('Новость дня')
                    ->help('Напишите заголовок для новости'),

                TextArea::make('post.description')
                    ->title('Описание новости')
                    ->rows(3)
                    ->maxlength(200)
                    ->placeholder('Краткое описание новости'),

                Relation::make('post.author')
                    ->title('Автор')
                    ->fromModel(User::class, 'name'),

                Quill::make('post.body')
                    ->title('Текст новости'),

                TextArea::make('tagsList')
                    ->title('Теги')
                    ->help('Теги указываются через запятую'),

				Upload::make('post.attachment')
					->title('Все файлы')
            ]),
            Layout::component(Tags::class),
        ];
    }

    /**
     * @param Post    $post
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Post $post, Request $request): \Illuminate\Http\RedirectResponse
    {
        $post->fill($request->get('post'))->save();

		$post->attachment()->syncWithoutDetaching(
			$request->input('post.attachment', [])
		);

        if (strpos($request->input('tagsList'), ',')) {
            $tags = explode(", ", $request->input('tagsList'));
            $post->syncTagsWithType($tags, 'resource');
        }else{
            $post->syncTagsWithType([$request->input('tagsList')], 'news');
        }

        Alert::info('Вы успешно создали новость!');

        return redirect()->route('platform.posts.list');
    }

    /**
     * @param Post $post
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Post $post): \Illuminate\Http\RedirectResponse
    {
        $post->delete()
            ? Alert::info('Вы успешно удалили новость!.')
            : Alert::warning('Упс. Ошибка')
        ;

        return redirect()->route('platform.posts.list');
    }
}
