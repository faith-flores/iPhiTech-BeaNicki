<?php

namespace App\Http\Livewire;

use App\Http\Forms\Schema\AccountCompanySchema;
use Closure;
use App\Http\Forms\Schema\ProfileBillingSchema;
use App\Http\Forms\Schema\ProfileSchema;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use Filament\Forms\Components;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\HtmlString;
use Livewire\Component;

class ProfileWizard extends Component implements HasForms
{
    use InteractsWithForms;

    public User $user;

    public $data;

    protected static string $accountType;

    protected static bool $isSkippable = false;

    public function mount(): void
    {
        $account = $this->user->account;
        $profile = $account->profiles()->where('account_id', $account->getKey())->first();

        $this->form->fill($profile ? [
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'phone' => $profile->phone,
            'email' => $account->email,
            'address' => [
                'account_type' => $account->account_type,
                'address_line_1' => $account->address->address_line_1,
                'address_line_2' => $account->address->address_line_2,
                'city' => $account->address->city,
                'street' => $account->address->street,
                'province' => $account->address->province,
                'zip_code' => $account->address->zip_code,
                'address_type' => $account->address->address_type,
            ]
        ] : []);
    }

    protected function getFormSchema(): array
    {
        return [
            Components\Card::make()
                ->schema([
                    Wizard::make([
                        static::getProfileStep(),
                        static::getCompanyStep(),
                        static::getBillingStep(),
                        static::getCompleteStep()
                    ])
                    ->reactive()
                    ->skippable(function() {
                        $account = $this->user->account;
                        return $account->master_profile || false;
                    })
                    ->submitAction(new HtmlString('<button type="submit" class="btn btn-primary">Go to Dashboard</button>'))
                ])
        ];
    }

    protected static function getProfileStep() : Step
    {
        return Wizard\Step::make('Profile')
            ->icon('heroicon-o-identification')
            ->description('Lorem ipsum dolor')
            ->schema([
                Components\Section::make('Profile Details')
                    ->description('Please complete your profile information below.')
                    ->schema([
                        ProfileSchema::make(),
                        Radio::make('account_type')
                            ->options([
                                '0' => 'Personal',
                                '1' => 'Business / Agency'
                            ])
                            ->required()
                            ->reactive(),
                        Checkbox::make('terms')
                            ->label('I accept the BeaNicki Terms of Service and Privacy Policy')
                            ->required(),
                    ])
            ])
            ->afterValidation(function($state, \Filament\Forms\Set $set) {
                app(Profile::class)->editProfile(auth()->user(), $state);
            });
    }

    protected static function getCompanyStep() : Step
    {
        return Wizard\Step::make('Business')
            ->icon('heroicon-o-building-office')
            ->description('Lorem ipsum dolor')
            ->schema([
                Components\Section::make('Business Details')
                    ->description('Please complete your business information below.')
                    ->schema([AccountCompanySchema::make()])
            ])
            ->visible(fn (array $state): ?string => $state['account_type'] == Account::ACCOUNT_TYPE_BUSINESS)
            ->reactive();
    }

    protected static function getBillingStep(): Step
    {
        return Wizard\Step::make('Billing')
                ->icon('heroicon-o-document')
                ->description('Lorem ipsum dolor')
                ->schema([
                    Components\Section::make('Billing Details')
                        ->description('Please complete your billing information below.')
                        ->schema([ProfileBillingSchema::make()])
                ]);
    }

    protected static function getCompleteStep(): Step
    {
        return Wizard\Step::make('Complete')
                ->icon('heroicon-o-flag')
                ->description('You have completed our online registration')
                ->schema([
                    Components\Section::make('Congratulations')
                        ->description('You are now ready to start uploading files for processing to your account. Once uploaded you will be presented with options based on your preferred turnaround as well as any extras you may require. You are able to save your job to your account as a draft at any time, should you wish to come back later and complete your order. You can also use our order process to generate a no obligation quote which is valid for 14 days.')
                ]);
    }

    public function submit()
    {
        if ($this->user->account->master_profile->setProfileCompleted()) {
            $this->redirectRoute('filament.pages.dashboard');
        }
    }

    protected function getFormModel(): User
    {
        return $this->user;
    }

    protected function getFormStatePath(): string
    {
        return 'data';
    }

    public function render()
    {
        return view('livewire.profile-wizard')->extends('layouts.auth');
    }
}
