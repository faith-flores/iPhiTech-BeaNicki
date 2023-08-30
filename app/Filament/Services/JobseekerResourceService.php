<?php

declare(strict_types=1);

namespace App\Filament\Services;

use App\Models\Jobseeker;
use App\Models\PicklistItem;
use App\Models\Services\ModelService;
use App\Models\User;
use Illuminate\Support\Arr;

class JobseekerResourceService extends ModelService
{
    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string|Jobseeker
     */
    public function getClassName()
    {
        return Jobseeker::class;
    }

    /**
     * @param array $data
     *
     * @return bool|Model
     */
    public function add($data) : bool|Jobseeker
    {
        $jobseeker = self::make($data);

        if ($user_id = Arr::get($data, 'user_id')) {
            if ($user = User::query()->find($user_id)) {
                $jobseeker->user()->associate($user);
            }
        }

        if ($jobseeker->save()) {
            return $jobseeker;
        }

        return false;
    }

    /**
     * @param array $data
     */
    public function update($data, Jobseeker $jobseeker) : bool|Jobseeker
    {
        $jobseeker = $this->getModel($jobseeker);

        $jobseeker->fill($data);

        $jobseeker = $this->fillRelations($jobseeker, $data);

        if ($jobseeker->save()) {
            return $jobseeker;
        }

        return false;
    }

    public function fillRelations(Jobseeker $jobseeker, array $data)
    {
        if (Arr::exists($data, 'address')) {
            if ($address = Arr::pull($data, 'address')) {
                $jobseeker->address()->updateOrCreate([], $address);
            }
        }

        if ($gender_id = Arr::get($data, 'gender_id')) {
            if ($gender = PicklistItem::query()->find($gender_id)) {
                $jobseeker->gender()->associate($gender);
            }
        }

        if ($desired_salary_id = Arr::get($data, 'desired_salary_id')) {
            if ($desired_salary = PicklistItem::query()->find($desired_salary_id)) {
                $jobseeker->desired_salary()->associate($desired_salary);
            }
        }

        if ($education_attainment_id = Arr::get($data, 'education_attainment_id')) {
            if ($education_attainment = PicklistItem::query()->find($education_attainment_id)) {
                $jobseeker->education_attainment()->associate($education_attainment);
            }
        }

        if ($employment_status_id = Arr::get($data, 'employment_status_id')) {
            if ($employment_status = PicklistItem::query()->find($employment_status_id)) {
                $jobseeker->employment_status()->associate($employment_status);
            }
        }

        if ($hours_to_work_id = Arr::get($data, 'hours_to_work_id')) {
            if ($hours_to_work = PicklistItem::query()->find($hours_to_work_id)) {
                $jobseeker->hours_to_work()->associate($hours_to_work);
            }
        }

        if (Arr::exists($data, 'skills')) {
            if ($skills = Arr::pull($data, 'skills')) {
                $jobseeker->skills()->sync($skills);
            }
        }

        return $jobseeker;
    }

    public function editProfile($jobseeker, $data)
    {
        if ($this->update($data, $jobseeker)) {
            if ($jobseeker->setProfileCompleted()) {
                return $jobseeker;
            }
        }

        return false;
    }
}
