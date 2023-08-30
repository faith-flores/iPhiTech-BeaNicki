<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Services\AccountResourceService;
use App\Filament\Services\ProfileResourceService;
use App\Http\Forms\Schema\AccountCompanySchema;
use App\Http\Forms\Schema\AddressSchema;
use App\Http\Forms\Schema\JobseekerProfileWizardSchema;
use App\Http\Forms\Schema\ProfileBillingSchema;
use App\Http\Forms\Schema\ProfileSchema;
use App\Http\Forms\Schema\Types\AccountType;
use App\Http\Forms\Schema\Types\InvoiceName;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;

class ProfileWizard extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = UserResource::class;

    protected static ?string $title = "Complete Your Profile";

    protected static string $view = 'filament.employer.resources.user-resource.pages.profile-wizard';

    protected static bool $test = false;

    public ?array $data = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);

        abort_unless(auth()->user()->can('edit', $this->record), 403);

        $account = $this->record->account;
        $account->load(['address']);

        $data = [
            'master_profile' => $account->master_profile ? [
                ...$account?->master_profile->toArray(),
                ...['email' => $account->email]
            ] : null,
            'account' => $account,
            'billing' => $account?->master_profile->billing ?? []
        ];

        $this->form->fill([...$account->toArray(), ...$data]);
    }

    public function form(Form $form): Form
    {
        return $this->employerForm($form);
    }

    public function employerForm(Form $form)
    {
        return $form
        ->schema([
            Wizard::make([
                self::getProfileStep(),
                self::getCompanyStep(),
                self::getBillingStep(),
                self::getCompleteStep()
            ])
            ->submitAction(new HtmlString('<button type="submit" class="btn btn-primary">Go to Dashboard</button>'))
        ])
        ->statePath('data');
    }

    public function submit()
    {
        return redirect()->route('filament.app.pages.dashboard');
    }

    protected function getProfileStep() : Step
    {
        return Wizard\Step::make('Profile')
            ->icon('heroicon-o-identification')
            ->schema([
                    Section::make('Profile Details')
                        ->description('Please complete your profile information below.')
                        ->schema([
                            ProfileSchema::make()
                                ->relationship('master_profile')
                                ->model(Account::class),
                            AccountType::make()
                                ->visible($this->record->hasRole(User::USER_ROLE_EMPLOYER))
                                ->afterStateUpdated(fn (Set $set) => $set('same_company_address', false)),
                            Checkbox::make('terms')
                                ->label('I accept the BeaNicki Terms of Service and Privacy Policy')
                                ->required(),
                        ])->model(Profile::class)
            ])->afterValidation(function($state) {
                app(ProfileResourceService::class)->editProfile($state, $this->record);
            });
    }

    protected function getCompanyStep() : Step
    {
        return Step::make('Business')
            ->icon('heroicon-o-building-office')
            ->schema([
                Section::make('Business Details')
                    ->description('Please complete your business information below.')
                    ->schema([AccountCompanySchema::make()])
            ])
            ->visible(fn (Get $get): bool => $get('account_type') == Account::ACCOUNT_TYPE_BUSINESS)
            ->afterValidation(function($state) {
                app(AccountResourceService::class)->editBusinessDetails($state['account'], $this->record->account);
            });
    }

    protected function getBillingStep(): Step
    {
        return Wizard\Step::make('Billing')
                ->icon('heroicon-o-document')
                ->schema([
                    Section::make('Billing Details')
                        ->description('Please complete your billing information below.')
                        ->schema([
                            Checkbox::make('same_company_address')
                                ->label('Same as company address')
                                ->visible(fn (Get $get): bool => $get('account_type') == Account::ACCOUNT_TYPE_BUSINESS)
                                ->live(),
                            InvoiceName::make()->visible(fn (Get $get) : bool => $get('same_company_address') ? true : false),
                            Group::make()
                                ->schema([ProfileBillingSchema::make()])
                                ->relationship('billing')
                                ->model(Profile::class)
                                ->visible(fn (Get $get) : bool => $get('same_company_address') ? false : true)
                        ])
                ])
                ->afterValidation(function($state) {
                    app(ProfileResourceService::class)->editBillingDetails($state, $this->record->account);
                });
    }

    /**
     * TODO: Need to add more details
     */
    protected function getCompleteStep(): Step
    {
        return Wizard\Step::make('Complete')
                ->icon('heroicon-o-flag')
                ->description('You have completed our online registration')
                ->schema([
                    Section::make('Congratulations')
                        ->description('You are now ready to start uploading files for processing to your account. Once uploaded you will be presented with options based on your preferred turnaround as well as any extras you may require. You are able to save your job to your account as a draft at any time, should you wish to come back later and complete your order. You can also use our order process to generate a no obligation quote which is valid for 14 days.')
                ]);
    }

    protected function getFormModel(): User
    {
        return $this->user;
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }
}
