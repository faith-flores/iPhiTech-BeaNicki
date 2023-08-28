<?php

namespace Database\Seeders\Production;

use App\Filament\Services\JobseekerResourceService;
use App\Filament\Services\PicklistItemResourceService;
use App\Filament\Services\SkillResourceService;
use App\Models\Jobseeker;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class OldDataSeeder extends Seeder
{
    /**
     * @var JobseekerResourceService $service
     */
    private JobseekerResourceService $jobseekerService;

    /**
     * @var PicklistItemResourceService $picklistService
     */
    private PicklistItemResourceService $picklistService;

    public function __construct(
        JobseekerResourceService $jobseekerService,
        PicklistItemResourceService $picklistService
    )
    {
        $this->jobseekerService = $jobseekerService;
        $this->picklistService = $picklistService;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_path = base_path('database/data/old_users.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $users = json_decode($data, true);

            $role = Role::query()->where("name", "=", 'jobseeker')->first();

            app(SkillResourceService::class)->clearCachedSelectableList('web_development');

            foreach ($users as $key => $userData) {
                Log::debug('USER_LOOP === Start for ' . $userData['email']);

                // create user
                $user = $this->createUser($userData);

                if ($user) {
                    $user->syncRoles([$role]);

                    // create jobseeker profile
                    $jobseeker = $this->createJobseeker($user, $userData);

                    // create jobseeker address, add picklist data e.g gender, desired_salary and etc.
                    $jobseeker = $this->fillJobseekerData($jobseeker, $userData);

                    // create jobseeker skills
                    $jobseeker = $this->createJobseekerSkills($jobseeker, $userData['id']);
                } else {
                    Log::debug('FAILED_USER === Unable to create user for ' . $userData['email']);
                }

                Log::debug('USER_LOOP === End for ' . $userData['email']);
            }
        }
    }

    /**
     * @param array $data
     *
     * @return User|Model|bool
     */
    private function createUser($data) : bool|User
    {
        $user = User::query()->where('email', '=', $data['email'])->first();

        if (! $user) {
            $user = User::create([
                'name' => "{$data['first_name']} {$data['last_name']}",
                'email' => $data['email'],
                'password' => $data['password']
            ]);
        }

        $user->email_verified_at = $data['email_verified_at'] ?? now();
        $user->terms = true;
        $user->remember_token = $data['remember_token'];
        $user->created_at = $data['created_at'];
        $user->updated_at = $data['updated_at'];

        if ($user->save()) {
            return $user;
        }

        return false;
    }

    /**
     * @param User $user
     * @param array $data
     *
     * @return Model
     */
    private function createJobseeker(User $user, $data)
    {
        $jobseeker = Jobseeker::query()->where('user_id', '=', $user->getKey())->first();

        $data = [
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'first_name' => $data['first_name'],
            'last_name' => $data['first_name'],
            'nickname' => $data['first_name'],
            'date_of_birth' => $data['birthdate'],
            'job_title' => $data['biography_title'],
            'skills_summary' => $data['biography_description'],
        ];

        if (empty($jobseeker)) {
            $data['user_id'] = $user->getKey();
            $jobseeker = $this->jobseekerService->add($data);
        } else {
            $jobseeker = $this->jobseekerService->update($data, $jobseeker);
        }

        return $jobseeker;
    }

    private function fillJobseekerData(Jobseeker $jobseeker, $oldUserData) : bool|Jobseeker
    {
        $data = [];

        $data['address'] = [
            'address_line_1' => $oldUserData['address_1'],
            'address_line_2' => $oldUserData['address_2'],
            'street' => $oldUserData['street'],
            'city' => $oldUserData['city'],
            'province' => $oldUserData['province'],
            'zip_code' => $oldUserData['zip_code'],
        ];

        if ($oldUserData['gender']) {
            $data['gender_id'] = picklist_item('gender', $oldUserData['gender'], 'id');
        }

        Log::debug('JOBSEEKER_ADDRESS === start ===');

        $jobseeker = $this->jobseekerService->update($data, $jobseeker);

        Log::debug('JOBSEEKER_ADDRESS === end ===');

        return $jobseeker;
    }

    private function createJobseekerSkills(Jobseeker $jobseeker, $old_user_id) : bool|Jobseeker
    {
        $skills = $this->getSkills();

        // jobseeker skills
        $userSkills = $this->filterUserSkillsData($old_user_id);

        if ($userSkills) {
            $jobseekerSkills = [];

            foreach ($userSkills as $key => $userSkill) {
                $rating = $userSkill['rating'];
                $skill = $skills->where('id', '=', $userSkill['skill_id'])->first();

                if ($skill) {
                    $skill_item_id = skill_item('web_development', $skill['tag'], 'id');

                    if ($skill_item_id) {
                        $jobseekerSkills[] = [
                            'skill_item_id' => skill_item('web_development', $skill['tag'], 'id'),
                            'rating' => $rating
                        ];
                    } else {
                        Log::debug('FAILED_SKILL === Unable to find skill for ' . $skill['tag']);
                    }
                }
            }

            if (count($jobseekerSkills) > 0) {
                $jobseeker = $this->jobseekerService->update(['skills' => $jobseekerSkills], $jobseeker);
            }
        }

        return $jobseeker;
    }

    private function filterUserSkillsData($user_id)
    {
        $file_path = base_path('database/data/old_user_skills.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $skills = json_decode($data, true);

            $filtered = collect($skills)->where('user_id', $user_id)->all();

            return $filtered;
        }

        return false;
    }

    private function getSkills()
    {
        $file_path = base_path('database/data/old_skills.json');

        if (file_exists($file_path)) {
            $data = File::get($file_path);
            $skills = json_decode($data, true);

            return collect($skills);
        }

        return false;
    }
}
