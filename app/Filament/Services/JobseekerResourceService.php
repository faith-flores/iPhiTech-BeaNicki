<?php

namespace App\Filament\Services;

use App\Models\Jobseeker;
use App\Models\Services\ModelService;
use App\Models\User;
use Exception;
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
            if($user = User::query()->find($user_id)) {
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
     *
     * @return bool|Model
     */
    public function update($data, Jobseeker $jobseeker)
    {
        $jobseeker = $this->getModel($jobseeker);

        $jobseeker->fill($data);

        $jobseeker = $this->fillRelations($jobseeker, $data);

        if ($jobseeker->save()) {
            return $jobseeker;
        }

        return false;
    }

    public function fillRelations($jobseeker, $data)
    {
        if (Arr::exists($data, 'address')) {
            if ($address = Arr::pull($data, 'address')) {
                $jobseeker->address()->updateOrCreate([], $address);
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
        $jobseeker = $this->getModel($jobseeker);

        if ($this->update($data, $jobseeker)) {
            if ($jobseeker->setProfileCompleted()) {
                return $jobseeker;
            }
        }

        return false;
    }
}
