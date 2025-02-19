<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\UserCreateLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserFiltersLayout;
use App\Orchid\Layouts\User\UserListLayout;
use Illuminate\Http\Request;
use Orchid\Platform\Models\User;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Support\Facades\Dashboard;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'User';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All registered users';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => User::with('roles')
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function commandBar(): array
    {
        return [
            ModalToggle::make('Создать пользователя')
                ->icon('lock-open')
                ->method('createUser')
                ->modal('createUserModal'),
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
            UserFiltersLayout::class,
            UserListLayout::class,

            Layout::modal('createUserModal', [
                UserCreateLayout::class,
            ])->async('asyncGetUser'),

            Layout::modal('oneAsyncModal', [
                UserEditLayout::class,
            ])->async('asyncGetUser'),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User    $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(User $user, Request $request)
    {
        $request->validate([
            'user.email' => 'required|unique:users,email,'.$user->id,
        ]);

        $user->fill($request->get('user'))
            ->replaceRoles($request->input('user.roles'))
            ->save();

        Toast::info(__('User was saved.'));

        return back();
    }

    public function createUser(User $user, Request $request)
    {

        $request->validate([
            'user.email' => 'required|unique:users,email,'.$user->id,
        ]);

        $newUser = Dashboard::modelClass(User::class)
            ->createAdmin(
                $request->input('user.name'),
                $request->input('user.email'),
                $request->input('user.password')
            );


        Toast::info('Пользователь создан.');

        return back();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        User::findOrFail($request->get('id'))
            ->delete();

        Toast::info(__('User was removed'));

        return back();
    }
}
