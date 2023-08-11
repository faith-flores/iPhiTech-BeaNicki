<?php

namespace App\Livewire;

use App\Events\UserRegistered;
use App\Http\Forms\Schema\Types\Email;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Component as FormComponent;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Spatie\Permission\Models\Role;

/**
 * TODO: Replace placeholder content
 */
class Register extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected string $model = User::class;

    /** @var string */
    public $email = '';

    /** @var string */
    public $password = '';

    /** @var string */
    public $passwordConfirmation = '';

    /** @var string */
    private $role = '';

    public function register($data)
    {
        $role = Role::findByName($this->getRole());

        $user = User::create([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if ($role) {
            $user->syncRoles($role);
        } else {
            $this->addError('register', 'There wrong process.');
        }

        event(new UserRegistered($user, $role));

        Auth::login($user, true);

        return $user;
    }

    public function registerEmployerAction()
    {
        $this->setRole(User::USER_ROLE_EMPLOYER);

        return $this->registerAction('registerEmployerAction', User::USER_ROLE_EMPLOYER)
            ->modalContent(new HtmlString('
                <h3 class="text-xl">Create Employer Account</h3>
                <p class="text-sm">Dolores numquam cumque laudantium quia nihil. Doloremque ut consectetur est asperiores.</p>
            '));
    }

    public function registerJobseekerAction()
    {
        $this->setRole(User::USER_ROLE_JOBSEEKER);

        return $this->registerAction('registerJobseekerAction', User::USER_ROLE_JOBSEEKER)
            ->modalContent(new HtmlString('
                <h3 class="text-xl">Create Jobseeker Account</h3>
                <p class="text-sm">Dolores numquam cumque laudantium quia nihil. Doloremque ut consectetur est asperiores.</p>
            '));
    }

    protected function registerAction($action, $accountType)
    {
        return CreateAction::make($action)
                ->model(User::class)
                ->label('Create a '. $accountType . ' account')
                ->modalHeading('Account')
                ->form([
                    TextInput::make('name')->required(),
                    Email::make()->unique('users'),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ])
                ->modalFooterActionsAlignment(Alignment::Center)
                ->modalSubmitActionLabel('Register')
                ->stickyModalFooter(false)
                ->modalWidth('md')
                ->slideOver()
                ->using(function (array $data): Model {
                    $user = $this->register($data);
                    return $user;
                })
                ->successNotification(
                    /**
                     * TODO: Change success message
                     */
                    Notification::make()
                        ->success()
                        ->title('User registered')
                        ->body('The user has been created successfully.')->send(),
                )
            ;
    }

    protected function getPasswordFormComponent(): FormComponent
    {
        return TextInput::make('password')
            ->label(__('Password'))
            ->password()
            ->required()
            ->dehydrated(fn ($state): bool => filled($state))
            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
            ->live()
            ->same('passwordConfirmation');
    }

    protected function getPasswordConfirmationFormComponent(): FormComponent
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('Confirm password'))
            ->password()
            ->required()
            ->dehydrated(false);
    }

    protected function getRole()
    {
        return $this->role;
    }

    protected function setRole($role = "")
    {
        $this->role = $role;
    }

    public function render()
    {
        return view('livewire.auth.register', ['class' => 'test'])->extends('layouts.auth');
    }
}
